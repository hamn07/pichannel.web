CREATE DATABASE IF NOT EXISTS pichannel DEFAULT CHARACTER SET utf8;

USE pichannel;

/* user */
CREATE TABLE IF NOT EXISTS user (
  id VARCHAR(254) NOT NULL,
  name VARCHAR(50) NOT NULL,
  music_sha1 VARCHAR(40)          );

ALTER TABLE user
  ADD PRIMARY KEY (id);

INSERT INTO user
VALUES(
  "hamn07@gmail.com",
  "Henry LEE",
  NULL
);

/* subscription */
CREATE TABLE IF NOT EXISTS subscription (
  user_id VARCHAR(254) NOT NULL,
  subscription VARCHAR(254) NOT NULL,
  has_new_posts TINYINT(1)          );

ALTER TABLE subscription
  ADD PRIMARY KEY (user_id,subscription);

INSERT INTO subscription
VALUES(
  "hamn07",
  "hamn07",
  1
);


/* post */
CREATE TABLE IF NOT EXISTS post (
  id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  post_unixtimestamp_original INT UNSIGNED NOT NULL,
  user_id VARCHAR(254) NOT NULL,
  image_sha1 VARCHAR(40) NOT NULL,
  text VARCHAR(100),
  PRIMARY KEY (user_id,id)
) ENGINE=MyISAM;

select id,from_unixtime(post_unixtimestamp_original),user_id,image_sha1,text from post;

/* image */
CREATE TABLE IF NOT EXISTS image (
  sha1 VARCHAR(40) NOT NULL,
  exif_unixtimestamp_original INT UNSIGNED,
  PRIMARY KEY (sha1)
);

select sha1,from_unixtime(exif_unixtimestamp_original) from image order by 2 desc;

/* music */
CREATE TABLE IF NOT EXISTS music (
  sha1 VARCHAR(40) NOT NULL      );

ALTER TABLE music
  ADD PRIMARY KEY (sha1);
