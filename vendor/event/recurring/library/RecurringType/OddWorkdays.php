<?php
namespace EventRecurring\RecurringType;

/**
 * OddWorkdays recurring type
 * @author m
 *
 */
class OddWorkdays extends \EventRecurring\RecurringType
{
	
	/**
	 * Base recurring interval
	 */
	protected $_interval = 'P1D';
	
	/**
	 * Returns an array of dates for specified constrains
	 *
	 * (non-PHPdoc)
	 * @see EventRecurring.RecurringType::dates()
	 */
	public function dates()
	{
		$array = array();
		$counter = 0;
		foreach($this->_getDays($this->_interval) as $day) {
			if(!in_array(strtolower($day->format('D')), array('mon', 'wed', 'fri')))
				continue;
			$counter++;
			if(($this->_limitCount > 0 && $counter > $this->_limitCount) || ($this->_limitDate instanceof \DateTime && $day >= $this->_limitDate))
				return $array;
			if($day < $this->_boundLower) {
				continue;
			}
			$array[] = $day->format('Y-m-d');
		}
		return $array;
	}
}