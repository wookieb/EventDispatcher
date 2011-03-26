<?php
echo chdir(dirname(__FILE__));

$pearDirectory = exec('pear config-get php_dir');
ini_set('include_path', ini_get('include_path').
		PATH_SEPARATOR.realpath('../lib').
		PATH_SEPARATOR.realpath('../').
		PATH_SEPARATOR.$pearDirectory.'/PHPUnit/phpunit-mock-objects'.
		PATH_SEPARATOR.$pearDirectory.'/PHPUnit');
require_once 'autoloader.php';
