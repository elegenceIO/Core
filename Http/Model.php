<?php
namespace ElegenceIO\Core\Http;

use ElegenceIO\Database\Database;
use Exception;
use ElegenceIO\QueryBuilder\QueryBuilder;
use ReflectionClass;

class Model
{
    protected array $attributes = [];
    protected array $fillable = [];
    protected array $guarded = [];
    protected array $passedValues = [];
    public bool $exists = false;
    protected string $table =  "";
    protected string $model = "";
    protected string $namespace ="";


    public function __construct(array $attributes = [])
    {
        $this->namespace = new ReflectionClass($this)->getNamespaceName();
        if(!empty($this->passedValues))
        {
            $this->passedValues = [];
        }
        $this->attributes = [];
        $this->attributes = $attributes;

        
    }
    // Magic setter stores values for the builder
    public function __set(string $key, mixed $value)
    {
        $this->attributes[$key] = $value;
    }

    // Magic getter
    public function __get(string $key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function builder(string $table):QueryBuilder
    {
        $namespace = __NAMESPACE__;
        $builder = new QueryBuilder($table,app("db"));
        if(!empty($this->attributes))
        {
            $builder->setAttributes($this->attributes);
        }
        if (!empty($this->fillable)) {
            $builder->fillable($this->fillable);
        }
        if (!empty($this->guarded)) {
            $builder->guarded($this->guarded);
        }

        if($this->exists === true)
        {
            $builder->setExists();
        }
        
        $model = (!empty($this->model)) ? $this->namespace."\\".$this->model : static::class;
        $builder->setModel($model);
        return $builder;
    }
 
     public static function __callStatic(string $method, array $params)
    {
        $instance = new static();
        return $instance->__call($method, $params);
    }


public function __call(string $method, array $params)
{
    $table = $this->table ?: strtolower((new ReflectionClass($this))->getShortName());
    $builder = $this->builder($table);
    if (!empty($this->attributes) && in_array($method, ['insert','update'])) {

        if (empty($params)) {
            $params[] = $this->attributes;
        }
    }

    return $builder->$method(...$params);
}

}