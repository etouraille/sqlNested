<?php

namespace sqlNested\tests\fixtures;

class Database {

	public static function set() {
		
		$db = \sqlNested\tests\Database\SqliteProvider::db();

		$queryDropArticle = " DROP TABLE IF EXISTS `articles` ";

		$queryCreateArticle = "
		CREATE TABLE `articles` (
  			`id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  			`title` VARCHAR(255) NULL,
  			`content` VARCHAR(255) NULL,
  			`date` DATE NULL
		);
		";

		$queryDropComment = " DROP TABLE IF EXISTS `comments` ";

		$queryCreateComment = "
		CREATE TABLE `comments` (
  			`id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  			`content` VARCHAR(255) NULL,
  			`date` DATE NULL,
  			`article_id` INTEGER
		);
		";

		$db->query( $queryDropArticle );
		$db->query( $queryCreateArticle );
		
		$db->query( $queryDropComment );
		$db->query( $queryCreateComment );
		
	}
}



