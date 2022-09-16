-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3325
-- Generation Time: Sep 13, 2022 at 10:32 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `athenacc`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `scuid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `assessment_tool`
--

CREATE TABLE `assessment_tool` (
  `id` int(10) NOT NULL,
  `name` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `availability`
--

CREATE TABLE `availability` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cluster`
--

CREATE TABLE `cluster` (
  `id` int(10) NOT NULL,
  `parent_clusterid` int(10) DEFAULT NULL,
  `long_name` varchar(255) NOT NULL,
  `short_name` varchar(10) DEFAULT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cluster`
--

INSERT INTO `cluster` (`id`, `parent_clusterid`, `long_name`, `short_name`, `description`) VALUES
(1, NULL, 'Competence Clusters', 'CC', ''),
(2, 1, 'Network', 'N', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer eget lacinia mi. Morbi magna quam, ultricies nec aliquam vel, dignissim sagittis nibh.'),
(3, 2, 'CyberSecurity', 'CybSec', 'Curabitur sed euismod felis, at accumsan ante. Nunc lacinia magna non magna vulputate, nec elementum nunc condimentum.- Cybersecurity'),
(4, 1, 'Bussiness', NULL, 'Proin pretium diam sit amet mi iaculis, eu tincidunt dolor lobortis. - Bussiness'),
(5, 2, 'Microwaves, Antennas and Electromagnetic Application', 'M,A&EA', 'Donec nec lectus lorem. In egestas nulla quis tristique eleifend. Integer ex ligula, sollicitudin et commodo at, rutrum at tortor. - Microwaves, Antennas and Electromagnetic Application');

-- --------------------------------------------------------

--
-- Table structure for table `content_type`
--

CREATE TABLE `content_type` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Scientific, Technological, Contextual, ...';

-- --------------------------------------------------------

--
-- Table structure for table `granularity`
--

CREATE TABLE `granularity` (
  `id` int(10) NOT NULL,
  `granularity` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Full course, Module, Activity';

--
-- Dumping data for table `granularity`
--

INSERT INTO `granularity` (`id`, `granularity`) VALUES
(1, 'Full course'),
(2, 'Module'),
(3, 'Activity');

-- --------------------------------------------------------

--
-- Table structure for table `institution`
--

CREATE TABLE `institution` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

CREATE TABLE `instructor` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `academic?title` varchar(255) NOT NULL,
  `academic?degree` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `instructor_scu_subject`
--

CREATE TABLE `instructor_scu_subject` (
  `instructorid` int(10) NOT NULL,
  `scu_subjectscuid` int(10) NOT NULL,
  `scu_subjectsubjectid` int(10) NOT NULL,
  `institutionid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `learning_material`
--

CREATE TABLE `learning_material` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `learning_material_scu_subject`
--

CREATE TABLE `learning_material_scu_subject` (
  `learning_materialid` int(10) NOT NULL,
  `scu_subjectscuid` int(10) NOT NULL,
  `scu_subjectsubjectid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `learning_outcome`
--

CREATE TABLE `learning_outcome` (
  `id` int(10) NOT NULL,
  `scuid` int(10) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `learning_outcome_skill_competence`
--

CREATE TABLE `learning_outcome_skill_competence` (
  `learning_outcomeid` int(10) NOT NULL,
  `skill_competenceid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `learning_outcome_subject`
--

CREATE TABLE `learning_outcome_subject` (
  `learning_outcomeid` int(10) NOT NULL,
  `subjectid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mode`
--

CREATE TABLE `mode` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Physical, Blended, Virtual';

--
-- Dumping data for table `mode`
--

INSERT INTO `mode` (`id`, `name`) VALUES
(1, 'Physical'),
(2, 'Blended'),
(3, 'Virtual');

-- --------------------------------------------------------

--
-- Table structure for table `pedagogical_approach`
--

CREATE TABLE `pedagogical_approach` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Experiential learning, Inquiry learning, Role playing, Flipped classroom, Inverted lecture hall, Hands-on, Case studies, Demonstrative, Peer review, Brainsstorming, Writing, Expositive';

--
-- Dumping data for table `pedagogical_approach`
--

INSERT INTO `pedagogical_approach` (`id`, `name`) VALUES
(1, 'Experiential learning'),
(2, 'Inquiry learning'),
(3, 'Role playing'),
(4, 'Flipped classroom'),
(5, 'Inverted lecture hall'),
(6, 'Hands-on'),
(7, 'Case studies'),
(8, 'Demonstrative'),
(9, 'Peer review'),
(10, 'Brainsstorming'),
(11, 'Writing'),
(12, 'Expositive');

-- --------------------------------------------------------

--
-- Table structure for table `pre-requisite`
--

CREATE TABLE `pre-requisite` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `scu`
--

CREATE TABLE `scu` (
  `id` int(10) NOT NULL,
  `clusterid` int(10) NOT NULL,
  `modeid` int(10) NOT NULL,
  `granularityid` int(10) NOT NULL,
  `pedagogical_approachid` int(10) NOT NULL,
  `long_name` varchar(255) NOT NULL,
  `short_name` varchar(10) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `ects` int(10) NOT NULL,
  `total_work_hours` int(10) NOT NULL,
  `auto_study_hours` int(10) DEFAULT NULL,
  `sync_teaching_hours` int(10) DEFAULT NULL,
  `async_teaching_hours` int(10) DEFAULT NULL,
  `theory_hours` int(10) DEFAULT NULL,
  `practice_hours` int(10) DEFAULT NULL,
  `work_based_hours` int(10) DEFAULT NULL,
  `interactive_activity_hours` int(10) DEFAULT NULL,
  `non_interactive_activity_hours` int(10) DEFAULT NULL,
  `degree` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `scu`
--

INSERT INTO `scu` (`id`, `clusterid`, `modeid`, `granularityid`, `pedagogical_approachid`, `long_name`, `short_name`, `description`, `ects`, `total_work_hours`, `auto_study_hours`, `sync_teaching_hours`, `async_teaching_hours`, `theory_hours`, `practice_hours`, `work_based_hours`, `interactive_activity_hours`, `non_interactive_activity_hours`, `degree`) VALUES
(1, 3, 3, 1, 5, 'Information System Security and Information System Technology and Ecommerce', 'ISS&IST&E', 'Praesent molestie at eros non gravida. Duis ac eros auctor, facilisis quam in, porta massa. Donec ac ligula finibus, euismod odio id, tempus diam. - Information System Security and Information System Technology and Ecommerce', 3, 60, NULL, NULL, NULL, 3, 0, NULL, NULL, NULL, NULL),
(2, 5, 1, 2, 7, 'Antennas and Wireless Communication', NULL, 'Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Vivamus hendrerit at eros nec tempor. - Antennas and Wireless Communication', 4, 80, NULL, NULL, NULL, 2, 2, NULL, NULL, NULL, NULL),
(3, 5, 1, 1, 1, 'Defense Technologies & Electromagnetic Compatibility', 'DT&EC', 'In faucibus nibh vel mi consequat, non bibendum neque fringilla. Nulla porttitor ante vitae lectus tincidunt placerat.  - Defense Technologies & Electromagnetic Compatibility', 5, 125, NULL, NULL, NULL, 3, 2, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `scu_availability`
--

CREATE TABLE `scu_availability` (
  `scuid` int(10) NOT NULL,
  `availabilityid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `scu_content_type`
--

CREATE TABLE `scu_content_type` (
  `scuid` int(10) NOT NULL,
  `content_typeid` int(10) NOT NULL,
  `percentage` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `scu_language`
--

CREATE TABLE `scu_language` (
  `scuid` int(10) NOT NULL,
  `languageid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `scu_pre-requisite`
--

CREATE TABLE `scu_pre-requisite` (
  `scuid` int(10) NOT NULL,
  `pre-requisiteid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `scu_subject`
--

CREATE TABLE `scu_subject` (
  `scuid` int(10) NOT NULL,
  `subjectid` int(10) NOT NULL,
  `class_time` int(10) DEFAULT NULL,
  `autonomous_time` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `scu_subject_assessment_tool`
--

CREATE TABLE `scu_subject_assessment_tool` (
  `scu_subjectscuid` int(10) NOT NULL,
  `scu_subjectsubjectid` int(10) NOT NULL,
  `assessment_toolid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `skill_competence`
--

CREATE TABLE `skill_competence` (
  `id` int(10) NOT NULL,
  `name` int(10) NOT NULL,
  `taxonomy_subject_skillid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` int(10) NOT NULL,
  `taxonomy_subject_skillid` int(10) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `subject_cluster`
--

CREATE TABLE `subject_cluster` (
  `subjectid` int(10) NOT NULL,
  `clusterid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `taxonomy_subject_skill`
--

CREATE TABLE `taxonomy_subject_skill` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKactivity581435` (`scuid`);

--
-- Indexes for table `assessment_tool`
--
ALTER TABLE `assessment_tool`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `availability`
--
ALTER TABLE `availability`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cluster`
--
ALTER TABLE `cluster`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent` (`parent_clusterid`);

--
-- Indexes for table `content_type`
--
ALTER TABLE `content_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `granularity`
--
ALTER TABLE `granularity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `institution`
--
ALTER TABLE `institution`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instructor`
--
ALTER TABLE `instructor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instructor_scu_subject`
--
ALTER TABLE `instructor_scu_subject`
  ADD PRIMARY KEY (`instructorid`,`scu_subjectscuid`,`scu_subjectsubjectid`),
  ADD KEY `FKinstructor145924` (`scu_subjectscuid`,`scu_subjectsubjectid`),
  ADD KEY `FKinstructor277972` (`institutionid`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `learning_material`
--
ALTER TABLE `learning_material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `learning_material_scu_subject`
--
ALTER TABLE `learning_material_scu_subject`
  ADD PRIMARY KEY (`learning_materialid`,`scu_subjectscuid`,`scu_subjectsubjectid`),
  ADD KEY `FKlearning_m634373` (`scu_subjectscuid`,`scu_subjectsubjectid`);

--
-- Indexes for table `learning_outcome`
--
ALTER TABLE `learning_outcome`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKlearning_o854008` (`scuid`);

--
-- Indexes for table `learning_outcome_skill_competence`
--
ALTER TABLE `learning_outcome_skill_competence`
  ADD PRIMARY KEY (`learning_outcomeid`,`skill_competenceid`),
  ADD KEY `FKlearning_o322287` (`skill_competenceid`);

--
-- Indexes for table `learning_outcome_subject`
--
ALTER TABLE `learning_outcome_subject`
  ADD PRIMARY KEY (`learning_outcomeid`,`subjectid`),
  ADD KEY `FKlearning_o75500` (`subjectid`);

--
-- Indexes for table `mode`
--
ALTER TABLE `mode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pedagogical_approach`
--
ALTER TABLE `pedagogical_approach`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre-requisite`
--
ALTER TABLE `pre-requisite`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scu`
--
ALTER TABLE `scu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKscu151273` (`clusterid`),
  ADD KEY `FKscu326408` (`modeid`),
  ADD KEY `FKscu509965` (`granularityid`),
  ADD KEY `FKscu955367` (`pedagogical_approachid`);

--
-- Indexes for table `scu_availability`
--
ALTER TABLE `scu_availability`
  ADD PRIMARY KEY (`scuid`,`availabilityid`),
  ADD KEY `FKscu_availa91181` (`availabilityid`);

--
-- Indexes for table `scu_content_type`
--
ALTER TABLE `scu_content_type`
  ADD PRIMARY KEY (`scuid`,`content_typeid`),
  ADD KEY `FKscu_conten576029` (`content_typeid`);

--
-- Indexes for table `scu_language`
--
ALTER TABLE `scu_language`
  ADD PRIMARY KEY (`scuid`,`languageid`),
  ADD KEY `FKscu_langua327012` (`languageid`);

--
-- Indexes for table `scu_pre-requisite`
--
ALTER TABLE `scu_pre-requisite`
  ADD PRIMARY KEY (`scuid`,`pre-requisiteid`),
  ADD KEY `FKscu_pre-re997725` (`pre-requisiteid`);

--
-- Indexes for table `scu_subject`
--
ALTER TABLE `scu_subject`
  ADD PRIMARY KEY (`scuid`,`subjectid`),
  ADD KEY `FKscu_subjec703936` (`subjectid`);

--
-- Indexes for table `scu_subject_assessment_tool`
--
ALTER TABLE `scu_subject_assessment_tool`
  ADD PRIMARY KEY (`scu_subjectscuid`,`scu_subjectsubjectid`,`assessment_toolid`),
  ADD KEY `FKscu_subjec451640` (`assessment_toolid`);

--
-- Indexes for table `skill_competence`
--
ALTER TABLE `skill_competence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKskill_comp257265` (`taxonomy_subject_skillid`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `FKsubject342899` (`taxonomy_subject_skillid`);

--
-- Indexes for table `subject_cluster`
--
ALTER TABLE `subject_cluster`
  ADD PRIMARY KEY (`subjectid`,`clusterid`),
  ADD KEY `FKsubject_cl240496` (`clusterid`);

--
-- Indexes for table `taxonomy_subject_skill`
--
ALTER TABLE `taxonomy_subject_skill`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assessment_tool`
--
ALTER TABLE `assessment_tool`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `availability`
--
ALTER TABLE `availability`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cluster`
--
ALTER TABLE `cluster`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `content_type`
--
ALTER TABLE `content_type`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `granularity`
--
ALTER TABLE `granularity`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `institution`
--
ALTER TABLE `institution`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instructor`
--
ALTER TABLE `instructor`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_material`
--
ALTER TABLE `learning_material`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_outcome`
--
ALTER TABLE `learning_outcome`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mode`
--
ALTER TABLE `mode`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pedagogical_approach`
--
ALTER TABLE `pedagogical_approach`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pre-requisite`
--
ALTER TABLE `pre-requisite`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scu`
--
ALTER TABLE `scu`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `skill_competence`
--
ALTER TABLE `skill_competence`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `taxonomy_subject_skill`
--
ALTER TABLE `taxonomy_subject_skill`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `FKactivity581435` FOREIGN KEY (`scuid`) REFERENCES `scu` (`id`);

--
-- Constraints for table `cluster`
--
ALTER TABLE `cluster`
  ADD CONSTRAINT `parent` FOREIGN KEY (`parent_clusterid`) REFERENCES `cluster` (`id`);

--
-- Constraints for table `instructor_scu_subject`
--
ALTER TABLE `instructor_scu_subject`
  ADD CONSTRAINT `FKinstructor145924` FOREIGN KEY (`scu_subjectscuid`,`scu_subjectsubjectid`) REFERENCES `scu_subject` (`scuid`, `subjectid`),
  ADD CONSTRAINT `FKinstructor277972` FOREIGN KEY (`institutionid`) REFERENCES `institution` (`id`),
  ADD CONSTRAINT `FKinstructor294395` FOREIGN KEY (`instructorid`) REFERENCES `instructor` (`id`);

--
-- Constraints for table `learning_material_scu_subject`
--
ALTER TABLE `learning_material_scu_subject`
  ADD CONSTRAINT `FKlearning_m448911` FOREIGN KEY (`learning_materialid`) REFERENCES `learning_material` (`id`),
  ADD CONSTRAINT `FKlearning_m634373` FOREIGN KEY (`scu_subjectscuid`,`scu_subjectsubjectid`) REFERENCES `scu_subject` (`scuid`, `subjectid`);

--
-- Constraints for table `learning_outcome`
--
ALTER TABLE `learning_outcome`
  ADD CONSTRAINT `FKlearning_o854008` FOREIGN KEY (`scuid`) REFERENCES `scu` (`id`);

--
-- Constraints for table `learning_outcome_skill_competence`
--
ALTER TABLE `learning_outcome_skill_competence`
  ADD CONSTRAINT `FKlearning_o298671` FOREIGN KEY (`learning_outcomeid`) REFERENCES `learning_outcome` (`id`),
  ADD CONSTRAINT `FKlearning_o322287` FOREIGN KEY (`skill_competenceid`) REFERENCES `skill_competence` (`id`);

--
-- Constraints for table `learning_outcome_subject`
--
ALTER TABLE `learning_outcome_subject`
  ADD CONSTRAINT `FKlearning_o75500` FOREIGN KEY (`subjectid`) REFERENCES `subject` (`id`),
  ADD CONSTRAINT `FKlearning_o903748` FOREIGN KEY (`learning_outcomeid`) REFERENCES `learning_outcome` (`id`);

--
-- Constraints for table `scu`
--
ALTER TABLE `scu`
  ADD CONSTRAINT `FKscu151273` FOREIGN KEY (`clusterid`) REFERENCES `cluster` (`id`),
  ADD CONSTRAINT `FKscu326408` FOREIGN KEY (`modeid`) REFERENCES `mode` (`id`),
  ADD CONSTRAINT `FKscu509965` FOREIGN KEY (`granularityid`) REFERENCES `granularity` (`id`),
  ADD CONSTRAINT `FKscu955367` FOREIGN KEY (`pedagogical_approachid`) REFERENCES `pedagogical_approach` (`id`);

--
-- Constraints for table `scu_availability`
--
ALTER TABLE `scu_availability`
  ADD CONSTRAINT `FKscu_availa517793` FOREIGN KEY (`scuid`) REFERENCES `scu` (`id`),
  ADD CONSTRAINT `FKscu_availa91181` FOREIGN KEY (`availabilityid`) REFERENCES `availability` (`id`);

--
-- Constraints for table `scu_content_type`
--
ALTER TABLE `scu_content_type`
  ADD CONSTRAINT `FKscu_conten576029` FOREIGN KEY (`content_typeid`) REFERENCES `content_type` (`id`),
  ADD CONSTRAINT `FKscu_conten820088` FOREIGN KEY (`scuid`) REFERENCES `scu` (`id`);

--
-- Constraints for table `scu_language`
--
ALTER TABLE `scu_language`
  ADD CONSTRAINT `FKscu_langua125857` FOREIGN KEY (`scuid`) REFERENCES `scu` (`id`),
  ADD CONSTRAINT `FKscu_langua327012` FOREIGN KEY (`languageid`) REFERENCES `language` (`id`);

--
-- Constraints for table `scu_pre-requisite`
--
ALTER TABLE `scu_pre-requisite`
  ADD CONSTRAINT `FKscu_pre-re476683` FOREIGN KEY (`scuid`) REFERENCES `scu` (`id`),
  ADD CONSTRAINT `FKscu_pre-re997725` FOREIGN KEY (`pre-requisiteid`) REFERENCES `pre-requisite` (`id`);

--
-- Constraints for table `scu_subject`
--
ALTER TABLE `scu_subject`
  ADD CONSTRAINT `FKscu_subjec703936` FOREIGN KEY (`subjectid`) REFERENCES `subject` (`id`),
  ADD CONSTRAINT `FKscu_subjec976231` FOREIGN KEY (`scuid`) REFERENCES `scu` (`id`);

--
-- Constraints for table `scu_subject_assessment_tool`
--
ALTER TABLE `scu_subject_assessment_tool`
  ADD CONSTRAINT `FKscu_subjec451640` FOREIGN KEY (`assessment_toolid`) REFERENCES `assessment_tool` (`id`),
  ADD CONSTRAINT `FKscu_subjec746493` FOREIGN KEY (`scu_subjectscuid`,`scu_subjectsubjectid`) REFERENCES `scu_subject` (`scuid`, `subjectid`);

--
-- Constraints for table `skill_competence`
--
ALTER TABLE `skill_competence`
  ADD CONSTRAINT `FKskill_comp257265` FOREIGN KEY (`taxonomy_subject_skillid`) REFERENCES `taxonomy_subject_skill` (`id`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `FKsubject342899` FOREIGN KEY (`taxonomy_subject_skillid`) REFERENCES `taxonomy_subject_skill` (`id`);

--
-- Constraints for table `subject_cluster`
--
ALTER TABLE `subject_cluster`
  ADD CONSTRAINT `FKsubject_cl240496` FOREIGN KEY (`clusterid`) REFERENCES `cluster` (`id`),
  ADD CONSTRAINT `FKsubject_cl599464` FOREIGN KEY (`subjectid`) REFERENCES `subject` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
