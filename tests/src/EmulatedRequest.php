<?php
namespace Piggly\Dev\Http;

use Piggly\Http\BaseRequest;

class EmulatedRequest extends BaseRequest
{
	/**
	 * EMULATE REQUEST HEADERS.
	 * 
	 * @var array
	 * @since 1.0.0
	 */
	private $_headers = [
		'Content-Type' => 'application/json'
	];

	/**
	 * EMULATE REQUEST ATTRIBUTES.
	 * 
	 * @var array
	 * @since 1.0.0
	 */
	private $_attributes = [];

	/**
	 * Check if a header $name exists.
	 * 
	 * @param string $name
	 * @since 1.0.0
	 * @return bool
	 */
	public function hasHeader ( string $name ) : bool
	{ return isset($this->_headers[$name]); }

	/**
	 * EMULATE A REQUEST HEADERS ARRAY.
	 * Get a header data.
	 * 
	 * @param string $name
	 * @param mixed $default Default value when empty.
	 * @since 1.0.0
	 * @return mixed
	 */
	public function header ( string $name, $default = null )
	{ return $this->hasHeader($name) ? $this->_headers[$name] : $default; }

	/**
	 * Get all headers from original request object as array.
	 * Return an empty array if headers has no data.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function getHeaders () : array
	{ return $this->headers; }

	/**
	 * EMULATE QUERY PARAMETERS FROM REQUEST.
	 * Get all query string parameters from original request object as array.
	 * Return an empty array if query string parameters has no data.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function getQueryParams () : array
	{
		return [
			'user_id' => 10
		];
	}

	/**
	 * Get all body data from original request object as array.
	 * Return an empty array if body has no data.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function getParsedBody () : array
	{
		return [
			'name' => 'John Connor',
			'email' => 'john@skynet.com',
			'phone' => '+1-202-555-0172',
			'address' => [
				'address' => 'Future Avenue',
				'number' => '2047',
				'complement' => 'High Tech World',
				'district' => 'Nobody\'s Alive',
				'city' => 'Unknown',
				'country_id' => 'US',
				'postal_code' => '55372'
			]
		];
	}

	/**
	 * Get all files data from original request object as array.
	 * Return an empty array if body has no data.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function getFiles () : array
	{ return []; }

	/**
	 * Set request attribute.
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @since 1.0.5
	 * @return self
	 */
	public function setAttribute ( string $key, $value )
	{ $this->_attributes[$key] = $value; }

	/**
	 * Get request attribute.
	 * 
	 * @param string $key
	 * @param mixed $default Default value to return when $key is empty.
	 * @since 1.0.5
	 * @return mixed
	 */
	public function getAttribute ( string $key, $default )
	{ return $this->_attributes[$key] ?? $default; }

	/**
	 * Get current request method.
	 * 
	 * @since 1.0.5
	 * @return string
	 */
	public function getMethod ( $method = 'GET' ) : string
	{ return $method; }
}