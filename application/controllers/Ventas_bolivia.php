<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas_bolivia extends CI_Controller {
    public function __construct() {
        parent::__construct();
        
        // 🔒 SOLO BOLIVIA
        if ($this->session->userdata('pais') != 'bolivia') {
            $this->session->set_flashdata('error', '❌ Acceso restringido a Bolivia');
            redirect(base_url());
        }
        
        $this->load->model('Inventario_model');
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

        // Si vino solo fecha, se agrega hora actual
        if (strlen($fecha_limpia) <= 10) {
            return date('Y-m-d', $timestamp) . ' ' . date('H:i:s');
        }

        return date('Y-m-d H:i:s', $timestamp);
    }


    public function nueva_cotizacion() {
        $data['distribuidores'] = $this->db->get('distribuidores_bolivia')->result();
        
        // Traemos todos los productos de Bolivia sin el filtro 'tipo'
        $data['productos'] = $this->db->get('productos_bolivia')->result();

        $this->load->view('layouts/header');
        $this->load->view('layouts/sidebar');
        $this->load->view('ventas/cotizacion_bolivia', $data);
        $this->load->view('layouts/footer');
    }

    public function buscar_cliente_ajax() {
        $dni = $this->input->post('dni');
        $cliente = $this->db->get_where('clientes_bolivia', ['dni' => $dni])->row();
        echo json_encode($cliente);
    }

public function guardar_cotizacion() {

    // 0. Cargar el modelo de inventario
    $this->load->model('Inventario_model');

    // 1. Recoger datos principales
    $adelanto          = (float)$this->input->post('adelanto');
    $alfredo           = (float)$this->input->post('alfredo'); 
    $total             = (float)$this->input->post('total_final'); 
    $comision_delivery = (float)$this->input->post('comision_delivery'); 
    
    // FORZAMOS ESTADO APROBADO: Para que el sistema descuente stock "de frente"
    $estado_envio      = 'Aprobado'; 
    
    // CAPTURAR FECHA/HORA desde el formulario y normalizarla
    $fecha_con_hora = $this->normalizar_fecha_hora($this->input->post('fecha'));

    // Determinar estado de pago
    $estado_pago = 'Pendiente';
    if ($adelanto >= $total && $total > 0) { 
        $estado_pago = 'Completado'; 
    } elseif ($adelanto > 0) { 
        $estado_pago = 'Parcial'; 
    }

    $this->db->trans_start(); // INICIO DE TRANSACCIÓN

        // 2. Insertar en tabla ventas_bolivia
        $data_venta = [
            'fecha'             => $fecha_con_hora,
            'nit'               => $this->input->post('nit'),
            'nombre'            => $this->input->post('nombre'),
            'celular'           => $this->input->post('celular'),
            'celular_cliente'   => $this->input->post('celular_cliente'),
            'ubicacion'         => $this->input->post('ubicacion'),
            'tipo_venta'        => $this->input->post('tipo_venta'),
            'destino'           => $this->input->post('destino'),
            'total_venta'       => $total,
            'comision_delivery' => $comision_delivery,
            'total_pagado'      => $adelanto,
            'estado_pago'       => $estado_pago,
            'estado_envio'      => $estado_envio // <--- Guardamos ya aprobado
        ];
        
        $this->db->insert('ventas_bolivia', $data_venta);
        $id_venta = $this->db->insert_id();

        // 3. Insertar Detalles y Descontar Inventario
        $productos_ids = $this->input->post('producto_id'); 
        $cantidades    = $this->input->post('cant');        
        $precios       = $this->input->post('precio');      

        if (!empty($productos_ids)) {
            foreach ($productos_ids as $i => $id_p) {
                if (!empty($id_p)) {
                    $cant = (float)$cantidades[$i];
                    $prec = (float)$precios[$i];
                    
                    // Insertar detalle (Subtotal corregido: precio * cantidad)
                    $this->db->insert('venta_detalles_bolivia', [
                        'id_venta'        => $id_venta,
                        'id_producto'     => $id_p,
                        'cantidad'        => $cant,
                        'precio_unitario' => $prec,
                        'subtotal'        => ($prec * $cant) 
                    ]);

                    // Registrar movimiento de salida en Kardex (Como forzamos 'Aprobado', se ejecuta siempre)
                    $this->Inventario_model->registrar_movimiento_bolivia(
                        $id_p, 
                        $cant, 
                        'Salida', 
                        'Ventas', 
                        $id_venta, 
                        'Venta directa aprobada (Bolivia) - Salida automática'
                    );
                }
            }
        }

        // 4. Lógica de Alfredo y Pago Inicial
        if ($alfredo > 0) {
            // Registrar en pedidos alfredo
            $this->db->insert('pedidos_alfredo', [
                'id_venta'      => $id_venta,
                'cliente'       => $this->input->post('nombre'),
                'celular'       => $this->input->post('celular_cliente'),
                'destino'       => $this->input->post('destino'),
                'total_pedido'  => $total + $alfredo,
                'monto_alfredo' => $alfredo,
                'fecha_pedido'  => $fecha_con_hora
            ]);

            // Registrar el pago de la transferencia en el historial
            $this->db->insert('venta_pagos_bolivia', [
                'id_venta'    => $id_venta,
                'monto'       => $alfredo,
                'fecha_pago'  => date('Y-m-d H:i:s'),
                'metodo_pago' => 'Transferencia Alfredo',
                'nota'        => 'Pago inicial registrado automáticamente'
            ]);
        }

    $this->db->trans_complete(); // FIN TRANSACCIÓN

    if ($this->db->trans_status() === FALSE) {
        $this->session->set_flashdata('error', 'Error crítico al procesar la venta en Bolivia.');
    } else {
        $this->session->set_flashdata('success', 'Venta registrada y stock descontado con éxito.');
    }

    redirect('ventas_bolivia/listado');
}

public function registrar_abono_ajax() {
    $id_venta = $this->input->post('id_venta');
    $monto    = (float)$this->input->post('monto');
    $metodo   = $this->input->post('metodo');
    $nota     = $this->input->post('nota');

    $this->db->trans_start();

        // 1. Insertar el registro del abono
        $this->db->insert('venta_pagos_bolivia', [
            'id_venta'    => $id_venta,
            'monto'       => $monto,
            'metodo_pago' => $metodo,
            'nota'        => $nota,
            'fecha_pago'  => date('Y-m-d H:i:s')
        ]);

        // 2. Actualizar el acumulado pagado en la venta
        $this->db->set('total_pagado', 'total_pagado + ' . $monto, FALSE);
        $this->db->where('id', $id_venta);
        $this->db->update('ventas_bolivia');

        // 3. Obtener datos frescos (Incluimos nombre, celular y destino para Alfredo)
        $venta = $this->db->select('nombre, celular_cliente, destino, total_venta, total_pagado, comision_delivery')
                          ->where('id', $id_venta)
                          ->get('ventas_bolivia')
                          ->row();

        // 4. Cálculo de saldo
        $total_v = round((float)$venta->total_venta, 2);
        $total_p = round((float)$venta->total_pagado, 2);
        $comis_d = round((float)($venta->comision_delivery ?? 0), 2);
        
        $saldo = round($total_v - $comis_d - $total_p, 2);

        // 5. Determinar nuevo estado
        $nuevo_estado = ($saldo <= 0.01) ? 'Completado' : 'Parcial';

        // 6. Actualizar el estado de la venta
        $this->db->where('id', $id_venta);
        $this->db->update('ventas_bolivia', [
            'estado_pago' => $nuevo_estado
        ]);

        // --- NUEVA LÓGICA PARA ALFREDO ---
        // Si el pago se completa en este abono, registramos en pedidos_alfredo
        if ($nuevo_estado === 'Completado') {
            
            // Verificamos si ya existe en alfredo para no duplicar si abonan de más
            $existe = $this->db->where('id_venta', $id_venta)->get('pedidos_alfredo')->num_rows();
            
            if ($existe == 0) {
                // El monto de alfredo en este contexto parece ser el total de la venta 
                // o lo que definas como su comisión/pago. Aquí uso el total_v como guía.
                $this->db->insert('pedidos_alfredo', [
                    'id_venta'      => $id_venta,
                    'cliente'       => $venta->nombre,
                    'celular'       => $venta->celular_cliente,
                    'destino'       => $venta->destino,
                    'total_pedido'  => $total_v, 
                    'monto_alfredo' => $total_v, // Ajusta este valor según tu regla de negocio
                    'fecha_pedido'  => date('Y-m-d H:i:s')
                ]);
            }
        }
        // ---------------------------------

    $this->db->trans_complete();

    echo json_encode([
        'status'       => $this->db->trans_status(),
        'saldo'        => $saldo,
        'estado'       => $nuevo_estado,
        'total_pagado' => $total_p
    ]);
}

    public function listado() {
    // Traemos las ventas ordenadas por la más reciente
    $this->db->order_by('fecha', 'DESC');
    $data['ventas'] = $this->db->get('ventas_bolivia')->result();

    $this->load->view('layouts/header');
    $this->load->view('layouts/sidebar');
    $this->load->view('ventas/listado_bolivia', $data);
    $this->load->view('layouts/footer');
    }

    // Función AJAX para ver el detalle de una venta sin recargar la página
public function ver_detalle_ajax($id_venta) {
    // Hemos cambiado d.color por p.color y d.talla por p.talla
    $this->db->select('p.nombre as producto_nombre, p.color, p.talla, d.cantidad, d.subtotal');
    $this->db->from('venta_detalles_bolivia d');
    $this->db->join('productos_bolivia p', 'p.id = d.id_producto');
    $this->db->where('d.id_venta', $id_venta);
    $productos = $this->db->get()->result();

    // Obtener historial de pagos
    $this->db->where('id_venta', $id_venta);
    $this->db->order_by('fecha_pago', 'DESC');
    $pagos = $this->db->get('venta_pagos_bolivia')->result();

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
            $this->db->update('ventas_bolivia', ['estado_envio' => $nuevo_estado]);

            // 2. Si el estado es "Aprobado", descontamos del inventario
            if ($nuevo_estado == 'Aprobado') {
                // Traemos los detalles de esta venta específica
                $detalles = $this->db->get_where('venta_detalles_bolivia', ['id_venta' => $id])->result();
                
                foreach ($detalles as $item) {
                    // Registramos la salida en el Kardex de Bolivia
                    $this->Inventario_model->registrar_movimiento_bolivia(
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
    // 1. Traemos la venta por su ID
    $venta = $this->db->get_where('ventas_bolivia', ['id' => $id])->row();
    $data['venta'] = $venta;

    // 2. Buscamos al distribuidor usando el NIT que guardó la venta
    // Esto es necesario para obtener el 'id' real del distribuidor
    $distribuidor = $this->db->get_where('distribuidores_bolivia', ['nit' => $venta->nit])->row();

    // 3. Traemos los productos filtrados por el ID del distribuidor encontrado
    if ($distribuidor) {
        $data['productos'] = $this->db->get_where('productos_bolivia', [
            'id_distribuidor' => $distribuidor->id 
        ])->result();
    } else {
        // Si por alguna razón no existe el distribuidor, traemos vacío para evitar errores
        $data['productos'] = [];
    }

    // 4. Traemos el detalle de la venta
    $data['detalles'] = $this->db->get_where('venta_detalles_bolivia', ['id_venta' => $id])->result();

    $this->load->view('layouts/header');
    $this->load->view('layouts/sidebar');
    $this->load->view('ventas/editar_bolivia', $data);
    $this->load->view('layouts/footer');
}

    public function actualizar_venta() {
    $id_venta = $this->input->post('id_venta');
    $total = (float)$this->input->post('total_final');

    $this->db->trans_start();

    // 1. Actualizar datos de la venta principal
    $data_venta = [
        'fecha'        => $this->normalizar_fecha_hora($this->input->post('fecha')),
        'nit'          => $this->input->post('nit'),
        'nombre'       => $this->input->post('nombre'),
        'celular'      => $this->input->post('celular'),
        'tipo_venta'   => $this->input->post('tipo_venta'),
        'destino'      => $this->input->post('destino'),
        'comision_delivery'      => $this->input->post('comision_delivery'),
        'total_venta'  => $total
    ];
    $this->db->where('id', $id_venta);
    $this->db->update('ventas_bolivia', $data_venta);

    // 2. ELIMINAR DETALLES ANTERIORES (Para re-insertar los nuevos)
    $this->db->where('id_venta', $id_venta);
    $this->db->delete('venta_detalles_bolivia');

    // 3. Insertar los nuevos detalles del formulario
    $productos_ids = $this->input->post('producto_id'); 
    $cantidades    = $this->input->post('cant');        
    $precios       = $this->input->post('precio');      

    foreach ($productos_ids as $i => $id_p) {
        if (!empty($id_p)) {
            $cant = (float)$cantidades[$i];
            $prec = (float)$precios[$i];
            $this->db->insert('venta_detalles_bolivia', [
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
    redirect('ventas_bolivia/listado');
}


public function verificar_stock_pedido($id_venta)
{
    $detalles = $this->db->select('d.id_producto, d.cantidad, p.nombre, p.stock, p.color, p.talla')
                         ->from('venta_detalles_bolivia d')
                         ->join('productos_bolivia p', 'p.id = d.id_producto')
                         ->where('d.id_venta', $id_venta)
                         ->get()
                         ->result();

    $errores = [];

    foreach ($detalles as $item) {
        if ($item->stock < $item->cantidad) {
            // Formato exacto: NOMBRE (Talla: X): Solicitado X, Stock actual: X
            $errores[] = "<b>{$item->nombre}</b> (Talla: {$item->talla}): Solicitado {$item->cantidad}, Stock actual: " . number_format($item->stock, 2);
        }
    }

    if (empty($errores)) {
        echo json_encode(['success' => true]);
    } else {
        // Unimos los errores con un salto de línea simple
        $mensaje = "Los siguientes productos no tienen stock suficiente:<br><br>" . implode("<br>", $errores);
        echo json_encode([
            'success' => false,
            'mensaje' => $mensaje
        ]);
    }
}
}