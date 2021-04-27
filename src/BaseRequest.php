<?php
namespace Piggly\Http;

use InvalidArgumentException;
use Piggly\Http\Interfaces\HttpPayloadInterface;

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
	 * @return HttpPayloadInterface
	 */
	public function payloableBody ( string $class ) : HttpPayloadInterface
	{
		if ( !\class_exists($class) )
		{ throw new InvalidArgumentException(sprintf('Payload class `%s` does not exist.', $class)); }

		/** @var HttpPayloadInterface $payload */
		$payload = new $class();

		$payload->import($this->getParsedBody());
		return $payload;
	}

	/**
	 * Get query parameters and import it to the payload $class.
	 * 
	 * @param string $class
	 * @since 1.0.0
	 * @return HttpPayloadInterface
	 */
	public function payloableQuery ( string $class ) : HttpPayloadInterface
	{
		if ( !\class_exists($class) )
		{ throw new InvalidArgumentException(sprintf('Payload class `%s` does not exist.', $class)); }

		/** @var HttpPayloadInterface $payload */
		$payload = new $class();

		$payload->import($this->getQueryParams());
		return $payload;
	}
}