# phpMyAdmin SQL Dump
# version 2.5.6-rc1
# http://www.phpmyadmin.net
#
# Serveur: bsql-v.free.fr
# Généré le : Mardi 29 Juin 2004 à 23:59
# Version du serveur: 4.0.20
# Version de PHP: 4.3.4
# 
# Base de données: `viveparis`
# 
CREATE DATABASE `viveparis`;
USE viveparis;

# --------------------------------------------------------

#
# Structure de la table `anecdotes`
#

DROP TABLE IF EXISTS `anecdotes`;
CREATE TABLE `anecdotes` (
  `ID` tinyint(4) NOT NULL auto_increment,
  `TITRE` tinytext NOT NULL,
  `TEXTE` text NOT NULL,
  `URLIMAGE` tinytext NOT NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `ID_2` (`ID`),
  KEY `ID` (`ID`)
) TYPE=MyISAM PACK_KEYS=1 AUTO_INCREMENT=14 ;

#
# Contenu de la table `anecdotes`
#

INSERT INTO `anecdotes` (`ID`, `TITRE`, `TEXTE`, `URLIMAGE`) VALUES (1, 'Macabre cave', 'Quel ne fut pas l\'étonnement d\'un voisin de ce cimetière de plus de 8 siècles d\'âge lorsqu\'il vit débouler ces dizaines de mètres cube de macchabées par le mur de sa cave qui avait cédé. \\n\r\nEn effet, pendant toutes ces années, se sont retrouvés là, plusieurs millions de parisiens de toutes origines : les morts de l\'Hôtel-Dieu (fournisseur de 50.000 pestiférés en 5 semaines de 1418), les inconnus de la Morgue (lien morgue) et les habitués des 22 paroisses de Paris qui ne possédaient pas de cimetière. \\n\r\nA la fermeture du cimetière en 1786, le niveau de la terre entre ses hauts murs s\'était élevé de 2 mètres! Normal,  que sous une telle pression, les caves du voisinnage n\'y resistent pas !', 'comblesAuxCranes.jpg');
INSERT INTO `anecdotes` (`ID`, `TITRE`, `TEXTE`, `URLIMAGE`) VALUES (2, 'Quelle Terre !', 'La terre du cimetière des Innocents était réputée comme "excellente et mange son cadavre en 9 jours". \\nCette qualité faisait des jaloux, à tel point que les ecclésiastes, inhumés dans les cimetières de leur paroisse, demandaient qu\'on ajouta une poignée de cet humus dans leur cercueil! \\nIl faut dire que le cimetière était surpeuplé, chacun pouvait y venir avec sa pelle enterrer ceux qu\'il voulait. Les fosses communes pouvaient contenir jusqu\'à 1500 corps superposés! On y accueillait même les inconnus de la morgue et les cadavres découverts sur la voie publique mais sur un lopin isolé et non beni!', 'ileDeLaCite.jpg');
INSERT INTO `anecdotes` (`ID`, `TITRE`, `TEXTE`, `URLIMAGE`) VALUES (3, 'Les petits rats', 'C’est de Paris que provient l’expression les ‘ petits rats ‘ : jeunes danseurs logés et nourris à l’opéra. Mais d’où vient-elle ? \nOn dit qu’elle viendrait de travaux effectués dans le sol de l’Opéra Garnier et que les ouvriers auraient précipitamment arrêté leur labeur vu l’affluence des rats due à la présence du gigantesque réservoir d’eau anti-incendie sous le Palais Garnier. Comme Balzac utilise cette expression dans « Splendeurs et misères des courtisanes » et qu’il est mort 25 ans avant la construction de cette salle, oublions cette explication. \nBalzac, Larousse et Théophile Gautier s’accordent à dire que ces jeunes danseurs ont été surnommés ainsi en raison de « leur plaisir de faire du dégât » , « leur appétit féroce » et leur façon d’être « toujours en mouvement ». \nEt si tout simplement « les petits rats » étaient la contraction des « petits danseurs de l’opéra » ?', 'test.jpg');
INSERT INTO `anecdotes` (`ID`, `TITRE`, `TEXTE`, `URLIMAGE`) VALUES (4, 'Mal conservé', 'Funeste journée que ce 2 mars 1871 qui vit le partie ouest de Paris envahie par les Prussiens. Napoléon III avait été défait à Sedan (le 4 septembre 1870) par Bismarck et la Commune de Paris n’allait commencer que le 18 mars. Ce jour donc, M. Morel-Fatio, conservateur du musée du Louvre et grand patriote, se fit accompagner à son travail par son fils, officier des gardes mobiles. Le soir, n’ayant aucune nouvelle de son père, celui-ci accourut au Louvre et, ne le trouvant pas à son bureau, montât sur la terrasse. Il y découvrit son père allongé, une longue vue à la main. Le médecin dépêché sur place conclut à une congestion cérébrale survenue à l’heure exacte où les allemands entraient dans Paris !', 'ileDeLaCite.jpg');
INSERT INTO `anecdotes` (`ID`, `TITRE`, `TEXTE`, `URLIMAGE`) VALUES (5, 'Bisou', 'En 1306, le prévôt de Paris, représentant de la police royale, commit une bourde lourde de conséquences. Il fit pendre un clerc du nom de Pierre le Barbier pour un assassinat alors que cet acte de justice revenait à l’Eglise. A l’époque, de nombreux seigneurs « hauts justiciers » avaient le droit de vie ou de mort sur leur quartier. Une procession fut donc organisée jusqu’au domicile de Pierre Jumel (le prévôt !) à laquelle participèrent tous les curés de Paris !\nNon content de donner tort au prévôt, Philippe le Bel le destitua, le condamna à demander pardon à l’Eglise, à aller dépendre lui-même le corps du pendu, à l’embrasser sur la bouche ( !), et même, cerise sur le gâteau, le pape excommunia Pierre Jumel !', 'ileDeLaCite.jpg');
INSERT INTO `anecdotes` (`ID`, `TITRE`, `TEXTE`, `URLIMAGE`) VALUES (6, 'Forteresse !', 'Le ministère des finances de Bercy n’est en fait rien d’autre qu’une énorme forteresse. Les douves, agrémentées de gazon, protègent le bâtiment d’éventuels jets de pierre. Les grilles qui fermes les divers accès sont renforcées et sont prévus pour résister aux assaut de camion qui serraient utilisés comme béliers. Ne parlons même pas des dispositifs électronique, qui en font un bâtiment inviolable. Tel des passages secrets, un vaste héliport qui surplombe l’édifice ainsi qu’un embarcadère sont prévus pour assurer la fuite du personnel. A quand, les mâchicoulis, et autres oubliettes afin d’en faire le parfait château fort du 21e siècle. \nClayou', 'ileDeLaCite.jpg');
INSERT INTO `anecdotes` (`ID`, `TITRE`, `TEXTE`, `URLIMAGE`) VALUES (7, 'Piétinons la Bastille !', 'Impossible de relier le faubourg Saint-Germain de celui de Saint-Honoré (et vis versa) sous l’ancien régime ! Après la révolution, on décide enfin d’y remédier ! Le pont changera plusieurs fois de nom en même temps que la place avant d’adopter définitivement celui de : « Pont de la Concorde ». Sa construction entraîna l’utilisation de pierre provenant de la Bastille, pour « que le peuple pût continuellement fouler aux pieds l’antique forteresse ».\nOn peut penser que ces pierres sont sacrées vue que son accès est souvent interdit lors de manifestation vue sont emplacement. A quand la prise du pont de la concorde !  \nClayou', 'ileDeLaCite.jpg');
INSERT INTO `anecdotes` (`ID`, `TITRE`, `TEXTE`, `URLIMAGE`) VALUES (8, 'Pas très catholique...', 'La cathédrale qui tel un être vivant se dresse sur l’île de la Cité a été voulue dans un genre « gothique précoce » par l’évêque Maurice de Sully. Les travaux débutèrent vers 1160. Suite à la révolution l’église Notre-Dame fut transformée en temple, où l’on honora le déesse Raison… L’ex-cathédrale faillit être mis en vente, ce qui eût sans doute entraîné sa destruction ! Rendue au culte constitutionnel et purifiée ( !) en 1795, elle rentra dans la famille catholique romaine en 1802. \nClayou', 'ileDeLaCite.jpg');
INSERT INTO `anecdotes` (`ID`, `TITRE`, `TEXTE`, `URLIMAGE`) VALUES (9, 'D\'enfer !', 'La station de RER est non seulement la plus ancienne gare de la capitale (1846), mais encore la plus originale, avec un plan en hémicycle dont on comprend mal, aujourd’hui, la raison d’être. A l’origine cette gare était au terminus de la ligne de Sceaux et pour éviter d’installer des plaques tournantes, un ingénieur avait imaginé des trains articulés pouvant effectuer un virage sur un très court rayon. Une boucle, autour de laquelle fut bâti l’édifice, faisait que la voie d’arrivée était aussi celle du départ. Ce système ce révélant fragile fut supprimé en 1895 lors du prolongement de la ligne jusqu’au jardin du Luxembourg.\nClayou', 'ileDeLaCite.jpg');
INSERT INTO `anecdotes` (`ID`, `TITRE`, `TEXTE`, `URLIMAGE`) VALUES (10, 'L\'abbé con fesse.', 'Les champs, la plus belle avenue du monde, n’étaient en 1670 qu’une trouée dans les broussailles du jardin des tuileries jusqu\'à l’actuel rond-point des Champs-Elysées. En 1770, elle fut prolongée jusqu’au pont de Neuilly. Malgré l’environnement luxueux, cette avenue fut peu et mal fréquentée jusqu\'à la révolution. En 1788, un garde interpella un ecclésiastique et nota dans son rapport : « Arrêté, vers les huit heures du soir, un abbé avec une négresse, qui disait être son confesseur qui s’instruisait. Relaxés avec injonction à M. l’abbé de ne pas récidiver à confesser ses pénitentes sous les arbres nuitamment. »\n« Priez pour nous pauvres pêcheur ! »\nClayou', 'ileDeLaCite.jpg');
INSERT INTO `anecdotes` (`ID`, `TITRE`, `TEXTE`, `URLIMAGE`) VALUES (11, 'Triste utilisation', 'La colonne de juillet a servi des la fin de sa construction (1840) à un candidat au suicide. Etrange utilisation pour un mémorial des victimes des trois glorieuse (27,28 et 29 juillet) élevé « à la gloire des citoyen qui s’armèrent et combattirent pour la défense des libertés publiques ». L’escalier à vis est maintenant inaccessible au public car on ne sait pas ce que cette colonne de 52 mètres de haut peut encore comploter.\nClayou', 'ileDeLaCite.jpg');
INSERT INTO `anecdotes` (`ID`, `TITRE`, `TEXTE`, `URLIMAGE`) VALUES (12, 'Un suppo et au lit !', 'A l’annonce de la sélection du projet de M Eiffel, 300 personnalités adressèrent au ministre une pétition : « … La ville de Paris va-t-elle s’associer plus longtemps aux baroques, aux mercantiles imaginations d’un constructeur de machines pour s’enlaidir irréparablement et se déshonorer ? … » Parmi les signataires, Alexandre Dumas, Charles Gounod, Guy de Maupassant… D’autres ajoutèrent des appréciation de leur cru : « volière horrible, lampadaire tragique, chandelier creux » et même « suppositoire solitaire ».\nClayou', 'ileDeLaCite.jpg');
INSERT INTO `anecdotes` (`ID`, `TITRE`, `TEXTE`, `URLIMAGE`) VALUES (13, 'Un appartement dans les nuages', 'Malgré ses détracteur, Gustave Eiffel continua à tricoter le fer de sa tour. Une fois celle-ci achevée, le grand homme dont la taille ne dépassait pas 1.64 mètre multiplia les invitations à dîner dans les appartements qu’il s’était fait aménager au dessus du troisième étage. On surpris même Gounod, une nuit à improviser sur le piano d’Eiffel une mélodie accompagnant des vers de Musset.\nClayou', 'ileDeLaCite.jpg');

