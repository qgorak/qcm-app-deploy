CREATE DATABASE IF NOT EXISTS `QCM` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `QCM`;

CREATE TABLE `QcmQuestion` (
  `idQuestion` int(11),
  `idQcm` int(11),
  PRIMARY KEY (`idQuestion`, `idQcm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `UserAnswer` (
  `idUser` int(11),
  `idAnswer` int(11),
  `idQcm` int(11),
  PRIMARY KEY (`idUser`, `idAnswer`, `idQcm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Question` (
  `id` int(11),
  `caption` VARCHAR(42),
  `points` int(11),
  `tags` TEXT,
  `idUser` int(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Answer` (
  `id` int(11),
  `caption` VARCHAR(42),
  `score` FLOAT,
  `idQuestion` int(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Qcm` (
  `id` int(11),
  `name` VARCHAR(42),
  `description` VARCHAR(42),
  `cdate` DATETIME,
  `status` VARCHAR(10),
  `idExam` int(11),
  `idUser` int(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `User` (
  `id` int(11),
  `login` VARCHAR(42),
  `password` VARCHAR(42),
  `firstname` VARCHAR(42),
  `lastname` VARCHAR(42),
  `email` VARCHAR(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `UserGroup` (
  `idGroup` int(11),
  `idUser` int(11),
  PRIMARY KEY (`idGroup`, `idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Exam` (
  `id` int(11),
  `dated` DATETIME,
  `datef` DATETIME,
  `status` VARCHAR(42),
  `idGroup` int(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ExamOption` (
  `idExam` int(11),
  `idOption` int(11),
  `value` VARCHAR(42),
  PRIMARY KEY (`idExam`, `idOption`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Tag` (
  `id` int(11),
  `name` VARCHAR(42),
  `color` VARCHAR(42),
  `idUser` int(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Group` (
  `id` int(11),
  `name` VARCHAR(42),
  `description` TEXT,
  `idUser` int(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Option` (
  `id` int(11),
  `key` VARCHAR(42),
  `description` TEXT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `Group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `Question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `Answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `Qcm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `Tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `Exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `Option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
  
ALTER TABLE `QcmQuestion` ADD FOREIGN KEY (`idQuestion`) REFERENCES `Question` (`id`);
ALTER TABLE `QcmQuestion` ADD FOREIGN KEY (`idQcm`) REFERENCES `Qcm` (`id`);
ALTER TABLE `UserAnswer` ADD FOREIGN KEY (`idAnswer`) REFERENCES `Answer` (`id`);
ALTER TABLE `UserAnswer` ADD FOREIGN KEY (`idUser`) REFERENCES `User` (`id`);
ALTER TABLE `UserAnswer` ADD FOREIGN KEY (`idQcm`) REFERENCES `Qcm` (`id`);
ALTER TABLE `Question` ADD FOREIGN KEY (`idUser`) REFERENCES `User` (`id`);
ALTER TABLE `Answer` ADD FOREIGN KEY (`idQuestion`) REFERENCES `Question` (`id`);
ALTER TABLE `Exam` ADD FOREIGN KEY (`idGroup`) REFERENCES `Group` (`id`);
ALTER TABLE `Qcm` ADD FOREIGN KEY (`idExam`) REFERENCES `Exam` (`id`);
ALTER TABLE `Qcm` ADD FOREIGN KEY (`idUser`) REFERENCES `User` (`id`);
ALTER TABLE `UserGroup` ADD FOREIGN KEY (`idUser`) REFERENCES `User` (`id`);
ALTER TABLE `UserGroup` ADD FOREIGN KEY (`idGroup`) REFERENCES `Group` (`id`);
ALTER TABLE `ExamOption` ADD FOREIGN KEY (`idExam`) REFERENCES `Exam` (`id`);
ALTER TABLE `ExamOption` ADD FOREIGN KEY (`idOption`) REFERENCES `Option` (`id`);
ALTER TABLE `Tag` ADD FOREIGN KEY (`idUser`) REFERENCES `User` (`id`);
ALTER TABLE `Group` ADD FOREIGN KEY (`idUser`) REFERENCES `User` (`id`);

