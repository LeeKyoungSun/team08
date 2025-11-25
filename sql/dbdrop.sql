-- dbdrop.sql
-- 2170045 서자영

USE team08;
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS AllstarFull, Batting, Fielding, Pitching, Salaries, Teams, Master;
SET FOREIGN_KEY_CHECKS=1;
