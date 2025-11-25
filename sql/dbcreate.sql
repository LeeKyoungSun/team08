-- dbcreate.sql
-- 2170045 서자영

CREATE DATABASE IF NOT EXISTS team08 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE team08;

SET FOREIGN_KEY_CHECKS=0;

-- 1. Master (선수 마스터 테이블)
DROP TABLE IF EXISTS Master;
CREATE TABLE Master (
    playerID    VARCHAR(10) NOT NULL,
    birthYear   INT,
    nameFirst   VARCHAR(50),
    nameLast    VARCHAR(50),
    bats        CHAR(1),
    throws      CHAR(1),
    PRIMARY KEY (playerID)
);

-- 2. Teams (팀 마스터 테이블)
DROP TABLE IF EXISTS Teams;
CREATE TABLE Teams (
    yearID      INT NOT NULL,
    lgID        CHAR(2) NOT NULL,
    teamID      CHAR(3) NOT NULL,
    name        VARCHAR(50),
    W           INT,
    L           INT,
    `Rank`      INT,
    PRIMARY KEY (yearID, lgID, teamID)
);

-- 3. Batting (타격 성적 테이블)
DROP TABLE IF EXISTS Batting;
CREATE TABLE Batting (
    playerID    VARCHAR(10) NOT NULL,
    yearID      INT NOT NULL,
    stint       INT NOT NULL,
    teamID      CHAR(3),
    lgID        CHAR(2),
    G           INT,
    AB          INT,
    H           INT,
    HR          INT,
    PRIMARY KEY (playerID, yearID, stint),
    FOREIGN KEY (playerID) REFERENCES Master(playerID),
    FOREIGN KEY (yearID, lgID, teamID) REFERENCES Teams(yearID, lgID, teamID)
);

-- 4. Pitching (투구 성적 테이블)
DROP TABLE IF EXISTS Pitching;
CREATE TABLE Pitching (
    playerID    VARCHAR(10) NOT NULL,
    yearID      INT NOT NULL,
    stint       INT NOT NULL,
    teamID      CHAR(3),
    lgID        CHAR(2),
    W           INT,
    L           INT,
    G           INT,
    ERA         DECIMAL(5, 2),
    PRIMARY KEY (playerID, yearID, stint),
    FOREIGN KEY (playerID) REFERENCES Master(playerID),
    FOREIGN KEY (yearID, lgID, teamID) REFERENCES Teams(yearID, lgID, teamID)
);

-- 5. Fielding (수비/포지션 테이블)
DROP TABLE IF EXISTS Fielding;
CREATE TABLE Fielding (
    playerID    VARCHAR(10) NOT NULL,
    yearID      INT NOT NULL,
    stint       INT NOT NULL,
    teamID      CHAR(3),
    lgID        CHAR(2),
    POS         VARCHAR(2) NOT NULL,
    PRIMARY KEY (playerID, yearID, stint, POS),
    FOREIGN KEY (playerID) REFERENCES Master(playerID),
    FOREIGN KEY (yearID, lgID, teamID) REFERENCES Teams(yearID, lgID, teamID)
);

-- 6. Salaries (연봉 테이블)
DROP TABLE IF EXISTS Salaries;
CREATE TABLE Salaries (
    playerID    VARCHAR(10) NOT NULL,
    yearID      INT NOT NULL,
    teamID      CHAR(3) NOT NULL,
    lgID        CHAR(2) NOT NULL,
    salary      BIGINT,
    PRIMARY KEY (playerID, yearID, teamID, lgID),
    FOREIGN KEY (playerID) REFERENCES Master(playerID),
    FOREIGN KEY (yearID, lgID, teamID) REFERENCES Teams(yearID, lgID, teamID)
);

-- 7. AllstarFull (올스타전 명단 테이블)
DROP TABLE IF EXISTS AllstarFull;
CREATE TABLE AllstarFull (
    playerID    VARCHAR(10) NOT NULL,
    yearID      INT NOT NULL,
    gameNum     INT NOT NULL,
    gameID      VARCHAR(12),
    teamID      CHAR(3),
    lgID        CHAR(2),
    startingPos INT,
    PRIMARY KEY (playerID, yearID, gameNum),
    FOREIGN KEY (playerID) REFERENCES Master(playerID),
    FOREIGN KEY (yearID, lgID, teamID) REFERENCES Teams(yearID, lgID, teamID)
);

-- 8. Users (회원 테이블)
DROP TABLE IF EXISTS Users;
CREATE TABLE Users (
    userId      VARCHAR(50) NOT NULL,
    userPW      VARCHAR(255) NOT NULL,
    userName    VARCHAR(100),
    joinDate    DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (userId)
);

-- INDEX
CREATE INDEX idx_fielding_pos ON Fielding(POS);
CREATE INDEX idx_salaries_year ON Salaries(yearID);
CREATE INDEX idx_batting_year ON Batting(yearID);
CREATE INDEX idx_pitching_year ON Pitching(yearID);
CREATE INDEX idx_master_name ON Master(nameLast, nameFirst);

SET FOREIGN_KEY_CHECKS=1;
