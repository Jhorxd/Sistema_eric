<?php $this->load->view('layouts/header'); ?>
<?php $this->load->view('layouts/sidebar'); ?>

<div class="md:ml-64 min-h-screen bg-slate-50 transition-all duration-300">
    
    <div class="p-4 sm:p-6 lg:p-10 w-full">
        
        <header class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6 border-b border-slate-200 pb-6">
            <div>
                <nav class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Dashboard Administrativo</nav>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Estado del Sistema</p>
                    <p class="text-sm font-semibold text-slate-700">Conectado / En Línea</p>
                </div>
                <span class="flex items-center px-4 py-2 rounded-xl text-sm font-bold bg-slate-900 text-white shadow-lg shadow-slate-200">
                    <i class="far fa-clock mr-2 text-blue-400"></i>
                    <?= date('d/m/Y h:i A') ?>
                </span>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-10">
            <div class="bg-white rounded-2xl border border-slate-200 p-8 flex items-center shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex-shrink-0 w-16 h-16 flex items-center justify-center bg-blue-50 rounded-2xl text-blue-600">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </div>
                <div class="ml-6">
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-tighter">Ventas Totales del Mes</p>
                    <h3 class="text-3xl font-black text-slate-900">S/ <?= number_format($total_ventas_mes ?? 0, 2) ?></h3>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-8 flex items-center shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex-shrink-0 w-16 h-16 flex items-center justify-center bg-emerald-50 rounded-2xl text-emerald-600">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
                <div class="ml-6">
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-tighter">Saldos por Cobrar</p>
                    <h3 class="text-3xl font-black text-slate-900">S/ <?= number_format($total_saldos ?? 0, 2) ?></h3>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-8 flex items-center shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-b-4 border-b-red-500">
                <div class="flex-shrink-0 w-16 h-16 flex items-center justify-center bg-red-50 rounded-2xl text-red-600">
                    <i class="fas fa-boxes text-2xl"></i>
                </div>
                <div class="ml-6">
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-tighter text-red-400">Stock Crítico (&lt; 5)</p>
                    <h3 class="text-3xl font-black text-slate-900"><?= $productos_bajo_stock ?? 0 ?> <span class="text-lg text-slate-400 font-light">Items</span></h3>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-10">
            
            <div class="xl:col-span-3 order-2 xl:order-1">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 bg-white flex justify-between items-center">
                        <h3 class="font-black text-slate-800 flex items-center gap-3 text-lg">
                            <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                            Últimos Movimientos de Inventario
                        </h3>
                        <button class="text-xs font-bold text-slate-400 hover:text-blue-600 transition-colors uppercase">Filtros Avanzados</button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-widest border-b border-slate-100">
                                    <th class="px-8 py-4 font-bold">Detalle del Producto</th>
                                    <th class="px-8 py-4 font-bold text-center">Operación</th>
                                    <th class="px-8 py-4 font-bold text-right">Cantidad</th>
                                    <th class="px-8 py-4 font-bold text-right">Stock Resultante</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if(!empty($ultimos_movimientos)): foreach($ultimos_movimientos as $m): ?>
                                <tr class="hover:bg-slate-50 transition-all duration-200 group">
                                    <td class="px-8 py-6">
                                        <div class="font-bold text-slate-800 text-base group-hover:text-blue-600 transition-colors"><?= $m->producto_nombre ?></div>
                                        <div class="text-xs text-slate-400 flex items-center mt-1">
                                            <i class="far fa-calendar-alt mr-1"></i> <?= date('d/m/Y H:i', strtotime($m->fecha_registro)) ?>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter <?= ($m->tipo_movimiento == 'Entrada') ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' ?>">
                                            <i class="fas <?= ($m->tipo_movimiento == 'Entrada') ? 'fa-arrow-up' : 'fa-arrow-down' ?> mr-1.5"></i>
                                            <?= $m->tipo_movimiento ?>
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-right font-mono text-slate-600 font-semibold"><?= number_format($m->cantidad, 2) ?></td>
                                    <td class="px-8 py-6 text-right">
                                        <span class="text-lg font-black text-slate-900"><?= number_format($m->stock_actual, 2) ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center">
                                        <i class="fas fa-inbox text-4xl text-slate-200 mb-4 block"></i>
                                        <p class="text-slate-400 font-medium italic">Sin registros de movimientos recientes.</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 text-right">
                        <a href="<?= base_url('inventario/kardex_peru') ?>" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-black uppercase tracking-tighter">
                            Ir al reporte Kardex completo <i class="fas fa-external-link-alt ml-2 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-1 order-1 xl:order-2">
                <div class="sticky top-8 space-y-6">
                    
                    <div class="bg-slate-900 rounded-2xl p-8 shadow-2xl shadow-blue-200">
                        <h3 class="text-white font-bold text-sm uppercase tracking-[0.2em] mb-6">Accesos Directos</h3>
                        <div class="space-y-4">
                            <a href="<?= base_url('ventas/nueva_cotizacion') ?>" class="flex items-center group bg-white/10 hover:bg-white text-white hover:text-slate-900 p-4 rounded-xl transition-all duration-300 border border-white/10">
                                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-blue-500 text-white mr-4 shadow-lg group-hover:scale-110 transition-transform">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <span class="font-bold tracking-tight">Nueva Venta</span>
                            </a>

                            <a href="<?= base_url('productos/nuevo') ?>" class="flex items-center group bg-white/10 hover:bg-white text-white hover:text-slate-900 p-4 rounded-xl transition-all duration-300 border border-white/10">
                                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-slate-700 text-white mr-4 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-box"></i>
                                </div>
                                <span class="font-bold tracking-tight">Registrar Producto</span>
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Resumen Operativo</p>
                        <div class="space-y-4">
                            <div class="flex justify-between items-end border-b border-slate-50 pb-4">
                                <span class="text-slate-500 text-sm font-medium">Variedad de Stock</span>
                                <span class="text-3xl font-black text-slate-900"><?= $total_items ?? 0 ?> <small class="text-xs font-bold text-slate-400">SKU</small></span>
                            </div>
                            <div class="pt-2">
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-blue-600 h-full w-2/3"></div>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-2 italic">* Datos actualizados según último cierre de almacén.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->load->view('layouts/footer'); ?>