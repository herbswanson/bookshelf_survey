CREATE TABLE `wp_bookshelf` (`googleid` char(64) not NULL , `title` text NOT NULL, `subtitle` text, `author` text,  `publisher` text, `bookid` text not NULL,`ind_type` char(64), `category` text,  `review` text,`knt` int default 0,`ip`  json default null, primary key (`googleid`));

LOAD DATA INFILE "books_db.csv" INTO TABLE wp_bookshelf COLUMNS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"' ESCAPED BY '"' LINES TERMINATED BY '\n';

