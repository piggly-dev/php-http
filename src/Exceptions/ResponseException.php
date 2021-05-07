<?php
namespace Piggly\Http\Exceptions;

use Exception;
use Piggly\Http\BaseResponse;
use Piggly\Payload\Interfaces\PayloadInterface;
use Throwable;

/**
 * A ResponseException which can be handled as an BaseResponse.
 * 
 * An exception means some error, and error does not include payloads.
 * But, you can manually include by calling $exception->response()->payload($payload)
 * before the $exception->handle() method.
 *
 * @since 1.0.0
 * @since 1.0.5 Remove $response property and handle requires BaseResponse
 * @package Piggly\Http
 * @subpackage Piggly\Http\Exceptions
 * @author Caique Araujo <caique@piggly.com.br>
 */
class ResponseException extends Exception
{
	/** @var int INVALID_REQUEST_PARAM_CODE Response code when request parameters are invalid. */
	const INVALID_REQUEST_PARAM_CODE = 5;
	/** @var int INVALID_REQUEST_CODE Response code when request data is invalid. */
	const INVALID_REQUEST_CODE = 15;
	/** @var int SERVER_ERROR_CODE Response code when handle a server error. */
	const SERVER_ERROR_CODE = 20;
	
	/**
	 * Exception HTTP Code.
	 * 
	 * @var int
	 * @since 1.0.5
	 */
	private $http_code;

	/**
	 * Exception hint.
	 * 
	 * @var string
	 * @since 1.0.5
	 */
	private $hint;

	/**
	 * Exception payload.
	 * 
	 * @var PayloadInterface
	 * @since 1.0.6
	 */
	private $payload;

	/**
	 * Create a new response exception.
	 * 
	 * @param string|null $message
	 * @param int $code
	 * @param int $http_code
	 * @param string|null $hint
	 * @param Throwable $previous Previous exception.
	 * @since 1.0.0
	 * @return self
	 */
	public function __construct (
		$message,
		$code,
		$http_code = 400,
		$hint = null,
		Throwable $previous = null
	)
	{
		parent::__construct($message, $code, $previous);

		$this->hint = $hint;
		$this->http_code = $http_code;
	}

	/**
	 * Get payload associated to response.
	 * 
	 * @since 1.0.6
	 * @return PayloadInterface|null
	 */
	public function getPayload () : ?PayloadInterface
	{ return $this->payload; }

	/**
	 * Set payload to response.
	 * 
	 * @param PayloadInterface $payload
	 * @since 1.0.6
	 * @return self
	 */
	public function payload ( PayloadInterface $payload )
	{ $this->payload = $payload; return $this; }

	/**
	 * Get response hint.
	 * 
	 * @since 1.0.5
	 * @return string|null
	 */
	public function getHint () : ?string
	{ return $this->hint; }

	/**
	 * Get response http code.
	 * 
	 * @since 1.0.5
	 * @return int|null
	 */
	public function getHttpCode () : ?int
	{ return $this->http_code; }

	/**
	 * Throw when some body parameters values are invalid or malformed.
	 * 
	 * @param array $parameters With parameters name.
	 * @param string|null $hint Response hint. As default show parameters name that has invalid value.
	 * @param string|null $message As default "Invalid parameters found => the request parameters are invalid or malformed."
	 * @param Throwable $previous
	 * @since 1.0.0
	 * @return self
	 */
	public static function invalidParameter ( 
		array $parameters, 
		$hint = null,
		$message = null,
		Throwable $previous = null 
	) : self
	{ 
		$hint = $hint ?? sprintf('The following parameters is invalid `%s`.', implode(', ', $parameters));

		return new static (
			$message ?? 'Invalid parameters found => the request parameters are invalid or malformed.',
			self::INVALID_REQUEST_PARAM_CODE,
			422,
			$hint,
			$previous
		);
	}

	/**
	 * Throw when some query parameters values are invalid or malformed.
	 * 
	 * @param array $parameters With parameters name.
	 * @param string|null $hint Response hint. As default show parameters name that has invalid value.
	 * @param string|null $message As default "Invalid parameters found => the request parameters are invalid or malformed."
	 * @param Throwable $previous
	 * @since 1.0.0
	 * @return self
	 */
	public static function invalidQueryParameter ( 
		array $parameters, 
		$hint = null,
		$message = null,
		Throwable $previous = null 
	) : self
	{ 
		$hint = $hint ?? sprintf('The following query parameters is invalid `%s`.', implode(', ', $parameters));
		
		return new static (
			$message ?? 'Invalid parameters found => the request parameters are invalid or malformed.',
			self::INVALID_REQUEST_PARAM_CODE,
			422,
			$hint,
			$previous
		);
	}

	/**
	 * Throw that request is invalid and has failed.
	 * 
	 * @param string|null $hint Response hint.
	 * @param string|null $message As default "The request data is invalid and has failed."
	 * @param Throwable $previous
	 * @since 1.0.0
	 * @return self
	 */
	public static function invalidRequest ( 
		$hint = null, 
		$message = null, 
		Throwable $previous = null 
	) : self
	{ 
		return new static (
			$message ?? 'The request data is invalid and has failed.',
			self::INVALID_REQUEST_CODE,
			400,
			$hint,
			$previous
		);
	}

	/**
	 * Throw an server error exception.
	 * 
	 * @param string|null $hint Response hint.
	 * @param string|null $message As default "Request has failed."
	 * @param Throwable $previous
	 * @since 1.0.0
	 * @return ResponseException
	 */
	public static function serverError (
		$hint = null,
		$message = null, 
		Throwable $previous = null
	) : ResponseException
	{
		return new ResponseException (
			$message ?? 'Request has failed.',
			self::SERVER_ERROR_CODE,
			500,
			$hint,
			$previous
		);
	}

	/**
	 * Handle response object returning the response.
	 * 
	 * @since 1.0.0
	 * @since 1.0.6 Added payload to response.
	 * @return mixed
	 */
	public function handle ( BaseResponse $response )
	{
		$response
			->code($this->code)
			->httpCode($this->http_code)
			->message($this->message)
			->hint($this->hint);

		if ( isset($this->payload) && !empty($this->payload) )
		{ $response->payload($this->payload); }
		
		$prev = $this->getPrevious();

		if ( $prev instanceof ResponseException )
		{
			if ( $prev->getCode() !== $this->getCode() )
			{ $response->hint( $prev->getHint() ); }
		}

		return $response->handle();
	}
}