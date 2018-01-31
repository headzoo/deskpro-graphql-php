<?php
use Deskpro\API\GraphQL;

require(__DIR__ . '/../vendor/autoload.php');

$client = new GraphQL\Client('http://deskpro-dev.com');
$client->setAuthKey(1, 'dev-admin-code');

$fragment = new GraphQL\Fragment('news_fragment', 'News', [
    'title',
    'content'
]);

$query = $client->createQuery('GetNews', '$id: ID!');
$query->field('content_get_news', 'id: $id', $fragment);

$data = $query->execute([
    'id' => 1
]);

print_r($data['content_get_news']);
