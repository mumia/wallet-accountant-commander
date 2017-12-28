CREATE DATABASE events CHARACTER SET utf8 COLLATE utf8_bin;
CREATE USER 'evtstr'@'%' IDENTIFIED BY 'mynormalpw';
CREATE USER 'evtstr'@'localhost' IDENTIFIED BY 'mynormalpw';
GRANT ALL PRIVILEGES ON events.* TO 'evtstr'@'%';
FLUSH PRIVILEGES;
