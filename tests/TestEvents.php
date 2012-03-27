<?php

require_once 'PHPUnit\Framework\TestCase.php';
require_once '..\source\Events.php';
/**
 * test case.
 */
class TestEvents extends PHPUnit_Framework_TestCase {
	
	
	public function testTriggering() {
		
		$boolean = false;
		$this->trait->bind("executing", function() use (&$boolean) {
			$boolean = true;
		});
		$this->trait->trigger("executing");
		$this->assertEquals(true, $boolean, "Executing binded event");
		
	}

	public function testTriggeringWithParams() {

		
		$this->trait->bind("passing_variables", function($boolean) {
			$this->assertEquals(true, $boolean, "Passing values from trigger to bind");
		});
		$this->trait->trigger("passing_variables", true);
		
	
	}
	
	public function testTriggeringUnknownTrigger() {
		$this->trait->trigger("unknown_trigger", false);
	}
	
	public function testPriority() {
		
		$priority = 1;

		$this->trait->bind("priority", function() use (&$priority) {
			$priority = 2;
		});
		$this->trait->bind("priority", function() use (&$priority) {
			$priority = 3;
		}, 3);

		$this->trait->trigger("priority");
		$this->assertEquals(2, $priority, "Higher priority functions should be ran first");
		
	}
	
	public function testStopping() {
		
		$stopping = false;
		$this->trait->bind("stopping", function() use (&$stopping) {
			$stopping = true;
			return false;
		});
		$this->trait->bind("stopping", function() use (&$stopping) {
			$stopping = false;
		});
		$this->trait->trigger("stopping");
		$this->assertEquals(true, $stopping, "First binded event should stop the event from propagating");
	}
	
	
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		
		parent::setUp ();
		$this->trait = $this->getObjectForTrait('Events');
	
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated Events::tearDown()
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}

}

