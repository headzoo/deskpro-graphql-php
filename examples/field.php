<?php
use Deskpro\API\GraphQL;

require(__DIR__ . '/../vendor/autoload.php');

$client = new GraphQL\Client('http://deskpro-dev.com');
$client->setAuthKey(1, 'dev-admin-code');

$query = $client->createQuery('GetNews', [
    '$id' => GraphQL\Type::id(false)
]);
$query->field('content_get_news', 'id: $id', [
    'title',
    'content'
]);

$data = $query->execute([
    'id' => 1
]);

print_r($data['content_get_news']);
