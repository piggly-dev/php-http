<?php
namespace Piggly\Dev\Http;

use Piggly\Http\BaseResponse;

class EmulatedResponse extends BaseResponse
{
	/**
	 * Handle the current response redirect object to your application
	 * returning the expected response.
	 * 
	 * @param array $uri URI to redirect.
	 * @param int $status HTTP status code.
	 * @param array $headers Headers.
	 * @since 1.0.6
	 * @return mixed
	 */
	protected function _redirect ( 
		string $uri, 
		int $status = 302, 
		array $headers = []
	)
	{ return $uri; }

	/**
	 * Handle the current response object to your application
	 * returning the expected response.
	 * 
	 * @param array $content Body.
	 * @param int $status HTTP status code.
	 * @param array $headers Headers.
	 * @since 1.0.0
	 * @since 1.0.6 Changed function behavior
	 * @return mixed
	 */
	protected function _handle ( 
		array $content, 
		int $status, 
		array $headers
	)
	{ return json_encode($content); }
}