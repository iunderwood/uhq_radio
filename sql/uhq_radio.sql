CREATE TABLE uhqradio_handoffs (
  reqtime DATETIME,
  requser INT UNSIGNED NOT NULL,
  reqip   CHAR(50)     NOT NULL,
  reqstat INT UNSIGNED NOT NULL,
  PRIMARY KEY (reqtime)
)
  ENGINE = MyISAM;

CREATE TABLE uhqradio_airstaff (
  djid     CHAR(5)               NOT NULL,
  userkey  MEDIUMINT(8) UNSIGNED NOT NULL,
  urlpic   CHAR(120),
  urlsite  CHAR(120),
  flag_req INT UNSIGNED          NOT NULL,
  blurb    VARCHAR(1024),
  play_mu  VARCHAR(120),
  play_ga  VARCHAR(120),
  rdb_port INT UNSIGNED          NOT NULL,
  rdb_user CHAR(20),
  rdb_pass CHAR(20),
  rdb_name CHAR(20),
  sam_port INT UNSIGNED          NOT NULL,
  PRIMARY KEY (djid)
)
  ENGINE = MyISAM;

CREATE TABLE uhqradio_mountpoints (
  mpid       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  server     CHAR(50)     NOT NULL,
  port       INT UNSIGNED NOT NULL,
  type       CHAR(1)      NOT NULL,
  mount      CHAR(20),
  auth_un    CHAR(20)     NOT NULL,
  auth_pw    CHAR(20)     NOT NULL,
  codec      CHAR(1)      NOT NULL,
  bitrate    INT UNSIGNED NOT NULL,
  fallback   CHAR(20),
  listeners  INT UNSIGNED NOT NULL,
  variance   INT          NOT NULL,
  flag_text  INT,
  flag_count INT,
  PRIMARY KEY (mpid)
)
  ENGINE = MyISAM;

CREATE TABLE uhqradio_channels (
  chid        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  chan_name   CHAR(80),
  chan_tag    VARCHAR(80),
  chan_web    VARCHAR(80),
  chan_descr  VARCHAR(512),
  text_mpid   INT UNSIGNED NOT NULL,
  flag_djid   INT UNSIGNED NOT NULL,
  flag_d_sol  INT UNSIGNED NOT NULL,
  flag_d_eol  INT UNSIGNED NOT NULL,
  delim_dj_s  CHAR(5),
  delim_dj_e  CHAR(5),
  flag_show   INT UNSIGNED NOT NULL,
  flag_s_sol  INT UNSIGNED NOT NULL,
  flag_s_eol  INT UNSIGNED NOT NULL,
  delim_sh_s  CHAR(5),
  delim_sh_e  CHAR(5),
  lc_guid     VARCHAR(40),
  tunein_pid  CHAR(10),
  tunein_pkey CHAR(15),
  tunein_sid  CHAR(10),
  PRIMARY KEY (chid)
)
  ENGINE = MyISAM;

CREATE TABLE uhqradio_countmap (
  chid INT UNSIGNED NOT NULL,
  mpid INT UNSIGNED NOT NULL,
  PRIMARY KEY (chid, mpid)
)
  ENGINE = MyISAM;

CREATE TABLE uhqradio_lhistory (
  mpid    INT UNSIGNED NOT NULL,
  stamp   DATETIME,
  stamp_a DATETIME,
  status  CHAR(1),
  pop     INT UNSIGNED NOT NULL,
  PRIMARY KEY (mpid, stamp)
)
  ENGINE = MyISAM;

CREATE TABLE uhqradio_shistory (
  chid      INT UNSIGNED NOT NULL,
  stamp     DATETIME,
  djid      CHAR(5)      NOT NULL,
  showname  VARCHAR(128),
  artist    VARCHAR(255),
  track     VARCHAR(255),
  songtype  CHAR(1),
  album     VARCHAR(255),
  albumyear VARCHAR(4),
  label     VARCHAR(100),
  picture   VARCHAR(255),
  requestID INT,
  requestor VARCHAR(32),
  viewers   INT          NOT NULL,
  PRIMARY KEY (chid, stamp)
)
  ENGINE = MyISAM;
