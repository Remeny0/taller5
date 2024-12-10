-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-12-2024 a las 22:26:11
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `biblioteca`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_busqueda_avanzada` (IN `p_titulo` VARCHAR(255), IN `p_autor` VARCHAR(255), IN `p_genero` VARCHAR(255))   BEGIN
    SELECT * FROM Libros
    WHERE 
        (titulo LIKE CONCAT('%', p_titulo, '%') OR p_titulo IS NULL)
        AND (autor LIKE CONCAT('%', p_autor, '%') OR p_autor IS NULL)
        AND (genero LIKE CONCAT('%', p_genero, '%') OR p_genero IS NULL);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_control_suscripciones` (IN `p_usuario_id` INT, IN `p_tipo_suscripcion` VARCHAR(255))   BEGIN
    UPDATE Usuarios
    SET tipo_suscripcion = p_tipo_suscripcion
    WHERE usuario_id = p_usuario_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_gestion_prestamos` (IN `p_usuario_id` INT, IN `p_libro_id` INT)   BEGIN
    DECLARE num_accesos INT;
    
    -- Verificar si el libro ya está prestado (con estado 'activo')
    SELECT COUNT(*) INTO num_accesos
    FROM Prestamos
    WHERE libro_id = p_libro_id AND estado = 'activo';
    
    -- Si el libro ya está prestado, lanzar un error
    IF num_accesos >= 1 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El libro ya está prestado.';
    ELSE
        -- Si el libro no está prestado, proceder con la inserción
        INSERT INTO Prestamos (usuario_id, libro_id, fecha_prestamo, estado)
        VALUES (p_usuario_id, p_libro_id, NOW(), 'activo');
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_gestion_recomendaciones` (IN `p_usuario_id` INT, IN `p_libro_id` INT)   BEGIN
    INSERT INTO Recomendaciones (usuario_id, libro_id, fecha_recomendacion)
    VALUES (p_usuario_id, p_libro_id, NOW());
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_lectura`
--

CREATE TABLE `historial_lectura` (
  `historial_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `libro_id` int(11) DEFAULT NULL,
  `fecha_lectura` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_lectura`
--

INSERT INTO `historial_lectura` (`historial_id`, `usuario_id`, `libro_id`, `fecha_lectura`) VALUES
(1, 1, 1, '2024-12-09 23:42:04'),
(2, 3, 3, '2024-12-10 00:09:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `libro_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `autor` varchar(255) NOT NULL,
  `genero` varchar(100) NOT NULL,
  `fecha_publicacion` year(4) NOT NULL,
  `popularidad` int(11) DEFAULT 0,
  `disponible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`libro_id`, `titulo`, `autor`, `genero`, `fecha_publicacion`, `popularidad`, `disponible`) VALUES
(1, 'El Viaje de los Sueños', 'uan Pérez', 'Fantasía', '1950', 1, 50),
(3, 'Cien años de soledad', 'Gabriel García Márquez', 'nove{a', '1950', 1, 60);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `prestamo_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `libro_id` int(11) DEFAULT NULL,
  `fecha_prestamo` datetime DEFAULT current_timestamp(),
  `fecha_devolucion` datetime DEFAULT NULL,
  `estado` varchar(50) NOT NULL CHECK (`estado` in ('activo','vencido','devuelto'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`prestamo_id`, `usuario_id`, `libro_id`, `fecha_prestamo`, `fecha_devolucion`, `estado`) VALUES
(1, 1, 1, '2024-12-01 00:00:00', '2024-12-02 00:00:00', 'Devuelto'),
(2, 3, 3, '2024-12-08 00:00:00', '2024-12-15 00:00:00', 'Activo');

--
-- Disparadores `prestamos`
--
DELIMITER $$
CREATE TRIGGER `trg_actualizar_popularidad` AFTER INSERT ON `prestamos` FOR EACH ROW BEGIN
    UPDATE Libros
    SET popularidad = popularidad + 1
    WHERE libro_id = NEW.libro_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_control_accesos` BEFORE INSERT ON `prestamos` FOR EACH ROW BEGIN
    DECLARE num_accesos INT;
    
    -- Contar los préstamos activos para el libro
    SELECT COUNT(*) INTO num_accesos
    FROM Prestamos
    WHERE libro_id = NEW.libro_id AND estado = 'activo';
    
    -- Si el libro ya está prestado, lanzar un error
    IF num_accesos >= 1 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'El libro ya está prestado.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_registrar_historial` AFTER INSERT ON `prestamos` FOR EACH ROW BEGIN
    INSERT INTO Historial_Lectura (usuario_id, libro_id, fecha_lectura)
    VALUES (NEW.usuario_id, NEW.libro_id, NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recomendaciones`
--

CREATE TABLE `recomendaciones` (
  `recomendacion_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `libro_id` int(11) DEFAULT NULL,
  `fecha_recomendacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recomendaciones`
--

INSERT INTO `recomendaciones` (`recomendacion_id`, `usuario_id`, `libro_id`, `fecha_recomendacion`) VALUES
(150, 1, 1, '2024-12-10 17:20:46'),
(151, 3, 3, '2024-12-10 17:20:46'),
(153, 3, 3, '2024-12-10 17:21:46'),
(154, 1, 1, '2024-12-10 17:21:46'),
(156, 3, 3, '2024-12-10 17:22:46'),
(157, 1, 1, '2024-12-10 17:22:46'),
(159, 3, 3, '2024-12-10 17:23:46'),
(160, 1, 1, '2024-12-10 17:23:46'),
(162, 3, 3, '2024-12-10 17:24:46'),
(163, 1, 1, '2024-12-10 17:24:46'),
(165, 3, 3, '2024-12-10 17:25:46'),
(166, 1, 1, '2024-12-10 17:25:46'),
(168, 3, 3, '2024-12-10 17:26:46'),
(169, 1, 1, '2024-12-10 17:26:46'),
(171, 3, 3, '2024-12-10 17:27:46'),
(172, 1, 1, '2024-12-10 17:27:46'),
(174, 3, 3, '2024-12-10 17:28:46'),
(175, 1, 1, '2024-12-10 17:28:46'),
(177, 3, 3, '2024-12-10 17:29:46'),
(178, 1, 1, '2024-12-10 17:29:46'),
(180, 3, 3, '2024-12-10 17:30:46'),
(181, 1, 1, '2024-12-10 17:30:46'),
(183, 3, 3, '2024-12-10 17:31:46'),
(184, 1, 1, '2024-12-10 17:31:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `tipo_suscripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `nombre`, `email`, `fecha_registro`, `tipo_suscripcion`) VALUES
(1, 'santiago', 'santiaggo2005@gmail.com', '2024-12-09 00:00:00', 'Basico'),
(3, 'andres', 'andrae@gmail.com', '2024-12-10 00:00:00', 'Premium');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `historial_lectura`
--
ALTER TABLE `historial_lectura`
  ADD PRIMARY KEY (`historial_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `libro_id` (`libro_id`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`libro_id`),
  ADD KEY `titulo` (`titulo`,`autor`,`genero`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`prestamo_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `libro_id` (`libro_id`);

--
-- Indices de la tabla `recomendaciones`
--
ALTER TABLE `recomendaciones`
  ADD PRIMARY KEY (`recomendacion_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `libro_id` (`libro_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `historial_lectura`
--
ALTER TABLE `historial_lectura`
  MODIFY `historial_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `libro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `prestamo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `recomendaciones`
--
ALTER TABLE `recomendaciones`
  MODIFY `recomendacion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `historial_lectura`
--
ALTER TABLE `historial_lectura`
  ADD CONSTRAINT `historial_lectura_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historial_lectura_ibfk_2` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`libro_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`libro_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `recomendaciones`
--
ALTER TABLE `recomendaciones`
  ADD CONSTRAINT `recomendaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recomendaciones_ibfk_2` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`libro_id`) ON DELETE CASCADE;

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `ev_limpieza_prestamos` ON SCHEDULE EVERY 1 MINUTE STARTS '2024-12-09 14:46:51' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    DELETE FROM prestamos
    WHERE estado = 'vencido' AND fecha_devolucion < NOW();
END$$

CREATE DEFINER=`root`@`localhost` EVENT `ev_generacion_recomendaciones` ON SCHEDULE EVERY 1 MINUTE STARTS '2024-12-09 14:49:46' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    -- Lógica para generar recomendaciones basadas en historial de lectura
    INSERT INTO Recomendaciones (usuario_id, libro_id, fecha_recomendacion)
    SELECT usuario_id, libro_id, NOW()
    FROM Historial_Lectura
    GROUP BY usuario_id, libro_id
    ORDER BY COUNT(*) DESC
    LIMIT 5; -- Ejemplo de las 5 recomendaciones más populares
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
