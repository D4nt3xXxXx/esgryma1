-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: mysql.esgryma.dreamhosters.com
-- Tiempo de generación: 02-02-2020 a las 09:59:41
-- Versión del servidor: 5.7.25-log
-- Versión de PHP: 7.1.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `esgryma_dev`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad`
--

CREATE TABLE `actividad` (
  `idActividad` int(11) NOT NULL,
  `actividad` varchar(255) NOT NULL,
  `undMedida` varchar(10) NOT NULL,
  `codigoActividad` varchar(50) NOT NULL,
  `requiereInforme` int(11) NOT NULL,
  `tiempoinforme` int(11) NOT NULL,
  `idCategoria` int(11) NOT NULL,
  `idTema` int(11) NOT NULL,
  `idTipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asisAdminxCliente`
--

CREATE TABLE `asisAdminxCliente` (
  `idAsistenteAdmin` int(10) UNSIGNED NOT NULL,
  `idCliente` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `idCategoria` int(11) NOT NULL,
  `categoriaActividad` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idCliente` int(11) NOT NULL,
  `nit` varchar(15) NOT NULL,
  `nombreCliente` varchar(150) NOT NULL,
  `tipoCliente` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idCliente`, `nit`, `nombreCliente`, `tipoCliente`) VALUES
(1, '890903790-5', 'SURA ARL', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `idEstado` int(11) NOT NULL,
  `codEstado` int(11) NOT NULL,
  `nomEstado` varchar(50) NOT NULL,
  `entidad` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`idEstado`, `codEstado`, `nomEstado`, `entidad`) VALUES
(1, 10, 'PRE-RESERVA', 'RESERVA'),
(2, 11, 'PLANEADA-ASIGNADA', 'RESERVA'),
(3, 12, 'EN GESTION', 'RESERVA'),
(4, 20, 'GESTIONADA SIN PROG', 'OT'),
(5, 21, 'PROGRAMADA', 'OT'),
(6, 22, 'REALIZADA - PEND INF', 'OT'),
(7, 23, 'REALIZADA - PEND REV', 'OT'),
(8, 24, 'EJECUTADA', 'OT');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metames`
--

