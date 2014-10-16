<?php
namespace EventRecurring;

/**
 * Factory class for recurring types
 * @author m
 *
 */
class Factory extends \EventRecurring\Object
{
	const DEFAULT_TYPE = 'daily';
	const RECURRING_TYPE_BASENAME = 'RecurringType';
	/**
	 * Name of the recurring type class to create
	 * @var unknown_type
	 */
	protected $_className = null;
	
	public function __construct()
	{
		
	}
	
	/**
	 * Create recurring type extended class based on the type
	 * @param string $type
	 * @throws \EventRecurring\Exception\Type
	 */
	public function create($type = self::DEFAULT_TYPE)
	{
		$this->_className = $this->_namespacedType($type);
		if(class_exists($this->_className)) {
			return new $this->_className();
		}
		throw new \EventRecurring\Exception\Type("Recurring Type not supported");
	}
		
	/**
	 * Resolve namespace of the library
	 * @param string $type
	 */
	protected function _namespacedType($type)
	{
		return __NAMESPACE__ . '\\' . self::RECURRING_TYPE_BASENAME . '\\' . $this->_underscoreToCamelCase($type);
	}
	
	/**
	 * Underscore to camelcase format
	 * @param string $string
	 */
	protected function _underscoreToCamelCase($string)
	{
		return preg_replace('/(^|_)([a-z])/e', 'strtoupper("\\2")', $string);
	}
}