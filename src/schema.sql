DROP TABLE IF EXISTS loads;
DROP TABLE IF EXISTS configuration;
DROP TABLE IF EXISTS heartbeat;
DROP TABLE IF EXISTS associates;
DROP TABLE IF EXISTS groups;
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

CREATE TABLE groups (
	group_name VARCHAR(255) NOT NULL PRIMARY KEY,
	group_description TEXT
) ENGINE=InnoDB;
# Add default group and description
INSERT INTO groups (group_name, group_description) VALUES('default', 'default group');

CREATE TABLE associates (
	mac CHAR(12) NOT NULL PRIMARY KEY,
	group_name VARCHAR(255) NOT NULL,
	FOREIGN KEY (mac) REFERENCES ap(mac) ON DELETE CASCADE,
	FOREIGN KEY (group_name) REFERENCES groups(group_name) ON DELETE CASCADE
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
	command VARCHAR(255),
	FOREIGN KEY (module_name) REFERENCES modules(module_name) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE module_packages (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	module_name VARCHAR(255) NOT NULL,
	package_name VARCHAR(255),
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
	FOREIGN KEY (group_name) REFERENCES groups(group_name) ON DELETE CASCADE,
	FOREIGN KEY (module_name) REFERENCES modules(module_name) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
