CREATE TABLE `phpvms_aircraft` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`icao` VARCHAR( 4 ) NOT NULL ,
	`name` VARCHAR( 12 ) NOT NULL ,
	`fullname` VARCHAR( 50 ) NOT NULL ,
	`range` FLOAT NOT NULL ,
	`weight` FLOAT NOT NULL ,
	`cruise` SMALLINT NOT NULL ,
	PRIMARY KEY ( `id` ),
	UNIQUE KEY `name` (`name`)
);

CREATE TABLE `phpvms_airports` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`icao` VARCHAR( 5 ) NOT NULL ,
	`name` VARCHAR( 30 ) NOT NULL ,
	`country` VARCHAR( 50 ) NOT NULL ,
	`lat` FLOAT( 10 ) NOT NULL ,
	`lng` FLOAT( 10 ) NOT NULL ,
	PRIMARY KEY ( `id` ),
	UNIQUE KEY `icao` (`icao`)
);

CREATE TABLE `phpvms_news` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`subject` VARCHAR( 30 ) NOT NULL ,
	`body` TEXT NOT NULL ,
	`postdate` DATETIME NOT NULL ,
	`postedby` VARCHAR( 50 ) NOT NULL,
	PRIMARY KEY ( `id` )
);

CREATE TABLE `phpvms_pages` (
	`pageid` int(11) NOT NULL auto_increment,
	`pagename` varchar(30) NOT NULL default '',
	`filename` varchar(30) NOT NULL default '',
	`order` smallint(6) NOT NULL default '0',
	`postedby` varchar(50) NOT NULL default '',
	`postdate` datetime NOT NULL default '0000-00-00 00:00:00',
	`enabled` smallint(6) NOT NULL default '1',
	PRIMARY KEY  (`pageid`),
	UNIQUE KEY `pagename` (`pagename`)
);

CREATE TABLE `phpvms_users` (
	`userid` int(11) NOT NULL auto_increment,
	`firstname` varchar(25) NOT NULL default '',
	`lastname` varchar(25) NOT NULL default '',
	`email` varchar(32) NOT NULL default '',
	`location` varchar(32) NOT NULL default '',
	`password` varchar(32) NOT NULL default '',
	`salt` varchar(32) NOT NULL default '',
	`lastlogin` date NOT NULL default '0000-00-00',
	`totalflights` int(11) NOT NULL default '0',
	`totalhours` float NOT NULL default '0',
	`confirmed` enum('y','n') NOT NULL default 'n',
	`retired` enum('y','n') NOT NULL default 'y',
	PRIMARY KEY  (`userid`),
	UNIQUE KEY `email` (`email`),
	INDEX (`email`)
);

CREATE TABLE `phpvms_pireps` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`userid` INT NOT NULL ,
	`airline` VARCHAR( 3 ) NOT NULL ,
	`flightnum` VARCHAR( 7 ) NOT NULL ,
	`depicao` VARCHAR( 4 ) NOT NULL ,
	`arricao` VARCHAR( 4 ) NOT NULL ,
	`deptime` VARCHAR( 15 ) NOT NULL ,
	`arrtime` VARCHAR( 15 ) NOT NULL ,
	`flighttime` SMALLINT NOT NULL ,
	`distance` SMALLINT NOT NULL ,
	`submitdate` DATETIME NOT NULL ,
	`accepted` SMALLINT NOT NULL ,
	PRIMARY KEY ( `id` ),
	FOREIGN KEY (`userid`) REFERENCES phpvms_users(`userid`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`flightnum`) REFERENCES phpvms_schedules(`flightnum`) ON UPDATE CASCADE
);

CREATE TABLE `phpvms_pirepcomments` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`pirepid` INT NOT NULL ,
	`commenter` VARCHAR( 20 ) NOT NULL ,
	`comment` TEXT NOT NULL ,
	`postdate` DATETIME NOT NULL ,
	PRIMARY KEY ( `id` ),
	FOREIGN KEY (`pirepid`) REFERENCES phpvms_pireps(`id`) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE `phpvms_schedules` (
	`id` int(11) NOT NULL auto_increment,
	`code` varchar(3) NOT NULL default '',
	`flightnum` varchar(10) NOT NULL default '0',
	`leg` smallint(6) NOT NULL default '1',
	`depicao` varchar(4) NOT NULL default '',
	`arricao` varchar(4) NOT NULL default '',
	`route` text NOT NULL,
	`aircraft` varchar(12) NOT NULL default '',
	`distance` float NOT NULL default '0',
	`deptime` varchar(15) NOT NULL default '',
	`arrtime` varchar(15) NOT NULL default '',
	`flighttime` int(11) NOT NULL default '0',
	`timesflown` int(11) NOT NULL default '0',
	PRIMARY KEY  (`id`),
	INDEX `depicao_arricao` (`depicao`, `arricao`),
	FOREIGN KEY (`aircraft`) REFERENCES phpvms_aircraft(`name`) ON UPDATE CASCADE
);

