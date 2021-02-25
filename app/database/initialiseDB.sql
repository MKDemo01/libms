/* Database and Table Initialisation SQL Statements for libraryms */

-- Create libraryms main database
CREATE DATABASE IF NOT EXISTS libraryms;

-- Use libraryms database
USE libraryms;

-- Create users table
CREATE TABLE IF NOT EXISTS `users` (
  `UserID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Username` VARCHAR(40) NOT NULL UNIQUE,
  `Password` VARCHAR(100) NOT NULL,
  `FirstName` VARCHAR(40),
  `LastName` VARCHAR (40),
  `Email` VARCHAR (40) NOT NULL,
  `ContactNo` VARCHAR(40),
  `IsAdmin` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0=NotAdmin, 1=Admin',
  `UserStatus` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0=Unapproved, 1=Approved',
  `RecordStatus` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '0=Inactive, 1=Active'
);

-- Load initial test users using index.php?p=register to ensure Password Hashing

-- Manually Update User and Admin Status for test users
/* UPDATE `users` SET `UserStatus` = 1 WHERE `UserID` = 1 OR `UserID` = 2;
UPDATE `users` SET `IsAdmin` = 1 WHERE `UserID` = 1; */

-- Create books table
CREATE TABLE IF NOT EXISTS `books` (
  `BookID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Title` VARCHAR(40) NOT NULL UNIQUE,
  `Author` VARCHAR(40) NOT NULL,
  `Publisher` VARCHAR(40) NOT NULL,
  `ISBN` VARCHAR(40) DEFAULT NULL,
  `Price` DECIMAL(10, 2) DEFAULT 0.00,
  `QtyTotal` INT(11) NOT NULL DEFAULT 0,
  `QtyAvail` INT(11) NOT NULL DEFAULT 0,
  `ImgFilename` VARCHAR(40) DEFAULT NULL,
  `AddedTimestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `AddedUserID` INT(11) NOT NULL,
  `RecordStatus` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '0=Inactive, 1=Active'
);

-- Create books_issued table
CREATE TABLE IF NOT EXISTS `books_issued` (
  `IssuedID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `BookID` INT(11) NOT NULL,
  `UserID` INT(11) NOT NULL,
  `IssuedDate` DATE NOT NULL,
  `ReturnDueDate` DATE NOT NULL,
  `ReturnActualDate` DATE DEFAULT NULL,
  `RecordStatus` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '0=Inactive, 1=Active',
  FOREIGN KEY (`BookID`) REFERENCES `books` (`BookID`),
  FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`)
);

-- Create books_issued_view uncoded view
CREATE VIEW IF NOT EXISTS `books_issued_view` AS SELECT
  `books_issued`.`IssuedID` AS `IssuedID`,
  `books_issued`.`BookID` AS `BookID`,
  `books`.`Title` AS `Title`,
  `books_issued`.`UserID` AS `UserID`,
  `users`.`Username` AS `Username`,
  `books_issued`.`IssuedDate` AS `IssuedDate`,
  `books_issued`.`ReturnDueDate` AS `ReturnDueDate`,
  `books_issued`.`ReturnActualDate` AS `ReturnActualDate`,
  `books_issued`.`RecordStatus` AS `RecordStatus`
  FROM `books_issued`
  LEFT JOIN `books` ON `books`.`BookID` = `books_issued`.`BookID`
  LEFT JOIN `users` ON `users`.`UserID` = `books_issued`.`UserID`;

-- Create messages table
CREATE TABLE IF NOT EXISTS `messages` (
  `MessageID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `SenderID` INT(11) NOT NULL,
  `ReceiverID` INT(11) NOT NULL,
  `Subject` VARCHAR(40) NOT NULL,
  `Body` VARCHAR(500) NOT NULL,
  `AddedTimestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
  `MessageStatus` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0=Unread, 1=Read',
  `RecordStatus` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '0=Inactive, 1=Active',
  FOREIGN KEY (`SenderID`) REFERENCES `users` (`UserID`),
  FOREIGN KEY (`ReceiverID`) REFERENCES `users` (`UserID`)
);

-- Create messages_view uncoded view
CREATE VIEW IF NOT EXISTS `messages_view` AS SELECT
  `messages`.`MessageID` AS `MessageID`,
  `messages`.`SenderID` AS `SenderID`,
  `users_s`.`Username` AS `SenderName`,
  `users_s`.`RecordStatus` AS `SenderStatus`,
  `messages`.`ReceiverID` AS `ReceiverID`,
  `users_r`.`Username` AS `ReceiverName`,
  `users_r`.`RecordStatus` AS `ReceiverStatus`,
  `messages`.`Subject` AS `Subject`,
  `messages`.`Body` AS `Body`,
  `messages`.`AddedTimestamp` AS `AddedTimestamp`,
  `messages`.`MessageStatus` AS `MessageStatus`,
  `messages`.`RecordStatus` AS `RecordStatus`
  FROM `messages`
  LEFT JOIN `users` AS `users_s` ON `users_s`.`UserID` = `messages`.`SenderID`
  LEFT JOIN `users` AS `users_r` ON `users_r`.`UserID` = `messages`.`ReceiverID`;
