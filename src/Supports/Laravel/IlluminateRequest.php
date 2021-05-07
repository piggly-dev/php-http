<?php
namespace Piggly\Http\Supports\Laravel;

use Piggly\Http\BaseRequest;

/**
 * Implementation to Illuminate\Http\Request
 * 
 * @since 1.0.6
 * @package Piggly\Http
 * @subpackage Piggly\Http\Supports
 * @author Caique Araujo <caique@piggly.com.br>
 */
class IlluminateRequest extends BaseRequest
{
	/**
	 * Check if a header $name exists.
	 * 
	 * @param string $name
	 * @since 1.0.0
	 * @since 1.0.6 Removed $default parameter.
	 * @return bool
	 */
	public function hasHeader ( string $name ) : bool
	{ return $this->_request->hasHeader($name); }

	/**
	 * Get a header data.
	 * 
	 * @param string $name
	 * @param mixed $default Default value when empty.
	 * @since 1.0.0
	 * @return mixed
	 */
	public function header ( string $name, $default = null )
	{ return $this->_request->header($name, $default); }

	/**
	 * Get all headers from original request object as array.
	 * Return an empty array if headers has no data.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function getHeaders () : array
	{ return $this->_request->headers->all(); }

	/**
	 * Get all query string parameters from original request object as array.
	 * Return an empty array if query string parameters has no data.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function getQueryParams () : array
	{ return $this->_request->query->all(); }

	/**
	 * Get all body data from original request object as array.
	 * Return an empty array if body has no data.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function getParsedBody () : array
	{ return $this->_request->request->all(); }

	/**
	 * Get all files data from original request object as array.
	 * Return an empty array if body has no data.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function getFiles () : array
	{ return $this->_request->files->all(); }

	/**
	 * Set request attribute.
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @since 1.0.5
	 * @return self
	 */
	public function setAttribute ( string $key, $value )
	{ $this->_request->attributes->set($key, $value); return $this; }

	/**
	 * Get request attribute.
	 * 
	 * @param string $key
	 * @param mixed $default Default value to return when $key is empty.
	 * @since 1.0.5
	 * @return mixed
	 */
	public function getAttribute ( string $key, $default )
	{ return $this->_request->attributes->get($key, $default); }

	/**
	 * Get current request method.
	 * 
	 * @since 1.0.5
	 * @return string
	 */
	public function getMethod () : string
	{ return $this->_request->getMethod(); }
}