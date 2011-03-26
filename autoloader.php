<?php

function event_dispatcher_autoloader($class) {
	require_once str_replace('_', '/', $class).'.php';
}
spl_autoload_register('event_dispatcher_autoloader');
