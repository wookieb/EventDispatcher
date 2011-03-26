<?php

/**
 * @author wookieb
 * @package Events
 */
class Event_Cancelable extends Event implements Event_Cancelable_Interface {
	private $_canceled = false;

	public function cancel() {
		$this->_canceled = true;
		return $this;
	}

	public function isCanceled() {
		return $this->_canceled;
	}
}
