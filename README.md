Deskpro PHP GraphQL Client
==========================
PHP library that queries the Deskpro GraphQL API.

* [Installing](#installing)
* [Basic Usage](#basic-usage)
* [Default Headers](#default-headers)
* [Logging](#logging)
* [Guzzle](#guzzle)
* [Testing](#testing)

## Requirements

* PHP 5.5+ with Composer

## Installing

```
composer require deskpro/graphql-php
```

## Basic Usage

```php
<?php
use Deskpro\API\GraphQL\GraphQLClient;
use Deskpro\API\GraphQL\Exception\GrapQLException;

$client = new GraphQLClient('https://deskpro.company.com');
$client->setAuthKey(1, 'dev-admin-code');

$query = '
    query GetNews ($id: ID!) {
        content_get_news(id: $id) {
                title
                content
        }
    }
';

try {
    $data = $client->execute($query, [
        'id' => 1
    ]);
    print_r($data);
    
} catch (GrapQLException $e) {
    echo $e->getMessage();
}
```

#### Query Builder
Using the query builder.

```php
<?php
use Deskpro\API\GraphQL\GraphQLClient;
use Deskpro\API\GraphQL\Exception\GrapQLException;

$client = new GraphQLClient('https://deskpro.company.com');
$client->setAuthKey(1, 'dev-admin-code');

$query = $client->createQuery('GetNews', [
    '$id' => 'ID!'
])->field('content_get_news', 'id: $id', [
    'title',
    'content'
]);

try {
    $data = $query->execute([
        'id' => 1
    ]);
    print_r($data);
    
} catch (GrapQLException $e) {
    echo $e->getMessage();
}
```

#### Multiple Fields

```php
<?php
use Deskpro\API\GraphQL\GraphQLClient;
use Deskpro\API\GraphQL\Exception\GrapQLException;

$client = new GraphQLClient('http://deskpro-dev.com');

$query = $client->createQuery('GetNews', [
    '$newsId'    => 'ID!',
    '$articleId' => 'ID!'
])->field('content_get_news', 'id: $newsId', [
    'title',
    'content'
])->field('content_get_articles', 'id: $articleId', [
    'title',
    'content'
]);

try {
    $data = $query->execute([
        'newsId'    => 1,
        'articleId' => 100
    ]);
    print_r($data);
    
} catch (GrapQLException $e) {
    echo $e->getMessage();
}
```


## Default Headers
Custom headers may be sent with each request by passing them to the `setDefaultHeaders()` method.

```php
<?php
use Deskpro\API\GraphQL\GraphQLClient;

$client = new GraphQLClient('https://deskpro.company.com');
$client->setDefaultHeaders([
    'X-Custom-Value' => 'foo'
]);
```

## Logging
Requests may be logged by providing an instance of `Psr\Log\LoggerInterface` to the `setLogger()` method.

```php
<?php
use Deskpro\API\GraphQL\GraphQLClient;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('name');
$logger->pushHandler(new StreamHandler('path/to/your.log', Logger::DEBUG));

$client = new GraphQLClient('https://deskpro.company.com');
$client->setLogger($logger);
```

## Guzzle
Guzzle is used to make HTTP requests. A default Guzzle client will be used unless one is provided.

```php
<?php
use Deskpro\API\GraphQL\GraphQLClient;
use GuzzleHttp\Client;

$httpClient = new Client([
    'timeout' => 60
]);

$client = new GraphQLClient('https://deskpro.company.com');
$client->setHTTPClient($guzzle);
```

## Testing
The composer "test" script runs the PHPUnit tests.

```
composer test
```
