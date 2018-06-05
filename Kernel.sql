-- MySQL dump 10.13  Distrib 5.7.17, for macos10.12 (x86_64)
--
-- Host: 127.0.0.1    Database: mydb
-- ------------------------------------------------------
-- Server version	8.0.11

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `changerequests`
--

DROP TABLE IF EXISTS `changerequests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `changerequests` (
  `REQUESTID` int(11) NOT NULL AUTO_INCREMENT,
  `REQUESTDETAILS` text NOT NULL,
  `REQUESTSTATUS` varchar(45) NOT NULL,
  `users_REQUESTEDBY` int(11) NOT NULL,
  `REQUESTEDDATE` varchar(20) NOT NULL,
  `users_APPROVEDBY` int(11) DEFAULT NULL,
  `APPROVEDDATE` varchar(20) DEFAULT NULL,
  `REMARKS` text,
  PRIMARY KEY (`REQUESTID`),
  KEY `fk_changerequests_users1_idx` (`users_REQUESTEDBY`),
  KEY `fk_changerequests_users2_idx` (`users_APPROVEDBY`),
  CONSTRAINT `fk_changerequests_users1` FOREIGN KEY (`users_REQUESTEDBY`) REFERENCES `users` (`userid`),
  CONSTRAINT `fk_changerequests_users2` FOREIGN KEY (`users_APPROVEDBY`) REFERENCES `users` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `changerequests`
--

LOCK TABLES `changerequests` WRITE;
/*!40000 ALTER TABLE `changerequests` DISABLE KEYS */;
/*!40000 ALTER TABLE `changerequests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `DEPARTMENTID` int(11) NOT NULL AUTO_INCREMENT,
  `DEPARTMENTNAME` varchar(100) NOT NULL,
  PRIMARY KEY (`DEPARTMENTID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dependencies`
--

DROP TABLE IF EXISTS `dependencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dependencies` (
  `DEPENDENCYID` int(11) NOT NULL AUTO_INCREMENT,
  `PRETASKID` varchar(45) NOT NULL,
  `tasks_POSTTASKID` int(11) DEFAULT NULL,
  PRIMARY KEY (`DEPENDENCYID`),
  KEY `fk_dependencies_tasks1_idx` (`tasks_POSTTASKID`),
  CONSTRAINT `fk_dependencies_tasks1` FOREIGN KEY (`tasks_POSTTASKID`) REFERENCES `tasks` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dependencies`
--

LOCK TABLES `dependencies` WRITE;
/*!40000 ALTER TABLE `dependencies` DISABLE KEYS */;
/*!40000 ALTER TABLE `dependencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documents` (
  `DOCUMENTID` int(11) NOT NULL AUTO_INCREMENT,
  `DOCUMENTSTATUS` varchar(45) NOT NULL,
  `DOCUMENTNAME` varchar(45) NOT NULL,
  `VERSION` varchar(45) NOT NULL,
  `users_UPLOADEDBY` int(11) NOT NULL,
  `UPLOADEDDATE` varchar(20) NOT NULL,
  `users_ACKNOWLEDGEDBY` int(11) NOT NULL,
  `ACKNOWLEDGEDDATE` varchar(20) DEFAULT NULL,
  `projects_PROJECTID` int(11) NOT NULL,
  PRIMARY KEY (`DOCUMENTID`),
  KEY `fk_documents_users1_idx` (`users_UPLOADEDBY`),
  KEY `fk_documents_projects1_idx` (`projects_PROJECTID`),
  KEY `fk_documents_users2_idx` (`users_ACKNOWLEDGEDBY`),
  CONSTRAINT `fk_documents_projects1` FOREIGN KEY (`projects_PROJECTID`) REFERENCES `projects` (`projectid`),
  CONSTRAINT `fk_documents_users1` FOREIGN KEY (`users_UPLOADEDBY`) REFERENCES `users` (`userid`),
  CONSTRAINT `fk_documents_users2` FOREIGN KEY (`users_ACKNOWLEDGEDBY`) REFERENCES `users` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `LOGID` int(11) NOT NULL AUTO_INCREMENT,
  `LOGDETAILS` varchar(1000) NOT NULL,
  `TIMESTAMP` datetime NOT NULL,
  `projects_PROJECTID` int(11) NOT NULL,
  PRIMARY KEY (`LOGID`),
  KEY `fk_logs_projects1_idx` (`projects_PROJECTID`),
  CONSTRAINT `fk_logs_projects1` FOREIGN KEY (`projects_PROJECTID`) REFERENCES `projects` (`projectid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `NOTIFICATIONID` int(11) NOT NULL AUTO_INCREMENT,
  `users_USERID` int(11) NOT NULL,
  `DETAILS` text NOT NULL,
  `STATUS` varchar(45) NOT NULL,
  `NOTIFICATIONDATE` varchar(20) NOT NULL,
  `departments_DEPARTMENTID` int(11) NOT NULL,
  `TYPE` varchar(45) NOT NULL,
  PRIMARY KEY (`NOTIFICATIONID`),
  KEY `fk_notifications_users1_idx` (`users_USERID`),
  KEY `fk_notifications_departments1_idx` (`departments_DEPARTMENTID`),
  CONSTRAINT `fk_notifications_departments1` FOREIGN KEY (`departments_DEPARTMENTID`) REFERENCES `departments` (`departmentid`),
  CONSTRAINT `fk_notifications_users1` FOREIGN KEY (`users_USERID`) REFERENCES `users` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `PROJECTID` int(11) NOT NULL AUTO_INCREMENT,
  `PROJECTTITLE` varchar(100) NOT NULL,
  `PROJECTSTARTDATE` varchar(20) NOT NULL,
  `PROJECTENDDATE` varchar(20) NOT NULL,
  `PROJECTDESCRIPTION` text NOT NULL,
  `PROJECTSTATUS` varchar(45) NOT NULL,
  `users_USERID` int(11) NOT NULL COMMENT 'PROJECT OWNER',
  PRIMARY KEY (`PROJECTID`),
  KEY `fk_projects_users1_idx` (`users_USERID`),
  CONSTRAINT `fk_projects_users1` FOREIGN KEY (`users_USERID`) REFERENCES `users` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `raci`
--

DROP TABLE IF EXISTS `raci`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `raci` (
  `RACIID` int(11) NOT NULL AUTO_INCREMENT,
  `ROLE` varchar(45) NOT NULL,
  `users_USERID` int(11) NOT NULL,
  `tasks_TASKID` int(11) NOT NULL,
  PRIMARY KEY (`RACIID`),
  KEY `fk_raci_users1_idx` (`users_USERID`),
  KEY `fk_raci_tasks1_idx` (`tasks_TASKID`),
  CONSTRAINT `fk_raci_tasks1` FOREIGN KEY (`tasks_TASKID`) REFERENCES `tasks` (`taskid`),
  CONSTRAINT `fk_raci_users1` FOREIGN KEY (`users_USERID`) REFERENCES `users` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `raci`
--

LOCK TABLES `raci` WRITE;
/*!40000 ALTER TABLE `raci` DISABLE KEYS */;
/*!40000 ALTER TABLE `raci` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks` (
  `TASKID` int(11) NOT NULL AUTO_INCREMENT,
  `TASKTITLE` varchar(45) NOT NULL,
  `TASKSTARTDATE` varchar(20) NOT NULL,
  `TASKENDDATE` varchar(20) NOT NULL,
  `TASKSTATUS` varchar(45) NOT NULL,
  `TASKREMARKS` varchar(45) NOT NULL,
  `PERIOD` int(11) NOT NULL,
  `CATEGORY` varchar(45) NOT NULL,
  `projects_PROJECTID` int(11) NOT NULL,
  `users_USERID` int(11) NOT NULL,
  PRIMARY KEY (`TASKID`),
  KEY `fk_tasks_projects1_idx` (`projects_PROJECTID`),
  KEY `fk_tasks_users1_idx` (`users_USERID`),
  CONSTRAINT `fk_tasks_projects1` FOREIGN KEY (`projects_PROJECTID`) REFERENCES `projects` (`projectid`),
  CONSTRAINT `fk_tasks_users1` FOREIGN KEY (`users_USERID`) REFERENCES `users` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `USERID` int(11) NOT NULL AUTO_INCREMENT,
  `FIRSTNAME` varchar(100) NOT NULL,
  `LASTNAME` varchar(100) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `PASSWORD` varchar(100) NOT NULL,
  `POSITION` varchar(100) NOT NULL,
  `departments_DEPARTMENTID` int(11) NOT NULL,
  `usertype_USERTYPEID` int(11) NOT NULL,
  PRIMARY KEY (`USERID`),
  KEY `fk_users_departments_idx` (`departments_DEPARTMENTID`),
  KEY `fk_users_usertype1_idx` (`usertype_USERTYPEID`),
  CONSTRAINT `fk_users_departments` FOREIGN KEY (`departments_DEPARTMENTID`) REFERENCES `departments` (`departmentid`),
  CONSTRAINT `fk_users_usertype1` FOREIGN KEY (`usertype_USERTYPEID`) REFERENCES `usertype` (`usertypeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usertype`
--

DROP TABLE IF EXISTS `usertype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usertype` (
  `USERTYPEID` int(11) NOT NULL AUTO_INCREMENT,
  `USERTYPE` varchar(45) NOT NULL,
  PRIMARY KEY (`USERTYPEID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usertype`
--

LOCK TABLES `usertype` WRITE;
/*!40000 ALTER TABLE `usertype` DISABLE KEYS */;
/*!40000 ALTER TABLE `usertype` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-28 17:40:05
