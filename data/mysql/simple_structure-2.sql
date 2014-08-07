-- phpMyAdmin SQL Dump
-- version 4.1.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 07, 2014 at 03:54 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nb`
--

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE IF NOT EXISTS `author` (
  `authorID` int(11) NOT NULL AUTO_INCREMENT,
  `authorVal` varchar(155) NOT NULL,
  PRIMARY KEY (`authorID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=122 ;

-- --------------------------------------------------------

--
-- Table structure for table `bib`
--

CREATE TABLE IF NOT EXISTS `bib` (
  `bibID` int(11) NOT NULL AUTO_INCREMENT,
  `bibName` varchar(66) NOT NULL,
  `bibEditor` int(2) NOT NULL,
  `bibTyp` int(11) NOT NULL,
  `noteID` int(11) NOT NULL,
  PRIMARY KEY (`bibID`),
  UNIQUE KEY `noteID` (`noteID`),
  UNIQUE KEY `bibName` (`bibName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=302 ;

-- --------------------------------------------------------

--
-- Table structure for table `bibDetail`
--

CREATE TABLE IF NOT EXISTS `bibDetail` (
  `bibDetailID` int(11) NOT NULL AUTO_INCREMENT,
  `bibID` int(11) NOT NULL,
  `bibFieldID` int(11) NOT NULL,
  `bibDetailVal` varchar(255) NOT NULL,
  PRIMARY KEY (`bibDetailID`),
  UNIQUE KEY `sourceID` (`bibID`,`bibFieldID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=534 ;

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

-- --------------------------------------------------------

--
-- Table structure for table `label`
--

CREATE TABLE IF NOT EXISTS `label` (
  `labelID` int(11) NOT NULL AUTO_INCREMENT,
  `labelVal` varchar(33) NOT NULL,
  PRIMARY KEY (`labelID`),
  UNIQUE KEY `tagName` (`labelVal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=666 ;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `locationID` int(11) NOT NULL AUTO_INCREMENT,
  `locationVal` varchar(155) NOT NULL,
  PRIMARY KEY (`locationID`),
  UNIQUE KEY `locationVal` (`locationVal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Table structure for table `note`
--

CREATE TABLE IF NOT EXISTS `note` (
  `noteID` int(11) NOT NULL AUTO_INCREMENT,
  `noteTitle` varchar(255) NOT NULL,
  `noteSubtitle` varchar(255) DEFAULT NULL,
  `noteComment` text NOT NULL,
  `noteLink` varchar(255) DEFAULT NULL,
  `bibID` int(11) DEFAULT NULL,
  `bibTyp` int(11) DEFAULT NULL,
  `pageStart` int(11) NOT NULL DEFAULT '0',
  `pageEnd` int(11) DEFAULT '0',
  `noteMedia` varchar(55) DEFAULT NULL,
  `notePublic` int(11) NOT NULL DEFAULT '0',
  `dateYear` int(4) DEFAULT '0',
  `dateCreated` datetime NOT NULL,
  `dateModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userID` int(11) NOT NULL DEFAULT '1',
  `checkID` varchar(33) DEFAULT NULL,
  `noteCategory` int(11) NOT NULL,
  `noteProject` int(11) NOT NULL,
  PRIMARY KEY (`noteID`),
  UNIQUE KEY `checkID` (`checkID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1675 ;

-- --------------------------------------------------------

--
-- Table structure for table `rel_bib_author`
--

CREATE TABLE IF NOT EXISTS `rel_bib_author` (
  `authorID` int(11) NOT NULL,
  `bibID` int(11) NOT NULL,
  PRIMARY KEY (`authorID`,`bibID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rel_bib_location`
--

CREATE TABLE IF NOT EXISTS `rel_bib_location` (
  `locationID` int(11) NOT NULL,
  `bibID` int(11) NOT NULL,
  PRIMARY KEY (`locationID`,`bibID`)
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
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userID` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `userName` varchar(80) NOT NULL,
  `pass` char(32) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(33) DEFAULT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`userName`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
