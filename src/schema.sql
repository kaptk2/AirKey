DROP TABLE IF EXISTS ap;
CREATE TABLE ap (
	mac CHAR(12) NOT NULL PRIMARY KEY,
	ap_key VARCHAR(255) NOT NULL UNIQUE,
	is_active TINYINT NOT NULL DEFAULT 0,
	ap_name VARCHAR(255),
	notes TEXT,
	location TEXT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS groups;
CREATE TABLE groups (
	group_name VARCHAR(255) NOT NULL PRIMARY KEY,
	group_description TEXT
) ENGINE=InnoDB;
# Add default group and description
INSERT INTO groups (group_name, group_description) VALUES('default', 'default group');

DROP TABLE IF EXISTS associates;
CREATE TABLE associates (
	mac CHAR(12) NOT NULL PRIMARY KEY,
	group_name VARCHAR(255) NOT NULL,
	FOREIGN KEY (mac) REFERENCES ap(mac) ON DELETE CASCADE,
	FOREIGN KEY (group_name) REFERENCES groups(group_name) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS administrator;
CREATE TABLE administrator (
	user_name VARCHAR(255) NOT NULL PRIMARY KEY,
	password VARCHAR(255) NOT NULL,
	real_name VARCHAR(255),
	description TEXT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS heartbeat;
CREATE TABLE heartbeat (
	mac CHAR(12) NOT NULL PRIMARY KEY,
	uptime CHAR(10),
	ap_version VARCHAR(255),
	time_stamp VARCHAR(20),
	FOREIGN KEY (mac) REFERENCES ap(mac) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS module_uses;
CREATE TABLE module_uses (
	mac CHAR(12) NOT NULL PRIMARY KEY,
	module_name VARCHAR(255) NOT NULL UNIQUE,
	module_version INT NOT NULL,
	FOREIGN KEY (mac) REFERENCES ap(mac) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS module_files;
CREATE TABLE module_files (
	module_name VARCHAR(255) NOT NULL PRIMARY KEY,
	remote_file VARCHAR(255) NOT NULL,
	local_file VARCHAR(255) NOT NULL,
	FOREIGN KEY (module_name) REFERENCES module_uses(module_name) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS module_commands;
CREATE TABLE module_commands (
	module_name VARCHAR(255) NOT NULL PRIMARY KEY,
	command VARCHAR(255),
	package_name VARCHAR(255),
	FOREIGN KEY (module_name) REFERENCES module_uses(module_name) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS configuration;
CREATE TABLE configuration (
	mac CHAR(12) NOT NULL PRIMARY KEY,
	current_version INT NOT NULL DEFAULT 0,
	run_command VARCHAR(255),
	FOREIGN KEY (mac) REFERENCES ap(mac) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS contains;
CREATE TABLE contains (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	mac CHAR(12) NOT NULL,
	module_name VARCHAR(255) NOT NULL,
	FOREIGN KEY (module_name) REFERENCES module_uses(module_name) ON DELETE CASCADE,
	FOREIGN KEY (mac) REFERENCES configuration(mac) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS loads;
CREATE TABLE loads (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	mac CHAR(12) NOT NULL,
	group_name VARCHAR(255) NOT NULL,
	FOREIGN KEY (mac) REFERENCES configuration(mac) ON DELETE CASCADE,
	FOREIGN KEY (group_name) REFERENCES groups(group_name) ON DELETE CASCADE
) ENGINE=InnoDB;
