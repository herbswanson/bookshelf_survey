CREATE TABLE `wp_bookshelf` (`googleid` char(64) not NULL , `title` text NOT NULL, `subtitle` text, `author` text,  `publisher` text, `bookid` text not NULL,`ind_type` char(64), `category` text,  `review` text,`cnt` int default 0,`ip`  char(64) default null, primary key (`googleid`));

LOAD DATA INFILE "books_db.csv" INTO TABLE wp_bookshelf COLUMNS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"' ESCAPED BY '"' LINES TERMINATED BY '\n';


create table `wp_bookshelf_ip` (`googleid` char(64) not NULL, `userip` char(64) not NULL, primary key (`googleid`));

create table `wp_bookshelf_personal` (`library_name` char(64) not null,`book_id` char(64) not null,`shelf_name` char(64),`googleid` char(64) not null,`date_added` timestamp, primary key (`library_name`,`book_id`));
