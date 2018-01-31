<?php
use Deskpro\API\GraphQL;

require(__DIR__ . '/../vendor/autoload.php');

$client = new GraphQL\Client('http://deskpro-dev.com');
$client->setAuthKey(1, 'dev-admin-code');

$query = $client->createQuery('GetNews', [
    'id' => 'ID!'
]);
$query->field('news: content_get_news', 'id: $id', [
    'title',
    'content'
]);

$data = $client->execute($query, [
    'id' => 1
]);
print_r($data['news']);