<?php

use PHPUnit\Framework\TestCase;
use Deskpro\API\GraphQL;

class VerifyOperationsTest extends TestCase
{
    /**
     * @dataProvider providesQueries
     * 
     * @param string $name
     * @param array $args
     * @param array $fields
     */
    public function testQueries($name, $args, $fields)
    {
        $fieldArgs = [];
        $values = [];
        foreach($args as $arg => $type) {
            $fieldArgs[$arg] = '$' . $arg;
            $values[$arg] = $this->getValueType($type);
        }
        
        $client = new GraphQL\Client('http://deskpro-dev.com');
        $query = $client->createQuery('Verify', $args);
        $query->field($name, $fieldArgs, array_keys($fields));
        
        try {
            $result = $query->execute($values);
        } catch (GraphQL\Exception\NotFoundException $e) {
            
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'sub selection') === false) {
                echo '"' . $name . '",' . "\n";
            }
        }
    }

    /**
     * @return array
     */
    public function providesQueries()
    {
        $queries = file_get_contents('http://deskpro-dev.com/api/v2/graphql/doc/queries.json');
        $queries = json_decode($queries, true);
        
        $arr = [];
        foreach($queries as $name => $values) {
            $arr[] = [$name, $values['args'], $values['fields']];
        }
        
        return $arr;
    }

    /**
     * @param string $type
     * @return bool|float|int|string
     */
    protected function getValueType($type)
    {
        if (preg_match('/ID/', $type)) {
            return 1;
        } else if (preg_match('/Integer/', $type)) {
            return 1;
        } else if (preg_match('/String/', $type)) {
            return "";
        } else if (preg_match('/Boolean/', $type)) {
            return true;
        } else if (preg_match('/Float', $type)) {
            return 0.0;
        } else {
            return 0;
        }
    }
}