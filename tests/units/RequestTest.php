<?php
namespace Piggly\Tests\Http;

use PHPUnit\Framework\TestCase;
use Piggly\Dev\Http\EmulatedRequest;
use Piggly\Dev\Http\EmulatedResponse;
use Piggly\Dev\Payload\Person;

class RequestTest extends TestCase
{
	/**
	 * Emulated request data.
	 * @var EmulatedRequest
	 * @since 1.0.0
	 */
	protected $emuRequest;

	/**
	 * Emulated response data.
	 * @var EmulatedResponse
	 * @since 1.0.0
	 */
	protected $emuResponse;

	/**
	 * Setup base request/response for testing.
	 * 
	 * @since 1.0.0
	 * @return void
	 */
	protected function setUp () : void
	{
		$this->emuRequest = new EmulatedRequest([]);
		$this->emuResponse = new EmulatedResponse($this->emuRequest);
	}

	/** @test Convert request body to a valid payload data. */
	public function bodyToPayload ()
	{
		$payload = $this->emuRequest->payloableBody(Person::class);
		$this->assertInstanceOf(Person::class, $payload);
	}

	/** @test Convert payload data to array. */
	public function payloadToResponse ()
	{
		$payload = $this->emuRequest->payloableBody(Person::class);
		$this->emuResponse->payload($payload);
		$this->assertSame($this->emuRequest->getParsedBody(), $this->emuResponse->getContent());
	}
}