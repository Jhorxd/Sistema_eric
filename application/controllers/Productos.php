<?php
class Productos extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('session');

        if ($this->session->userdata('pais') != 'peru') {
            $this->session->set_flashdata('error', 'Acceso restringido a Perú');
            redirect(base_url());
        }
        // Verificación de sesión (opcional pero recomendada)
        if (!$this->session->userdata('id')) {
            redirect('login');
        }
    }

    // Listado de productos de Perú
    public function index() {
        // Obtenemos todos los registros de la tabla productos_peru
        $query = $this->db->get('productos_peru');
        $data['productos'] = $query->result();

        // Cargamos la vista del listado
        $this->load->view('layouts/header');
        $this->load->view('layouts/sidebar'); // Si usas un layout
        $this->load->view('productos/index_peru', $data);
        $this->load->view('layouts/footer');
    }

    // Vista para el formulario de creación
    public function nuevo() {
        $this->load->view('layouts/header');
        $this->load->view('layouts/sidebar'); // Si usas un layout
        $this->load->view('productos/crear_peru');
        $this->load->view('layouts/footer');
    }

    // Proceso de guardado
public function guardar_peru() {
    $this->load->model('Inventario_model');

    $tipo = $this->input->post('tipo');
    $codigo = $this->input->post('codigo');
    $stock_inicial = (float)$this->input->post('stock');
    $fecha_actual = date('Y-m-d H:i:s');

    $data = [
        'codigo'       => $codigo . '-C',
        'nombre'       => $this->input->post('nombre'),
        'tipo'         => $tipo,
        'detalles'    => $this->input->post('detalles'),
        'color'        => $this->input->post('color'),
        'talla'        => $this->input->post('talla'),
        'tipo_tela'    => $this->input->post('tipo_tela'),
        'precio_venta' => $this->input->post('precio_venta'),
        'created_at'   => $fecha_actual
    ];

    $this->db->trans_start();

        // 1. Crear el producto principal (sea Corte o Producto Normal)
        $this->db->insert('productos_peru', $data);
        $id_producto = $this->db->insert_id();

        // 2. Lógica especial si es tipo "corte"
        if($tipo == 'corte'){
            $data_final = [
                'codigo'       => $codigo . '-F', // SKU diferente para el terminado
                'nombre'       => $this->input->post('nombre'),
                'tipo'         => 'final',
                'detalles'    => $this->input->post('detalles'),
                'color'        => $this->input->post('color'),
                'talla'        => $this->input->post('talla'),
                'tipo_tela'    => $this->input->post('tipo_tela'),
                'precio_venta' => $this->input->post('precio_venta'),
                'created_at'   => $fecha_actual
            ];

            // Insertar el producto final relacionado
            $this->db->insert('productos_peru', $data_final);
            $id_final = $this->db->insert_id();

            // Guardar la relación en el producto "corte"
            $this->db->where('id', $id_producto);
            $this->db->update('productos_peru', [
                'producto_final_id' => $id_final
            ]);
        }

        // 3. Registrar stock inicial
        // Corregido: Ahora permite registrar stock si es > 0, sin importar el tipo
        if ($stock_inicial > 0) {
            $this->Inventario_model->registrar_movimiento_peru(
                $id_producto,      // Se asigna al producto recién creado (el Corte)
                $stock_inicial,
                'Entrada',
                'Inventario Inicial',
                NULL,
                'Carga inicial de sistema'
            );
        }

    $this->db->trans_complete();

    // Verificación de éxito de la transacción
    if ($this->db->trans_status() === FALSE) {
        // Opcional: Manejar el error (ej: set_flashdata con error)
        log_message('error', 'Error al guardar producto y movimientos en guardar_peru');
    }

    redirect('productos');
}

    public function editar($id) {
    // Buscamos el producto por su ID
    $this->db->where('id', $id);
    $query = $this->db->get('productos_peru');
    $data['producto'] = $query->row(); // Obtenemos solo un registro

    if (!$data['producto']) {
        redirect('productos'); // Si no existe, regresamos al index
    }

    $this->load->view('layouts/header');
    $this->load->view('layouts/sidebar');
    // Usamos la misma vista 'crear_peru' pero le pasamos los datos
    $this->load->view('productos/crear_peru', $data);
    $this->load->view('layouts/footer');
    }

    // Método para procesar la actualización de un producto existente
    public function actualizar() {
        // Recibimos el ID del campo oculto (hidden)
        $id = $this->input->post('id');

        // Preparamos los nuevos datos desde el formulario
        $data = [
            'codigo'       => $this->input->post('codigo'),
            'nombre'       => $this->input->post('nombre'),
            'tipo'         => $this->input->post('tipo'),
            'detalles'    => $this->input->post('detalles'),
            'color'        => $this->input->post('color'),
            'talla'        => ($this->input->post('tipo') == 'final') ? $this->input->post('talla') : NULL,
            'tipo_tela'    => $this->input->post('tipo_tela'),
            'stock'        => $this->input->post('stock'),
            'precio_venta' => $this->input->post('precio_venta')
        ];

        // Aplicamos el WHERE para no actualizar toda la tabla por error
        $this->db->where('id', $id);
        
        if ($this->db->update('productos_peru', $data)) {
            // Si sale bien, mandamos mensaje de éxito
            $this->session->set_flashdata('success', '¡Producto actualizado correctamente!');
        } else {
            // Si falla, mandamos mensaje de error
            $this->session->set_flashdata('error', 'No se pudieron guardar los cambios.');
        }

        // Al terminar, regresamos al listado principal
        redirect('productos');
    }

    public function eliminar($id) {
    // 1. Verificar si el producto existe o tiene movimientos (opcional)
    $this->db->where('id', $id);
    
    if ($this->db->delete('productos_peru')) { // Verifica que tu tabla se llame 'productos'
        $this->session->set_flashdata('success', 'Producto eliminado del inventario.');
    } else {
        $this->session->set_flashdata('error', 'No se pudo eliminar. El producto podría estar vinculado a una venta.');
    }
    
    redirect('productos'); // O la ruta donde tengas tu listado de inventario
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
    
    $existe = $this->db->get('productos_peru')->num_rows();

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
            redirect('productos');
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
        $success = $this->Inventario_model->registrar_movimiento_peru(
            $id_producto,
            $cantidad,
            'Entrada',
            'Ajuste Manual', // Valor exacto del ENUM
            NULL,
            $detalle_motivo
        );

        // 3. Respuesta y redirección
        if ($success) {
            $this->session->set_flashdata('success', '¡Stock actualizado correctamente en Perú!');
        } else {
            $this->session->set_flashdata('error', 'No se pudo registrar el movimiento. Verifique los valores del ENUM.');
        }

        redirect('productos');
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
    $success = $this->Inventario_model->registrar_movimiento_peru(
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

    redirect('productos');
}
}