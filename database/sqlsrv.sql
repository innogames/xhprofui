CREATE TABLE profiling (
  id INT IDENTITY NOT NULL,
  timestamp DATETIME2(6) NOT NULL,
  server_name NVARCHAR(255) NOT NULL,
  request_uri NVARCHAR(255) NOT NULL,
  request_method NVARCHAR(4) NOT NULL,
  get_params VARCHAR(MAX),
  post_params VARCHAR(MAX),
  cookies_params VARCHAR(MAX),
  wall_time INT NOT NULL,
  cpu INT NOT NULL,
  memory INT NOT NULL,
  peak_memory INT NOT NULL,
  data VARBINARY(MAX) NOT NULL,
  PRIMARY KEY (id)
);