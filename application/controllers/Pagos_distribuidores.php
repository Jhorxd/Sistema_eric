<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagos_distribuidores extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // 🔒 SOLO BOLIVIA
        if ($this->session->userdata('pais') != 'bolivia') {
            $this->session->set_flashdata('error', '❌ Acceso restringido a Bolivia');
            redirect(base_url());
        }
    }

public function index() {
    // 1. Capturar filtros
    $f_inicio = $this->input->get('fecha_inicio');
    $f_fin    = $this->input->get('fecha_fin');
    $dist_id  = $this->input->get('distribuidor_id');
    $metodo   = $this->input->get('metodo_pago');

    // 2. Consulta base
    $this->db->select('
        p.id,
        p.id_venta,
        p.monto, 
        p.fecha_pago,
        p.metodo_pago,
        p.nota,
        v.nit as venta_nit,
        d.nombre as distribuidor_nombre
    ');
    $this->db->from('venta_pagos_bolivia p'); 
    $this->db->join('ventas_bolivia v', 'v.id = p.id_venta', 'inner');
    $this->db->join('distribuidores_bolivia d', 'CONVERT(d.nit USING latin1) = v.nit', 'left');

    // --- EXCLUSIÓN ESPECÍFICA ---
    // Esto ignora siempre los pagos de Alfredo para que no ensucien el reporte
    $this->db->where('p.metodo_pago !=', 'Transferencia Alfredo');

    // 3. Aplicación de filtros dinámicos
    if (!empty($f_inicio)) {
        $this->db->where('p.fecha_pago >=', $f_inicio . ' 00:00:00');
    }
    if (!empty($f_fin)) {
        $this->db->where('p.fecha_pago <=', $f_fin . ' 23:59:59');
    }
    if (!empty($dist_id)) {
        $this->db->where('d.id', $dist_id);
    }
    if (!empty($metodo)) {
        // Si el usuario filtra por un método, se sumará a la exclusión anterior
        $this->db->where('p.metodo_pago', $metodo);
    }

    $this->db->order_by('p.fecha_pago', 'DESC');
    $data['pagos'] = $this->db->get()->result();

    // 4. Datos para el selector del formulario
    $data['distribuidores'] = $this->db->order_by('nombre', 'ASC')->get('distribuidores_bolivia')->result();

    $this->load->view('layouts/header');
    $this->load->view('layouts/sidebar');
    $this->load->view('pagos/index', $data); 
    $this->load->view('layouts/footer');
}

    public function eliminar($id) {
        $this->db->where('id', $id);
        if($this->db->delete('pagos_distribuidores')) {
            $this->session->set_flashdata('success', '✅ Pago eliminado correctamente.');
        } else {
            $this->session->set_flashdata('error', '❌ No se pudo eliminar el pago.');
        }
        redirect(base_url('pagos_distribuidores'));
    }
}