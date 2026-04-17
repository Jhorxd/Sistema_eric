<style>
    /* Configuración de etiquetas y inputs para SweetAlert */
    .swal2-label-custom {
        display: block;
        margin-top: 15px;
        font-weight: bold;
        color: #444;
        text-align: left;
        padding-left: 45px;
    }
    .swal-input-custom {
        margin: 5px auto !important;
        width: 80% !important;
        display: block !important;
    }

    /* Estilos Responsivos para Listado de Ventas - Versión Completa y Organizada */
    @media (max-width: 1024px) {
        #tabla-listado-ventas thead { display: none; }
        #tabla-listado-ventas, #tabla-listado-ventas tbody, #tabla-listado-ventas tr, #tabla-listado-ventas td { display: block; width: 100%; border: none; }
        
        #tabla-listado-ventas tr { 
            display: flex;
            flex-direction: column;
            gap: 0;
            margin-bottom: 1.5rem; 
            border: 1px solid #e2e8f0; 
            border-radius: 1.5rem; 
            padding: 1.25rem; 
            background: #fff;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        #tabla-listado-ventas td { 
            padding: 0.75rem 0; 
            border-bottom: 1px solid #f1f5f9;
        }

        /* --- SECCIÓN 1: CABECERA (ID, FECHA, ESTADOS) --- */
        #tabla-listado-ventas td[data-label="ID / Fecha"] {
            border-bottom: 2px solid #6366f1;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        #tabla-listado-ventas td[data-label="ID / Fecha"]::before { content: "ORDEN "; font-size: 0.75rem; color: #6366f1; font-weight: 900; }
        
        #tabla-listado-ventas td[data-label="Pago"],
        #tabla-listado-ventas td[data-label="Envío"] {
            display: inline-flex;
            width: auto;
            border: none;
            padding: 0;
            margin-bottom: 1rem;
        }
        #tabla-listado-ventas td[data-label="Pago"] { margin-right: 0.5rem; }
        #tabla-listado-ventas td[data-label="Pago"]::before { content: "PAGO: "; font-size: 0.55rem; font-weight: 800; margin-right: 4px; color: #94a3b8; }
        #tabla-listado-ventas td[data-label="Envío"]::before { content: "ENVÍO: "; font-size: 0.55rem; font-weight: 800; margin-right: 4px; color: #94a3b8; }

        /* --- SECCIÓN 2: CLIENTE Y PRODUCTO --- */
        #tabla-listado-ventas td[data-label="Distribuidor"],
        #tabla-listado-ventas td[data-label="Celular"],
        #tabla-listado-ventas td[data-label="Tipo"] {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
        }
        #tabla-listado-ventas td[data-label="Distribuidor"] .flex { justify-content: flex-end !important; }
        #tabla-listado-ventas td[data-label="Tipo"]::before { content: "MÉTODO"; }

        /* --- SECCIÓN 3: RESUMEN FINANCIERO CORREGIDO --- */
        /* Hacemos que los 4 montos se vean juntos en un bloque gris */
        #tabla-listado-ventas td[data-label="Total Venta"],
        #tabla-listado-ventas td[data-label="Saldo"],
        #tabla-listado-ventas td[data-label="Pagado"],
        #tabla-listado-ventas td[data-label="Comisión"] {
            display: inline-flex;
            width: 48%; /* Dos por fila */
            flex-direction: column;
            align-items: flex-start;
            background: #f8fafc;
            padding: 0.5rem;
            margin-top: 0.5rem;
            border: 1px solid #edf2f7;
            border-radius: 0.5rem;
        }
        #tabla-listado-ventas td[data-label="Total Venta"], 
        #tabla-listado-ventas td[data-label="Pagado"] { margin-right: 4%; }
        
        #tabla-listado-ventas td[data-label="Total Venta"]::before { content: "TOTAL"; color: #1e293b; }
        #tabla-listado-ventas td[data-label="Saldo"]::before { content: "SALDO"; color: #ef4444; }
        #tabla-listado-ventas td[data-label="Pagado"]::before { content: "PAGADO"; color: #22c55e; }
        #tabla-listado-ventas td[data-label="Comisión"]::before { content: "COMISIÓN"; }

        /* --- SECCIÓN 4: ACCIONES --- */
        #tabla-listado-ventas td[data-label="Acciones"] {
            border: none;
            padding-top: 1.25rem;
            margin-top: 0.5rem;
        }
        #tabla-listado-ventas td[data-label="Acciones"]::before { display: none; }
        #tabla-listado-ventas td[data-label="Acciones"] .flex {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            width: 100%;
        }
        #tabla-listado-ventas td[data-label="Acciones"] button, 
        #tabla-listado-ventas td[data-label="Acciones"] a { 
            width: 100%;
            padding: 0.85rem 0.5rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            font-weight: 800;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        #tabla-listado-ventas td[data-label="Acciones"] span { display: inline; }

        /* Etiquetas Genéricas */
        #tabla-listado-ventas td::before {
            content: attr(data-label);
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.65rem;
            color: #64748b;
            letter-spacing: 0.05em;
        }
    }
