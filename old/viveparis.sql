# phpMyAdmin MySQL-Dump
# version 2.2.0rc4
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# Serveur: localhost
# Généré le : March 9, 2002, 11:49 am
# Version du serveur: 3.23.40
# Version de PHP: 4.0.6
# Base de données: `viveparis`
# --------------------------------------------------------

#
# Structure de la table `lieux`
#

DROP TABLE IF EXISTS `lieux`;
CREATE TABLE `lieux` (
  `id` tinyint(4) NOT NULL auto_increment,
  `lieu` varchar(100) default NULL,
  `idMediaPrincipal` smallint(6) default NULL,
  `x` smallint(6) NOT NULL default '0',
  `y` smallint(6) NOT NULL default '0',
  `numero` smallint(6) default NULL,
  `voie` varchar(15) default NULL,
  `rue` varchar(30) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

#
# Contenu de la table `lieux`
#

INSERT INTO `lieux` (`id`, `lieu`, `idMediaPrincipal`, `x`, `y`, `numero`, `voie`, `rue`) VALUES (1,'','',3328,4355,'','impasse de la','Gaité');
INSERT INTO `lieux` (`id`, `lieu`, `idMediaPrincipal`, `x`, `y`, `numero`, `voie`, `rue`) VALUES (2,'Tour Montparnasse',4,2928,3852,'','','');
# --------------------------------------------------------

#
# Structure de la table `lieux_medias`
#

DROP TABLE IF EXISTS `lieux_medias`;
CREATE TABLE `lieux_medias` (
  `idmedia` smallint(6) NOT NULL default '0',
  `idlieu` smallint(6) NOT NULL default '0'
) TYPE=MyISAM;

#
# Contenu de la table `lieux_medias`
#

INSERT INTO `lieux_medias` (`idmedia`, `idlieu`) VALUES (1,1);
INSERT INTO `lieux_medias` (`idmedia`, `idlieu`) VALUES (2,1);
INSERT INTO `lieux_medias` (`idmedia`, `idlieu`) VALUES (3,1);
INSERT INTO `lieux_medias` (`idmedia`, `idlieu`) VALUES (4,2);
# --------------------------------------------------------

#
# Structure de la table `medias`
#

DROP TABLE IF EXISTS `medias`;
CREATE TABLE `medias` (
  `id` tinyint(4) NOT NULL auto_increment,
  `titremedia` varchar(150) NOT NULL default '',
  `fichier` varchar(150) NOT NULL default '',
  `auteurm` varchar(100) NOT NULL default '',
  `note` tinyint(4) NOT NULL default '0',
  `date` int(14) default NULL,
  `anecdote` text NOT NULL,
  `auteura` varchar(100) NOT NULL default '',
  `categories` varchar(150) NOT NULL default '',
  `soleil` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) TYPE=MyISAM;

#
# Contenu de la table `medias`
#

INSERT INTO `medias` (`id`, `titremedia`, `fichier`, `auteurm`, `note`, `date`, `anecdote`, `auteura`, `categories`, `soleil`) VALUES (1,'gaité ?','deja repertorie/PICT0004.JPG','',2,1010238688,'ze','','rue','');
INSERT INTO `medias` (`id`, `titremedia`, `fichier`, `auteurm`, `note`, `date`, `anecdote`, `auteura`, `categories`, `soleil`) VALUES (2,'ddsdf','mixte/PICT0012.JPG','',2,1010238883,'sdf','','sdf','');
INSERT INTO `medias` (`id`, `titremedia`, `fichier`, `auteurm`, `note`, `date`, `anecdote`, `auteura`, `categories`, `soleil`) VALUES (3,'miro','maison/salon tableaux.JPG','steph',2,952081932,'kdjlfsjdlkfjs &#039;&#039;&#039;&#039; &quot;&quot;&quot;&quot;&quot; &amp;&amp;&amp;&amp; &ccedil;&ccedil; &egrave;&eacute;&ugrave;%','','maison','');
INSERT INTO `medias` (`id`, `titremedia`, `fichier`, `auteurm`, `note`, `date`, `anecdote`, `auteura`, `categories`, `soleil`) VALUES (4,'Droit comme un i','mixte/PICT0011.JPG','Steph',2,1010238864,'','','monument','');

