-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 01 oct. 2022 à 15:16
-- Version du serveur : 5.7.36
-- Version de PHP : 8.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `forum`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `idArt` int(11) NOT NULL AUTO_INCREMENT,
  `titreArt` varchar(60) NOT NULL,
  `dateArt` date NOT NULL,
  `contenuArt` varchar(100) NOT NULL,
  `idMemb` varchar(50) NOT NULL,
  `idRub` int(11) NOT NULL,
  PRIMARY KEY (`idArt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `idCat` int(11) NOT NULL AUTO_INCREMENT,
  `iconCat` varchar(100) NOT NULL,
  `nomCat` varchar(30) NOT NULL,
  PRIMARY KEY (`idCat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

DROP TABLE IF EXISTS `membre`;
CREATE TABLE IF NOT EXISTS `membre` (
  `idMemb` varchar(50) NOT NULL,
  `pseudoMemb` text NOT NULL,
  `mdpMemb` varchar(100) NOT NULL,
  `typeMemb` int(11) NOT NULL,
  `certifMemb` int(11) NOT NULL,
  `dateIns` date NOT NULL,
  PRIMARY KEY (`idMemb`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `reponse`
--

DROP TABLE IF EXISTS `reponse`;
CREATE TABLE IF NOT EXISTS `reponse` (
  `idRep` int(11) NOT NULL AUTO_INCREMENT,
  `idMemb` varchar(50) NOT NULL,
  `idArt` int(1) NOT NULL,
  `dateRep` date NOT NULL,
  `contenuRep` varchar(1024) NOT NULL,
  PRIMARY KEY (`idRep`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `rubrique`
--

DROP TABLE IF EXISTS `rubrique`;
CREATE TABLE IF NOT EXISTS `rubrique` (
  `idRub` int(11) NOT NULL AUTO_INCREMENT,
  `nomRub` varchar(100) NOT NULL,
  `descRub` varchar(300) NOT NULL,
  `idCat` int(11) NOT NULL,
  PRIMARY KEY (`idRub`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