CREATE TABLE `phpvms_customfields` (
	`fieldid` INT NOT NULL AUTO_INCREMENT ,
	`fieldname` VARCHAR( 25 ) NOT NULL ,
	`type` VARCHAR( 25 ) NOT NULL DEFAULT 'text',
	`public` ENUM( 'y', 'n' ) NOT NULL ,
	`showonregister` ENUM( 'y', 'n' ) NOT NULL ,
	PRIMARY KEY ( `fieldid` ),
	UNIQUE KEY `fieldname` (`fieldname`)
);

CREATE TABLE `phpvms_fieldvalues` (
	`id` INT NOT NULL AUTO_INCREMENT ,
	`fieldid` INT NOT NULL ,
	`userid` INT NOT NULL ,
	`value` VARCHAR( 25 ) NOT NULL ,
	PRIMARY KEY ( `id` ),
	FOREIGN KEY (`fieldid`) REFERENCES phpvms_customfields(`fieldid`) ON DELETE CASCADE,
	FOREIGN KEY (`userid`) REFERENCES phpvms_users(`userid`) ON DELETE CASCADE
);


CREATE TABLE `phpvms_groups` (
	`groupid` INT NOT NULL AUTO_INCREMENT ,
	`name` VARCHAR( 25 ) NOT NULL ,
	PRIMARY KEY ( `groupid` ),
	UNIQUE KEY `name` (`name`)
);

INSERT INTO `phpvms_groups` (`name`) VALUES ('Administrators');
INSERT INTO `phpvms_groups` (`name`) VALUES ('Active Pilots');

CREATE TABLE `phpvms_groupmembers` (
	`id` int(11) NOT NULL auto_increment,
	`groupid` int(11) NOT NULL default '0',
	`userid` int(11) NOT NULL default '0',
	PRIMARY KEY  (`id`),
	FOREIGN KEY (`groupid`) REFERENCES phpvms_groups(`groupid`) ON DELETE CASCADE,
	FOREIGN KEY (`userid`) REFERENCES phpvms_users(`userid`) ON DELETE CASCADE
);

CREATE TABLE `phpvms_settings` (
	`id` int(11) NOT NULL auto_increment,
	`friendlyname` varchar(25) NOT NULL default '',
	`name` varchar(25) NOT NULL default '',
	`value` varchar(150) NOT NULL default '',
	`descrip` varchar(150) NOT NULL default '',
	`core` enum('t','f') NOT NULL default 'f',
	PRIMARY KEY  (`id`),
	UNIQUE KEY `name` (`name`)
);

INSERT INTO `phpvms_settings` VALUES(1, 'phpVMS Version', 'PHPVMS_VERSION', '0.0.1', '', 't');
INSERT INTO `phpvms_settings` VALUES(2, 'Virtual Airline Name', 'SITE_NAME', 'PHPVMS', 'The name of your site. This will show up in the browser title bar.', 't');
INSERT INTO `phpvms_settings` VALUES(3, 'Webmaster Email Address', 'ADMIN_EMAIL', '', 'This is the email address that email will get sent to/from', 't');
INSERT INTO `phpvms_settings` VALUES(4, 'Pilot ID Prefix', 'PID_PREFIX', 'VMS', 'This is the prefix for the pilot ID. For example, DVA.', 't');
INSERT INTO `phpvms_settings` VALUES(5, 'Date Format', 'DATE_FORMAT', 'm/d/Y', 'This is the date format to be used around the site.', 't');
INSERT INTO `phpvms_settings` VALUES(6, 'Website URL', 'SITE_URL', 'http://www.phpvms.net/test', 'This is the URL to the "base" of your site. Links are based off of this', 't');
INSERT INTO `phpvms_settings` VALUES(7, 'Current Skin', 'CURRENT_SKIN', 'crystal', 'Available skins', 't');
INSERT INTO `phpvms_settings` VALUES(8, 'Friendly URLs', 'FRIENDLY_URLS', 'false', 'Enable URL rewriting for clean URLs. MUST have mod_rewrite available, and .htaccess enabled', 't');
INSERT INTO `phpvms_settings` VALUES(9, 'Cache Templates', 'TEMPLATE_USE_CACHE', 'false', 'Cache database queries. Can alleviate alot of DB load on high-traffic sites', 't');
INSERT INTO `phpvms_settings` VALUES(10, 'Template Cache Timeout', 'TEMPLATE_CACHE_EXPIRE', '24', 'Number of hours to automatically refresh the display cache', 't');
INSERT INTO `phpvms_settings` VALUES(11, 'Cache Database Queries', 'DBASE_USE_CACHE', 'false', 'Cache database queries. Can alleviate alot of DB load on high-traffic sites', 't');
INSERT INTO `phpvms_settings` VALUES(12, 'Database Cache Timeout', 'DBASE_CACHE_TIMEOUT', '24', 'Number of hours to expire the cache in', 't');
INSERT INTO `phpvms_settings` VALUES(13, 'Cache Path', 'CACHE_PATH', '/home/nssliven/public_html/phpvms.net/test/core/cache', 'Absolute path to the database cache', 't');
INSERT INTO `phpvms_settings` VALUES(14, 'Default User Group', 'DEFAULT_GROUP', 'GeneralUsers', 'This is the default group if they are not explicitly denied', 't');
