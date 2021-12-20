/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/ arthutlmtt /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE arthutlmtt;

DROP TABLE IF EXISTS user;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` text NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS announces;
CREATE TABLE `announces` (
  `id` int NOT NULL AUTO_INCREMENT,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postcode` int NOT NULL,
  `daily_price` double NOT NULL,
  `images` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id_id` int NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `max_animals` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3B879C659D86650F` (`user_id_id`),
  CONSTRAINT `FK_3B879C659D86650F` FOREIGN KEY (`user_id_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS doctrine_migration_versions;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS messages;
CREATE TABLE `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int NOT NULL,
  `recipient_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `is_read` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DB021E96F624B39D` (`sender_id`),
  KEY `IDX_DB021E96E92F8F78` (`recipient_id`),
  CONSTRAINT `FK_DB021E96E92F8F78` FOREIGN KEY (`recipient_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_DB021E96F624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS reservations;
CREATE TABLE `reservations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `gardien_id_id` int NOT NULL,
  `client_id_id` int NOT NULL,
  `announce_id_id` int NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4DA23946BD882B` (`gardien_id_id`),
  KEY `IDX_4DA239DC2902E0` (`client_id_id`),
  KEY `IDX_4DA23971A61F06` (`announce_id_id`),
  CONSTRAINT `FK_4DA23946BD882B` FOREIGN KEY (`gardien_id_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_4DA23971A61F06` FOREIGN KEY (`announce_id_id`) REFERENCES `announces` (`id`),
  CONSTRAINT `FK_4DA239DC2902E0` FOREIGN KEY (`client_id_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO announces(id,address,city,postcode,daily_price,images,title,user_id_id,description,max_animals) VALUES(13,'16 rue des fontenottes','Besançon',25000,12,'Maison-traditionnelle-du-Haut-Doubs-619788adbe8e9.jpg','Ma maison dans le doubs',5,X'4a65206d652066657261697320756e20706c61697369722064652067617264657220766f74726520636869656e2e204c657320636869656e73207327616d757365726f6e74206465686f7273207369206c6520626561752074656d7073206e6f7573206c65207065726d65742c206e6f757320666169736f6e7320322067726f737365732070726f6d656e6164657320706172206a6f75722064616e73206c6120666f72c3aa742073697475c3a965206465727269c3a872652e',3),(14,'8 chemin de montoille','Besançon',25000,9,'44284-1-6197892cec96f.jpg','Famille Gardunchien',5,X'4e6f7472652066616d696c6c6520736572612072617669652064276163637565696c6c697220766f74726520636869656e',1),(15,'16 rue paul verlaine','La courneuve',93210,15,'appartement-55-m-aubervilliers-93300-2910120625653323673-6197b0bbb5bd5.jpg','Appart à la Courneuve',5,X'417070617274656d656e7420c3a0206c6120436f75726e6575766520617665632062616c636f6e20706f757220717565206c657320636869656e73207072656e6e656e74206c276169722e204d65732063617061636974c3a97320736f6e7420646520646575782070657469747320636869656e73206f7520756e207365756c2067726f7320636869656e2e0d0a332070726f6d656e6164657320706172206a6f75722c2064616e73206c657320626f69732c207061726320616c656e746f7572732c206f7520656e2076696c6c652e',2),(16,'21 rue gambetta','Besancon',25000,25,'44284-1-6197b1e609972.jpg','La maison a besancon',9,X'42656c6c65206d6169736f6e',4);

INSERT INTO doctrine_migration_versions(version,executed_at,execution_time) VALUES('DoctrineMigrations\\Version20211104082721','2021-11-04 09:27:38',143),('DoctrineMigrations\\Version20211104084343','2021-11-04 09:43:52',118),('DoctrineMigrations\\Version20211104084500','2021-11-04 09:45:07',246),('DoctrineMigrations\\Version20211104092318','2021-11-04 10:23:24',118),('DoctrineMigrations\\Version20211105111624','2021-11-05 12:20:48',146),('DoctrineMigrations\\Version20211105120013','2021-11-05 13:00:19',140),('DoctrineMigrations\\Version20211108144930','2021-11-08 15:49:43',183),('DoctrineMigrations\\Version20211119112104','2021-11-19 12:21:11',2447);

INSERT INTO messages(id,sender_id,recipient_id,title,message,created_at,is_read) VALUES(1,5,6,'Titre',X'4d657373616765','2021-11-12 14:19:02',0),(2,5,6,'Yo',X'4d6563','2021-11-12 14:58:02',1),(3,5,6,'Yo',X'617a65617a65','2021-11-12 15:04:04',0),(4,5,6,'Yo',X'617a65617a65','2021-11-12 15:04:33',0),(7,5,9,'rfez',X'667a65','2021-12-16 17:30:02',1),(8,9,5,'Premier message',X'617a65617a65','2021-12-16 17:33:02',1);

INSERT INTO reservations(id,gardien_id_id,client_id_id,announce_id_id,start,end) VALUES(12,5,5,14,'2021-11-22 12:24:07','2021-11-28 12:24:07'),(13,5,5,14,'2021-11-29 12:24:42','2021-12-05 12:24:42'),(14,5,5,15,'2021-11-22 15:12:41','2021-11-29 15:12:41'),(15,5,9,13,'2021-11-22 15:18:35','2021-11-29 15:18:35');
