<div class="md:ml-64 min-h-screen bg-slate-50 pt-20">

    <!-- HEADER -->
    <section class="px-4 md:px-8 py-5 border-b bg-white shadow-sm">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">
                <?= isset($producto) ? 'Editar Producto' : 'Nuevo Producto' ?> (Bolivia)
            </h1>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="px-4 md:px-8 py-6">
        <div class="max-w-6xl mx-auto bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <form action="<?= isset($producto) ? base_url('productos_bolivia/actualizar') : base_url('productos_bolivia/guardar_bolivia') ?>" method="POST" id="formBolivia" class="space-y-6 p-6">
                
                <?php if(isset($producto)): ?>
                    <input type="hidden" name="id" id="producto_id" value="<?= $producto->id ?>">
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

                    <!-- Código -->
                    <div class="md:col-span-4">
                        <label class="block font-medium text-slate-700 mb-1">Código</label>
                        <input type="text" name="codigo" id="codigo_bol" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" placeholder="Ej: POL-001" value="<?= isset($producto) ? $producto->codigo : '' ?>" required>
                        <small id="error_codigo_bol" class="text-red-600 hidden">Este código ya existe en los registros de Bolivia.</small>
                    </div>

                    <!-- Nombre -->
                    <div class="md:col-span-8">
                        <label class="block font-medium text-slate-700 mb-1">Nombre del Producto</label>
                        <input type="text" name="nombre" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" placeholder="Nombre completo" value="<?= isset($producto) ? $producto->nombre : '' ?>" required>
                    </div>

                    <!-- Color -->
                    <div class="md:col-span-4">
                        <label class="block font-medium text-slate-700 mb-1">Color</label>
                        <input type="text" name="color" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" value="<?= isset($producto) ? $producto->color : '' ?>">
                    </div>

                    <!-- Talla -->
                    <div class="md:col-span-4">
                        <label class="block font-medium text-slate-700 mb-1">Talla</label>
                        <select name="talla" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200">
                            <option value="">Seleccionar Talla</option>
                            <?php 
                                $tallas = ['S', 'M', 'L', 'XL', 'XXL', 'Estándar'];
                                foreach($tallas as $t): 
                                    $selected = (isset($producto) && $producto->talla == $t) ? 'selected' : '';
                            ?>
                                <option value="<?= $t ?>" <?= $selected ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Detalles -->
                    <div class="md:col-span-4">
                        <label class="block font-medium text-slate-700 mb-1">Detalles</label>
                        <input type="text" name="detalles" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" placeholder="Detalles del producto" value="<?= isset($producto) ? $producto->detalles : '' ?>">
                    </div>


                    <!-- Stock -->
                    <div class="md:col-span-6">
                        <label class="block font-medium text-slate-700 mb-1">Stock <?= isset($producto) ? '(Actual)' : 'Inicial' ?></label>
                        <input type="number" step="0.01" name="stock" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" 
                               value="<?= isset($producto) ? $producto->stock : '0' ?>" 
                               <?= isset($producto) ? 'readonly style="background-color: #e9ecef;"' : '' ?> required>
                        <?php if(isset($producto)): ?>
                            <small class="text-gray-500"><i class="fas fa-lock"></i> Para modificar stock use el Kardex Bolivia.</small>
                        <?php endif; ?>
                    </div>

                    <!-- Precio -->
                    <div class="md:col-span-6">
                        <label class="block font-medium text-slate-700 mb-1">Precio de Venta (Bs.)</label>
                        <input type="number" step="0.01" name="precio_venta" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" value="<?= isset($producto) ? $producto->precio_venta : '0.00' ?>" required>
                    </div>

                <div class="md:col-span-12 py-2">
                    <label class="block font-medium text-slate-700 mb-2">Distribuidor / Proveedor</label>
                    <select name="id_distribuidor" id="id_distribuidor" class="w-full select2-bolivia" required>
                        <option value="">Seleccione un distribuidor</option>
                        <?php foreach($distribuidores as $d): ?>
                            <?php $selected = (isset($producto) && $producto->id_distribuidor == $d->id) ? 'selected' : ''; ?>
                            <option value="<?= $d->id ?>" <?= $selected ?>>
                                <?= $d->nombre ?> (NIT: <?= $d->nit ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                </div>

                <!-- FOOTER -->
                <div class="flex flex-col md:flex-row justify-end gap-2">
                    <a href="<?= base_url('productos_bolivia') ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded shadow hover:bg-gray-300 text-center">Cancelar</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 flex items-center gap-1">
                        <i class="fas fa-save"></i> <?= isset($producto) ? 'Guardar Cambios' : 'Registrar Producto' ?>
                    </button>
                </div>

            </form>
        </div>
    </section>
</div>

<style>
    /* 1. Contenedor principal */
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
        height: 42px !important; /* Ajustado ligeramente a 42px para match con Tailwind */
        display: flex !important;
        align-items: center !important; /* Centrado vertical */
        transition: border-color 0.2s;
    }

    /* 2. EL ARREGLO DEL TEXTO: Quitamos el line-height y el padding-top por defecto */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1e293b !important;
        line-height: normal !important; /* Importante: normal para que no empuje hacia abajo */
        padding-left: 12px !important;
        padding-top: 0px !important; 
        margin-top: 0px !important;
        display: block;
        width: 100%;
    }

    /* 3. Centrado de la flecha */
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100% !important; /* Que ocupe todo el alto para centrar el icono */
        right: 10px !important;
        display: flex;
        align-items: center;
    }

    /* 4. Estilo de Foco */
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #3b82f6 !important;
        outline: none;
    }

    /* 5. Dropdown */
    .select2-dropdown {
        border-color: #d1d5db !important;
        border-radius: 0.375rem !important;
        z-index: 9999;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Usamos una función anónima para proteger el scope
    (function($) {
        $(document).ready(function() {
            console.log("Iniciando intento de rescate de Select2...");

            var intento = 0;
            var maxIntentos = 50; // 5 segundos máximo

            var initSelect = setInterval(function() {
                intento++;
                
                // Verificamos si la función existe en el objeto jQuery actual
                if (typeof $.fn.select2 !== 'undefined') {
                    console.log("✅ ¡Select2 encontrado!");
                    
                    $('.select2-bolivia').select2({
                        placeholder: "Seleccione un distribuidor",
                        allowClear: true,
                        width: '100%'
                    });

                    clearInterval(initSelect);
                } else {
                    console.warn("⏳ Intento " + intento + ": Select2 aún no disponible...");
                }

                if (intento >= maxIntentos) {
                    console.error("❌ Error fatal: Select2 no cargó después de 5 segundos.");
                    clearInterval(initSelect);
                }
            }, 100);
        });
    })(jQuery); // Le pasamos el objeto jQuery explícitamente
</script>
<script>
$(document).ready(function() {
    
    // 1. Feedback visual al salir del campo
    $('#codigo_bol').on('blur', function() {
        let codigo = $(this).val().trim();
        let id = $('#producto_id').val() || '';

        if (codigo !== '') {
            $.post('<?= base_url("productos_bolivia/verificar_codigo") ?>', { codigo: codigo, id: id }, function(data) {
                let res = JSON.parse(data);
                if (res.existe) {
                    $('#codigo_bol').addClass('is-invalid');
                    $('#error_codigo_bol').show();
                } else {
                    $('#codigo_bol').removeClass('is-invalid');
                    $('#error_codigo_bol').hide();
                }
            });
        }
    });

    // 2. Validación INFALIBLE al enviar
    $('#formBolivia').on('submit', function(e) {
        e.preventDefault(); 
        
        let form = this;
        let codigo = $('#codigo_bol').val().trim();
        let id = $('#producto_id').val() || '';

        $.post('<?= base_url("productos_bolivia/verificar_codigo") ?>', { 
            codigo: codigo, 
            id: id 
        }, function(data) {
            let res = JSON.parse(data);
            if (res.existe) {
                $('#codigo_bol').addClass('is-invalid');
                $('#error_codigo_bol').show();
                Swal.fire('Código Duplicado', 'El código "' + codigo + '" ya existe en Bolivia. Intente con otro.', 'error');
            } else {
                form.submit();
            }
        });
    });
});
</script>