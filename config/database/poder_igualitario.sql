-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 18-05-2025 a las 03:54:47
-- Versión del servidor: 8.0.17
-- Versión de PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `poder_igualitario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `correo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contrasena` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`id_admin`, `nombre`, `correo`, `contrasena`) VALUES
(1, 'Voces_IgualitariasADM', 'vocesigualitarias@voces.mx', 'JuanoManzano');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`) VALUES
(2, 'Economía'),
(3, 'Educación'),
(6, 'Líderes'),
(1, 'Política'),
(4, 'Salud y Bienestar'),
(5, 'Tecnología');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id_comentario` int(11) NOT NULL,
  `id_noticia` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `comentario` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_comentario` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `created_at`) VALUES
(1, 'infokevarias@gmail.com', 'd1e46d2df313060fe61c0829b70bd0e0f9bdd79c55ac04c7a482fbccb855115f', '2025-05-18 01:38:31'),
(2, 'infokevarias@gmail.com', '9e784fcb2fd639e3721c8040324d7c2809a6ab35100103fc5f5532a09bccc2c3', '2025-05-18 01:50:59'),
(3, 'infokevarias@gmail.com', 'a54e3b679bbf97fcddb21612d52a5a47be87f224a24bd5b27daad5dd13a8b2d3', '2025-05-18 01:56:55'),
(4, 'infokevarias@gmail.com', 'ff7252bb4a3ff77600b393d5d9f3335ce5de00e97a54e257f04320f622265d12', '2025-05-18 01:56:56'),
(5, 'infokevarias@gmail.com', 'fbfdc45ef687d6bfc1bc1f09a510ca51025e9a58e15d0ea5ca3be579d47bf3ba', '2025-05-18 01:56:57'),
(6, 'infokevarias@gmail.com', '989bafadf63dafac23938fa6a1e790fbb15ad1f672e6f61d678f2bf17a421dc3', '2025-05-18 02:02:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones`
--

CREATE TABLE `publicaciones` (
  `id_noticia` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `titular` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion_corta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `imagen_principal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contenido` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `referencia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `archivada` tinyint(1) DEFAULT '0',
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `publicaciones`
--

INSERT INTO `publicaciones` (`id_noticia`, `fecha`, `titular`, `descripcion_corta`, `imagen_principal`, `contenido`, `referencia`, `archivada`, `id_categoria`) VALUES
(10, '2025-01-29', 'La igualdad de género aún está en la agenda global', 'Mientras en varias partes de la región persiste el debate sobre la continuidad de las políticas de género, y algunas empresas multinacionales optan por desmantelar sus programas de Diversidad, Equidad e Inclusión (DEI)', '../uploads/img_682949dee71ae.avif', 'Líderes de diferentes sectores del mundo se reunieron en Davos para participar del debate Levelling the Playing Field del World Economic Forum. En ese panel, participé junto a destacados referentes internacionales, poniendo en evidencia que, de seguir al ritmo actual, estamos a 134 años de alcanzar la equidad de género, un llamado urgente a la acción', 'https://youtu.be/cedT4OQnTOo?si=k4AnrYZyI3nTwX7G', 0, 6),
(11, '2025-05-08', 'Legisladores aprueban reforma para mejorar la inclusión financiera en Hidalgo', 'Destacaron la importancia de reconocer el papel crucial de las mujeres en la economía de todos los niveles', '../uploads/img_68294aa4cdf2b.webp', 'Durante la reciente sesión de la LXVI Legislatura del Estado de Hidalgo, diputadas y diputados aprobaron varios dictámenes y reformas clave para el desarrollo social, económico y de derechos humanos en la entidad. Con 22 votos a favor, cero en contra y cero abstenciones, el Pleno aprobó el dictamen que reforma la Ley para la Igualdad entre Mujeres y Hombres del Estado de Hidalgo, en materia de inclusión financiera. \r\n\r\nLa diputada Johana Montserrat Hernández Pérez (PRI) destacó la importancia de reconocer el papel crucial de las mujeres en la economía de todos los niveles y en la administración financiera de los hogares.\r\n\r\nPor su parte, el diputado Marco Antonio Mendoza Bustamante (PRI) subrayó que esta reforma busca enfrentar los desafíos estructurales que limitan la autonomía financiera de las mujeres y promover su participación en el sistema financiero. Según Mendoza, esta medida representa una oportunidad clave para cerrar las brechas de género y mejorar las condiciones de vida de las mujeres y sus familias.', 'https://www.milenio.com/politica/congreso-hidalgo-aprueba-reformas-inclusion-financiera', 0, 2),
(14, '2025-05-17', 'Con reforma constitucional buscan paridad de género en el Congreso, concejos y asambleas del país', '50 mujeres congresistas, lideradas por María José Pizarro, radicaron este jueves el proyecto de acto legislativo.', '../uploads/img_6829495764c3b.jpeg', 'La senadora del Pacto Histórico, María José Pizarro, lidera la iniciativa que modificaría el artículo 262 de la Constitución, para que, luego del evento electoral, los resultados determinen igual representación de hombres y mujeres para los cargos de elección popular. “Así como la sociedad colombiana está compuesta por el 51,2 por ciento de mujeres, también las corporaciones deberían tener esta representación y composición. Sabemos que tenemos que enfrentar múltiples retos para que este proyecto sea una realidad; sin embargo, si queremos avanzar en justicia y en derechos, el Congreso no debe desconocer la necesidad de garantizar la participación real de las mujeres en la política”, señaló la senadora.\r\n(Le sugerimos: Escudo de Colombia: la razón por la que el presidente Gustavo Petro propone modificarlo)\r\nAsí, las corporaciones públicas mencionadas estarían conformados por el 50 por ciento hombres y 50 por ciento mujeres.\r\nAl tratarse de una propuesta de reforma constitucional, deberá ser aprobada en ocho debates en el Congreso y será discutida será en las comisiones primeras de Senado y Cámara de Representantes.\r\n\"El proyecto viene con un amplio respaldo y esperamos poder dar esta discusión\", concluyó María José Pizarro.', 'https://www.eltiempo.com/politica/congreso/con-reforma-constitucional-buscan-paridad-de-genero-en-el-congreso-concejos-y-asambleas-del-pais-3370324', 0, 1),
(16, '2024-10-18', 'Sheinbaum refuerza igualdad de género y estrategias contra la violencia en México', 'Citlalli Hernández destacó los logros y retos del gobierno de Claudia Sheinbaum en sus primeros 100 días, subrayando la lucha contra la violencia de género y el fortalecimiento de derechos para las mujeres.', '../uploads/img_682952a4893603.80178971.jpg', 'En entrevista con Pamela Cerdeira, para MVS Noticias, Citlalli Hernández, secretaria de las Mujeres, habló del gobierno de Claudia Sheinbaum que subraya la igualdad de género en informe a 100 días de su gobierno.\r\n\r\nA 100 días de iniciado el gobierno de Claudia Sheinbaum, la administración subraya su compromiso con la igualdad de género y la lucha contra la violencia hacia las mujeres. Citlalli Hernández, Secretaria de las Mujeres, destacó los avances logrados, como la disminución en la incidencia de feminicidios y la implementación de estrategias de prevención en diversas instituciones del país.\r\n\r\n“En estos 100 días hemos delineado proyectos importantes, incluyendo diagnósticos estatales sobre la situación de las mujeres en cuanto al acceso a sus derechos y niveles de violencia,” señaló Hernández.Una de las iniciativas destacadas es la próxima presentación de una cartilla de derechos para las mujeres, acompañada de una campaña mediática y territorial en escuelas. Esta acción busca no solo dar a conocer los derechos, sino también fomentar su exigencia efectiva.\r\n\r\n“Todas las autoridades están doblemente obligadas, tras las reformas presidenciales, a garantizar la igualdad, promoverla y combatir las violencias,” indicó Hernández.', 'https://mvsnoticias.com/entrevistas/2025/1/13/sheinbaum-refuerza-igualdad-de-genero-estrategias-contra-la-violencia-en-mexico-673771.html', 0, 6),
(17, '2025-04-20', 'La brecha digital de género se consolida tanto en la industria móvil y como en la espacial-satelital', 'Dos informes, uno de la industria móvil y otro de la espacial satelital, ofrecen cifras de la desigualdad y revelan que el tema es un problema social y económico', '../uploads/img_6829531250a639.08590535.jpg', 'La brecha entre hombres y mujeres, en términos generales o lo que puede (o no) acceder una persona por su condición biológica, es del 68,5 por ciento, según lo estimó el Global Gender Gap Report 2024 del Foro Económico Mundial (WEF); un abordaje que mide cuatro dimensiones: la participación y oportunidad económica (situada en el 60,5 por ciento de la brecha cerrada); seguido por el 94,5 por ciento del logro educativo, del 96 por ciento en términos de salud y supervivencia; y cae al 22,5 por ciento cuando se mide el empoderamiento político. ¿Y la brecha digital de género?\r\n\r\nDos informes recientes, uno de la GSMA y otro de la Oficina de las Naciones Unidas para Asuntos del Espacio Ultraterrestre (UNOOSA), pincelaron un escenario de retroceso entre los indicadores registrados en 2024 frente a 2023; pero también que las probabilidades de revertir esta situación dependen de factores como la asequibilidad, la alfabetización y las habilidades digitales. De los trabajos también se recoge que la participación de las mujeres en roles de liderazgo se asocia con mayor productividad, colaboración e innovación. Aquí, la historia de una brecha que no sólo deja afuera a muchas mujeres, sino que también impide vivir en mejores y más beneficiosas sociedades.', 'https://www.telesemana.com/blog/2025/05/16/la-brecha-de-genero-se-consolida-tanto-en-la-industria-movil-y-como-en-la-espacial-satelital/', 0, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `correo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contrasena` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `contrasena`, `fecha_registro`) VALUES
(1, 'qbin', 'valdokevin02@gmail.com', '$2y$10$g7NkSuEBnvXZrV1ZqLAkreb7D4O9K31qNFc4.KtvF9ffAubjE3okC', '2025-05-13 14:19:01'),
(2, 'usuario2', 'usuario2@gmail.com', '$2y$10$BibM4fWZy/H7yx1XZex6D.j1C8BYfSNuUWrQYLwOloIiPWrIIfOom', '2025-05-17 13:34:59'),
(3, 'kevarias', 'infokevarias@gmail.com', '$2y$10$JEZQZaL9xNHsRL8hbaqusOxN7wYlJHevrhLDEOGKGMwj8Bst5KGMW', '2025-05-17 13:36:09');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `id_noticia` (`id_noticia`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD PRIMARY KEY (`id_noticia`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  MODIFY `id_noticia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_noticia`) REFERENCES `publicaciones` (`id_noticia`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
