DROP TABLE IF EXISTS civicrm_event_sk;

CREATE TABLE civicrm_event_sk (
id INT NOT NULL AUTO_INCREMENT,
event_id INT NOT NULL ,
is_sk tinyint(4) default 0,
PRIMARY KEY(id)
);
