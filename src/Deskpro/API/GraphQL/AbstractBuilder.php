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
     * @var array
     */
    protected static $scalarTypes = [
        'ID',
        'Int',
        'String',
        'Float',
        'Boolean'
    ];

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
     * @param ClientInterface $client        Executes the query
     * @param string          $operationName Name of the operation
     * @param array|string    $operationArgs Operation arguments
     *
     * @throws Exception\QueryBuilderException
     */
    public function __construct(ClientInterface $client, $operationName, $operationArgs = [])
    {
        $this->client = $client;
        $this->setOperationName($operationName);
        $this->setOperationArgs($operationArgs);
    }

    /**
     * @return string
     */
    public abstract function getOperationType();
    
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
                sprintf('Invalid field name "%s" must match %s', $name, self::$regexValidateName)
            );
        }
        if ($alias && !preg_match(self::$regexValidateName, $alias)) {
            throw new Exception\QueryBuilderException(
                sprintf('Invalid alias "%s" must match %s', $alias, self::$regexValidateName)
            );
        }
        if ($fields instanceof Fragment && !preg_match(self::$regexValidateName, $fields->getName())) {
            throw new Exception\QueryBuilderException(
                sprintf('Invalid fragment name "%s" must match %s', $fields->getName(), self::$regexValidateName)
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
     * @param string $condition
     * @param array $fields
     *
     * @return Directive
     */
    public function includeIf($condition, $fields = [])
    {
        if (strpos($condition, 'if:') === false) {
            $condition = 'if: ' . $condition;
        }

        return new Directive('@include', $condition, $fields);
    }

    /**
     * @param string $condition
     * @param array $fields
     *
     * @return Directive
     */
    public function skipIf($condition, $fields = [])
    {
        if (strpos($condition, 'if:') === false) {
            $condition = 'if: ' . $condition;
        }

        return new Directive('@skip', $condition, $fields);
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
                sprintf('Invalid operation name "%s" must match %s', $operationName, self::$regexValidateName)
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
     * @throws Exception\QueryBuilderException
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
            "%s %s {\n%s\n}\n%s",
            $this->getOperationType(),
            $this->buildOperation(),
            rtrim($this->cache),
            $this->buildFragments()
        );

        return $this->cache;
    }

    /**
     * @param array $values
     *
     * @return string
     * @throws Exception\QueryBuilderException
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

        $open_brace = ($args) ? '(' : '';
        $close_brace = ($args) ? ')' : '';

        $type = sprintf(
            "%s%s%s%s%s%s%s",
            $this->tabs(1),
            $alias,
            $name,
            $open_brace,
            $args,
            $close_brace,
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
     * @param int          $indent
     *
     * @return string
     * @throws Exception\QueryBuilderException
     */
    protected function buildFields($fields, $indent = 3)
    {
        if (is_string($fields)) {
            $fields = array_map('trim', explode(',', $fields));
        } else if ($fields instanceof Fragment) {
            $fields = [$fields];
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
            } else if ($field instanceof Fragment) {
                $sanitizedFields[] = sprintf('...%s', $field->getName());
            } else if ($field instanceof Directive) {
                $sanitizedFields[] = $this->buildDirective($name, $field);
            } else if (!in_array($field, self::$scalarTypes)) {
                if (!preg_match(self::$regexValidateName, $field)) {
                    throw new Exception\QueryBuilderException(
                        sprintf('Invalid field name "%s" must match %s', $field, self::$regexValidateName)
                    );
                }
                $sanitizedFields[] = $field;
            }
        }

        return join("\n" . $this->tabs($indent), $sanitizedFields);
    }

    /**
     * @return string
     * @throws Exception\QueryBuilderException
     */
    protected function buildFragments()
    {
        $sanitizedFragments = [];
        foreach($this->fields as $field) {
            $field = $field['fields'];
            if (!($field instanceof Fragment)) {
                continue;
            }
            
            $sanitizedFragments[] = sprintf(
                    "fragment %s on %s {\n%s%s\n}\n",
                    $field->getName(),
                    $field->getOnType(),
                    $this->tabs(3),
                    $this->buildFields($field->getFields())
            );
        }

        $fragments = '';
        if ($sanitizedFragments) {
            $fragments = "\n" . join("\n\n", $sanitizedFragments);
        }
        
        return $fragments;
    }

    /**
     * @param string $name
     * @param Directive $directive
     *
     * @return string
     * @throws Exception\QueryBuilderException
     */
    protected function buildDirective($name, Directive $directive)
    {
        $type = $directive->getType();
        if ($type[0] !== '@') {
            $type = '@' . $type;
        }

        $fields = $this->buildFields($directive->getFields(), 4);
        if ($fields) {
            $fields = sprintf(
                " {\n%s%s\n%s}",
                $this->tabs(4),
                $fields,
                $this->tabs(3)
            );
        }
        
        return sprintf(
            "%s %s(%s)%s",
            $name,
            $type,
            $directive->getCondition(),
            $fields
        );
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