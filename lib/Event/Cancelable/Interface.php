<?php

/**
 * @author wookieb
 * @package Events
 */
interface Event_Cancelable_Interface {
	public function cancel();
	public function isCanceled();
}
