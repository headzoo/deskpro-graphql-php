<?php
namespace Deskpro\API\GraphQL;

/**
 * Class Schema
 */
class Schema
{
    /**
     * @var array 
     */
    protected static $scalarTypes = [
        'String',
        'Int',
        'ID',
        'Boolean',
        'Float'
    ];
    
    /**
     * @var array 
     */
    protected $fields = [];

    /**
     * Constructor
     *
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param string $fieldName
     *
     * @return array
     */
    public function get($fieldName)
    {
        if (substr($fieldName, -1) === '!') {
            $fieldName = substr($fieldName, 0, -1);
        }
        if (substr($fieldName, -2) === '[]') {
            $fieldName = substr($fieldName, 0, -2);
        }
        
        if (!isset($this->fields[$fieldName])) {
            return [];
        }
        
        return $this->fields[$fieldName];
    }

    /**
     * @return array
     */
    public function getQuery()
    {
        return $this->get('Query');
    }

    /**
     * @return array
     */
    public function getMutation()
    {
        return $this->get('Mutation');
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        $types = [];
        foreach($this->fields as $name => $fields) {
            if ($name === 'Query' || $name === 'Mutation' || in_array($name, self::$scalarTypes)) {
                continue;
            }
            
            $types[$name] = $fields;
        }
        
        return $types;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->fields;
    }
}