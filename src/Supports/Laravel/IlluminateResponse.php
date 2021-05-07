<?php
namespace Piggly\Http\Supports\Laravel;

use Piggly\Http\BaseResponse;

/**
 * Implementation to Illuminate\Http\Response
 * 
 * @since 1.0.6
 * @package Piggly\Http
 * @subpackage Piggly\Http\Supports
 * @author Caique Araujo <caique@piggly.com.br>
 */
class IlluminateResponse extends BaseResponse
{
	/**
	 * Handle the current response redirect object to your application
	 * returning the expected response.
	 * 
	 * @param array $uri URI to redirect.
	 * @param int $status HTTP status code.
	 * @param array $headers Headers.
	 * @since 1.0.6
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function _redirect ( 
		string $uri, 
		int $status = 302, 
		array $headers = []
	)
	{ return redirect()->away($uri)->setStatusCode($status)->withHeaders($headers); }

	/**
	 * Handle the current response object to your application
	 * returning the expected response.
	 * 
	 * @param array $content Body.
	 * @param int $status HTTP status code.
	 * @param array $headers Headers.
	 * @param BaseResponse $_response The base response object.
	 * @since 1.0.0
	 * @since 1.0.6 Changed function behavior
	 * @return \Illuminate\Http\Response
	 */
	protected function _handle (
		array $content, 
		int $status, 
		array $headers
	)
	{ return response()->json( $content, $status )->withHeaders($headers); }
}