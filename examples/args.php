<?php
use Deskpro\API\GraphQL;
use Deskpro\API\GraphQL\Type\GraphQLType as Type;

require(__DIR__ . '/../vendor/autoload.php');

$client = new GraphQL\GraphQLClient('http://deskpro-dev.com');

$query = $client->createQuery('GetNews', [
    '$articleId' => Type::nonNull(Type::id())
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