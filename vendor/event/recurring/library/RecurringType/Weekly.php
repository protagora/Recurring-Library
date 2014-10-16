<?php
namespace EventRecurring\RecurringType;

/**
 * Weekly recurring type
 * @author m
 *
 */
class Weekly extends \EventRecurring\RecurringType
{
	/**
	 * Base recurring interval
	 */
	protected $_interval = 'P7D';
	
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