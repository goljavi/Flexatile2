-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-11-2016 a las 19:39:32
-- Versión del servidor: 5.5.52-MariaDB
-- Versión de PHP: 5.6.27

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `276909_famago`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `description` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date` date NOT NULL,
  `image_file` text NOT NULL,
  `thumbnail` text NOT NULL,
  `createdby` varchar(255) NOT NULL,
  `editby` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `blog`
--

INSERT INTO `blog` (`id`, `title`, `url`, `description`, `content`, `date`, `image_file`, `thumbnail`, `createdby`, `editby`, `keywords`) VALUES
(2, 'asdasd', 'asdasd', 'sadasdsa', 'asdsad', '2016-10-22', 'pto_dorso.jpg', 'pto_dorso_thumb.jpg', '', '', 'sadsad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cms`
--

CREATE TABLE IF NOT EXISTS `cms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `description` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `important` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `cms`
--

INSERT INTO `cms` (`id`, `title`, `url`, `description`, `content`, `date`, `important`) VALUES
(4, 'Home', 'home', '-', 'home', '2016-09-01 17:43:32', 1),
(5, 'Contacto', 'contacto', '-', 'contacto', '2016-09-01 17:47:14', 1),
(7, 'Quienes Somos', 'quienes-somos', 'quienes somos', '<div class="col-sm-6"><p><br><img src="http://kingofwallpapers.com/flores/flores-004.jpg" xss="removed"></p></div><div class="col-sm-6"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla egestas justo sed metus consequat, in ultricies quam rhoncus. Quisque ultricies bibendum consequat. Nam malesuada nisl eu arcu dictum porttitor. Nam placerat lobortis massa sed aliquet. Vivamus sit amet lobortis ante, quis cursus nibh. Nunc lobortis, elit nec finibus hendrerit, eros nisl tincidunt lorem, eget viverra lacus libero non augue. Suspendisse maximus ex arcu, at consequat enim egestas non. Duis mattis mauris nec sem viverra, eu euismod mi tempor. Aliquam feugiat ut leo malesuada pellentesque. Ut vel tincidunt enim. Nullam lacinia mi a ante maximus, in eleifend erat aliquet.</p><p>Sed quis lorem sagittis, porttitor justo vitae, vestibulum metus. Ut vehicula aliquam pretium. Nunc vehicula ut augue id sollicitudin. Sed quis pulvinar purus. Nullam eget faucibus velit, nec dapibus arcu. Quisque id gravida sapien. In mattis aliquam est, eget tristique tortor ullamcorper sed. Suspendisse malesuada ante dui, sed ornare orci sodales ut.</p><p>Ut sit amet leo leo. Proin eget dictum est, id suscipit augue. Nullam nec dui eu lectus placerat pulvinar. Nunc ac elit nec lacus lobortis tristique. Donec eu mauris eget orci porttitor faucibus. Sed magna odio, consectetur vel ante a, vehicula dapibus ipsum. Etiam elementum metus sit amet libero cursus, faucibus vestibulum justo sagittis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus odio lorem, suscipit ut erat nec, dignissim luctus urna. Nullam sit amet finibus velit, lacinia cursus lectus. Nunc placerat erat non leo mollis ullamcorper. Vestibulum erat sapien, porta ut enim in, faucibus sagittis dui. In hac habitasse platea dictumst. Nulla pharetra ornare velit, nec mollis felis porttitor eu.</p></div>', '2016-09-13 20:53:27', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_images`
--

CREATE TABLE IF NOT EXISTS `item_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_file` text NOT NULL,
  `thumb` text NOT NULL,
  `item_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Volcado de datos para la tabla `item_images`
--

INSERT INTO `item_images` (`id`, `image_file`, `thumb`, `item_id`, `priority`) VALUES
(17, '18760.jpg', '18760_thumb.jpg', 13, 1),
(18, '21473.jpg', '21473_thumb.jpg', 13, 2),
(19, '24793.jpg', '24793_thumb.jpg', 16, 1),
(20, '16659.jpg', '16659_thumb.jpg', 15, 1),
(21, '15492.jpg', '15492_thumb.jpg', 17, 1),
(22, '22471.jpg', '22471_thumb.jpg', 18, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `site_security`
--

CREATE TABLE IF NOT EXISTS `site_security` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `permissions` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `site_security`
--

INSERT INTO `site_security` (`id`, `username`, `password`, `permissions`) VALUES
(1, 'demo', '62cc2d8b4bf2d8728120d052163a77df', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `slider`
--

CREATE TABLE IF NOT EXISTS `slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_file` text NOT NULL,
  `thumb` text NOT NULL,
  `priority` int(11) NOT NULL,
  `link` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `slider`
--

INSERT INTO `slider` (`id`, `image_file`, `thumb`, `priority`, `link`) VALUES
(5, '2898.png', '2898_thumb.png', 1, '#'),
(6, '32291.jpg', '32291_thumb.jpg', 2, '#'),
(7, '3872.jpg', '3872_thumb.jpg', 3, '#');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `store_categories`
--

CREATE TABLE IF NOT EXISTS `store_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `url` text NOT NULL,
  `priority` int(11) NOT NULL,
  `important` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Volcado de datos para la tabla `store_categories`
