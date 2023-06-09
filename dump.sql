-- database
CREATE DATABASE IF NOT EXISTS mydatabase;

USE mydatabase;

-- user
CREATE USER 'mydatabase_user'@'localhost' IDENTIFIED BY 'X2eLjSPq16uzN0BPo';

GRANT SELECT, INSERT, UPDATE, DELETE ON mydatabase.* 
   TO 'mydatabase_user'@'localhost';

FLUSH PRIVILEGES;

-- tables
CREATE OR REPLACE TABLE account
(
    guid VARCHAR(23)  NOT NULL,
    pwd  VARCHAR(128) NOT NULL,
    salt VARCHAR(16)  NOT NULL
);

ALTER TABLE account
    ADD PRIMARY KEY (guid);

CREATE OR REPLACE TABLE accountattempts
(
    guid       VARCHAR(23)                           NOT NULL,
    attempt_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP() NOT NULL ON UPDATE CURRENT_TIMESTAMP()
);

CREATE OR REPLACE TABLE accountauthorization
(
    guid        VARCHAR(23) NOT NULL,
    web_service TEXT        NOT NULL
);

ALTER TABLE accountauthorization
    ADD PRIMARY KEY (guid);

CREATE OR REPLACE TABLE accountotp
(
    guid     VARCHAR(23)                           NOT NULL,
    otp      TEXT                                  NOT NULL,
    validity TIMESTAMP DEFAULT CURRENT_TIMESTAMP() NOT NULL ON UPDATE CURRENT_TIMESTAMP()
);

ALTER TABLE accountotp
    ADD PRIMARY KEY (guid);

CREATE OR REPLACE TABLE accounttmp
(
    guid VARCHAR(23)  NOT NULL,
    pwd  VARCHAR(128) NOT NULL,
    salt VARCHAR(16)  NOT NULL
);

ALTER TABLE accounttmp
    ADD PRIMARY KEY (guid);

CREATE OR REPLACE TABLE publicauthorization
(
    id          INT AUTO_INCREMENT
        PRIMARY KEY,
    web_service TEXT NOT NULL
);

CREATE OR REPLACE TABLE session
(
    id       INT AUTO_INCREMENT
        PRIMARY KEY,
    guid     VARCHAR(23)                           NOT NULL,
    token    VARCHAR(128)                          NOT NULL,
    salt     VARCHAR(16)                           NOT NULL,
    validity TIMESTAMP DEFAULT CURRENT_TIMESTAMP() NOT NULL ON UPDATE CURRENT_TIMESTAMP()
);

CREATE OR REPLACE TABLE user
(
    guid  VARCHAR(23)  NOT NULL,
    email VARCHAR(128) NOT NULL
);

-- constraints
ALTER TABLE user
    ADD PRIMARY KEY (guid);

ALTER TABLE account
    ADD CONSTRAINT fk_guid
        FOREIGN KEY (guid) REFERENCES user (guid);

ALTER TABLE accountattempts
    ADD CONSTRAINT accountattempts_user_guid_fk
        FOREIGN KEY (guid) REFERENCES user (guid);

ALTER TABLE accountauthorization
    ADD CONSTRAINT fk_guid_4
        FOREIGN KEY (guid) REFERENCES user (guid);

ALTER TABLE accountotp
    ADD CONSTRAINT fk_guid_2
        FOREIGN KEY (guid) REFERENCES user (guid);

ALTER TABLE accounttmp
    ADD CONSTRAINT fk_guid_1
        FOREIGN KEY (guid) REFERENCES user (guid);

ALTER TABLE session
    ADD CONSTRAINT session_user_guid_fk
        FOREIGN KEY (guid) REFERENCES user (guid);

ALTER TABLE user
    ADD CONSTRAINT email
        UNIQUE (email);
