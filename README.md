# Packing to Request/Response for APIs

> This library was developed to meet Piggly team requirements. It may not work to you, but work so well to us, expanding the flexibility of our HTTP management.

Today, there are a lot of ways to manipulate requests and responses at PHP. The most common pattern is following the PSR-7 interfaces *([see here](https://www.php-fig.org/psr/psr-7/))*, which makes easy to manipulate HTTP protocol data.

But, nothing is perfect. Some frameworks, smart customizations and even handcraft artists can create your own patterns for handle HTTP requests and responses. It makes hard to packing some data and create libraries without forcing standards.

For example, Laravel uses `Illuminate\Http` packages which has no **PSR-7** implementation. So, when you are creating an external global library which requires PSR-7 implementation, well... Laravel artists has to do some fixes to everythings work well an so on.

We know, you can get the **PSR-7** from Laravel Requests\Response, but then you have to go back if you want to use native Laravel HTTP resources and functions. Why so complicated? And, this is main reason to our library exists. To solve it. By implementing smart abstract classes to request and response which can handle any type request and response objects, still access the original request/response and use the most common methods.

First of all, this library was thought to APIs. An API has some behaviours attached to requests and responses. In general, from API requests we get body parameters, query string parameters and headers. 

We also want to packing content data for responses as smart objects. Or even, transform request parameters to smart objects to better handle expected values and validation. And better, we can handle exceptions by automatically converting it to a response with no headache.

To achieve it, at responses we include an standard `payload` with status and messages, as:

* `status_code` *(int)*: with response code into API;
* `status_message` *(string)*: a supplemental message to request;
* `status_hint` *(string|array)*: helping to solve response issues or smart tips;

The response `payload`, however, will have two different behavior. When, response `code` is equal to `BaseResponse::SUCCESS_CODE`, then `payload` will be the response. If not, then `payload` will be added to `body` key at response. See below:

Responses incluing `code` as `BaseResponse::SUCCESS_CODE`:

```json
{
	"first_name": "Caique Araujo",
	"last_name": "Araujo"
}
```

Any other responses codes:

```json
{
	"status_code": 12,
	"status_message": "Cannot connect to server.",
	"status_hint": "Your credentials are invalid.",
	"body": {
		"message": "Try again after %s",
		"timestamp": 1620337766
	}
}
```

From now, by usign this package, our libraries are much more flexibles. It doesn't care if you're using the pure `PHP` variables, PSR-7 interfaces or event `Illuminate\Http`. Just packing whatever Request and Response objects you are using to a basic `BaseRequest` and `BaseResponse` objects without losing your original objects.

See below how everything works.

## `BaseRequest` object

The `BaseRequest` object defines the HTTP request for application. It's an abstract class which makes our original request object acessible and do some universal implementations:

* `hasHeader( string $name )` method to check if a header is present on request;
* `header( string $name, $default = null )` method to get some header from request;
* `getHeaders()` method to get an array with all headers as an `array`;
* `getQueryParams()` method to get all query string parameters as an `array`;
* `getParsedBody()` method to get all body parameter as an `array`;
* `getFiles()` method to get all $_FILES as an `array`;
* `setAttribute( string $key, $default )` method to set a request attribute;
* `getAttribute( string $key, $default = null )` method to get a request attribute;
* `getMethod()` method to get the http request method;

And has some functions:

* `request()` method to make original request object acessible;
* `payloableBody( string $class )` method to convert body parameters to an object which implements `PayloadImportable`;
* `payloableQuery( string $class )` method to convert query string parameters to an object which implements `PayloadImportable`.

## `BaseResponse` object

The `BaseResponse` object defines the HTTP response for application. It's an abstract class which makes our original response object acessible and do one universal implementation:

* `handle()` method to handle current `BaseResponse` object to original response object.

And has some functions:

* `response()` method to make original response object acessible;
* `request( BaseRequest $request )` and `getRequest()` to manipulate request;
* `payload( HttpPayloadInterface $payload )` and `getPayload()` to manipulate response payload data;
* `hint( $hint )` and `getHint()` to manipulate response hints;
* `message( $message )` and `getMessage()` to manipulate response message;
* `code( int $code )` and `getCode()` to manipulate response code;
* `httpCode( int $http_code )` and `getHttpCode()` to manipulate response HTTP code;
* `header( string $name, $value )`, `withHeaders( array $headers )` and `getHeaders()` to manipulate response headers;
* `getContent()` which parses response data to an array;

## `PayloadRequest` and `PayloadResponse`

Both manage `payloads` for Requests and Responses. They are not required. However, `BaseResponse` will require any `PayloadInterface` to dealing with payloads.

## `ResponseException` handler

Create an exception that can be handled to a response. It will make easy just throw an exception to `client` which can be converted to a response object. And has some functions:

* `getPayload()` and `payload()` to manage response payload.
* `getHint()` to get response hint.
* `getHttpCode()` to get response HTTP code.
* `handle()` method to handle the response object.

There is also some default exceptions models to throw:

`method` | `code` | `http_code` | `message`
--- | --- | --- | ---
`ResponseException::invalidParameter` | 5 | 422 | Invalid parameters found => the request parameters are invalid or malformed.
`ResponseException::invalidQueryParameter` | 5 | 422 | Invalid parameters found => the request query parameters are invalid or malformed.
`ResponseException::invalidRequest` | 15 | 400 | The request data is invalid and has failed.
`ResponseException::serverError` | 20 | 500 | Request has failed.

## Changelog

See the [CHANGELOG](CHANGELOG.md) file for information about all code changes.

## Testing the code

This library uses the PHPUnit. We carry out tests of all the main classes of this application.

```bash
vendor/bin/phpunit
```

## Contributions

See the [CONTRIBUTING](CONTRIBUTING.md) file for information before submitting your contribution.

## Credits

- [Caique Araujo](https://github.com/caiquearaujo)
- [All contributors](../../contributors)

## Support the project

Piggly Studio is an agency located in Rio de Janeiro, Brazil. If you like this library and want to support this job, be free to donate any value to BTC wallet `3DNssbspq7dURaVQH6yBoYwW3PhsNs8dnK` ❤.

## License

MIT License (MIT). See [LICENSE](LICENSE).