--

INSERT INTO `store_categories` (`id`, `title`, `parent_id`, `url`, `priority`, `important`) VALUES
(30, 'Home', 0, 'home', 0, 1),
(31, 'Flores', 0, 'ramos-de-flores', 3, 0),
(32, 'Ramos', 0, 'ramos', 1, 0),
(33, 'Destacados', 0, 'destacados', 2, 0),
(34, 'Rosas', 32, 'rosas', 2, 0),
(35, 'Jazmines', 32, 'jazmines', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `store_cat_assign`
--

CREATE TABLE IF NOT EXISTS `store_cat_assign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Volcado de datos para la tabla `store_cat_assign`
--

INSERT INTO `store_cat_assign` (`id`, `category_id`, `item_id`) VALUES
(8, 30, 13),
(9, 30, 15),
(10, 30, 16),
(14, 30, 17),
(15, 30, 18),
(16, 31, 15),
(17, 32, 15),
(18, 34, 15),
(19, 33, 18),
(20, 31, 18),
(21, 35, 17),
(22, 32, 17),
(23, 31, 16),
(24, 32, 16),
(25, 34, 16),
(26, 35, 16),
(27, 33, 16),
(28, 31, 13),
(29, 32, 13),
(30, 34, 13),
(31, 35, 13),
(32, 33, 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `store_items`
--

CREATE TABLE IF NOT EXISTS `store_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_title` varchar(255) NOT NULL,
  `item_url` varchar(255) NOT NULL,
  `item_price` decimal(7,2) NOT NULL,
  `item_description` text NOT NULL,
  `was_price` decimal(7,2) NOT NULL,
  `item_active` int(11) NOT NULL,
  `date_created` int(11) NOT NULL,
  `date_edit` int(11) NOT NULL,
  `createdby` varchar(255) NOT NULL,
  `editby` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Volcado de datos para la tabla `store_items`
--

INSERT INTO `store_items` (`id`, `item_title`, `item_url`, `item_price`, `item_description`, `was_price`, `item_active`, `date_created`, `date_edit`, `createdby`, `editby`) VALUES
(13, 'VENETO', 'veneto', 400.00, 'La fuerza de lo compacto; la explosión de colores y tonalidades caprichosas y sensuales hacen de este ramo el complemento ideal para dinamizar y dotar de luz cualquier ambiente. Transformarás los espacios de los tuyos.', 500.00, 1, 1472414546, 1473785942, '', ''),
(15, 'ILUSIÓN De medianoche al amanecer', 'ilusion', 500.00, 'ILUSIÓN', 0.00, 1, 1472414958, 1473799691, '', 'admin'),
(16, 'SHYRAZ', 'shyraz', 300.00, 'Arreglo bicolor de Claveles. Clásico y con fuerza, se convierte en un ramos resultón para cualquier ocasión.', 0.00, 1, 1472414982, 1473786037, '', ''),
(17, 'RECIFE', 'recife', 320.00, 'Composición cálida y entusiasta en tonalidades naranjas. Optimista, alegre y jovial. Sin duda inundará de alegría el estado de ánimo de los tuyos.', 0.00, 1, 1473786875, 1473787515, 'superadmin', 'superadmin'),
(18, 'MEDITERRÁNEO', 'mediterraneo', 360.00, 'El mediterráneo inspira esta composición llena de elegancia y sofisticación. El oro y el púrpura se fusionan para emular luz y frescura del mediterráneo. Un ramo, sin lugar a dudas que sorprenderá y no dejará indiferente. Te recordarán como la frescura y la energía del mediterráneo.', 0.00, 1, 1473787583, 1473787610, 'superadmin', 'superadmin');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
