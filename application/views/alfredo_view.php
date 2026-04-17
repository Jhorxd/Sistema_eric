<div class="md:ml-64 min-h-screen bg-slate-50 pt-20">

    <!-- HEADER -->
    <section class="px-4 md:px-8 py-5 md:py-6 border-b bg-white shadow-sm">
        <h1 class="text-xl md:text-3xl font-bold text-slate-800 flex items-center gap-3">
            <i class="fas fa-users text-slate-600"></i> Depositos a Alfredo
        </h1>
    </section>

    <!-- FILTROS -->
    <section class="p-4 md:p-8 space-y-6">

        <div class="bg-white rounded-xl shadow p-4">
            <h3 class="text-lg font-semibold mb-4 text-slate-800 flex items-center gap-2">
                <i class="fas fa-filter text-slate-600"></i> Filtros de búsqueda
            </h3>
            <form method="GET" action="<?= base_url('liquidaciones') ?>" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

                    <div class="md:col-span-3">
                        <label class="block mb-1 font-medium text-slate-700">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" value="<?= $f_inicio ?? '' ?>" 
                               class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block mb-1 font-medium text-slate-700">Fecha Fin</label>
                        <input type="date" name="fecha_fin" value="<?= $f_fin ?? '' ?>" 
                               class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    <div class="md:col-span-2 flex gap-2 items-end">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg flex items-center justify-center gap-2">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        <a href="<?= base_url('liquidaciones') ?>" class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 rounded-lg flex items-center justify-center gap-2">
                            <i class="fas fa-eraser"></i> Limpiar
                        </a>
                    </div>

                </div>
            </form>
        </div>

        <!-- TABLA DE LIQUIDACIONES -->
        <div class="bg-white rounded-xl shadow p-4 overflow-x-auto">
            <table class="min-w-[900px] w-full text-sm text-left divide-y">
                <thead class="bg-slate-100 text-slate-700 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3">ID / Fecha</th>
                        <th class="px-4 py-3">Cliente Celular</th>
                        <th class="px-4 py-3">Destino</th>
                        <th class="px-4 py-3">Monto Alfredo</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    <?php foreach($pendientes as $p): ?>
                    <tr class="hover:bg-slate-50">

                        <td class="px-4 py-3">
                            <strong>#<?= $p->id_venta ?></strong><br>
                            <small class="text-slate-500">
                                <?= date('d/m/Y H:i', strtotime($p->fecha_pedido)) ?>
                            </small>
                        </td>
                        <td class="px-4 py-3">
                            <?= $p->celular ?>
                        </td>

                        <td class="px-4 py-3">
                            <?= $p->destino ?>
                        </td>

                        <td class="px-4 py-3 font-semibold text-red-600">
                            Bs. <?= number_format($p->monto_alfredo,2) ?>
                        </td>

                        <td class="px-4 py-3">
                            <?php if($p->estado == 'Pendiente'): ?>
                                <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded text-xs font-bold">
                                    Pendiente
                                </span>
                            <?php else: ?>
                                <span class="bg-green-200 text-green-800 px-2 py-1 rounded text-xs font-bold">
                                    Pagado
                                </span>
                            <?php endif; ?>
                        </td>

                        <td class="px-4 py-3">
                            <?php if($p->estado == 'Pendiente'): ?>

                            <button 
                                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-1 px-2 rounded flex items-center justify-center gap-2 text-sm" 
                                onclick="abrirModalPago(<?= $p->id ?>, '<?= $p->cliente ?>', <?= $p->monto_alfredo ?>)">
                                
                                <i class="fas fa-hand-holding-usd"></i> Cobrar
                            </button>

                            <?php endif; ?>
                            <button 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1 px-2 rounded flex items-center justify-center gap-2 text-sm"
                                onclick="verHistorialPagos(<?= $p->id ?>, '<?= $p->cliente ?>')">
                                
                                <i class="fas fa-history"></i> Ver Pagos
                            </button>
                        </td>
                        

                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </section>
</div>

