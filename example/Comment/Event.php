<?php

/**
 * @author wookieb
 * @package examples
 * @subpackage Comment
 */
class Comment_Event extends Event {
	/**
	 * Event name called before save Comment
	 */
	const BEFORE_SAVE = 'CommentEvent_BeforeSave';
	/**
	 * Event name called after save Comment
	 */
	const AFTER_SAVE = 'CommentEvent_AfterSave';

	private $_isEdition = false;

	/**
	 * @param boolean $to
	 * @return self
	 */
	public function setIsEdition($to) {
		$this->_isEdition = (bool)$to;
		return $this;
	}

	public function getIsEdition() {
		return $this->_isEdition;
	}
}

