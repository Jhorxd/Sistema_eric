<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Liquidaciones extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if ($this->session->userdata('pais') != 'bolivia') {
            $this->session->set_flashdata('error', '❌ Acceso restringido a Bolivia');
            redirect(base_url());
        }
        // Cargar modelos necesarios
    }

    public function index() {

        $fecha_inicio  = $this->input->get('fecha_inicio');
        $fecha_fin     = $this->input->get('fecha_fin');
        $distribuidor  = $this->input->get('distribuidor');

        $this->db->from('pedidos_alfredo');

        // FILTRO POR CLIENTE
        if (!empty($distribuidor)) {
            $this->db->like('cliente', $distribuidor);
        }

        // FILTRO POR FECHA
        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $this->db->where('fecha_pedido >=', $fecha_inicio);
            $this->db->where('fecha_pedido <=', $fecha_fin . ' 23:59:59');
        }

        $this->db->order_by('fecha_pedido', 'DESC');

        $data['pendientes'] = $this->db->get()->result();

        // mantener filtros
        $data['f_inicio'] = $fecha_inicio;
        $data['f_fin']    = $fecha_fin;
        $data['f_dist']   = $distribuidor;

        $this->load->view('layouts/header');
        $this->load->view('layouts/sidebar');
        $this->load->view('alfredo_view', $data);
        $this->load->view('layouts/footer');
    }

public function registrar_pago_alfredo() {
    $id_pedido = $this->input->post('id_pedido');
    $monto_abono = (float)$this->input->post('monto_pago');
    $metodo = $this->input->post('metodo');

    // Obtener pedido de Alfredo
    $pedido = $this->db->get_where('pedidos_alfredo', ['id' => $id_pedido])->row();

    if ($pedido) {
        // --- NUEVA VALIDACIÓN ---
        // 1. Calcular cuánto se ha pagado hasta ahora
        $this->db->select_sum('monto_pagado');
        $this->db->where('id_pedido_alfredo', $id_pedido);
        $suma_pagos = $this->db->get('pagos_alfredo')->row()->monto_pagado ?? 0;

        // 2. Calcular el saldo pendiente
        $saldo_pendiente = $pedido->monto_alfredo - $suma_pagos;

        // 3. Verificar si el abono excede la deuda (con un pequeño margen por decimales)
        if ($monto_abono > ($saldo_pendiente + 0.01)) {
            $this->session->set_flashdata('error', 'El monto excede la deuda actual ($' . number_format($saldo_pendiente, 2) . ').');
            redirect('liquidaciones');
            return; // Detener la ejecución
        }
        // ------------------------

        $this->db->trans_start();

        // Registrar pago en historial
        $data_pago = [
            'id_pedido_alfredo' => $id_pedido,
            'monto_pagado'      => $monto_abono,
            'metodo_pago'       => $metodo,
            'fecha_pago'        => date('Y-m-d H:i:s')
        ];
        $this->db->insert('pagos_alfredo', $data_pago);

        // Actualizar estado si se cubre el total
        $nuevo_total_pagado = $suma_pagos + $monto_abono;
        if ($nuevo_total_pagado >= $pedido->monto_alfredo) {
            $this->db->where('id', $id_pedido);
            $this->db->update('pedidos_alfredo', ['estado' => 'Pagado']);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Error técnico al registrar el pago.');
        } else {
            $this->session->set_flashdata('success', 'Pago registrado correctamente.');
        }
    }

    redirect('liquidaciones');
}

    public function obtener_historial_pagos($id) {

        $this->db->where('id_pedido_alfredo', $id);
        $this->db->order_by('fecha_pago', 'DESC');

        $pagos = $this->db->get('pagos_alfredo')->result();

        echo json_encode($pagos);
    }

}