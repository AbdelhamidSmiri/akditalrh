-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           5.7.28 - MySQL Community Server (GPL)
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Listage de la structure de table akditalrh. hotelprices
CREATE TABLE IF NOT EXISTS `hotelprices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) NOT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `prix` varchar(45) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Listage des données de la table akditalrh.hotelprices : ~0 rows (environ)
INSERT IGNORE INTO `hotelprices` (`id`, `hotel_id`, `date_debut`, `date_fin`, `prix`, `created`) VALUES
	(1, 1, '2025-06-23', '2025-09-23', '1500', '2025-06-23 08:50:06'),
	(2, 1, '2025-01-23', '2025-06-22', '500', '2025-06-23 08:56:16');

-- Listage de la structure de table akditalrh. hotels
CREATE TABLE IF NOT EXISTS `hotels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) DEFAULT NULL,
  `etoile` varchar(45) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `images` text,
  `mail` varchar(255) DEFAULT NULL,
  `telephone` varchar(45) DEFAULT NULL,
  `nom_responsable` varchar(255) DEFAULT NULL,
  `reglement` text,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Listage des données de la table akditalrh.hotels : ~0 rows (environ)
INSERT IGNORE INTO `hotels` (`id`, `nom`, `etoile`, `region`, `ville`, `adresse`, `images`, `mail`, `telephone`, `nom_responsable`, `reglement`, `created`) VALUES
	(1, 'hotel1', '3', 'Casablanca', 'Noisy-le-Grand', '1 LT BACHKOU ESC A ETG 3 APPT 14', '68591145595bd.jpg', 'x@hotel.com', '0522282763', 'ahmed', 'check in 16:00 \r\ncheck out 10:00\r\n', '2025-06-23 08:33:09');

-- Listage de la structure de table akditalrh. reservations
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `checkin` date DEFAULT NULL,
  `checkout` date DEFAULT NULL,
  `num_odm` varchar(45) DEFAULT NULL,
  `ordre_mission` varchar(255) DEFAULT NULL,
  `cin` varchar(255) DEFAULT NULL,
  `message` text,
  `created` datetime DEFAULT NULL,
  `confirmation` varchar(255) DEFAULT NULL,
  `etat` varchar(45) DEFAULT NULL,
  `reponse` text,
  `date_reponse` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Listage des données de la table akditalrh.reservations : ~0 rows (environ)

-- Listage de la structure de table akditalrh. roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(255) DEFAULT NULL,
  `plafond_hotel` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Listage des données de la table akditalrh.roles : ~4 rows (environ)
INSERT IGNORE INTO `roles` (`id`, `role`, `plafond_hotel`) VALUES
	(1, 'Admin', '1000'),
	(2, 'Agence', '0'),
	(3, 'Achat', '1500'),
	(4, 'Directeur de projet', '1000');

-- Listage de la structure de table akditalrh. sites
CREATE TABLE IF NOT EXISTS `sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Listage des données de la table akditalrh.sites : ~1 rows (environ)
INSERT IGNORE INTO `sites` (`id`, `nom`) VALUES
	(1, 'site 1 '),
	(2, 'Site Casa 2');

-- Listage de la structure de table akditalrh. users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `etat` varchar(50) DEFAULT 'Actif',
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Listage des données de la table akditalrh.users : ~1 rows (environ)
INSERT IGNORE INTO `users` (`id`, `role_id`, `username`, `password`, `etat`, `nom`, `prenom`, `created`) VALUES
	(1, 1, 'admin', '95ebe0b79574fa6549dc5ec7e2722ad650ce7cf2', '1', 'admin', 'admin', '2025-06-15 23:37:50'),
	(2, 2, 'agence', '95ebe0b79574fa6549dc5ec7e2722ad650ce7cf2', 'Actif', 'agence', 'A1', '2025-06-20 17:02:35'),
	(3, 4, 'd1', '3e24583cacfac14295a8f97531360a64d038a6bc', 'Actif', 'un test', 'ok', '2025-06-22 13:57:40');

-- Listage de la structure de table akditalrh. volreservations
CREATE TABLE IF NOT EXISTS `volreservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `depart` varchar(255) DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `date_aller` date DEFAULT NULL,
  `date_retour` date DEFAULT NULL,
  `num_odm` varchar(45) DEFAULT NULL,
  `ordre_mission` varchar(255) DEFAULT NULL,
  `cin` varchar(255) DEFAULT NULL,
  `passport` varchar(255) DEFAULT NULL,
  `message` text,
  `created` datetime DEFAULT NULL,
  `etat` varchar(45) DEFAULT 'En cours',
  `documents` text COMMENT 'Ajout un document avec un titre save as json',
  `reponse` text,
  `date_reponse` datetime DEFAULT NULL,
  `num_vol` varchar(255) DEFAULT NULL,
  `file_aller` varchar(255) DEFAULT NULL,
  `file_retour` varchar(255) DEFAULT NULL,
  `transfer` varchar(255) DEFAULT NULL,
  `nom_transfer` varchar(45) DEFAULT NULL,
  `date_transfer` datetime DEFAULT NULL,
  `tel_transfer` varchar(45) DEFAULT NULL,
  `description_transfer` text,
  `prix_vol` varchar(50) DEFAULT '0',
  `prix_transfert` varchar(50) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Listage des données de la table akditalrh.volreservations : ~5 rows (environ)
