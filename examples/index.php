<?php
use Deskpro\API\GraphQL;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require(__DIR__ . '/../vendor/autoload.php');

$logger = new Logger('GraphQL');
$logger->pushHandler(new StreamHandler('graphql.log', Logger::DEBUG));

$client = new GraphQL\Client('http://deskpro-dev.com');
$client->setAUthKey(1, 'dev-admin-code');
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
die();

/*$mutation = $client->createMutation('UpdateArticle', [
    '$id'      => 'Int',
    '$article' => 'ArticleTypeInput!'
])->field('content_update_articles', 'id: $id, article: $article');*/

/*$data = $mutation->execute([
    'id'      => 100,
    'article' => [
        'title' => 'Hello, World!'
    ]
]);
dump($data);
die();*/

$query = $client->createQuery('GetNews', '$id: ID!');
$query->field('content_get_news', [], 'title');
$data = $query->getQuery();
echo $data;
die("\n");