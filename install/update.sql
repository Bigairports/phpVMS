CREATE TABLE `phpvms_awards` (
	`awardid` INT NOT NULL AUTO_INCREMENT ,
	`name` VARCHAR( 25 ) NOT NULL ,
	`descrip` VARCHAR( 100 ) NOT NULL ,
	`image` TEXT NOT NULL ,
PRIMARY KEY ( `awardid` )
) ENGINE = MYISAM;

CREATE TABLE `phpvms_awardsgranted` (
`id` INT NOT NULL AUTO_INCREMENT ,
`awardid` INT NOT NULL ,
`pilotid` INT NOT NULL ,
`dateissued` DATETIME NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM;