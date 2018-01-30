<?php
require_once('GraphQLTestCase.php');

use Deskpro\API\GraphQL\ClientInterface;
use Deskpro\API\GraphQL\QueryBuilder;

/**
 * Class QueryBuilderTest
 */
class QueryBuilderTest extends GraphQLTestCase
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
    
    public function testOperationArgsAsString()
    {
        $expected = '
            query GetNews ($id: ID!) {

            }
        ';

        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }
    
    public function testOperationArgsAsArray()
    {
        $expected = '
            query GetNews ($id: ID!) {
            
            }
        ';
        
        $fixture = new QueryBuilder($this->clientMock, 'GetNews', [
            '$id: ID!'
        ]);
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }
    
    public function testOperationArgsAsAssocArray()
    {
        $expected = '
            query GetNews ($id: ID!) {
            
            }
        ';
        
        $fixture = new QueryBuilder($this->clientMock, 'GetNews', [
            '$id' => 'ID!'
        ]);
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }
    
    public function testOperationArgsWithoutDollarSign()
    {
        $expected = '
            query GetNews ($id: ID!) {

            }
        ';

        $fixture = new QueryBuilder($this->clientMock, 'GetNews', 'id: ID!');
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }
    
    public function testFieldArgsAsString()
    {
        $expected = '
            query GetNews ($id: ID!) {
                content_get_news(id: $newsId)
            }
        ';

        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $fixture->field('content_get_news', 'id: $newsId');
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }
    
    public function testFieldArgsAsArray()
    {
        $expected = '
            query GetNews ($id: ID!) {
                content_get_news(id: $newsId)
            }
        ';
        
        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $fixture->field('content_get_news', ['id: $newsId']);
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }
    
    public function testFieldArgsAsAssocArray()
    {
        $expected = '
            query GetNews ($id: ID!) {
                content_get_news(id: $newsId)
            }
        ';
        
        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $fixture->field('content_get_news', ['id' => '$newsId']);
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }
    
    public function testFieldsAsString()
    {
        $expected = '
            query GetNews ($id: ID!) {
                content_get_news(id: $newsId) {
                    title
                    content
                }
            }
        ';
        
        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $fixture->field('content_get_news', 'id: $newsId', 'title, content');
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }
    
    public function testFieldsAsArray()
    {
        $expected = '
            query GetNews ($id: ID!) {
                content_get_news(id: $newsId) {
                    title
                    content
                }
            }
        ';

        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $fixture->field('content_get_news', 'id: $newsId', [
            'title',
            'content'
        ]);
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }
    
    public function testGetQuerySingleField()
    {
        $expected = '
            query GetNews ($id: ID!) {
                content_get_news(id: $newsId) {
                    title
                    content
                }
            }
        ';
        
        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $fixture->field('content_get_news', 'id: $newsId', [
            'title',
            'content'
        ]);
        
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }
    
    public function testGetQueryMultipleFields()
    {
        $expected = '
            query GetNews ($newsId: ID!, $articleId: ID!) {
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
        
        $fixture = new QueryBuilder($this->clientMock, 'GetNews', [
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
        
        $this->assertGraphQLQueriesAreEqual($expected, $fixture->getQuery());
    }
    
    public function testExecute()
    {
        $fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $this->clientMock->method('execute')
            ->with($this->equalTo($fixture))
            ->willReturn([]);
        $fixture->execute(['id' => 1]);
    }
}