<?php
namespace Deskpro\API\GraphQL;

/**
 * Class AbstractBuilder
 */
abstract class AbstractBuilder implements BuilderInterface
{
    /**
     * @var int
     */
    protected static $tabLength = 4;

    /**
     * @var string
     */
    protected static $regexValidateName = '/^[_a-z]+[_a-z0-9]*$/i';

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $operationName;

    /**
     * @var array
     */
    protected $operationArgs = [];

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var string
     */
    protected $cache;

    /**
     * Constructor
     *
     * @param ClientInterface $client Executes the query
     * @param string $operationName Name of the operation
     * @param array|string $operationArgs Operation arguments
     */
    public function __construct(ClientInterface $client, $operationName, $operationArgs = [])
    {
        $this->client = $client;
        $this->setOperationName($operationName);
        $this->setOperationArgs($operationArgs);
    }

    /**
     * {@inheritdoc}
     */
    public function field($name, $args = [], $fields = [])
    {
        $alias = null;
        if (preg_match('/^(.*?)\s*:\s*(.*?)$/i', $name, $matches)) {
            $alias = $matches[1];
            $name  = $matches[2];
        }

        if (!preg_match(self::$regexValidateName, $name)) {
            throw new Exception\QueryBuilderException(
                sprintf('Invalid field name must match %s', self::$regexValidateName)
            );
        }
        if ($alias && !preg_match(self::$regexValidateName, $alias)) {
            throw new Exception\QueryBuilderException(
                sprintf('Invalid alias must match %s', self::$regexValidateName)
            );
        }

        $this->fields[] = compact('name', 'alias', 'fields', 'args');
        $this->cache = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(array $args = [])
    {
        return $this->client->execute($this, $args);
    }

    /**
     * {@inheritdoc}
     */
    public function getOperationName()
    {
        return $this->operationName;
    }

    /**
     * {@inheritdoc}
     */
    public function setOperationName($operationName)
    {
        if (!preg_match(self::$regexValidateName, $operationName)) {
            throw new Exception\QueryBuilderException(
                sprintf('Invalid operation name, must match %s', self::$regexValidateName)
            );
        }

        $this->operationName = $operationName;
        $this->cache = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOperationArgs()
    {
        return $this->operationArgs;
    }

    /**
     * {@inheritdoc}
     */
    public function setOperationArgs($operationArgs)
    {
        $this->operationArgs = $operationArgs;
        $this->cache = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->build();
    }

    /**
     * @return string
     */
    protected abstract function getOperationType();

    /**
     * @return string
     */
    protected function build()
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        $this->cache = '';
        foreach($this->fields as $values) {
            $this->cache .= $this->buildField($values) . "\n\n";
        }

        $this->cache = sprintf(
            "%s %s {\n%s\n}",
            $this->getOperationType(),
            $this->buildOperation(),
            rtrim($this->cache)
        );

        return $this->cache;
    }
    
    /**
     * @param array $values
     *
     * @return string
     */
    protected function buildField(array $values)
    {
        $name   = $values['name'];
        $alias  = isset($values['alias']) ? $values['alias'] . ': ' : null;
        $args   = $this->buildArgs($values['args']);
        $fields = $this->buildFields($values['fields']);
        if ($fields) {
            $fields = sprintf(
                " {\n%s%s\n%s}",
                $this->tabs(3),
                $fields,
                $this->tabs(1)
            );
        }

        $type = sprintf(
            "%s%s%s(%s)%s",
            $this->tabs(1),
            $alias,
            $name,
            $args,
            $fields
        );

        return $type;
    }

    /**
     * @return string
     */
    protected function buildOperation()
    {
        $operationName = $this->operationName;
        if ($this->operationArgs) {
            $operationArgs = $this->operationArgs;
            if (is_string($operationArgs)) {
                $operationArgs = array_map('trim', explode(',', $operationArgs));
            }

            $sanitizedArgs = [];
            foreach($operationArgs as $name => $type) {
                if (is_integer($name)) {
                    list($name, $type) = array_map('trim', explode(':', $type));
                }
                if ($name[0] !== '$') {
                    $name = '$' . $name;
                }
                $sanitizedArgs[] = sprintf('%s: %s', $name, (string)$type);
            }

            $operationName = sprintf('%s (%s)', $operationName, join(', ', $sanitizedArgs));
        }

        return $operationName;
    }

    /**
     * @param array|string $args
     *
     * @return string
     */
    protected function buildArgs($args)
    {
        if (is_string($args)) {
            $args = array_map('trim', explode(',', $args));
        }

        $sanitizedArgs = [];
        foreach($args as $name => $arg) {
            if (is_integer($name)) {
                list($name, $arg) = array_map('trim', explode(':', $arg));
            }
            $sanitizedArgs[] = sprintf('%s: %s', $name, $arg);
        }

        return join(', ', $sanitizedArgs);
    }

    /**
     * @param array|string $fields
     * @param int $indent
     *
     * @return string
     */
    protected function buildFields($fields, $indent = 3)
    {
        if (is_string($fields)) {
            $fields = array_map('trim', explode(' ', $fields));
        }

        $sanitizedFields = [];
        foreach($fields as $name => $field) {
            if (is_array($field)) {
                $indent++;
                $sanitizedFields[] = sprintf(
                    "%s {\n%s%s\n%s}",
                    $name,
                    $this->tabs($indent),
                    $this->buildFields($field, $indent),
                    $this->tabs($indent - 1)
                );
                $indent--;
            } else {
                $sanitizedFields[] = $field;
            }
        }

        return join("\n" . $this->tabs($indent), $sanitizedFields);
    }

    /**
     * @param int $length
     *
     * @return string
     */
    protected function tabs($length)
    {
        return str_repeat(' ', $length * self::$tabLength);
    }
}