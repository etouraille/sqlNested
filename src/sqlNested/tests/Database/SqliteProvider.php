<?php

namespace sqlNested\tests\Database;

class SqliteProvider {

	static private $dbConnector = null;

	private function __construct() {
		require_once __DIR__.'/../../../../vendor/eden/core/src/Control.php';
		self::$dbConnector = eden('sqlite', __DIR__.'/../datas/sqlite.txt'); 
	}

	public function db() {
		if( ! isset( self::$dbConnector ) ) {
			new self();
		}
		return self::$dbConnector;
	}
}