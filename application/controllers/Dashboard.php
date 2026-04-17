<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cargamos la base de datos por si no está en el autoload
        $this->load->database();
    }

    public function index() {
        // Verificación de seguridad: si no hay país en la sesión, no está logueado
        if (!$this->session->userdata('pais')) {
            redirect('login'); 
        }

        $pais = $this->session->userdata('pais');
        $mes = date('m');
        $anio = date('Y');
        $data = array();

        // 1. Obtener datos según el país
        if ($pais == 'peru') {
            $data = $this->_get_data_peru($mes, $anio);
            $vista = 'dashboard_peru';
        } else {
            $data = $this->_get_data_bolivia($mes, $anio);
            $vista = 'dashboard_bolivia';
        }

        // 2. Cargar la vista (Los layouts se cargan dentro de la vista o aquí)
        // Si tus vistas ya tienen el header/sidebar por dentro, solo deja la carga de $vista
        $this->load->view($vista, $data);
    }

    // --- LÓGICA DE DATOS PARA PERÚ ---
    private function _get_data_peru($mes, $anio) {
        // Ventas del mes
        $this->db->select_sum('total_venta');
        $this->db->where('MONTH(fecha)', $mes);
        $this->db->where('YEAR(fecha)', $anio);
        $ventas = $this->db->get('ventas_peru')->row();

        // Saldos pendientes (Total - Pagado)
        $saldos = $this->db->query("SELECT SUM(total_venta - total_pagado) as saldo FROM ventas_peru WHERE estado_pago != 'Completado'")->row();

        // Stock Crítico
        $stock = $this->db->where('stock <', 5)->count_all_results('productos_peru');

        // Últimos 5 movimientos del Kardex
        $this->db->select('k.*, p.nombre as producto_nombre');
        $this->db->from('producto_movimientos_peru k');
        $this->db->join('productos_peru p', 'k.id_producto = p.id');
        $this->db->order_by('k.fecha_registro', 'DESC');
        $this->db->limit(5);
        $movimientos = $this->db->get()->result();

        return [
            'total_ventas_mes'     => $ventas->total_venta ?? 0,
            'total_saldos'         => $saldos->saldo ?? 0,
            'productos_bajo_stock' => $stock,
            'ultimos_movimientos'  => $movimientos,
            'total_items'          => $this->db->count_all('productos_peru')
        ];
    }

    // --- LÓGICA DE DATOS PARA BOLIVIA ---
    private function _get_data_bolivia($mes, $anio) {
        // Ventas del mes (Filtrando por marca de país si existe en tu tabla ventas)
        $this->db->select_sum('total_venta');
        $this->db->where('MONTH(fecha)', $mes);
        $this->db->where('YEAR(fecha)', $anio);
        // $this->db->where('pais_id', 2); // Ejemplo si tienes ID por país
        $ventas = $this->db->get('ventas_bolivia')->row();

        // Stock Crítico Bolivia
        $stock = $this->db->where('stock <', 5)->count_all_results('productos_bolivia');

        // Últimos 5 movimientos Kardex Bolivia
        $this->db->select('k.*, p.nombre as producto_nombre');
        $this->db->from('producto_movimientos_bolivia k');
        $this->db->join('productos_bolivia p', 'k.id_producto = p.id');
        $this->db->order_by('k.fecha_registro', 'DESC');
        $this->db->limit(5);
        $movimientos = $this->db->get()->result();

        return [
            'total_ventas_mes'     => $ventas->total_venta ?? 0,
            'productos_bajo_stock' => $stock,
            'ultimos_movimientos'  => $movimientos,
            'total_items'          => $this->db->count_all('productos_bolivia')
        ];
    }
}