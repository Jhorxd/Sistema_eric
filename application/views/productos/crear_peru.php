<?php 
    // Detectamos si es edición o creación
    $es_edicion = isset($producto);
    $titulo = $es_edicion ? "Editar Producto" : "Registrar Producto - Perú";
    $url_action = $es_edicion ? base_url('productos/actualizar') : base_url('productos/guardar_peru');
?>

<div class="md:ml-64 min-h-screen bg-slate-50 pt-20">

    <!-- HEADER -->
    <section class="px-4 md:px-8 py-5 border-b bg-white shadow-sm">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800"><?= $titulo ?></h1>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="px-4 md:px-8 py-6">
        <div class="max-w-6xl mx-auto bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <form action="<?= $url_action ?>" method="post" id="formProducto" class="space-y-6 p-6">
                
                <?php if($es_edicion): ?>
                    <input type="hidden" name="id" id="producto_id" value="<?= $producto->id ?>">
                <?php endif; ?>

                <!-- PRIMERA FILA -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-3">
                        <label class="block font-medium text-slate-700 mb-1">Código/SKU</label>
                        <input type="text" name="codigo" id="codigo" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" 
                               value="<?= $es_edicion ? $producto->codigo : '' ?>" required>
                        <small id="error_codigo" class="text-red-600 hidden">Este código ya existe.</small>
                    </div>
                    <div class="md:col-span-6">
                        <label class="block font-medium text-slate-700 mb-1">Nombre del Producto</label>
                        <input type="text" name="nombre" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" 
                               value="<?= $es_edicion ? $producto->nombre : '' ?>" required>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block font-medium text-slate-700 mb-1">Tipo</label>
                            <select name="tipo" id="tipo" 
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" 
                            onchange="actualizarInterfaz()" required>

                            <option value="corte"
                            <?= ($es_edicion && $producto->tipo == 'corte') ? 'selected' : '' ?>>
                            Producto Corte (Producción)
                            </option>

                            <option value="final"
                            <?= ($es_edicion && $producto->tipo == 'final') ? 'selected' : '' ?>>
                            Producto Final (Prenda)
                            </option>

                            </select>
                    </div>
                </div>

                <!-- SEGUNDA FILA -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-3">
                        <label class="block font-medium text-slate-700 mb-1">Color</label>
                        <input type="text" name="color" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" 
                               value="<?= $es_edicion ? $producto->color : '' ?>">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-medium text-slate-700 mb-1">Talla</label>
                        <select name="talla" id="talla" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200">
                            <option value="">N/A</option>
                            <?php $tallas = ['S', 'M', 'L', 'XL']; ?>
                            <?php foreach($tallas as $t): ?>
                                <option value="<?= $t ?>" <?= ($es_edicion && $producto->talla == $t) ? 'selected' : '' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Detalles -->
                    <div class="md:col-span-2">
                        <label class="block font-medium text-slate-700 mb-1">Detalles</label>
                        <input type="text" name="detalles" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" placeholder="Detalles del producto" value="<?= isset($producto) ? $producto->detalles : '' ?>">
                    </div>
                    
                    <div class="md:col-span-4" id="contenedor_tipo_tela">
                        <label class="block font-medium text-slate-700 mb-1">Tipo de Tela</label>
                        <input type="text" name="tipo_tela" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" 
                               value="<?= $es_edicion ? $producto->tipo_tela : '' ?>">
                    </div>
                </div>

                <!-- TERCERA FILA -->
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div class="md:col-span-3">
                        <label class="block font-medium text-slate-700 mb-1">Stock</label>
                        <input type="number" step="0.01" name="stock" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" 
                               value="<?= $es_edicion ? $producto->stock : '0' ?>"
                               <?= $es_edicion ? 'readonly style="background-color: #e9ecef;"' : '' ?>>
                        <?php if($es_edicion): ?>
                            <small class="text-gray-500"><i class="fas fa-info-circle"></i> Para ajustar stock use el Kardex.</small>
                        <?php endif; ?>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block font-medium text-slate-700 mb-1">Precio Venta</label>
                        <input type="number" step="0.01" name="precio_venta" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" 
                               value="<?= $es_edicion ? $producto->precio_venta : '0' ?>">
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="flex flex-col md:flex-row justify-end gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                        <?= $es_edicion ? 'Actualizar Cambios' : 'Guardar Producto' ?>
                    </button>
                    <a href="<?= base_url('productos') ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded shadow hover:bg-gray-300 text-center">Cancelar</a>
                </div>

            </form>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // 1. Validación visual rápida cuando el usuario sale del campo (blur)
    $('#codigo').on('blur', function() {
        validarCodigoAJAX();
    });

    function validarCodigoAJAX() {
        let codigoValue = $('#codigo').val();
        let idValue = $('#producto_id').val() || '';

        if (codigoValue.trim() === '') return Promise.resolve(false);

        return $.post('<?= base_url("productos/verificar_codigo") ?>', {
            codigo: codigoValue,
            id: idValue
        }).then(function(response) {
            let res = JSON.parse(response);
            if (res.existe) {
                $('#codigo').addClass('is-invalid');
                $('#error_codigo').show();
                return true; 
            } else {
                $('#codigo').removeClass('is-invalid');
                $('#error_codigo').hide();
                return false;
            }
        });
    }

    // 2. BLOQUEO DEFINITIVO EN EL SUBMIT
    $('#formProducto').on('submit', function(e) {
        e.preventDefault(); 
        var form = this;

        let codigoValue = $('#codigo').val();
        let idValue = $('#producto_id').val() || '';

        $.post('<?= base_url("productos/verificar_codigo") ?>', {
            codigo: codigoValue,
            id: idValue
        }, function(response) {
            let res = JSON.parse(response);
            
            if (res.existe) {
                $('#codigo').addClass('is-invalid');
                $('#error_codigo').show();
                Swal.fire('Error', 'El código ingresado ya está registrado. Use uno diferente.', 'error');
            } else {
                form.submit();
            }
        });
    });
});

function actualizarInterfaz() {
    var tipo = document.getElementById("tipo").value;
    var talla = document.getElementById("talla");
    var contenedorTipoTela = document.getElementById("contenedor_tipo_tela");
}
window.onload = actualizarInterfaz;
</script>