<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagos_alfredo extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // 🔒 SOLO BOLIVIA
        if ($this->session->userdata('pais') != 'bolivia') {
            $this->session->set_flashdata('error', '❌ Acceso restringido a Bolivia');
            redirect(base_url());
        }
    }

    public function index() {
        // Capturar fechas o usar hoy por defecto
        $fecha_inicio = $this->input->post('fecha_inicio') ? $this->input->post('fecha_inicio') : date('Y-m-d');
        $fecha_fin    = $this->input->post('fecha_fin') ? $this->input->post('fecha_fin') : date('Y-m-d');

        // CONSULTA DIRECTA (Sin Model)
        $this->db->where('DATE(fecha_pago) >=', $fecha_inicio);
        $this->db->where('DATE(fecha_pago) <=', $fecha_fin);
        $pagos = $this->db->get('pagos_alfredo')->result();

        $data = [
            'pagos'        => $pagos,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin'    => $fecha_fin,
            'titulo'       => 'Reporte de Pagos a Alfredo'
        ];

        $this->load->view('layouts/header');
        $this->load->view('layouts/sidebar'); 
        $this->load->view('pagos_alfredo/index', $data);
        $this->load->view('layouts/footer');
    }

    public function guardar_pago() {
        $data = [
            'id_pedido_alfredo' => $this->input->post('id_pedido_alfredo'),
            'monto_pagado'      => (float)$this->input->post('monto_pagado'),
            'metodo_pago'       => $this->input->post('metodo_pago'),
            'fecha_pago'        => date('Y-m-d H:i:s'),
            'observacion'       => !empty($this->input->post('observacion')) ? $this->input->post('observacion') : NULL
        ];

        if ($this->db->insert('pagos_alfredo', $data)) {
            $this->session->set_flashdata('success', 'Pago registrado correctamente.');
        } else {
            $this->session->set_flashdata('error', 'No se pudo registrar el pago.');
        }
        redirect('pagos_alfredo');
    }

    public function eliminar($id) {
        $this->db->where('id', $id);
        if ($this->db->delete('pagos_alfredo')) {
            $this->session->set_flashdata('success', 'Registro eliminado.');
        }
        redirect('pagos_alfredo');
    }
}