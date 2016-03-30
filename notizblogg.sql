-- phpMyAdmin SQL Dump
-- version 4.4.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 30, 2016 at 11:23 PM
-- Server version: 5.6.26
-- PHP Version: 5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `notizblogg`
--
CREATE DATABASE IF NOT EXISTS `notizblogg` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `notizblogg`;
-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE IF NOT EXISTS `author` (
  `authorID` int(11) NOT NULL,
  `author` varchar(155) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bib`
--

CREATE TABLE IF NOT EXISTS `bib` (
  `bibID` int(11) NOT NULL,
  `bib` varchar(66) NOT NULL,
  `bibEditor` int(2) NOT NULL,
  `bibTyp` int(11) NOT NULL,
  `noteID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bibDetail`
--

CREATE TABLE IF NOT EXISTS `bibDetail` (
  `bibDetailID` int(11) NOT NULL,
  `bibID` int(11) NOT NULL,
  `bibFieldID` int(11) NOT NULL,
  `bibDetail` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bibField`
--

CREATE TABLE IF NOT EXISTS `bibField` (
  `bibFieldID` int(11) NOT NULL,
  `bibField` varchar(20) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bibField`
--

INSERT INTO `bibField` (`bibFieldID`, `bibField`) VALUES
(1, 'date'),
(2, 'day'),
(3, 'doi'),
(4, 'edition'),
(5, 'eventtitle'),
(6, 'institution'),
(7, 'journalsubtitle'),
(8, 'journaltitle'),
(9, 'month'),
(10, 'number'),
(11, 'organization'),
(12, 'pages'),
(13, 'publisher'),
(14, 'series'),
(15, 'url'),
(16, 'urldate'),
(17, 'venue'),
(18, 'version'),
(19, 'volume'),
(20, 'volumes'),
(21, 'maintitle'),
(22, 'mainsubtitle'),
(23, 'type'),
(24, 'crossref'),
(25, 'year');

-- --------------------------------------------------------

--
-- Table structure for table `bibTyp`
--

CREATE TABLE IF NOT EXISTS `bibTyp` (
  `bibTypID` int(11) NOT NULL,
  `bibTyp` varchar(20) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bibTyp`
--

INSERT INTO `bibTyp` (`bibTypID`, `bibTyp`) VALUES
(1, 'article'),
(2, 'book'),
(3, 'inbook'),
(4, 'booklet'),
(5, 'collection'),
(6, 'incollection'),
(7, 'manual'),
(8, 'misc'),
(9, 'online'),
(10, 'periodical'),
(11, 'proceedings'),
(12, 'inproceedings'),
(13, 'report'),
(14, 'thesis'),
(15, 'unpublished'),
(16, 'project');

-- --------------------------------------------------------

--
-- Table structure for table `label`
--

CREATE TABLE IF NOT EXISTS `label` (
  `labelID` int(11) NOT NULL,
  `label` varchar(33) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `locationID` int(11) NOT NULL,
  `location` varchar(155) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `note`
--

CREATE TABLE IF NOT EXISTS `note` (
  `noteID` int(11) NOT NULL,
  `noteTitle` varchar(255) DEFAULT NULL,
  `noteSubtitle` varchar(255) DEFAULT NULL,
  `noteComment` text NOT NULL,
  `noteLink` varchar(255) DEFAULT NULL,
  `bibID` int(11) DEFAULT NULL,
  `bibTyp` int(11) DEFAULT NULL,
  `pageStart` int(11) DEFAULT '0',
  `pageEnd` int(11) DEFAULT '0',
  `noteMedia` varchar(55) DEFAULT NULL,
  `notePublic` tinyint(4) DEFAULT '0',
  `dateYear` int(4) DEFAULT '0',
  `dateCreated` datetime DEFAULT NULL,
  `dateModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userID` int(11) DEFAULT '1',
  `checkID` varchar(33) DEFAULT NULL,
  `noteCategory` int(11) DEFAULT '0',
  `noteProject` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rel_bib_author`
--

CREATE TABLE IF NOT EXISTS `rel_bib_author` (
  `authorID` int(11) NOT NULL,
  `bibID` int(11) NOT NULL,
  `pos` int(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rel_bib_location`
--

CREATE TABLE IF NOT EXISTS `rel_bib_location` (
  `locationID` int(11) NOT NULL,
  `bibID` int(11) NOT NULL,
  `pos` int(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rel_note_label`
--

CREATE TABLE IF NOT EXISTS `rel_note_label` (
  `labelID` int(11) NOT NULL,
  `noteID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userID` int(2) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `user` varchar(80) NOT NULL,
  `pass` char(32) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(33) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `name`, `user`, `pass`, `email`, `token`) VALUES
(1, 'Niklas Luhmann', 'luhmann', 'd4eb347b663f58289b3a7e2bab53d116', '', '79DBD2E711C27E7B');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`authorID`);

--
-- Indexes for table `bib`
--
ALTER TABLE `bib`
  ADD PRIMARY KEY (`bibID`),
  ADD UNIQUE KEY `noteID` (`noteID`),
  ADD UNIQUE KEY `bibName` (`bib`);

--
-- Indexes for table `bibDetail`
--
ALTER TABLE `bibDetail`
  ADD PRIMARY KEY (`bibDetailID`),
  ADD UNIQUE KEY `sourceID` (`bibID`,`bibFieldID`);

--
-- Indexes for table `bibField`
--
ALTER TABLE `bibField`
  ADD PRIMARY KEY (`bibFieldID`),
  ADD UNIQUE KEY `bibFieldVal` (`bibField`);

--
-- Indexes for table `bibTyp`
--
ALTER TABLE `bibTyp`
  ADD PRIMARY KEY (`bibTypID`),
  ADD UNIQUE KEY `bibTypVal` (`bibTyp`);

--
-- Indexes for table `label`
--
ALTER TABLE `label`
  ADD PRIMARY KEY (`labelID`),
  ADD UNIQUE KEY `tagName` (`label`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`locationID`),
  ADD UNIQUE KEY `locationVal` (`location`);

--
-- Indexes for table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`noteID`),
  ADD UNIQUE KEY `checkID` (`checkID`),
  ADD FULLTEXT KEY `noteComment` (`noteComment`,`noteTitle`);

--
-- Indexes for table `rel_bib_author`
--
ALTER TABLE `rel_bib_author`
  ADD PRIMARY KEY (`authorID`,`bibID`);

--
-- Indexes for table `rel_bib_location`
--
ALTER TABLE `rel_bib_location`
  ADD PRIMARY KEY (`locationID`,`bibID`);

--
-- Indexes for table `rel_note_label`
--
ALTER TABLE `rel_note_label`
  ADD PRIMARY KEY (`labelID`,`noteID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`user`),
  ADD UNIQUE KEY `token` (`token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
  MODIFY `authorID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bib`
--
ALTER TABLE `bib`
  MODIFY `bibID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bibDetail`
--
ALTER TABLE `bibDetail`
  MODIFY `bibDetailID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bibField`
--
ALTER TABLE `bibField`
  MODIFY `bibFieldID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `bibTyp`
--
ALTER TABLE `bibTyp`
  MODIFY `bibTypID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `label`
--
ALTER TABLE `label`
  MODIFY `labelID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `locationID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `note`
--
ALTER TABLE `note`
  MODIFY `noteID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(2) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