<!-- MODAL PAGO -->
<div class="modal fade" id="modalPago" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('liquidaciones/registrar_pago_alfredo') ?>" method="POST">
            <div class="modal-content rounded-xl overflow-hidden">

                <div class="bg-green-600 text-white px-4 py-3 flex justify-between items-center">
                    <h5 class="font-semibold text-lg">Registrar Pago a Alfredo</h5>
                    <button type="button" class="text-white text-2xl" data-dismiss="modal">&times;</button>
                </div>

                <div class="p-4 space-y-4">

                    <!-- ID DEL PEDIDO ALFREDO -->
                    <input type="hidden" name="id_pedido" id="pago_id_pedido">

                    <p>
                        Cliente: <strong id="pago_nombre"></strong>
                    </p>

                    <div>
                        <label class="block mb-1 font-medium text-slate-700">
                            Monto del Abono (Bs.)
                        </label>

                        <input 
                            type="number" 
                            step="0.01" 
                            name="monto_pago" 
                            id="pago_monto"
                            class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                            required
                        >
                    </div>

                    <div>
                        <label class="block mb-1 font-medium text-slate-700">
                            Método
                        </label>

                        <select 
                            name="metodo" 
                            class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400"
                        >
                            <option value="Efectivo">Efectivo</option>
                            <option value="Transferencia">Transferencia / QR</option>
                        </select>
                    </div>

                </div>

                <div class="px-4 py-3 bg-slate-50 flex justify-end">
                    <button 
                        type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2"
                    >
                        Confirmar Pago
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- MODAL HISTORIAL -->
<div class="modal fade" id="modalHistorial" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-xl overflow-hidden">
            <div class="bg-blue-600 text-white px-4 py-3 flex justify-between items-center">
                <h5 class="font-semibold text-lg">Historial de Pagos - <span id="historial_nombre"></span></h5>
                <button type="button" class="text-white text-2xl" data-dismiss="modal">&times;</button>
            </div>
            <div class="p-4 overflow-x-auto">
                <table class="min-w-[500px] w-full text-sm divide-y">
                    <thead class="bg-slate-100 text-slate-700 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-4 py-2">Fecha/Hora</th>
                            <th class="px-4 py-2">Método</th>
                            <th class="px-4 py-2 text-right">Monto Pagado</th>
                        </tr>
                    </thead>
                    <tbody id="tabla_historial" class="divide-y"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Función para abrir modal de pago
function abrirModalPago(id, cliente, saldoPendiente) {
    // Limpiamos errores previos al abrir
    $('#error_monto').addClass('hidden');
    $('button[type="submit"]').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');

    $('#pago_id_pedido').val(id);
    $('#pago_nombre').text(cliente);
    
    // Configuramos el input
    const inputMonto = $('#pago_monto');
    inputMonto.val(saldoPendiente);      // Sugerimos liquidar la deuda total
    inputMonto.attr('max', saldoPendiente); // Límite máximo para el navegador
    
    // Mostramos el saldo en el label (si agregaste el span id="label_saldo")
    $('#label_saldo').text("(Máximo: Bs. " + parseFloat(saldoPendiente).toFixed(2) + ")");

    $('#modalPago').modal('show');
}

// Validación en tiempo real mientras el usuario escribe
$(document).on('input', '#pago_monto', function() {
    let montoIngresado = parseFloat($(this).val()) || 0;
    let montoMaximo = parseFloat($(this).attr('max')) || 0;
    let btnSubmit = $(this).closest('form').find('button[type="submit"]');
    let errorMsg = $('#error_monto');

    // Si el monto es mayor al saldo o menor/igual a cero
    if (montoIngresado > montoMaximo || montoIngresado <= 0) {
        errorMsg.removeClass('hidden');
        btnSubmit.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
    } else {
        errorMsg.addClass('hidden');
        btnSubmit.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
    }
});

// Función AJAX para ver historial de pagos
function verHistorialPagos(id, nombre) {

    $('#historial_nombre').text(nombre);

    $('#tabla_historial').html(
        '<tr><td colspan="3" class="text-center">Cargando historial...</td></tr>'
    );

    $('#modalHistorial').modal('show');

    $.get('<?= base_url("liquidaciones/obtener_historial_pagos/") ?>' + id, function(data) {

        let pagos = JSON.parse(data);
        let html = '';

        if (pagos.length > 0) {

            pagos.forEach(p => {

                html += `<tr>
                    <td>${p.fecha_pago}</td>
                    <td>${p.metodo_pago}</td>
                    <td class="text-right font-weight-bold">
                        Bs. ${parseFloat(p.monto_pagado).toFixed(2)}
                    </td>
                </tr>`;

            });

        } else {

            html = `
                <tr>
                    <td colspan="3" class="text-center text-gray-500">
                        No hay abonos registrados para este pedido.
                    </td>
                </tr>
            `;

        }

        $('#tabla_historial').html(html);

    });

}
</script>