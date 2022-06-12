-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 12, 2022 at 10:44 PM
-- Server version: 10.3.34-MariaDB-0ubuntu0.20.04.1
-- PHP Version: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `proyectointegrado`
--

--
-- Dumping data for table `api_content_address`
--

INSERT INTO `api_content_address` (`id`, `address`, `postal_code`, `country`, `api_content_user_id`) VALUES
(1, 'Gran Vía 1, Granada', '18001', 'España', 1),
(2, 'Gran Vía 2, Granada', '18010', 'España', 2),
(3, 'Puentezuelas 1, Granada', '18002', 'España', 3),
(4, 'Avenida de la Constitución 4, Granada', '18012', 'España', 4),
(9, 'Gran Vía 1, Granada', '18001', 'España', 9),
(10, 'Gran Vía 2, Granada', '18010', 'España', 10),
(11, 'Puentezuelas 1, Granada', '18002', 'España', 11),
(12, 'Avenida de la Constitución 4, Granada', '18012', 'España', 12);

--
-- Dumping data for table `api_content_phone`
--

INSERT INTO `api_content_phone` (`id`, `api_content_user_id`, `type`, `number`) VALUES
(1, 1, 'Casa', '+34958000000'),
(2, 1, 'Móvil', '+34666000000'),
(3, 2, 'Casa', '987654321'),
(4, 3, 'Móvil', '+34799987654'),
(5, 3, 'Trabajo', '+34958001002'),
(6, 4, 'Móvil', '+34622122133'),
(13, 9, 'Casa', '+34958000000'),
(14, 9, 'Móvil', '+34666000000'),
(15, 10, 'Casa', '987654321'),
(16, 11, 'Móvil', '+34799987654'),
(17, 11, 'Trabajo', '+34958001002'),
(18, 12, 'Móvil', '+34622122133');

--
-- Dumping data for table `api_content_user`
--

INSERT INTO `api_content_user` (`id`, `name`, `public_id`, `user_id`, `last_name`) VALUES
(1, 'Paco', 1, 1, 'Porras'),
(2, 'Pepe', 2, 1, 'Porras'),
(3, 'María', 3, 1, 'Fernández'),
(4, 'Juana', 4, 1, 'Rodríguez'),
(9, 'Paco', 1, 2, 'Porras'),
(10, 'Pepe', 2, 2, 'Porras'),
(11, 'María', 3, 2, 'Fernández'),
(12, 'Juana', 4, 2, 'Rodríguez');

--
-- Dumping data for table `api_token`
--

INSERT INTO `api_token` (`id`, `owner_id`, `token`, `is_enabled`) VALUES
(1, 1, '5ruNYvLIxVtWYIsyDnXskKXzaUqNgdas+ZXzGVP58Zs65+hGpdG08aLl8VvtzZenVZQvIlDAKq9zx+Yu', 1),
(2, 2, 'bmy/2KxKexyrZfJRImUVNR/H2Skj3Vj1C/qxyir08oaW4AfB9HH7nlGMgyUiCA4DBJ/6AzQPit676NcV', 1);

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `has_api_data_copy`) VALUES
(1, 'admin@admin.admin', '[\"ROLE_ADMIN\"]', '$2y$13$e4N/LMFKqaXyewCswDMh/uMMHTIJZhSrJL0hmMQd5ykXBWnmk3Fz6', 1),
(2, 'user1@user.user', '[]', '$2y$13$YRcoFt7OxE8eIN5HJoNuXuMf96eOGlzr3m6a821aT6Hqrc8b7oQLK', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
