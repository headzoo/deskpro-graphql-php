<?php
namespace Deskpro\API\GraphQL;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class GraphQLClient
 */
class GraphQLClient implements GraphQLClientInterface
{
    use LoggerAwareTrait;
    
    /**
     * GraphQL endpoint
     */
    const GRAPHQL_PATH = '/api/v2/graphql';

    /**
     * The authentication header
     */
    const AUTH_HEADER = 'Authorization';
    
    /**
     * Key to use for token authentication
     */
    const AUTH_TOKEN_KEY = 'token';

    /**
     * Key to use for key authentication
     */
    const AUTH_KEY_KEY = 'key';
    
    /**
     * @var string
     */
    protected $helpdeskUrl;

    /**
     * @var Guzzle
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $authToken;
    /**
     * @var string
     */
    protected $authKey;

    /**
     * @var array
     */
    protected $defaultHeaders = [];

    /**
     * Constructor
     * 
     * @param string          $helpdeskUrl The base URL to the Deskpro instance
     * @param ClientInterface $httpClient  The HTTP client used to make requests
     * @param LoggerInterface $logger      Used to log requests
     */
    public function __construct($helpdeskUrl, ClientInterface $httpClient = null, LoggerInterface $logger = null)
    {
        $this->setHelpdeskUrl($helpdeskUrl);
        $this->setHTTPClient($httpClient ?: new Guzzle());
        $this->setLogger($logger ?: new NullLogger());
    }

    /**
     * Sets the person ID and authentication token
     *
     * @param int $personId The ID of the person being authenticated
     * @param string $token The authentication token
     *
     * @return $this
     */
    public function setAuthToken($personId, $token)
    {
        $this->authToken = sprintf("%d:%s", $personId, $token);

        return $this;
    }
    
    /**
     * Sets the person ID and authentication key
     *
     * @param int $personId The ID of the person being authenticated
     * @param string $key The authentication key
     *
     * @return $this
     */
    public function setAuthKey($personId, $key)
    {
        $this->authKey = sprintf("%d:%s", $personId, $key);

        return $this;
    }

    /**
     * Returns the base URL to the Deskpro instance
     *
     * @return string
     */
    public function getHelpdeskUrl()
    {
        return $this->helpdeskUrl;
    }

    /**
     * Sets the base URL to the Deskpro instance
     *
     * @param string $helpdeskUrl The base URL to the Deskpro instance
     *
     * @return $this
     */
    public function setHelpdeskUrl($helpdeskUrl)
    {
        $this->helpdeskUrl = rtrim($helpdeskUrl, '/');

        return $this;
    }

    /**
     * Returns the HTTP client used to make requests
     *
     * @return ClientInterface
     */
    public function getHTTPClient()
    {
        return $this->httpClient;
    }

    /**
     * Sets the HTTP client used to make requests
     *
     * @param ClientInterface $httpClient HTTP client used to make requests
     *
     * @return $this
     */
    public function setHTTPClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        
        return $this;
    }

    /**
     * Returns the headers sent with each request
     *
     * @return array
     */
    public function getDefaultHeaders()
    {
        return $this->defaultHeaders;
    }

    /**
     * Sets the headers sent with each request
     *
     * @param array $defaultHeaders The headers to send
     *
     * @return $this
     */
    public function setDefaultHeaders(array $defaultHeaders)
    {
        $this->defaultHeaders = $defaultHeaders;

        return $this;
    }

    /**
     * @param string $operationName
     * @param array $args
     * @return QueryBuilder
     */
    public function createQuery($operationName, array $args = [])
    {
        return new QueryBuilder($this, $operationName, $args);
    }

    /**
     * @param QueryBuilder|string $query
     * @param array $variables
     * 
     * @return array
     * 
     * @throws Exception\InvalidResponseException
     * @throws Exception\QueryErrorException
     */
    public function execute($query, array $variables = [])
    {
        $query = trim((string)$query);
        $sanitizedVariables = [];
        foreach($variables as $name => $variable) {
            if ($name[0] === '$') {
                $name = substr($name, 1);
            }
            $sanitizedVariables[$name] = $variable;
        }
        
        $req = $this->makeRequest([
            'query'     => $query,
            'variables' => $sanitizedVariables
        ]);
        $this->logger->debug(sprintf('POST %s', (string)$req->getUri()), [
            'query'     => $query,
            'variables' => $variables
        ]);
        $resp = $this->httpClient->send($req);

        return $this->makeResponse($resp);
    }

    /**
     * @param array $headers
     *
     * @return array
     */
    protected function makeHeaders(array $headers = [])
    {
        $headers = array_merge($this->defaultHeaders, $headers);
        if (!isset($headers[self::AUTH_HEADER])) {
            if ($this->authToken) {
                $headers[self::AUTH_HEADER] = sprintf('%s %s', self::AUTH_TOKEN_KEY, $this->authToken);
            } else if ($this->authKey) {
                $headers[self::AUTH_HEADER] = sprintf('%s %s', self::AUTH_KEY_KEY, $this->authKey);
            }
        }
        return $headers;
    }

    /**
     * @param mixed $body
     * @param array $headers
     *
     * @return Request
     */
    protected function makeRequest($body = null, array $headers = [])
    {
        if (!is_string($body)) {
            $body = json_encode($body);
        }
        $url     = $this->helpdeskUrl . self::GRAPHQL_PATH;
        $headers = $this->makeHeaders($headers);;

        return new Request('POST', $url, $headers, $body);
    }

    /**
     * @param Response $resp
     * 
     * @return array
     * 
     * @throws Exception\InvalidResponseException
     * @throws Exception\QueryErrorException
     */
    protected function makeResponse(Response $resp)
    {
        $body = (string)$resp->getBody();
        $this->logger->debug("RESPONSE ${body}");

        $json = json_decode($body, true);
        if ($json === null) {
            throw new Exception\InvalidResponseException('Unable to JSON decode response.');
        }
        if (isset($json['errors'])) {
            throw new Exception\QueryErrorException($json['errors'][0]['message']);
        }
        if (!isset($json['data'])) {
            throw new Exception\InvalidResponseException();
        }

        return $json['data'];
    }
}