<style>
    .section-title { border-left: 4px solid #007bff; padding-left: 10px; margin-bottom: 20px; font-weight: bold; color: #333; }
    .total-box { background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #ddd; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05); }
    .table thead th { background-color: #343a40; color: white; border: none; }
    .btn-remove-row { margin-top: 5px; }

    /* Estilos Responsivos para Tabla de Productos */
    @media (max-width: 768px) {
        #tabla-ventas thead { display: none; }
        #tabla-ventas, #tabla-ventas tbody, #tabla-ventas tr, #tabla-ventas td { display: block; width: 100%; }
        #tabla-ventas tr { 
            margin-bottom: 1.5rem; 
            border: 1px solid #e2e8f0; 
            border-radius: 1rem; 
            padding: 1rem; 
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        #tabla-ventas td { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 0.75rem 0; 
            border-bottom: 1px solid #f1f5f9;
        }
        #tabla-ventas td:last-child { border-bottom: none; }
        #tabla-ventas td::before {
            content: attr(data-label);
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.65rem;
            color: #64748b;
            letter-spacing: 0.05em;
        }
        .select2-container { width: 100% !important; }
        .input-cant, .input-precio { width: 120px !important; text-align: right; }
    }
</style>


<div class="md:ml-64 min-h-screen bg-slate-50 transition-all duration-300">
    <div class="p-4 sm:p-6 lg:p-10 w-full">
        
        <header class="mb-8 border-b border-slate-200 pb-6">
            <h1 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                <i class="fas fa-truck-loading text-indigo-600"></i> 
                Registro de Pedido - Bolivia
            </h1>
        </header>

        <form action="<?= base_url('ventas_bolivia/guardar_cotizacion') ?>" method="POST" id="formVenta">
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
                
                <div class="xl:col-span-4 space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
                            <h5 class="text-sm font-black text-indigo-700 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-user-tag"></i> Información del Distribuidor
                            </h5>
                        </div>
                        <div class="p-6 space-y-4">
                            <?php if (isset($distribuidor_logueado)): ?>
                                <input type="hidden" name="id_distribuidor" value="<?= $distribuidor_logueado->id ?>">
                            <?php endif; ?>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">NIT</label>
                                <div class="flex gap-2">
                                    <input type="text" name="nit" id="nit" 
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all" 
                                        placeholder="Ingrese NIT..." 
                                        value="<?= isset($distribuidor_logueado) ? $distribuidor_logueado->nit : '' ?>"
                                        <?= isset($distribuidor_logueado) ? 'readonly' : '' ?>
                                        required>
                                    <?php if (!isset($distribuidor_logueado)): ?>
                                        <button type="button" onclick="buscarDistribuidor()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl transition-colors shadow-md shadow-indigo-100">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nombre Completo / Razón Social</label>
                                <input type="text" name="nombre" id="nombre" 
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none" 
                                    value="<?= isset($distribuidor_logueado) ? $distribuidor_logueado->nombre : '' ?>"
                                    <?= isset($distribuidor_logueado) ? 'readonly' : '' ?>
                                    required placeholder="Nombre del distribuidor">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Celular</label>
                                    <input type="text" name="celular" id="celular" 
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm" 
                                        value="<?= isset($distribuidor_logueado) ? $distribuidor_logueado->celular : '' ?>"
                                        <?= isset($distribuidor_logueado) ? 'readonly' : '' ?>
                                        placeholder="70000000">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Ciudad</label>
                                    <input type="text" name="ubicacion" id="ubicacion" 
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm" 
                                        value="<?= isset($distribuidor_logueado) ? $distribuidor_logueado->destino : '' ?>"
                                        <?= isset($distribuidor_logueado) ? 'readonly' : '' ?>
                                        placeholder="Ej: La Paz">
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
                                    <option value="DELIVERY">DELIVERY</option>
                                    <option value="ENVIO">ENVIO (Provincias)</option>
                                </select>
                            </div>

                            <div id="div_destino" style="display: none;">
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Destino (Dirección/Agencia)</label>
                                <input type="text" name="destino" id="destino" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-blue-500" placeholder="Calle, Nro o Nombre de Agencia">
                            </div>

                             <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Celular cliente</label>
                                <input type="text" name="celular_cliente" id="celular_cliente" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm" placeholder="70000000">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="panel-productos" class="xl:col-span-8 space-y-6 <?= isset($distribuidor_logueado) ? '' : 'hidden' ?>">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-8 py-4 sm:py-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <h3 class="font-black text-slate-800 text-lg flex items-center gap-3 italic">
                                <i class="fas fa-shopping-basket text-slate-400 font-normal"></i> Productos en Pedido
                            </h3>
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                                <input type="datetime-local" name="fecha" class="text-xs sm:text-sm font-bold bg-slate-100 border-none rounded-lg px-3 py-2" value="<?= date('Y-m-d\TH:i') ?>">
                                <button type="button" id="btn-add-producto" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest px-6 py-3 rounded-xl transition-all shadow-lg shadow-emerald-100 flex items-center justify-center gap-2">
                                    <i class="fas fa-plus"></i> AGREGAR PRODUCTO
                                </button>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse" id="tabla-ventas">
                                <thead>
                                    <tr class="bg-slate-100 text-slate-800 text-base uppercase tracking-tight border-b-2 border-slate-200">
                                        <th class="px-8 py-5 font-black italic">Descripción del Producto</th>
                                        <th class="px-4 py-5 font-black w-32 text-center">Cant.</th>
                                        <th class="px-4 py-5 font-black w-48 text-right">Precio Unit.</th>
                                        <th class="px-4 py-5 font-black w-40 text-right bg-slate-200/50">Subtotal</th>
                                        <th class="px-8 py-5 w-16"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <tr class="fila-venta">
                                        <td class="px-8 py-5" data-label="Producto">
                                            <select name="producto_id[]" id="primer-select-prod" class="w-full bg-transparent border-b-2 border-slate-100 py-2 focus:border-indigo-500 outline-none text-sm font-semibold select-prod" required>
                                                <?php if (isset($distribuidor_logueado)): ?>
                                                    <option value="">-- Seleccione Producto --</option>
                                                    <?php foreach($productos as $p): ?>
                                                        <option value="<?= $p->id ?>" data-precio="<?= $p->precio_venta ?>" data-stock="<?= $p->stock ?>">
                                                            <?= $p->nombre ?> <?= $p->color ?> <?= $p->talla ?> | Stock: <?= $p->stock ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <option value="">-- Busque un distribuidor primero --</option>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                        <td class="px-4 py-5" data-label="Cantidad">
                                            <input type="number" name="cant[]" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2 py-2 text-center font-bold input-cant" value="1" min="1">
                                        </td>
                                        <td class="px-4 py-5 text-right" data-label="Precio Unit.">
                                            <div class="flex items-center justify-end">
                                                <span class="text-slate-400 font-bold mr-1 text-xs">Bs.</span>
                                                <input type="number" name="precio[]" class="w-24 bg-slate-50 border border-slate-200 rounded-lg px-2 py-2 text-right font-bold input-precio" step="0.01">
                                            </div>
                                        </td>
                                        <td class="px-4 py-5 text-right font-mono font-black text-slate-900" data-label="Subtotal">
                                            Bs. <span class="subtotal-text text-lg">0.00</span>
                                        </td>
                                        <td class="px-8 py-5 text-center" data-label="Acción">
                                            <button type="button" class="text-slate-400 hover:text-red-500 btn-remove-row flex items-center gap-2">
                                                <i class="fas fa-times-circle text-lg"></i>
                                                <span class="md:hidden text-xs font-bold uppercase">Quitar</span>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="bg-slate-900 p-8">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                                <div class="space-y-3">
                                    <div class="flex justify-between text-slate-400 font-medium">
                                        <span>Subtotal Productos:</span>
                                        <span class="text-white">Bs. <span id="subtotal_productos_texto">0.00</span></span>
                                    </div>
                                    <div class="flex justify-between items-center text-slate-400 font-medium">
                                        <span>Comisión Delivery:</span>
                                        <div class="flex items-center gap-2 bg-slate-800 rounded-lg px-2 py-1">
                                            <span class="text-[10px]">Bs.</span>
                                            <input type="number" name="comision_delivery" id="comision_delivery" class="bg-transparent border-none text-white text-right w-16 outline-none p-0" step="0.01" value="0.00">
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center text-slate-400 font-medium">
                                        <span>Transferencia alfredo:</span>
                                        <div class="flex items-center gap-2 bg-slate-800 rounded-lg px-2 py-1">
                                            <span class="text-[10px]">Bs.</span>
                                            <input type="number" name="alfredo" id="alfredo" class="bg-transparent border-none text-white text-right w-16 outline-none p-0" step="0.01" value="0.00">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-center lg:text-right border-t lg:border-t-0 lg:border-l border-slate-800 pt-6 lg:pt-0 lg:pl-8">
                                    <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mb-1">Total General</p>
                                    <h3 class="text-4xl sm:text-5xl font-black text-white italic tracking-tighter">Bs. <span id="total_final_texto">0.00</span></h3>
                                    <input type="hidden" name="total_final" id="total_final_val">
                                </div>
                            </div>
                        </div>

                        <div class="px-8 py-6 bg-white flex justify-end">
                            <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-slate-900 text-white font-black uppercase tracking-[0.15em] text-sm px-10 py-4 rounded-2xl transition-all shadow-xl shadow-indigo-100 flex items-center justify-center gap-3">
                                <i class="fas fa-save"></i> Registrar Pedido
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

    if (typeof jQuery === 'undefined') {
        cargarScript("https://code.jquery.com/jquery-3.6.0.min.js", function() {
            verificarSelect2();
        });
    } else {
        verificarSelect2();
    }

    function verificarSelect2() {
        if (typeof jQuery.fn.select2 === 'undefined') {
            cargarScript("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js", function() {
                initModuloVentas(jQuery);
            });
        } else {
            initModuloVentas(jQuery);
        }
    }

function initModuloVentas($) {
    console.log("⚙️ Inicializando Módulo de Ventas (Stock, Comisión y Exclusión)...");

    function aplicarSelect2(selector = '.select-prod') {
        $(selector).select2({
            theme: 'bootstrap4',
            placeholder: "-- Seleccione Producto --",
            allowClear: true,
            width: '100%'
        });
    }

    // --- NUEVA FUNCIÓN: EVITAR PRODUCTOS DUPLICADOS ---
    function actualizarDisponibilidadProductos() {
        var seleccionados = [];

        // 1. Recolectar IDs seleccionados
        $('.select-prod').each(function() {
            var val = $(this).val();
            if (val && val !== "") seleccionados.push(val.toString());
        });

        // 2. Deshabilitar opciones en los otros selects
        $('.select-prod').each(function() {
            var $select = $(this);
            var miValor = $select.val() ? $select.val().toString() : "";

            $select.find('option').each(function() {
                var optVal = $(this).val() ? $(this).val().toString() : "";
                if (optVal !== "") {
                    // Si el producto ya está en otra fila, lo bloqueamos en esta
                    if (seleccionados.includes(optVal) && optVal !== miValor) {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                }
            });

            // Forzar a Select2 a refrescar la vista
            if ($select.data('select2')) {
                $select.trigger('change.select2');
            }
        });
    }

    aplicarSelect2();

    // --- FUNCIÓN DE CÁLCULO MAESTRO ---
    function calcularTotales() {

        var subtotalProductos = 0;
        $('.subtotal-text').each(function() {
            subtotalProductos += parseFloat($(this).text()) || 0;
        });

        var comisionDelivery = parseFloat($('#comision_delivery').val()) || 0;
        var alfredo = parseFloat($('#alfredo').val()) || 0;

        var totalGeneral = subtotalProductos - alfredo;

        $('#subtotal_productos_texto').text(subtotalProductos.toFixed(2));
        $('#total_final_texto').text(totalGeneral.toFixed(2));
        $('#total_final_val').val(totalGeneral.toFixed(2));
    }

    $(document).on('input', '#alfredo', function() {
        calcularTotales();
    });

    // --- AGREGAR PRODUCTO (CON EXCLUSIÓN) ---
    $(document).on('click', '#btn-add-producto', function(e) {
        e.preventDefault();
        var $tbody = $('#tabla-ventas tbody');
        var $filaMolde = $tbody.find('.fila-venta').first();

        if ($filaMolde.find('.select-prod').data('select2')) {
            $filaMolde.find('.select-prod').select2('destroy');
        }

        var $nuevaFila = $filaMolde.clone();
        aplicarSelect2($filaMolde.find('.select-prod')); 

        $nuevaFila.find('.select2-container').remove(); 
        $nuevaFila.find('select').removeClass('select2-hidden-accessible').removeAttr('data-select2-id').val('');
        // Importante: habilitar opciones en el clon por si el molde tenía algo bloqueado
        $nuevaFila.find('select option').prop('disabled', false);
        
        $nuevaFila.find('input').val('');
        $nuevaFila.find('.input-cant').val(1);
        $nuevaFila.find('.subtotal-text').text('0.00');

        $tbody.append($nuevaFila);
        aplicarSelect2($nuevaFila.find('.select-prod')); 
        
        // Sincronizar después de agregar
        actualizarDisponibilidadProductos();
    });

    // --- EVENTOS DE CAMBIO ---
    $(document).on('change', '.select-prod', function() {
        var $fila = $(this).closest('tr');
        var precio = $(this).find(':selected').data('precio') || 0;
        $fila.find('.input-precio').val(parseFloat(precio).toFixed(2));
        
        // Sincronizar disponibles al cambiar selección
        actualizarDisponibilidadProductos();
        actualizarSubtotalFila($fila);
    });

    $(document).on('input', '.input-cant, .input-precio', function() {
        actualizarSubtotalFila($(this).closest('tr'));
    });

    $(document).on('click', '.btn-remove-row', function() {
        if ($('.fila-venta').length > 1) {
            $(this).closest('tr').remove();
            actualizarDisponibilidadProductos(); // Liberar producto
            calcularTotales();
        } else {
            Swal.fire('Atención', 'El pedido debe tener al menos un producto.', 'warning');
        }
    });

    function actualizarSubtotalFila($fila) {
        var $select = $fila.find('.select-prod');
        var $inputCant = $fila.find('.input-cant');
        
        var stockDisponible = parseInt($select.find(':selected').data('stock')) || 0;
        var cant = parseFloat($inputCant.val()) || 0;
        var precio = parseFloat($fila.find('.input-precio').val()) || 0;

        if ($select.val() !== "" && cant > stockDisponible) {
            Swal.fire({
                icon: 'warning',
                title: 'Stock Insuficiente',
                text: 'La cantidad se ajustó al máximo: ' + stockDisponible,
                confirmButtonColor: '#3085d6'
            });
            cant = stockDisponible; 
            $inputCant.val(cant);
        }

        var subtotal = precio;
        $fila.find('.subtotal-text').text(subtotal.toFixed(2));
        calcularTotales();
    }

    $('#formVenta').on('submit', function(e) {
        var total = parseFloat($('#total_final_val').val()) || 0;
        var hayErrorStock = false;

        $('.fila-venta').each(function() {
            var s = parseInt($(this).find('.select-prod :selected').data('stock')) || 0;
            var c = parseInt($(this).find('.input-cant').val()) || 0;
            if (c > s) hayErrorStock = true;
        });

        if (total <= 0) {
            e.preventDefault();
            Swal.fire('Error', 'El total de la venta no puede ser 0.00', 'error');
            return false;
        }

        if (hayErrorStock) {
            e.preventDefault();
            Swal.fire('Error de Stock', 'Revise las cantidades antes de continuar.', 'error');
            return false;
        }
    });

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
});
function buscarDistribuidor() {
    var nit = document.getElementById('nit').value;
    if(!nit) return alert("Ingrese un NIT para buscar");

    $.post('<?= base_url("distribuidores/buscar_por_nit") ?>', {nit: nit}, function(res) {
        if(res && res !== null) {
            // 1. Llenar datos del distribuidor
            $('#nombre').val(res.nombre);
            $('#celular').val(res.celular);
            $('#ubicacion').val(res.destino);
            
            // 2. BUSCAR PRODUCTOS DE ESTE DISTRIBUIDOR
            $.post('<?= base_url("distribuidores/obtener_productos_por_distribuidor") ?>', {distribuidor_id: res.id}, function(productos) {
                let options = '<option value="">-- Seleccione Producto --</option>';
                
                productos.forEach(function(p) {
                    options += `<option value="${p.id}" data-precio="${p.precio_venta}" data-stock="${p.stock}">
                                    ${p.nombre} ${p.color} ${p.talla} ${p.detalles} | Stock: ${p.stock}
                                </option>`;
                });

                // Llenamos todos los selects de productos que existan
                $('.select-prod').html(options);
                
                // 3. Mostramos el panel
                $('#panel-productos').removeClass('hidden').fadeIn();
            }, 'json');

            Swal.fire({ icon: 'success', title: 'Distribuidor encontrado', text: res.nombre, timer: 1500, showConfirmButton: false });
        } else {
            Swal.fire({ icon: 'info', title: 'No encontrado', text: 'El distribuidor no existe.' });
        }
    }, 'json');
}
</script>