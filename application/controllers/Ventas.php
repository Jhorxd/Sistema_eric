<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas extends CI_Controller {

public function __construct() {
    // 1. PRIMERO: Inicializar el núcleo de CodeIgniter
    parent::__construct();

    // 2. SEGUNDO: Cargar librerías o modelos
    $this->load->library('session');
    $this->load->model('Inventario_model');

    // 3. TERCERO: Ejecutar tu lógica de validación
    if ($this->session->userdata('pais') != 'peru') {
        $this->session->set_flashdata('error', 'Acceso restringido a Perú');
        redirect(base_url());
    }

    // Verificación de sesión de usuario
    if (!$this->session->userdata('id')) {
        redirect('login');
    }
}

private function normalizar_fecha_hora($fecha_input = null) {
    if (empty($fecha_input)) {
        return date('Y-m-d H:i:s');
    }

    // Soporta date (Y-m-d) y datetime-local (Y-m-dTH:i)
    $fecha_limpia = str_replace('T', ' ', trim($fecha_input));
    $timestamp = strtotime($fecha_limpia);

    if ($timestamp === false) {
        return date('Y-m-d H:i:s');
    }

    // Si llega solo fecha, se completa con hora actual
    if (strlen($fecha_limpia) <= 10) {
        return date('Y-m-d', $timestamp) . ' ' . date('H:i:s');
    }

    return date('Y-m-d H:i:s', $timestamp);
}

    public function nueva_cotizacion() {
        // Tablas actualizadas: clientes_peru y productos_peru
        $data['clientes'] = $this->db->get('clientes_peru')->result();
        $data['productos'] = $this->db->get_where('productos_peru', ['tipo' => 'final'])->result();

        $this->load->view('layouts/header');
        $this->load->view('layouts/sidebar');
        $this->load->view('ventas/cotizacion', $data);
        $this->load->view('layouts/footer');
    }

    public function buscar_cliente_ajax() {
        $dni = $this->input->post('dni');
        $cliente = $this->db->get_where('clientes_peru', ['dni' => $dni])->row();
        echo json_encode($cliente);
    }

public function guardar_cotizacion() {
    $this->load->model('Inventario_model');

    $adelanto = (float)$this->input->post('adelanto');
    $metodo_pago = $this->input->post('metodo_pago');
    $total = (float)$this->input->post('total_final');
    $estado_envio = $this->input->post('estado'); 
    
    // Capturamos la fecha/hora del POST y la normalizamos
    $fecha_con_hora = $this->normalizar_fecha_hora($this->input->post('fecha'));

    $estado_pago = 'Pendiente';
    if ($adelanto >= $total && $total > 0) { 
        $estado_pago = 'Completado'; 
    } elseif ($adelanto > 0) { 
        $estado_pago = 'Parcial'; 
    }

    $this->db->trans_start();

        $data_venta = [
            'fecha'         => $fecha_con_hora, // <--- Fecha con hora exacta
            'dni'           => $this->input->post('dni'),
            'nombre'        => $this->input->post('nombre'),
            'celular'       => $this->input->post('celular'),
            'ubicacion'       => $this->input->post('ubicacion'),
            'total_venta'   => $total,
            'total_pagado'  => $adelanto,
            'estado_pago'   => $estado_pago
        ];
        
        $this->db->insert('ventas_peru', $data_venta);
        $id_venta = $this->db->insert_id();

        // 3. Insertar Detalles
        $productos_ids = $this->input->post('producto_id'); 
        $cantidades    = $this->input->post('cant');        
        $precios       = $this->input->post('precio');      

        if (!empty($productos_ids)) {
            foreach ($productos_ids as $i => $id_p) {
                if (!empty($id_p)) {
                    $cant = (float)$cantidades[$i];
                    $prec = (float)$precios[$i];
                    
                    $this->db->insert('venta_detalles_peru', [
                        'id_venta'        => $id_venta,
                        'id_producto'     => $id_p,
                        'cantidad'        => $cant,
                        'precio_unitario' => $prec,
                        'subtotal'        => ($prec)
                    ]);

                    if ($estado_envio == 'Aprobado') {
                        $this->Inventario_model->registrar_movimiento_peru(
                            $id_p, 
                            $cant, 
                            'Salida', 
                            'Ventas', 
                            $id_venta, 
                            'Venta directa aprobada'
                        );
                    }
                }
            }
        }

        // 4. Registrar el pago inicial
        if ($adelanto > 0) {
            $this->db->insert('venta_pagos_peru', [
                'id_venta'    => $id_venta,
                'monto'       => $adelanto,
                'fecha_pago'  => date('Y-m-d H:i:s'), // Ya lo tenías con hora aquí
                'metodo_pago' => $metodo_pago,
                'nota'        => 'Pago inicial al registrar pedido'
            ]);
        }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        $this->session->set_flashdata('error', 'Error crítico al procesar la venta.');
    } else {
        $this->session->set_flashdata('success', 'Pedido registrado con éxito');
    }

    redirect('ventas/listado');
}

