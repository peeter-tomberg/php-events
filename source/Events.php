<?php

trait Events {

	/**
	 * Array of SplPriorityQueues
	 * @var array
	 */
	private $events = [];
	
	/**
	 * Trigger all callbacks tied to this event, based on priority
	 * @param String $event
	 * @param mixed $arguments [, mixed $... ]
	 */
	public function trigger($event) {
		
		if(!array_key_exists($event, $this->events)) {
			return;
		}
		
		/**
		 * Lets pass all arguments from trigger to the Closure
		 * @var array
		 */
		$arguments = array_slice(func_get_args(), 1);
		
		/**
		 * @var SplPriorityQueue
		 */
		$queue = $this->events[$event];
		
		while($queue->valid()){
			
			$callback = $queue->current();
			
			$result = call_user_func_array($callback, $arguments);
			if($result === false) {
				break;
			}
			
			$queue->next();
		}
		
	}
	/**
	 * Bind an event to this model 
	 * @param String $event
	 * @param Closure $callback
	 * @param Integer $priority
	 */
	public function bind($event, $callback, $priority = 0) {
		
		$eventAndNamespace = preg_split("/:/", $event, 2);
		
		if(count($eventAndNamespace) > 1) {
			$event = $eventAndNamespace[0];
			$namespace = $eventAndNamespace[1];
		}
		else {
			$event = $eventAndNamespace[0];
			$namespace = "";
		}
		
		/**
		 * If we're binding a new event, lets create a queue for it
		 */
		if(!array_key_exists($event, $this->events)) {
			$this->events[$event] = new SplPriorityQueue();
		}
		/**
		 * 
		 * @var SplPriorityQueue
		 */
		$queue = $this->events[$event];
		$queue->insert($callback, $priority);
		
	
	}
	
}


?>