<?php

namespace Rbz\Data\Traits;

trait AdapterTrait
{
    protected string $_adapter;

    public function setAdapter(string $adapter)
    {
        $this->_adapter = $adapter;
        return $this;
    }

    public function __get($name)
    {
        if (isset($this->_adapter) && method_exists($this->_adapter, $name)) {
            return call_user_func([new $this->_adapter, $name], $this);
        }
        return parent::__get($name);
    }
}
