<style>
/* SweetAlert inputs */
.swal2-label-custom{
    @apply block mt-4 font-bold text-gray-700 text-left pl-10;
}
.swal-input-custom{
    @apply block mx-auto w-4/5;
}
</style>

<div class="md:ml-64 min-h-screen bg-slate-50 pt-20">

    <!-- HEADER -->
    <section class="px-4 md:px-8 py-5 md:py-6 border-b bg-white shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 md:gap-6">
            
            <h1 class="text-xl md:text-3xl font-bold text-slate-800 flex items-center gap-3">
                <i class="fas fa-list text-slate-600"></i>
                Listado de Ventas y Pedidos
            </h1>

            <a href="<?= base_url('ventas/nueva_cotizacion') ?>"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-5 md:py-2.5 rounded-lg shadow text-sm md:text-base">
                <i class="fas fa-plus"></i>
                Nueva Venta
            </a>

        </div>
    </section>


    <!-- TABLA -->
    <section class="p-4 md:p-8">

        <div class="bg-white rounded-xl shadow border border-slate-200 p-4 md:p-6">

            <!-- contenedor responsive -->
            <div class="w-full md:overflow-visible overflow-x-auto">

                <table id="tabla-listado-ventas" class="min-w-[900px] w-full text-sm text-left">

                    <thead class="bg-slate-100 text-slate-700 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Fecha</th>
                            <th class="px-4 py-3">Cliente</th>
                            <th class="px-4 py-3">Destino</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Pagado</th>
                            <th class="px-4 py-3">Saldo</th>
                            <th class="px-4 py-3">Pago</th>
                            <th class="px-4 py-3">Estado Compra</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        <?php foreach($ventas as $v): 
                            $saldo = $v->total_venta - $v->total_pagado;

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

                        <tr class="hover:bg-slate-50">

                            <td class="px-4 py-3 whitespace-nowrap whitespace-nowrap">
                                <?= date('d/m/Y h:i A', strtotime($v->fecha)) ?>
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="font-semibold text-slate-800">
                                    <?= $v->nombre ?>
                                </div>
                                <div class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-id-card"></i>
                                    <?= $v->dni ?>
                                </div>
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-700">
                                <?= $v->ubicacion ?>
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap whitespace-nowrap">
                                S/ <?= number_format($v->total_venta,2) ?>
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap text-green-600 font-medium whitespace-nowrap">
                                S/ <?= number_format($v->total_pagado,2) ?>
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap font-bold <?= ($saldo > 0) ? 'text-red-600' : 'text-gray-500' ?> whitespace-nowrap">
                                S/ <?= number_format($saldo,2) ?>
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap">
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

                                <div class="flex justify-center gap-1 md:gap-2">

                                    <button type="button"
                                        class="btn-ver-detalle bg-sky-500 hover:bg-sky-600 text-white px-3 py-1.5 rounded text-sm"
                                        data-id="<?= $v->id ?>"
                                        title="Ver productos y pagos">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <a href="<?= base_url('ventas/editar_venta/'.$v->id) ?>"
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

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
                    <option value="Yape">Yape</option>
                    <option value="Plin">Plin</option>
                    <option value="Transferencia BCP">Transferencia BCP</option>
                    <option value="Transferencia BBVA">Transferencia BBVA</option>
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
        $.post('<?= base_url("ventas/registrar_abono_ajax") ?>', {
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
        showLoaderOnConfirm: true, // Muestra un spinner mientras verifica
        preConfirm: () => {
            // 1. Primero consultamos al servidor si hay stock suficiente para este pedido
            return fetch(`<?= base_url('ventas/verificar_stock_pedido/') ?>${id}`)
                .then(response => {
                    if (!response.ok) { throw new Error(response.statusText) }
                    return response.json();
                })
                .catch(error => {
                    Swal.showValidationMessage(`Error de red: ${error}`);
                });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            // 2. Si el servidor responde que todo está OK
            if (result.value.success) {
                ejecutarCambioEstado(id, 'Aprobado');
            } else {
                // 3. Si no hay stock, mostramos el error detallado que mande el servidor
                Swal.fire({
                    title: '¡Sin Stock Suficiente!',
                    html: `<p class="text-sm">${result.value.mensaje}</p>`,
                    icon: 'error',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
    });
}

function ejecutarCambioEstado(id, nuevoEstado) {
    Swal.fire({ title: 'Procesando...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
    
    $.post('<?= base_url("ventas/actualizar_estado_envio") ?>', {
        id: id,
        estado: nuevoEstado
    }, function(res) {
        location.reload();
    });
}


document.addEventListener("DOMContentLoaded", function() {
    console.log("1. DOM cargado. Iniciando carga dinámica de DataTables...");
    
    // Función para cargar scripts dinámicamente si fallan
    function cargarScript(url, callback) {
        let script = document.createElement("script");
        script.type = "text/javascript";
        script.src = url;
        script.onload = callback;
        document.head.appendChild(script);
    }

    let intentos = 0;
    let checkjQuery = setInterval(function() {
        intentos++;
        const jqueryCargado = (typeof jQuery !== 'undefined');

        if (jqueryCargado) {
            // Si jQuery existe pero DataTable no, intentamos cargarlo manualmente
            if (!jQuery.fn.DataTable) {
                console.warn("jQuery detectado, pero DataTable no. Forzando carga de script...");
                clearInterval(checkjQuery);
                cargarScript("https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js", function() {
                    cargarScript("https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js", function() {
                        console.log("Scripts inyectados manualmente. Inicializando...");
                        inicializarTodo();
                    });
                });
            } else {
                clearInterval(checkjQuery);
                console.log("2. Dependencias listas desde el inicio.");
                inicializarTodo();
            }
        }

        if (intentos > 30) {
            clearInterval(checkjQuery);
            console.error("ERROR: No se encontró jQuery tras varios intentos.");
        }
    }, 200);

    function inicializarTodo() {
        const tabla = jQuery('#tabla-listado-ventas');
        if (tabla.length === 0) return;

        // Si ya estaba inicializada por otro script, la destruimos para evitar errores
        if (jQuery.fn.DataTable.isDataTable('#tabla-listado-ventas')) {
            tabla.DataTable().destroy();
        }

        try {
            tabla.DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 10,
                "order": [[0, "desc"]],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                }
            });
            console.log("4. ¡DataTable inicializado correctamente!");
        } catch (e) {
            console.error("Error en inicialización:", e);
        }

        // Delegación de eventos para el botón Ver Detalle
        jQuery(document).on('click', '.btn-ver-detalle', function() {
            let id = jQuery(this).data('id');
            jQuery('#contenido-detalle-prod').html('<tr><td colspan="3" class="text-center">Cargando...</td></tr>');
            jQuery('#contenido-detalle-pagos').html('<tr><td colspan="4" class="text-center">Cargando...</td></tr>');
            jQuery('#modalDetalle').modal('show');

            jQuery.get('<?= base_url("ventas/ver_detalle_ajax/") ?>' + id, function(data) {
                try {
                    let res = JSON.parse(data);
                    let htmlProd = '';
                    res.productos.forEach(p => {
                        htmlProd += `<tr>
                            <td>${p.producto_nombre} <small class="text-muted">(${p.color} - ${p.talla})</small></td>
                            <td class="text-center">${p.cantidad}</td>
                            <td class="text-right">S/ ${parseFloat(p.subtotal).toFixed(2)}</td>
                        </tr>`;
                    });
                    jQuery('#contenido-detalle-prod').html(htmlProd || '<tr><td colspan="3" class="text-center">No hay productos</td></tr>');

                    let htmlPagos = '';
                    res.pagos.forEach(p => {
                        htmlPagos += `<tr>
                            <td>${p.fecha_pago}</td>
                            <td><span class="badge badge-secondary">${p.metodo_pago}</span></td>
                            <td><small>${p.nota ? p.nota : '-'}</small></td>
                            <td class="text-success font-weight-bold text-right">S/ ${parseFloat(p.monto).toFixed(2)}</td>
                        </tr>`;
                    });
                    jQuery('#contenido-detalle-pagos').html(htmlPagos || '<tr><td colspan="4" class="text-center">No hay pagos</td></tr>');
                } catch (err) {
                    console.error("Error JSON:", err);
                }
            });
        });
    }
});
</script>