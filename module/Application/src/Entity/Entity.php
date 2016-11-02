<?php


namespace Application\Entity;


class Entity
{
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value)
    {
        $method = 'set' . $name;

        if (!method_exists($this, $method)) {
            throw new \Exception('Invalid Method');
        }
    }

    public function __get($name)
    {
        $method = 'get' . $name;

        if (!method_exists($this, $method)) {
            throw new \Exception('Invalid Method');
        }

        return $this->$method();
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this); //array names methods

        foreach ($options as $key => $value) {

            $pattern = array('/_(.?)/', '/-(.?)/', '/ (.?)/');
            $method = preg_replace_callback($pattern,
                create_function('$matches','return strtoupper($matches[1]);'),
                $key); //search and change

            $method = 'set' . ucfirst($method);

            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }

        return $this;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}