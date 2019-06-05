CREATE TABLE `wp_bookshelf` (`googleid` char(64) not NULL , `title` text NOT NULL, `subtitle` text, `author` text,  `publisher` text, `bookid` text not NULL,`ind_type` char(64), `category` text,  `review` text,`cnt` int default 0,`update_time`  timestamp, primary key (`googleid`));

LOAD DATA INFILE "/tmp/books_db.csv" INTO TABLE wp_bookshelf COLUMNS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"' ESCAPED BY '"' LINES TERMINATED BY '\n';

update wp_bookshelf set cnt = 1;

grant file on *.* to 'vsaker_wp2'@'localhost';


create table `wp_bookshelf_ip` (`googleid` char(64) not NULL, `userip` char(64) not NULL, primary key (`googleid`));

create table `wp_bookshelf_personal` (`library_name` char(64) not null,`book_id` char(64) not null,`shelf_name` char(64),`googleid` char(64) not null,`date_added` timestamp, primary key (`library_name`,`book_id`));
