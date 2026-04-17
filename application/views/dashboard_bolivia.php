<?php $this->load->view('layouts/header'); ?>
<?php $this->load->view('layouts/sidebar'); ?>

<div class="md:ml-64 min-h-screen bg-slate-50 transition-all duration-300">
    
    <div class="p-4 sm:p-6 lg:p-10 w-full">
        
        <header class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6 border-b border-slate-200 pb-6">
            <div>
                <nav class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Sucursal Internacional</nav>
            </div>
            <div class="flex items-center gap-3">
                <span class="flex items-center px-4 py-2 rounded-xl text-sm font-bold bg-slate-900 text-white shadow-lg shadow-slate-200">
                    <i class="far fa-clock mr-2 text-amber-400"></i>
                    <?= date('d/m/Y h:i A') ?>
                </span>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-10">
            <div class="bg-white rounded-2xl border border-slate-200 p-8 flex items-center shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="flex-shrink-0 w-16 h-16 flex items-center justify-center bg-amber-50 rounded-2xl text-amber-600">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </div>
                <div class="ml-6">
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-tighter">Ventas del Mes (BO)</p>
                    <h3 class="text-3xl font-black text-slate-900 uppercase">Bs. <?= number_format($total_ventas_mes ?? 0, 2) ?></h3>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-8 flex items-center shadow-sm hover:shadow-xl transition-all duration-300 border-b-4 border-b-red-500">
                <div class="flex-shrink-0 w-16 h-16 flex items-center justify-center bg-red-50 rounded-2xl text-red-600">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                <div class="ml-6">
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-tighter text-red-400">Stock Crítico (BO)</p>
                    <h3 class="text-3xl font-black text-slate-900"><?= $productos_bajo_stock ?? 0 ?> <span class="text-lg text-slate-400 font-light">unid.</span></h3>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-8 flex items-center shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="flex-shrink-0 w-16 h-16 flex items-center justify-center bg-slate-100 rounded-2xl text-slate-600">
                    <i class="fas fa-boxes text-2xl"></i>
                </div>
                <div class="ml-6">
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-tighter">Productos Registrados</p>
                    <h3 class="text-3xl font-black text-slate-900"><?= $total_items ?? 0 ?> <span class="text-lg text-slate-400 font-light">SKUs</span></h3>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-10">
            
            <div class="xl:col-span-3 order-2 xl:order-1">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 bg-white flex justify-between items-center">
                        <h3 class="font-black text-slate-800 flex items-center gap-3 text-lg text-uppercase">
                            <span class="w-2 h-6 bg-amber-500 rounded-full"></span>
                            Últimos Movimientos - Bolivia
                        </h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-widest border-b border-slate-100">
                                    <th class="px-8 py-4 font-bold">Fecha de Registro</th>
                                    <th class="px-8 py-4 font-bold">Producto</th>
                                    <th class="px-8 py-4 font-bold text-center">Tipo</th>
                                    <th class="px-8 py-4 font-bold text-right">Cantidad</th>
                                    <th class="px-8 py-4 font-bold text-right">Stock Final</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if(!empty($ultimos_movimientos)): foreach($ultimos_movimientos as $m): ?>
                                <tr class="hover:bg-slate-50 transition-all duration-200 group">
                                    <td class="px-8 py-6 text-sm text-slate-500">
                                        <i class="far fa-calendar-alt mr-1"></i> <?= date('d/m/Y H:i', strtotime($m->fecha_registro)) ?>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="font-bold text-slate-800 text-base group-hover:text-amber-600 transition-colors uppercase leading-tight"><?= $m->producto_nombre ?></div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter <?= ($m->tipo_movimiento == 'Entrada') ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' ?>">
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
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <p class="text-slate-400 font-medium italic">No hay movimientos recientes en la sucursal Bolivia.</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 text-center md:text-right">
                        <a href="<?= base_url('inventario/kardex_bolivia') ?>" class="inline-flex items-center text-sm text-amber-600 hover:text-amber-800 font-black uppercase tracking-tighter transition-colors">
                            Ver Reporte Kardex Bolivia <i class="fas fa-arrow-right ml-2 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-1 order-1 xl:order-2">
                <div class="sticky top-8 space-y-6">
                    
                    <div class="bg-slate-900 rounded-2xl p-8 shadow-2xl shadow-slate-200">
                        <h3 class="text-white font-bold text-sm uppercase tracking-[0.2em] mb-6">Operaciones BO</h3>
                        <div class="space-y-4">
                            <a href="<?= base_url('ventas/nueva_cotizacion') ?>" class="flex items-center group bg-white/10 hover:bg-white text-white hover:text-slate-900 p-4 rounded-xl transition-all duration-300 border border-white/10">
                                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-amber-500 text-white mr-4 shadow-lg group-hover:rotate-12 transition-transform">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <span class="font-bold tracking-tight">Nueva Cotización</span>
                            </a>
                        </div>
                    </div>

                    <div class="bg-amber-50 rounded-2xl border border-amber-100 p-8">
                        <div class="flex items-center gap-3 mb-4 text-amber-700">
                            <i class="fas fa-info-circle"></i>
                            <h4 class="font-black text-xs uppercase">Nota de Almacén</h4>
                        </div>
                        <p class="text-sm text-amber-800/70 font-medium leading-relaxed italic">
                            Los datos y reportes visualizados corresponden únicamente a la sucursal de **Bolivia**. Asegúrese de estar operando bajo la divisa correcta (Bs.).
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->load->view('layouts/footer'); ?>