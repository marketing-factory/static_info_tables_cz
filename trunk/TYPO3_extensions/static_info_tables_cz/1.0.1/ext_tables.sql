CREATE TABLE static_countries (
  cn_short_cz varchar(50) DEFAULT '' NOT NULL,
  cn_official_name_cz varchar(128) DEFAULT '' NOT NULL,
  cn_capital_cz varchar(45) DEFAULT '' NOT NULL,
  KEY cn_short_cz (cn_short_cz)
);

CREATE TABLE static_country_zones (
  zn_name_cz varchar(50) DEFAULT '' NOT NULL,
  KEY zn_name_cz (zn_name_cz)
);

CREATE TABLE static_currencies (
  cu_name_cz varchar(50) DEFAULT '' NOT NULL,
  cu_sub_name_cz varchar(20) DEFAULT '' NOT NULL
);

CREATE TABLE static_languages (
  lg_name_cz varchar(50) DEFAULT '' NOT NULL
);

CREATE TABLE static_territories (
  tr_name_cz varchar(50) DEFAULT '' NOT NULL
);

