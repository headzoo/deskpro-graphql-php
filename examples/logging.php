<?php
use Deskpro\API\GraphQL;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require(__DIR__ . '/../vendor/autoload.php');

$logger = new Logger('GraphQL');
$logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));

$client = new GraphQL\Client('http://deskpro-dev.com');
$client->setAuthKey(1, 'dev-admin-code');
$client->setLogger($logger);

$query = $client->createQuery('GetNews', [
    '$newsId'    => 'ID!',
    '$articleId' => 'ID!',
])->field('content_get_news', 'id: $newsId', [
    'title',
    'content'
])->field('content_get_articles', 'id: $articleId', [
    'title',
    'content',
    'categories' => [
        'id',
        'title'
    ]
]);

$data = $query->execute([
    'newsId' => 1,
    'articleId' => 100
]);
dump($data);
