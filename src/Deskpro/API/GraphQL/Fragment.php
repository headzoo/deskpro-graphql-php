<?php

namespace Deskpro\API\GraphQL;

class Fragment
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $onType;

    /**
     * @var array 
     */
    protected $fields = [];

    /**
     * Constructor
     *
     * @param string $name
     * @param string $onType
     * @param array $fields
     */
    public function __construct($name, $onType, $fields = [])
    {
        $this->setName($name);
        $this->setOnType($onType);
        $this->setFields($fields);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Fragment
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getOnType()
    {
        return $this->onType;
    }

    /**
     * @param string $onType
     *
     * @return Fragment
     */
    public function setOnType($onType)
    {
        $this->onType = $onType;

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
     * @return Fragment
     */
    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }
}