# --------------------------------------------------------

#
# Structure de la table `jointureLieux`
#

DROP TABLE IF EXISTS `jointureLieux`;
CREATE TABLE `jointureLieux` (
  `IDANECDOTE` tinyint(4) NOT NULL default '0',
  `IDLIEU` tinyint(4) NOT NULL default '0'
) TYPE=MyISAM PACK_KEYS=1;

#
# Contenu de la table `jointureLieux`
#

INSERT INTO `jointureLieux` (`IDANECDOTE`, `IDLIEU`) VALUES (1, 1);
INSERT INTO `jointureLieux` (`IDANECDOTE`, `IDLIEU`) VALUES (2, 1);
INSERT INTO `jointureLieux` (`IDANECDOTE`, `IDLIEU`) VALUES (3, 3);
INSERT INTO `jointureLieux` (`IDANECDOTE`, `IDLIEU`) VALUES (4, 2);
INSERT INTO `jointureLieux` (`IDANECDOTE`, `IDLIEU`) VALUES (5, 4);
INSERT INTO `jointureLieux` (`IDANECDOTE`, `IDLIEU`) VALUES (6, 8);
INSERT INTO `jointureLieux` (`IDANECDOTE`, `IDLIEU`) VALUES (7, 6);
INSERT INTO `jointureLieux` (`IDANECDOTE`, `IDLIEU`) VALUES (8, 11);
INSERT INTO `jointureLieux` (`IDANECDOTE`, `IDLIEU`) VALUES (9, 9);
INSERT INTO `jointureLieux` (`IDANECDOTE`, `IDLIEU`) VALUES (10, 10);
INSERT INTO `jointureLieux` (`IDANECDOTE`, `IDLIEU`) VALUES (11, 5);
INSERT INTO `jointureLieux` (`IDANECDOTE`, `IDLIEU`) VALUES (12, 7);
INSERT INTO `jointureLieux` (`IDANECDOTE`, `IDLIEU`) VALUES (13, 7);

