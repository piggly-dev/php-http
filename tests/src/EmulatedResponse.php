<?php
namespace Piggly\Dev\Http;

use Piggly\Http\BaseResponse;

class EmulatedResponse extends BaseResponse
{
	/**
	 * EMULATED HANDLE REQUEST.
	 * Handle the current response object to your application
	 * returning the expected response.
	 * 
	 * 	content -> $this->getContent();
	 * 	headers -> $this->getHeaders();
	 * 	code -> $this->getCode();
	 * 	httpCode -> $this->getHttpCode();
	 * 
	 * @since 1.0.0
	 * @return mixed
	 */
	public function handle ()
	{ return json_encode($this); }
}