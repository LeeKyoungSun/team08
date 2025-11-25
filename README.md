# ⚾ Team08 Baseball Analytics (칼퇴기원) ⚾

**Kaggle Baseball Databank**의 오픈소스 데이터를 활용한 **야구 빅데이터 분석 웹 플랫폼**입니다.
1871년부터 2015년까지의 방대한 메이저리그 데이터를 기반으로, 단순한 기록 조회를 넘어 **OLAP, Window Functions, Ranking** 등 고급 SQL 기법을 적용하여 다차원적인 분석 정보를 제공합니다.

-----

## 1\. 프로젝트 개요 및 팀원 (Team Info)

### 👥 팀원 및 역할 (Team Members)

| 이름 | 학번 | 담당 역할 (Role) | 주요 업무 |
|:---:|:---:|:---:|:---|
| **이경선** | 2271107 | BE / Leader | 특정 선수 성적 추이(Trend), 경기별 출전 명단 및 포지션 분포 분석 |
| **이유진** | 2176279 | FE | UX/UI 개발 및 디자인, 프론트엔드-백엔드 연동, 차트 시각화 |
| **서자영** | 2170045 | BE / DB | DB 스키마 구축, 랭킹 분석(Ranking), 회원 관리 시스템(Auth), 메인 아키텍처 |
| **주원교** | 2271103 | BE | 포지션별 선수 성적 비교(Stats), 리그/팀별 연봉 비교(OLAP) |

### 🛠 기술 스택 (Tech Stack)

  * **Frontend:** HTML5, CSS3, JavaScript (Chart.js, AJAX)
  * **Backend:** PHP (Native)
  * **Database:** MySQL (Star Schema, Advanced SQL)
  * **Server:** Apache (XAMPP)
  * **Environment:** Visual Studio Code, IntelliJ, Git/GitHub

-----

## 2\. 주요 기능 및 구현 로직 (Key Features)

이 프로젝트는 **고급 SQL 쿼리**를 웹 서비스에 실제로 적용하는 데 중점을 두었습니다.

### 📊 고급 분석 (Advanced Analysis)

1.  **OLAP (Rollup & Drill-down)**
      * **기능:** 리그별, 팀별 연봉 총합 및 평균을 계층적으로 집계하여 비교합니다.
      * **핵심 기술:** `GROUP BY ... WITH ROLLUP`을 사용하여 소계(Subtotal)와 총계(Grand Total)를 한 번의 쿼리로 산출했습니다.
2.  **Ranking System**
      * **기능:** 연도별 선수 연봉 순위 및 시즌별 팀 승률 순위를 제공합니다.
      * **핵심 기술:** `RANK() OVER (PARTITION BY ... ORDER BY ...)` 윈도우 함수를 사용하여 그룹 내 순위를 동적으로 계산합니다.
3.  **Complex Grouping (Aggregates)**
      * **기능:** 포지션별(투수/타자) 주요 성적을 비교 분석합니다.
      * **핵심 기술:** Fielding 테이블과 Batting/Pitching 테이블을 조인하고 복합 그룹화하여 데이터를 집계합니다.
4.  **Windowing (Trend Analysis)**
      * **기능:** 특정 선수의 5년 이상 성적 변화 추이를 시각화합니다.
      * **핵심 기술:** PHP 로직과 SQL을 결합하여 전년도 대비 성적 증감을 계산하고 시계열 데이터를 처리합니다.

### 🔐 회원 관리 (Authentication)

  * **CRUD 구현:** 회원가입(Create), 로그인(Read), 정보수정(Update), 탈퇴(Delete) 기능을 완벽하게 구현했습니다.
  * **보안:** `password_hash()`와 `password_verify()`를 사용하여 비밀번호를 암호화하여 저장합니다.
  * **세션 관리:** PHP Session을 활용하여 로그인 상태를 유지하고 접근을 제어합니다.

-----

## 3\. 데이터베이스 구조 (Database Schema)
<img width="1572" height="1046" alt="image" src="https://github.com/user-attachments/assets/0c36798a-9aa5-4454-af20-6339c3b17d11" />

총 8개의 테이블로 구성된 **Star Schema** 유사 구조를 가집니다.

  * **Dimension Tables (기준 정보)**
      * Master: 선수 정보
      * Teams: 팀 정보
  * **Fact Tables (성적 데이터)**
      * Batting: 타자 기록
      * Pitching: 투수 기록
      * Fielding: 수비 기록
      * Salaries: 선수 연봉 정보
      * AllstarFull: 올스타전 출전 및 포지션 기록
  * **Management Table**
      * Users: 회원 정보

-----

## 4\. 프로젝트 구조 (Directory Structure)

