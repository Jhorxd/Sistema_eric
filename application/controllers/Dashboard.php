<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cargamos la base de datos por si no está en el autoload
        $this->load->database();
    }

    public function index() {
        if (!$this->session->userdata('pais')) {
            redirect('login'); 
        }

        $pais = $this->session->userdata('pais');
        
        // Filtros
        $f_inicio   = $this->input->get('fecha_inicio') ?: date('Y-m-01');
        $f_fin      = $this->input->get('fecha_fin') ?: date('Y-m-d');
        $id_dist    = $this->input->get('distribuidor');
        $f_nombre_prod = $this->input->get('producto_nombre');

        $data = array();
        
        // Carga de datos específicos por país para filtros
        if ($pais == 'bolivia') {
            $data['distribuidores'] = $this->db->get('distribuidores_bolivia')->result();
            
            // Cargar nombres únicos de productos
            $this->db->select('DISTINCT(nombre) as nombre');
            if ($id_dist && $id_dist != 'alfredo') {
                $this->db->where('id_distribuidor', $id_dist);
            }
            $data['lista_productos_nombres'] = $this->db->get('productos_bolivia')->result();
        } else {
            $data['distribuidores'] = []; // Perú no usa distribuidores en dashboard por ahora
            $data['lista_productos_nombres'] = $this->db->select('DISTINCT(nombre) as nombre')->get('productos_peru')->result();
        }
        
        $data['f_inicio'] = $f_inicio;
        $data['f_fin']    = $f_fin;
        $data['f_dist']   = $id_dist;
        $data['f_prod_nombre'] = $f_nombre_prod;

        if ($pais == 'peru') {
            $data = array_merge($data, $this->_get_data_peru($f_inicio, $f_fin));
            $vista = 'dashboard_peru';
        } else {
            $data = array_merge($data, $this->_get_data_bolivia($f_inicio, $f_fin, $id_dist, $f_nombre_prod));
            $vista = 'dashboard_bolivia';
        }

        $this->load->view($vista, $data);
    }

    // --- LÓGICA DE DATOS PARA BOLIVIA ---
    private function _get_data_bolivia($inicio, $fin, $id_distribuidor = null, $producto_nombre = null) {
        
        $is_alfredo = ($id_distribuidor == 'alfredo');

        // 1. Ventas del periodo
        $this->db->select_sum('total_venta');
        $this->db->where('fecha >=', $inicio);
        $this->db->where('fecha <=', $fin . ' 23:59:59');
        if ($is_alfredo) {
            $this->db->where('(id_distribuidor IS NULL OR id_distribuidor = 0)');
        } elseif ($id_distribuidor) {
            $this->db->where('id_distribuidor', $id_distribuidor);
        }
        
        if ($producto_nombre) {
            $this->db->where("id IN (SELECT id_venta FROM venta_detalles_bolivia vd JOIN productos_bolivia p ON vd.id_producto = p.id WHERE p.nombre = '$producto_nombre')");
        }
        $ventas = $this->db->get('ventas_bolivia')->row();

        // 2. Pendiente de Depósito (Distribuidores Regulares)
        // Calculamos: SUM(total_venta - delivery) - SUM(pagos en pagos_distribuidores)
        $where_dist = "";
        if ($is_alfredo) {
            $where_dist = "WHERE (v.id_distribuidor IS NULL OR v.id_distribuidor = 0)";
        } elseif ($id_distribuidor) {
            $where_dist = "WHERE v.id_distribuidor = $id_distribuidor";
        }

        $query_pend = $this->db->query("
            SELECT SUM(v.total_venta - v.comision_delivery - COALESCE(p.total_pagado, 0)) as pendiente
            FROM ventas_bolivia v
            LEFT JOIN (
                SELECT id_venta, SUM(monto_pagado) as total_pagado 
                FROM pagos_distribuidores 
                GROUP BY id_venta
            ) p ON v.id = p.id_venta
            $where_dist
        ")->row();
        $total_pendiente = $query_pend->pendiente ?? 0;

        // 3. Alfredo Pending (Suma de saldos de pedidos_alfredo)
        $alfredo_data = $this->db->query("
            SELECT SUM(p.monto_alfredo - COALESCE(pagos.total_pagado, 0)) as pendiente 
            FROM pedidos_alfredo p
            LEFT JOIN (
                SELECT id_pedido_alfredo, SUM(monto_pagado) as total_pagado 
                FROM pagos_alfredo 
                GROUP BY id_pedido_alfredo
            ) pagos ON p.id = pagos.id_pedido_alfredo
            WHERE p.estado != 'Pagado'
        ")->row();
        $pendiente_alfredo = $alfredo_data->pendiente ?? 0;

        // 4. Stock Crítico
        $this->db->where('stock <', 5);
        if ($is_alfredo) {
            $this->db->where('(id_distribuidor IS NULL OR id_distribuidor = 0)');
        } elseif ($id_distribuidor) {
            $this->db->where('id_distribuidor', $id_distribuidor);
        }
        if ($producto_nombre) $this->db->where('nombre', $producto_nombre);
        $stock = $this->db->count_all_results('productos_bolivia');

        // 5. Últimos Movimientos
        $this->db->select('k.*, p.nombre as producto_nombre');
        $this->db->from('producto_movimientos_bolivia k');
        $this->db->join('productos_bolivia p', 'k.id_producto = p.id');
        $this->db->where('k.fecha_registro >=', $inicio);
        $this->db->where('k.fecha_registro <=', $fin . ' 23:59:59');
        if ($is_alfredo) {
            $this->db->where('(p.id_distribuidor IS NULL OR p.id_distribuidor = 0)');
        } elseif ($id_distribuidor) {
            $this->db->where('p.id_distribuidor', $id_distribuidor);
        }
        if ($producto_nombre) $this->db->where('p.nombre', $producto_nombre);
        $this->db->order_by('k.fecha_registro', 'DESC');
        $this->db->limit(10);
        $movimientos = $this->db->get()->result();

        // 6. Productos / Pedidos Alfredo
        $pedidos_alfredo = [];
        $productos_distribuidor = []; // Asegurar inicialización

        if ($is_alfredo) {
            // Simplificamos la consulta para asegurar que traiga datos
            $pedidos_alfredo = $this->db->query("
                SELECT *, 
                (SELECT COALESCE(SUM(monto_pagado), 0) FROM pagos_alfredo WHERE id_pedido_alfredo = p.id) as pagado
                FROM pedidos_alfredo p
                WHERE estado != 'Pagado'
                ORDER BY id DESC
            ")->result();

            // Calcular saldo manualmente para cada uno para evitar errores de tipo
            foreach($pedidos_alfredo as $pa) {
                $pa->saldo = $pa->monto_alfredo - $pa->pagado;
            }
            
            // Cargar productos sin distribuidor (Stock de Oficina/Alfredo)
            $this->db->where('(id_distribuidor IS NULL OR id_distribuidor = 0 OR id_distribuidor = \'\')');
            if ($producto_nombre) $this->db->where('nombre', $producto_nombre);
            $productos_distribuidor = $this->db->get('productos_bolivia')->result();
        } else {
            if ($id_distribuidor) $this->db->where('id_distribuidor', $id_distribuidor);
            if ($producto_nombre) $this->db->where('nombre', $producto_nombre);
            $productos_distribuidor = $this->db->get('productos_bolivia')->result();
        }

        return [
            'total_ventas_mes'       => $ventas->total_venta ?? 0,
            'total_pendiente'        => $total_pendiente,
            'pendiente_alfredo'      => $pendiente_alfredo,
            'productos_bajo_stock'   => $stock,
            'ultimos_movimientos'    => $movimientos,
            'productos_distribuidor' => $productos_distribuidor,
            'pedidos_alfredo'        => $pedidos_alfredo,
            'is_alfredo'             => $is_alfredo,
            'total_items'            => count($productos_distribuidor)
        ];
    }

    // --- LÓGICA DE DATOS PARA PERÚ ---
    private function _get_data_peru($inicio, $fin) {
        // 1. Ventas del periodo
        $this->db->select_sum('total_venta');
        $this->db->where('fecha >=', $inicio);
        $this->db->where('fecha <=', $fin . ' 23:59:59');
        $ventas = $this->db->get('ventas_peru')->row();

        // 2. Saldos por cobrar (Ventas no pagadas totalmente)
        $this->db->select_sum('total_venta');
        $this->db->select_sum('total_pagado');
        $this->db->where('total_pagado < total_venta');
        $saldos_q = $this->db->get('ventas_peru')->row();
        $total_saldos = ($saldos_q->total_venta ?? 0) - ($saldos_q->total_pagado ?? 0);

        // 3. Stock Crítico
        $this->db->where('stock <', 5);
        $stock = $this->db->count_all_results('productos_peru');

        // 4. Últimos Movimientos
        $this->db->select('k.*, p.nombre as producto_nombre');
        $this->db->from('producto_movimientos_peru k');
        $this->db->join('productos_peru p', 'k.id_producto = p.id');
        $this->db->order_by('k.id', 'DESC');
        $this->db->limit(10);
        $movimientos = $this->db->get()->result();

        // 5. Total ítems (SKU)
        $total_items = $this->db->count_all('productos_peru');

        return [
            'total_ventas_mes'       => $ventas->total_venta ?? 0,
            'total_saldos'           => $total_saldos,
            'productos_bajo_stock'   => $stock,
            'ultimos_movimientos'    => $movimientos,
            'total_items'            => $total_items
        ];
    }
}