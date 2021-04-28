<?php
namespace Piggly\Http;

use InvalidArgumentException;
use Piggly\Payload\Exceptions\InvalidDataException;
use Piggly\Payload\Interfaces\PayloadInterface;

/**
 * A BaseRequest object to support any type of Request object
 * and interact with request data in a smart way.
 *
 * @since 1.0.0
 * @package Piggly\Http
 * @subpackage Piggly\Http
 * @author Caique Araujo <caique@piggly.com.br>
 */
abstract class BaseRequest 
{
	/**
	 * Original request object.
	 * 
	 * @var mixed
	 * @since 1.0.0
	 */
	protected $_request;

	/**
	 * Create this request with original request
	 * object.
	 * 
	 * @param mixed $request Original request object.
	 * @since 1.0.0
	 * @return self
	 */
	public function __construct ( $request )
	{ $this->_request = $request; }

	/**
	 * Check if a header $name exists.
	 * 
	 * @param string $name
	 * @since 1.0.0
	 * @return bool
	 */
	abstract public function hasHeader ( string $name, $default = null ) : bool;

	/**
	 * Get a header data.
	 * 
	 * @param string $name
	 * @param mixed $default Default value when empty.
	 * @since 1.0.0
	 * @return mixed
	 */
	abstract public function header ( string $name, $default = null );

	/**
	 * Get all query string parameters from original request object as array.
	 * Return an empty array if query string parameters has no data.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	abstract public function getQueryParams () : array;

	/**
	 * Get all body data from original request object as array.
	 * Return an empty array if body has no data.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	abstract public function getParsedBody () : array;

	/**
	 * Get all files data from original request object as array.
	 * Return an empty array if body has no data.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	abstract public function getFiles () : array;

	/**
	 * Set request attribute.
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @since 1.0.5
	 * @return self
	 */
	abstract public function setAttribute ( string $key, $value );

	/**
	 * Get request attribute.
	 * 
	 * @param string $key
	 * @param mixed $default Default value to return when $key is empty.
	 * @since 1.0.5
	 * @return mixed
	 */
	abstract public function getAttribute ( string $key, $default );

	/**
	 * Get current request method.
	 * 
	 * @since 1.0.5
	 * @return string
	 */
	abstract public function getMethod () : string;

	/**
	 * Get original request object.
	 * 
	 * @since 1.0.0
	 * @return mixed
	 */
	public function request ()
	{ return $this->_request; }

	/**
	 * Get parsed body and import it to the payload $class.
	 * 
	 * @param string $class
	 * @since 1.0.0
	 * @return PayloadInterface
	 * @throws InvalidArgumentException When class does not exist or does not match
	 * @throws InvalidDataException When cannot import some data
	 */
	public function payloableBody ( string $class ) : PayloadInterface
	{
		if ( !\class_exists($class) )
		{ throw new InvalidArgumentException(sprintf('Payload class `%s` does not exist.', $class)); }
		
		if ( \in_array(PayloadInterface::class, \class_implements($class), true) === false )
		{ throw new InvalidArgumentException(sprintf('Payload class `%s` does not implement PayloadInterface.', $class)); }

		/** @var PayloadInterface $payload */
		$payload = new $class();

		$payload->import($this->getParsedBody());
		return $payload;
	}

	/**
	 * Get query parameters and import it to the payload $class.
	 * 
	 * @param string $class
	 * @since 1.0.0
	 * @return PayloadInterface
	 * @throws InvalidArgumentException When class does not exist or does not match
	 * @throws InvalidDataException When cannot import some data
	 */
	public function payloableQuery ( string $class ) : PayloadInterface
	{
		if ( !\class_exists($class) )
		{ throw new InvalidArgumentException(sprintf('Payload class `%s` does not exist.', $class)); }

		if ( \in_array(PayloadInterface::class, \class_implements($class), true) === false )
		{ throw new InvalidArgumentException(sprintf('Payload class `%s` does not implement PayloadInterface.', $class)); }

		/** @var PayloadInterface $payload */
		$payload = new $class();

		$payload->import($this->getQueryParams());
		return $payload;
	}
}