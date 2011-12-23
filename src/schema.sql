DROP TABLE IF EXISTS loads;
DROP TABLE IF EXISTS configuration;
DROP TABLE IF EXISTS heartbeat;
DROP TABLE IF EXISTS associates;
DROP TABLE IF EXISTS ap_groups;
DROP TABLE IF EXISTS administrator;
DROP TABLE IF EXISTS module_files;
DROP TABLE IF EXISTS module_commands;
DROP TABLE IF EXISTS module_packages;
DROP TABLE IF EXISTS modules;
DROP TABLE IF EXISTS ap;

CREATE TABLE ap (
	mac CHAR(12) NOT NULL PRIMARY KEY,
	ap_key VARCHAR(255) NOT NULL UNIQUE,
	is_active TINYINT NOT NULL DEFAULT 0,
	ap_name VARCHAR(255),
	notes TEXT,
	location TEXT
) ENGINE=InnoDB;

CREATE TABLE ap_groups (
	group_name VARCHAR(255) NOT NULL PRIMARY KEY,
	group_description TEXT
) ENGINE=InnoDB;
# Add default group and description
INSERT INTO ap_groups (group_name, group_description) VALUES('default', 'default group');

CREATE TABLE associates (
	mac CHAR(12) NOT NULL PRIMARY KEY,
	group_name VARCHAR(255) NOT NULL,
	FOREIGN KEY (mac) REFERENCES ap(mac) ON DELETE CASCADE,
	FOREIGN KEY (group_name) REFERENCES ap_groups(group_name) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE administrator (
	user_name VARCHAR(255) NOT NULL PRIMARY KEY,
	password VARCHAR(255) NOT NULL,
	real_name VARCHAR(255),
	description TEXT
) ENGINE=InnoDB;

CREATE TABLE heartbeat (
	mac CHAR(12) NOT NULL PRIMARY KEY,
	uptime CHAR(10),
	ap_version VARCHAR(255),
	time_stamp VARCHAR(20),
	alarm_status VARCHAR(255),
	FOREIGN KEY (mac) REFERENCES ap(mac) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE modules (
	module_name VARCHAR(255) NOT NULL PRIMARY KEY,
	module_version INT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE module_files (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	module_name VARCHAR(255) NOT NULL,
	remote_file VARCHAR(255) NOT NULL,
	local_file VARCHAR(255) NOT NULL,
	FOREIGN KEY (module_name) REFERENCES modules(module_name) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE module_commands (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	module_name VARCHAR(255) NOT NULL,
	command VARCHAR(255) NOT NULL,
	FOREIGN KEY (module_name) REFERENCES modules(module_name) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE module_packages (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	module_name VARCHAR(255) NOT NULL,
	package_name VARCHAR(255) NOT NULL,
	FOREIGN KEY (module_name) REFERENCES modules(module_name) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE configuration (
	mac CHAR(12) NOT NULL PRIMARY KEY,
	current_version INT NOT NULL DEFAULT 0,
	run_command VARCHAR(255),
	FOREIGN KEY (mac) REFERENCES ap(mac) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE loads (
	group_name VARCHAR(255) NOT NULL,
	module_name VARCHAR(255) NOT NULL,
	FOREIGN KEY (group_name) REFERENCES ap_groups(group_name) ON DELETE CASCADE,
	FOREIGN KEY (module_name) REFERENCES modules(module_name) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ----------------------------------- --
-- Added to support logins to the administrative
-- interface. These tables are from the Auth Spark
-- See: https://github.com/adamgriffiths/ag-auth/

--
-- Table structure for table `ci_sessions`
-- Keep session data in the database
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL default '0',
  `ip_address` varchar(16) NOT NULL default '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL default '0',
  `user_data` text NOT NULL,
  PRIMARY KEY  (`session_id`)
) ENGINE=InnoDB;

--
-- Table structure for table `groups`
-- Administrator Groups
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL,
  `title` varchar(20) NOT NULL default '',
  `description` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

--
-- Table structure for table `users`
-- Administrator Users
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL default '100',
  `token` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3;
