-- dbinsert.sql
-- 2170045 서자영

USE team08;

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;

-- 1. Master 테이블 로드
TRUNCATE TABLE Master;
LOAD DATA LOCAL INFILE '../source/Master.csv'
INTO TABLE Master
FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 ROWS
(playerID, birthYear, @birthMonth, @birthDay, @birthCountry, @birthState, @birthCity, @deathYear, @deathMonth, @deathDay, @deathCountry, @deathState, @deathCity, nameFirst, nameLast, @nameGiven, @weight, @height, bats, throws, @debut, @finalGame, @retroID, @bbrefID);

-- 2. Teams 테이블 로드
TRUNCATE TABLE Teams;
LOAD DATA LOCAL INFILE '../source/Teams.csv'
INTO TABLE Teams
FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 ROWS
(yearID, lgID, teamID, @franchID, @divID, `Rank`, @G, @Ghome, W, L, @DivWin, @WCWin, @LgWin, @WSWin, @R, @AB, @H, @2B, @3B, @HR, @BB, @SO, @SB, @CS, @HBP, @SF, @RA, @ER, @ERA, @CG, @SHO, @SV, @IPouts, @HA, @HRA, @BBA, @SOA, @E, @DP, @FP, name, @park, @attendance, @BPF, @PPF, @teamIDBR, @teamIDlahman45, @teamIDretro);

-- 3. Batting 테이블 로드
TRUNCATE TABLE Batting;
LOAD DATA LOCAL INFILE '../source/Batting.csv'
INTO TABLE Batting
FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 ROWS
(playerID, yearID, stint, teamID, lgID, G, AB, @R, H, @2B, @3B, HR, @RBI, @SB, @CS, @BB, @SO, @IBB, @HBP, @SH, @SF, @GIDP);

-- 4. Pitching 테이블 로드
TRUNCATE TABLE Pitching;
LOAD DATA LOCAL INFILE '../source/Pitching.csv'
INTO TABLE Pitching
FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 ROWS
(playerID, yearID, stint, teamID, lgID, W, L, G, @GS, @CG, @SHO, @SV, @IPouts, @H, @ER, @HR, @BB, @SO, @BAOpp, ERA, @IBB, @WP, @HBP, @BK, @BFP, @GF, @R, @SH, @SF, @GIDP);

-- 5. Fielding 테이블 로드
TRUNCATE TABLE Fielding;
LOAD DATA LOCAL INFILE '../source/Fielding.csv'
INTO TABLE Fielding
FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 ROWS
(playerID, yearID, stint, teamID, lgID, POS, @G, @GS, @InnOuts, @PO, @A, @E, @DP, @PB, @WP, @SB, @CS, @ZR);

-- 6. Salaries 테이블 로드
TRUNCATE TABLE Salaries;
LOAD DATA LOCAL INFILE '../source/Salaries.csv'
INTO TABLE Salaries
FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 ROWS
(@yearID, @teamID, @lgID, @playerID, @salary)
SET 
    playerID = @playerID,
    yearID = @yearID,
    teamID = @teamID,
    lgID = @lgID,
    salary = @salary;

-- 7. AllstarFull 테이블 로드
TRUNCATE TABLE AllstarFull;
LOAD DATA LOCAL INFILE '../source/AllstarFull.csv'
INTO TABLE AllstarFull
FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 ROWS
(playerID, yearID, gameNum, gameID, teamID, lgID, @GP, startingPos);


COMMIT;
SET FOREIGN_KEY_CHECKS=1;