</style>

<div class="md:ml-64 min-h-screen bg-slate-50 pt-20">

    <!-- HEADER -->
    <section class="px-4 md:px-8 py-5 md:py-6 border-b bg-white shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <h1 class="text-xl md:text-3xl font-bold text-slate-800 flex items-center gap-3">
                <i class="fas fa-list text-slate-600"></i>
                Listado de Ventas y Pedidos
            </h1>

            <a href="<?= base_url('ventas_bolivia/nueva_cotizacion') ?>"
               class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 md:px-5 md:py-2.5 rounded-xl shadow-lg shadow-blue-100 text-sm md:text-base font-bold transition-all">
                <i class="fas fa-plus"></i>
                NUEVA VENTA
            </a>

        </div>
    </section>

    <!-- TABLA -->
    <section class="p-4 md:p-8">

        <div class="bg-white rounded-xl shadow border border-slate-200 p-4 md:p-6">

            <div class="w-full md:overflow-visible overflow-x-auto">

                <table id="tabla-listado-ventas" class="min-w-[900px] w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-4 text-left">ID / Fecha</th>
                            <?php if ($this->session->userdata('rol') != 'distribuidor'): ?>
                                <th class="px-4 py-4 text-left">Distribuidor</th>
                            <?php endif; ?>
                            <th class="px-4 py-4 text-center">Tipo</th>
                            <th class="px-4 py-4 text-left">Celular Cliente</th>
                            <th class="px-4 py-4 text-right">Total Venta</th>
                            <th class="px-4 py-4 text-right">Comisión</th>
                            <th class="px-4 py-4 text-right">Por Pagar</th>
                            <th class="px-4 py-4 text-right">Pagado</th>
                            <th class="px-4 py-4 text-center">Pago</th>
                            <th class="px-4 py-4 text-center">Envío</th>
                            <th class="px-4 py-4 text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        <?php foreach($ventas as $v): 
                            $saldo_inicial = $v->total_venta - $v->comision_delivery;
                            $saldo = $saldo_inicial - $v->total_pagado;

                            $badge_pago = ($v->estado_pago == 'Completado')
                                ? 'bg-green-100 text-green-700'
                                : (($v->estado_pago == 'Parcial')
                                ? 'bg-yellow-100 text-yellow-700'
                                : 'bg-red-100 text-red-700');

                            $badge_envio = ($v->estado_envio == 'Enviado' || $v->estado_envio == 'Entregado')
                                ? 'bg-blue-100 text-blue-700'
                                : (($v->estado_envio == 'Aprobado')
                                ? 'bg-cyan-100 text-cyan-700'
                                : 'bg-gray-200 text-gray-700');
                        ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-4" data-order="<?= strtotime($v->fecha) ?>" data-label="ID / Fecha">
                                <div class="font-bold text-slate-900">#<?= $v->id ?></div>
                                <div class="text-[10px] text-slate-400 font-medium"><?= date('d/m/Y H:i', strtotime($v->fecha)) ?></div>
                            </td>

                            <?php if ($this->session->userdata('rol') != 'distribuidor'): ?>
                                <td class="px-4 py-4" data-label="Distribuidor">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-[10px]">
                                            <?= substr($v->nombre ?? 'D', 0, 1) ?>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-700 text-xs"><?= $v->nombre ?></span>
                                            <span class="text-[9px] text-slate-400 uppercase font-black tracking-tighter">Bolivia</span>
                                        </div>
                                    </div>
                                </td>
                            <?php endif; ?>

                            <td class="px-4 py-4 text-center" data-label="Tipo">
                                <?php if($v->tipo_venta == 'ENVIO'): ?>
                                    <span class="bg-purple-50 text-purple-600 border border-purple-100 px-2 py-0.5 rounded text-[10px] font-black">ENVÍO</span>
                                <?php else: ?>
                                    <span class="bg-slate-50 text-slate-600 border border-slate-100 px-2 py-0.5 rounded text-[10px] font-black">DELIVERY</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-4 py-4" data-label="Celular">
                                <div class="font-bold text-slate-700 text-xs"><?= $v->celular_cliente ?: 'N/A' ?></div>
                                <?php if(!empty($v->destino)): ?>
                                    <div class="text-[9px] text-blue-500 font-bold flex items-center gap-1">
                                        <i class="fas fa-truck-loading text-[8px]"></i> <?= $v->destino ?>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td class="px-4 py-4 text-right font-bold text-slate-700" data-label="Total Venta">
                                <span class="text-[10px] text-slate-400 mr-1">Bs.</span><?= number_format($v->total_venta, 2) ?>
                            </td>

                            <td class="px-4 py-4 text-right text-slate-500 font-medium" data-label="Comisión">
                                <span class="text-[10px] text-slate-300 mr-1">Bs.</span><?= number_format($v->comision_delivery, 2) ?>
                            </td>

                            <td class="px-4 py-4 text-right font-black <?= ($saldo > 0) ? 'text-red-500' : 'text-slate-400' ?>" data-label="Saldo">
                                <span class="text-[10px] opacity-50 mr-1">Bs.</span><?= number_format($saldo, 2) ?>
                            </td>

                            <td class="px-4 py-4 text-right text-emerald-600 font-bold" data-label="Pagado">
                                <span class="text-[10px] text-emerald-300 mr-1">Bs.</span><?= number_format($v->total_pagado, 2) ?>
                            </td>

                            <td class="px-4 py-4 text-center" data-label="Pago">
                                <span class="px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-tight <?= $badge_pago ?>">
                                    <?= $v->estado_pago ?>
                                </span>
                            </td>

                            <td class="px-4 py-4 text-center" data-label="Envío">
                                <span class="px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-tight <?= $badge_envio ?>">
                                    <?= $v->estado_envio ?>
                                </span>
                            </td>

                            <td class="px-4 py-4 text-center" data-label="Acciones">
                                <div class="flex justify-center gap-1.5">
                                    <button class="btn-ver-detalle bg-white border border-slate-200 text-sky-500 hover:bg-sky-50 p-2 rounded-lg transition-all shadow-sm" data-id="<?= $v->id ?>" title="Ver Detalle">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <?php if ($this->session->userdata('rol') != 'distribuidor'): ?>
                                        <a href="<?= base_url('ventas_bolivia/editar_venta/'.$v->id) ?>" class="bg-white border border-slate-200 text-amber-500 hover:bg-amber-50 p-2 rounded-lg transition-all shadow-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button class="bg-white border border-slate-200 text-emerald-500 hover:bg-emerald-50 p-2 rounded-lg transition-all shadow-sm" onclick="abrirModalAbono(<?= $v->id ?>,'<?= $v->nombre ?>',<?= $saldo ?>)" title="Abonar">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </button>

                                        <?php if ($v->estado_envio !== 'Aprobado'): ?>
                                            <button class="bg-white border border-slate-200 text-indigo-500 hover:bg-indigo-50 p-2 rounded-lg transition-all shadow-sm" onclick="cambiarEstadoEnvio(<?= $v->id ?>)" title="Aprobar">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>

            </div>

        </div>

    </section>

