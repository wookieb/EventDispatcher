<?php

/**
 * @author wookieb
 * @package examples
 * @subpackage Comment
 */
class Comment extends Event_Eventable {
	private $_directory;

	public function __construct($dir) {
		$this->setDirectory($dir);
	}

	/**
	 * @param string $dir
	 * @return self;
	 */
	public function setDirectory($dir) {
		$realdir = realpath($dir);
		if ($realdir === false) {
			throw new InvalidArgumentException('Directory "'.$dir.'" does not exists');
		}
		$this->_directory = $dir.'/';
		return $this;
	}

	public function getDirectory() {
		return $this->_directory;
	}

	/**
	 * @param string $id
	 * @return Comment_Data
	 */
	public function get($id) {
		$full_path = $this->_directory.'/'.$get;
		if (file_exists($full_path)) {
			return unserialize(file_get_contents($full_path));
		}
	}

	/**
	 * Save comment
	 * @param Comment_Data $data
	 * @param string $id edited id
	 */
	public function save(Comment_Data $data, $id = null) {

		// prevent unnecessary load Event_Dispatcher and Event class
		if ($this->hasEventDispatcher()) {
			$event = new Comment_Event(Comment_Event::BEFORE_SAVE, $this, $data);
			$event->setIsEdition($id !== null);
			$this->getEventDispatcher()
					->dispatch($event);
		}
		$this->_save($data, $id);

		if ($this->hasEventDispatcher()) {
			$event = new Comment_Event(Comment_Event::AFTER_SAVE, $this, $data);
			$event->setIsEdition($id !== null);
			$this->getEventDispatcher()
					->dispatch($event);
		}
	}

	/**
	 * Save comment to file
	 * @param Comment_Data $data
	 * @param string $id
	 */
	protected function _save(Comment_Data $data, $id = null) {
		if ($id === null) {
			$id = $this->getNewId();
		}
		$fullPath = $this->_directory.$id;
		file_put_contents($fullPath, serialize($data));
		$data->setId($id);
	}

	/**
	 * Create new id for comment
	 * @return string
	 */
	public function getNewId() {
		return uniqid('comment_');
	}
}