INSERT IGNORE INTO `volreservations` (`id`, `user_id`, `site_id`, `depart`, `destination`, `date_aller`, `date_retour`, `num_odm`, `ordre_mission`, `cin`, `passport`, `message`, `created`, `etat`, `documents`, `reponse`, `date_reponse`, `num_vol`, `file_aller`, `file_retour`, `transfer`, `nom_transfer`, `date_transfer`, `tel_transfer`, `description_transfer`, `prix_vol`, `prix_transfert`) VALUES
	(1, 1, 1, 'casa', 'rabat', '2025-06-23', '2025-07-19', '', '685466c6a3dde.png', '685466c6a4999.png', '685466c6a4d4d.png', 'ok un test', '2025-06-19 19:36:38', 'Validé', '68559fe40328d.png', 'ok', '2025-06-20 17:52:36', 'K1022', '68559fe4016e2.png', '68559fe402063.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(2, 1, 1, 'casa', 'rabat', '2025-06-20', '2025-06-21', 'ok un test', '68558f0f974ab.png', '68558f0f98c33.png', '68558f0f99cc4.png', 'ok un test', '2025-06-20 16:40:47', 'Annulé', NULL, 'je n\'ai pas trouvé ce que tu cherche ', '2025-06-22 13:24:49', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(3, 1, 1, 'casa', 'rabat', '2025-06-20', '2025-06-20', 'ok un test', '68558f936140b.png', '', '', 'ok', '2025-06-20 16:42:59', 'Validé', '6858006e220b6.png', 'ok merci', '2025-06-22 13:09:02', 'K1022', '6858006e21104.png', '6858006e21a64.png', '1', 'Mohamed', '2025-06-22 13:08:00', '0662358681', 'ok un truc ', '2000', '1200'),
	(4, 3, 1, 'casa', 'rabat', '2025-06-22', '2025-09-22', 'ok un test', '685826e4f31cb.png', '685826e4f3e1d.png', '685826e500179.png', 'un test', '2025-06-22 15:53:09', 'Validé', '6858296be1ea5.jpg', 'ok ', '2025-06-22 16:03:55', '85552', '6858296be1305.png', '6858296be187c.png', '1', 'HAmid', '2025-06-22 16:03:00', '0662358681', 'ok il va venir', '15200', '1200'),
	(5, 3, 1, 'casa', 'rabat', '2025-06-22', '2025-06-23', '5223', '6858367bdcefa.png', '6858367bddc08.png', '6858367bdebd9.png', 'ok rifai', '2025-06-22 16:59:39', 'Validé', '', 'ok vous avez le vol a midi', '2025-06-22 17:02:52', 'K1022', '6858373cc8191.png', '6858373cc88ac.png', '1', 'ahmed', '2025-06-22 17:00:00', '0662358681', 'il va venir avec voiture', '14220', '2000');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
