CREATE TABLE Users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    email varchar(50) NOT NULL UNIQUE,
    pwd varchar(2000) NOT NULL,
    hash varchar(2000) NOT NULL,
    active INT(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (id)
);