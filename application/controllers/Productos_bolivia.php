<?php
class Productos_bolivia extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if ($this->session->userdata('pais') != 'bolivia') {
            $this->session->set_flashdata('error', '❌ Acceso restringido a Bolivia');
            redirect(base_url());
        }
        // Verificación de sesión
        if (!$this->session->userdata('id')) {
            redirect('login');
        }
    }

    // Listado de productos de Bolivia
public function index() {
    // 1. Seleccionamos todo de productos y el nombre del distribuidor
    $this->db->select('pb.*, d.nombre as nombre_distribuidor');
    $this->db->from('productos_bolivia pb');
    
    // 2. Unimos con la tabla distribuidores usando el ID que guardamos
    // Usamos 'left' para que si un producto no tiene distribuidor, igual aparezca en la lista
    $this->db->join('distribuidores_bolivia d', 'd.id = pb.id_distribuidor', 'left');
    
    // 3. Ordenamos por los más recientes
    $this->db->order_by('pb.id', 'DESC');
    
    $query = $this->db->get();
    $data['productos'] = $query->result();

    // 4. Carga de vistas
    $this->load->view('layouts/header');
    $this->load->view('layouts/sidebar');
    $this->load->view('productos/index_bolivia', $data);
    $this->load->view('layouts/footer');
}
    // Vista para el formulario de creación
