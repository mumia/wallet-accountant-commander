DROP DATABASE IF EXISTS events_test;
CREATE DATABASE events_test CHARACTER SET utf8 COLLATE utf8_bin;
DROP USER IF EXISTS evtstr_test;
CREATE USER IF NOT EXISTS 'evtstr_test'@'%' IDENTIFIED BY 'mynormalpw';
CREATE USER IF NOT EXISTS 'evtstr_test'@'localhost' IDENTIFIED BY 'mynormalpw';
GRANT ALL PRIVILEGES ON events_test.* TO 'evtstr_test'@'%';
GRANT ALL PRIVILEGES ON events_test.* TO 'evtstr_test'@'localhost';
FLUSH PRIVILEGES;
