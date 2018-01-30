<?php
require('GraphQLQueriesAreEqualConstraint.php');

use PHPUnit\Framework\TestCase;
use Deskpro\API\GraphQL\ClientInterface;
use Deskpro\API\GraphQL\QueryBuilder;

/**
 * @coversDefaultClass \Deskpro\API\GraphQL\QueryBuilder
 */
class QueryBuilderTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockBuilder|ClientInterface
     */
    public $clientMock;
    
    /**
     * @var QueryBuilder
     */
    public $fixture;
    
    /**
     * Run before each test
     */
    public function setUp()
    {
        $this->clientMock = $this->getMockBuilder(ClientInterface::class)->getMock();
        $this->fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
    }

    /**
     * @covers ::getQuery
     * @covers ::field
     */
    public function testGetQuerySingleField()
    {
        $this->fixture->field('content_get_news', 'id: $newsId', [
            'title',
            'content'
        ]);
        
        $actual   = $this->fixture->getQuery();
        $expected = '
            query GetNews ($id: ID!) {
                content_get_news(id: $newsId) {
                    title
                    content
                }
            }
        ';
        
        $this->assertGraphQLQueriesAreEqual($expected, $actual);
    }

    /**
     * @covers ::getQuery
     * @covers ::field
     */
    public function testGetQueryMultipleFields()
    {
        $this->fixture = new QueryBuilder($this->clientMock, 'GetNews', [
            '$newsId'    => 'ID!',
            '$articleId' => 'ID!',
        ]);
        $this->fixture
        ->field('content_get_news', 'id: $newsId', [
            'title',
            'content'
        ])->field('content_get_articles', 'id: $articleId', [
            'title',
            'content' => [
                'id'
            ]
        ]);

        $actual   = $this->fixture->getQuery();
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

        $this->assertGraphQLQueriesAreEqual($expected, $actual);
    }

    /**
     * @covers ::getQuery
     */
    public function testOperationArgs()
    {
        $expected = '
            query GetNews ($id: ID!) {
            
            }
        ';

        $this->fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $this->assertGraphQLQueriesAreEqual($expected, $this->fixture->getQuery());

        $this->fixture = new QueryBuilder($this->clientMock, 'GetNews', [
            '$id: ID!'
        ]);
        $this->assertGraphQLQueriesAreEqual($expected, $this->fixture->getQuery());
        
        $this->fixture = new QueryBuilder($this->clientMock, 'GetNews', [
            '$id' => 'ID!'
        ]);
        $this->assertGraphQLQueriesAreEqual($expected, $this->fixture->getQuery());
    }

    /**
     * @covers ::getQuery
     */
    public function testFieldArgs()
    {
        $expected = '
            query GetNews ($id: ID!) {
                content_get_news(id: $newsId)
            }
        ';

        $this->fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $this->fixture->field('content_get_news', 'id: $newsId');
        $this->assertGraphQLQueriesAreEqual($expected, $this->fixture->getQuery());

        $this->fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $this->fixture->field('content_get_news', ['id: $newsId']);
        $this->assertGraphQLQueriesAreEqual($expected, $this->fixture->getQuery());

        $this->fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $this->fixture->field('content_get_news', ['id' => '$newsId']);
        $this->assertGraphQLQueriesAreEqual($expected, $this->fixture->getQuery());
    }

    /**
     * @covers ::getQuery
     */
    public function testFields()
    {
        $expected = '
            query GetNews ($id: ID!) {
                content_get_news(id: $newsId) {
                    title
                    content
                }
            }
        ';
        
        $this->fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $this->fixture->field('content_get_news', 'id: $newsId', [
            'title',
            'content'
        ]);
        $this->assertGraphQLQueriesAreEqual($expected, $this->fixture->getQuery());

        $this->fixture = new QueryBuilder($this->clientMock, 'GetNews', '$id: ID!');
        $this->fixture->field('content_get_news', 'id: $newsId', 'title, content');
        $this->assertGraphQLQueriesAreEqual($expected, $this->fixture->getQuery());
    }

    /**
     * @param string $expected
     * @param string $actual
     * @param string $message
     */
    public static function assertGraphQLQueriesAreEqual($expected, $actual, $message = '')
    {
        self::assertThat($actual, new GraphQLQueriesAreEqualConstraint($expected), $message);
    }
}