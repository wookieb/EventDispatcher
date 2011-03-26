<?php

/**
 * @author wookieb
 * @package Events
 * @subpackage Dispatcher
 */
class Event_Eventable implements Event_Eventable_Interface {
	private $_dispatcher;

	/**
	 * Return event dispatcher
	 * If EventDispatcher does not exists then will be created
	 * @return Event_Dispatcher
	 */
	public function getEventDispatcher() {
		if (!$this->_dispatcher) {
			$this->_dispatcher = new Event_Dispatcher();
		}
		return $this->_dispatcher;
	}

	/**
	 * Check whenever event dispatcher instance exists
	 * @return boolean
	 */
	public function hasEventDispatcher() {
		return $this->_dispatcher !== null;
	}

	/**
	 * @param Event_Dispatcher $dispatcher
	 * @return self
	 */
	public function setEventDispatcher(Event_Dispatcher $dispatcher) {
		$this->_dispatcher = $dispatcher;
		return $this;
	}
}
