<?php
namespace EventRecurring;
use EventRecurring;

/**
 * Class to demonstrate use of the library
 * @author m
 *
 */
class Scheduler extends Object
{
	/**
	 * Maximum number of event repetitions
	 * @var int
	 */
	protected $_count = null;
	
	/**
	 * Last date of event repetition
	 * @var DateTime
	 */
	protected $_end = null;
	
	/**
	 * Repetition cycle: SPL DateInterval format
	 * @var string
	 */
	protected $_interval = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	protected $_eventDate = null;
	
	/**
	 * Lower date bound
	 * @var mixed
	 */
	protected $_windowStart = null;
	
	/**
	 * Upper date bound
	 * @var mixed
	 */
	protected $_windowEnd = null;
	
	/**
	 * Instance recurring type
	 * @var object \EventRecurring\RecurringType
	 */
	protected $_recurringInstance = null;
	
	/**
	 * Recurring type factory
	 * @var object \EventRecurring\Factory
	 */
	protected $_recurringTypeFactory = null;
	
	public function __construct()
	{
		
	}
	
	/**
	 * 
	 * @param mixed $eventDate
	 * @param mixed $windowStart
	 * @param mixed $windownEnd
	 * @param string $interval
	 * @param string $count
	 */
	public function schedule($eventDate, $windowStart, $windowEnd, $interval = 'daily', $count = 0, $end = null)
	{
		$this->_count = $count;
		$this->_end	= $end;
		$this->_interval = $interval;
		$this->_eventDate = $eventDate;
		$this->_windowStart = $windowStart;
		$this->_windowEnd = $windowEnd;
		return $this->_schedule();
	}
	
	/**
	 * Get schedule dates
	 * @return array Schedule Dates
	 */
	protected function _schedule()
	{
		$this->_recurringTypeFactory = new \EventRecurring\Factory();
		$this->_recurringInstance = $this->_recurringTypeFactory->create($this->_interval);
		$this->_recurringInstance->set(
											$this->_eventDate,
											$this->_windowStart,
											$this->_windowEnd,
											$this->_end,
											$this->_count
										);
		
		return $this->_recurringInstance->dates();
	}
	
}