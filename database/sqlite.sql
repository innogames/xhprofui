CREATE TABLE profiling (
  id INTEGER NOT NULL,
  timestamp DATETIME NOT NULL,
  server_name VARCHAR(255) NOT NULL,
  request_uri VARCHAR(255) NOT NULL,
  request_method VARCHAR(4) NOT NULL,
  get_params CLOB DEFAULT NULL,
  post_params CLOB DEFAULT NULL,
  cookies_params CLOB DEFAULT NULL,
  wall_time INTEGER NOT NULL,
  cpu INTEGER NOT NULL,
  memory INTEGER NOT NULL,
  peak_memory INTEGER NOT NULL,
  data BLOB NOT NULL,
  PRIMARY KEY(id)
);