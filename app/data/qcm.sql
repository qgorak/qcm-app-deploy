CREATE DATABASE `qcm`;
USE `qcm`;
CREATE TABLE `answer` (`id` int(11) NOT NULL,`caption` varchar(42) DEFAULT NULL,`score` float DEFAULT 0,`idQuestion` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `exam` (`id` int(11) NOT NULL,`dated` datetime DEFAULT CURRENT_TIMESTAMP,`datef` datetime DEFAULT CURRENT_TIMESTAMP,`status` varchar(42) DEFAULT NULL,`idGroup` int(11) NOT NULL,`idQcm` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `examoption` (`idExam` int(11) NOT NULL,`idOption` int(11) NOT NULL,`value` varchar(42) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `group` (`id` int(11) NOT NULL,`name` varchar(42) DEFAULT NULL,`description` text DEFAULT NULL,`keyCode` varchar(255) NOT NULL,`idUser` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `option` (`id` int(11) NOT NULL,`key` varchar(42) DEFAULT NULL,`description` text DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `qcm` (`id` int(11) NOT NULL,`name` varchar(42) DEFAULT NULL,`description` varchar(42) DEFAULT NULL,`cdate` datetime DEFAULT CURRENT_TIMESTAMP,`idUser` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `question` (`id` int(11) NOT NULL,`caption` varchar(42) DEFAULT NULL,`ckcontent` TEXT DEFAULT NULL,`points` int(11) DEFAULT 0,`idTypeq` int(11) DEFAULT NULL, `idUser` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `tag` (`id` int(11) NOT NULL,`name` varchar(42) DEFAULT NULL,`color` varchar(42) DEFAULT NULL,`idUser` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `typeq` (`id` int(11) NOT NULL,`caption` varchar(42) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `typeq` (`id`, `caption`) VALUES
(1, 'choix multiple'),
(2, 'ouverte'),
(3, 'courte'),
(4, 'code');



CREATE TABLE `user` (`id` int(11) NOT NULL,`password` varchar(42) DEFAULT NULL,`firstname` varchar(42) DEFAULT NULL,`lastname` varchar(42) DEFAULT NULL,`email` varchar(255) DEFAULT NULL,`language` varchar(32) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `useranswer` (`idUser` int(11) NOT NULL,`idAnswer` int(11) NOT NULL,`idQcm` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `usergroup` (`idGroup` int(11) NOT NULL,`idUser` int(11) NOT NULL,`status` varchar(255) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `qcmquestion` (`idQuestion` int(11) NOT NULL,`idQcm` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `questiontag` (`idQuestion` int(11) NOT NULL,`idTag` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `answer` ADD PRIMARY KEY (`id`);
ALTER TABLE `answer` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `answer` ADD  KEY (`idQuestion`);
ALTER TABLE `exam` ADD PRIMARY KEY (`id`);
ALTER TABLE `exam` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `exam` ADD  KEY (`idGroup`);
ALTER TABLE `exam` ADD  KEY (`idQcm`);
ALTER TABLE `examoption` ADD PRIMARY KEY (`idExam`,`idOption`);
ALTER TABLE `examoption` ADD  KEY (`idExam`);
ALTER TABLE `examoption` ADD  KEY (`idOption`);
ALTER TABLE `group` ADD PRIMARY KEY (`id`);
ALTER TABLE `group` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `group` ADD  KEY (`idUser`);
ALTER TABLE `option` ADD PRIMARY KEY (`id`);
ALTER TABLE `option` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `qcm` ADD PRIMARY KEY (`id`);
ALTER TABLE `qcm` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `qcm` ADD  KEY (`idUser`);
ALTER TABLE `question` ADD PRIMARY KEY (`id`);
ALTER TABLE `question` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `question` ADD  KEY (`idUser`);
ALTER TABLE `question` ADD  KEY (`idTypeq`);
ALTER TABLE `tag` ADD PRIMARY KEY (`id`);
ALTER TABLE `tag` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `tag` ADD  KEY (`idUser`);
ALTER TABLE `typeq` ADD PRIMARY KEY (`id`);
ALTER TABLE `typeq` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT=1;
ALTER TABLE `user` ADD PRIMARY KEY (`id`);
ALTER TABLE `user` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `useranswer` ADD PRIMARY KEY (`idUser`,`idAnswer`,`idQcm`);
ALTER TABLE `useranswer` ADD  KEY (`idAnswer`);
ALTER TABLE `useranswer` ADD  KEY (`idQcm`);
ALTER TABLE `useranswer` ADD  KEY (`idUser`);
ALTER TABLE `usergroup` ADD PRIMARY KEY (`idGroup`,`idUser`);
ALTER TABLE `usergroup` ADD  KEY (`idGroup`);
ALTER TABLE `usergroup` ADD  KEY (`idUser`);
ALTER TABLE `qcmquestion` ADD PRIMARY KEY (`idQuestion`,`idQcm`);
ALTER TABLE `qcmquestion` ADD  KEY (`idQuestion`);
ALTER TABLE `qcmquestion` ADD  KEY (`idQcm`);
ALTER TABLE `questiontag` ADD PRIMARY KEY (`idQuestion`,`idTag`);
ALTER TABLE `questiontag` ADD  KEY (`idQuestion`);
ALTER TABLE `questiontag` ADD  KEY (`idTag`);

ALTER TABLE `answer` ADD CONSTRAINT `fk_answer_question` FOREIGN KEY (`idQuestion`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE `exam` ADD CONSTRAINT `fk_exam_group` FOREIGN KEY (`idGroup`) REFERENCES `group` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE `exam` ADD CONSTRAINT `fk_exam_qcm` FOREIGN KEY (`idQcm`) REFERENCES `qcm` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE `examoption` ADD CONSTRAINT `fk_examoption_exam` FOREIGN KEY (`idExam`) REFERENCES `exam` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE `examoption` ADD CONSTRAINT `fk_examoption_option` FOREIGN KEY (`idOption`) REFERENCES `option` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE `group` ADD CONSTRAINT `fk_group_user` FOREIGN KEY (`idUser`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE `qcm` ADD CONSTRAINT `fk_qcm_user` FOREIGN KEY (`idUser`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE `question` ADD CONSTRAINT `fk_question_user` FOREIGN KEY (`idUser`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE `question` ADD CONSTRAINT `fk_question_typeq` FOREIGN KEY (`idTypeq`) REFERENCES `typeq` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE `tag` ADD CONSTRAINT `fk_tag_user` FOREIGN KEY (`idUser`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE `useranswer` ADD CONSTRAINT `fk_useranswer_answer` FOREIGN KEY (`idAnswer`) REFERENCES `answer` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE `useranswer` ADD CONSTRAINT `fk_useranswer_qcm` FOREIGN KEY (`idQcm`) REFERENCES `qcm` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE `useranswer` ADD CONSTRAINT `fk_useranswer_user` FOREIGN KEY (`idUser`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE `usergroup` ADD CONSTRAINT `fk_usergroup_group` FOREIGN KEY (`idGroup`) REFERENCES `group` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE `usergroup` ADD CONSTRAINT `fk_usergroup_user` FOREIGN KEY (`idUser`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE `qcmquestion` ADD CONSTRAINT `fk_qcmquestion_question` FOREIGN KEY (`idQuestion`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE `qcmquestion` ADD CONSTRAINT `fk_qcmquestion_qcm` FOREIGN KEY (`idQcm`) REFERENCES `qcm` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
