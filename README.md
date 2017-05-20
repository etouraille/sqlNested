Couple of class to convert the result of a join query into nested arrays : no limits.

Thats propbly some component of an ORM ( See Doctrine ).

For it to work, use plural for the definition of nested array in mapping, 'ie comments' and {singular}_id for the id of nested join in id in query. 
algorythm uses id to reorder the datas

`
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

		array(1) {
		  [0]=>
		  array(4) {
		    ["id"]=>
		    string(1) "1"
		    ["title"]=>
		    string(32) "Les alliés débarquent à Paris"
		    ["articleContent"]=>
		    string(38) "La libération de la france est proche"
		    ["comments"]=>
		    array(2) {
		      [0]=>
		      array(2) {
		        ["commentContent"]=>
		        string(36) "Un joyeux bordel organisé s'annonce"
		        ["id"]=>
		        string(1) "1"
		      }
		      [1]=>
		      array(2) {
		        ["commentContent"]=>
		        string(53) "Mais que va t'il se passer ? on est un peu inquiets !"
		        ["id"]=>
		        string(1) "2"
		      }
		    }
		  }
		}

`