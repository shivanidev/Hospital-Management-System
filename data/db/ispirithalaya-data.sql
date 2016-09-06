-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 26, 2012 at 11:31 AM
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

--
-- Dumping data for table `admin_user`
--


--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `chat_from`, `chat_to`, `message`, `sent`, `recd`) VALUES
(1, 'DinukaThilanga', 'YasasRangika', 'aaaa', '2011-10-15 18:29:52', 1);

--
-- Dumping data for table `checking`
--

INSERT INTO `checking` (`id`, `name`, `payment`, `description`, `status`, `create_by`, `create_on`, `change_by`, `change_on`) VALUES
(1, 'checking 1', 10000, 'ssss', 1, 1, '2011-10-15 07:40:36', 0, '2011-10-15 11:52:36'),
(2, 'checking 2', 20000, 'swwww', 1, 1, '2011-10-15 07:40:13', 0, '2011-10-15 11:53:13');

--
-- Dumping data for table `checking_patient_map`
--

INSERT INTO `checking_patient_map` (`id`, `patient_id`, `checking_id`, `date`, `comment`, `create_by`, `create_on`, `change_by`, `change_on`) VALUES
(1, 5, 2, '2011-10-25 00:00:00', NULL, 5, '2011-10-17 22:10:29', 5, '2011-10-17 22:10:29');

--
-- Dumping data for table `checking_time`
--

INSERT INTO `checking_time` (`id`, `checking_id`, `day`, `from_time`, `to_time`) VALUES
(1, 1, 'sunday', '07:00:00', '10:00:00'),
(2, 2, 'tuesday', '05:00:00', '10:00:00');

--
-- Dumping data for table `checking_user`
--


--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`id`, `user_id`, `degree`, `payment`, `description`, `create_by`, `create_on`, `change_by`, `change_on`) VALUES
(1, 2, 'cs', 10000, 'des', 1, '2011-10-14 19:52:54', 2, '2011-10-17 05:53:47'),
(2, 3, 'cs', 1200, 'dess', 1, '2011-10-14 19:55:09', 1, '2011-10-14 19:55:09');

--
-- Dumping data for table `doctor_patient_map`
--

INSERT INTO `doctor_patient_map` (`id`, `doctor_id`, `patient_id`, `channel_date`, `comment`, `create_by`, `create_on`, `change_by`, `change_on`, `checking_patient_status`) VALUES
(2, 2, 4, '2011-10-24', 'cc', 4, '2011-10-14 20:47:22', 4, '2011-10-14 20:47:22', 0),
(3, 2, 5, '2011-10-24', 'ssw', 5, '2011-10-14 20:50:48', 5, '2011-10-14 20:50:48', 0),
(4, 2, 5, '2011-10-24', 'for chek', 5, '2011-10-17 08:49:34', 5, '2011-10-17 08:49:34', 0),
(5, 2, 1, '2011-10-24', 'admin chanel', 1, '2011-10-18 03:02:33', 1, '2011-10-18 03:02:33', 0);

--
-- Dumping data for table `doctor_spe_map`
--

INSERT INTO `doctor_spe_map` (`id`, `doctor_id`, `specialist_id`) VALUES
(3, 1, 1),
(2, 2, 4);

--
-- Dumping data for table `doctor_time`
--

INSERT INTO `doctor_time` (`id`, `doctor_id`, `day`, `from_time`, `to_time`) VALUES
(3, 1, 'sunday', '05:00:00', '07:00:00'),
(2, 2, 'monday', '14:00:00', '16:00:00'),
(4, 1, 'wednesday', '04:00:00', '11:00:00');

--
-- Dumping data for table `labtest`
--

INSERT INTO `labtest` (`id`, `name`, `payment`, `description`, `status`, `create_by`, `create_on`, `change_by`, `change_on`) VALUES
(1, 'Lab test 1', 750, 'blood red cell count', 1, 1, '2011-10-17 15:10:01', 0, '2011-10-17 09:57:01'),
(2, 'lab test 2', 500, 'blood preser', 1, 1, '2011-10-17 16:10:21', 0, '2011-10-17 10:01:21');

--
-- Dumping data for table `labtest_patient_map`
--

INSERT INTO `labtest_patient_map` (`id`, `lab_test_id`, `patient_id`, `date`, `comment`, `create_by`, `create_on`, `change_by`, `change_on`) VALUES
(1, 1, 5, '2011-10-23', NULL, 5, '2011-10-17 22:10:44', 5, '2011-10-17 22:10:44');

--
-- Dumping data for table `labtest_time`
--

INSERT INTO `labtest_time` (`id`, `labtest_id`, `day`, `from_time`, `to_time`) VALUES
(1, 1, 'sunday', '02:00:00', '17:00:00'),
(2, 2, 'friday', '06:00:00', '13:00:00');

--
-- Dumping data for table `labtest_user`
--