</div>

<div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg shadow-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fas fa-file-invoice-dollar mr-2"></i> Detalle de la Venta</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <h6 class="text-primary font-weight-bold"><i class="fas fa-box-open"></i> Productos del Pedido</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover border">
                        <thead class="bg-light">
                            <tr>
                                <th>Descripción</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="contenido-detalle-prod"></tbody>
                    </table>
                </div>

                <?php if ($this->session->userdata('rol') != 'distribuidor'): ?>
                    <hr>
                    <h6 class="text-success font-weight-bold"><i class="fas fa-history"></i> Historial de Abonos</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped border">
                            <thead class="bg-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Método</th>
                                    <th>Nota / Referencia</th>
                                    <th class="text-right">Monto</th>
                                </tr>
                            </thead>
                            <tbody id="contenido-detalle-pagos"></tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
// (Aquí iría tu inicialización de DataTable y ver_detalle_ajax que ya tienes)

async function abrirModalAbono(id, nombre, saldoMax) {
    if(saldoMax <= 0) {
        Swal.fire('Venta Pagada', 'No hay saldo pendiente por cobrar.', 'success');
        return;
    }

    const { value: formValues } = await Swal.fire({
        title: 'Registrar Abono',
        html:
            `<div class="text-left">` +
                `<p class="text-center mb-3">Cliente: <b>${nombre}</b></p>` +
                
                `<label class="swal2-label-custom">Monto a cobrar</label>` +
                `<input id="swal-monto" class="swal2-input swal-input-custom" type="number" step="0.01" max="${saldoMax}" value="${saldoMax.toFixed(2)}">` +
                
                `<label class="swal2-label-custom">Método de pago</label>` +
                `<select id="swal-metodo" class="swal2-input swal-input-custom">
                    <option value="Efectivo">Efectivo</option>
                    <option value="Deposito">Desposito</option>
                    <option value="Otro">Otro</option>
                </select>` +

                `<label class="swal2-label-custom">Nota o Referencia (Opcional)</label>` +
                `<input id="swal-nota" class="swal2-input swal-input-custom" type="text" placeholder="Ej: Operación 123456">` +
            `</div>`,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Guardar Pago',
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const monto = document.getElementById('swal-monto').value;
            const metodo = document.getElementById('swal-metodo').value;
            const nota = document.getElementById('swal-nota').value;

            if (!monto || monto <= 0 || monto > (saldoMax + 0.05)) {
                Swal.showValidationMessage('El monto es inválido o supera el saldo');
                return false;
            }
            return { monto: monto, metodo: metodo, nota: nota };
        }
    });

    if (formValues) {
        $.post('<?= base_url("ventas_bolivia/registrar_abono_ajax") ?>', {
            id_venta: id,
            monto: formValues.monto,
            metodo: formValues.metodo,
            nota: formValues.nota // Enviamos la nota al controlador
        }, function(res) {
            location.reload();
        });
    }
}

