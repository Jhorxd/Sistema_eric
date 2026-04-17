<div class="md:ml-64 min-h-screen bg-slate-50 pt-20">

    <!-- HEADER -->
    <section class="px-4 md:px-8 py-5 md:py-6 border-b bg-white shadow-sm">
        <h1 class="text-xl md:text-3xl font-bold text-slate-800 flex items-center gap-3">
            <i class="fas fa-history text-slate-600"></i> Kardex de Inventario - Bolivia
        </h1>
    </section>

    <!-- FILTROS -->
    <section class="p-4 md:p-8 space-y-6">

<div class="bg-white rounded-xl shadow p-4">
    <h3 class="text-lg font-semibold mb-4 text-slate-800">Filtros de búsqueda</h3>
    <form action="<?= base_url('inventario/kardex_bolivia') ?>" method="GET" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-10 gap-4">

            <div class="md:col-span-2">
                <label class="block mb-1 font-medium text-slate-700">Desde:</label>
                <input type="date" name="fecha_inicio" value="<?= $this->input->get('fecha_inicio') ?>" 
                       class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="md:col-span-2">
                <label class="block mb-1 font-medium text-slate-700">Hasta:</label>
                <input type="date" name="fecha_fin" value="<?= $this->input->get('fecha_fin') ?>" 
                       class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="md:col-span-2">
                <label class="block mb-1 font-medium text-slate-700">Distribuidor:</label>
                <select name="distribuidor_id" class="form-control select2 w-full">
                    <option value="">-- Todos --</option>
                    <?php foreach($distribuidores as $d): ?>
                        <option value="<?= $d->id ?>" <?= $this->input->get('distribuidor_id') == $d->id ? 'selected' : '' ?>>
                            <?= $d->nombre ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block mb-1 font-medium text-slate-700">Producto:</label>
                <select name="producto_id" class="form-control select2 w-full">
                    <option value="">-- Todos los productos --</option>
                    <?php foreach($productos as $p): ?>
                        <option value="<?= $p->id ?>" <?= $this->input->get('producto_id') == $p->id ? 'selected' : '' ?>>
                            <?= $p->nombre ?> (<?= $p->codigo ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="md:col-span-2 flex gap-2 mt-auto">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i>
                </button>
                <a href="<?= base_url('inventario/kardex_bolivia') ?>" class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 rounded-lg flex items-center justify-center gap-2">
                    <i class="fas fa-eraser"></i>
                </a>
            </div>

        </div>
    </form>
</div>

        <!-- TABLA KARDEX -->
        <div class="bg-white rounded-xl shadow p-4 overflow-x-auto">
            <table id="tabla-kardex" class="min-w-[900px] w-full text-sm text-left divide-y">
                <thead class="bg-slate-100 text-slate-700 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Distribuidor</th>
                        <th class="px-4 py-3">Producto</th>
                        <th class="px-4 py-3">Origen</th>
                        <th class="px-4 py-3 text-center">Tipo</th>
                        <th class="px-4 py-3 text-right">Cant.</th>
                        <th class="px-4 py-3 text-right">Ant.</th>
                        <th class="px-4 py-3 text-right">Act.</th>
                        <th class="px-4 py-3">Motivo / Referencia</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php if(!empty($movimientos)): ?>
                        <?php foreach($movimientos as $m): ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3"><?= date('d/m/Y H:i', strtotime($m->fecha_registro)) ?></td>
                            <td>
                                <span class="px-2 py-1 rounded bg-slate-100 text-slate-700 text-xs font-bold">
                                    <?= $m->distribuidor_nombre ?? 'SISTEMA' ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <b><?= $m->producto_nombre ?></b><br>
                                <small class="text-slate-500"><?= $m->codigo ?></small>
                            </td>

                            <td class="px-4 py-3"><?= $m->origen ?></td>
                            <td class="px-4 py-3 text-center">
                                <span class="<?= $m->tipo_movimiento == 'Entrada' ? 'bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold' : 'bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold' ?>">
                                    <?= $m->tipo_movimiento ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold">
                                <?= ($m->tipo_movimiento == 'Entrada' ? '+' : '-') ?> <?= number_format($m->cantidad, 2) ?>
                            </td>
                            <td class="px-4 py-3 text-right text-slate-500"><?= number_format($m->stock_anterior, 2) ?></td>
                            <td class="px-4 py-3 text-right font-semibold bg-slate-50"><?= number_format($m->stock_actual, 2) ?></td>
                            <td class="px-4 py-3">
                                <small><?= $m->motivo ?></small>
                                <?php if($m->referencia_id): ?>
                                    <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded-full text-xs border">ID: <?= $m->referencia_id ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
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
    // Usar jQuery.noConflict() por si hay otras versiones chocando
    var $j = jQuery.noConflict();
    $j(document).ready(function() {
        if ($j.isFunction($j.fn.select2)) {
            $j('.select2').select2({
                theme: 'bootstrap4'
            });
        } else {
            console.error("Select2 no se ha cargado correctamente.");
        }
    });
</script>

<script>
    var $j = jQuery.noConflict();
    $j(document).ready(function() {
        
        // 1. Inicializar Select2
        if ($j.isFunction($j.fn.select2)) {
            $j('.select2').select2({
                theme: 'bootstrap4',
                allowClear: true
            });
        }

        // 2. Inicializar DataTables (Paginación)
        if ($j.isFunction($j.fn.DataTable)) {
            $j('#tabla-kardex').DataTable({
                "paging": true,          // Habilita paginación
                "lengthChange": true,    // Permite cambiar de 10 a 25, 50, etc.
                "searching": false,       // Buscador interno rápido
                "ordering": true,        // Permite ordenar columnas
                "info": true,            // Muestra "Mostrando 1 de 10..."
                "autoWidth": false,
                "responsive": true,
                "pageLength": 10,        // Mostrar 10 por página por defecto
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" // Traducción al español
                },
                "order": [[0, "desc"]]   // Ordenar por fecha (primera columna) de más reciente a antiguo
            });
        }
    });
</script>