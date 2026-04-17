<style>
    .section-title { border-left: 4px solid #ffc107; padding-left: 10px; margin-bottom: 20px; font-weight: bold; color: #333; }
    .total-box { background: #fff3cd; padding: 15px; border-radius: 8px; border: 1px solid #ffeeba; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05); }
    .table thead th { background-color: #343a40; color: white; border: none; }
    .btn-remove-row { margin-top: 5px; }
    /* Ajuste para que Select2 se vea bien en tablas */
    .select2-container--bootstrap4 .select2-selection--single { height: calc(2.25rem + 2px) !important; }
</style>

<div class="md:ml-64 min-h-screen bg-slate-50 transition-all duration-300">
    <div class="p-4 sm:p-6 lg:p-10 w-full">
        
        <header class="mb-8 border-b border-slate-200 pb-6 flex justify-between items-end">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                    <i class="fas fa-edit text-amber-500"></i> 
                    Editando Pedido #<?= $venta->id ?>
                </h1>
            </div>
            <a href="<?= base_url('ventas/listado') ?>" class="text-slate-400 hover:text-slate-600 font-bold text-sm transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Volver al listado
            </a>
        </header>

        <form action="<?= base_url('ventas/actualizar_venta') ?>" method="POST" id="formVenta">
            <input type="hidden" name="id_venta" value="<?= $venta->id ?>">

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
                
                <div class="xl:col-span-4 space-y-6">
                    
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="bg-amber-50 px-6 py-4 border-b border-amber-100">
                            <h5 class="text-sm font-black text-amber-700 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-user-tag"></i> Información del Cliente
                            </h5>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">DNI / RUC</label>
                                <div class="flex gap-2">
                                    <input type="text" name="dni" id="dni" value="<?= $venta->dni ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none" required maxlength="11">
                                    <button type="button" onclick="buscarCliente()" id="btn-buscar-cliente" class="bg-slate-800 text-white px-4 py-2.5 rounded-xl hover:bg-black transition-colors shadow-md">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nombre Completo</label>
                                <input type="text" name="nombre" id="nombre" value="<?= $venta->nombre ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none" required>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Celular</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                        <i class="fas fa-mobile-alt text-xs"></i>
                                    </span>
                                    <input type="text" name="celular" id="celular" value="<?= $venta->celular ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-500">
                                </div>
                            </div>

                                                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Celular</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                        <i class="fas fa-map-marker-alt text-blue-500"></i>
                                    </span>
                                    <input type="text" name="ubicacion" id="ubicacion" value="<?= $venta->ubicacion ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden border-l-4 border-l-blue-500">
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                            <h5 class="text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-blue-500"></i> Datos de Destino
                            </h5>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Destino</label>
                                <input type="text" name="destino" id="destino" value="<?= $venta->destino ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Ej: Lima, Cusco...">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="xl:col-span-8 space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="font-black text-slate-800 text-lg italic flex items-center gap-3">
                                <i class="fas fa-shopping-basket text-slate-300 font-normal"></i> Detalle del Pedido
                            </h3>
                            <div class="flex items-center gap-4">
                                <input type="datetime-local" 
                                    name="fecha" 
                                    value="<?= date('Y-m-d\TH:i', strtotime($venta->fecha)) ?>" 
                                    class="text-sm font-bold bg-slate-100 border-none rounded-lg px-3 py-1.5 outline-none focus:ring-2 focus:ring-amber-500 transition-all">
                                <button type="button" id="btn-add-producto" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-emerald-100">
                                    <i class="fas fa-plus mr-1"></i> Agregar
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse" id="tabla-ventas">
                                <thead>
                                    <tr class="bg-slate-100 text-slate-800 text-base uppercase tracking-tight border-b-2 border-slate-200">
                                        <th class="px-8 py-5 font-black italic">Producto / Color / Talla</th>
                                        <th class="px-4 py-5 font-black w-28 text-center">Cant.</th>
                                        <th class="px-4 py-5 font-black w-44 text-right">Precio Unit.</th>
                                        <th class="px-4 py-5 font-black w-40 text-right bg-slate-200/30">Subtotal</th>
                                        <th class="px-8 py-5 w-16"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php foreach($detalles as $det): ?>
                                    <tr class="fila-venta group">
                                        <td class="px-8 py-4">
                                            <select name="producto_id[]" class="w-full bg-transparent border-b border-transparent group-hover:border-slate-200 py-1 focus:border-amber-500 outline-none text-sm font-semibold transition-all select-prod" required>
                                                <option value="">-- Seleccione --</option>
                                                <?php foreach($productos as $p): ?>
                                                    <option value="<?= $p->id ?>" 
                                                            data-precio="<?= $p->precio_venta ?>" 
                                                            data-stock="<?= $p->stock ?>" 
                                                            <?= (isset($det) && $p->id == $det->id_producto) ? 'selected' : '' ?>>
                                                        <?= $p->nombre ?> <?= $p->color ?> <?= $p->talla ?> | Stock: <?= $p->stock ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="px-4 py-4">
                                            <input type="number" name="cant[]" value="<?= $det->cantidad ?>" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2 py-2 text-center font-bold text-sm input-cant" min="1">
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center justify-end">
                                                <span class="text-slate-400 font-bold mr-1 text-xs">S/</span>
                                                <input type="number" name="precio[]" value="<?= $det->precio_unitario ?>" class="w-24 bg-slate-50 border border-slate-200 rounded-lg px-2 py-2 text-right font-bold text-sm input-precio" step="0.01">
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-right font-mono font-black text-slate-800 text-base">
                                            S/ <span class="subtotal-text"><?= number_format($det->subtotal, 2, '.', '') ?></span>
                                        </td>
                                        <td class="px-8 py-4 text-center">
                                            <button type="button" class="text-slate-300 hover:text-red-500 btn-remove-row transition-colors text-lg">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="p-8 bg-slate-50 border-t border-slate-100">
                            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                                <div class="text-center md:text-left">
                                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Monto Total a Pagar</p>
                                    <h3 class="text-4xl font-black text-slate-900 italic tracking-tighter">
                                        S/ <span id="total_final_texto"><?= number_format($venta->total_venta, 2) ?></span>
                                    </h3>
                                    <input type="hidden" name="total_final" id="total_final_val" value="<?= $venta->total_venta ?>">
                                </div>
                                
                                <div class="flex gap-3 w-full md:w-auto">
                                    <button type="submit" class="flex-1 md:flex-none bg-amber-500 hover:bg-slate-900 text-white font-black uppercase tracking-widest text-sm px-10 py-4 rounded-2xl transition-all shadow-xl shadow-amber-100 flex items-center justify-center gap-3">
                                        <i class="fas fa-sync-alt"></i> Actualizar Pedido
                                    </button>
                                </div>
                            </div>
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

<script>
    // Función para cargar scripts dinámicamente y devolver una promesa
    function loadScript(src) {
        return new Promise(function(resolve, reject) {
            var script = document.createElement('script');
            script.src = src;
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }

    console.log("1. 🏁 Iniciando carga de dependencias...");

    // Forzamos la carga de Select2 después de que jQuery esté listo
    loadScript("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js")
    .then(function() {
        console.log("2. ✅ Select2 cargado con éxito.");
        // Una vez que el JS de Select2 cargó, iniciamos tu lógica
        if (typeof jQuery !== 'undefined') {
            initModuloVentas(jQuery);
        }
    })
    .catch(function() {
        console.error("❌ Error: No se pudo cargar el archivo JS de Select2 desde la CDN.");
    });

function initModuloVentas($) {
    console.log("3. 🚀 Iniciando initModuloVentas...");

    // 1. INICIALIZAR SELECT2
    function initSelect2(element) {
        var $el = $(element);
        if ($el.length > 0) {
            $el.select2({
                theme: 'bootstrap4',
                placeholder: "-- Seleccione Producto --",
                allowClear: true,
                width: '100%'
            });
        }
    }

    // --- FUNCIÓN PARA BLOQUEAR DUPLICADOS ---
    function actualizarOpcionesDuplicadas() {
        var seleccionados = [];

        // Obtener todos los IDs elegidos actualmente
        $('.select-prod').each(function() {
            var val = $(this).val();
            if (val && val !== "") seleccionados.push(val.toString());
        });

        // Recorrer cada select y deshabilitar opciones ocupadas
        $('.select-prod').each(function() {
            var $select = $(this);
            var miValor = $select.val() ? $select.val().toString() : "";

            $select.find('option').each(function() {
                var optVal = $(this).val() ? $(this).val().toString() : "";
                if (optVal !== "") {
                    // Si está seleccionado en otra fila, lo deshabilitamos aquí
                    if (seleccionados.includes(optVal) && optVal !== miValor) {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                }
            });

            // Forzar a Select2 a refrescar la vista de las opciones disabled
            if ($select.data('select2')) {
                $select.trigger('change.select2');
            }
        });
    }
    
    initSelect2('.select-prod');
    actualizarOpcionesDuplicadas(); // Carga inicial


    // 3. AGREGAR PRODUCTO
    $('#btn-add-producto').on('click', function() {
        console.log("➕ Agregando nueva fila...");
        var $tbody = $('#tabla-ventas tbody');
        var $primeraFila = $tbody.find('.fila-venta').first();
        
        if ($primeraFila.find('.select-prod').data('select2')) {
            $primeraFila.find('.select-prod').select2('destroy');
        }

        var $nuevaFila = $primeraFila.clone();
        initSelect2($primeraFila.find('.select-prod'));

        $nuevaFila.find('.select2-container').remove(); 
        $nuevaFila.find('select')
                  .removeClass('select2-hidden-accessible')
                  .removeAttr('data-select2-id')
                  .val('') 
                  .find('option').removeAttr('data-select2-id').prop('disabled', false); // Importante resetear disabled
        
        $nuevaFila.find('input').val('');
        $nuevaFila.find('.input-cant').val(1);
        $nuevaFila.find('.subtotal-text').text('0.00');
        
        $tbody.append($nuevaFila);
        initSelect2($nuevaFila.find('.select-prod'));

        // Sincronizar después de agregar
        actualizarOpcionesDuplicadas();
    });

    // 4. ELIMINAR FILA
    $(document).on('click', '.btn-remove-row', function() {
        if ($('.fila-venta').length > 1) {
            $(this).closest('tr').remove();
            calcularGranTotal();
            // Sincronizar al eliminar (libera el producto)
            actualizarOpcionesDuplicadas();
        }
    });

    // 5. EVENTOS DE CAMBIO
    $(document).on('change', '.select-prod', function() {
        var $fila = $(this).closest('tr');
        var precio = $(this).find(':selected').data('precio') || 0;
        if(precio > 0) $fila.find('.input-precio').val(parseFloat(precio).toFixed(2));
        
        // Sincronizar cuando cambia la selección
        actualizarOpcionesDuplicadas();
        recalcular($fila);
    });

    $(document).on('input', '.input-cant, .input-precio', function() {
        recalcular($(this).closest('tr'));
    });

    function recalcular($f) {
        var $select = $f.find('.select-prod');
        var $inputCant = $f.find('.input-cant');
        
        var stockDisponible = parseInt($select.find(':selected').data('stock')) || 0;
        var c = parseFloat($inputCant.val()) || 0;
        var p = parseFloat($f.find('.input-precio').val()) || 0;

        if ($select.val() !== "" && c > stockDisponible) {
            Swal.fire({
                icon: 'warning',
                title: 'Stock Insuficiente',
                text: 'El stock máximo es: ' + stockDisponible,
                confirmButtonColor: '#3085d6'
            });
            c = stockDisponible;
            $inputCant.val(c);
        }

        $f.find('.subtotal-text').text((p).toFixed(2));
        calcularGranTotal();
    }

    function calcularGranTotal() {
        var total = 0;
        $('.subtotal-text').each(function() { total += parseFloat($(this).text()) || 0; });
        $('#total_final_texto').text(total.toLocaleString('en-US', {minimumFractionDigits: 2}));
        $('#total_final_val').val(total.toFixed(2));
    }

    $('#formVenta').on('submit', function(e) {
        var total = parseFloat($('#total_final_val').val()) || 0;
        if(total <= 0) {
            e.preventDefault();
            Swal.fire('Error', 'Debe ingresar productos con precios válidos.', 'error');
            return false;
        }
    });
}

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