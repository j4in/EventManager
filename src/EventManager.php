<?php
/**
 * @author Jain Jacob
 *
 */

namespace J4in;

/**
 * Class EventManager
 *
 * @package J4in
 *
 */
class EventManager
{
	/**
	 * Holds callbacks
	 */
    private static $callbacks;

    /**
     * Constructor.
     *
     * @param string $event (Optional) Name of the event.
     *
     */
    function __construct($event = null)
    {
        if ($event) {
            return $this->register($event);
        }
    }

    /**
     * Registers an event.
     *
     * @param string $event Name of the event.
     *
     */
    protected function register($event)
    {
        if (empty(self::$callbacks[$event])) {
            return self::$callbacks[$event] = array();
        }
    }

    /**
     * Binds callback to an event.
     *
     * @param string $event Name of the event.
     * @param string $callback function to be called.
     *
     */
    public function bind($event, $callback)
    {
        self::$callbacks[$event][] = $callback;
        if(!is_callable($callback)) { //Get the class
            $trace = debug_backtrace();
            self::$callbacks[$event.'class'][] = $trace[1]['class'];
            return;
        }
        return self::$callbacks[$event.'class'][] = null;
    }

    /**
     * Detaches a callback from an event.
     *
     * @param string $event Name of the event.
     * @param string $callback The function to be detached.
     *
     */
    public function unbind($event, $callback)
    {
        foreach (self::$callbacks[$event] as $key => $event_callback) {
            if ($event_callback == $callback) {
                unset(self::$callbacks[$event][$key]);
            }
        }
        return;
    }

    /**
     * Executes callbacks binded to the event with arguments as an Array.
     *
     * @param string $event Name of the event.
     * @param array $arguments The arguments to pass to each callback.
     *
     */
    public static function fire($event, $arguments = array())
    {
        if (!empty(self::$callbacks[$event])) {
            foreach (self::$callbacks[$event] as $key => $callback) {
                if(is_callable($callback)){
                    call_user_func($callback, $arguments);
                }elseif(self::$callbacks[$event.'class'][$key]) {
                    try{
                        $rc = new \ReflectionMethod(self::$callbacks[$event.'class'][$key],$callback);
                        $rc->invoke(new self::$callbacks[$event.'class'][$key],$arguments);
                    } catch (\ReflectionException $ex) {
                        echo "<br>No callback method <b>$callback()</b><br>";
                        return 'NO_METHOD';
                    }
                }
            }
        }
        return;
    }
}