<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distribuidores extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if ($this->session->userdata('pais') != 'bolivia') {
            $this->session->set_flashdata('error', '❌ Acceso restringido a Bolivia');
            redirect(base_url());
        }
        // Opcional: Verificar sesión aquí
    }

    public function index() {
        // Obtenemos los datos de la tabla de Bolivia
        $data['distribuidores'] = $this->db->get('distribuidores_bolivia')->result();
        
        $this->load->view('layouts/header');
        $this->load->view('layouts/sidebar');
        $this->load->view('distribuidores/index', $data);
        $this->load->view('layouts/footer');
    }

        public function guardar() {
            $nit = $this->input->post('nit');
            $nombre = $this->input->post('nombre');

            // 1. Verificar si el NIT ya existe en distribuidores_bolivia
            $query = $this->db->get_where('distribuidores_bolivia', ['nit' => $nit]);
            $existe = $query->row();

            if ($existe) {
                // 2. Si existe, enviamos error y evitamos el duplicado
                $this->session->set_flashdata('error', "El NIT <b>$nit</b> ya pertenece al distribuidor: <b>{$existe->nombre}</b>");
            } else {
                // 3. Si no existe, procedemos al registro
                $data = [
                    'nit'     => $nit,
                    'nombre'  => $nombre,
                    'celular' => $this->input->post('celular'),
                    'destino' => $this->input->post('destino')
                ];
                
                if ($this->db->insert('distribuidores_bolivia', $data)) {
                    $this->session->set_flashdata('success', 'Distribuidor registrado correctamente.');
                } else {
                    $this->session->set_flashdata('error', 'Error interno al intentar guardar.');
                }
            }

            redirect('distribuidores');
        }

    // Buscador AJAX para el módulo de Ventas/Cotizaciones
        public function buscar_por_nit() {
            // 1. Recibes el NIT
            $nit = $this->input->post('nit');
            
            // 2. Consultas a la tabla específica de Bolivia
            $distribuidor = $this->db->get_where('distribuidores_bolivia', ['nit' => $nit])->row();
            
            // 3. Estableces el header para que el navegador sepa que es JSON
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($distribuidor));
        }

        public function obtener_productos_por_distribuidor() {
    $distribuidor_id = $this->input->post('distribuidor_id');
    
    // Aquí ajusta la consulta según tu lógica. 
    // Por ejemplo, si los productos tienen un campo 'distribuidor_id':
    $productos = $this->db->get_where('productos_bolivia', ['id_distribuidor' => $distribuidor_id])->result();
    
    return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($productos));
}

    public function editar_ajax($id) {
        $distribuidor = $this->db->get_where('distribuidores_bolivia', ['id' => $id])->row();
        echo json_encode($distribuidor);
    }

public function actualizar() {
    // 1. Recibir el ID (ahora coincide con el name="id_distribuidor")
    $id = $this->input->post('id_distribuidor');
    
    // 2. Armar el array de datos
    // Asegúrate de que las columnas 'nit', 'nombre', etc., existan tal cual en 'distribuidores_bolivia'
    $data = [
        'nit'     => $this->input->post('nit'),
        'nombre'  => $this->input->post('nombre'),
        'celular' => $this->input->post('celular'),
        'destino' => $this->input->post('destino')
    ];

    // 3. Validar que el ID no sea nulo antes de actualizar
    if (!empty($id)) {
        $this->db->where('id', $id);
        if ($this->db->update('distribuidores_bolivia', $data)) {
            $this->session->set_flashdata('success', 'Distribuidor actualizado correctamente.');
        } else {
            $this->session->set_flashdata('error', 'Error al actualizar en la base de datos.');
        }
    } else {
        $this->session->set_flashdata('error', 'No se recibió un ID válido.');
    }

    redirect('distribuidores');
}

    public function eliminar($id) {
        $this->db->where('id', $id);
        if ($this->db->delete('distribuidores_bolivia')) {
            $this->session->set_flashdata('success', 'Distribuidor eliminado.');
        } else {
            $this->session->set_flashdata('error', 'No se pudo eliminar (tiene registros asociados).');
        }
        redirect('distribuidores');
    }
}