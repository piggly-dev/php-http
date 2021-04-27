<?php
namespace Piggly\Http\Interfaces;

use ArrayAccess;
use JsonSerializable;
use Serializable;

interface HttpPayloadInterface extends ArrayAccess, JsonSerializable, Serializable
{
	/**
	 * Import $values data to payload.
	 * 
	 * @param array $values
	 * @since 1.0.0
	 * @return void
	 */
	public function import ( array $values );

	/**
	 * Export all payload data to an array.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function toArray () : array;

	/**
	 * Export all payload data to a JSON string.
	 * 
	 * @since 1.0.0
	 * @return string
	 * @throws JsonEncodingException If can't parse JSON.
	 */
	public function toJson ( int $option = \JSON_ERROR_NONE, int $depth = 512 ) : string;
}