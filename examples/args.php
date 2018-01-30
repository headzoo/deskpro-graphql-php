<?php
use Deskpro\API\GraphQL;

require(__DIR__ . '/../vendor/autoload.php');

$client = new GraphQL\Client('http://deskpro-dev.com');

$query = $client->createQuery('GetNews', [
    '$articleId' => 'ID!'
])
->field('content_get_articles',
    [
        'id' => '$articleId'
    ],
    [
        'title',
        'content'
    ]
);

$data = $query->execute([
    'articleId' => 100
]);
print_r($data);