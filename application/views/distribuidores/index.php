<div class="md:ml-64 min-h-screen bg-slate-50 pt-20">

    <!-- HEADER -->
    <section class="px-4 md:px-8 py-5 md:py-6 border-b bg-white shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 md:gap-6">
            <h1 class="text-xl md:text-3xl font-bold text-slate-800 flex items-center gap-3">
                <i class="fas fa-truck-loading text-slate-600"></i>
                Módulo de Distribuidores (Bolivia)
            </h1>
        </div>
    </section>

    <!-- CONTENIDO -->
    <section class="p-4 md:p-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- NUEVO DISTRIBUIDOR -->
            <div class="bg-white rounded-xl shadow p-4">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-slate-800">
                    <i class="fas fa-user-plus text-blue-500"></i> Nuevo Distribuidor
                </h3>
                <form action="<?= base_url('distribuidores/guardar') ?>" method="POST" class="space-y-4">
                    <div>
                        <label class="block mb-1 font-medium text-slate-700">NIT / CI</label>
                        <input type="text" name="nit" class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Ingrese NIT o CI" required maxlength="15">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium text-slate-700">Razón Social / Nombre</label>
                        <input type="text" name="nombre" class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Ej: Distribuidora Oriente" required>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium text-slate-700">Celular / WhatsApp</label>
                        <input type="text" name="celular" class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="70012345">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium text-slate-700">Ciudad / Departamento</label>
                        <input type="text" name="destino" class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Ej: Santa Cruz, La Paz">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Guardar Distribuidor
                    </button>
                </form>
            </div>

            <!-- LISTADO DE DISTRIBUIDORES -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow p-4">
                <h3 class="text-lg font-semibold mb-4 text-slate-800">Listado de Distribuidores Registrados</h3>
                <div class="w-full overflow-x-auto md:overflow-x-visible">
                    <table id="tabla_distribuidores" class="min-w-[700px] w-full text-sm text-left divide-y">
                        <thead class="bg-slate-100 text-slate-700 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-4 py-3">NIT / CI</th>
                                <th class="px-4 py-3">Nombre / Razón Social</th>
                                <th class="px-4 py-3">Celular</th>
                                <th class="px-4 py-3">Destino</th>
                                <th class="px-4 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <?php if(!empty($distribuidores)): ?>
                                <?php foreach($distribuidores as $c): ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 font-bold"><?= $c->nit ?></td>
                                    <td class="px-4 py-3"><?= $c->nombre ?></td>
                                    <td class="px-4 py-3"><?= $c->celular ?></td>
                                    <td class="px-4 py-3"><?= $c->destino ?></td>
                                    <td class="px-4 py-3 text-center">
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

<!-- MODAL EDITAR DISTRIBUIDOR -->
<div class="modal fade" id="modalEditarDistribuidor" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-xl shadow-lg">
            <div class="modal-header bg-yellow-500 text-white px-4 py-3 rounded-t-xl flex justify-between items-center">
                <h5 class="font-bold text-lg flex items-center gap-2"><i class="fas fa-user-edit"></i> Editar Distribuidor</h5>
                <button type="button" class="text-white text-2xl font-bold" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= base_url('distribuidores/actualizar') ?>" method="POST" class="space-y-4 p-4">
                <input type="hidden" name="id_distribuidor" id="edit_id">
                <div>
                    <label class="block mb-1 font-medium text-slate-700">NIT / CI</label>
                    <input type="text" name="nit" id="edit_dni" class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" required maxlength="15">
                </div>
                <div>
                    <label class="block mb-1 font-medium text-slate-700">Razón Social / Nombre</label>
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
function cargarCliente(id) {
    if (typeof jQuery === 'undefined') return;

    // Cambiamos la URL al controlador de Bolivia
    var url = '<?= base_url("distribuidores/editar_ajax/") ?>' + id;

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(c) {
            $('#edit_id').val(c.id);
            $('#edit_dni').val(c.nit); // Usamos dni_nit
            $('#edit_nombre').val(c.nombre);
            $('#edit_celular').val(c.celular);
            $('#edit_destino').val(c.destino);
            
            $('#modalEditarDistribuidor').modal('show');
        },
        error: function() {
            alert("Error al obtener los datos del distribuidor.");
        }
    });
}

function eliminarCliente(id) {
    if (confirm('¿Deseas eliminar este distribuidor del sistema de Bolivia?')) {
        window.location.href = '<?= base_url("distribuidores/eliminar/") ?>' + id;
    }
}

document.addEventListener("DOMContentLoaded", function() {
    console.log("Iniciando DataTables para Distribuidores...");

    async function inicializarTablaDistribuidores() {
        try {
            // Verificación y carga de librerías (por si no están en el DOM todavía)
            if (typeof jQuery === 'undefined') {
                await inyectarScript("https://code.jquery.com/jquery-3.6.0.min.js");
            }
            if (!jQuery.fn.DataTable) {
                await inyectarScript("https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js");
                await inyectarScript("https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js");
            }

            const $ = jQuery;
            const tableID = '#tabla_clientes';

            // Destruir si ya existe para evitar errores de reinicialización
            if ($.fn.DataTable.isDataTable(tableID)) {
                $(tableID).DataTable().destroy();
            }

            // Inicialización con tus preferencias
            $(tableID).DataTable({
                "paging": true,
                "searching": false,
                "lengthChange": false, // QUITA EL SELECT DE "MOSTRAR X REGISTROS"
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 10,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                }
            });

            console.log("Tabla de Distribuidores lista y sin selector de cantidad.");

        } catch (error) {
            console.error("Error al cargar la tabla de distribuidores:", error);
        }
    }

    // Auxiliar para inyectar si es necesario
    function inyectarScript(url) {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = url;
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }

    inicializarTablaDistribuidores();
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