# --------------------------------------------------------

#
# Structure de la table `jointureThemes`
#

DROP TABLE IF EXISTS `jointureThemes`;
CREATE TABLE `jointureThemes` (
  `IDANECDOTE` tinyint(4) NOT NULL default '0',
  `IDTHEME` tinyint(4) NOT NULL default '0'
) TYPE=MyISAM PACK_KEYS=1;

#
# Contenu de la table `jointureThemes`
#

INSERT INTO `jointureThemes` (`IDANECDOTE`, `IDTHEME`) VALUES (1, 1);
INSERT INTO `jointureThemes` (`IDANECDOTE`, `IDTHEME`) VALUES (2, 1);
INSERT INTO `jointureThemes` (`IDANECDOTE`, `IDTHEME`) VALUES (3, 2);
INSERT INTO `jointureThemes` (`IDANECDOTE`, `IDTHEME`) VALUES (4, 1);
INSERT INTO `jointureThemes` (`IDANECDOTE`, `IDTHEME`) VALUES (5, 1);
INSERT INTO `jointureThemes` (`IDANECDOTE`, `IDTHEME`) VALUES (6, 3);
INSERT INTO `jointureThemes` (`IDANECDOTE`, `IDTHEME`) VALUES (7, 3);
INSERT INTO `jointureThemes` (`IDANECDOTE`, `IDTHEME`) VALUES (8, 3);
INSERT INTO `jointureThemes` (`IDANECDOTE`, `IDTHEME`) VALUES (9, 3);
INSERT INTO `jointureThemes` (`IDANECDOTE`, `IDTHEME`) VALUES (10, 3);
INSERT INTO `jointureThemes` (`IDANECDOTE`, `IDTHEME`) VALUES (11, 3);
INSERT INTO `jointureThemes` (`IDANECDOTE`, `IDTHEME`) VALUES (7, 4);
INSERT INTO `jointureThemes` (`IDANECDOTE`, `IDTHEME`) VALUES (8, 5);
INSERT INTO `jointureThemes` (`IDANECDOTE`, `IDTHEME`) VALUES (7, 5);
INSERT INTO `jointureThemes` (`IDANECDOTE`, `IDTHEME`) VALUES (11, 5);
INSERT INTO `jointureThemes` (`IDANECDOTE`, `IDTHEME`) VALUES (11, 1);
INSERT INTO `jointureThemes` (`IDANECDOTE`, `IDTHEME`) VALUES (12, 3);
INSERT INTO `jointureThemes` (`IDANECDOTE`, `IDTHEME`) VALUES (13, 3);

