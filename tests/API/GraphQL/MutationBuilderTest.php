<?php
require_once('GraphQLTestCase.php');

use Deskpro\API\GraphQL\ClientInterface;
use Deskpro\API\GraphQL\MutationBuilder;

/**
 * Class MutationBuilderTest
 */
class MutationBuilderTest extends GraphQLTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ClientInterface
     */
    public $clientMock;

    /**
     * Run before each test
     */
    public function setUp()
    {
        $this->clientMock = $this->getMockBuilder(ClientInterface::class)->getMock();
    }
    
    public function testGetQuerySingleField()
    {
        $expected = '
            mutation GetNews ($id: ID!) {
                content_get_news(id: $newsId) {
                    title
                    content
                }
            }
        ';

        $fixture = new MutationBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $fixture->field('content_get_news', 'id: $newsId', [
            'title',
            'content'
        ]);

        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getMutation());
    }
    
    public function testGetQueryMultipleFields()
    {
        $expected = '
            mutation GetNews ($newsId: ID!, $articleId: ID!) {
                content_get_news(id: $newsId) {
                    title
                    content
                }
            
                content_get_articles(id: $articleId) {
                    title
                    content {
                        id
                    }
                }
            }
        ';

        $fixture = new MutationBuilder($this->clientMock, 'GetNews', [
            '$newsId'    => 'ID!',
            '$articleId' => 'ID!',
        ]);
        $fixture
            ->field('content_get_news', 'id: $newsId', [
                'title',
                'content'
            ])->field('content_get_articles', 'id: $articleId', [
                'title',
                'content' => [
                    'id'
                ]
            ]);

        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getMutation());
    }
    
    public function testExecute()
    {
        $fixture = new MutationBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $this->clientMock->method('execute')
            ->with($this->equalTo($fixture))
            ->willReturn([]);
        $fixture->execute(['id' => 1]);
    }
}