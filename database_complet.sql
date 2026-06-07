-- Database schema consolidation for ViveParis (PHP 8 / MySQL)
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `lieux` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `lieu` VARCHAR(100) DEFAULT '',
  `idMediaPrincipal` INT DEFAULT NULL,
  `x` INT NOT NULL DEFAULT 0,
  `y` INT NOT NULL DEFAULT 0,
  `numero` INT DEFAULT NULL,
  `voie` VARCHAR(30) DEFAULT '',
  `rue` VARCHAR(100) DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `typesdelieux` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `titre` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `picto` VARCHAR(100) DEFAULT '',
  `couleur` VARCHAR(100) DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lieux_typesdelieux` (
  `idlieu` INT NOT NULL,
  `idtypedelieu` INT NOT NULL,
  PRIMARY KEY (`idlieu`, `idtypedelieu`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `medias` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `titremedia` VARCHAR(150) NOT NULL DEFAULT '',
  `fichier` VARCHAR(150) NOT NULL DEFAULT '',
  `repertoire` VARCHAR(150) NOT NULL DEFAULT '',
  `auteurm` VARCHAR(100) NOT NULL DEFAULT '',
  `poids` INT NOT NULL DEFAULT 0,
  `date` INT DEFAULT NULL,
  `anecdote` TEXT NOT NULL,
  `auteura` VARCHAR(100) NOT NULL DEFAULT '',
  `note` INT NOT NULL DEFAULT 0,
  `soleil` TINYINT NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lieux_medias` (
  `idmedia` INT NOT NULL,
  `idlieu` INT NOT NULL,
  PRIMARY KEY (`idmedia`, `idlieu`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `caracteristiques` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `titre` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `picto` VARCHAR(100) DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `medias_caracteristiques` (
  `idMedia` INT NOT NULL,
  `idCaracteristique` INT NOT NULL,
  PRIMARY KEY (`idMedia`, `idCaracteristique`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `categorie` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `picto` VARCHAR(100) DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `medias_categories` (
  `idMedia` INT NOT NULL,
  `idCategorie` INT NOT NULL,
  PRIMARY KEY (`idMedia`, `idCategorie`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `login` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- Seed default user
INSERT INTO `utilisateurs` (`id`, `login`, `password`) VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3') ON DUPLICATE KEY UPDATE id=id;

-- Seed types de lieux
INSERT INTO `typesdelieux` (`id`, `titre`, `description`, `picto`, `couleur`) VALUES
(1, 'Eglise', 'Lieux religieux', 'croix.gif', 'rouge.gif'),
(2, 'Monument', 'Monuments et bâtiments notables', 'monuments.gif', 'bleuClair.gif'),
(3, 'Musée', 'Musées et galeries', 'musee.gif', 'marron.gif'),
(4, 'Parc / Jardin', 'Parcs, jardins et espaces verts', 'arbre.gif', 'vert.gif'),
(5, 'Rue / Place', 'Rues, avenues, boulevards, places', 'rue.gif', 'blanc.gif'),
(6, 'Bar / Restaurant', 'Lieux de restauration', 'bar.gif', 'grisClair.gif')
ON DUPLICATE KEY UPDATE id=id;

-- Seed lieux
INSERT INTO `lieux` (`id`, `lieu`, `idMediaPrincipal`, `x`, `y`, `numero`, `voie`, `rue`) VALUES
(1, 'Impasse de la Gaité', 1, 3328, 4355, 0, 'impasse de la', 'Gaité'),
(2, 'Tour Montparnasse', 4, 2928, 3852, 0, '', '')
ON DUPLICATE KEY UPDATE id=id;

-- Seed lieux_typesdelieux
INSERT INTO `lieux_typesdelieux` (`idlieu`, `idtypedelieu`) VALUES
(1, 5),
(2, 2)
ON DUPLICATE KEY UPDATE idlieu=idlieu;

-- Seed medias
INSERT INTO `medias` (`id`, `titremedia`, `fichier`, `repertoire`, `auteurm`, `poids`, `date`, `anecdote`, `auteura`, `note`, `soleil`) VALUES
(1, 'Gaité ?', 'PICT0004.JPG', 'deja repertorie', '', 2, 1010238688, 'Anecdote sur l\'impasse de la gaité.', '', 2, 0),
(2, 'Test média 2', 'PICT0012.JPG', 'mixte', '', 2, 1010238883, 'Une autre petite anecdote.', '', 2, 0),
(3, 'Miro', 'salon tableaux.JPG', 'maison', 'steph', 2, 952081932, 'Description et anecdotes amusantes.', '', 2, 0),
(4, 'Droit comme un i', 'PICT0011.JPG', 'mixte', 'Steph', 2, 1010238864, 'Anecdote sur la Tour Montparnasse.', '', 2, 0)
ON DUPLICATE KEY UPDATE id=id;

-- Seed lieux_medias
INSERT INTO `lieux_medias` (`idmedia`, `idlieu`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 2)
ON DUPLICATE KEY UPDATE idmedia=idmedia;
