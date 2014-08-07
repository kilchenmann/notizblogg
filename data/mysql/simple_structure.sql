-- phpMyAdmin SQL Dump
-- version 4.0.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 07, 2014 at 08:41 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nb`
--
-- CREATE DATABASE IF NOT EXISTS `nb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
-- USE `nb`;

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE IF NOT EXISTS `author` (
  `authorID` int(11) NOT NULL AUTO_INCREMENT,
  `authorVal` varchar(155) NOT NULL,
  PRIMARY KEY (`authorID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bibDetail`
--

CREATE TABLE IF NOT EXISTS `bibDetail` (
  `bibDetailID` int(11) NOT NULL AUTO_INCREMENT,
  `bibName` varchar(44) NOT NULL,
  `noteID` int(11) NOT NULL,
  `bibEditor` int(2) NOT NULL,
  `bibFieldID` int(11) NOT NULL,
  `bibDetailVal` varchar(255) NOT NULL,
  PRIMARY KEY (`bibDetailID`),
  UNIQUE KEY `sourceID` (`noteID`,`bibFieldID`),
  UNIQUE KEY `bibName` (`bibName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bibField`
--

CREATE TABLE IF NOT EXISTS `bibField` (
  `bibFieldID` int(11) NOT NULL AUTO_INCREMENT,
  `bibFieldVal` varchar(20) NOT NULL,
  PRIMARY KEY (`bibFieldID`),
  UNIQUE KEY `bibFieldVal` (`bibFieldVal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `bibField`
--

INSERT INTO `bibField` (`bibFieldID`, `bibFieldVal`) VALUES
(24, 'crossref'),
(1, 'date'),
(2, 'day'),
(3, 'doi'),
(4, 'edition'),
(5, 'eventtitle'),
(6, 'institution'),
(7, 'journalsubtitle'),
(8, 'journaltitle'),
(22, 'mainsubtitle'),
(21, 'maintitle'),
(9, 'month'),
(10, 'number'),
(11, 'organization'),
(12, 'pages'),
(13, 'publisher'),
(14, 'series'),
(23, 'type'),
(15, 'url'),
(16, 'urldate'),
(17, 'venue'),
(18, 'version'),
(19, 'volume'),
(20, 'volumes'),
(25, 'year');

-- --------------------------------------------------------

--
-- Table structure for table `bibTyp`
--

CREATE TABLE IF NOT EXISTS `bibTyp` (
  `bibTypID` int(11) NOT NULL AUTO_INCREMENT,
  `bibTypVal` varchar(20) NOT NULL,
  PRIMARY KEY (`bibTypID`),
  UNIQUE KEY `bibTypVal` (`bibTypVal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `bibTyp`
--

INSERT INTO `bibTyp` (`bibTypID`, `bibTypVal`) VALUES
(1, 'article'),
(2, 'book'),
(4, 'booklet'),
(5, 'collection'),
(3, 'inbook'),
(6, 'incollection'),
(12, 'inproceedings'),
(7, 'manual'),
(8, 'misc'),
(9, 'online'),
(10, 'periodical'),
(11, 'proceedings'),
(16, 'project'),
(13, 'report'),
(14, 'thesis'),
(15, 'unpublished');

-- --------------------------------------------------------

--
-- Table structure for table `label`
--

CREATE TABLE IF NOT EXISTS `label` (
  `labelID` int(11) NOT NULL AUTO_INCREMENT,
  `labelVal` varchar(33) NOT NULL,
  PRIMARY KEY (`labelID`),
  UNIQUE KEY `tagName` (`labelVal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `locationID` int(11) NOT NULL AUTO_INCREMENT,
  `locationVal` varchar(155) NOT NULL,
  PRIMARY KEY (`locationID`),
  UNIQUE KEY `locationVal` (`locationVal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `note`
--

CREATE TABLE IF NOT EXISTS `note` (
  `noteID` int(11) NOT NULL AUTO_INCREMENT,
  `noteTitle` varchar(255) NOT NULL,
  `noteSubtitle` varchar(255) NOT NULL,
  `noteComment` text NOT NULL,
  `noteLink` varchar(255) NOT NULL,
  `bibID` int(11) NOT NULL,
  `bibTyp` int(11) DEFAULT NULL,
  `pageStart` int(11) NOT NULL,
  `pageEnd` int(11) NOT NULL,
  `noteMedia` varchar(55) NOT NULL,
  `notePublic` int(11) NOT NULL,
  `dateYear` int(4) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userID` int(11) NOT NULL,
  `checkID` varchar(33) DEFAULT NULL,
  PRIMARY KEY (`noteID`),
  UNIQUE KEY `checkID` (`checkID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rel_note_author`
--

CREATE TABLE IF NOT EXISTS `rel_note_author` (
  `authorID` int(11) NOT NULL,
  `noteID` int(11) NOT NULL,
  PRIMARY KEY (`authorID`,`noteID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rel_note_label`
--

CREATE TABLE IF NOT EXISTS `rel_note_label` (
  `labelID` int(11) NOT NULL,
  `noteID` int(11) NOT NULL,
  PRIMARY KEY (`labelID`,`noteID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rel_note_location`
--

CREATE TABLE IF NOT EXISTS `rel_note_location` (
  `locationID` int(11) NOT NULL,
  `noteID` int(11) NOT NULL,
  PRIMARY KEY (`locationID`,`noteID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `username` varchar(80) NOT NULL,
  `pass` char(32) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(33) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `name`, `username`, `pass`, `email`, `token`) VALUES
(1, 'ak', 'andr&eacute;', '24e033b1aea2d53db0e51ea20c4a5afe', '', 'a208aee0dd642c7a28b302f0d84bcab7');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
