<?php
use Deskpro\API\GraphQL;

require(__DIR__ . '/../vendor/autoload.php');

$query = '
    query GetNews ($id: ID!) {
        content_get_news(id: $id) {
                title
                content
        }
    }
';

$client = new GraphQL\Client('http://deskpro-dev.com');

$data = $client->execute($query, [
    'id' => 1
]);
print_r($data['content_get_news']);