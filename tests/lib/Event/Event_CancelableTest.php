<?php
class Event_CancelableTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var Event_Cancelable
	 */
	protected $object;

	protected function setUp() {
		$this->object = new Event_Cancelable('name');
	}

	public function testDefaultEventIsNotCanceled() {
		$this->assertFalse($this->object->isCanceled());
	}

	public function testSuccessCancel() {
		$this->assertSame($this->object, $this->object->cancel());
		$this->assertTrue($this->object->isCanceled());
	}
}
