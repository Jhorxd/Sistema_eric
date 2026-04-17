<div class="md:ml-64 min-h-screen bg-slate-50 pt-20">

    <!-- HEADER -->
    <section class="px-4 md:px-8 py-5 md:py-6 border-b bg-white shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 md:gap-6">
            <h1 class="text-xl md:text-3xl font-bold text-slate-800 flex items-center gap-3">
                <i class="fas fa-users text-slate-600"></i>
                Módulo de Clientes
            </h1>
        </div>
    </section>

    <!-- CONTENIDO -->
    <section class="p-4 md:p-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- NUEVO CLIENTE -->
            <div class="bg-white rounded-xl shadow p-4">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-slate-800">
                    <i class="fas fa-user-plus text-blue-500"></i> Nuevo Cliente
                </h3>
                <form action="<?= base_url('clientes/guardar') ?>" method="POST" class="space-y-4">
                    <div>
                        <label class="block mb-1 font-medium text-slate-700">DNI / RUC</label>
                        <input type="text" name="dni" class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Ingrese documento" required maxlength="11">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium text-slate-700">Nombre Completo / Razón Social</label>
                        <input type="text" name="nombre" class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Juan Pérez..." required>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium text-slate-700">Celular</label>
                        <input type="text" name="celular" class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="987654321">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium text-slate-700">Ciudad / Destino de Envío</label>
                        <input type="text" name="destino" class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Ej: Lima, San Borja">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Guardar Cliente
                    </button>
                </form>
            </div>

            <!-- LISTADO DE CLIENTES -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow p-4">
                <h3 class="text-lg font-semibold mb-4 text-slate-800">Listado de Clientes Registrados</h3>
                <div class="w-full overflow-x-auto md:overflow-x-visible">
                    <table id="tabla_clientes" class="min-w-[700px] w-full text-sm text-left divide-y">
                        <thead class="bg-slate-100 text-slate-700 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Documento</th>
                                <th class="px-4 py-3">Nombre</th>
                                <th class="px-4 py-3">Celular</th>
                                <th class="px-4 py-3">Destino</th>
                                <th class="px-4 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <?php if(!empty($clientes)): ?>
                                <?php foreach($clientes as $c): ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 font-bold"><?= $c->dni ?></td>
                                    <td class="px-4 py-3"><?= $c->nombre ?></td>
                                    <td class="px-4 py-3"><?= $c->celular ?></td>
                                    <td class="px-4 py-3"><?= $c->ubicacion ?></td>
                                    <td class="px-4 py-3">
                                        <div class="flex justify-center gap-2">
                                            <button type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded text-sm flex items-center gap-1" onclick="cargarCliente(<?= $c->id ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded text-sm flex items-center gap-1" onclick="eliminarCliente(<?= $c->id ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- MODAL EDITAR CLIENTE -->
<div class="modal fade" id="modalEditarCliente" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-xl shadow-lg">
            <div class="modal-header bg-yellow-500 text-white px-4 py-3 rounded-t-xl flex justify-between items-center">
                <h5 class="font-bold text-lg flex items-center gap-2"><i class="fas fa-user-edit"></i> Editar Cliente</h5>
                <button type="button" class="text-white text-2xl font-bold" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= base_url('clientes/actualizar') ?>" method="POST" class="space-y-4 p-4">
                <input type="hidden" name="id_cliente" id="edit_id">
                <div>
                    <label class="block mb-1 font-medium text-slate-700">DNI / RUC</label>
                    <input type="text" name="dni" id="edit_dni" class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" required maxlength="11">
                </div>
                <div>
                    <label class="block mb-1 font-medium text-slate-700">Nombre Completo / Razón Social</label>
                    <input type="text" name="nombre" id="edit_nombre" class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" required>
                </div>
                <div>
                    <label class="block mb-1 font-medium text-slate-700">Celular</label>
                    <input type="text" name="celular" id="edit_celular" class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                </div>
                <div>
                    <label class="block mb-1 font-medium text-slate-700">Ciudad / Destino</label>
                    <input type="text" name="destino" id="edit_destino" class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                </div>
                <div class="flex justify-between">
                    <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded flex items-center gap-2">
                        <i class="fas fa-sync"></i> Actualizar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Función global (fuera de cualquier ready)
function cargarCliente(id) {
    console.log("Iniciando carga de cliente ID:", id);
    
    // 1. Verificar si jQuery existe
    if (typeof jQuery === 'undefined') {
        alert("Error: jQuery no está cargado.");
        return;
    }

    var url = '<?= base_url("clientes/editar_ajax/") ?>' + id;

    // 2. Petición AJAX
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(c) {
            console.log("Datos recibidos:", c);
            
            // Llenar campos
            $('#edit_id').val(c.id);
            $('#edit_dni').val(c.dni);
            $('#edit_nombre').val(c.nombre);
            $('#edit_celular').val(c.celular);
            $('#edit_destino').val(c.destino);
            
            // 3. Abrir Modal (Intentar de dos formas)
            if (typeof $.fn.modal !== 'undefined') {
                $('#modalEditarCliente').modal('show');
            } else {
                // Si el JS de bootstrap no carga, al menos forzamos el CSS
                $('#modalEditarCliente').addClass('show').css('display', 'block');
                alert("Bootstrap JS no cargó, forzando apertura visual.");
            }
        },
        error: function(xhr, status, error) {
            alert("Error en la red o URL no encontrada. Revisa la consola.");
            console.error("Detalle del error:", error);
            console.error("Respuesta del servidor:", xhr.responseText);
        }
    });
}

