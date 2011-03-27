<?php
set_include_path(get_include_path().
		PATH_SEPARATOR.realpath('example/').
		PATH_SEPARATOR.realpath('lib/'));
require_once 'autoloader.php';

$comment = new Comment('resources/comments');

// Using EventDispatcher you don't need to modify Comment class to "catch" moment of save data
$comment->getEventDispatcher()
		->addListener(Comment_Event::BEFORE_SAVE, 'before_save_comment')
		->addListener(Comment_Event::BEFORE_SAVE, 'edit_comment_before_save')
		->addListener(Comment_Event::AFTER_SAVE, 'register_comment_id')
		->addListener(Comment_Event::AFTER_SAVE, 'after_save_comment');

function before_save_comment(Comment_Event $event) {
	echo 'Before save'.PHP_EOL;
}

function edit_comment_before_save(Comment_Event $event) {
	$comment = $event->getData();
	/* @var $comment Comment_Data */
	$comment->setTitle($comment->getTitle().' Edited in '.__FUNCTION__);
}

function register_comment_id(Comment_Event $event) {
	if (!$event->getIsEdition()) {
		file_put_contents('resources/comments_ids.txt', $event->getData()->getId().PHP_EOL, FILE_APPEND);
	}
}

function after_save_comment() {
	echo 'After save'.PHP_EOL;
}
$data= new Comment_Data();
$data->setTitle('EventDispatcher save your time!')
		->setBody('Visit https://github.com/wookieb/EventDispatcher')
		->setDateCreated(new DateTime());
$comment->save($data);