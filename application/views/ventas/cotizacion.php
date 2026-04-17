<style>
    .section-title { border-left: 4px solid #007bff; padding-left: 10px; margin-bottom: 20px; font-weight: bold; color: #333; }
    .total-box { background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #ddd; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05); }
    .table thead th { background-color: #343a40; color: white; border: none; }
    .btn-remove-row { margin-top: 5px; }
</style>


<div class="md:ml-64 min-h-screen bg-slate-50 transition-all duration-300">
    <div class="p-4 sm:p-6 lg:p-10 w-full">
        
        <header class="mb-8 border-b border-slate-200 pb-6">
            <h1 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                <i class="fas fa-file-invoice text-blue-600"></i> 
                Nueva Cotización / Venta
            </h1>
        </header>

        <form action="<?= base_url('ventas/guardar_cotizacion') ?>" method="POST" id="formVenta">
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
                
        <div class="xl:col-span-4 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                    <h5 class="text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-user-tag text-blue-500"></i> Información del Cliente
                    </h5>
                </div>
                <div class="p-6 space-y-4">
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">
                            DNI / RUC <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <input type="text" name="dni" id="dni" 
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all invalid:border-red-300" 
                                placeholder="Buscar..." required>
                            <button type="button" onclick="buscarCliente()" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl transition-colors shadow-md shadow-blue-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nombre" id="nombre" 
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none invalid:border-red-300" 
                            required placeholder="Nombre del cliente">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">
                            Celular <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="celular" id="celular" 
                            required
                            pattern="[0-9]+"
                            title="Solo se permiten números"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500 invalid:border-red-300" 
                            placeholder="999 999 999">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">
                            Departamento / Ciudad <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="ubicacion" id="ubicacion" 
                            required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500 invalid:border-red-300" 
                            placeholder="Ej: Lima, Arequipa...">
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100 hidden xl:block">
                <p class="text-blue-700 text-xs leading-relaxed font-medium">
                    <i class="fas fa-info-circle mr-1"></i> Recuerde verificar el stock actual antes de confirmar el pedido con el cliente.
                </p>
            </div>
        </div>

                

                <div class="xl:col-span-8 space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-100 flex flex-wrap justify-between items-center gap-4">
                            <h3 class="font-black text-slate-800 text-lg flex items-center gap-3 italic">
                                <i class="fas fa-shopping-basket text-slate-400 font-normal"></i> Productos en Pedido
                            </h3>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase">Fecha:</label>
                                    <input type="datetime-local" name="fecha" class="text-sm font-bold bg-slate-100 border-none rounded-lg px-3 py-1.5 focus:ring-0" value="<?= date('Y-m-d\TH:i') ?>">
                                </div>
                                <button type="button" id="btn-add-producto" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-emerald-100">
                                    <i class="fas fa-plus mr-1"></i> Agregar
                                </button>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse" id="tabla-ventas">
                                <thead>
                                <tr class="bg-slate-100 text-slate-800 text-base uppercase tracking-tight border-b-2 border-slate-200">
                                    <th class="px-8 py-5 font-black italic">
                                        <i class="fas fa-tag mr-2 text-blue-600"></i> Descripción del Producto
                                    </th>
                                    <th class="px-4 py-5 font-black w-32 text-center">
                                        Cant.
                                    </th>
                                    <th class="px-4 py-5 font-black w-48 text-right">
                                        Precio Unit.
                                    </th>
                                    <th class="px-4 py-5 font-black w-40 text-right bg-slate-200/50">
                                        Subtotal
                                    </th>
                                    <th class="px-8 py-5 w-20 text-center">
                                        <i class="fas fa-cog text-slate-400"></i>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <tr class="fila-venta group">
                                        <td class="px-8 py-5">
                                            <select name="producto_id[]" class="w-full bg-transparent border-b-2 border-slate-100 py-2 focus:border-blue-500 outline-none text-sm font-semibold select-prod" required>
                                                <option value="" data-precio="0">-- Seleccione Producto --</option>
                                                <?php foreach($productos as $p): ?>
                                                    <option value="<?= $p->id ?>" data-precio="<?= $p->precio_venta ?>" data-stock="<?= $p->stock ?>">
                                                        <?= $p->nombre ?> | <?= $p->color ?> - <?= $p->talla ?> (Stock: <?= $p->stock ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="px-4 py-5">
                                            <input type="number" name="cant[]" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2 py-2 text-center font-bold text-slate-700 input-cant" value="1" min="1">
                                        </td>
                                        <td class="px-4 py-5 text-right">
                                            <div class="flex items-center justify-end">
                                                <span class="text-slate-400 font-bold mr-1 text-xs">S/</span>
                                                <input type="number" name="precio[]" class="w-24 bg-slate-50 border border-slate-200 rounded-lg px-2 py-2 text-right font-bold text-slate-700 input-precio" step="0.01">
                                            </div>
                                        </td>
                                        <td class="px-4 py-5 text-right font-mono font-black text-slate-900">
                                            S/ <span class="subtotal-text text-lg">0.00</span>
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            <button type="button" class="text-slate-300 hover:text-red-500 transition-colors btn-remove-row"><i class="fas fa-times-circle text-lg"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="bg-slate-900 p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                <div class="flex flex-wrap items-center gap-4 text-white">
                                    <div class="w-full sm:w-auto">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Método de Pago</label>
                                        <select name="metodo_pago" class="bg-slate-800 border-none text-white text-sm rounded-xl px-4 py-2.5 outline-none focus:ring-1 focus:ring-blue-500">
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Yape/Plin">Yape / Plin</option>
                                            <option value="Transferencia">Transferencia</option>
                                            <option value="Tarjeta">Tarjeta</option>
                                        </select>
                                    </div>
                                    <div class="w-full sm:w-auto">
                                        <label class="block text-[10px] font-bold text-emerald-400 uppercase mb-2">Adelanto S/</label>
                                        <input type="number" name="adelanto" id="adelanto" class="bg-slate-800 border-none text-emerald-400 text-lg font-black rounded-xl px-4 py-2 w-32 outline-none" step="0.01" value="0.00">
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mb-1">Total a Pagar</p>
                                    <h3 class="text-4xl sm:text-5xl font-black text-white italic">S/ <span id="total_final_texto">0.00</span></h3>
                                    <input type="hidden" name="total_final" id="total_final_val">
                                </div>
                            </div>
                        </div>

                        <div class="px-8 py-6 bg-white border-t border-slate-100 flex justify-end">
                            <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-slate-900 text-white font-black uppercase tracking-[0.15em] text-sm px-10 py-4 rounded-2xl transition-all shadow-xl shadow-blue-100 flex items-center justify-center gap-3 group">
                                <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                                Registrar Pedido
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    console.log("🚀 Iniciando cargador maestro...");

    function cargarScript(url, callback) {
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = url;
        script.onload = callback;
        document.head.appendChild(script);
    }

    // Paso 1: Verificar jQuery
    if (typeof jQuery === 'undefined') {
        console.log("📦 jQuery no encontrado. Cargando...");
        cargarScript("https://code.jquery.com/jquery-3.6.0.min.js", function() {
            verificarSelect2();
        });
    } else {
        verificarSelect2();
    }

    // Paso 2: Verificar Select2
    function verificarSelect2() {
        if (typeof jQuery.fn.select2 === 'undefined') {
            console.log("📦 Select2 no encontrado. Cargando...");
            cargarScript("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js", function() {
                console.log("✅ Todo cargado dinámicamente.");
                initModuloVentas(jQuery);
            });
        } else {
            initModuloVentas(jQuery);
        }
    }

function initModuloVentas($) {
    console.log("⚙️ Inicializando Módulo de Ventas...");

    const $form = $('#formVenta');

    function aplicarSelect2(selector = '.select-prod') {
        $(selector).select2({
            theme: 'bootstrap4',
            placeholder: "-- Seleccione Producto --",
            allowClear: true,
            width: '100%'
        });
    }

    // --- FUNCIÓN REFORZADA: OCULTAR DUPLICADOS ---
    function actualizarDisponibilidadProductos() {
        var seleccionados = [];

        // 1. Capturar todos los IDs seleccionados (evitando nulos)
        $('.select-prod').each(function() {
            var val = $(this).val();
            if (val && val !== "") {
                seleccionados.push(val.toString());
            }
        });

        // 2. Aplicar filtro a cada select
        $('.select-prod').each(function() {
            var $select = $(this);
            var miValorActual = $select.val() ? $select.val().toString() : "";

            $select.find('option').each(function() {
                var optVal = $(this).val() ? $(this).val().toString() : "";

                if (optVal !== "") {
                    // Si el ID está en la lista global pero NO es el que yo tengo puesto
                    if (seleccionados.includes(optVal) && optVal !== miValorActual) {
                        $(this).prop('disabled', true); // Deshabilitar
                        $(this).attr('disabled', 'disabled'); // Refuerzo atributo
                    } else {
                        $(this).prop('disabled', false);
                        $(this).removeAttr('disabled');
                    }
                }
            });

            // IMPORTANTE: Forzar a Select2 a leer de nuevo el estado del <select> original
            if ($select.data('select2')) {
                $select.select2('destroy'); // Destruimos momentáneamente
                aplicarSelect2($select);    // Re-inicializamos con el nuevo estado de los options
            }
        });
    }

    aplicarSelect2();

    // --- AGREGAR FILA ---
    $(document).on('click', '#btn-add-producto', function(e) {
        e.preventDefault();
        var $tbody = $('#tabla-ventas tbody');
        var $filaMolde = $tbody.find('.fila-venta').first();

        // Limpieza de Select2 en el molde antes de clonar
        if ($filaMolde.find('.select-prod').data('select2')) {
            $filaMolde.find('.select-prod').select2('destroy');
        }

        var $nuevaFila = $filaMolde.clone();
        
        // Re-inicializar el original
        aplicarSelect2($filaMolde.find('.select-prod')); 

        // Limpiar el clon
        $nuevaFila.find('.select2-container').remove(); 
        $nuevaFila.find('select').val('').removeClass('select2-hidden-accessible').removeAttr('data-select2-id');
        $nuevaFila.find('input').val('');
        $nuevaFila.find('.input-cant').val(1);
        $nuevaFila.find('.subtotal-text').text('0.00');

        $tbody.append($nuevaFila);
        
        // Inicializar Select2 en el nuevo y sincronizar todos
        aplicarSelect2($nuevaFila.find('.select-prod')); 
        actualizarDisponibilidadProductos();
    });

    // --- CAMBIO DE PRODUCTO ---
    $(document).on('change', '.select-prod', function() {
        var $fila = $(this).closest('tr');
        var selectedOpt = $(this).find(':selected');
        
        // Cargar precio
        var precio = parseFloat(selectedOpt.data('precio')) || 0;
        $fila.find('.input-precio').val(precio.toFixed(2));

        // DISPARAR SINCRONIZACIÓN DE DISPONIBLES
        actualizarDisponibilidadProductos();
        
        // Recalcular esta fila
        validarYCalcular($fila);
    });

    // --- EVENTOS DE CANTIDAD/PRECIO ---
    $(document).on('keyup input', '.input-precio', function() {
        validarYCalcular($(this).closest('tr'));
    });

    function validarYCalcular($fila) {
        var $select = $fila.find('.select-prod');
        var $inputCant = $fila.find('.input-cant');
        var stock = parseInt($select.find(':selected').data('stock')) || 0;
        var cant = parseFloat($inputCant.val()) || 0;
        var precio = parseFloat($fila.find('.input-precio').val()) || 0;

        if ($select.val() !== "" && cant > stock) {
            cant = stock;
            $inputCant.val(stock);
            Swal.fire('Stock Insuficiente', 'Se ajustó al máximo disponible: ' + stock, 'warning');
        }

        var subtotal = precio;
        $fila.find('.subtotal-text').text(subtotal.toFixed(2));
        recalcularTotalGeneral();
    }

    // --- ELIMINAR FILA ---
    $(document).on('click', '.btn-remove-row', function() {
        if ($('#tabla-ventas tbody tr').length > 1) {
            $(this).closest('tr').remove();
            recalcularTotalGeneral();
            actualizarDisponibilidadProductos(); // Al borrar, el producto queda libre
        }
    });

    function recalcularTotalGeneral() {
        var total = 0;
        $('.subtotal-text').each(function() {
            total += parseFloat($(this).text()) || 0;
        });
        $('#total_final_texto').text(total.toFixed(2));
        $('#total_final_val').val(total.toFixed(2));
    }
}
});

// Esta función debe quedar fuera para el 'onclick' del botón buscar
function buscarCliente() {
    var dni = document.getElementById('dni').value;
    if(!dni) return alert("Ingrese un DNI o RUC para buscar");

    // Apuntamos al controlador de Clientes que acabamos de crear
    $.post('<?= base_url("clientes/buscar_por_dni") ?>', {dni: dni}, function(data) {
        if(data && data !== "null") {
            var res = JSON.parse(data);
            $('#nombre').val(res.nombre);
            $('#celular').val(res.celular);
            $('#ubicacion').val(res.ubicacion);
            
            // Un pequeño aviso visual
            Swal.fire({
                icon: 'success',
                title: 'Cliente encontrado',
                text: res.nombre,
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                icon: 'info',
                title: 'No encontrado',
                text: 'El cliente no existe. Puedes ingresar los datos manualmente y se guardarán con la venta.'
            });
        }
    });
}
</script>