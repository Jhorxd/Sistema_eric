<style>
    /* Oculta el contenedor del buscador nativo de DataTables */
    .dataTables_filter {
        display: none;
    }
</style>

<div class="md:ml-64 min-h-screen bg-slate-50 pt-20">

    <!-- HEADER -->
    <section class="px-4 md:px-8 py-5 md:py-6 border-b bg-white shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 md:gap-6">
            <h1 class="text-xl md:text-3xl font-bold text-slate-800 flex items-center gap-3">
                <i class="fas fa-boxes text-slate-600"></i>
                Inventario Perú
            </h1>

            <div class="flex flex-wrap gap-2">

            
                <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow flex items-center gap-1"
                        onclick="abrirModalIngresoGlobal()">
                    <i class="fas fa-arrow-down"></i> Ingreso Stock
                </button>

                <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow flex items-center gap-1"
                        onclick="abrirModalSalidaGlobal()">
                    <i class="fas fa-arrow-up"></i> Salida Stock
                </button>

                <a href="<?= base_url('productos/nuevo') ?>" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow flex items-center gap-1">
                    <i class="fas fa-plus"></i> Nuevo Producto
                </a>
            </div>
        </div>
    </section>

    <!-- FILTROS -->
<section class="px-4 md:px-8 py-4">
    <div class="bg-white rounded-xl shadow border border-slate-200 p-4 md:p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4 md:gap-6">

            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 mb-1">Buscar Producto</label>
                <div class="relative">
                    <input type="text" 
                           id="filtro_descripcion" 
                           class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" 
                           placeholder="Escribe nombre...">
                    <div class="absolute left-3 top-2.5 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 mb-1">Color/Tela</label>
                <select class="form-control select2 w-full" id="filtro_color">
                    <option value="">-- TODOS --</option>
                    <?php 
                    $colores = array_unique(array_column($productos, 'color'));
                    sort($colores);
                    foreach($colores as $color): if($color): ?>
                        <option value="<?= $color ?>"><?= $color ?></option>
                    <?php endif; endforeach; ?>
                </select>
            </div>

            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 mb-1">Talla</label>
                <select class="form-control select2 w-full" id="filtro_talla">
                    <option value="">-- TODAS --</option>
                    <?php 
                    $tallas = array_unique(array_column($productos, 'talla'));
                    sort($tallas);
                    foreach($tallas as $talla): ?>
                        <option value="<?= $talla ? $talla : 'N/A' ?>"><?= $talla ? $talla : 'N/A' ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>
    </div>
</section>

    <!-- TABLA INVENTARIO -->
    <section class="px-4 md:px-8 pb-8">
        <div class="bg-white rounded-xl shadow border border-slate-200 p-4 md:p-6">

            <!-- Contenedor responsive -->
        <div class="w-full overflow-x-auto md:overflow-x-visible">
            <table id="tabla_productos" class="min-w-[900px] w-full text-sm text-left">
                <thead class="bg-slate-100 text-slate-700 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3">Código</th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Color/Tela</th>
                        <th class="px-4 py-3">Talla</th>
                        <th class="px-4 py-3">Detalles</th>
                        <th class="px-4 py-3">Stock</th>
                        <th class="px-4 py-3">Precio</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php foreach($productos as $p): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3"><?= $p->codigo ?></td>
                        <td class="px-4 py-3 font-semibold text-slate-800"><?= $p->nombre ?></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold <?= $p->tipo == 'corte' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' ?>">
                                <?= $p->tipo == 'corte' ? 'Corte' : 'Producto Final' ?>
                            </span>
                        </td>
                        <td class="px-4 py-3"><?= $p->color ?> <?= $p->tipo_tela ? "($p->tipo_tela)" : "" ?></td>
                        <td class="px-4 py-3"><?= $p->talla ? $p->talla : 'N/A' ?></td>
                        <td class="px-4 py-3"><?= $p->detalles ? $p->detalles : 'N/A' ?></td>
                        <td class="px-4 py-3 font-bold <?= ($p->stock <= 5) ? 'text-red-600' : 'text-slate-700' ?>"><?= $p->stock ?></td>
                        <td class="px-4 py-3 font-semibold">S/ <?= number_format($p->precio_venta,2) ?></td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                <a href="<?= base_url('productos/editar/'.$p->id) ?>"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded text-sm flex items-center gap-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded text-sm flex items-center gap-1"
                                        onclick="confirmarEliminarProducto(<?= $p->id ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

            <!-- PAGINADO -->
            <div class="mt-4 flex justify-end">
                <nav id="paginado" class="inline-flex items-center gap-1">
                    <!-- Aquí tu paginado dinámico o DataTables -->
                </nav>
            </div>

        </div>
    </section>

</div> 