function eliminarCliente(id) {
    // Confirmación nativa del navegador
    if (confirm('¿Estás seguro de que deseas eliminar este cliente? Esta acción no se puede deshacer.')) {
        console.log("Eliminando cliente ID:", id);
        
        // Redirigir a la URL de eliminación
        window.location.href = '<?= base_url("clientes/eliminar/") ?>' + id;
    }
}

// Mantener la inicialización de DataTable por separado
document.addEventListener("DOMContentLoaded", function() {
    // Usamos la misma función de reparación forzada para asegurar la carga
    async function inicializarModuloClientes() {
        try {
            // 1. Verificar/Cargar dependencias si fallan
            if (typeof jQuery === 'undefined') {
                await inyectarScript("https://code.jquery.com/jquery-3.6.0.min.js");
            }
            if (!jQuery.fn.DataTable) {
                await inyectarScript("https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js");
                await inyectarScript("https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js");
            }

            const $ = jQuery;
            
            // 2. Limpiar instancia previa si existe
            if ($.fn.DataTable.isDataTable('#tabla_clientes')) {
                $('#tabla_clientes').DataTable().destroy();
            }

            // 3. Inicialización Limpia
            $('#tabla_clientes').DataTable({
                "paging": true,          // Mantiene el paginado
                "searching": false,      // OCULTA EL BUSCADOR
                "lengthChange": false,   // OCULTA EL SELECT "Mostrar X registros"
                "info": true,            // Mantiene el texto de "Mostrando X de Y"
                "autoWidth": false,
                "responsive": true,
                "pageLength": 10,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                }
            });

            console.log("Tabla de Clientes inicializada sin selector de cantidad.");

        } catch (error) {
            console.error("Error crítico en Clientes:", error);
        }
    }

    // Función auxiliar de carga
    function inyectarScript(url) {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = url;
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }

    inicializarModuloClientes();
});


document.addEventListener("DOMContentLoaded", function() {
    // Si hay un mensaje de ERROR (DNI duplicado, etc)
    <?php if($this->session->flashdata('error')): ?>
        Swal.fire({
            icon: 'error',
            title: '¡No se pudo registrar!',
            html: '<?= $this->session->flashdata('error') ?>', // Usamos .html para que se vea la negrita del PHP
            confirmButtonColor: '#3085d6'
        });
    <?php endif; ?>

    // Si hay un mensaje de ÉXITO
    <?php if($this->session->flashdata('success')): ?>
        Swal.fire({
            icon: 'success',
            title: '¡Logrado!',
            text: '<?= $this->session->flashdata('success') ?>',
            timer: 2500,
            showConfirmButton: false
        });
    <?php endif; ?>
});
</script>