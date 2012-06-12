<?php

trait Events {

	
	/**
	 * Used for seperating event names and namespaces
	 * @var string
	 */
	private $seperator = ":";
	
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
		
		$keys = $this->parseEventIntoEventKeys($event);
		
		/**
		 * Lets pass all arguments from trigger to the Closure
		 * @var array
		 */
		$arguments = array_slice(func_get_args(), 1);

		/**
		 * Lets loop through and trigger all events
		 */
		foreach($keys as $key) {

			/**
			 * @var SplPriorityQueue
			 */
			$queue = $this->events[$key];
			
			while($queue->valid()){
					
				$callback = $queue->current();
					
				$result = call_user_func_array($callback, $arguments);
				if($result === false) {
					break;
				}
					
				$queue->next();
			}
			
		}
		
	}
	/**
	 * Bind an event to this model 
	 * @param String $event
	 * @param Closure $callback
	 * @param Integer $priority
	 */
	public function bind($event, $callback, $priority = 0) {
		
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
	/**
	 * Unbind an event from this model
	 * @param string $event
	 */
	public function unbind($event) {

		$keys = $this->parseEventIntoEventKeys($event);
		
		foreach($keys as $key) 
			unset($this->events[$key]);
		
	}
	
	
	/**
	 * Parses the string event into corresponding keys in the events array
	 * @param string $event
	 * @return array keys
	 */
	private function parseEventIntoEventKeys($event) {
	
		/**
		 * Event and namespace splitting
		 */
		$eventAndNamespace = preg_split("/$this->seperator/", $event, 2);
	
		if(count($eventAndNamespace) > 1) {
			$event = $eventAndNamespace[0];
			$namespace = $eventAndNamespace[1];
		}
		else {
			$event = $eventAndNamespace[0];
			$namespace = "";
		}
		/**
		 * Find events with the right namespace (or all events if no namespace defined)
		 */
		$keys = array_filter(array_keys($this->events), function($key) use ($event, $namespace) {
	
			if(mb_strlen($namespace) > 0)
				return $key == $event . "$this->seperator" . $namespace;
			else
				return mb_strpos($key, $event) === 0;
		});
	
		return $keys;
	}
	
	
}


?>