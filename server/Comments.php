<?php
include_once 'DB.php';
class Comments extends DB
{
  public function __construct() {
    parent::__construct("comments");
  }

  public function addComment(string $author, string $comment) {
    return parent::add([
      'comment' => $comment,
      'author' => $author
    ]);
  }
}