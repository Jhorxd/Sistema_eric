-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2026 at 03:21 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistema_eric`
--

-- --------------------------------------------------------

--
-- Table structure for table `clientes_peru`
--

CREATE TABLE `clientes_peru` (
  `id` int(11) NOT NULL,
  `dni` varchar(15) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `distribuidores_bolivia`
--

CREATE TABLE `distribuidores_bolivia` (
  `id` int(11) NOT NULL,
  `nit` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Equivalente al DNI/RUC en Perú',
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `celular` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destino` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dirección o ciudad de destino',
  `correo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pagos_alfredo`
--

CREATE TABLE `pagos_alfredo` (
  `id` int(11) NOT NULL,
  `id_pedido_alfredo` int(11) DEFAULT NULL,
  `monto_pagado` decimal(10,2) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `fecha_pago` datetime DEFAULT NULL,
  `observacion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pagos_distribuidores`
--

CREATE TABLE `pagos_distribuidores` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `monto_pagado` decimal(10,2) NOT NULL,
  `fecha_pago` datetime DEFAULT CURRENT_TIMESTAMP,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `observacion` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pedidos_alfredo`
--

CREATE TABLE `pedidos_alfredo` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `cliente` varchar(150) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `destino` varchar(200) DEFAULT NULL,
  `total_pedido` decimal(10,2) DEFAULT NULL,
  `monto_alfredo` decimal(10,2) DEFAULT NULL,
  `fecha_pedido` datetime DEFAULT NULL,
  `estado` enum('Pendiente','Pagado') DEFAULT 'Pendiente',
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `productos_bolivia`
--

CREATE TABLE `productos_bolivia` (
  `id` int(11) NOT NULL,
  `id_distribuidor` int(11) DEFAULT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `detalles` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `talla` varchar(10) DEFAULT NULL,
  `stock` decimal(10,2) DEFAULT '0.00',
  `precio_venta` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `productos_peru`
--

CREATE TABLE `productos_peru` (
  `id` int(11) NOT NULL,
  `producto_final_id` int(11) DEFAULT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `tipo` enum('material','corte','final') NOT NULL,
  `detalles` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `talla` varchar(10) DEFAULT NULL,
  `tipo_tela` varchar(50) DEFAULT NULL,
  `stock` decimal(10,2) DEFAULT '0.00',
  `precio_venta` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `producto_movimientos_bolivia`
--

CREATE TABLE `producto_movimientos_bolivia` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_distribuidor` int(11) DEFAULT NULL,
  `tipo_movimiento` enum('Entrada','Salida') NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `stock_anterior` decimal(10,2) NOT NULL,
  `stock_actual` decimal(10,2) NOT NULL,
  `origen` enum('Ventas','Produccion','Ajuste Manual','Anulacion') NOT NULL,
  `referencia_id` int(11) DEFAULT NULL,
  `motivo` text,
  `usuario` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `producto_movimientos_peru`
--

CREATE TABLE `producto_movimientos_peru` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `tipo_movimiento` enum('Entrada','Salida') NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `stock_anterior` decimal(10,2) NOT NULL,
  `stock_actual` decimal(10,2) NOT NULL,
  `origen` enum('Ventas','Produccion','Ajuste Manual','Anulacion') NOT NULL,
  `referencia_id` int(11) DEFAULT NULL,
  `motivo` text,
  `usuario` varchar(100) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `pais` enum('peru','bolivia') NOT NULL,
  `estado` tinyint(4) DEFAULT '1',
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ventas_bolivia`
--

CREATE TABLE `ventas_bolivia` (
  `id` int(11) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `nit` varchar(15) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `celular_cliente` varchar(20) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `destino` varchar(255) DEFAULT NULL,
  `monto_productos` decimal(10,2) DEFAULT '0.00',
  `comision_delivery` decimal(10,2) DEFAULT '0.00',
  `total_venta` decimal(10,2) DEFAULT '0.00',
  `total_pagado` decimal(10,2) DEFAULT '0.00',
  `estado_pago` enum('Pendiente','Parcial','Completado') DEFAULT 'Pendiente',
  `estado_envio` enum('Cotizacion','Aprobado','Enviado','Entregado') DEFAULT 'Cotizacion',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ventas_peru`
--

CREATE TABLE `ventas_peru` (
  `id` int(11) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `dni` varchar(15) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `total_venta` decimal(10,2) DEFAULT '0.00',
  `total_pagado` decimal(10,2) DEFAULT '0.00',
  `estado_pago` enum('Pendiente','Parcial','Completado') DEFAULT 'Pendiente',
  `estado_envio` enum('Cotizacion','Aprobado','Enviado','Entregado') DEFAULT 'Cotizacion',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `venta_detalles_bolivia`
--

CREATE TABLE `venta_detalles_bolivia` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `venta_detalles_peru`
--

CREATE TABLE `venta_detalles_peru` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `venta_pagos_bolivia`
--

CREATE TABLE `venta_pagos_bolivia` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `fecha_pago` datetime DEFAULT CURRENT_TIMESTAMP,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `nota` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `venta_pagos_peru`
--

CREATE TABLE `venta_pagos_peru` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `fecha_pago` datetime DEFAULT CURRENT_TIMESTAMP,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `nota` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clientes_peru`
--
ALTER TABLE `clientes_peru`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indexes for table `distribuidores_bolivia`
--
ALTER TABLE `distribuidores_bolivia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pagos_alfredo`
--
ALTER TABLE `pagos_alfredo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pagos_distribuidores`
--
ALTER TABLE `pagos_distribuidores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_venta` (`id_venta`);

--
-- Indexes for table `pedidos_alfredo`
--
ALTER TABLE `pedidos_alfredo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productos_bolivia`
--
ALTER TABLE `productos_bolivia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productos_peru`
--
ALTER TABLE `productos_peru`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `producto_movimientos_bolivia`
--
ALTER TABLE `producto_movimientos_bolivia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `producto_movimientos_peru`
--
ALTER TABLE `producto_movimientos_peru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_producto_peru` (`id_producto`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ventas_bolivia`
--
ALTER TABLE `ventas_bolivia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ventas_peru`
--
ALTER TABLE `ventas_peru`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `venta_detalles_bolivia`
--
ALTER TABLE `venta_detalles_bolivia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detalles_bolivia` (`id_venta`);

--
-- Indexes for table `venta_detalles_peru`
--
ALTER TABLE `venta_detalles_peru`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `venta_pagos_bolivia`
--
ALTER TABLE `venta_pagos_bolivia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pagos_bolivia` (`id_venta`);

--
-- Indexes for table `venta_pagos_peru`
--
ALTER TABLE `venta_pagos_peru`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clientes_peru`
--
ALTER TABLE `clientes_peru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `distribuidores_bolivia`
--
ALTER TABLE `distribuidores_bolivia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pagos_alfredo`
--
ALTER TABLE `pagos_alfredo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pagos_distribuidores`
--
ALTER TABLE `pagos_distribuidores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pedidos_alfredo`
--
ALTER TABLE `pedidos_alfredo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productos_bolivia`
--
ALTER TABLE `productos_bolivia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productos_peru`
--
ALTER TABLE `productos_peru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `producto_movimientos_bolivia`
--
ALTER TABLE `producto_movimientos_bolivia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `producto_movimientos_peru`
--
ALTER TABLE `producto_movimientos_peru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ventas_bolivia`
--
ALTER TABLE `ventas_bolivia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ventas_peru`
--
ALTER TABLE `ventas_peru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `venta_detalles_bolivia`
--
ALTER TABLE `venta_detalles_bolivia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `venta_detalles_peru`
--
ALTER TABLE `venta_detalles_peru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `venta_pagos_bolivia`
--
ALTER TABLE `venta_pagos_bolivia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `venta_pagos_peru`
--
ALTER TABLE `venta_pagos_peru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pagos_distribuidores`
--
ALTER TABLE `pagos_distribuidores`
  ADD CONSTRAINT `pagos_distribuidores_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas_bolivia` (`id`);

--
-- Constraints for table `producto_movimientos_peru`
--
ALTER TABLE `producto_movimientos_peru`
  ADD CONSTRAINT `fk_mov_prod_peru` FOREIGN KEY (`id_producto`) REFERENCES `productos_peru` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `venta_detalles_bolivia`
--
ALTER TABLE `venta_detalles_bolivia`
  ADD CONSTRAINT `fk_detalles_bolivia` FOREIGN KEY (`id_venta`) REFERENCES `ventas_bolivia` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `venta_pagos_bolivia`
--
ALTER TABLE `venta_pagos_bolivia`
  ADD CONSTRAINT `fk_pagos_bolivia` FOREIGN KEY (`id_venta`) REFERENCES `ventas_bolivia` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
