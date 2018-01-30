<?php
use Deskpro\API\GraphQL;
use Deskpro\API\GraphQL\Type\GraphQLType as Type;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require(__DIR__ . '/../vendor/autoload.php');

$logger = new Logger('GraphQL');
$logger->pushHandler(new StreamHandler('graphql.log', Logger::DEBUG));

$client = new GraphQL\GraphQLClient('http://deskpro-dev.com');
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
    'newsId'    => 1,
    'articleId' => 100
]);
dump($data);
die();
$data = $query->getQuery();
echo $data;
die("\n");