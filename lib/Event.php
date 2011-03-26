<?php

/**
 * Container for data binded with event
 * @author wookieb
 * @package Events
 */
class Event {
	/**
	 * Object which is the source of event
	 * @var object
	 */
	private $_target;
	private $_name;
	private $_data;

	public function __construct($name, $target = null, $data = null) {
		$name = trim((string)$name);
		if (empty($name)) {
			throw new InvalidArgumentException('Event name cannot be empty');
		}
		$this->_name = $name;
		$this->setTarget($target);
		$this->_data = $data;
	}

	public function setTarget($target) {
		if ($target !== null && !is_object($target)) {
			throw new InvalidArgumentException('Target must be a object');
		}
		$this->_target = $target;
		return $this;
	}

	public function getTarget() {
		return $this->_target;
	}

	public function getName() {
		return $this->_name;
	}

	public function getData() {
		return $this->_data;
	}
}

