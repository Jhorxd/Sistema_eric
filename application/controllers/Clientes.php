<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes extends CI_Controller {

    public function index() {

    $this->load->library('session');

        if ($this->session->userdata('pais') != 'peru') {
            $this->session->set_flashdata('error', 'Acceso restringido a Perú');
            redirect(base_url());
        }
        $data['clientes'] = $this->db->get('clientes_peru')->result();
        
        $this->load->view('layouts/header');
        $this->load->view('layouts/sidebar');
        $this->load->view('clientes/index', $data);
        $this->load->view('layouts/footer');
    }

    // Guardar cliente nuevo (desde el módulo de clientes)
    public function guardar() {
        $dni = $this->input->post('dni');
        $nombre = $this->input->post('nombre');

        // 1. Verificar si el DNI ya existe en la tabla clientes_peru
        $query = $this->db->get_where('clientes_peru', ['dni' => $dni]);
        $cliente_existente = $query->row();

        if ($cliente_existente) {
            // 2. Si existe, no insertamos y mandamos alerta de error
            $mensaje = "El DNI/RUC <b>$dni</b> ya está registrado a nombre de: <b>{$cliente_existente->nombre}</b>";
            $this->session->set_flashdata('error', $mensaje);
        } else {
            // 3. Si NO existe, procedemos a guardar
            $data = [
                'dni'       => $dni,
                'nombre'    => $nombre,
                'celular'   => $this->input->post('celular'),
                'ubicacion' => $this->input->post('destino')
            ];
            
            if ($this->db->insert('clientes_peru', $data)) {
                $this->session->set_flashdata('success', '¡Cliente registrado con éxito!');
            } else {
                $this->session->set_flashdata('error', 'Ocurrió un error inesperado al guardar.');
            }
        }

        // Redirigir siempre al módulo de clientes
        redirect('clientes');
    }

    // IMPORTANTE: Esta es la función que usa el buscador de la Cotización
    public function buscar_por_dni() {
        $dni = $this->input->post('dni');
        $cliente = $this->db->get_where('clientes_peru', ['dni' => $dni])->row();
        
        if($cliente) {
            echo json_encode($cliente);
        } else {
            echo json_encode(null);
        }
    }

    // Obtener datos para el modal
    public function editar_ajax($id) {
        $cliente = $this->db->get_where('clientes_peru', ['id' => $id])->row();
        echo json_encode($cliente);
    }

    // Procesar la actualización
    public function actualizar() {
        $id = $this->input->post('id_cliente');
        $data = [
            'dni'     => $this->input->post('dni'),
            'nombre'  => $this->input->post('nombre'),
            'celular' => $this->input->post('celular'),
            'destino' => $this->input->post('destino')
        ];

        $this->db->where('id', $id);
        if ($this->db->update('clientes_peru', $data)) {
            $this->session->set_flashdata('success', 'Cliente actualizado correctamente.');
        } else {
            $this->session->set_flashdata('error', 'Error al actualizar cliente.');
        }
        redirect('clientes');
    }

    public function eliminar($id) {
    // 1. Intentar eliminar el registro
    $this->db->where('id', $id);
    if ($this->db->delete('clientes_peru')) { // Asegúrate que el nombre sea 'clientes' o 'clientes_peru'
        $this->session->set_flashdata('success', 'Cliente eliminado correctamente.');
    } else {
        $this->session->set_flashdata('error', 'No se pudo eliminar el cliente porque tiene registros asociados.');
    }
    
    // 2. Redirigir de vuelta al listado
    redirect('clientes');
}
}