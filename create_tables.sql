CREATE TABLE `wp_bookshelf` (`googleid` char(64) not NULL , `title` varchar(200) NOT NULL, `subtitle` text, `author` char(64),  `publisher` char(64), `bookid` char(64) not NULL,`ind_type` char(64), `category` char(32),  `review` text,`knt` int default 0,`ip`  json default null, primary key (`googleid`));

LOAD DATA INFILE "books_db.csv" INTO TABLE wp_bookshelf COLUMNS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"' ESCAPED BY '"' LINES TERMINATED BY '\n';

