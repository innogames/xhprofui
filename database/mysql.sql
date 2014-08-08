CREATE TABLE profiling (
  id INT AUTO_INCREMENT NOT NULL,
  timestamp DATETIME NOT NULL,
  server_name VARCHAR(255) NOT NULL,
  request_uri VARCHAR(255) NOT NULL,
  request_method VARCHAR(4) NOT NULL,
  get_params LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json_array)',
  post_params LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json_array)',
  cookies_params LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json_array)',
  wall_time INT NOT NULL,
  cpu INT NOT NULL,
  memory INT NOT NULL,
  peak_memory INT NOT NULL,
  data LONGBLOB NOT NULL,
  PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8
  COLLATE utf8_unicode_ci
  ENGINE = InnoDB;