# --------------------------------------------------------

#
# Structure de la table `lieux`
#

DROP TABLE IF EXISTS `lieux`;
CREATE TABLE `lieux` (
  `ID` tinyint(4) NOT NULL auto_increment,
  `TITRE` tinytext NOT NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `ID_2` (`ID`),
  KEY `ID` (`ID`)
) TYPE=MyISAM PACK_KEYS=1 AUTO_INCREMENT=12 ;

#
# Contenu de la table `lieux`
#

INSERT INTO `lieux` (`ID`, `TITRE`) VALUES (1, 'Cimetière des Innocents');
INSERT INTO `lieux` (`ID`, `TITRE`) VALUES (2, 'Louvre');
INSERT INTO `lieux` (`ID`, `TITRE`) VALUES (3, 'Opéra Garnier');
INSERT INTO `lieux` (`ID`, `TITRE`) VALUES (4, 'Hotel de ville');
INSERT INTO `lieux` (`ID`, `TITRE`) VALUES (5, 'Bastille');
INSERT INTO `lieux` (`ID`, `TITRE`) VALUES (6, 'Concorde');
INSERT INTO `lieux` (`ID`, `TITRE`) VALUES (7, 'Tour Eiffel');
INSERT INTO `lieux` (`ID`, `TITRE`) VALUES (8, 'Bercy');
INSERT INTO `lieux` (`ID`, `TITRE`) VALUES (9, 'Denfert Rochereau');
INSERT INTO `lieux` (`ID`, `TITRE`) VALUES (10, 'Champs-Elysées');
INSERT INTO `lieux` (`ID`, `TITRE`) VALUES (11, 'Notre-Dame');

# --------------------------------------------------------

#
# Structure de la table `themes`
#

DROP TABLE IF EXISTS `themes`;
CREATE TABLE `themes` (
  `ID` tinyint(4) NOT NULL auto_increment,
  `TITRE` tinytext NOT NULL,
  PRIMARY KEY  (`ID`)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

#
# Contenu de la table `themes`
#

INSERT INTO `themes` (`ID`, `TITRE`) VALUES (1, 'La mort');
INSERT INTO `themes` (`ID`, `TITRE`) VALUES (2, 'Les expressions');
INSERT INTO `themes` (`ID`, `TITRE`) VALUES (3, 'Monuments');
INSERT INTO `themes` (`ID`, `TITRE`) VALUES (4, 'Ponts');
INSERT INTO `themes` (`ID`, `TITRE`) VALUES (5, 'Révolutions');
