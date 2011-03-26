<?php

/**
 * @author wookieb
 * @package Events
 * @subpackage Dispatcher
 */
class Event_Dispatcher implements Event_Dispatcher_Interface {
	/**
	 * @var array
	 */
	private $_listeners = array();
	/**
	 * @var object
	 */
	private $_target;

	/**
	 * Add listener of event
	 * @param string $eventName
	 * @param callback $callback
	 * @return self
	 */
	public function addListener($eventName, $callback) {
		$eventName = $this->_checkAndReturnEventName($eventName);
		if (!is_callable($callback, true)) {
			throw new InvalidArgumentException('Invalid callback');
		}
		$this->_listeners[$eventName][] = $callback;
		return $this;
	}

	private function _checkAndReturnEventName($eventName) {
		$eventName = trim((string)$eventName);
		if (empty($eventName)) {
			throw new InvalidArgumentException('Event name is empty');
		}
		return $eventName;
	}

	/**
	 * @param string $eventName
	 * @param callback $callback
	 * @return self
	 */
	public function removeListener($eventName, $callback) {
		$eventName = $this->_checkAndReturnEventName($eventName);
		if (isset($this->_listeners[$eventName])) {
			$listeners = &$this->_listeners[$eventName];
			$foundKeys = array_keys($listeners, $callback, true);

			foreach ($foundKeys as $key) {
				unset($listeners[$key]);
			}

			if (empty($this->_listeners[$eventName])) {
				unset($this->_listeners[$eventName]);
			}
		}
		return $this;
	}

	/**
	 * Check whether event name has any listeners
	 * @param string $eventName
	 * @return boolean
	 */
	public function hasListeners($eventName) {
		$this->_checkAndReturnEventName($eventName);
		return isset($this->_listeners[$eventName]);
	}

	/**
	 * @param Event $event
	 * @return Event_Dispatcher
	 */
	public function dispatch(Event $event) {
		if ($this->_target) {
			$event->setTarget($this->_target);
		}
		$this->_notifyListeners($event);
		return $this;
	}

	private function _notifyListeners(Event $event) {
		$eventName = $event->getName();
		if (empty($this->_listeners[$eventName])) {
			return;
		}

		$isEventCancelable = $event instanceof Event_Cancelable_Interface;
		foreach ($this->_listeners[$eventName] as $listener) {
			call_user_func($listener, $event);
			if ($isEventCancelable && $event->isCanceled()) {
				return;
			}
		}
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
}

