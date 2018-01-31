<?php
use Deskpro\API\GraphQL;

require(__DIR__ . '/../vendor/autoload.php');

$client = new GraphQL\Client('http://deskpro-dev.com');
$client->setAuthKey(1, 'dev-admin-code');

$query = $client->createQuery('GetNews', [
    '$id' => 'ID!',
    '$withCategories' => 'Boolean!'
]);
$query->field('content_get_articles', 'id: $id', [
    'title',
    'categories' => $query->includeIf('$withCategories', [
        'id',
        'title'
    ])
]);

/*$data = $query->execute([
    'id' => 100,
    'withCategories' => true
]);*/
echo $query->getQuery();die();
print_r($data);
