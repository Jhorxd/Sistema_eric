<div class="md:ml-64 min-h-screen bg-slate-50 pt-20">

    <section class="px-4 md:px-8 py-5 md:py-6 border-b bg-white shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h1 class="text-xl md:text-3xl font-bold text-slate-800 flex items-center gap-3">
                <i class="fas fa-user-tie text-blue-600"></i> Pagos Alfredo
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
            <form action="<?= base_url('pagos_alfredo') ?>" method="POST" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-4">
                        <label class="block mb-1 font-medium text-slate-700 text-sm">Desde:</label>
                        <input type="date" name="fecha_inicio" value="<?= $fecha_inicio ?>" 
                               class="w-full border border-slate-300 rounded px-3 py-1.5 focus:ring-2 focus:ring-blue-400 outline-none text-sm">
                    </div>
                    <div class="md:col-span-4">
                        <label class="block mb-1 font-medium text-slate-700 text-sm">Hasta:</label>
                        <input type="date" name="fecha_fin" value="<?= $fecha_fin ?>" 
                               class="w-full border border-slate-300 rounded px-3 py-1.5 focus:ring-2 focus:ring-blue-400 outline-none text-sm">
                    </div>
                    <div class="md:col-span-4 flex gap-2 mt-auto">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-search text-xs"></i> Filtrar
                        </button>
                        <a href="<?= base_url('pagos_alfredo') ?>" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-2 rounded-lg transition-colors flex items-center justify-center gap-2 text-sm">
                            <i class="fas fa-sync-alt text-xs"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow p-4 border border-slate-200">
            <div class="w-full md:overflow-visible overflow-x-auto">
                <table id="tabla-pagos-alfredo" class="table min-w-full text-sm text-left">
                    <thead class="bg-slate-100 text-slate-700 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-4 py-3">ID</th> 
                            <th class="px-4 py-3 text-center">Ref. Pedido</th> 
                            <th class="px-4 py-3">Método</th> 
                            <th class="px-4 py-3">Fecha de Pago</th> 
                            <th class="px-4 py-3">Nota/Observación</th> 
                            <th class="px-4 py-3 text-right">Monto Pagado</th> 
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php 
                        $total_acumulado = 0;
                        if(!empty($pagos)): 
                            foreach($pagos as $p): 
                                $total_acumulado += $p->monto_pagado;
                        ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 font-mono text-slate-400">#<?= $p->id ?></td>
                            <td class="px-4 py-3 text-center">
                                <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-100 font-mono text-xs">
                                    #<?= $p->id_pedido_alfredo ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                    <?= $p->metodo_pago == 'Efectivo' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700' ?>">
                                    <?= $p->metodo_pago ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="font-medium text-slate-700"><?= date('d/m/Y', strtotime($p->fecha_pago)) ?></div>
                                <div class="text-xs text-slate-400"><?= date('H:i', strtotime($p->fecha_pago)) ?></div>
                            </td>
                            <td class="px-4 py-3 text-slate-500 italic text-xs">
                                <?= $p->observacion ? $p->observacion : '<span class="text-slate-300 italic">NULL</span>' ?>
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-slate-900 text-base">
                                <?= number_format($p->monto_pagado, 2) ?> <small class="text-xs font-normal">Bs.</small>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="bg-slate-800 text-white">
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-right font-bold uppercase tracking-wider text-xs border-none">Total Recaudado:</td>
                            <td class="px-4 py-4 text-right font-bold text-lg text-yellow-400 border-none">
                                <?= number_format($total_acumulado, 2) ?> <span class="text-xs font-normal">Bs.</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    var $j = jQuery.noConflict();
    $j(document).ready(function() {
        if ($j.isFunction($j.fn.DataTable)) {
            $j('#tabla-pagos-alfredo').DataTable({
                "paging": true,          // PAGINADO ACTIVADO
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                "pageLength": 10,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                },
                "order": [[0, "desc"]] 
            });
        }
    });
</script>