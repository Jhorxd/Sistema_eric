<?php $this->load->view('layouts/header'); ?>
<?php $this->load->view('layouts/sidebar'); ?>

<div class="md:ml-64 min-h-screen bg-slate-50 transition-all duration-300 pt-16">
    
    <div class="p-4 sm:p-6 lg:p-10 w-full">
        
        <header class="mb-8">
            <nav class="text-[10px] font-black text-blue-500 uppercase tracking-[0.3em] mb-2">Sucursal Internacional</nav>
            <h1 class="text-4xl font-black text-slate-800 tracking-tight">Panel de Control <span class="text-blue-600">Bolivia</span></h1>
        </header>

        <!-- FILTROS PREMIUM -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-2 mb-10">
            <form action="<?= base_url('dashboard') ?>" method="GET" class="flex flex-col lg:flex-row lg:items-center gap-2">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 flex-grow gap-2 p-2">
                    <div class="relative group">
                        <label class="absolute -top-2 left-4 bg-white px-2 text-[9px] font-black text-slate-400 uppercase tracking-widest z-10 group-focus-within:text-blue-500 transition-colors">Distribuidor</label>
                        <select name="distribuidor" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-xs font-bold outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none cursor-pointer text-slate-700">
                            <option value="">-- Todos --</option>
                            <option value="alfredo" <?= ($f_dist == 'alfredo') ? 'selected' : '' ?>>&#128100; ALFREDO (Cobros)</option>
                            <?php foreach($distribuidores as $d): ?>
                                <option value="<?= $d->id ?>" <?= ($f_dist == $d->id) ? 'selected' : '' ?>><?= $d->nombre ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none text-[10px]"></i>
                    </div>

                    <div class="relative group">
                        <label class="absolute -top-2 left-4 bg-white px-2 text-[9px] font-black text-slate-400 uppercase tracking-widest z-10 group-focus-within:text-blue-500 transition-colors">Producto (Modelo)</label>
                        <select name="producto_nombre" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3.5 text-xs font-bold outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none cursor-pointer text-slate-700">
                            <option value="">-- Todos los Modelos --</option>
                            <?php foreach($lista_productos_nombres as $p): ?>
                                <option value="<?= $p->nombre ?>" <?= ($f_prod_nombre == $p->nombre) ? 'selected' : '' ?>><?= $p->nombre ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none text-[10px]"></i>
                    </div>

                    <div class="relative group">
                        <label class="absolute -top-2 left-4 bg-white px-2 text-[9px] font-black text-slate-400 uppercase tracking-widest z-10 group-focus-within:text-blue-500 transition-colors">Desde</label>
                        <input type="date" name="fecha_inicio" value="<?= $f_inicio ?>" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3 text-xs font-bold outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-600">
                    </div>

                    <div class="relative group">
                        <label class="absolute -top-2 left-4 bg-white px-2 text-[9px] font-black text-slate-400 uppercase tracking-widest z-10 group-focus-within:text-blue-500 transition-colors">Hasta</label>
                        <input type="date" name="fecha_fin" value="<?= $f_fin ?>" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3 text-xs font-bold outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-600">
                    </div>
                </div>

                <div class="flex items-center gap-2 p-2 lg:pr-4">
                    <button type="submit" class="w-full lg:w-auto bg-slate-900 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg shadow-slate-200 active:scale-95 flex items-center justify-center gap-3">
                        <i class="fas fa-sync-alt text-[10px]"></i> Actualizar
                    </button>
                    <?php if($f_dist || $f_prod_nombre || $f_inicio != date('Y-m-01')): ?>
                        <a href="<?= base_url('dashboard') ?>" class="bg-red-50 text-red-500 w-12 h-12 flex items-center justify-center rounded-2xl hover:bg-red-500 hover:text-white transition-all group" title="Limpiar Filtros">
                            <i class="fas fa-trash-alt group-hover:rotate-12 transition-transform"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- STATS GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
            <!-- Ventas Mes -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 flex items-center justify-center bg-blue-50 rounded-xl text-blue-600">
                        <i class="fas fa-shopping-cart text-xl"></i>
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Mes Actual</span>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter mb-1">Ventas Totales</p>
                <h3 class="text-2xl font-black text-slate-900">Bs. <?= number_format($total_ventas_mes ?? 0, 2) ?></h3>
            </div>

            <!-- Pendiente Depósito (Semanal) - SE OCULTA SI ES ALFREDO -->
            <?php if($f_dist != 'alfredo'): ?>
            <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col shadow-sm hover:shadow-xl transition-all duration-300 border-l-4 border-l-emerald-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 flex items-center justify-center bg-emerald-50 rounded-xl text-emerald-600">
                        <i class="fas fa-money-bill-wave text-xl"></i>
                    </div>
                    <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Por Depositar</span>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter mb-1">Pendiente Semanal</p>
                <h3 class="text-2xl font-black text-emerald-600">Bs. <?= number_format($total_pendiente ?? 0, 2) ?></h3>
            </div>
            <?php endif; ?>

            <!-- Pendiente Alfredo - SE OCULTA SI ES OTRO DISTRIBUIDOR -->
            <?php if(!$f_dist || $f_dist == 'alfredo'): ?>
            <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col shadow-sm hover:shadow-xl transition-all duration-300 border-l-4 border-l-amber-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 flex items-center justify-center bg-amber-50 rounded-xl text-amber-600">
                        <i class="fas fa-user-clock text-xl"></i>
                    </div>
                    <span class="text-[10px] font-black text-amber-400 uppercase tracking-widest">Alfredo</span>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter mb-1">Pendiente de Cobro</p>
                <h3 class="text-2xl font-black text-amber-600">Bs. <?= number_format($pendiente_alfredo ?? 0, 2) ?></h3>
            </div>
            <?php endif; ?>

            <!-- Stock Crítico -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col shadow-sm hover:shadow-xl transition-all duration-300 border-l-4 border-l-red-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 flex items-center justify-center bg-red-50 rounded-xl text-red-600">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                    <span class="text-[10px] font-black text-red-400 uppercase tracking-widest">Alertas</span>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter mb-1">Stock Cr&iacute;tico</p>
                <h3 class="text-2xl font-black text-slate-900"><?= $productos_bajo_stock ?? 0 ?> <span class="text-xs text-slate-400 font-bold">Unid.</span></h3>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
            
            <div class="xl:col-span-3 space-y-8">
                
                <!-- TABLA DE COBRANZAS ALFREDO (Solo si es Alfredo) -->
                <?php if($is_alfredo): ?>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden border-l-4 border-l-amber-500">
                    <div class="px-8 py-6 border-b border-slate-100 bg-white">
                        <h3 class="font-black text-slate-800 flex items-center gap-3 text-lg text-uppercase">
                            <i class="fas fa-hand-holding-usd text-amber-500"></i>
                            Pendientes Alfredo (Cobranzas)
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="tabla-alfredo-bolivia" class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-widest border-b border-slate-100">
                                    <th class="px-4 py-4 font-bold">Fecha</th>
                                    <th class="px-4 py-4 font-bold">Cliente / Detalle</th>
                                    <th class="px-4 py-4 font-bold text-center">Estado</th>
                                    <th class="px-4 py-4 font-bold text-right">Monto</th>
                                    <th class="px-4 py-4 font-bold text-right">Pagado</th>
                                    <th class="px-4 py-4 font-bold text-right">Saldo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if(!empty($pedidos_alfredo)): foreach($pedidos_alfredo as $pa): ?>
                                    <tr class="hover:bg-amber-50/30 transition-all duration-200">
                                        <td class="px-4 py-4 text-[10px] font-bold text-slate-400"><?= date('d/m/Y', strtotime($pa->fecha_registro)) ?></td>
                                        <td class="px-4 py-4">
                                            <div class="font-black text-slate-700 uppercase leading-tight"><?= $pa->cliente ?></div>
                                            <div class="text-[10px] text-slate-400 italic">Venta #<?= $pa->id_venta ?></div>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-tighter bg-blue-100 text-blue-700">
                                                <?= $pa->estado ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-right font-bold text-slate-600">Bs. <?= number_format($pa->monto_alfredo, 2) ?></td>
                                        <td class="px-4 py-4 text-right font-bold text-emerald-600">Bs. <?= number_format($pa->pagado, 2) ?></td>
                                        <td class="px-4 py-4 text-right font-black text-red-600">Bs. <?= number_format($pa->saldo, 2) ?></td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <!-- TABLA DE RANKING DE VENTAS POR MODELO (NUEVA SECCIÓN) -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 bg-white">
                        <h3 class="font-black text-slate-800 flex items-center gap-3 text-lg text-uppercase">
                            <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                            &#128202; Ranking de Ventas por Modelo
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="tabla-ranking-bolivia" class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-widest border-b border-slate-100">
                                    <th class="px-4 py-4 font-bold">Producto (Modelo)</th>
                                    <th class="px-4 py-4 font-bold text-right">Total Vendido (Unid.)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if(!empty($ventas_por_modelo)): foreach($ventas_por_modelo as $vm): ?>
                                    <tr class="hover:bg-slate-50 transition-all duration-200">
                                        <td class="px-4 py-4 font-bold text-slate-700 uppercase"><?= $vm->nombre ?></td>
                                        <td class="px-4 py-4 text-right">
                                            <span class="bg-emerald-50 text-emerald-700 px-3 py-1 rounded-lg text-xs font-black">
                                                <?= number_format($vm->total_vendido, 0) ?> unidades
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- DESGLOSE POR DISTRIBUIDOR (Solo si no hay filtro de distribuidor) -->
                <?php if(!$f_dist && !empty($desglose_distribuidores)): ?>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8 border-l-4 border-l-emerald-500">
                    <div class="px-8 py-6 border-b border-slate-100 bg-white">
                        <h3 class="font-black text-slate-800 flex items-center gap-3 text-lg text-uppercase">
                            <i class="fas fa-users text-emerald-500"></i>
                            Saldos Pendientes por Distribuidor
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-widest border-b border-slate-100">
                                    <th class="px-6 py-4 font-bold">Distribuidor</th>
                                    <th class="px-6 py-4 font-bold text-right">Saldo Pendiente</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php foreach($desglose_distribuidores as $dd): ?>
                                <tr class="hover:bg-emerald-50/20 transition-all">
                                    <td class="px-6 py-4 font-black text-slate-700 uppercase"><?= $dd->nombre ?></td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-sm font-black text-emerald-600">Bs. <?= number_format($dd->saldo, 2) ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <!-- TABLA DE STOCK (Se oculta si es Alfredo) -->
                <?php if(!$is_alfredo): ?>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 bg-white">
                        <h3 class="font-black text-slate-800 flex items-center gap-3 text-lg text-uppercase">
                            <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                            Cat&aacute;logo y Stock por Distribuidor
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="tabla-stock-bolivia" class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-widest border-b border-slate-100">
                                    <th class="px-4 py-4 font-bold">Producto</th>
                                    <th class="px-4 py-4 font-bold text-center">Talla</th>
                                    <th class="px-4 py-4 font-bold text-center">Color</th>
                                    <th class="px-4 py-4 font-bold text-right">Stock Actual</th>
                                    <th class="px-4 py-4 font-bold text-right">Precio</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php 
                                $color_map = [
                                    'NEGRO' => '#000000', 'BLANCO' => '#FFFFFF', 'ROJO' => '#FF0000',
                                    'AZUL' => '#0000FF', 'VERDE' => '#228B22', 'MARRON' => '#8B4513',
                                    'MARRÓN' => '#8B4513', 'ROSA' => '#FFC0CB', 'CAMEL' => '#C19A6B',
                                    'BEIGE' => '#F5F5DC', 'GRIS' => '#808080', 'AMARILLO' => '#FFFF00',
                                    'NARANJA' => '#FFA500', 'CELESTE' => '#87CEEB', 'LILA' => '#C8A2C8',
                                    'FUCSIA' => '#FF00FF', 'MOSTAZA' => '#E1AD01', 'GUINDA' => '#800000'
                                ];

                                if(!empty($productos_distribuidor)): foreach($productos_distribuidor as $pd): 
                                    $color_key = strtoupper(trim($pd->color));
                                    $bg_color = isset($color_map[$color_key]) ? $color_map[$color_key] : '#CBD5E1';
                                ?>
                                <tr class="hover:bg-slate-50 transition-all duration-200">
                                    <td class="px-4 py-4 font-bold text-slate-700 uppercase"><?= $pd->nombre ?></td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-[10px] font-black"><?= $pd->talla ?></span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <span class="inline-block w-4 h-4 rounded-full border border-slate-200 shadow-sm" style="background-color: <?= $bg_color ?>;"></span>
                                            <span class="text-xs font-bold text-slate-500 uppercase"><?= $pd->color ?></span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <span class="text-sm font-black <?= ($pd->stock < 5) ? 'text-red-500' : 'text-slate-900' ?>"><?= $pd->stock ?></span>
                                    </td>
                                    <td class="px-4 py-4 text-right font-bold text-slate-400 text-xs">Bs. <?= number_format($pd->precio_venta, 2) ?></td>
                                </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <!-- SECCIÓN MOVIMIENTOS OCULTA POR SOLICITUD -->
                <?php if(false): ?>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 bg-white flex justify-between items-center">
                        <h3 class="font-black text-slate-800 flex items-center gap-3 text-lg text-uppercase">
                            <span class="w-2 h-6 bg-amber-500 rounded-full"></span>
                            Últimos Movimientos - Bolivia
                        </h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table id="tabla-movimientos-bolivia" class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-widest border-b border-slate-100">
                                    <th class="px-4 py-4 font-bold">Fecha</th>
                                    <th class="px-4 py-4 font-bold">Producto</th>
                                    <th class="px-4 py-4 font-bold text-center">Tipo</th>
                                    <th class="px-4 py-4 font-bold text-right">Cant.</th>
                                    <th class="px-4 py-4 font-bold text-right">Stock Final</th>
                                </tr>
                            </thead>
                                <?php if(!empty($ultimos_movimientos)): foreach($ultimos_movimientos as $m): ?>
                                <tr class="hover:bg-slate-50 transition-all duration-200 group">
                                    <td class="px-4 py-5 text-[11px] text-slate-400 font-bold">
                                        <?= date('d/m/Y H:i', strtotime($m->fecha_registro)) ?>
                                    </td>
                                    <td class="px-4 py-5">
                                        <div class="font-bold text-slate-700 text-sm group-hover:text-amber-600 transition-colors uppercase"><?= $m->producto_nombre ?></div>
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-tighter <?= ($m->tipo_movimiento == 'Entrada') ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' ?>">
                                            <?= $m->tipo_movimiento ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-5 text-right font-mono text-slate-500 font-bold text-xs"><?= number_format($m->cantidad, 0) ?></td>
                                    <td class="px-4 py-5 text-right font-black text-slate-900"><?= number_format($m->stock_actual, 0) ?></td>
                                </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- SIDEBAR ACTIONS -->
            <div class="xl:col-span-1 space-y-6">
                <div class="bg-slate-900 rounded-3xl p-8 shadow-2xl shadow-slate-200">
                    <h3 class="text-white font-black text-xs uppercase tracking-[0.2em] mb-8 opacity-50">Acceso Rápido</h3>
                    <div class="space-y-4">
                        <a href="<?= base_url('ventas_bolivia/nueva_cotizacion') ?>" class="flex items-center group bg-white/5 hover:bg-white text-white hover:text-slate-900 p-4 rounded-2xl transition-all duration-300 border border-white/5">
                            <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-amber-500 text-white mr-4 shadow-lg group-hover:rotate-12 transition-transform">
                                <i class="fas fa-plus"></i>
                            </div>
                            <span class="font-black text-xs uppercase tracking-widest">Nueva Venta</span>
                        </a>
                        <a href="<?= base_url('liquidaciones') ?>" class="flex items-center group bg-white/5 hover:bg-white text-white hover:text-slate-900 p-4 rounded-2xl transition-all duration-300 border border-white/5">
                            <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-500 text-white mr-4 shadow-lg group-hover:rotate-12 transition-transform">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                            <span class="font-black text-xs uppercase tracking-widest">Alfredo</span>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-4 text-blue-600">
                        <i class="fas fa-info-circle"></i>
                        <h4 class="font-black text-[10px] uppercase tracking-widest">Soporte</h4>
                    </div>
                    <p class="text-[11px] text-slate-500 font-medium leading-relaxed italic">
                        Filtre por **Distribuidor** para ver su stock actual y los depósitos que tiene pendientes por realizar esta semana.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Estilos personalizados para DataTables en el Dashboard */
    /* Compactar filas y columnas para evitar scroll horizontal */
    #tabla-stock-bolivia td, #tabla-movimientos-bolivia td, #tabla-alfredo-bolivia td, #tabla-ranking-bolivia td,
    #tabla-stock-bolivia th, #tabla-movimientos-bolivia th, #tabla-alfredo-bolivia th, #tabla-ranking-bolivia th { 
        padding-left: 1rem !important; 
        padding-right: 1rem !important; 
        padding-top: 0.6rem !important; 
        padding-bottom: 0.6rem !important;
        white-space: nowrap;
    }
    
    .dataTables_wrapper .dataTables_paginate { 
        width: 100%; 
        display: flex !important; 
        justify-content: center !important; 
        margin-top: 1.5rem !important; 
        margin-bottom: 1.5rem !important;
        float: none !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button { 
        border-radius: 10px !important; 
        margin: 0 3px !important; 
        border: 1px solid #f1f5f9 !important;
        background: #f8fafc !important;
        color: #64748b !important;
        font-size: 11px !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        padding: 0.5rem 1rem !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #0f172a !important;
        color: white !important;
        border-color: #0f172a !important;
    }

    table.dataTable { width: 100% !important; margin: 0 !important; border-collapse: collapse !important; }
</style>

<script>
    $(document).ready(function() {
        const configDT = {
            "paging": true,
            "lengthChange": false,
            "searching": false, // <--- OCULTAR BUSCADOR
            "ordering": true,
            "info": false,      // <--- OCULTAR "SHOWING X TO Y..."
            "autoWidth": false,
            "responsive": false,
            "pageLength": 8,
            "dom": 'rtp',       // <--- SOLO TABLA Y PAGINACIÓN
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        };

        if ($.fn.DataTable) {
            if ($('#tabla-alfredo-bolivia').length) $('#tabla-alfredo-bolivia').DataTable(configDT);
            if ($('#tabla-ranking-bolivia').length) $('#tabla-ranking-bolivia').DataTable(configDT);
            if ($('#tabla-stock-bolivia').length) $('#tabla-stock-bolivia').DataTable(configDT);
            if ($('#tabla-movimientos-bolivia').length) $('#tabla-movimientos-bolivia').DataTable(configDT);
        }
    });
</script>

<?php $this->load->view('layouts/footer'); ?>