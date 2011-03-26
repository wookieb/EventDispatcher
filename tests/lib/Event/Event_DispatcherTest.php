<?php

class Event_DispatcherTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var Event_Dispatcher
	 */
	protected $object;
	private $_dispatchCount = 0;
	private $_lastDispatchedTarget;

	protected function setUp() {
		$this->object = new Event_Dispatcher;
	}

	protected function tearDown() {
		$this->_dispatchCount = 0;
	}

	public function dispatchEventCallback(Event $event) {
		$this->_dispatchCount++;
	}

	public function dispatchEventCallbackWithCancel(Event_Cancelable_Interface $event) {
		$this->_dispatchCount++;
		$event->cancel();
	}

	public function dispatchEventCallbackDoubleIncrement(Event $event) {
		$this->_dispatchCount++;
		$this->_dispatchCount++;
	}

	public function dispatchEventSaveTarget(Event $event) {
		$this->_lastDispatchedTarget = $event->getTarget();
	}

	public function testSuccessAddListener() {
		$this->assertSame($this->object, $this->object->addListener('name', 'trim'));
		$this->assertTrue($this->object->hasListeners('name'));
	}

	public function testSuccessAddListenerForNotExistsCallback() {
		$this->assertSame($this->object, $this->object->addListener('name', 'notExistedFunction'));
	}

	public function testFailedAddListenerToEmptyEventName() {
		$this->setExpectedException('InvalidArgumentException', 'Event name is empty');
		$this->object->addListener('', 'trim');
	}

	public function testFailedAddListenerToPotentiallyEmptyEventName() {
		$this->setExpectedException('InvalidArgumentException', 'Event name is empty');
		$this->object->addListener('     ', 'trim');
	}

	public function testFailedAddListenerWithIncorrectCallback() {
		$this->setExpectedException('InvalidArgumentException', 'Invalid callback');
		$this->object->addListener('name', false);
	}

	public function testSuccessCheckExistsListeners() {
		$this->object->addListener('name', 'trim');
		$this->assertTrue($this->object->hasListeners('name'));
	}

	public function testSuccessCheckNotExistsListeners() {
		$this->object->addListener('name_2', 'trim');
		$this->assertFalse($this->object->hasListeners('name'));
	}

	public function testFailedCheckExistsListenersForEmptyEventName() {
		$this->setExpectedException('InvalidArgumentException', 'Event name is empty');
		$this->object->hasListeners('');
	}

	public function testFailedCheckExistsListenersForPotentiallyEmptyEventName() {
		$this->setExpectedException('InvalidArgumentException', 'Event name is empty');
		$this->object->hasListeners('    ');
	}

	/**
	 * @depends testSuccessCheckExistsListeners
	 * @depends testSuccessAddListener
	 */
	public function testSuccessRemoveListener() {
		$this->object->addListener('name', 'trim');
		$this->assertSame($this->object, $this->object->removeListener('name', 'trim'));
		$this->assertFalse($this->object->hasListeners('name'));
	}

	/**
	 * @depends testSuccessCheckExistsListeners
	 * @depends testSuccessAddListener
	 * @depends testSuccessRemoveListener
	 */
	public function testSuccessRemoveMoreThanOnceSameListeners() {
		$this->object->addListener('nmae', 'trim');
		$this->object->addListener('name', 'trim');
		$this->assertTrue($this->object->hasListeners('name'));
		$this->object->removeListener('name', 'trim');
		$this->assertFalse($this->object->hasListeners('name'));
	}

	/**
	 * @depends testSuccessCheckExistsListeners
	 * @depends testSuccessAddListener
	 */
	public function testSuccessRemoveListenerAndSuccessCheckExistsOfOtherListeners() {
		$this->object->addListener('name', 'trim');
		$this->object->addListener('name', 'strtolower');
		$this->object->removeListener('name', 'trim');
		$this->assertTrue($this->object->hasListeners('name'));
	}

	public function testFailedRemoveListenerForEmptyEventName() {
		$this->setExpectedException('InvalidArgumentException', 'Event name is empty');
		$this->object->removeListener('', 'trim');
	}

	public function testFailedRemoveListenerForPotentiallyEmptyEventName() {
		$this->setExpectedException('InvalidArgumentException', 'Event name is empty');
		$this->object->removeListener('       ', 'trim');
	}

	/**
	 * @depends testSuccessAddListener
	 */
	public function testSuccessDispatchEvent() {
		$this->object->addListener('name', array($this, 'dispatchEventCallback'));
		$this->assertSame($this->object, $this->object->dispatch(new Event('name')));
		$this->assertSame(1, $this->_dispatchCount);
	}

	/**
	 * @depends testSuccessAddListener
	 */
	public function testSuccessDispatchEventUntilEventIsCanceled() {
		$this->object->addListener('name', array($this, 'dispatchEventCallbackWithCancel'));
		$this->object->addListener('name', array($this, 'dispatchEventCallback'));

		$eventCancelableMock = $this->getMockBuilder('Event_Cancelable')
						->setMethods(array(
							'getName', 'isCanceled', 'cancel'
						))
						->disableOriginalConstructor()
						->getMock();

		$eventCancelableMock->expects($this->any())
				->method('getName')
				->will($this->returnValue('name'));

		$eventCancelableMock->expects($this->once())
				->method('cancel')
				->will($this->returnValue($eventCancelableMock));

		$eventCancelableMock->expects($this->atLeastOnce())
				->method('isCanceled')
				->will($this->returnValue(true));
		$this->object->dispatch($eventCancelableMock);
		$this->assertSame(1, $this->_dispatchCount);
	}

	/**
	 * @depends testSuccessAddListener
	 */
	public function testSuccessDispatchEventOnlyOnListenersForSpecifiedEventName() {
		$this->object->addListener('name', array($this, 'dispatchEventCallback'));
		$this->object->addListener('name2', array($this, 'dispatchEventCallback'));
		$this->object->dispatch(new Event('name'));
		$this->assertSame(1, $this->_dispatchCount);
	}

	/**
	 * @depends testSuccessAddListener
	 */
	public function testSuccessDispatchEventWhenListenersForSpecifiedEventNameDoesNotExists() {
		$this->object->addListener('name', array($this, 'dispatchEventCallback'));
		$this->object->dispatch(new Event('name2'));
		$this->assertSame(0, $this->_dispatchCount);
	}

	public function testSuccessSetAndGetTarget() {
		$target = new stdClass();
		$this->assertSame($this->object, $this->object->setTarget($target));
		$this->assertSame($target, $this->object->getTarget());
	}

	public function testSuccessDefaultTargetIsNull() {
		$this->assertNull($this->object->getTarget());
	}

	public function testFailedSetTargetWhichIsNotObject() {
		$this->setExpectedException('InvalidArgumentException', 'Target must be a object');
		$this->object->setTarget('test');
	}

	/**
	 * @depends testSuccessSetAndGetTarget
	 * @depends testSuccessAddListener
	 * @depends testSuccessDispatchEvent
	 */
	public function testSuccessDispatchEventWithTarget() {
		$object = new stdClass;
		$this->object->setTarget($object);
		$this->object->addListener('name', array($this, 'dispatchEventSaveTarget'));
		$this->object->dispatch(new Event('name', $object));
		$this->assertSame($object, $this->_lastDispatchedTarget);
	}

	/**
	 * @depends testSuccessAddListener
	 * @depends testSuccessDispatchEvent
	 */
	public function testSuccessOverrideTargetForDispatchedEvent() {
		$object = new stdClass;
		$object->uniqueValue = true;

		$this->object->setTarget($object);
		$this->object->addListener('name', array($this, 'dispatchEventSaveTarget'));

		$object2 = new stdClass();
		$this->object->dispatch(new Event('name', $object2));

		$this->assertSame($object, $this->_lastDispatchedTarget);
	}

	/**
	 * @depends testSuccessAddListener
	 * @depends testSuccessDispatchEvent
	 */
	public function testSuccessDispatchEventWithoutTarget() {
		$this->object->addListener('name', array($this, 'dispatchEventSaveTarget'));
		$this->object->dispatch(new Event('name'));
		$this->assertNull($this->_lastDispatchedTarget);
	}
}
