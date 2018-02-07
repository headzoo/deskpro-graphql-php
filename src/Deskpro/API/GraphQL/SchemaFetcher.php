<?php
namespace Deskpro\API\GraphQL;

/**
 * Class SchemaFetcher
 */
class SchemaFetcher
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * Constructor
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return Schema
     * @throws Exception\GraphQLException
     */
    public function fetch()
    {
        $data   = $this->client->execute($this->getQuery());
        $parsed = $this->parseResponse($data);
        
        return new Schema($parsed);
    }

    /**
     * @param array $data
     * 
     * @return array
     */
    protected function parseResponse(array $data)
    {
        $parsed = [];
        foreach($data['__schema']['types'] as $type) {
            if (strpos($type['name'], '__') === false) {
                $parsed[$type['name']] = $this->parseFields($type['fields'] ?: []);
            }
        }
        
        return $parsed;
    }

    /**
     * @param array $fields
     *
     * @return array
     */
    protected function parseFields(array $fields)
    {
        $parsed = [];
        foreach($fields as $field) {
            $parsed[$field['name']] = [
                'args' => $this->parseArgs($field['args']),
                'type' => $this->parseType($field['type']),
                'description' => $field['description']
            ];
        }
        
        return $parsed;
    }

    /**
     * @param array $args
     *
     * @return array
     */
    protected function parseArgs(array $args)
    {
        $parsed = [];
        foreach($args as $arg) {
            $parsed[$arg['name']] = $this->parseType($arg['type']);
        }
        
        return $parsed;
    }

    /**
     * @param array $type
     *
     * @return string
     */
    protected function parseType(array $type)
    {
        $name = $type['name'];
        if ($type['kind'] === 'LIST') {
            $name = $type['ofType']['name'] . '[]';
        }
        
        return $name;
    }

    /**
     * @return string
     */
    protected function getQuery()
    {
        return '
            query IntrospectionQuery {
              __schema {
                queryType {
                  name
                }
                mutationType {
                  name
                }
                subscriptionType {
                  name
                }
                types {
                  ...FullType
                }
                directives {
                  name
                  description
                  locations
                  args {
                    ...InputValue
                  }
                }
              }
            }
            
            fragment FullType on __Type {
              kind
              name
              description
              fields(includeDeprecated: true) {
                name
                description
                args {
                  ...InputValue
                }
                type {
                  ...TypeRef
                }
                isDeprecated
                deprecationReason
              }
              inputFields {
                ...InputValue
              }
              interfaces {
                ...TypeRef
              }
              enumValues(includeDeprecated: true) {
                name
                description
                isDeprecated
                deprecationReason
              }
              possibleTypes {
                ...TypeRef
              }
            }
            
            fragment InputValue on __InputValue {
              name
              description
              type {
                ...TypeRef
              }
              defaultValue
            }
            
            fragment TypeRef on __Type {
              kind
              name
              ofType {
                kind
                name
                ofType {
                  kind
                  name
                  ofType {
                    kind
                    name
                    ofType {
                      kind
                      name
                      ofType {
                        kind
                        name
                        ofType {
                          kind
                          name
                          ofType {
                            kind
                            name
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
        ';
    }
}