<?php
namespace Deskpro\API\GraphQL;

/**
 * Class Directive
 */
class Directive
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $condition;

    /**
     * @var array 
     */
    protected $fields = [];

    /**
     * Constructor
     *
     * @param string $type
     * @param string $condition
     * @param array $fields
     */
    public function __construct($type, $condition, $fields = [])
    {
        $this->setType($type);
        $this->setCondition($condition);
        $this->setFields($fields);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Directive
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param string $condition
     *
     * @return Directive
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     *
     * @return Directive
     */
    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }
}