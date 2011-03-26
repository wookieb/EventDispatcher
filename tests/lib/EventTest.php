<?php

class EventTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var Event
	 */
	private $object;

	protected function setUp() {
		$this->object = new Event('name');
	}

	public function testSuccessSetAndGetTargetObject() {
		$target = new stdClass;
		$this->assertSame($this->object, $this->object->setTarget($target));
		$this->assertSame($target, $this->object->getTarget());
	}

	public function testFailedSetTargetNonObject() {
		$this->setExpectedException('InvalidArgumentException');
		$this->object->setTarget('non_object');
	}

	public function testFailedToGiveEmptyEventName() {
		$this->setExpectedException('InvalidArgumentException');
		new Event('');
	}

	public function testFailedToGivePotentiallyEmptyEventName() {
		$this->setExpectedException('InvalidArgumentException');
		new Event('   ');
	}

	public function testSuccessNamingEvent() {
		$event = new Event('event_name');
		$this->assertSame('event_name', $event->getName());
	}

	public function testSuccessGiveAnyTypeOdDataForEvent() {
		$event = new Event('event_name', null, 'some_data');
		$this->assertSame('some_data', $event->getData());
		$event = new Event('event_name', null, array(1, 2, 3));
		$this->assertSame(array(1, 2, 3), $event->getData());
	}
}
