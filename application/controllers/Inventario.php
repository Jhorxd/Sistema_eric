<?php
class Inventario extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Inventario_model');
    }

public function kardex_peru() {
    // 🔒 BLOQUEO POR PAÍS - Solo Perú
    if ($this->session->userdata('pais') != 'peru') {
        $this->session->set_flashdata('error', '❌ Acceso restringido. Solo usuarios Perú');
        redirect(base_url('login'));
    }

    // Obtener filtros
    $f_inicio = $this->input->get('fecha_inicio');
    $f_fin    = $this->input->get('fecha_fin');
    $prod_id  = $this->input->get('producto_id');

    // Consulta de Movimientos PERÚ
    $this->db->select('m.*, p.nombre as producto_nombre, p.codigo');
    $this->db->from('producto_movimientos_peru m');
    $this->db->join('productos_peru p', 'p.id = m.id_producto');

    // Filtros
    if ($f_inicio) {
        $this->db->where('DATE(m.fecha_registro) >=', $f_inicio);
    }
    if ($f_fin) {
        $this->db->where('DATE(m.fecha_registro) <=', $f_fin);
    }
    if ($prod_id) {
        $this->db->where('m.id_producto', $prod_id);
    }

    $this->db->order_by('m.id', 'DESC');
    $data['movimientos'] = $this->db->get()->result();

    // Lista de productos PERÚ para filtro
    $data['productos'] = $this->db
        ->order_by('nombre', 'ASC')
        ->get('productos_peru')
        ->result();
    
    // Título específico por país
    $data['titulo'] = 'Kardex Perú';
    
    $this->load->view('layouts/header');
    $this->load->view('layouts/sidebar');
    $this->load->view('inventario/kardex_peru', $data);
    $this->load->view('layouts/footer');
}


public function kardex_bolivia() {
    if ($this->session->userdata('pais') != 'bolivia') {
        $this->session->set_flashdata('error', '❌ Acceso restringido. Solo usuarios Bolivia');
        redirect(base_url('login'));
    }

    // Capturamos con nombres consistentes
    $f_inicio = $this->input->get('fecha_inicio');
    $f_fin    = $this->input->get('fecha_fin');
    $prod_id  = $this->input->get('producto_id');
    $dist_id  = $this->input->get('distribuidor_id'); // Asegúrate que coincida con el NAME del HTML

    $this->db->select('m.*, p.nombre as producto_nombre, p.codigo, p.talla, p.color, d.nombre as distribuidor_nombre');
    $this->db->from('producto_movimientos_bolivia m');
    $this->db->join('productos_bolivia p', 'p.id = m.id_producto');
    $this->db->join('distribuidores_bolivia d', 'd.id = m.id_distribuidor', 'left');

    // Usar !empty ayuda a ignorar valores "" o NULL
    if (!empty($f_inicio)) {
        $this->db->where('DATE(m.fecha_registro) >=', $f_inicio);
    }
    if (!empty($f_fin)) {
        $this->db->where('DATE(m.fecha_registro) <=', $f_fin);
    }
    if (!empty($prod_id)) {
        $this->db->where('m.id_producto', $prod_id);
    }
    if (!empty($dist_id)) {
        $this->db->where('m.id_distribuidor', $dist_id); 
    }

    $this->db->order_by('m.fecha_registro', 'DESC');
    $data['movimientos'] = $this->db->get()->result();

    $data['productos'] = $this->db->order_by('nombre', 'ASC')->get('productos_bolivia')->result();
    $data['distribuidores'] = $this->db->order_by('nombre', 'ASC')->get('distribuidores_bolivia')->result();
    
    $this->load->view('layouts/header');
    $this->load->view('layouts/sidebar');
    $this->load->view('inventario/kardex_bolivia', $data);
    $this->load->view('layouts/footer');
}
    }