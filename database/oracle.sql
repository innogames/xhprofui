CREATE TABLE profiling (
  id NUMBER(10) NOT NULL,
  timestamp TIMESTAMP(0) NOT NULL,
  server_name VARCHAR2(255) NOT NULL,
  request_uri VARCHAR2(255) NOT NULL,
  request_method VARCHAR2(4) NOT NULL,
  get_params CLOB DEFAULT NULL,
  post_params CLOB DEFAULT NULL,
  cookies_params CLOB DEFAULT NULL,
  wall_time NUMBER(10) NOT NULL,
  cpu NUMBER(10) NOT NULL,
  memory NUMBER(10) NOT NULL,
  peak_memory NUMBER(10) NOT NULL,
  data BLOB NOT NULL,
  PRIMARY KEY(id)
);
COMMENT ON COLUMN profiling.get_params IS '(DC2Type:json_array)';
COMMENT ON COLUMN profiling.post_params IS '(DC2Type:json_array)';
COMMENT ON COLUMN profiling.cookies_params IS '(DC2Type:json_array)';
CREATE SEQUENCE profiling_id_seq START WITH 1 MINVALUE 1 INCREMENT BY 1;