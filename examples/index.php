<?php
use Deskpro\API\GraphQL;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require(__DIR__ . '/../vendor/autoload.php');

$logger = new Logger('GraphQL');
$logger->pushHandler(new StreamHandler('graphql.log', Logger::DEBUG));

$client = new GraphQL\Client('http://deskpro-dev.com');
$client->setLogger($logger);

/*$query = $client->createQuery('GetNews', [
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
]);*/

$mutation = $client->createMutation('UpdateArticle', [
    '$id'      => 'Int',
    '$article' => 'ArticleTypeInput!'
])->field('content_update_articles', 'id: $id, article: $article');

$data = $mutation->execute([
    'id'      => 100,
    'article' => [
        'title' => 'Hello, World!'
    ]
]);
dump($data);
die();
$data = $mutation->getMutation();
echo $data;
die("\n");