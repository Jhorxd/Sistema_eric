<?php
class Inventario_model extends CI_Model {

    public function registrar_movimiento_peru($id_producto, $cantidad, $tipo, $origen, $ref_id, $motivo) {
        // 1. Obtener stock actual
        $prod = $this->db->get_where('productos_peru', ['id' => $id_producto])->row();
        if (!$prod) return false;

        $stock_anterior = $prod->stock;

        // 2. Calcular nuevo stock
        $stock_actual = ($tipo == 'Entrada') ? ($stock_anterior + $cantidad) : ($stock_anterior - $cantidad);

        // 3. Iniciar Transacción
        $this->db->trans_start();
            // Actualizar stock en productos_peru
            $this->db->where('id', $id_producto);
            $this->db->update('productos_peru', ['stock' => $stock_actual]);

            // Insertar en el historial
            $this->db->insert('producto_movimientos_peru', [
                'id_producto'     => $id_producto,
                'tipo_movimiento' => $tipo,
                'cantidad'        => $cantidad,
                'stock_anterior'  => $stock_anterior,
                'stock_actual'    => $stock_actual,
                'origen'          => $origen,
                'referencia_id'   => $ref_id,
                'motivo'          => $motivo,
                'usuario'         => $this->session->userdata('nombre') ?? 'Sistema',
                'fecha_registro'  => date('Y-m-d H:i:s')
            ]);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    
public function registrar_movimiento_bolivia($id_producto, $cantidad, $tipo, $origen, $ref_id, $motivo, $id_distribuidor = null) {
    
    // Si no se envía id_distribuidor, intentamos obtener el que tiene el producto por defecto
    if (!$id_distribuidor) {
        $prod_info = $this->db->get_where('productos_bolivia', ['id' => $id_producto])->row();
        $id_distribuidor = $prod_info ? $prod_info->id_distribuidor : null;
    }

    // 1. Obtener stock actual de ESTE producto para ESTE distribuidor
    // Consultamos el último movimiento de este distribuidor para saber su saldo
    $ultimo_mov = $this->db->where('id_producto', $id_producto)
                          ->where('id_distribuidor', $id_distribuidor)
                          ->order_by('id', 'DESC')
                          ->limit(1)
                          ->get('producto_movimientos_bolivia')
                          ->row();

    $stock_anterior = ($ultimo_mov) ? $ultimo_mov->stock_actual : 0;

    // 2. Calcular nuevo stock
    $stock_actual = ($tipo == 'Entrada') ? ($stock_anterior + $cantidad) : ($stock_anterior - $cantidad);

    // 3. Iniciar Transacción
    $this->db->trans_start();
        
        // OPCIONAL: Si aún mantienes el stock global en 'productos_bolivia', actualízalo.
        // Pero lo ideal es que el stock por distribuidor se maneje solo en los movimientos o una tabla relacional.
        $this->db->set('stock', 'stock ' . ($tipo == 'Entrada' ? '+' : '-') . ' ' . $cantidad, FALSE);
        $this->db->where('id', $id_producto);
        $this->db->update('productos_bolivia');

        // 4. Insertar en el historial con el ID del DISTRIBUIDOR
        $this->db->insert('producto_movimientos_bolivia', [
            'id_producto'     => $id_producto,
            'id_distribuidor' => $id_distribuidor, // <--- CLAVE
            'tipo_movimiento' => $tipo,
            'cantidad'        => $cantidad,
            'stock_anterior'  => $stock_anterior,
            'stock_actual'    => $stock_actual,
            'origen'          => $origen,
            'referencia_id'   => $ref_id,
            'motivo'          => $motivo,
            'usuario'         => $this->session->userdata('nombre') ?? 'Sistema',
            'fecha_registro'  => date('Y-m-d H:i:s')
        ]);

    $this->db->trans_complete();

    return $this->db->trans_status();
}
}