--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `patient_id`, `note`, `date`, `change_by`, `type`, `status`) VALUES
(2, 4, 'huhuhu', '2011-10-17 08:44:03', 2, 1, 0),
(3, 5, 'no one kill', '2011-10-17 08:42:16', 2, 1, 0),
(4, 5, 'HELL GO', '2011-10-17 08:55:38', 2, 1, 0),
(5, 5, 'aa kill me soft', '2011-10-17 10:54:06', 7, 2, 0),
(6, 5, NULL, NULL, NULL, 3, 1),
(7, 1, NULL, NULL, NULL, 1, 1);

--
-- Dumping data for table `reservation`
--


--
-- Dumping data for table `reservation_user`
--


--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'doctor'),
(3, 'lbuser'),
(4, 'chuser'),
(5, 'rsuser'),
(6, 'nmuser'),
(7, 'patient');

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `name`, `quantity`, `payment`, `status`, `create_by`, `create_on`, `change_by`, `change_on`) VALUES
(1, 'Congernce', 2, 4800, 1, 1, '2011-10-17 17:10:00', 0, '2011-10-17 11:40:00'),
(2, 'X-Ray', 4, 2500, 1, 1, '2011-10-17 17:10:40', 0, '2011-10-17 11:40:40');

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
(1, 'admin@ispirithalaya.com', '0192023a7bbd73250516f069df18b500', 'Mr.', 'Admin', 'User', 'I', '0000-00-00', 0, '', '', '', 'Sri Lanka', NULL, 1, 1, 0, '2012-06-26 11:23:47', 0, '2012-06-26 11:24:16', 1),
(2, 'yasasn86@gmail.com', 'e99a18c428cb38d5f260853678922e03', 'Dr', 'Yasas', 'Rangika', 'WM', '2011-10-10', 52352345, 'address', 'address', 'sandalankawa', 'Sri Lanka', NULL, 2, 1, 1, '2011-10-14 19:52:54', 2, '2011-10-17 15:13:19', 0),
(3, 'duleep@gmail.com', 'e99a18c428cb38d5f260853678922e03', 'Dr', 'Duleep', 'Dissanayake', 'DD', '2011-10-11', 345325326, 'add', 'addw', 'avissawella', 'Sri Lanka', NULL, 2, 1, 1, '2011-10-14 19:55:09', 1, '2011-10-15 12:25:02', 0),
(4, 'nuwan@gmail.com', 'e99a18c428cb38d5f260853678922e03', 'Mr.', 'Nuwan_patinet', 'Jayasinghe', 'NN', '2011-10-24', 34235, 'aee', 'aww', 'Pannala', NULL, NULL, 7, 1, 1, '2011-10-14 19:56:24', 1, '2011-10-17 09:41:26', 0),
(5, 'sandeepa@gmail.com', 'e99a18c428cb38d5f260853678922e03', 'Mr.', 'Sandeepa_patient', 'Peiris', 'SS', '2011-10-10', 5235324, 'sss', 'sse', 'Makandura', NULL, NULL, 7, 1, 0, '2011-10-14 20:49:57', 0, '2011-10-17 16:55:46', 0),
(6, 'lahiru@gmail.com', 'e99a18c428cb38d5f260853678922e03', 'Mrs.', 'Lahiru_Labtest', 'gmhani', 'Y. K', '1985-06-24', 715623025, '', '', '', 'Bangladesh', NULL, 3, 1, 0, '2011-10-17 04:15:46', 1, '2011-10-17 16:41:14', 0),
(7, 'ruwan@gmail.com', 'e99a18c428cb38d5f260853678922e03', 'Mr.', 'Ruwan_Labtest', 'Samarakon', 'S. K', '1985-06-24', 715623025, '', '', '', 'Bangladesh', NULL, 3, 1, 0, '2011-10-17 04:17:07', 1, '2011-10-17 16:54:48', 0),
(8, 'gimhani@gmail.com', 'e99a18c428cb38d5f260853678922e03', 'Mrs.', 'Ayesha_Checking', 'Kulathunga', 'P.R', '1986-06-19', 715623025, '', '', '', 'Bangladesh', NULL, 4, 1, 0, '2011-10-17 05:12:40', 1, '2011-10-17 05:38:10', 0),
(9, 'nelka@gmail.com', 'e99a18c428cb38d5f260853678922e03', 'Ms.', 'Nelaka_Checking', 'Perera', 'X.X.X', '1986-06-19', 715623025, '', '', '', 'Bangladesh', NULL, 4, 1, 0, '2011-10-17 05:19:26', 1, '2011-10-17 05:37:57', 0),
(10, 'ayesha@gmail.com', 'e99a18c428cb38d5f260853678922e03', 'Mrs.', 'Ayesha_Patient', 'Nadeshani', 'A.B', '1986-06-19', 715623025, '', '', '', 'Anguilla', NULL, 7, 1, 0, '2011-10-17 05:34:11', 0, '2011-10-17 05:34:11', 0);