function cambiarEstadoEnvio(id) {
    Swal.fire({
        title: '¿Aprobar Pedido?',
        text: "Se verificará el stock disponible antes de aprobar.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-check mr-2"></i> Sí, Aprobar ahora',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        reverseButtons: true,
        preConfirm: () => {
            return fetch(`<?= base_url('ventas_bolivia/verificar_stock_pedido/') ?>${id}`)
                .then(response => {
                    if (!response.ok) throw new Error('Error en el servidor');
                    return response.json();
                })
                .catch(error => {
                    Swal.showValidationMessage(`Error: ${error.message}`);
                });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            // Caso Éxito
            if (result.value && result.value.success) {
                ejecutarCambioEstado(id, 'Aprobado');
            } 
            // Caso Error (Muestra el formato exacto que pediste)
            else if (result.value && !result.value.success) {
                Swal.fire({
                    title: '¡Sin Stock Suficiente!',
                    html: `<p class="text-sm text-gray-700">${result.value.mensaje}</p>`,
                    icon: 'error',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Cerrar'
                });
            }
        }
    });
}

function ejecutarCambioEstado(id, nuevoEstado) {
    Swal.fire({ 
        title: 'Actualizando inventario...', 
        html: 'Por favor espere mientras se procesa la salida de stock.',
        allowOutsideClick: false, 
        didOpen: () => { Swal.showLoading(); } 
    });
    
    // Cambiado a la ruta de ventas general o la que corresponda a tu controlador actual
    $.post('<?= base_url("ventas_bolivia/actualizar_estado_envio") ?>', {
        id: id,
        estado: nuevoEstado
    }, function(res) {
        // Alerta de éxito antes de recargar para que el usuario sepa que terminó
        Swal.fire({
            icon: 'success',
            title: '¡Pedido Aprobado!',
            text: 'El stock ha sido descontado correctamente.',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            location.reload();
        });
    });
}

