<?php
namespace Piggly\Http\Payloads;

use Piggly\Payload\Exceptions\JsonEncodingException;
use Piggly\Payload\Interfaces\PayloadInterface;

/**
 * A payload response model.
 *
 * @since 1.0.3
 * @package Piggly\Http
 * @subpackage Piggly\Http\Payloads
 * @author Caique Araujo <caique@piggly.com.br>
 */ 
class PayloadResponse implements PayloadInterface
{
	/**
	 * Payload data.
	 * @var array
	 * @since 1.0.3
	 */
	private $_payload;

	/**
	 * Create a new payload for response.
	 * 
	 * @param array $payload 
	 * @since 1.0.3
	 * @return self
	 */
	public function __construct ( array $payload = [] )
	{ $this->_payload = $payload; }

	/**
	 * Add a new $key to payload data.
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @since 1.0.3
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
	 * @since 1.0.3
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
	 * @since 1.0.3
	 * @return self
	 */
	protected function remove ( string $key )
	{ unset($this->_payload[$key]); return $this; }

	/**
	 * Remove a $key from payload data, only when $condition
	 * is equal to TRUE.
	 * 
	 * @param string $key
	 * @since 1.0.3
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
	 * @since 1.0.3
	 * @return mixed
	 */
	protected function get ( string $key, $default = null )
	{ return $this->_payload[$key] ?? $default; }

	/**
	 * Get $key from payload data and remove after.
	 * 
	 * @param string $key
	 * @param mixed $default Default value when $key not set.
	 * @since 1.0.3
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
	 * @since 1.0.3
	 * @return bool
	 */
	protected function has ( string $key ) : bool
	{ return isset($this->_payload[$key]); }

	/**
	 * Export all payload data to an array.
	 * 
	 * @since 1.0.3
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
	 * @since 1.0.3
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
	 * @since 1.0.3
	 * @return array
	 */
	public function jsonSerialize()
	{ return $this->toArray(); }

	/**
	 * Generate a storable representation of payload object.
	 * 
	 * @since 1.0.3
	 * @return string
	 */
	public function serialize ()
	{ return \serialize($this->_payload); }

	/**
	 * Create a Payload object from a stored representation.
	 * 
	 * @param string $data
	 * @since 1.0.3
	 * @return string
	 */
	public function unserialize ( $data )
	{ $this->_payload = \unserialize($data); } 
}