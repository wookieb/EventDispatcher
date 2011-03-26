<?php
/**
 * @author wookieb
 * @package Events
 * @subpackage Dispatcher
 */
interface Event_Eventable_Interface {
	public function hasEventDispatcher();
	public function getEventDispatcher();
	public function setEventDispatcher(Event_Dispatcher $dispatcher);
}
