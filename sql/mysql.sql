-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Dec 14, 2004 at 09:57 AM
-- Server version: 4.0.22
-- PHP Version: 4.3.9
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `xent_park_equips`
-- 

CREATE TABLE `xent_park_equips` (
    `ID_EQUIPS`      INT(5)       NOT NULL DEFAULT '0',
    `name`           VARCHAR(255) NOT NULL DEFAULT '',
    `desc`           TEXT         NOT NULL,
    `serial`         VARCHAR(255) NOT NULL DEFAULT '',
    `id_type_equips` INT(5)       NOT NULL DEFAULT '0',
    PRIMARY KEY (`ID_EQUIPS`)
)
    ENGINE = ISAM;

-- 
-- Dumping data for table `xent_park_equips`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `xent_park_equiptype`
-- 

CREATE TABLE `xent_park_equiptypes` (
    `ID_EQUIPTYPE` INT(5)       NOT NULL DEFAULT '0',
    `name`         VARCHAR(255) NOT NULL DEFAULT '',
    PRIMARY KEY (`ID_EQUIPTYPE`)
)
    ENGINE = ISAM;

-- 
-- Dumping data for table `xent_park_equiptype`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `xent_park_link_workstation_equips`
-- 

CREATE TABLE `xent_park_link_workstation_equips` (
    `ID_WORKSTATION` INT(5) NOT NULL DEFAULT '0',
    `ID_EQUIP`       INT(5) NOT NULL DEFAULT '0'
)
    ENGINE = ISAM;

-- 
-- Dumping data for table `xent_park_link_workstation_equips`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `xent_park_rents`
-- 

CREATE TABLE `xent_park_rents` (
    `ID_RENT`   INT(5)  NOT NULL DEFAULT '0',
    `date_rent` INT(11) NOT NULL DEFAULT '0',
    `date_back` INT(11) NOT NULL DEFAULT '0',
    `id_equip`  INT(5)  NOT NULL DEFAULT '0',
    PRIMARY KEY (`ID_RENT`)
)
    ENGINE = ISAM;

-- 
-- Dumping data for table `xent_park_rents`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `xent_park_sites`
-- 

CREATE TABLE `xent_park_sites` (
    `ID_SITE` INT(5)       NOT NULL DEFAULT '0',
    `name`    VARCHAR(255) NOT NULL DEFAULT '',
    `desc`    TEXT         NOT NULL,
    PRIMARY KEY (`ID_SITE`)
)
    ENGINE = ISAM;

-- 
-- Dumping data for table `xent_park_sites`
-- 

INSERT INTO `xent_park_site`
VALUES (0, '007', 'Numéro de poste');

-- --------------------------------------------------------

-- 
-- Table structure for table `xent_park_workstations`
-- 

CREATE TABLE `xent_park_workstations` (
    `ID_WORKSTATION` INT(5)       NOT NULL AUTO_INCREMENT,
    `name`           VARCHAR(255) NOT NULL DEFAULT '',
    `desc`           TEXT         NOT NULL,
    `owner`          VARCHAR(255) NOT NULL DEFAULT '',
    `type`           INT(5)       NOT NULL DEFAULT '0',
    `id_site`        INT(5)       NOT NULL DEFAULT '0',
    PRIMARY KEY (`ID_WORKSTATION`)
)
    ENGINE = ISAM COMMENT ='About type field : 0=desktop, 1=laptop'
    AUTO_INCREMENT = 2;

-- 
-- Dumping data for table `xent_park_workstations`
-- 

INSERT INTO `xent_park_workstations`
VALUES (1, 'SUPPORT', 'Poste à Alex', '60', 0, 0);
