<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produccion extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if ($this->session->userdata('pais') != 'peru') {
            $this->session->set_flashdata('error', 'Acceso restringido a Perú');
            redirect(base_url());
        }
        // Verificamos que el usuario esté logueado
        if (!$this->session->userdata('id')) {
            redirect('login');
        }
    }

    // 1. Vista principal del módulo de producción
        public function index() {

            // Productos corte
            $this->db->where('tipo', 'corte');
            $data['cortes'] = $this->db->get('productos_peru')->result();

            // Productos finales
            $this->db->where('tipo', 'final');
            $data['finales'] = $this->db->get('productos_peru')->result();

            $this->load->view('layouts/header');
            $this->load->view('layouts/sidebar');
            $this->load->view('produccion/nuevo_registro', $data);
            $this->load->view('layouts/footer');
        }

    // 2. Proceso de transformación de stock
    public function procesar_produccion() {

        $this->load->model('Inventario_model');

        $producto_corte = $this->input->post('producto_corte_id');

        $productos_destino = $this->input->post('producto_id');
        $cantidades_producidas = $this->input->post('cantidad_resultado');

        if (empty($producto_corte) || empty($productos_destino)) {

            $this->session->set_flashdata('error', 'Debe seleccionar un corte y al menos un producto final.');
            redirect('produccion');
        }

        $this->db->trans_start();

        // Obtener corte
        $corte_info = $this->db->get_where('productos_peru', [
            'id' => $producto_corte
        ])->row();

        /*
        ====================================
        CALCULAR TOTAL PRODUCIDO
        ====================================
        */

        $total_producido = 0;

        foreach ($cantidades_producidas as $qty) {
            $total_producido += (float)$qty;
        }

        /*
        ====================================
        SALIDA DEL CORTE
        ====================================
        */

        if ($total_producido > 0) {

            $this->Inventario_model->registrar_movimiento_peru(

                $producto_corte,
                $total_producido,
                'Salida',
                'Produccion',
                NULL,
                'Consumo de corte para producción'

            );
        }

        /*
        ====================================
        ENTRADA PRODUCTOS FINALES
        ====================================
        */

        foreach ($productos_destino as $index => $id_prod_final) {

            $qty = (float)$cantidades_producidas[$index];

            if ($qty > 0) {

                $this->Inventario_model->registrar_movimiento_peru(

                    $id_prod_final,
                    $qty,
                    'Entrada',
                    'Produccion',
                    NULL,
                    'Producción desde corte: ' . $corte_info->nombre

                );

            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            $this->session->set_flashdata('error', 'Error al registrar producción');

        } else {

            $this->session->set_flashdata('success', 'Producción registrada correctamente');

        }

        redirect('productos');
    }
}