// Esperar a que el documento esté listo
document.addEventListener("DOMContentLoaded", function() {
    console.log("1. Iniciando reparación forzada de DataTables...");

    // Función para inyectar scripts dinámicamente
    function inyectarScript(url) {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = url;
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }

    async function repararYEjecutar() {
        try {
            // Esperamos un momento por si jQuery carga del sistema
            if (typeof jQuery === 'undefined') {
                console.log("jQuery no hallado, cargando desde CDN...");
                await inyectarScript("https://code.jquery.com/jquery-3.6.0.min.js");
            }

            console.log("Cargando librerías de DataTables directamente...");
            await inyectarScript("https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js");
            await inyectarScript("https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js");

            const $ = jQuery;
            console.log("¡Todo listo! Inicializando tabla...");

            if ($.fn.DataTable.isDataTable('#tabla-listado-ventas')) {
                $('#tabla-listado-ventas').DataTable().destroy();
            }

            $('#tabla-listado-ventas').DataTable({
                "paging": true,
                "searching": false,
                "lengthChange": false, 
                "order": [[0, "desc"]],
                "language": { "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" }
            });

            // Re-vincular tus eventos de detalle aquí
            jQuery(document).on('click', '.btn-ver-detalle', function() {
                let id = jQuery(this).data('id');
                jQuery('#contenido-detalle-prod').html('<tr><td colspan="3" class="text-center">Cargando...</td></tr>');
                jQuery('#contenido-detalle-pagos').html('<tr><td colspan="4" class="text-center">Cargando...</td></tr>');
                jQuery('#modalDetalle').modal('show');

                jQuery.get('<?= base_url("ventas_bolivia/ver_detalle_ajax/") ?>' + id, function(data) {
                    try {
                        let res = JSON.parse(data);
                        let htmlProd = '';
                        res.productos.forEach(p => {
                            htmlProd += `<tr>
                                <td>${p.producto_nombre} <small class="text-muted">(${p.color} - ${p.talla})</small></td>
                                <td class="text-center">${p.cantidad}</td>
                                <td class="text-right">Bs. ${parseFloat(p.subtotal).toFixed(2)}</td>
                            </tr>`;
                        });
                        jQuery('#contenido-detalle-prod').html(htmlProd || '<tr><td colspan="3" class="text-center">No hay productos</td></tr>');

                        if (jQuery('#contenido-detalle-pagos').length > 0) {
                            let htmlPagos = '';
                            res.pagos.forEach(p => {
                                htmlPagos += `<tr>
                                    <td>${p.fecha_pago}</td>
                                    <td><span class="badge badge-secondary">${p.metodo_pago}</span></td>
                                    <td><small>${p.nota ? p.nota : '-'}</small></td>
                                    <td class="text-success font-weight-bold text-right">Bs. ${parseFloat(p.monto).toFixed(2)}</td>
                                </tr>`;
                            });
                            jQuery('#contenido-detalle-pagos').html(htmlPagos || '<tr><td colspan="4" class="text-center">No hay pagos</td></tr>');
                        }
                    } catch (err) {
                        console.error("Error JSON:", err);
                    }
                });
            });

            console.log("PROCESO COMPLETADO: Paginado debería ser visible.");

        } catch (error) {
            console.error("No se pudieron cargar las librerías: ", error);
        }
    }

    repararYEjecutar();
});
</script>