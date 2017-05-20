<?php

namespace sqlNested\tests\units;

use atoum;

class Converter extends atoum {

	public function test () {
		
		$this->init();
		
		$query = "
				SELECT 
				articles.id as id,
				title, 
				articles.content as articleContent, 
				comments.id as comment_id,
				comments.content as commentContent
				FROM articles
				JOIN comments ON articles.id = comments.article_id
			"
		;

		$db = \sqlNested\tests\Database\SqliteProvider::db();

		$res = $db->query( $query );

		$mapping = [
			'id',
			'title',
			'articleContent',
			'comments' => [
				'id',
				'commentContent'
			]
		];

		$ret = \sqlNested\Converter::process( $res, $mapping );

		var_dump( $ret );

	}

	private function init() {
		\sqlNested\tests\fixtures\Loader::load();

	}
}