<?php

namespace sqlNested\tests\Model;

class Comment {

	public $id;
	public $content;
	public $date;
	public $article_id;

	public function setArticle( Article $article ) {
		$this->article_id = $article->id;
	}
}