<?php
namespace Piggly\Http\Exceptions;

use Exception;
use Piggly\Http\BaseResponse;
use Throwable;

class ResponseException extends Exception
{
	/** @var int INVALID_REQUEST_PARAM_CODE Response code when request parameters are invalid. */
	const INVALID_REQUEST_PARAM_CODE = 5;
	/** @var int INVALID_REQUEST_CODE Response code when request data is invalid. */
	const INVALID_REQUEST_CODE = 21;
	/** @var int SERVER_ERROR_CODE Response code when handle a server error. */
	const SERVER_ERROR_CODE = 18;

	/**
	 * Response created to exception.
	 * 
	 * @var BaseResponse
	 * @since 1.0.0
	 */
	protected $_response;

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
		$this->_response = BaseResponse::make($code, $http_code, $message, $hint);
	}

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
	 * Get current response associated with expection.
	 * 
	 * @since 1.0.0
	 * @return BaseResponse
	 */
	public function response () : BaseResponse
	{ return $this->_response; }

	/**
	 * Handle response object returning the response.
	 * 
	 * @since 1.0.0
	 * @return mixed
	 */
	public function handle ()
	{
		$prev = $this->getPrevious();

		if ( $prev instanceof ResponseException )
		{
			if ( $prev->response()->getCode() !== $this->response()->getCode() )
			{ $this->response()->hint( $prev->response()->getContent() ); }
		}

		return $this->response()->handle();
	}
}