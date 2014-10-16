<?php
/**
 * Base namespace
 */
namespace EventRecurring;

/**
 * Base class
 * @author m
 *
 */
class Object
{
	/**
	 * Getter magic
	 * 
	 * Class property name
	 * @param string $property
	 */
	public function __get($property) {
		return $this->{$property};
	}
	
	/**
	 * Setter magic
	 * 
	 * Class property name
	 * @param string $property
	 * Class property value
	 * @param mixed $value
	 */
	public function __set($property, $value) {
		$this->{'_'.$property} = $value;
		return $this;
	}
}