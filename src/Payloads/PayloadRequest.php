<?php
namespace Piggly\Http\Payloads;

use Exception;
use Piggly\Http\BaseRequest;
use Piggly\Payload\Concerns\PayloadValidable;
use Piggly\Payload\Exceptions\InvalidDataException;
use Piggly\Payload\Exceptions\JsonEncodingException;
use Piggly\Payload\Interfaces\PayloadInterface;

/**
 * A payload request model.
 *
 * @since 1.0.1
 * @package Piggly\Http
 * @subpackage Piggly\Http\Payloads
 * @author Caique Araujo <caique@piggly.com.br>
 */ 
abstract class PayloadRequest implements PayloadInterface, PayloadValidable
{
	/**
	 * Fill $_PARAMS from request body.
	 * 
	 * @var int
	 * @since 1.0.1
	 */
	const FROM_BODY = 1;

	/**
	 * Fill $_PARAMS from request query string.
	 * 
	 * @var int
	 * @since 1.0.1
	 */
	const FROM_QUERY = 2;

	/**
	 * Request parameters.
	 * 
	 * @var array
	 * @since 1.0.1
	 */
	private $_PARAMS = [];

	/**
	 * Payload data.
	 * @var array
	 * @since 1.0.0
	 */
	private $_payload = [];

	/**
	 * A key-pair array with [$key => $value] where
	 * $key is the parameter name at request and
	 * $value is the setter method name.
	 *
	 * @var array
	 * @since 1.0.1
	 */
	protected $map = [];

	/**
	 * An array with all required parameters name.
	 *
	 * @var array
	 * @since 1.0.1
	 */
	protected $required = [];

	/**
	 * An array with all parameters which null is
	 * a "valid" data.
	 *
	 * @var array
	 * @since 1.0.1
	 */
	protected $allowedNull = [];

	/**
	 * Construct payload.
	 * 
	 * @param BaseRequest $request
	 * @param int $fillFrom Params origin.
	 * @since 1.0.1
	 * @return self
	 */
	public function __construct ( 
		BaseRequest $request = null, 
		int $fillFrom = self::FROM_BODY
	)
	{
		if ( $request instanceof BaseRequest )
		{ $this->import($request, $fillFrom); }
	}

	/**
	 * Import all params from $request.
	 * 
	 * @param BaseRequest $request
	 * @param int $fillFrom Params origin.
	 * @since 1.0.1
	 * @return self
	 */
	public function import (
		BaseRequest $request, 
		int $fillFrom = self::FROM_BODY
	)
	{
		switch ( $fillFrom )
		{
			case self::FROM_BODY:
				$this->_PARAMS = $request->getParsedBody();
				break;
			case self::FROM_QUERY:
				$this->_PARAMS = $request->getQueryParams();
				break;
		}

		foreach ( $this->map as $key => $method )
		{
			$value = $this->_PARAMS[$key] ?? null;

			// Fill when not null
			if ( !is_null($value) )
			{ 
				$this->{$method}($value); 
				continue;
			}

			// Fill if allow null
			if ( in_array($key, $this->allowedNull) )
			{
				$this->add($key, null);
				continue;
			}
		}

		return $this;
	}
	
	/**
	 * Validate all data from payload.
	 * Throw an exception when cannot validate.
	 * 
	 * @since 1.0.1
	 * @return void
	 * @throws InvalidDataException When some data is invalid.
	 */
	public function validate ()
	{
		// Validate all required fields
		foreach ( $this->required as $key )
		{ 
			$value = $this->get($key);

			if ( is_null($value) )
			{ throw InvalidDataException::invalid($this, $key, $value, 'Cannot be empty value'); }
		}
	}

	/**
	 * Validate all data from payload.
	 * But, instead throw an exception when cannot
	 * validate, return a boolean.
	 * 
	 * @since 1.0.2
	 * @return bool TRUE when valid, FALSE when invalid.
	 */
	public function isValid () : bool
	{
		try
		{ $this->validate(); }
		catch ( Exception $e )
		{ return false; }

		return true;
	}

	/**
	 * Add a new $key to payload data.
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @since 1.0.0
	 * @return self
	 */
	protected function add ( string $key, $value )
	{ $this->_payload[$key] = $value; return $this; }

	/**
	 * Add a new $key to payload data only when $condition
	 * is equal to TRUE.
	 * 
	 * @param bool $condition
	 * @param string $key
	 * @param mixed $value
	 * @since 1.0.0
	 * @return self
	 */
	protected function addWhen ( bool $condition, string $key, $value )
	{ 
		if ( $condition )
		{ $this->_payload[$key] = $value; }

		return $this;
	}

	/**
	 * Remove a $key from payload data.
	 * 
	 * @param string $key
	 * @since 1.0.0
	 * @return self
	 */
	protected function remove ( string $key )
	{ unset($this->_payload[$key]); return $this; }

	/**
	 * Remove a $key from payload data, only when $condition
	 * is equal to TRUE.
	 * 
	 * @param string $key
	 * @since 1.0.0
	 * @return self
	 */
	protected function removeWhen ( bool $condition, string $key )
	{ 
		if ( $condition )
		{ unset($this->_payload[$key]); }

		return $this; 
	}

	/**
	 * Get a $key from payload data.
	 * 
	 * @param string $key
	 * @param mixed $default Default value when $key not set.
	 * @since 1.0.0
	 * @return mixed
	 */
	protected function get ( string $key, $default = null )
	{ return $this->_payload[$key] ?? $default; }

	/**
	 * Get $key from payload data and remove after.
	 * 
	 * @param string $key
	 * @param mixed $default Default value when $key not set.
	 * @since 1.0.0
	 * @return mixed
	 */
	protected function getAndRemove ( string $key, $default = null )
	{
		$value = $this->get($key, $default);
		$this->remove($key);
		return $value;
	}

	/**
	 * Check if has $key at payload data.
	 * 
	 * @param string $key
	 * @since 1.0.0
	 * @return bool
	 */
	protected function has ( string $key ) : bool
	{ return isset($this->_payload[$key]); }

	/**
	 * Export all payload data to an array.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function toArray () : array
	{
		$_array = [];

		foreach ( $this->_payload as $key => $value )
		{
			if ( $value instanceof PayloadInterface )
			{ 
				$_array[$key] = $value->toArray();
				continue;
			}

			$_array[$key] = $value;
		}

		return $_array;
	}

	/**
	 * Export all payload data to a JSON string.
	 * 
	 * @since 1.0.0
	 * @return string
	 * @throws JsonEncodingException If can't parse JSON.
	 */
	public function toJson ( int $option = \JSON_ERROR_NONE, int $depth = 512 ) : string
	{
		$json = json_encode( $this->jsonSerialize(), $option, $depth );

		if ( JSON_ERROR_NONE !== json_last_error() ) 
		{ throw JsonEncodingException::for($this, \json_last_error_msg()); }

		return $json;
	}
  
	/**
	 * Prepare the resource for JSON serialization.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function jsonSerialize()
	{ return $this->toArray(); }

	/**
	 * Generate a storable representation of payload object.
	 * 
	 * @since 1.0.0
	 * @return string
	 */
	public function serialize ()
	{ return \serialize($this->_payload); }

	/**
	 * Create a Payload object from a stored representation.
	 * 
	 * @param string $data
	 * @since 1.0.0
	 * @return string
	 */
	public function unserialize ( $data )
	{ $this->_payload = \unserialize($data); } 
}