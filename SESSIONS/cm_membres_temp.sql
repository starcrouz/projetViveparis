# phpMyAdmin MySQL-Dump
# version 2.2.6
# http://phpwizard.net/phpMyAdmin/
# http://www.phpmyadmin.net/ (download page)
#
# Serveur: localhost
# Généré le : Mardi 21 Janvier 2003 à 23:20
# Version du serveur: 3.23.49
# Version de PHP: 4.2.0
# Base de données: `spip`
# --------------------------------------------------------

#
# Structure de la table `cm_membres_temp`
#

CREATE TABLE cm_membres_temp (
  id_membre int(4) unsigned NOT NULL auto_increment,
  prenom varchar(25) NOT NULL default '',
  nom varchar(25) NOT NULL default '',
  email varchar(45) NOT NULL default '',
  pass varchar(20) NOT NULL default '',
  date_naissance varchar(20) NOT NULL default '',
  role varchar(20) NOT NULL default '',
  prenom_conj varchar(25) NOT NULL default '',
  nom_conj varchar(25) NOT NULL default '',
  date_naissance_conj varchar(20) NOT NULL default '',
  date_mariage varchar(20) NOT NULL default '',
  region_mariage varchar(30) NOT NULL default '',
  budget_mariage float default NULL,
  budget_robe float default NULL,
  newsletter enum('o','n') default 'o',
  info_partenaires enum('o','n') default 'o',
  brochures_partenaires enum('o','n') default 'o',
  rue varchar(50) default NULL,
  ville varchar(25) default NULL,
  code_postal varchar(5) default NULL,
  date_enregistrement varchar(15) NOT NULL default '',
  PRIMARY KEY  (id_membre)
) TYPE=MyISAM;

#
# Contenu de la table `cm_membres_temp`
#

INSERT INTO cm_membres_temp VALUES (1, '', '', '', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '', '', '', '20:07 20/01/03');
INSERT INTO cm_membres_temp VALUES (2, 'reda', 'hassani', 'reda@briefme.co', '3de86576bd3033b6d462', '1991-01-1', '', 'reda', 'bouchtat', '1990-02-2', '2005-08-9', '', '0', '0', 'o', 'o', 'o', '', '', '', '20:07 20/01/03');
INSERT INTO cm_membres_temp VALUES (3, 'reda', 'hassani', 'reda@briefme.co', '3de86576bd3033b6d462', '1991-01-1', 'coucoucou cou', 'reda', 'bouchtat', '1990-02-2', '2005-08-9', '', '333', '333', 'o', '', 'o', '6 rue jean goujon', 'Paris', '75008', '20:12 20/01/03');
INSERT INTO cm_membres_temp VALUES (4, 'reda', 'hassani', 'reda@briefme.co', '3de86576bd3033b6d462', '1991-01-1', 'coucoucou cou', 'reda', 'bouchtat', '1990-02-2', '2005-08-9', 'Alsace', '333', '333', 'o', '', 'o', '6 rue jean goujon', 'Paris', '75008', '20:14 20/01/03');
INSERT INTO cm_membres_temp VALUES (5, 'reda', 'hassani', 'reda@briefme.co', '3de86576bd3033b6d462', '1991-01-1', 'coucoucou cou', 'reda', 'bouchtat', '1990-02-2', '2005-08-9', 'Alsace', '333', '333', 'o', '', 'o', '6 rue jean goujon', 'Paris', '75008', '20:20 20/01/03');
INSERT INTO cm_membres_temp VALUES (6, 'reda hass', 'hassani', 'reda@briefme.co', '3de86576bd3033b6d462', '1991 Janvier 1', 'La mariée', 'redaaa mat pas', 'hasss', '1991-01-1', '2000-01-1', 'Alsace', '333', '333', 'o', '', 'o', '6 rue jean goujon', 'paris champs elysees', '75008', '21:06 20/01/03');
INSERT INTO cm_membres_temp VALUES (7, 'red', 'reda', 'reda@briefme.co', '3de86576bd3033b6d462', '1990-02-3', 'La mariée', 'reda', 'reda', '1991-01-1', '2000-01-1', 'Alsace', '0', '0', 'o', 'o', 'o', '', '', '', '21:08 20/01/03');