public function registrar_abono_ajax() {
    $id_venta = $this->input->post('id_venta');
    $monto    = (float)$this->input->post('monto');
    $metodo   = $this->input->post('metodo');
    $nota     = $this->input->post('nota');

    $this->db->trans_start();
        // 1. CORREGIDO: 'id_venta' en lugar de 'venta_id'
        $this->db->insert('venta_pagos_peru', [
            'id_venta'    => $id_venta, // Nombre exacto de tu columna en la DB
            'monto'       => $monto,
            'metodo_pago' => $metodo,
            'nota'        => $nota,
            'fecha_pago'  => date('Y-m-d H:i:s')
        ]);

        // 2. Actualizar el total pagado en ventas_peru
        $this->db->set('total_pagado', 'total_pagado + ' . $monto, FALSE);
        $this->db->where('id', $id_venta);
        $this->db->update('ventas_peru');

        // 3. Recalcular estado de pago
        $venta = $this->db->get_where('ventas_peru', ['id' => $id_venta])->row();
        $nuevo_estado = ($venta->total_pagado >= $venta->total_venta) ? 'Completado' : 'Parcial';
        
        $this->db->where('id', $id_venta);
        $this->db->update('ventas_peru', ['estado_pago' => $nuevo_estado]);

    $this->db->trans_complete();

    echo json_encode(['status' => $this->db->trans_status()]);
}

    public function listado() {
    // Traemos las ventas ordenadas por la más reciente
    $this->db->order_by('fecha', 'DESC');
    $data['ventas'] = $this->db->get('ventas_peru')->result();

    $this->load->view('layouts/header');
    $this->load->view('layouts/sidebar');
    $this->load->view('ventas/listado', $data);
    $this->load->view('layouts/footer');
    }

    // Función AJAX para ver el detalle de una venta sin recargar la página
