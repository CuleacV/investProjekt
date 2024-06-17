DROP DATABASE IF EXISTS investUser;
CREATE DATABASE IF NOT EXISTS investUser;
USE investUser;

CREATE TABLE IF NOT EXISTS investuser (
                                          id INT PRIMARY KEY AUTO_INCREMENT,
                                          VorNachname VARCHAR(255) NOT NULL,
                                          userName VARCHAR(255) NOT NULL,
                                          email VARCHAR(255) NOT NULL,
                                          pwhash VARCHAR(255) NOT NULL
);