<!-- MODAL DE INGRESO -->
<div class="modal fade" id="modalIngresoGlobalPeru" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Registrar Movimiento de Ingreso (Perú)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('productos/registrar_ingreso') ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Seleccionar Producto:</label>
                        <select name="id_producto" class="form-control select2-modal w-full" required>
                            <option value="">-- Buscar Producto --</option>
                            <?php foreach($productos as $p): ?>
                                <option value="<?= $p->id ?>">
                                    <?= $p->codigo ?> - <?= $p->nombre ?> (Stock: <?= $p->stock ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label>Cantidad:</label>
                            <input type="number" name="cantidad" step="0.01" class="form-control w-full" placeholder="0.00" required min="0.01">
                        </div>
                        <div class="form-group">
                            <label>Motivo:</label>
                            <select name="motivo" class="form-control w-full" required>
                                <option value="Compra">Compra</option>
                                <option value="Devolución">Devolución</option>
                                <option value="Ajuste">Ajuste (+)</option>
                                <option value="Producción">Producción</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label>Observaciones / Nota:</label>
                        <textarea name="observacion" class="form-control w-full" rows="2" placeholder="Ej: Ingreso por orden de compra #..."></textarea>
                    </div>
                </div>
                <div class="modal-footer flex justify-end gap-2">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info">Procesar Ingreso</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modalSalidaGlobalPeru" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Registrar Movimiento de Salida (Perú)</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('productos/registrar_salida') ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Seleccionar Producto:</label>
                        <select name="id_producto" class="form-control select2-modal w-full" required>
                            <option value="">-- Buscar Producto --</option>
                            <?php foreach($productos as $p): ?>
                                <option value="<?= $p->id ?>">
                                    <?= $p->codigo ?> - <?= $p->nombre ?> (Disponible: <?= $p->stock ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Cantidad a retirar:</label>
                            <input type="number" name="cantidad" step="0.01" class="form-control w-full border-danger" placeholder="0.00" required min="0.01">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Motivo de salida:</label>
                            <select name="motivo" class="form-control w-full" required>
                                <option value="Venta">Venta</option>
                                <option value="Merma/Daño">Merma / Producto Dañado</option>
                                <option value="Ajuste">Ajuste (-)</option>
                                <option value="Uso Interno">Uso Interno</option>
                                <option value="Producción">Consumo para Producción</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label class="font-weight-bold">Observaciones / Nota:</label>
                        <textarea name="observacion" class="form-control w-full" rows="2" placeholder="Ej: Salida por despacho a cliente o tela fallada..."></textarea>
                    </div>
                </div>
                <div class="modal-footer flex justify-end gap-2">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Procesar Salida</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    
    async function inicializarPaginaProductos() {
        try {
            // 1. Carga de dependencias (se mantiene igual)
            if (typeof jQuery === 'undefined') {
                await inyectarScript("https://code.jquery.com/jquery-3.6.0.min.js");
            }
            if (!document.querySelector('link[href*="select2.min.css"]')) {
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css';
                document.head.appendChild(link);
            }
            if (!jQuery.fn.DataTable) {
                await inyectarScript("https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js");
                await inyectarScript("https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js");
            }
            if (!jQuery.fn.select2) {
                await inyectarScript("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js");
            }

            const $ = jQuery;

            // 2. Inicializar DataTable
            const tabla = $("#tabla_productos").DataTable({
                "responsive": true, 
                "lengthChange": false, 
                "autoWidth": false,
                "searching": true, 
                "language": { "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" }
            });

            // 3. Inicializar Select2 para todos los filtros
            $('.select2').select2({
                allowClear: true,
                theme: 'bootstrap4',
                width: '100%'
            });

            // 4. Lógica de Filtrado Multicriterio

            $('#filtro_descripcion').on('input', function() {
                const val = $(this).val();
                
                // Suponiendo que el NOMBRE/DESCRIPCIÓN está en la Columna 1.
                // Usamos búsqueda parcial (sin ^ ni $) para que encuentre coincidencias
                tabla.column(1).search(val).draw();
            });

            // Filtro por Color (Se mantiene igual, columna 3)
            $('#filtro_color').on('change', function() {
                const val = $(this).val();
                tabla.column(3).search(val).draw();
            });

            // Filtro por Talla (Se mantiene igual, columna 4)
            $('#filtro_talla').on('change', function() {
                const val = $(this).val();
                if (val === 'N/A') {
                    tabla.column(4).search('^N/A$', true, false).draw();
                } else {
                    tabla.column(4).search(val ? '^' + val + '$' : '', true, false).draw();
                }
            });

            // 5. Mensajes Flash (SweetAlert)
            <?php if($this->session->flashdata('success')): ?>
                Swal.fire({ icon: 'success', title: '¡Logrado!', text: '<?= $this->session->flashdata('success') ?>', timer: 3000, showConfirmButton: false });
            <?php endif; ?>

        } catch (error) {
            console.error("Error al cargar la página:", error);
        }
    }

    function inyectarScript(url) {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = url;
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }

    inicializarPaginaProductos();
});

// Función global de eliminación
function confirmarEliminarProducto(id) {
    Swal.fire({
        title: '¿Eliminar del Inventario?',
        text: "Se borrará el registro técnico del producto y su stock actual.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Eliminando...',
                didOpen: () => { Swal.showLoading(); }
            });
            window.location.href = "<?= base_url('productos/eliminar/') ?>" + id;
        }
    });
}

function abrirModalIngresoGlobal() {
    $('#modalIngresoGlobalPeru').modal('show');
    
    // Inicializamos Select2 para el buscador dentro del modal
    $('.select2-modal').select2({
        dropdownParent: $('#modalIngresoGlobalPeru'),
        theme: 'bootstrap4',
        placeholder: "Escriba código o nombre..."
    });
}

function abrirModalSalidaGlobal() {
    // Abrimos el modal de salida
    $('#modalSalidaGlobalPeru').modal('show');
    
    // Inicializamos Select2 para el buscador dentro del modal de salida
    $('.select2-modal').select2({
        dropdownParent: $('#modalSalidaGlobalPeru'),
        theme: 'bootstrap4',
        placeholder: "Escriba código o nombre...",
        width: '100%' // Asegura que ocupe todo el ancho del contenedor
    });
}
</script>