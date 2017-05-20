<?php

namespace sqlNested;

class Converter {

	public static function process( $joinQueryResult , $mapping ) {
		
		$ret = [];

		Recurser::recurse( $joinQueryResult, $mapping , $ret );
		
		return $ret;

	}
}