public function nuevo() {
    // 1. Obtener los distribuidores de la tabla (ajusta el nombre de la tabla si es distinto)
    $data['distribuidores'] = $this->db->get('distribuidores_bolivia')->result();

    $this->load->view('layouts/header');
    $this->load->view('layouts/sidebar');
    
    // 2. Pasar el array $data a la vista para que pueda usar la variable $distribuidores
    $this->load->view('productos/crear_bolivia', $data);
    
    $this->load->view('layouts/footer');
}

    // Proceso de guardado para Bolivia
    public function guardar_bolivia() {
        // 0. Cargar el modelo de inventario
        $this->load->model('Inventario_model');

        $stock_inicial   = (float)$this->input->post('stock');
        $id_distribuidor = $this->input->post('id_distribuidor'); // Capturamos el ID del Select2

        $data = [
            'id_distribuidor' => $id_distribuidor, // NUEVO CAMPO ASOCIADO
            'codigo'          => $this->input->post('codigo'),
            'nombre'          => $this->input->post('nombre'),
            'detalles'       => $this->input->post('detalles'),
            'color'           => $this->input->post('color'),
            'talla'           => $this->input->post('talla'),
            'precio_venta'    => $this->input->post('precio_venta'),
            'created_at'      => date('Y-m-d H:i:s')
        ];

        // Iniciamos transacción para asegurar que no haya datos huérfanos
        $this->db->trans_start();

            // 1. Insertar el producto en la tabla productos_bolivia
            $this->db->insert('productos_bolivia', $data);
            $id_producto = $this->db->insert_id();

            // 2. Registrar el movimiento inicial en el Kardex si hay stock
            if ($stock_inicial > 0) {
                $this->Inventario_model->registrar_movimiento_bolivia(
                    $id_producto, 
                    $stock_inicial, 
                    'Entrada', 
                    'Inventario Inicial', 
                    NULL, 
                    'Carga inicial al crear producto con distribuidor asignado (Bolivia)'
                );
            }

        $this->db->trans_complete();

        // Verificación de la transacción
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Error crítico: No se pudo crear el producto ni el registro de stock.');
        } else {
            $this->session->set_flashdata('success', '¡Producto y distribuidor registrados con éxito en Bolivia!');
        }

        redirect('productos_bolivia');
    }

    public function editar($id) {
        // Buscamos el producto en la tabla de Bolivia
        $this->db->where('id', $id);
        $query = $this->db->get('productos_bolivia');
        $data['producto'] = $query->row();
        $data['distribuidores'] = $this->db->get('distribuidores_bolivia')->result();

        if (!$data['producto']) {
            redirect('productos_bolivia');
        }

        $this->load->view('layouts/header');
        $this->load->view('layouts/sidebar');
        // Recomiendo copiar crear_bolivia.php a editar_bolivia.php o manejarlo en la misma
        $this->load->view('productos/crear_bolivia', $data);
        $this->load->view('layouts/footer');
    }

    // Proceso de actualización para Bolivia
    public function actualizar() {
        $id = $this->input->post('id');
        $id_distribuidor = $this->input->post('id_distribuidor');

        $data = [
            'id_distribuidor' => $id_distribuidor, // NUEVO CAMPO ASOCIADO
            'codigo'       => $this->input->post('codigo'),
            'nombre'       => $this->input->post('nombre'),
            'detalles'    => $this->input->post('detalles'),
            'color'        => $this->input->post('color'),
            'talla'        => $this->input->post('talla'),
            'stock'        => $this->input->post('stock'),
            'precio_venta' => $this->input->post('precio_venta')
        ];

        $this->db->where('id', $id);
        
        if ($this->db->update('productos_bolivia', $data)) {
            $this->session->set_flashdata('success', '¡Producto actualizado correctamente!');
        } else {
            $this->session->set_flashdata('error', 'No se pudieron guardar los cambios.');
        }

        redirect('productos_bolivia');
    }

    public function eliminar($id) {
        $this->db->where('id', $id);
        
        if ($this->db->delete('productos_bolivia')) {
            $this->session->set_flashdata('success', 'Producto eliminado del inventario de Bolivia.');
        } else {
            $this->session->set_flashdata('error', 'Error al eliminar el producto.');
        }
        
        redirect('productos_bolivia');
    }

    public function verificar_codigo() {
    // trim() elimina espacios accidentales al inicio o final
    $codigo = trim($this->input->post('codigo'));
    $id = $this->input->post('id'); 

    if (empty($codigo)) {
        echo json_encode(['existe' => false]);
        return;
    }

    $this->db->where('codigo', $codigo);
    if ($id) {
        $this->db->where('id !=', $id);
    }
    
    $existe = $this->db->get('productos_bolivia')->num_rows();

    echo json_encode(['existe' => ($existe > 0)]);
}

    public function registrar_ingreso() {
        // 1. Cargar el modelo
        $this->load->model('Inventario_model');

        // 2. Capturar datos del formulario
        $id_producto = $this->input->post('id_producto');
        $cantidad    = (float)$this->input->post('cantidad');
        $motivo_base = $this->input->post('motivo'); // Ej: Compra, Reposición
        $observacion = $this->input->post('observacion');

        // Validar cantidad
        if ($cantidad <= 0) {
            $this->session->set_flashdata('error', 'La cantidad debe ser mayor a cero.');
            redirect('productos_bolivia');
        }

        // Unimos el motivo seleccionado con la nota adicional para el campo 'motivo' de la BD
        $detalle_motivo = $motivo_base . ($observacion ? " - " . $observacion : "");

        /**
         * Llamada al modelo siguiendo el orden de parámetros:
         * 1: id_producto
         * 2: cantidad
         * 3: tipo ('Entrada')
         * 4: origen ('Ajuste Manual') <- Debe ser uno de los valores del ENUM
         * 5: referencia_id (NULL para ingresos manuales)
         * 6: motivo (El detalle del movimiento)
         */
        $success = $this->Inventario_model->registrar_movimiento_bolivia(
            $id_producto,
            $cantidad,
            'Entrada',
            'Ajuste Manual', // Valor exacto del ENUM
            NULL,
            $detalle_motivo
        );

        // 3. Respuesta y redirección
        if ($success) {
            $this->session->set_flashdata('success', '¡Stock actualizado correctamente en Bolivia!');
        } else {
            $this->session->set_flashdata('error', 'No se pudo registrar el movimiento. Verifique los valores del ENUM.');
        }

        redirect('productos_bolivia');
    }


        public function registrar_salida() {
    // 1. Cargar el modelo
    $this->load->model('Inventario_model');

    // 2. Capturar datos del formulario
    $id_producto = $this->input->post('id_producto');
    $cantidad    = (float)$this->input->post('cantidad');
    $motivo_base = $this->input->post('motivo'); // Ej: Venta, Merma, Ajuste
    $observacion = $this->input->post('observacion');

    // Validar cantidad mínima
    if ($cantidad <= 0) {
        $this->session->set_flashdata('error', 'La cantidad de salida debe ser mayor a cero.');
        redirect('productos');
    }

    // --- OPCIONAL: VALIDAR STOCK SUFICIENTE ---
    // Si no quieres permitir stock negativo, puedes consultar el stock actual aquí
    $producto = $this->db->get_where('productos_peru', ['id' => $id_producto])->row();
    if ($producto && $producto->stock < $cantidad) {
        $this->session->set_flashdata('error', "Stock insuficiente. Disponible: $producto->stock");
        redirect('productos');
    }
    // ------------------------------------------

    // Unimos el motivo seleccionado con la nota adicional
    $detalle_motivo = $motivo_base . ($observacion ? " - " . $observacion : "");

    /**
     * Llamada al modelo:
     * El parámetro 3 cambia a 'Salida'
     * El parámetro 4 se mantiene como 'Ajuste Manual' (o el valor ENUM que corresponda)
     */
    $success = $this->Inventario_model->registrar_movimiento_bolivia(
        $id_producto,
        $cantidad,
        'Salida',          // CAMBIO: Tipo de movimiento
        'Ajuste Manual',   // Valor exacto del ENUM en tu BD
        NULL,
        $detalle_motivo
    );

    // 3. Respuesta y redirección
    if ($success) {
        $this->session->set_flashdata('success', '¡Salida de stock registrada correctamente!');
    } else {
        $this->session->set_flashdata('error', 'No se pudo registrar la salida. Verifique los valores del ENUM.');
    }

    redirect('productos_bolivia');
}
}