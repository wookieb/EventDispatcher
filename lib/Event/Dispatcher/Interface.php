<?php

/**
 * @author wookieb
 * @package Events
 * @subpackage Dispatcher
 */
interface Event_Dispatcher_Interface {
	public function addListener($eventName, $callback);

	public function removeListener($eventName, $callback);

	public function dispatch(Event $event);

	public function hasListeners($eventName);

	public function setTarget($target);

	public function getTarget();
}
