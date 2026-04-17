<div class="md:ml-64 min-h-screen bg-slate-50 pt-20">

    <section class="px-4 md:px-8 py-5 md:py-6 border-b bg-white shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h1 class="text-xl md:text-3xl font-bold text-slate-800 flex items-center gap-3">
                <i class="fas fa-money-bill-wave text-green-600"></i> Pagos de Distribuidores
            </h1>
            <div class="text-sm text-slate-500">
                <i class="fas fa-calendar-alt"></i> Hoy: <?= date('d/m/Y') ?>
            </div>
        </div>
    </section>

    <section class="p-4 md:p-8 space-y-6">
        <div class="bg-white rounded-xl shadow p-4 border border-slate-200">
            <h3 class="text-lg font-semibold mb-4 text-slate-800 flex items-center gap-2">
                <i class="fas fa-filter text-blue-500 text-sm"></i> Filtros de Reporte
            </h3>
            <form action="<?= base_url('pagos_distribuidores') ?>" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

                    <div class="md:col-span-3">
                        <label class="block mb-1 font-medium text-slate-700 text-sm">Distribuidor:</label>
                        <select name="distribuidor_id" class="form-control select2 w-full">
                            <option value="">-- Todos los Distribuidores --</option>
                            <?php foreach($distribuidores as $d): ?>
                                <option value="<?= $d->id ?>" <?= $this->input->get('distribuidor_id') == $d->id ? 'selected' : '' ?>>
                                    <?= $d->nombre ?> (NIT: <?= $d->nit ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-1 font-medium text-slate-700 text-sm">Desde:</label>
                        <input type="date" name="fecha_inicio" value="<?= $this->input->get('fecha_inicio') ?>" 
                               class="w-full border border-slate-300 rounded px-3 py-1.5 focus:ring-2 focus:ring-blue-400 outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-1 font-medium text-slate-700 text-sm">Hasta:</label>
                        <input type="date" name="fecha_fin" value="<?= $this->input->get('fecha_fin') ?>" 
                               class="w-full border border-slate-300 rounded px-3 py-1.5 focus:ring-2 focus:ring-blue-400 outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-1 font-medium text-slate-700 text-sm">Método:</label>
                        <select name="metodo_pago" class="w-full border border-slate-300 rounded px-3 py-1.5 focus:ring-2 focus:ring-blue-400 outline-none">
                            <option value="">-- Todos --</option>
                            <option value="Efectivo" <?= $this->input->get('metodo_pago') == 'Efectivo' ? 'selected' : '' ?>>Efectivo</option>
                            <option value="Transferencia" <?= $this->input->get('metodo_pago') == 'Transferencia' ? 'selected' : '' ?>>Transferencia</option>
                            <option value="QR" <?= $this->input->get('metodo_pago') == 'QR' ? 'selected' : '' ?>>QR</option>
                        </select>
                    </div>

                    <div class="md:col-span-3 flex gap-2 mt-auto">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        <a href="<?= base_url('pagos_distribuidores') ?>" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-sync-alt"></i> Limpiar
                        </a>
                    </div>

                </div>
            </form>
        </div>
<div class="bg-white rounded-xl shadow p-4 border border-slate-200">
    <div class="w-full md:overflow-visible overflow-x-auto">
        <table id="tabla-pagos" class="min-w-full text-sm text-left">
            <thead class="bg-slate-100 text-slate-700 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-4 py-3">Fecha de Pago</th>
                    <th class="px-4 py-3">Distribuidor</th>
                    <th class="px-4 py-3 text-center">Ref. Venta</th>
                    <th class="px-4 py-3">Método</th>
                    <th class="px-4 py-3">Nota/Observación</th>
                    <th class="px-4 py-3 text-right">Monto</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php 
                $total_recaudado = 0;
                if(!empty($pagos)): 
                    foreach($pagos as $p): 
                        $total_recaudado += $p->monto; // Nombre de columna real
                ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3 whitespace-nowrap font-medium">
                        <?= date('d/m/Y', strtotime($p->fecha_pago)) ?><br>
                        <span class="text-xs text-slate-400"><?= date('H:i', strtotime($p->fecha_pago)) ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-bold text-slate-800"><?= $p->distribuidor_nombre ?? 'CLIENTE S/N' ?></div>
                        <div class="text-xs text-slate-500">NIT: <?= $p->venta_nit ?></div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-100 font-mono">
                            #<?= $p->id_venta ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold 
                            <?= $p->metodo_pago == 'Efectivo' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700' ?>">
                            <?= $p->metodo_pago ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-slate-500 italic text-xs">
                        <?= $p->nota ? $p->nota : '<span class="text-slate-300">Sin notas</span>' ?>
                    </td>
                    <td class="px-4 py-3 text-right font-bold text-slate-900 text-base">
                        <?= number_format($p->monto, 2) ?> <small>Bs.</small>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot class="bg-slate-800 text-white">
                <tr>
                    <td colspan="5" class="px-4 py-3 text-right font-bold uppercase tracking-wider">Total Recaudado:</td>
                    <td class="px-4 py-3 text-right font-bold text-lg text-yellow-400">
                        <?= number_format($total_recaudado, 2) ?> Bs.
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    var $j = jQuery.noConflict();
    $j(document).ready(function() {
        
        // 1. Select2
        $j('.select2').select2({
            theme: 'bootstrap4',
            allowClear: true,
            placeholder: '-- Seleccionar --'
        });

        // 2. DataTables
        if ($j.isFunction($j.fn.DataTable)) {
            $j('#tabla-pagos').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true, // Activado para búsqueda rápida en los resultados filtrados
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                },
                "order": [[0, "desc"]] // Por fecha de pago
            });
        }
    });
</script>