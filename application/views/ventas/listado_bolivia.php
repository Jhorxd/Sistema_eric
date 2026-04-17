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
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-5 md:py-2.5 rounded-lg shadow text-sm md:text-base">
                <i class="fas fa-plus"></i>
                Nueva Venta
            </a>

        </div>
    </section>

    <!-- TABLA -->
    <section class="p-4 md:p-8">

        <div class="bg-white rounded-xl shadow border border-slate-200 p-4 md:p-6">

            <div class="w-full md:overflow-visible overflow-x-auto">

                <table id="tabla-listado-ventas" class="min-w-[900px] w-full text-sm text-left">

                    <thead class="bg-slate-100 text-slate-700 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-4 py-3">ID / Fecha</th>
                            <?php if ($this->session->userdata('rol') != 'distribuidor'): ?>
                                <th class="px-4 py-3">Distribuidor</th>
                            <?php endif; ?>
                            <th class="px-4 py-3 text-center">Tipo</th>
                            <th class="px-4 py-3">Celular de cliente</th>
                            <th class="px-4 py-3 text-right">Total Venta</th>
                            <th class="px-4 py-3">Comisión</th>
                            <th class="px-4 py-3">Por pagar</th>
                            <th class="px-4 py-3">Pagado</th>
                            <th class="px-4 py-3">Estado Pago</th>
                            <th class="px-4 py-3">Estado Envío</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">

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

                        <td class="px-4 py-3" data-order="<?= strtotime($v->fecha) ?>">
                            <strong>#<?= $v->id ?></strong><br>
                            <small class="text-slate-500">
                                <?= date('d/m/Y H:i', strtotime($v->fecha)) ?>
                            </small>
                        </td>

                        <?php if ($this->session->userdata('rol') != 'distribuidor'): ?>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                        <?= substr($v->nombre ?? 'D', 0, 1) ?>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-slate-700 leading-none"><?= $v->nombre ?></span>
                                        <small class="text-slate-500 italic mt-0.5">Bolivia</small>
                                    </div>
                                </div>
                            </td>
                        <?php endif; ?>

                            <td class="px-4 py-3 text-center">
                                <?php if($v->tipo_venta == 'ENVIO'): ?>
                                    <span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded text-[10px] font-bold">ENVIO</span>
                                <?php else: ?>
                                    <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-[10px] font-bold">DELIVERY</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-800">
                                    <?= $v->celular_cliente ?>
                                </div>
                                <?php if(!empty($v->destino)): ?>
                                    <div class="text-[10px] text-blue-600 font-bold"><i class="fas fa-truck"></i> <?= $v->destino ?></div>
                                <?php endif; ?>
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap">
                                Bs. <?= number_format($v->total_venta, 2) ?>
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap">
                                Bs. <?= number_format($v->comision_delivery, 2) ?>
                            </td>

                            <td class="px-4 py-3 font-bold <?= ($saldo > 0) ? 'text-red-600' : 'text-gray-500' ?>">
                                Bs. <?= number_format($saldo, 2) ?>
                            </td>


                            <td class="px-4 py-3 text-green-600 font-medium whitespace-nowrap">
                                Bs. <?= number_format($v->total_pagado, 2) ?>
                            </td>


                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold <?= $badge_pago ?>">
                                    <?= $v->estado_pago ?>
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold <?= $badge_envio ?>">
                                    <?= $v->estado_envio ?>
                                </span>
                            </td>

                            <td class="px-4 py-3">

                                <div class="flex justify-center gap-2">

                                    <button
                                        class="btn-ver-detalle bg-sky-500 hover:bg-sky-600 text-white px-3 py-1.5 rounded text-sm"
                                        data-id="<?= $v->id ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <a href="<?= base_url('ventas_bolivia/editar_venta/'.$v->id) ?>"
                                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded text-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-sm"
                                        onclick="abrirModalAbono(<?= $v->id ?>,'<?= $v->nombre ?>',<?= $saldo ?>)">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </button>

                                    <?php if ($v->estado_envio !== 'Aprobado'): ?>
                                    <button
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-sm"
                                        onclick="cambiarEstadoEnvio(<?= $v->id ?>,'<?= $v->estado_envio ?>')">
                                        <i class="fas fa-clipboard-check"></i>
                                    </button>
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