public function ver_detalle_ajax($id_venta) {
    // Hemos cambiado d.color por p.color y d.talla por p.talla
    $this->db->select('p.nombre as producto_nombre, p.color, p.talla, d.cantidad, d.subtotal');
    $this->db->from('venta_detalles_peru d');
    $this->db->join('productos_peru p', 'p.id = d.id_producto');
    $this->db->where('d.id_venta', $id_venta);
    $productos = $this->db->get()->result();

    // Obtener historial de pagos
    $this->db->where('id_venta', $id_venta);
    $this->db->order_by('fecha_pago', 'DESC');
    $pagos = $this->db->get('venta_pagos_peru')->result();

    echo json_encode([
        'productos' => $productos,
        'pagos'     => $pagos
    ]);
}

    public function actualizar_estado_envio() {
        $id = $this->input->post('id');
        $nuevo_estado = $this->input->post('estado');

        $this->db->trans_start(); // Iniciamos transacción para seguridad

            // 1. Actualizar el estado en la tabla de ventas
            $this->db->where('id', $id);
            $this->db->update('ventas_peru', ['estado_envio' => $nuevo_estado]);

            // 2. Si el estado es "Aprobado", descontamos del inventario
            if ($nuevo_estado == 'Aprobado') {
                // Traemos los detalles de esta venta específica
                $detalles = $this->db->get_where('venta_detalles_peru', ['id_venta' => $id])->result();
                
                foreach ($detalles as $item) {
                    // Registramos la salida en el Kardex de Perú
                    $this->Inventario_model->registrar_movimiento_peru(
                        $item->id_producto, 
                        $item->cantidad, 
                        'Salida', 
                        'Ventas', 
                        $id, 
                        'Venta Aprobada - Descuento automático de stock'
                    );
                }
            }

        $this->db->trans_complete();

        echo json_encode(['status' => $this->db->trans_status()]);
    }

    // Para la vista de edición (debes crear el formulario similar a nueva_cotizacion)
    public function editar_venta($id) {
        $data['venta'] = $this->db->get_where('ventas_peru', ['id' => $id])->row();
        $data['detalles'] = $this->db->get_where('venta_detalles_peru', ['id_venta' => $id])->result();
        $data['productos'] = $this->db->get_where('productos_peru', ['tipo' => 'final'])->result();

        $this->load->view('layouts/header');
        $this->load->view('layouts/sidebar');
        $this->load->view('ventas/editar', $data); // Debes clonar la vista de cotización y adaptarla
        $this->load->view('layouts/footer');
    }

    public function actualizar_venta() {
    $id_venta = $this->input->post('id_venta');
    $total = (float)$this->input->post('total_final');
    $fecha_con_hora = $this->normalizar_fecha_hora($this->input->post('fecha'));

    $this->db->trans_start();

    // 1. Actualizar datos de la venta principal
    $data_venta = [
        'fecha'        => $fecha_con_hora,
        'dni'          => $this->input->post('dni'),
        'nombre'       => $this->input->post('nombre'),
        'celular'      => $this->input->post('celular'),
        'ubicacion'      => $this->input->post('ubicacion'),
        'destino'      => $this->input->post('destino'),
        'total_venta'  => $total
    ];
    $this->db->where('id', $id_venta);
    $this->db->update('ventas_peru', $data_venta);

    // 2. ELIMINAR DETALLES ANTERIORES (Para re-insertar los nuevos)
    $this->db->where('id_venta', $id_venta);
    $this->db->delete('venta_detalles_peru');

    // 3. Insertar los nuevos detalles del formulario
    $productos_ids = $this->input->post('producto_id'); 
    $cantidades    = $this->input->post('cant');        
    $precios       = $this->input->post('precio');      

    foreach ($productos_ids as $i => $id_p) {
        if (!empty($id_p)) {
            $cant = (float)$cantidades[$i];
            $prec = (float)$precios[$i];
            $this->db->insert('venta_detalles_peru', [
                'id_venta'        => $id_venta,
                'id_producto'     => $id_p,
                'cantidad'        => $cant,
                'precio_unitario' => $prec,
                'subtotal'        => ($prec)
            ]);
        }
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        $this->session->set_flashdata('error', 'Error al actualizar.');
    } else {
        $this->session->set_flashdata('success', 'Venta actualizada correctamente.');
    }
    redirect('ventas/listado');
}


public function verificar_stock_pedido($id_venta)
{
    // 1. Obtener los detalles del pedido (productos y cantidades solicitadas)
    $detalles = $this->db->select('d.id_producto, d.cantidad, p.nombre, p.stock, p.color, p.talla')
                         ->from('venta_detalles_peru d')
                         ->join('productos_peru p', 'p.id = d.id_producto')
                         ->where('d.id_venta', $id_venta)
                         ->get()
                         ->result();

    $errores = [];

    // 2. Verificar producto por producto
    foreach ($detalles as $item) {
        if ($item->stock < $item->cantidad) {
            $errores[] = "<b>{$item->nombre}</b> (Talla: {$item->talla}): Solicitado {$item->cantidad}, Stock actual: {$item->stock}";
        }
    }

    // 3. Responder al AJAX de SweetAlert
    if (empty($errores)) {
        echo json_encode(['success' => true]);
    } else {
        // Si hay errores, enviamos el listado de productos sin stock
        $mensaje = "Los siguientes productos no tienen stock suficiente:<br><br>" . implode("<br>", $errores);
        echo json_encode([
            'success' => false,
            'mensaje' => $mensaje
        ]);
    }
}
}