<?php
use Deskpro\API\GraphQL;
use Deskpro\API\GraphQL\Type\GraphQLType as Type;

require(__DIR__ . '/../vendor/autoload.php');

$client = new GraphQL\GraphQLClient('http://deskpro-dev.com');

$query = $client->createQuery('GetNews', [
    'id' => Type::nonNull(Type::id())
]);
$query->field('news: content_get_news', 'id: $id', [
    'title',
    'content'
]);

$data = $client->execute($query, [
    'id' => 1
]);
print_r($data['news']);