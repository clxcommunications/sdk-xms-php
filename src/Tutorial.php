<?php

/**
 * Contains a tutorial on how to use this PHP SDK.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms;

/**
 * An empty class whose documentation is a tutorial
 *
 * The purpose of this document is to present the basic concepts of
 * the CLX Communications HTTP REST Messaging API and how to use it
 * from PHP using the HTTP REST Messaging API SDK.
 *
 * HTTP REST Messaging API basics
 * ------------------------------
 *
 * HTTP REST Messaging API is a REST API that is provided by CLX
 * Communications for sending and receiving SMS messages. It also
 * provides various other services supporting this task such as
 * managing groups of recipients, tagging, and so forth.
 *
 * Note, for brevity we will in this document refer to HTTP REST
 * Messaging API as _XMS API_ and the HTTP REST Messaging API service
 * or HTTP endpoint as _XMS_.
 *
 * A great benefit of the XMS API is that it allows you to easily
 * create and send _batch SMS messages_, that is, SMS messages that
 * can have multiple recipients. When creating a batch message it is
 * possible to use _message templates_, which allows each recipient to
 * receive a personalized message.
 *
 * To use XMS it is necessary to have a _service plan identifier_ and
 * an _authentication token_, which can be obtained by creating an XMS
 * service plan.
 *
 * For full documentation of the XMS API please refer to the [REST API
 * documentation
 * site](https://www.clxcommunications.com/docs/sms/http-rest.html).
 * The documentation site contains up-to-date information about, for
 * example, status and error codes.
 *
 * Interacting with XMS through PHP
 * --------------------------------
 *
 * Using this PHP SDK, all interaction with XMS happens through an
 * _XMS client_, which can be created using the service plan
 * identifier and authentication token. Further configuration can be
 * performed on the XMS client but in the typical case a service plan
 * identifier and authentication token is sufficient.
 *
 * Once an XMS client has been created it is possible to send requests
 * to XMS and receive its responses. This is done by calling a
 * suitable method on the XMS client, supplying arguments as
 * necessary, and receiving the response as the return value.
 *
 * This SDK has a focus on asynchronous operation and all interaction
 * with XMS happens asynchronously. Therefore, while synchronous
 * methods are supplied within the library their use is discouraged in
 * most practical applications.
 *
 * The arguments passed to a connection method are sometimes very
 * simple, fetching a previously create batch simply requires the
 * batch identifier as argument. Other times the arguments are
 * complicated, for example to create the batch it may be necessary to
 * supply a large number of arguments that specify destination
 * addresses, the message body, expiry times, and so on. For such
 * complex arguments we use classes whose methods correspond to the
 * different parameters that are relevant for the request.
 *
 * In general the terms used in XMS carry through to the PHP API with
 * one major exception. The REST API uses the terms _to_ and _from_ to
 * indicate a message originator and message destination,
 * respectively. In the PHP API these are instead denoted _recipient_
 * and _sender_. The cause of this name change is to have less
 * confusing and more idiomatic PHP method names.
 *
 * Connection management
 * ---------------------
 *
 * The first step in using the XMS SDK is to create an XMS client
 * object, this object is instantiated from the
 * {@link \Clx\Xms\Client}
 * class and it describes everything we need in order to talk with the
 * XMS API endpoint. The minimal amount of information needed are the
 * service plan identifier and the authentication token and, as
 * previously mentioned, these will be provided to you when creating
 * an XMS service.
 *
 * Assuming we have been given the service plan identifier "myplan"
 * and authentication token "mytoken" then an XMS client `$client` is
 * created as follows
 *
 * ```php
 * $client = \Clx\Xms\Client('myserviceplan', 'mytoken');
 * ```
 *
 * Once created the client can be used to interact with XMS in the
 * ways described in the following sections of this tutorial.
 *
 * By default the connection will use
 * `https://api.clxcommunications.com/xms` as XMS endpoint. This can
 * be overridden by providing an extra argument to the
 * {@link \Clx\Xms\Client} constructor. For example, the code
 *
 * ```php
 * $client = \Clx\Xms\Client(
 *     'myserviceplan',
 *     'mytoken',
 *     'https://my.test.host:3000/my/base/path'
 * );
 * ```
 *
 * would make the client object believe that the
 * [batches](https://www.clxcommunications.com/docs/sms/http-rest.html#batches-endpoint)
 * endpoint is
 * `https://my.test.host:3000/my/base/path/v1/myplan/batches`.
 *
 * Sending batches
 * ---------------
 *
 * Creating a batch is typically one of the first things one would
 * like to do when starting to use XMS. To create a batch we must
 * specify, at a minimum, the originating address (typically a short
 * code), one or more recipient addresses (typically MSISDNs), and the
 * message body. Sending a simple hello world message to one recipient
 * is then accomplished using
 *
 * ```php
 * $batchParams = new \Clx\Xms\Api\MtBatchTextSmsCreate()
 * $batchParams->setSender('12345');
 * $batchParams->setRecipients(['987654321']);
 * $batchParams->setBody('Hello, World!');
 * $result = $client->createTextBatch($batchParams);
 * ```
 *
 * You will notice a few things with this code. We are using a
 * `$client` variable that corresponds to an XMS client that we assume
 * has been previously created. We are calling the
 * {@link \Clx\Xms\Client::createTextBatch()}
 * method on the connection with a single argument that describes the
 * batch we wish to create.
 *
 * Describing the batch is done using an
 * {@link \Clx\Xms\Api\MtBatchTextSmsCreate}
 * object. For a batch with a binary body you would similarly describe
 * it using an
 * {@link \Clx\Xms\Api\MtBatchBinarySmsCreate}
 * object.
 *
 * The return value of a batch create call is a
 * {@link \Clx\Xms\Api\MtBatchTextSmsResult} or
 * {@link \Clx\Xms\Api\MtBatchBinarySmsResult}
 * object that contains not only the submitted batch information but
 * also information included by XMS, such that the unique batch
 * identifier, the creation time, etc. For example, to simply print
 * the batch identifier we could add the code
 *
 * ```php
 * echo("Batch id is " . $result->getBatchId() ."\n");
 * ```
 *
 * It is not much harder to create a more complicated batch, for
 * example, here we create a parameterized message with multiple
 * recipients and a scheduled send time:
 *
 * ```php
 * $batchParams = new \Clx\Xms\Api\MtBatchTextSmsCreate();
 * $batchParams->setSender('12345');
 * $batchParams->setRecipients(['987654321', '123456789', '567894321']);
 * $batchParams->setBody('Hello, ${name}!');
 * $batchParams->setParameters(
 *     [
 *         'name' => [
 *             '987654321' => 'Mary',
 *             '123456789' => 'Joe',
 *             'default' => 'valued customer'
 *         ]
 *     ]
 * );
 * $batchParams->setSendAt(new DateTime('2016-12-20 10:00 UTC'));
 * $batch = $client->createTextBatch($batchParams);
 * ```
 *
 * Fetching batches
 * ----------------
 *
 * If you have a batch identifier and would like to retrieve
 * information concerning that batch then it is sufficient to use the
 * {@link \Clx\Xms\Client::fetchBatch()} method. Thus, if the desired
 * batch identifier is available in the variable `$batchId` then one
 * could write
 *
 * ```php
 * $batchId = // …
 * $result = $client->fetchBatch($batchId);
 * echo("Batch id is " . $result->getBatchId() . "\n");
 * ```
 *
 * Note, since {@link \Clx\Xms\Client::fetchBatch() fetchBatch} does
 * not know ahead of time whether the fetched batch is textual or
 * binary it returns a value of the type
 * {@link \Clx\Xms\Api\MtBatchSmsResult}. This type is the base class
 * of {@link \Clx\Xms\Api\MtBatchTextSmsResult} and
 * {@link \Clx\Xms\Api\MtBatchBinarySmsResult} and you may need to use
 * `instanceof` to determine the actual type.
 *
 * Listing batches
 * ---------------
 *
 * Once you have created a few batches it may be interesting to
 * retrieve a list of all your batches. Retrieving listings of batches
 * is done through a _paged result_. This means that a single request
 * to XMS may not retrieve all batches. As a result, when calling the
 * {@link \Clx\Xms\Client::fetchBatches()} method on your XMS client
 * it will not simply return a list of batches but rather a
 * {@link \Clx\Xms\Api\Pages} object. The pages object in turn can be
 * used to fetch specific pages or iterate over all available pages
 * while transparently performing necessary page requests.
 *
 * To limit the number of batches in the list it is also possible to
 * supply a filter that will restrict the fetched batches, for example
 * to those sent after a particular date or having a specific tag or
 * sender.
 *
 * More specifically, to print the identifier of each batch sent on
 * 2016-12-01 and having the tag "signup_notification", we may write
 * something like the following.
 *
 * ```php
 * $filter = new \Clx\Xms\BatchFilter()
 * $filter->setAddTag('signup_notification');
 * $filter->setStartDate(new DateTime('2016-12-01'));
 * $filter->setEndDate(new DateTime('2016-12-02'));
 *
 * $pages = $client->fetchBatches($filter);
 *
 * foreach ($pages as $page) {
 *     foreach ($page as $batch) {
 *         echo("Batch ID: " . $batch->getBatchId() . "\n");
 *     }
 * }
 * ```
 *
 * Other XMS requests
 * ------------------
 *
 * We have only shown explicitly how to create, list and fetch batches
 * but the same principles apply to all other XMS calls within the
 * SDK. For example, to fetch a group one could use the previously
 * given instructions for fetching batches and simply use
 * {@link \Clx\Xms\Client::fetchGroup()} with a group identifier.
 *
 * Canceling a batch and deleting a group is the same as fetching with
 * the exception that they do not return any result.
 *
 * Handling errors
 * ---------------
 *
 * Any error that occurs during an API operation will result in an
 * exception being thrown. The exceptions produced specifically by the
 * SDK all implement the {@link \Clx\Xms\ApiException} interface and
 * they are
 *
 * {@link \Clx\Xms\ErrorResponseException}
 * :   If the XMS server responded with a JSON error object containing
 *     an error code and error description.
 *
 * {@link \Clx\Xms\HttpCallException}
 * :   If the HTTP client library could not be initialized or an HTTP
 *     request failed. This may be due to a malformed URL or the XMS
 *     server could not be reached.
 *
 * {@link \Clx\Xms\NotFoundException}
 * :   If the XMS server response indicated that the desired resource
 *     does not exist. In other words, if the server responded with
 *     HTTP status 404 Not Found. During a fetch batch or group
 *     operation this exception would typically indicate that the batch
 *     or group identifier is incorrect.
 *
 * {@link \Clx\Xms\UnauthorizedException}
 * :   Thrown if the XMS server determined that the authentication
 *     token was invalid for the service plan.
 *
 * {@link \Clx\Xms\UnexpectedResponseException}
 * :   If the XMS server responded in a way that the SDK did not expect
 *     and cannot handle, the complete HTTP response body can be
 *     retrieved from the exception object using the
 *     {@link \Clx\Xms\UnexpectedResponseException::getHttpBody()}
 *     method.
 *
 * Due to the use of exceptions, a typical XMS operation in the PHP
 * SDK is surrounded by a try-catch statement such as
 *
 * ```php
 * try {
 *     // Invoke synchronous XMS client call here.
 * } catch (\Clx\Xms\ApiException $ex) {
 *     echo("Failed to communicate with XMS: " . $ex->getMessage() . "\n");
 * }
 * ```
 */
class Tutorial
{
}

?>