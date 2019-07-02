-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
-- CXUideas
--
-- Host: localhost
-- Generation Time: Jun 09, 2015 at 05:12 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `converts`
--

CREATE TABLE IF NOT EXISTS `converts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` varchar(2500) NOT NULL,
  `path` varchar(2500) NOT NULL,
  `ext` char(5) DEFAULT NULL,
  `res` char(100) DEFAULT NULL,
  `source` int(11) DEFAULT '0',
  `video_bitrate` int(11) DEFAULT '0',
  `audio_bitrate` int(11) DEFAULT '0',
  `sr` int(11) DEFAULT '0',
  `enc` varchar(1200) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `credentials`
--

CREATE TABLE IF NOT EXISTS `credentials` (
  `uname` varchar(2400) NOT NULL,
  `pwd` varchar(2400) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `credentials`
--

INSERT INTO `credentials` (`uname`, `pwd`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(2400) NOT NULL,
  `wm` int(11) NOT NULL DEFAULT '0',
  `name` varchar(2400) DEFAULT NULL,
  `con` int(11) NOT NULL DEFAULT '0',
  `wm_image` varchar(1200) DEFAULT '0',
  `final_path` varchar(1200) DEFAULT NULL,
  `prev_status` int(11) DEFAULT '0',
  `video2mp3` int(11) DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `previews`
--

CREATE TABLE IF NOT EXISTS `previews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `path` varchar(2400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
