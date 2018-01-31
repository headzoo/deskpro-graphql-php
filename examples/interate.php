<?php
use Deskpro\API\GraphQL;

require(__DIR__ . '/../vendor/autoload.php');

$client = new GraphQL\Client('http://deskpro-dev.com');
$client->setAuthKey(1, 'dev-admin-code');

$query = $client->createQuery('GetNews', [
    '$id' => 'ID!'
]);
$query->field('content_get_news', 'id: $id', [
    'title',
    'content'
]);

$rows = [];
$ids  = [1, 2, 3];
foreach($ids as $id) {
    $data = $query->execute([
        'id' => $id
    ]);
    $rows[$id] = $data['content_get_news'];
}

print_r($rows);
