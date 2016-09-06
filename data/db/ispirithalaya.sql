-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 26, 2012 at 11:21 AM
-- Server version: 5.1.63
-- PHP Version: 5.3.2-1ubuntu4.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ispirithalaya`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_user`
--

CREATE TABLE IF NOT EXISTS `admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `create_by` int(11) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `change_by` int(11) NOT NULL,
  `change_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user_id` (`create_by`,`change_by`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `chat_from` text NOT NULL,
  `chat_to` text NOT NULL,
  `message` text NOT NULL,
  `sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `recd` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `checking`
--

CREATE TABLE IF NOT EXISTS `checking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `payment` int(11) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `create_by` int(11) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `change_by` int(11) NOT NULL,
  `change_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user_id` (`create_by`,`change_by`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `checking_patient_map`
--

CREATE TABLE IF NOT EXISTS `checking_patient_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `checking_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `comment` varchar(500) DEFAULT NULL,
  `create_by` int(11) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `change_by` int(11) NOT NULL,
  `change_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user_id` (`patient_id`,`create_by`,`change_by`),
  KEY `fk_checking_id` (`checking_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `checking_time`
--

CREATE TABLE IF NOT EXISTS `checking_time` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `checking_id` int(11) NOT NULL,
  `day` varchar(20) NOT NULL,
  `from_time` time NOT NULL,
  `to_time` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_checking_id` (`checking_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `checking_user`
--

CREATE TABLE IF NOT EXISTS `checking_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `create_by` int(11) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `change_by` int(11) NOT NULL,
  `change_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user_id` (`create_by`,`change_by`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE IF NOT EXISTS `doctor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `degree` varchar(100) DEFAULT NULL,
  `payment` float DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `create_by` int(11) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `change_by` int(11) NOT NULL,
  `change_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_patient_map`
--

CREATE TABLE IF NOT EXISTS `doctor_patient_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `channel_date` date NOT NULL,
  `comment` varchar(500) DEFAULT NULL,
  `create_by` int(11) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `change_by` int(11) NOT NULL,
  `change_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `checking_patient_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_doctor_id` (`doctor_id`),
  KEY `fk_user_id` (`patient_id`,`doctor_id`,`create_by`,`change_by`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_spe_map`
--

CREATE TABLE IF NOT EXISTS `doctor_spe_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doctor_id` int(11) NOT NULL,
  `specialist_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_doctor_id` (`doctor_id`),
  KEY `fk_special_id` (`specialist_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_time`
--

CREATE TABLE IF NOT EXISTS `doctor_time` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doctor_id` int(11) NOT NULL,
  `day` enum('sunday','monday','tuesday','wednesday','thuesday','friday','saturday') NOT NULL,
  `from_time` time NOT NULL,
  `to_time` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `labtest`
--

CREATE TABLE IF NOT EXISTS `labtest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `payment` int(11) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `create_by` int(11) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `change_by` int(11) NOT NULL,
  `change_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user_id` (`change_by`,`create_by`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `labtest_patient_map`
--

CREATE TABLE IF NOT EXISTS `labtest_patient_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lab_test_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `comment` varchar(500) DEFAULT NULL,
  `create_by` int(11) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `change_by` int(11) NOT NULL,
  `change_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_labtest_id` (`lab_test_id`),
  KEY `fk_user_id` (`patient_id`,`create_by`,`change_by`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `labtest_time`
--

CREATE TABLE IF NOT EXISTS `labtest_time` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `labtest_id` int(11) NOT NULL,
  `day` enum('sunday','monday','tuesday','wednesday','thuesday','friday','saturday') NOT NULL,
  `from_time` time NOT NULL,
  `to_time` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_labtest_id` (`labtest_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `labtest_user`
--

CREATE TABLE IF NOT EXISTS `labtest_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `creat_by` int(11) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `change_by` int(11) NOT NULL,
  `change_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user_id` (`creat_by`,`user_id`,`change_by`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE IF NOT EXISTS `patient` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `note` text,
  `date` datetime DEFAULT NULL,
  `change_by` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `id_3` (`id`),
  KEY `id_2` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE IF NOT EXISTS `reservation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `from_time` datetime NOT NULL,
  `to_time` datetime NOT NULL,
  `description` text,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `create_by` int(11) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `change_by` int(11) NOT NULL,
  `change_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_room_id` (`room_id`),
  KEY `fk_user_id` (`patient_id`,`create_by`,`change_by`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reservation_user`
--

CREATE TABLE IF NOT EXISTS `reservation_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `create_by` int(11) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `change_by` int(11) NOT NULL,
  `change_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user_id` (`create_by`,`change_by`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE IF NOT EXISTS `room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `payment` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `create_by` int(11) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `change_by` int(11) NOT NULL,
  `change_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user_id` (`create_by`,`change_by`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `specialties`
--

CREATE TABLE IF NOT EXISTS `specialties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `title` varchar(5) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `initials` varchar(20) NOT NULL,
  `bday` date DEFAULT NULL,
  `tel_no` int(10) DEFAULT NULL,
  `address1` varchar(100) DEFAULT NULL,
  `address2` varchar(100) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `role_id` tinyint(4) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_by` int(11) NOT NULL,
  `create_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `change_by` int(11) NOT NULL,
  `change_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `online_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_id` (`create_by`,`change_by`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;


INSERT INTO `role` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'doctor'),
(3, 'lbuser'),
(4, 'chuser'),
(5, 'rsuser'),
(6, 'nmuser'),
(7, 'patient');

--
-- Dumping data for table `specialties`
--

INSERT INTO `specialties` (`id`, `title`, `description`) VALUES
(1, 'Acupuncture ', 'Uses procedures adapted from Chinese medicine to relieve pain or for therapeutic purposes.'),
(2, 'Addiction Medicine ', 'Works with patients who have substance abuse disorders. Specializes in prevention, diagnosis, treatment of withdrawal, medical or psychiatric complications, relapse, and the monitoring of recovery.'),
(3, 'Adolescent Medicine ', 'Focused on the care of people in the second decade of life. Problems in adolescence include sexual experiences resulting in pregnancies and sexually transmitted diseases, drug and alcohol use, violence-related behaviors, and reckless use of motor vehicles.'),
(4, 'Aerospace Medicine ', 'Focused on the health of the crew, passengers, and support personnel of air and space vehicles.'),
(5, 'Allergy ', 'Allergists diagnose and treat reactions due to irritating agents or allergens including foods, medicines, and pollens.'),
(6, 'Allergy/Immunology ', 'Concerned with the evaluation, diagnosis, and management of disorders involving the immune system, including asthma, eczema, allergic reactions, problems related to autoimmune disease, organ transplantation, and immune system malignancies.'),
(7, 'Anesthesiology ', 'Provides relief from pain and maintains or restores a stable condition during surgical, obstetric, or diagnostic procedures.');

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `title`, `first_name`, `last_name`, `initials`, `bday`, `tel_no`, `address1`, `address2`, `city`, `country`, `image`, `role_id`, `status`, `create_by`, `create_on`, `change_by`, `change_on`, `online_status`) VALUES
(1, 'admin@ispirithalaya.com', '0192023a7bbd73250516f069df18b500', 'Mr.', 'Admin', 'User', 'I', '0000-00-00', 0, '', '', '', 'Sri Lanka', NULL, 1, 1, 0, '2012-06-26 11:23:47', 0, '2012-06-26 11:24:16', 1);
