<?php


class ExtApcCache
{

    private $_conf = array();

    public function __construct($conf)
    {
        $this->_conf = $conf;
    }

    private function _getApcKey($key)
    {
        return System::hash($this->_conf["key"] . $key);
    }

    private function _getApcVar($var)
    {
        return serialize($var);
    }

    public function add($key, $var, $expire = "")
    {
        return apc_add(
            $this->_getApcKey($key),
            $this->_getApcVar($var),
            $expire ? $expire : $this->_conf["expire"]
        );
    }

    public function set($key, $var, $expire = "")
    {
        return apc_add(
            $this->_getApcKey($key),
            $this->_getApcVar($var),
            $expire ? $expire : $this->_conf["expire"]
        );
    }

    public function replace($key, $var, $expire = "")
    {
        $this->delete($key);
        return $this->set($key, $var, $expire);
    }

    public function get($key)
    {
        $get = apc_fetch($this->_getApcKey($key));
        return unserialize($get);
    }

    public function exists($key)
    {
        return apc_exists($this->_getApcKey($key));
    }

    public function delete($key)
    {
        return apc_delete($this->_getApcKey($key));
    }

    public function clean()
    {
        return apc_clear_cache("user");
    }
}
