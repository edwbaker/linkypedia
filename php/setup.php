#!/usr/bin/php
<?php
//Create Databse tables 

$db = mysqli_connect('localhost', 'linkypedia', 'password', 'linkypedia');

$sql = "CREATE  TABLE `linkypedia`.`stats_wikipiediaviews` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `url` TEXT NULL ,
  `year` INT NULL ,
  `month` INT NULL COMMENT '	' ,
  `views` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;";

mysqli_query($db, $sql);

$sql = "CREATE TABLE `linkypedia`.`wiki_data` (`id` INT AUTO_INCREMENT, `wiki_url` TEXT NULL, `wiki_page` LONGTEXT NULL, PRIMARY KEY (`id`) ) ENGINE = InnoDB;"

mysqli_query($db, $sql);