CREATE TABLE `metames` (
  `idMeta` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `meta` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2015_01_20_084450_create_roles_table', 1),
(4, '2015_01_20_084525_create_role_user_table', 1),
(5, '2015_01_24_080208_create_permissions_table', 1),
(6, '2015_01_24_080433_create_permission_role_table', 1),
(7, '2015_12_04_003040_add_special_role_column', 1),
(8, '2017_10_17_170735_create_permission_user_table', 1),
(9, '2019_08_19_000000_create_failed_jobs_table', 1),
(10, '2020_01_06_180714_create_sessions_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mtrcualificacion`
--

CREATE TABLE `mtrcualificacion` (
  `idGestor` int(10) UNSIGNED NOT NULL,
  `idActividad` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `novedad`
--

CREATE TABLE `novedad` (
  `idNovedad` int(11) NOT NULL,
  `Novedad` varchar(50) NOT NULL,
  `fechaInicio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fechaFin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `idGestor` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ot`
--

CREATE TABLE `ot` (
  `idOT` int(11) NOT NULL,
  `idGestor` int(10) UNSIGNED NOT NULL,
  `idReserva` int(11) NOT NULL,
  `idActividad` int(11) NOT NULL,
  `fechaActividad` timestamp NULL DEFAULT NULL,
  `asesorARL` varchar(50) DEFAULT NULL,
  `tipoActividad` varchar(20) NOT NULL,
  `tiempoEjecutar` int(11) NOT NULL,
  `fechaInforme` timestamp NOT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `coordDireccion` varchar(255) DEFAULT NULL,
  `nombreContacto` varchar(150) DEFAULT NULL,
  `estadoOT` int(11) NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `facturada` int(11) DEFAULT NULL,
  `urlInforme` varchar(255) DEFAULT NULL,
  `idEstadoOT` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission_role`
--

CREATE TABLE `permission_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission_user`
--

CREATE TABLE `permission_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `idReserva` int(11) NOT NULL,
  `nroOrden` varchar(30) NOT NULL,
  `nroOrdenPadre` varchar(30) DEFAULT NULL,
  `temaOrden` varchar(255) NOT NULL,
  `empresaOrden` varchar(255) NOT NULL,
  `valorOrdenUnit` double NOT NULL,
  `valorOrdenTotal` double NOT NULL,
  `obsOrden` varchar(255) DEFAULT NULL,
  `fecIniOrden` timestamp NOT NULL,
  `fecFinOrden` timestamp NOT NULL,
  `fecSubida` timestamp NOT NULL,
  `unidadesOrden` int(10) DEFAULT NULL,
  `undMedidaOrden` varchar(15) DEFAULT NULL,
  `idUserSube` int(10) UNSIGNED NOT NULL,
  `idTipo` int(11) NOT NULL,
  `idUserAsigna` int(10) UNSIGNED NOT NULL,
  `idUserAsignadoa` int(10) UNSIGNED NOT NULL,
  `idCliente` int(11) NOT NULL,
  `estadoReserva` int(11) NOT NULL,
  `gestorSolicitado` varchar(50) DEFAULT NULL,
  `idEstadoReserva` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `special` enum('all-access','no-access') COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`, `special`) VALUES
(1, 'Lider de Programacion', 'liderprogramacion', NULL, '2020-01-11 08:00:00', NULL, NULL),
(2, 'Asistente Administrativo', 'asisadministrativo', NULL, '2020-01-11 08:00:00', NULL, NULL),
(3, 'Gestor', 'gestor', NULL, '2020-01-11 08:00:00', NULL, NULL),
(4, 'Coordinador de Servicio', 'coordservicio', NULL, '2020-01-11 08:00:00', NULL, NULL),
(5, 'Coordinador Preventivo', 'coordpreventivo', NULL, '2020-01-11 08:00:00', NULL, NULL),
(6, 'Gerente', 'gerente', NULL, '2020-01-11 08:00:00', NULL, NULL),
(7, 'Administrador', 'admin', 'Administrador de toda la aplicacion', '2020-01-11 08:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_user`
--

CREATE TABLE `role_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `role_user`
--

INSERT INTO `role_user` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 6, '2020-02-02 08:00:00', NULL),
(2, 2, 7, '2020-02-02 08:00:00', NULL),
(3, 8, 3, '2020-02-02 08:00:00', NULL),
(4, 7, 1, '2020-02-02 08:00:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salon`
--

CREATE TABLE `salon` (
  `idSalon` int(10) NOT NULL,
  `nombreSalon` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('A024L7XEaMRdHCwfqpJxru6vc03lpQNbcexZUjEd', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid0NYcHZuYnplbWpYTnAxVzd3aXJ1T0FRWWlvV0J2RFFmT0ZtR0paSyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1580665440);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarifa`
--

CREATE TABLE `tarifa` (
  `idTarifa` int(11) NOT NULL,
  `undMed` varchar(10) NOT NULL,
  `valor` double NOT NULL,
  `idCliente` int(11) DEFAULT NULL,
  `idActividad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tema`
--

CREATE TABLE `tema` (
  `idTema` int(11) NOT NULL,
  `temaActividad` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo`
--

CREATE TABLE `tipo` (
  `idTipo` int(11) NOT NULL,
  `tipoActividad` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_reserva`
--

CREATE TABLE `tipo_reserva` (
  `idTipo` int(11) NOT NULL,
  `tipo_Reserva` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_reserva`
--

INSERT INTO `tipo_reserva` (`idTipo`, `tipo_Reserva`) VALUES
(1, 'ARL'),
(2, 'COMERCIAL'),
(3, 'ESGRYMA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Breyner José Perez Bolaño', 'analistadesarrollo@corplas.com', NULL, '$2y$10$1q.2.ilk1KswickG3zh9R.wJHgvI5PSlWgbEUV9tDc5gM44u5uIvG', NULL, '2020-01-03 19:49:10', '2020-01-03 19:49:10'),
(4, 'Breyner José Perez', 'breyner.jpb@gmail.com', NULL, '$2y$10$1q.2.ilk1KswickG3zh9R.wJHgvI5PSlWgbEUV9tDc5gM44u5uIvG', NULL, '2020-01-03 19:49:10', '2020-01-03 19:49:10'),
(6, 'Lider programacion', 'liderprog@esgryma.com', NULL, '$2y$10$6p1h.mz0xXNY2jNwK/M0DOhOnh4yZAKadyOG8ypQrSlvfDYEh6Wx2', NULL, '2020-02-03 01:42:14', '2020-02-03 01:42:14'),
(7, 'Asistente Administrativo', 'asisadministrativo@corplas.com', NULL, '$2y$10$GdT1rWorOiQFnpN80iL.E.FjFQHVpyGq.hPjuIKRLz8Zsjga2TOKO', NULL, '2020-02-03 01:43:13', '2020-02-03 01:43:13'),
(8, 'Gestor', 'gestor@esgryma.com', NULL, '$2y$10$bP..TZExaE3PuaIESmZFDeR.ymwpFp37ufiOIJiBEL.qTfLwCluw6', NULL, '2020-02-03 01:43:51', '2020-02-03 01:43:51');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividad`
--
ALTER TABLE `actividad`
  ADD PRIMARY KEY (`idActividad`),
  ADD KEY `idx_fk_actividad` (`idCategoria`),
  ADD KEY `idx_fk_actividad2` (`idTema`),
  ADD KEY `idx_fk_actividad3` (`idTipo`);

--
-- Indices de la tabla `asisAdminxCliente`
--
ALTER TABLE `asisAdminxCliente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fk_asisAdminxCliente` (`idAsistenteAdmin`),
  ADD KEY `idx_fk_asisAdminxCliente2` (`idCliente`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`idCategoria`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idCliente`),
  ADD UNIQUE KEY `nit` (`nit`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`idEstado`),
  ADD UNIQUE KEY `codEstado` (`codEstado`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `metames`
--
ALTER TABLE `metames`
  ADD PRIMARY KEY (`idMeta`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mtrcualificacion`
--
ALTER TABLE `mtrcualificacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fk_mtrCualificacion` (`idGestor`),
  ADD KEY `idx_fk_mtrCualificacion2` (`idActividad`);

--
-- Indices de la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indices de la tabla `novedad`
--
ALTER TABLE `novedad`
  ADD PRIMARY KEY (`idNovedad`),
  ADD KEY `idx_fk_novedad` (`idGestor`);

--
-- Indices de la tabla `ot`
--
ALTER TABLE `ot`
  ADD PRIMARY KEY (`idOT`),
  ADD KEY `idx_fk_OT` (`idGestor`),
  ADD KEY `idx_fk_OT2` (`idReserva`),
  ADD KEY `idx_fk_OT3` (`idActividad`),
  ADD KEY `idx_fk_OT4` (`idEstadoOT`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_slug_unique` (`slug`);

--
-- Indices de la tabla `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_role_permission_id_index` (`permission_id`),
  ADD KEY `permission_role_role_id_index` (`role_id`);

--
-- Indices de la tabla `permission_user`
--
ALTER TABLE `permission_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_user_permission_id_index` (`permission_id`),
  ADD KEY `permission_user_user_id_index` (`user_id`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`idReserva`),
  ADD KEY `fk_reserva` (`idUserSube`),
  ADD KEY `fk_reserva2` (`idTipo`),
  ADD KEY `fk_reserva3` (`idUserAsigna`),
  ADD KEY `fk_reserva4` (`idUserAsignadoa`),
  ADD KEY `fk_reserva5` (`idCliente`),
  ADD KEY `fk_reserva6` (`idEstadoReserva`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indices de la tabla `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_user_role_id_index` (`role_id`),
  ADD KEY `role_user_user_id_index` (`user_id`);

--
-- Indices de la tabla `salon`
--
ALTER TABLE `salon`
  ADD PRIMARY KEY (`idSalon`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD UNIQUE KEY `sessions_id_unique` (`id`);

--
-- Indices de la tabla `tarifa`
--
ALTER TABLE `tarifa`
  ADD PRIMARY KEY (`idTarifa`),
  ADD KEY `idx_fk_tarifa` (`idCliente`),
  ADD KEY `idx_fk_tarifa2` (`idActividad`);

--
-- Indices de la tabla `tema`
--
ALTER TABLE `tema`
  ADD PRIMARY KEY (`idTema`);

--
-- Indices de la tabla `tipo`
--
ALTER TABLE `tipo`
  ADD PRIMARY KEY (`idTipo`);

--
-- Indices de la tabla `tipo_reserva`
--
ALTER TABLE `tipo_reserva`
  ADD PRIMARY KEY (`idTipo`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividad`
--
ALTER TABLE `actividad`
  MODIFY `idActividad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asisAdminxCliente`
--
ALTER TABLE `asisAdminxCliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `idCategoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `idEstado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `metames`
--
ALTER TABLE `metames`
  MODIFY `idMeta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `mtrcualificacion`
--
ALTER TABLE `mtrcualificacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `novedad`
--
ALTER TABLE `novedad`
  MODIFY `idNovedad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ot`
--
ALTER TABLE `ot`
  MODIFY `idOT` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permission_role`
--
ALTER TABLE `permission_role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permission_user`
--
ALTER TABLE `permission_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `idReserva` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `salon`
--
ALTER TABLE `salon`
  MODIFY `idSalon` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tarifa`
--
ALTER TABLE `tarifa`
  MODIFY `idTarifa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tema`
--
ALTER TABLE `tema`
  MODIFY `idTema` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo`
--
ALTER TABLE `tipo`
  MODIFY `idTipo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_reserva`
--
ALTER TABLE `tipo_reserva`
  MODIFY `idTipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividad`
--
ALTER TABLE `actividad`
  ADD CONSTRAINT `fk_actividad` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`),
  ADD CONSTRAINT `fk_actividad2` FOREIGN KEY (`idTema`) REFERENCES `tema` (`idTema`),
  ADD CONSTRAINT `fk_actividad3` FOREIGN KEY (`idTipo`) REFERENCES `tipo` (`idTipo`);

--
-- Filtros para la tabla `asisAdminxCliente`
--
ALTER TABLE `asisAdminxCliente`
  ADD CONSTRAINT `fk_asisAdminxCliente` FOREIGN KEY (`idAsistenteAdmin`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asisAdminxCliente2` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `mtrcualificacion`
--
ALTER TABLE `mtrcualificacion`
  ADD CONSTRAINT `fk_mtrCualificacion` FOREIGN KEY (`idGestor`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_mtrCualificacion2` FOREIGN KEY (`idActividad`) REFERENCES `actividad` (`idActividad`);

--
-- Filtros para la tabla `novedad`
--
ALTER TABLE `novedad`
  ADD CONSTRAINT `fk_novedad` FOREIGN KEY (`idGestor`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `ot`
--
ALTER TABLE `ot`
  ADD CONSTRAINT `fk_OT` FOREIGN KEY (`idGestor`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_OT2` FOREIGN KEY (`idReserva`) REFERENCES `reserva` (`idReserva`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_OT3` FOREIGN KEY (`idActividad`) REFERENCES `actividad` (`idActividad`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_OT4` FOREIGN KEY (`idEstadoOT`) REFERENCES `estados` (`idEstado`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `fk_reserva` FOREIGN KEY (`idUserSube`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_reserva2` FOREIGN KEY (`idTipo`) REFERENCES `tipo_reserva` (`idTipo`),
  ADD CONSTRAINT `fk_reserva3` FOREIGN KEY (`idUserAsigna`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_reserva4` FOREIGN KEY (`idUserAsignadoa`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_reserva5` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`),
  ADD CONSTRAINT `fk_reserva6` FOREIGN KEY (`idEstadoReserva`) REFERENCES `estados` (`idEstado`);

--
-- Filtros para la tabla `tarifa`
--
ALTER TABLE `tarifa`
  ADD CONSTRAINT `fk_tarifa` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tarifa2` FOREIGN KEY (`idActividad`) REFERENCES `actividad` (`idActividad`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
