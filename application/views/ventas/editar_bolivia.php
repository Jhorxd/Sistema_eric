<style>
    .section-title { border-left: 4px solid #ffc107; padding-left: 10px; margin-bottom: 20px; font-weight: bold; color: #333; }
    .total-box { background: #fff3cd; padding: 15px; border-radius: 8px; border: 1px solid #ffeeba; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05); }
    .table thead th { background-color: #343a40; color: white; border: none; }
    .btn-remove-row { margin-top: 5px; }
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
            <a href="<?= base_url('ventas_bolivia/listado') ?>" class="text-slate-400 hover:text-slate-600 font-bold text-sm transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Volver al listado
            </a>
        </header>

        <form action="<?= base_url('ventas_bolivia/actualizar_venta') ?>" method="POST" id="formVenta">
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
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">NIT / CI</label>
                                <div class="flex gap-2">
                                    <input type="text" name="nit" id="nit" value="<?= $venta->nit ?>" 
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none transition-all" 
                                        <?= ($this->session->userdata('rol') == 'distribuidor') ? 'readonly' : '' ?>
                                        required>
                                    <?php if ($this->session->userdata('rol') != 'distribuidor'): ?>
                                        <button type="button" id="btn-buscar-cliente" onclick="buscarDistribuidor()" class="bg-slate-800 text-white px-4 py-2.5 rounded-xl hover:bg-black transition-colors shadow-md">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nombre Completo</label>
                                <input type="text" name="nombre" id="nombre" value="<?= $venta->nombre ?>" 
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none transition-all" 
                                    <?= ($this->session->userdata('rol') == 'distribuidor') ? 'readonly' : '' ?>
                                    required>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Celular</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                        <i class="fas fa-mobile-alt text-xs"></i>
                                    </span>
                                    <input type="text" name="celular" id="celular" value="<?= $venta->celular ?>" 
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-500"
                                        <?= ($this->session->userdata('rol') == 'distribuidor') ? 'readonly' : '' ?>>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Ubicación</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                        <i class="fas fa-map-marker-alt text-blue-500"></i>
                                    </span>
                                    <input type="text" name="ubicacion" id="ubicacion" value="<?= $venta->ubicacion ?>" 
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-amber-500 transition-all"
                                        <?= ($this->session->userdata('rol') == 'distribuidor') ? 'readonly' : '' ?>>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden border-l-4 border-l-blue-500">
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                            <h5 class="text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-truck text-blue-500"></i> Tipo y Destino
                            </h5>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tipo de Venta</label>
                                <select name="tipo_venta" id="tipo_venta" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                                    <option value="DELIVERY" <?= (isset($venta->tipo_venta) && $venta->tipo_venta == 'DELIVERY') ? 'selected' : '' ?>>DELIVERY</option>
                                    <option value="ENVIO" <?= (isset($venta->tipo_venta) && $venta->tipo_venta == 'ENVIO') ? 'selected' : '' ?>>ENVIO (Provincias)</option>
                                </select>
                            </div>

                            <div id="div_destino" style="<?= (isset($venta->tipo_venta) && $venta->tipo_venta == 'ENVIO') ? '' : 'display: none;' ?>">
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Destino (Dirección/Agencia)</label>
                                <input type="text" name="destino" id="destino" value="<?= $venta->destino ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500" placeholder="Calle, Nro o Nombre de Agencia">
                            </div>

                             <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Celular cliente</label>
                                <input type="text" name="celular_cliente" id="celular_cliente" value="<?= $venta->celular_cliente ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm" placeholder="70000000">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden border-l-4 border-l-blue-500">
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                            <h5 class="text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-truck text-blue-500"></i> Costos de Envío
                            </h5>
                        </div>
                        <div class="p-6">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Comisión Delivery</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold text-xs">Bs.</span>
                                <input type="number" name="comision_delivery" id="comision_delivery" value="<?= $venta->comision_delivery ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none font-bold">
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
                                <input type="datetime-local" name="fecha" value="<?= date('Y-m-d\TH:i', strtotime($venta->fecha)) ?>" class="text-sm font-bold bg-slate-100 border-none rounded-lg px-3 py-1.5 outline-none focus:ring-2 focus:ring-amber-500 transition-all">
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
                                                    <option value="<?= $p->id ?>" data-precio="<?= $p->precio_venta ?>" data-stock="<?= $p->stock ?>" <?= ($p->id == $det->id_producto) ? 'selected' : '' ?>>
                                                        <?= $p->nombre ?> <?= $p->color ?> <?= $p->talla ?> | Stock: <?= $p->stock ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="px-4 py-4">
                                            <input type="number" name="cant[]" value="<?= $det->cantidad ?>" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2 py-2 text-center font-bold text-sm input-cant" min="0.1" step="any">
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center justify-end">
                                                <span class="text-slate-400 font-bold mr-1 text-xs">Bs.</span>
                                                <input type="number" name="precio[]" value="<?= $det->precio_unitario ?>" class="w-24 bg-slate-50 border border-slate-200 rounded-lg px-2 py-2 text-right font-bold text-sm input-precio" step="0.01">
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-right font-mono font-black text-slate-800 text-base">
                                            Bs. <span class="subtotal-text"><?= number_format($det->subtotal, 2, '.', '') ?></span>
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
                                        Bs. <span id="total_final_texto"><?= number_format($venta->total_venta, 2) ?></span>
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

(function($) {
    "use strict";

    $(function() {
        console.log("Sistema de Ventas: Verificando entorno...");

        function forzarRegistroSelect2() {
            // Intentamos jalar el script de nuevo pero asegurando el registro
            $.getScript("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js")
                .done(function() {
                    // Si después de cargar, $.fn.select2 sigue fallando, 
                    // intentamos buscarlo en el objeto global window.jQuery
                    if (!$.fn.select2 && window.jQuery.fn.select2) {
                        $.fn.select2 = window.jQuery.fn.select2;
                    }

                    if ($.fn.select2) {
                        console.log("¡Logrado! Select2 registrado en la instancia actual.");
                        initModuloVentas($);
                    } else {
                        console.error("Fallo total: Select2 no se adhiere a jQuery. Reintentando en 500ms...");
                        setTimeout(forzarRegistroSelect2, 500);
                    }
                });
        }

        if (!$.fn.select2) {
            forzarRegistroSelect2();
        } else {
            initModuloVentas($);
        }
    });

    function initModuloVentas($) {
        
        function initSelect2(element) {
            var $el = $(element);
            if ($.fn.select2) {
                $el.select2({ 
                    theme: 'bootstrap4', 
                    placeholder: "-- Seleccione Producto --", 
                    allowClear: true,
                    width: '100%'
                });
            }
        }

        // Exponer funciones para buscarDistribuidor()
        window.initSelect2Global = initSelect2;
        window.recalcularTotalesGlobal = calcularGranTotal;

        // Inicializar filas existentes
        setTimeout(function() {
            $('.select-prod').each(function() { initSelect2(this); });
        }, 200);

        // --- FUNCIÓN: EVITAR DUPLICADOS ---
        function actualizarDisponibilidadGlobal() {
            var seleccionados = [];
            $('.select-prod').each(function() {
                var val = $(this).val();
                if (val && val !== "") seleccionados.push(val.toString());
            });

            $('.select-prod').each(function() {
                var $select = $(this);
                var miValor = $select.val() ? $select.val().toString() : "";
                $select.find('option').each(function() {
                    var optVal = $(this).val() ? $(this).val().toString() : "";
                    if (optVal !== "") {
                        $(this).prop('disabled', (seleccionados.includes(optVal) && optVal !== miValor));
                    }
                });
                if ($select.data('select2')) $select.trigger('change.select2');
            });
        }

        // --- 1. AGREGAR PRODUCTO ---
        $('#btn-add-producto').on('click', function() {
            var $tbody = $('#tabla-ventas tbody');
            var $nuevaFila = $tbody.find('.fila-venta').first().clone();
            var $nuevoSelect = $nuevaFila.find('.select-prod');

            $nuevaFila.find('.select2-container').remove(); 
            if ($nuevoSelect.hasClass('select2-hidden-accessible')) $nuevoSelect.select2('destroy');

            $nuevoSelect.removeClass('select2-hidden-accessible').removeAttr('data-select2-id').val('').prop('selected', false);
            $nuevoSelect.find('option').prop('disabled', false);
            $nuevaFila.find('input').val('');
            $nuevaFila.find('.input-cant').val(1);
            $nuevaFila.find('.subtotal-text').text('0.00');
            
            $tbody.append($nuevaFila);
            initSelect2($nuevoSelect);
            actualizarDisponibilidadGlobal();
        });

        // --- 2. ELIMINAR FILA ---
        $(document).on('click', '.btn-remove-row', function() {
            if ($('.fila-venta').length > 1) {
                $(this).closest('tr').remove();
                actualizarDisponibilidadGlobal(); 
                calcularGranTotal();
            } else {
                Swal.fire('Atención', "Debe haber al menos un producto.", 'warning');
            }
        });

        // --- 3. EVENTOS ---
        $(document).on('change', '.select-prod', function() {
            var $fila = $(this).closest('tr');
            var precio = $(this).find(':selected').data('precio') || 0;
            $fila.find('.input-precio').val(parseFloat(precio).toFixed(2));
            actualizarDisponibilidadGlobal();
            recalcular($fila);
        });

        $(document).on('input', '.input-cant, .input-precio, #comision_delivery', function() {
            if($(this).hasClass('input-cant') || $(this).hasClass('input-precio')) {
                recalcular($(this).closest('tr'));
            } else {
                calcularGranTotal();
            }
        });

            function recalcular($f) {
            var $select = $f.find('.select-prod');
            var stock = parseInt($select.find(':selected').data('stock')) || 0;
            var c = parseFloat($f.find('.input-cant').val()) || 0;
            var p = parseFloat($f.find('.input-precio').val()) || 0;

            // Solo usamos la cantidad para validar stock
            if ($select.val() !== "" && c > stock) {
                Swal.fire('Stock Insuficiente', 'Máximo: ' + stock, 'warning');
                $f.find('.input-cant').val(stock);
            }

            // EL CAMBIO: El subtotal es SOLO el precio
            $f.find('.subtotal-text').text(p.toFixed(2));
            
            calcularGranTotal();
        }

            function calcularGranTotal() {
                var subtotalProds = 0;
                
                // Suma todos los precios (subtotales) de las filas
                $('.subtotal-text').each(function() { 
                    subtotalProds += parseFloat($(this).text()) || 0; 
                });
                
                var comision = parseFloat($('#comision_delivery').val()) || 0;
                
                // EL CAMBIO: Sumar la comisión al total final
                var totalFinal = subtotalProds; 

                $('#total_final_texto').text(totalFinal.toFixed(2));
                $('#total_final_val').val(totalFinal.toFixed(2));
            }

            // --- LÓGICA TIPO VENTA (ENVIO/DELIVERY) ---
            $(document).on('change', '#tipo_venta', function() {
                if ($(this).val() === 'ENVIO') {
                    $('#div_destino').slideDown();
                    $('#destino').attr('required', true);
                } else {
                    $('#div_destino').slideUp();
                    $('#destino').removeAttr('required').val('');
                }
            });
    }
})(window.jQuery || window.$);


function buscarDistribuidor() {
    var nit = document.getElementById('nit').value;
    if(!nit) return Swal.fire('Atención', 'Ingrese un NIT para buscar', 'warning');

    $.post('<?= base_url("distribuidores/buscar_por_nit") ?>', {nit: nit}, function(res) {
        if(res && res !== null) {
            if(typeof res === 'string') res = JSON.parse(res);
            
            $('#nombre').val(res.nombre);
            $('#celular').val(res.celular);
            $('#destino').val(res.destino);

            // Traer productos del nuevo distribuidor
            $.post('<?= base_url("distribuidores/obtener_productos_por_distribuidor") ?>', {distribuidor_id: res.id}, function(productos) {
                let options = '<option value="">-- Seleccione Producto --</option>';
                productos.forEach(function(p) {
                    options += `<option value="${p.id}" data-precio="${p.precio_venta}" data-stock="${p.stock}">
                                    ${p.nombre} ${p.color} ${p.talla} | Stock: ${p.stock}
                                </option>`;
                });

                // 1. Destruir Select2 en todos los selectores antes de cambiar el HTML
                $('.select-prod').each(function() {
                    if ($(this).data('select2')) {
                        $(this).select2('destroy');
                    }
                });

                // 2. Insertar las nuevas opciones
                $('.select-prod').html(options);
                
                // 3. Reinicializar Select2 uno por uno usando tu función initSelect2
                $('.select-prod').each(function() {
                    // Llamamos a la función que definiste dentro de initModuloVentas
                    // Si initSelect2 no es global, asegúrate de que sea accesible aquí
                    if (typeof initSelect2 === 'function') {
                        initSelect2(this);
                    } else {
                        // Si no es accesible, lo inicializamos directamente:
                        $(this).select2({ theme: 'bootstrap4', width: '100%' });
                    }
                });

                // 4. Resetear valores y recalcular (nombre corregido a calcularGranTotal)
                $('.input-cant').val(1);
                $('.input-precio').val('');
                $('.subtotal-text').text('0.00');
                calcularGranTotal(); 

            }, 'json');

            Swal.fire({
                icon: 'success',
                title: 'Distribuidor actualizado',
                text: res.nombre,
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                icon: 'info',
                title: 'No encontrado',
                text: 'El distribuidor no existe.'
            });
        }
    }, 'json');
}
</script>