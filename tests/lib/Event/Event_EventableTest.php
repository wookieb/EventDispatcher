<?php

class Event_EventableTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var Event_Eventable
	 */
	protected $object;

	protected function setUp() {
		$this->object = new Event_Eventable;
	}

	public function testSuccessDefaultEventDispatcherDoesNotExists() {
		$this->assertFalse($this->object->hasEventDispatcher());
	}

	public function testSuccessDefaultCreateNewEventDispatcherIfNonExists() {
		$this->assertInstanceOf('Event_Dispatcher', $this->object->getEventDispatcher());
	}

	public function testSuccessSetAndGetEventDispatcher() {
		$dispatcher = new Event_Dispatcher();
		$this->assertSame($this->object, $this->object->setEventDispatcher($dispatcher));
		$this->assertSame($dispatcher, $this->object->getEventDispatcher());
	}

	public function testSuccessCheckEventDispatcherExists() {
		$this->object->getEventDispatcher();
		$this->assertTrue($this->object->hasEventDispatcher());
	}
}
