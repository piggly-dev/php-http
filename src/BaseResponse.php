<?php
namespace Piggly\Http;

use JsonSerializable;
use Piggly\Payload\Interfaces\PayloadInterface;

/**
 * A BaseRequest object to support any type of Response object
 * and interact with response data in a smart way.
 *
 * @since 1.0.0
 * @package Piggly\Http
 * @subpackage Piggly\Http
 * @author Caique Araujo <caique@piggly.com.br>
 */
abstract class BaseResponse implements JsonSerializable
{
	/**
	 * Success code for response.
	 * 
	 * @var int SUCCESS_CODE
	 * @since 1.0.0
	 */
	const SUCCESS_CODE = 1;
	
	/**
	 * Response HTTP Code.
	 * 
	 * @var int
	 * @since 1.0.0
	 */
	private $http_code;

	/**
	 * Response Code.
	 * 
	 * @var int
	 * @since 1.0.0
	 */
	private $code;

	/**
	 * Response hint.
	 * 
	 * @var string
	 * @since 1.0.0
	 */
	private $hint;

	/**
	 * Response message.
	 * 
	 * @var string
	 * @since 1.0.0
	 */
	private $message;

	/**
	 * Response payload.
	 * 
	 * @var PayloadInterface
	 * @since 1.0.0
	 */
	private $payload;

	/**
	 * Response headers.
	 * 
	 * @var array
	 * @since 1.0.0
	 */
	private $headers = [];

	/**
	 * Request associated with response.
	 * 
	 * @var BaseRequest
	 * @since 1.0.0
	 */
	private $_request;

	/**
	 * Startup response with default headers.
	 * 
	 * @param BaseRequest $request
	 * @param array $headers HTTP Headers
	 * @since 1.0.0
	 * @return self
	 */
	public function __construct ( BaseRequest $request = null, array $headers = [] )
	{
		$this->withHeaders(\array_merge([
			'Content-Type' => 'application/json'
		], $headers));

		if ( !empty($request) )
		{ $this->request($request); }
	}

	/**
	 * Create a new response object.
	 * 
	 * @param BaseRequest $request
	 * @param int $code
	 * @param int $http_code
	 * @param string|null $message
	 * @param string|null $hint
	 * @param array $headers HTTP Headers
	 * @since 1.0.0
	 * @return BaseResponse
	 */
	public static function make (
		BaseRequest $request = null,
		int $code = self::SUCCESS_CODE,
		int $http_code = 200,
		$message = null,
		$hint = null,
		array $headers = []
	) : BaseResponse
	{
		$response = new BaseResponse($request, $headers);

		$response
			->code($code)
			->httpCode($http_code)
			->message($message)
			->hint($hint);

		return $response;
	}

	/**
	 * Get all HTTP headers to response.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function getHeaders () : array
	{ return $this->headers; }

	/**
	 * Add a new header to response.
	 * 
	 * @param string $name HTTP Header Name
	 * @param mixed $value
	 * @since 1.0.0
	 * @return self
	 */
	public function header ( string $name, $value )
	{ $this->headers[$name] = $value; return $this; }

	/**
    * Add an array of headers to the response.

	 * @param array $headers
	 * @since 1.0.0
	 * @return self
	 */
	public function withHeaders ( array $headers )
	{
		foreach ( $headers as $name => $value )
		{ $this->header($name, $value); }

		return $this;
	}

	/**
	 * Get response hint.
	 * 
	 * @since 1.0.0
	 * @return string|null
	 */
	public function getHint () : ?string
	{ return $this->hint; }
		
	/**
	 * Set response hint.
	 * 
	 * @param string|null $hint
	 * @since 1.0.0
	 * @return self
	 */
	public function hint ( $hint = null )
	{ $this->hint = $hint; return $this; }

	/**
	 * Get response message.
	 * 
	 * @since 1.0.0
	 * @return string|null
	 */
	public function getMessage () : ?string
	{ return $this->message; }
		
	/**
	 * Set response message.
	 * 
	 * @param string|null $message
	 * @since 1.0.0
	 * @return self
	 */
	public function message ( $message = null )
	{ $this->message = $message; return $this; }

	/**
	 * Get response code.
	 * 
	 * @since 1.0.0
	 * @return int|null
	 */
	public function getCode () : ?int
	{ return $this->code; }
		
	/**
	 * Set response code.
	 * 
	 * @param int $code
	 * @since 1.0.0
	 * @return self
	 */
	public function code ( int $code )
	{ $this->code = $code; return $this; }

	/**
	 * Get response http code.
	 * 
	 * @since 1.0.0
	 * @return int|null
	 */
	public function getHttpCode () : ?int
	{ return $this->http_code; }
		
	/**
	 * Set response http code.
	 * 
	 * @param int $http_code
	 * @since 1.0.0
	 * @return self
	 */
	public function httpCode ( int $http_code )
	{ $this->http_code = $http_code; return $this; }

	/**
	 * Get request associated to response.
	 * 
	 * @since 1.0.0
	 * @return BaseRequest|null
	 */
	public function getRequest () : ?BaseRequest
	{ return $this->_request; }
		
	/**
	 * Set request to response.
	 * 
	 * @param BaseRequest $request
	 * @since 1.0.0
	 * @return self
	 */
	public function request ( BaseRequest $request )
	{ $this->_request = $request; return $this; }

	/**
	 * Get payload associated to response.
	 * 
	 * @since 1.0.0
	 * @return PayloadInterface|null
	 */
	public function getPayload () : ?PayloadInterface
	{ return $this->payload; }

	/**
	 * Set payload to response.
	 * 
	 * @param PayloadInterface $payload
	 * @since 1.0.0
	 * @return self
	 */
	public function payload ( PayloadInterface $payload )
	{ $this->payload = $payload; return $this; }

	/**
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
	abstract public function handle ();

	/**
	 * Get current $payload and add to it response status
	 * properties, as:
	 * 
	 * 	status_code: (int) Code to response.
	 * 	status_message: (string) Message associated with response. Include details about response.
	 * 	status_hint: (string) Hint to response.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function getContent () : array
	{
		/** @var array $payload Get current payload */
		$payload = [];

		if ( $this->code !== self::SUCCESS_CODE )
		{
			$payload['status_code'] = $this->code;
			
			if ( !empty( $this->message ) )
			{ $payload['status_message'] = $this->message; }

			if ( !empty($this->payload) )
			{ $payload['data'] = $this->payload->toArray(); }
		}
		else
		{ $payload = array_merge($payload, $this->payload->toArray()); }

		if ( !empty( $this->hint ) )
		{ $payload['status_hint'] = $this->hint; }

		return $payload;
	}
  
	/**
	 * Prepare the resource for JSON serialization.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function jsonSerialize()
	{ return $this->getContent(); }
}