-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2023 at 02:17 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `dctools`
--

CREATE TABLE `dctools` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tool_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dctools`
--

INSERT INTO `dctools` (`id`, `tool_name`, `description`, `category`, `created_at`, `updated_at`) VALUES
(1, 'tool_name', NULL, 'category', NULL, NULL),
(2, 'Vulnerable Children Enrolment Form - 100 sheets, A4, Bond paper, soft cover, soft bond', NULL, 'OVC', NULL, NULL),
(3, 'Care Giver Access to Emergency Fund - 100 sheets, A4, Bond paper, soft cover, soft bond', NULL, 'OVC', NULL, NULL),
(4, 'Household Vulnerability Assessment Form - 100 sheets, A4, Bond paper, soft cover, soft bond', NULL, 'OVC', NULL, NULL),
(5, 'Care Plan Achievement Checklist - 100 sheets, A4, Bond paper, soft cover, soft bond', NULL, 'OVC', NULL, NULL),
(6, 'Vulnerable Children Education Performance Assessment Tool - 100 sheets, A4, Bond paper, soft cover, soft bond', NULL, 'OVC', NULL, NULL),
(7, 'Household_Caregiver Service Form - 100 sheets, A4, Bond paper, soft cover, soft bond', NULL, 'OVC', NULL, NULL),
(8, 'Vulnerable Children Service Form - 100 sheets, A4, Bond paper, soft cover, soft bond', NULL, 'OVC', NULL, NULL),
(9, 'OVC Program Offer Form - Duplicate, A4, Soft Bond, Soft Cover', NULL, 'OVC', NULL, NULL),
(10, 'Household Care Plan Form - 100 sheets, A4, Bond paper, soft cover, soft bond', NULL, 'OVC', NULL, NULL),
(11, 'Community Based Paediatrics Checklist - 100 sheets, A4, Bond paper, soft cover, soft bond', NULL, 'OVC', NULL, NULL),
(12, 'Referral form for Vulnerable Household - Triplicate, A4, Soft Bond, Soft Cover', NULL, 'OVC', NULL, NULL),
(13, 'Nutrition assessment form - 100 sheets, A4, Bond paper, soft cover, soft bond', NULL, 'OVC', NULL, NULL),
(14, 'Care and Support checklist - 100 sheets, A4, Bond paper, soft cover, soft bond', NULL, 'OVC', NULL, NULL),
(15, 'OVC Project Consent Form - 100 sheets, A4, Bond paper, soft cover, soft bond', NULL, 'OVC', NULL, NULL),
(16, 'OVC Status Update Form - 100 sheets, A4, Bond paper, soft cover, soft bond', NULL, 'OVC', NULL, NULL),
(17, 'Paediatric Initial Clinical Evaluation Form - A4, Duplicate, Soft cover, Soft bond', NULL, 'Paediatric Treament', NULL, NULL),
(18, 'Pediatric & Adolescent (0-14 years) Risk stratification Checklist', NULL, 'Paediatric Treament', NULL, NULL),
(19, 'Paediatric Care & Support Assessment Intervention Form 1/2 - A4, Duplicate, Soft cover, Soft bond', NULL, 'Paediatric Treament', NULL, NULL),
(20, 'Paediatric Care & Support Assessment Intervention Form 2/2 - A4, Duplicate, Soft cover, Soft bond', NULL, 'Paediatric Treament', NULL, NULL),
(21, 'Operation Triple Zero (OTZ) Register - Bond paper, A3, Hard Bond, Hard Cover', NULL, 'Paediatric Treament', NULL, NULL),
(22, 'Viremia Register - Bond paper, A3, Hard Bond, Hard Cover', NULL, 'Paediatric Treament', NULL, NULL),
(23, 'Disclosure Checklist - 100 sheets, A4, Bond paper, soft cover, soft bond (Single Sheet)', NULL, 'Paediatric Treament', NULL, NULL),
(24, 'OTZ Enrolment Forms - 100 sheets, A4, Bond paper, soft cover, soft bond (Single Sheet)', NULL, 'Paediatric Treament', NULL, NULL),
(25, 'Peer Champion Modules - 100 sheets, A4, Bond paper, soft cover, soft bond (Single Sheet)', NULL, 'Paediatric Treament', NULL, NULL),
(26, 'Caregivers Modules - 100 sheets, A4, Bond paper, soft cover, soft bond (Single Sheet)', NULL, 'Paediatric Treament', NULL, NULL),
(27, 'OTZ Treatment Literacy Forms - 100 sheets, A4, Bond paper, soft cover, soft bond (Single Sheet)', NULL, 'Paediatric Treament', NULL, NULL),
(28, 'Paediatric Care & Support Summary Form - A4, Duplicate, Soft cover, Soft bond', NULL, 'Paediatric Treament', NULL, NULL),
(29, 'Adult Initial Clinical Evaluation  - A4, Duplicate, Soft cover, Soft bond', NULL, 'Adult Treatment', NULL, NULL),
(30, 'Combined Pharmacy Order Form - Triplicate, A4, Soft Bond, Soft Cover', NULL, 'Adult Treatment', NULL, NULL),
(31, 'Care/ART Card - A3, Card printed front and back', NULL, 'Adult Treatment', NULL, NULL),
(32, 'Post Exposure Prophelaxis Register', NULL, 'Adult Treatment', NULL, NULL),
(33, 'Lab Order and Result Form - Duplicate, A4, Soft Bond, Soft Cover', NULL, 'Adult Treatment', NULL, NULL),
(34, 'Viral Load Order and Result Form - Duplicate, A4, Soft Bond, Soft Cover', NULL, 'Adult Treatment', NULL, NULL),
(35, 'Client Tracking & Discontinuation Form - Duplicate, A4, Soft Bond, Soft Cover', NULL, 'Adult Treatment', NULL, NULL),
(36, 'ART Monthly Summary Form - A4, Triplicate, Soft cover, Soft bond', NULL, 'Adult Treatment', NULL, NULL),
(37, 'HIV Care Enrolment Register ? A3, Bond paper, Hard cover, Hard bond', NULL, 'Adult Treatment', NULL, NULL),
(38, 'ART Register - A3, Bond paper, Hard cover, Hard bond', NULL, 'Adult Treatment', NULL, NULL),
(39, 'HIV Patient Tracking Register ? A3, Bond paper, Hard cover, Hard bond', NULL, 'Adult Treatment', NULL, NULL),
(40, 'Pharmacy Daily Worksheet ? A3, Bond paper, Hard cover, Hard bond', NULL, 'Adult Treatment', NULL, NULL),
(41, 'Appointment Book - Bond paper, A4, Spiral Bond, Hard Cover', NULL, 'Adult Treatment', NULL, NULL),
(42, 'Community DSD Monitoring Register', NULL, 'Adult Treatment', NULL, NULL),
(43, 'Facility DSD Register', NULL, 'Adult Treatment', NULL, NULL),
(44, 'HIV Care and Treatment Transfer Form', NULL, 'Adult Treatment', NULL, NULL),
(45, 'Viral Load  Monitoring Register', NULL, 'Adult Treatment', NULL, NULL),
(46, 'Enhanced Adherence Counselling  Form', NULL, 'Adult Treatment', NULL, NULL),
(47, 'Enhanced Adherence Counselling  Monitoring Register', NULL, 'Adult Treatment', NULL, NULL),
(48, ' DSD Assesment & Acceptance form', NULL, 'Adult Treatment', NULL, NULL),
(49, 'Missed Appointment Register - A3, Bond paper, Hard cover, Hard bond', NULL, 'Adult Treatment', NULL, NULL),
(50, 'Advanced HIV Disease Intensive Patient  Follow up', NULL, 'Adult Treatment', NULL, NULL),
(51, 'Client Intake Form - Bond paper, A4, Hard Bond, Soft Cover', NULL, 'Prevention', NULL, NULL),
(52, 'Result and Request Form - Duplicate, Quoto, Soft Bond, Soft Cover', NULL, 'Prevention', NULL, NULL),
(53, 'HTS Monthly Summary Form - Triplicate, A4, Soft Bond, Soft Cover', NULL, 'Prevention', NULL, NULL),
(54, 'HIV testing screening checklist - 100 sheets, A4, Bond paper, Soft cover, Soft bond', NULL, 'Prevention', NULL, NULL),
(55, 'Bandasons\'s Screening tool or HIV Risk Assesment Tool', NULL, 'Prevention', NULL, NULL),
(56, 'Partner Notification Service Form ?  A4, Soft Bond, Soft Cover', NULL, 'Prevention', NULL, NULL),
(57, 'Index Testing Register -  Bond paper, A3, Hard Bond, Hard Cover', NULL, 'Prevention', NULL, NULL),
(58, 'Index Testing Monthly Summary Form -  Triplicate, A4, Soft Bond, Soft Cover', NULL, 'Prevention', NULL, NULL),
(59, 'HIV Testing Services Register - Bond paper, A3, Hard Bond, Hard Cover', NULL, 'Prevention', NULL, NULL),
(60, 'HTS Referral Register - Bond paper, A3, Hard Bond, Hard Cover', NULL, 'Prevention', NULL, NULL),
(61, 'Client Referral Form - A4 Portrait, Triplicate, Soft Bond, Soft Cover', NULL, 'Prevention', NULL, NULL),
(62, 'HIV Self-Testing Register - Bond paper, A3, Hard Bond, Hard Cover', NULL, 'Prevention', NULL, NULL),
(63, 'HIV Self-Testing Monthly Summary Form - Triplicate, A4, Soft Bond, Soft Cover', NULL, 'Prevention', NULL, NULL),
(64, 'Rapid Test for Recent Infection Proficiency Testing (PT) Result form - Bond paper, A4, Hard Bond, Soft Cover', NULL, 'Prevention', NULL, NULL),
(65, 'Adverse Event Tracking Register - Bond paper, A3, Hard Bond, Hard Cover', NULL, 'Prevention', NULL, NULL),
(66, 'Inventory Control Card ? A4, Card printed in the front', NULL, 'Prevention', NULL, NULL),
(67, 'Rapid Test for Recent Infection Form - Bond paper, A4, Hard Bond, Soft Cover', NULL, 'Prevention', NULL, NULL),
(68, 'Rapid Test for Recent Infection (RTRI) Quality Control (QC) Logbook', NULL, 'Prevention', NULL, NULL),
(69, 'SOP for Eligibility and Consent ? Booklet, Bond Paper, Soft Bond', NULL, 'Prevention', NULL, NULL),
(70, 'SOP for Asante Rapid HIV-1 Recency Assay ? Booklet, Bond Paper, Soft Bond', NULL, 'Prevention', NULL, NULL),
(71, 'SOP for Data Collection ? Booklet, Bond Paper, Soft Bond', NULL, 'Prevention', NULL, NULL),
(72, 'SOP for Data capture for Known Positive Retest ? Booklet, Bond Paper, Soft Bond', NULL, 'Prevention', NULL, NULL),
(73, 'SOP for Reporting Adverse Events ? Booklet, Bond Paper, Soft Bond', NULL, 'Prevention', NULL, NULL),
(74, 'Statement of Intent to maintain Confidentiality - Bond paper, A4, Hard Bond, Soft Cover', NULL, 'Prevention', NULL, NULL),
(75, 'SOP for Collection and Handling of Venous Blood for Viral Load Testing ? Booklet, Bond Paper, Soft Bond', NULL, 'Prevention', NULL, NULL),
(76, 'SOP for Collection, Handling, and transportation of Dried Blood Spot (DBS) Specimens using venous blood for Viral Load Testing ? Booklet, Bond Paper, Soft Bond', NULL, 'Prevention', NULL, NULL),
(77, 'SOP for QA and QC for Nigeria Recency Infection Surveillance ? Booklet, Bond Paper, Soft Bond', NULL, 'Prevention', NULL, NULL),
(78, 'Trace Incident Reporting Form - Bond paper, A4, Hard Bond, Soft Cover', NULL, 'Prevention', NULL, NULL),
(79, 'Daily HIV Test Worksheet - Bond paper, A3, Hard Bond, Hard Cover', NULL, 'Prevention', NULL, NULL),
(80, 'PrEP Eligibility form', NULL, 'Prevention', NULL, NULL),
(81, 'PrEP Eligibility Register', NULL, 'Prevention', NULL, NULL),
(82, 'PrEP MSF Monthly summary form', NULL, 'Prevention', NULL, NULL),
(83, 'PrEP Register', NULL, 'Prevention', NULL, NULL),
(84, 'PrEP card', NULL, 'Prevention', NULL, NULL),
(85, 'HIV Self-Test Response and Referral Card', NULL, 'Prevention', NULL, NULL),
(86, 'Family Index Testing Form', NULL, 'Prevention', NULL, NULL),
(87, 'PMTCT Monthly  Summary Form - Triplicate, A4, Soft Bond, Soft Cover', NULL, 'PMTCT', NULL, NULL),
(88, 'Maternal Reporting Form - A4, Triplicate, Soft cover, Soft bond', NULL, 'PMTCT', NULL, NULL),
(89, 'HEI Reporting Form - A4, Triplicate, Soft cover, Soft bond', NULL, 'PMTCT', NULL, NULL),
(90, 'General ANC Register - Bond paper, A3, Hard Bond, Hard Cover', NULL, 'PMTCT', NULL, NULL),
(91, 'PMTCT HTS Register - Bond paper, A3, Hard Bond, Hard Cover', NULL, 'PMTCT', NULL, NULL),
(92, 'Partner Register - Bond paper, A3, Hard Bond, Hard Cover', NULL, 'PMTCT', NULL, NULL),
(93, 'Delivery Register - Bond paper, A3, Hard Bond, Hard Cover', NULL, 'PMTCT', NULL, NULL),
(94, 'Child Follow-up Register - Bond paper, A3, Hard Bond, Hard Cover', NULL, 'PMTCT', NULL, NULL),
(95, 'TB ANC screening tool - A4, Duplicate, Soft cover, Soft bond', NULL, 'PMTCT', NULL, NULL),
(96, 'PMTCT TB presumptive register - A4, Duplicate, Soft cover, Soft bond', NULL, 'PMTCT', NULL, NULL),
(97, 'PMTCT TPT Monitoring register - A4, Duplicate, Soft cover, Soft bond', NULL, 'PMTCT', NULL, NULL),
(98, 'Maternal Cohort Register - Bond paper, A3, Hard Bond, Hard Cover', NULL, 'PMTCT', NULL, NULL),
(99, ' Cervical cancer screening and treatment register', NULL, 'PMTCT', NULL, NULL),
(100, ' Cervical cancer screening and treatment referral tracker', NULL, 'PMTCT', NULL, NULL),
(101, ' Referral? slips (booklet)', NULL, 'PMTCT', NULL, NULL),
(102, 'VIA Screening forms(booklet)', NULL, 'PMTCT', NULL, NULL),
(103, ' Cervical cancer screening and treatment monthly summary forms', NULL, 'PMTCT', NULL, NULL),
(104, 'TPT Cohort Register - Bond paper, A3, Hard Bond, Hard Cover', NULL, 'TB', NULL, NULL),
(105, 'IPT Screening Tool - A4, Card printed front and back', NULL, 'TB', NULL, NULL),
(106, 'TB referral volunteers register - A3, Bond paper, Hard cover, Hard bond', NULL, 'TB', NULL, NULL),
(107, 'PLHIV Presumptive TB Diagnostic Evaluation and Treatment Register -  A3, Bond paper, Hard cover, Hard bond', NULL, 'TB', NULL, NULL),
(108, 'Patient Locator Form', NULL, 'TB', NULL, NULL),
(109, 'Referral Forms ', NULL, 'TB', NULL, NULL),
(110, 'Tally Cards', NULL, 'TB', NULL, NULL),
(111, 'TB Screening Status Register', NULL, 'TB', NULL, NULL),
(112, 'National Gender Based Violence (GBV) Incidence Form - A4, Bond paper, Soft Cover, Soft Bond', NULL, 'Gender', NULL, NULL),
(113, 'National Gender Based Violence (GBV) Client Screening and Intake form - A4, Bond paper, Soft Cover, Soft Bond', NULL, 'Gender', NULL, NULL),
(114, 'National Gender Based Violence (GBV) Care Register - A3, Bond paper, Hard cover, Hard bond', NULL, 'Gender', NULL, NULL),
(115, 'Gender ? GBV Prevention / Outreach Log - A4, Card printed in the front', NULL, 'Gender', NULL, NULL),
(116, 'Gender Based Violence (GBV) Routine Enquiry Tool - Bond paper, A4, Hard Bond, Soft Cover', NULL, 'Gender', NULL, NULL),
(117, 'Gender Based Violence (GBV) Routine Enquiry Tool (Treatment Support) - Bond paper, A4, Hard Bond, Soft Cover', NULL, 'Gender', NULL, NULL),
(118, 'Gender ? GBV Training LOG - A4, Card printed in the front', NULL, 'Gender', NULL, NULL),
(119, 'Gender ? GBV Monthly Reporting Summary: Referrals - A4, Bond paper, Soft Cover, Soft Bond', NULL, 'Gender', NULL, NULL),
(120, 'Follow-Up Patient Form: PEP and POST PEP HIV TEST - A4, Bond paper, Soft Cover, Soft Bond', NULL, 'Gender', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dctools`
--
ALTER TABLE `dctools`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dctools`
--
ALTER TABLE `dctools`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