```bash
team08/
├── .idea/                 # IDE 설정 폴더
├── css/                   # 웹페이지 스타일시트 (.css) 저장 폴더
├── images/                # 이미지 리소스
├── pages/                 # 공통 컴포넌트 폴더 (nav.php)
├── source/                # dataset raw file
├── sql/                   # DB 스크립트 (dbcreate.sql, dbinsert.sql)
├── .gitignore             # Git 제외 설정
├── README.md              # 프로젝트 설명
├── db_connect.php         # [Core] MySQLi 객체 생성 및 utf8mb4 Charset 설정
├── db_connect_test.php    # [Util] Users 테이블 존재 여부로 DB 연결 상태 점검
├── delete_account.php     # [Auth/View] 회원 탈퇴 전 재확인 페이지
├── delete_account_process.php # [Auth/Logic] DELETE 쿼리로 계정 삭제 및 세션 파기
├── index.php              # [Main] 메인 페이지 (네비게이션 및 레이아웃 구조)
├── login.php              # [Auth/View] 로그인 입력 폼
├── login_process.php      # [Auth/Logic] password_verify로 비밀번호 검증 및 세션 생성
├── logout_process.php     # [Auth/Logic] session_destroy로 세션 종료 후 메인 리다이렉트
├── mypage.php             # [Auth/View] 현재 로그인한 사용자 정보 표시 및 이름 수정 폼
├── mypage_process.php     # [Auth/Logic] UPDATE 쿼리로 회원 이름 수정 및 세션 갱신
├── register.php           # [Auth/View] 신규 회원가입 입력 폼
├── register_process.php   # [Auth/Logic] 중복 ID 체크 후 password_hash로 비밀번호 암호화 저장
├── salary_ranking.php     # [Analysis/View] RANK() 함수로 연도별 선수 연봉 순위 계산 및 출력
└── team_ranking.php       # [Analysis/View] RANK() 함수로 시즌/리그별 팀 승률 순위 산출 및 공식 랭킹 비교
```

-----

## 5\. 프로젝트 환경 및 설치 방법 (Installation)

### 🖥️ 1. 프로젝트 환경

  * **Server:** XAMPP (Apache, MySQL)
  * **Database:** `team08`
  * **Account(ID/PW):** `team08` / `team08`

### 📂 2. 프로젝트 폴더 배치 (Deployment)

본 프로젝트는 **XAMPP 웹 루트 디렉토리** 내에서 실행되어야 합니다.

다운로드 받은 `team08` 폴더 전체를 `htdocs` 디렉토리 하위에 위치시켜 주세요. (ex. htdocs/team08)

### ⚙️ 3. DATABASE (MySQL) 구축 방법

#### 1단계: XAMPP 서버 실행

1.  XAMPP Control Panel 실행.
2.  `Apache`와 `MySQL` 모듈 **[Start]** 버튼 클릭.

#### 2단계: DB 생성 및 데이터 로드 (CMD 사용)

1.  Windows CMD(명령 프롬프트) 실행.
2.  프로젝트의 `sql` 폴더로 이동. (위의 필수 경로에 폴더를 배치했다고 가정)
    ```cmd
    cd C:\xampp\htdocs\team08\sql
    ```
3.  `team08` 계정으로 `team08` 데이터베이스에 접속.
    > **[중요]** `LOAD DATA` 오류(Error 2068) 방지를 위해 `--local-infile=1` 플래그를 **반드시 포함**해야 함.
    ```cmd
    mysql -u team08 -p --local-infile=1 team08
    ```
4.  비밀번호(`team08`) 입력.
5.  `mysql>` 프롬프트가 뜨면, `dbcreate.sql`을 실행하여 테이블 생성.
    ```sql
    mysql> source dbcreate.sql;
    ```
6.  `dbinsert.sql`을 실행하여 CSV 원본 데이터 로드. (데이터 양에 따라 시간 소요)
    ```sql
    mysql> source dbinsert.sql;
    ```
7.  `exit;`를 입력하여 종료.

-----

## 6\. ※ (필독) `LOAD DATA` 오류 (Error 2068) 해결 방법

2단계의 3번 명령어(`mysql ... --local-infile=1 ...`)를 실행했음에도 `ERROR 2068`이 발생한다면, 접근 권한 설정이 추가로 필요합니다.

### 1\. 서버 (XAMPP) 설정

1.  XAMPP Control Panel \> MySQL 모듈 \> **[Config]** \> **`my.ini`** 파일 열기.
2.  `[mysqld]` 섹션 아래에 다음 한 줄 추가.
    ```ini
    local-infile=1
    ```
    (※ `local_infile=1`이 아닌 `local-infile=1` 일 수 있음. `my.ini` 파일 내 다른 옵션들 표기법 참고)
3.  파일 저장 후, XAMPP의 **MySQL** 서버 **[Stop]** -\> **[Start]** (재시작).

### 2\. (선택) MySQL Workbench 설정

MySQL Workbench를 사용하여 `dbinsert.sql` 스크립트를 직접 실행할 경우 이 설정이 필요함.

1.  Workbench에서 `team08` DB 연결(Connection) 아이콘 우클릭 \> **[Edit Connection...]** 선택.
2.  **[Advanced]** 탭 클릭.
3.  `Others:` 텍스트 박스 안에 아래 내용 추가.
    ```
    OPT_LOCAL_INFILE=1
    ```
4.  창을 닫고 다시 접속하면 `LOAD DATA` 스크립트가 Workbench에서도 실행됨.
