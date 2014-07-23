CREATE TABLE profiling (
    id INT NOT NULL,
    timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
    server_name VARCHAR(255) NOT NULL,
    request_uri VARCHAR(255) NOT NULL,
    request_method VARCHAR(4) NOT NULL,
    get_params TEXT DEFAULT NULL,
    post_params TEXT DEFAULT NULL,
    cookies_params TEXT DEFAULT NULL,
    wall_time INT NOT NULL,
    cpu INT NOT NULL,
    memory INT NOT NULL,
    peak_memory INT NOT NULL,
    data BYTEA NOT NULL,
    PRIMARY KEY(id)
);

COMMENT ON COLUMN profiling.get_params IS '(DC2Type:json_array)';
COMMENT ON COLUMN profiling.post_params IS '(DC2Type:json_array)';
COMMENT ON COLUMN profiling.cookies_params IS '(DC2Type:json_array)';

CREATE SEQUENCE profiling_id_seq INCREMENT BY 1 MINVALUE 1 START 1;