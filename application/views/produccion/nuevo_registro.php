<style>
    /* Estilos personalizados para el módulo de producción */
    .content-wrapper { background-color: #f4f6f9; }
    
    .card-outline.card-danger { border-top: 3px solid #dc3545; }
    .card-outline.card-success { border-top: 3px solid #28a745; }
    
    .form-group label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 6px;
        border: 1px solid #ced4da;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
    }

    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        color: #495057;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .btn-sm {
        border-radius: 4px;
        padding: 5px 10px;
    }

    .fila-producto:hover {
        background-color: rgba(40, 167, 69, 0.05);
    }

    #btn-agregar {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s;
    }

    #btn-agregar:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
    }

    .card-footer {
        background-color: rgba(0,0,0,.03);
        border-top: 1px solid rgba(0,0,0,.125);
    }
</style>

<div class="md:ml-64 min-h-screen bg-slate-50 pt-20">

    <section class="px-4 md:px-8 py-5 border-b bg-white shadow-sm">
        <div class="container mx-auto">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-microchip mr-2"></i>Registrar Nueva Producción
            </h1>
        </div>
    </section>

    <section class="px-4 md:px-8 py-6">
        <div class="container mx-auto">
            <form action="<?= base_url('produccion/procesar_produccion') ?>" method="POST" id="formProduccion">
                <div class="flex flex-col md:flex-row gap-4">

                    <div class="md:w-1/3 flex flex-col">
                        <div class="bg-white border border-red-300 shadow-sm rounded-lg overflow-hidden flex-1 min-h-[350px]">
                            <div class="bg-red-100 px-4 py-4 border-b border-red-300">
                                <h3 class="font-semibold text-red-800 flex items-center">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Materia Prima (Salida)
                                </h3>
                            </div>
                            <div class="p-6 space-y-4 h-full">
                                <label class="block font-medium text-slate-700 mb-1">Seleccionar Corte</label>

                                <select name="producto_corte_id" id="producto_corte_id"
                                        class="w-full select2-dinamico border border-gray-300 rounded-md shadow-sm p-3" required>
                                    <option value="">-- Seleccione corte --</option>
                                    <?php foreach($cortes as $c): ?>
                                        <option value="<?= $c->id ?>" 
                                                data-stock="<?= $c->stock ?>" 
                                                data-final="<?= $c->producto_final_id ?>"> 
                                            <?= $c->nombre ?> | <?= $c->color ?> | Talla: <?= $c->talla ?> (Disp: <?= $c->stock ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                    <div>
                                        <label class="block font-medium text-slate-700 mb-1">Cantidad de corte disponible</label>
                                        <div class="flex">
                                            <input type="number" 
                                                name="cantidad_insumo" 
                                                id="cantidad_insumo" 
                                                class="flex-1 border border-gray-200 bg-gray-50 rounded-l-md p-3 text-gray-600 font-bold" 
                                                step="0.01" 
                                                value="0" 
                                                readonly> <span class="bg-red-100 border border-red-300 border-l-0 rounded-r-md flex items-center px-3 text-red-600">
                                                <i class="fas fa-cut"></i>
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1">
                                            <i class="fas fa-magic mr-1"></i>Esta cantidad se carga automáticamente según el material.
                                        </p>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <div class="md:w-2/3 flex flex-col">
                        <div class="bg-white border border-green-300 shadow-sm rounded-lg overflow-hidden flex-1 min-h-[350px]">
                            <div class="flex justify-between items-center bg-green-100 px-4 py-4 border-b border-green-300">
                                <h3 class="font-semibold text-green-800 flex items-center">
                                    <i class="fas fa-tshirt mr-2"></i>Productos Obtenidos (Entrada)
                                </h3>
                                <button type="button" id="btn-agregar" class="bg-blue-600 text-white text-sm px-3 py-2 rounded shadow hover:bg-blue-700 flex items-center">
                                    <i class="fas fa-plus-circle mr-1"></i> Agregar Fila
                                </button>
                            </div>

                            <div class="overflow-x-auto p-4 flex-1">
                                <table class="min-w-full divide-y divide-gray-200" id="tabla-productos">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="text-left px-4 py-2">Producto Final (Prenda)</th>
                                            <th class="text-left px-4 py-2" style="width:180px;">Cantidad Producida</th>
                                            <th class="px-4 py-2" style="width:60px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200" id="tbody-productos">
                                        <tr class="fila-producto">
                                            <td class="px-4 py-2 align-middle">
                                                <select name="producto_id[]" class="w-full select2-dinamico border border-gray-300 rounded-md p-3" required>
                                                    <option value="">-- Seleccione producto --</option>
                                                    <?php foreach($finales as $f): ?>
                                                        <option value="<?= $f->id ?>">
                                                            <?= $f->nombre ?> | <?= $f->color ?> | Talla: <?= $f->talla ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td class="px-4 py-2 align-middle">
                                                <input type="number" name="cantidad_resultado[]" class="w-full border border-gray-300 rounded-md p-3 focus:ring focus:ring-green-200" min="1" value="1" required>
                                            </td>
                                            <td class="px-4 py-2 text-center align-middle">
                                                <button type="button" class="btn-eliminar text-red-600 hover:text-red-800 rounded p-1">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="px-4 py-4 bg-white flex justify-between items-center border-t border-gray-200">
                                <a href="<?= base_url('productos') ?>" class="text-gray-500 hover:underline">Cancelar</a>
                                <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded shadow hover:bg-green-700 flex items-center">
                                    <i class="fas fa-sync-alt mr-2"></i> Procesar Producción
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
(function($) {
    "use strict";

    /* =====================================
       BÚSQUEDA INTELIGENTE (Ignora guiones y espacios)
    ===================================== */
    function matchInteligente(params, data) {
        if ($.trim(params.term) === '') return data;
        if (typeof data.text === 'undefined') return null;

        // Limpiamos tildes, guiones y espacios para una búsqueda total
        const term = params.term.toLowerCase()
            .normalize("NFD").replace(/[\u0300-\u036f]/g, "")
            .replace(/[^a-z0-9]/gi, '');
        const text = data.text.toLowerCase()
            .normalize("NFD").replace(/[\u0300-\u036f]/g, "")
            .replace(/[^a-z0-9]/gi, '');

        if (text.indexOf(term) > -1) return data;
        return null;
    }

    /* =====================================
       INICIALIZACIÓN SELECT2
    ===================================== */
    window.inicializarSelect2 = function(selector) {
        const $elementos = $(selector);
        if ($elementos.length === 0) return;

        $elementos.each(function(index, element) {
            if ($(element).hasClass("select2-hidden-accessible")) {
                $(element).select2('destroy');
            }

            $(element).select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: "-- Seleccione --",
                allowClear: true,
                matcher: matchInteligente
            });
        });
    };

    $(function() {
        console.log("🚀 Sistema de producción automatizado listo.");
        inicializarSelect2('.select2-dinamico');

        const btnAgregar = document.getElementById('btn-agregar');
        const tabla = document.getElementById('tabla-productos').getElementsByTagName('tbody')[0];
        const selectCorte = document.getElementById('producto_corte_id');
        const inputCantidadInsumo = document.getElementById('cantidad_insumo');

        /* =====================================
           EVENTO: CAMBIO EN CORTE (Materia Prima)
           - Filtra productos finales
           - Auto-selecciona producto relacionado
           - PONE AUTOMÁTICAMENTE EL STOCK DISPONIBLE
        ===================================== */
        $(selectCorte).on('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (!selectedOption || this.value === "") {
                inputCantidadInsumo.value = "0";
                return;
            }

            const corteId = this.value;
            const relacionadoId = selectedOption.getAttribute('data-final');
            const stockDisponible = selectedOption.getAttribute('data-stock') || "0";

            // A) Ponemos automáticamente el stock disponible en el input
            inputCantidadInsumo.value = stockDisponible;
            console.log(`📦 Stock cargado automáticamente: ${stockDisponible}`);

            // B) Filtrar productos finales y auto-seleccionar
            $('select[name="producto_id[]"]').each(function() {
                const $selectFinal = $(this);
                
                $selectFinal.find('option').each(function() {
                    const op = $(this);
                    const opCorte = op.data('corte');
                    
                    if (opCorte) {
                        if (opCorte == corteId || op.val() == relacionadoId || corteId == "") {
                            op.prop('disabled', false);
                        } else {
                            op.prop('disabled', true);
                        }
                    }
                });

                window.inicializarSelect2($selectFinal);

                // Solo auto-seleccionar en la primera fila
                if (relacionadoId && $selectFinal.is($('select[name="producto_id[]"]').first())) {
                    $selectFinal.val(relacionadoId).trigger('change');
                }
            });
        });

        /* =====================================
           AGREGAR FILA
        ===================================== */
        btnAgregar.addEventListener('click', function() {
            const filaMolde = document.querySelector('.fila-producto');
            const nuevaFila = filaMolde.cloneNode(true);
            const nuevoSelect = nuevaFila.querySelector('select');
            
            nuevoSelect.value = "";
            nuevaFila.querySelector('input').value = "1";
            
            tabla.appendChild(nuevaFila);
            window.inicializarSelect2($(nuevaFila).find('.select2-dinamico'));
        });

        /* =====================================
           ELIMINAR FILA
        ===================================== */
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-eliminar') || e.target.closest('.btn-remove') || e.target.closest('.btn-outline-danger')) {
                const filas = document.querySelectorAll('.fila-producto');
                if (filas.length > 1) {
                    e.target.closest('tr').remove();
                } else {
                    Swal.fire({ icon: 'warning', title: 'Atención', text: 'Debes producir al menos un producto terminado.' });
                }
            }
        });

        /* =====================================
           VALIDACIÓN FINAL AL ENVIAR
        ===================================== */
        document.getElementById('formProduccion').addEventListener('submit', function(e) {
            if (parseFloat(inputCantidadInsumo.value) <= 0 || inputCantidadInsumo.value === "") {
                e.preventDefault();
                Swal.fire('Error', 'El material seleccionado no tiene stock disponible.', 'error');
            }
        });
    });

})(jQuery);
</script>