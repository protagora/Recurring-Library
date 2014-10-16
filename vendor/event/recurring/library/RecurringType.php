<?php
namespace EventRecurring;

//testing
defined('SILLY_TESTS') or define('SILLY_TESTS', false);

/**
 * Base recurring type class
 * @author m
 *
 */
abstract class RecurringType extends \EventRecurring\Object
{
	
	/**
	 * Lower date bound
	 * @var mixed
	 */
	protected $_boundLower = null;
	
	/**
	 * Upper date bound
	 * @var mixed
	 */
	protected $_boundUpper = null;
	
	/**
	 * Date of event
	 * @var mixed
	 */
	protected $_eventDate = null;
	
	/**
	 * Maximum number of event repetitions
	 * @var mixed
	 */
	protected $_limitCount = null;
	
	/**
	 * Definition of the limit type: date in the future, total repetitions count or unlimited
	 * @var mixed
	 */
	protected $_limitDate = null;
	
	/**
	 * Repetition cycle: SPL DateInterval format
	 * @var string
	 */
	protected $_cycle = null;
	
	/**
	 * Check if the $value is unix format timestamp
	 * @param unknown $value
	 */
	public static function isUnixTimestamp($value = null)
	{
		return ((int) (string) $value === $value) && ($value <= PHP_INT_MAX) && ($value >= ~PHP_INT_MAX);
	}
	
	public function __construct()
	{
		
	}
	
	/**
	 * Properties setter
	 * @param mixed $eventDate
	 * @param mixed $boundLower
	 * @param mixed $boundUpper
	 * @param mixed $limitDate
	 * @param int $limitCount
	 */
	public function set($eventDate, $boundLower, $boundUpper, $limitDate, $limitCount)
	{
		$this->_eventDate = $eventDate;
		$this->_limitDate = $limitDate;
		$this->_limitCount = $limitCount;
		$this->_boundLower = $boundLower;
		$this->_boundUpper = $boundUpper;
		
		return $this->_normalize();
	}
	
	/**
	 * Returns dates in range
	 */
	abstract public function dates();
	
	/**
	 * Normalize property values
	 */
	protected function _normalize()
	{
		return $this->_normalizeDate($this->_eventDate)
					->_normalizeDate($this->_limitDate)
					->_normalizeDate($this->_boundLower)
					->_normalizeDate($this->_boundUpper)
					->_normalizeInt($this->_limitCount)
					->_verify();
	}
	
	/**
	 * Normalize date-ish values to DateTime
	 * @param mixed $dateParameter
	 * @throws \EventRecurring\Exception\Type
	 * @return \EventRecurring\RecurringType
	 */
	protected function _normalizeDate(&$dateParameter)
	{
		if(is_string($dateParameter)) {
			$dateParameter = new \DateTime(date("Y-m-d H:i:s", strtotime($dateParameter)));
		}
		else if(is_object($dateParameter)) {
			if(!$dateParameter instanceof \DateTime) {
				throw new \EventRecurring\Exception\Type("Only DateTime objects supported as time boundaries");
			}
		}
		else if(self::isUnixTimestamp($dateParameter)) {
			$dateParameter = new \DateTime(date("Y-m-d H:i:s", $dateParameter));
		}
		
		return $this;
	}
	
	/**
	 * Cast ints
	 * @param mixed $intParameter
	 */
	protected function _normalizeInt(&$intParameter)
	{
		$intParameter = (int) $intParameter;
		return $this;
	}
	
	/**
	 * Do some checking
	 * @throws \EventRecurring\Exception\Causality
	 */
	protected function _verify()
	{
		//Correct conditions
		$this->_boundUpper->modify('+1 day');
		if($this->_limitDate instanceof \DateTime)
			$this->_limitDate->modify('+1 day');
		if($this->_limitCount > 0) 
			$this->_limitCount++;
		
		if($this->_boundLower > $this->_boundUpper) throw new \EventRecurring\Exception\Causality("Date bounds not in check");
		if($this->_eventDate > $this->_boundLower) throw new \EventRecurring\Exception\Causality("Lower date bound not in check");
		if($this->_eventDate > $this->_boundUpper) throw new \EventRecurring\Exception\Causality("Upper date bound not in check");
		if(!is_null($this->_limitDate) && $this->_eventDate > $this->_limitDate) throw new \EventRecurring\Exception\Causality("Limit date not in check");
		if(!is_null($this->_limitDate) && !($this->_limitDate <= $this->_boundUpper && $this->_limitDate >= $this->_boundLower)) throw new \EventRecurring\Exception\Causality("Limit/Bounds not in check");
		if($this->_limitCount < 0) throw new \EventRecurring\Exception\Causality("Repetitions count must be greater than zero integer value");
		$interval = $this->_boundUpper->diff($this->_eventDate);
		if(!SILLY_TESTS && $this->_eventDate->format('U') < 1) throw new \EventRecurring\Exception\Causality("No negative unix timestamp values please");
		if(!SILLY_TESTS && $interval->format('%a') > 36525) throw new \EventRecurring\Exception\Causality("Let's not blow this out of proportion... (seriously?! More than 100 years?)");
		if(!SILLY_TESTS && $this->_limitCount > 30000) throw new \EventRecurring\Exception\Causality("Right! Let's plan ahead until... forever!");
		
		return $this;
	}
	
	/**
	 * Create dates for interval
	 * @param unknown_type $interval
	 */
	protected function _getDays($interval)
	{
		return new \DatePeriod($this->_eventDate, new \DateInterval($interval), $this->_boundUpper);
	}
}