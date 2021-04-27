# Request/Response for APIs

> This library was developed to meet Piggly team requirements

Today, there are a lot of ways to manipulate requests and responses at PHP. The most common pattern is following the PSR-7 interfaces *([see here](https://www.php-fig.org/psr/psr-7/))*, which makes easy to manipulate HTTP protocol data.

But, nothing is perfect. Some frameworks, smart customizations and even handcraft artists can create your own patterns for handle HTTP requests and responses. It makes hard to packing some data and create libraries without forcing standards.

For example, Laravel uses `Illuminate\Http` packages which has no **PSR-7** implementation. So, when creating a library which requires PSR-7 implementation, well... Laravel artists has to do some fixes to everythings work well an so on.

When we had to migrate an API built with Slim Framework to Laravel... was a headache. And, that is why our library exists. To solve it. By implementing smart abstract classes to request and response which can handle any request and response objects.

First of all, API has some behaviours attached to requests and responses. In general, from API requests we get body parameters, query string parameters  and headers. In other hands, at responses we include some data with status and messages, as:

* `status_code` *(int)*: with response code into API;
* `status_message` *(string)*: a supplemental message to request;
* `status_hint` *(string|array)*: helping to solve response issues or smart tips;
* `status_alerts` *(array)*: including alerts dispatched at code execution.

We also want to packing content data for responses as smart objects. Or even, transform request parameters to smart objects to better handle expected values and validation. And better, we can handle exceptions by automatically converting it to a response with no headache.

See below how everything works.

## `BaseRequest` object

The `BaseRequest` object defines the HTTP request for application. It's an abstract class which makes our original request object acessible and do three universal implementations:

* `header( string $name, $default = null )` method to get some header from request;
* `getQueryParams()` method to get all query string parameters as an `array`;
* `getParsedBody()` method to get all body parameter as an `array`.

And has some functions:

* `request()` method to make original request object acessible;
* `payloableBody( string $class )` method to convert body parameters to an object which implements `HttpPayloadInterface`;
* `payloableQuery( string $class )` method to convert query string parameters to an object which implements `HttpPayloadInterface`.

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

## `HttpPayloadInterface` implementation

The `HttpPayloadInterface` interface make any object available as an array or as a JSON string. Implementing the methods below:

* `import( array $values )` to get an array of values and set these values to object properties;
* `toArray()` to convert payload to an array of values;
* `toJson( int $option = \JSON_ERROR_NONE, int $depth = 512 )` to convert payload to a JSON string;

## `ResponseException` handler

Create an exception with response behavior. It will make easy just throw an exception to user which can be converted to a response object. And has some functions:

* `response()` method to make response object acessible;
* `handle()` method to handle response object.

There is also some default exceptions models to throw:

* `ResponseException::invalidParameter` throw when some body parameters values are invalid or malformed;
* `ResponseException::invalidQueryParameter` throw when some query parameters values are invalid or malformed;
* `ResponseException::invalidRequest` throw that request is invalid and has failed;
* `ResponseException::serverError` throw an server error exception.