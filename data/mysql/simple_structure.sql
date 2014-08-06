-- phpMyAdmin SQL Dump
-- version 4.1.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 06, 2014 at 02:50 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `notizblogg`
--

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE IF NOT EXISTS `author` (
  `authorID` int(11) NOT NULL AUTO_INCREMENT,
  `authorName` varchar(155) NOT NULL,
  PRIMARY KEY (`authorID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=122 ;

-- --------------------------------------------------------

--
-- Table structure for table `bibField`
--

CREATE TABLE IF NOT EXISTS `bibField` (
  `bibFieldID` int(11) NOT NULL AUTO_INCREMENT,
  `bibFieldName` varchar(20) NOT NULL,
  PRIMARY KEY (`bibFieldID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `bibTyp`
--

CREATE TABLE IF NOT EXISTS `bibTyp` (
  `bibTypID` int(11) NOT NULL AUTO_INCREMENT,
  `bibTypName` varchar(20) NOT NULL,
  PRIMARY KEY (`bibTypID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `label`
--

CREATE TABLE IF NOT EXISTS `label` (
  `labelID` int(11) NOT NULL AUTO_INCREMENT,
  `labelName` varchar(33) NOT NULL,
  PRIMARY KEY (`labelID`),
  UNIQUE KEY `tagName` (`labelName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=666 ;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `locationID` int(11) NOT NULL AUTO_INCREMENT,
  `locationName` varchar(155) NOT NULL,
  PRIMARY KEY (`locationID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `mediaID` int(11) NOT NULL AUTO_INCREMENT,
  `mediaType` varchar(11) NOT NULL,
  `mediaFile` varchar(55) NOT NULL,
  PRIMARY KEY (`mediaID`),
  UNIQUE KEY `noteID` (`mediaType`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=367 ;

-- --------------------------------------------------------

--
-- Table structure for table `note`
--

CREATE TABLE IF NOT EXISTS `note` (
  `noteID` int(11) NOT NULL AUTO_INCREMENT,
  `noteTitle` varchar(255) NOT NULL,
  `noteComment` text NOT NULL,
  `sourceExtern` varchar(255) NOT NULL,
  `sourceID` int(11) NOT NULL,
  `bibTyp` int(11) DEFAULT NULL,
  `pageStart` int(11) NOT NULL,
  `pageEnd` int(11) NOT NULL,
  `noteMedia` varchar(55) NOT NULL,
  `notePublic` int(11) NOT NULL,
  `dateCreate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userID` int(11) NOT NULL,
  `checkID` varchar(33) DEFAULT NULL,
  PRIMARY KEY (`noteID`),
  UNIQUE KEY `checkID` (`checkID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1362 ;

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
-- Table structure for table `rel_source_author`
--

CREATE TABLE IF NOT EXISTS `rel_source_author` (
  `authorID` int(11) NOT NULL,
  `sourceID` int(11) NOT NULL,
  PRIMARY KEY (`authorID`,`sourceID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rel_source_location`
--

CREATE TABLE IF NOT EXISTS `rel_source_location` (
  `locationID` int(11) NOT NULL,
  `sourceID` int(11) NOT NULL,
  PRIMARY KEY (`locationID`,`sourceID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `source`
--

CREATE TABLE IF NOT EXISTS `source` (
  `sourceID` int(11) NOT NULL AUTO_INCREMENT,
  `sourceName` varchar(255) NOT NULL,
  `sourceSubtitle` varchar(255) NOT NULL,
  `sourceYear` year(4) NOT NULL,
  `sourceTyp` int(11) NOT NULL,
  `sourceEditor` int(11) NOT NULL,
  `noteID` int(11) NOT NULL,
  PRIMARY KEY (`sourceID`),
  UNIQUE KEY `sourceRef` (`sourceName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=302 ;

-- --------------------------------------------------------

--
-- Table structure for table `sourceDetail`
--

CREATE TABLE IF NOT EXISTS `sourceDetail` (
  `sourceDetailID` int(11) NOT NULL AUTO_INCREMENT,
  `sourceID` int(11) NOT NULL,
  `bibFieldID` int(11) NOT NULL,
  `sourceDetailName` varchar(255) NOT NULL,
  PRIMARY KEY (`sourceDetailID`),
  UNIQUE KEY `sourceID` (`sourceID`,`bibFieldID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=534 ;

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
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
