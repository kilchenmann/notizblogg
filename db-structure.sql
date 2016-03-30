-- phpMyAdmin SQL Dump
-- version 4.4.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 17, 2015 at 08:10 AM
-- Server version: 5.6.26
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `notizblogg`
--

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bibTyp`
--

CREATE TABLE IF NOT EXISTS `bibTyp` (
  `bibTypID` int(11) NOT NULL,
  `bibTyp` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  MODIFY `bibFieldID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bibTyp`
--
ALTER TABLE `bibTyp`
  MODIFY `bibTypID` int(11) NOT NULL AUTO_INCREMENT;
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
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
