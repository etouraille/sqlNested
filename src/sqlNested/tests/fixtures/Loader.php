<?php

namespace sqlNested\tests\fixtures;

class Loader {

	public static function load() {

		Database::set();

		$loader = new \Nelmio\Alice\Loader\NativeLoader();
		$objectSet = $loader->loadData([
		    \sqlNested\tests\Model\Article::class => [
		        'article1' => [
		            'id' => 1,
		            'title' => 'Les alliés débarquent à Paris',
		            'content' => 'La libération de la france est proche',
		            'date' => '<date("Ymd")>',
		        ],
		        'article2' => [
		            'id' => 2,
		            'title' => 'Les carrotes sont cuites',
		            'content' => 'Bux Bunny est un sacré polission',
		            'date' => '<date("Ymd")>',
		        ],
		    ],
		    \sqlNested\tests\Model\Comment::class => [
		        'comment1' => [
		            'id' => 1,
		            'content' => "Un joyeux bordel organisé s'annonce",
		            'date' => '<date("Ymd")>',
		            'article' => '@article1'
		        ],
		        'comment2' => [
		            'id' => 2,
		            'content' => "Mais que va t'il se passer ? on est un peu inquiets !",
		            'date' => '<date("Ymd")>',
		            'article' => '@article1'
		        ],
		    ]
		]);

		$db = \sqlNested\tests\Database\SqliteProvider::db();

		foreach( $objectSet->getObjects() as $model ) {
			if( $model instanceof \sqlNested\tests\Model\Article ) {
				$db->insertRow('articles', (array) $model );
			}
			if( $model instanceof \sqlNested\tests\Model\Comment ) {
				$db->insertRow('comments', (array) $model );
			}
		}
	}
}

