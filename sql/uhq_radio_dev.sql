CREATE TABLE uhqradio_playlist (
	plid		INT			UNSIGNED NOT NULL AUTO_INCREMENT,
	name		CHAR(20),
	descr		CHAR(40),
	PRIMARY KEY (plid)
) ENGINE=MyISAM;

CREATE TABLE uhqradio_playmap (
	plid		INT		UNSIGNED NOT NULL,
	mpid		INT		UNSIGNED NOT NULL,
	weight		INT		UNSIGNED NOT NULL,
) ENGINE=MyISAM;

CREATE TABLE uhqradio_pls (
	pls			CHAR(20)	NOT NULL,
	name		CHAR(40)	NOT NULL,
	codec		CHAR(1)		NOT NULL,
	bitrate		INT			NOT NULL,
	PRIMARY KEY (pls)
) ENGINE=MyISAM;

CREATE TABLE uhqradio_playlist (
	pls			CHAR(20)	NOT NULL,
	server		CHAR(50)	NOT NULL,
	port		INT			UNSIGNED NOT NULL,
	mount		CHAR(20)	NOT NULL,
	sequence	INT			UNSIGNED,
	balance		INT			UNSIGNED,
	PRIMARY KEY (pls, server, port, mount)
) ENGINE=MyISAM;