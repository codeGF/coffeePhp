<?php


class ExtXcache
{

    private $_conf = array();

    public function __construct($conf)
    {
        $this->conf = $conf;
    }

    private function _getXcacheKey($key)
    {
        return System::hash($this->_conf["key"].$key);
    }

    private function _getXcacheVar($var)
    {
        return serialize($var);
    }

    public function add($key, $var, $expire="")
    {
        return xcache_set
        (
        		$this->_getXcacheKey($key),
        		$this->_getXcacheVar($var),
        		$expire ? $expire : $this->_conf["expire"]
        );
    }

    public function set($key, $var, $expire="")
    {
        return $this->add
        (
        		$key,
        		$var,
        		$expire ? $expire : $this->_conf["expire"]
        );
    }

    public function replace($key, $var, $expire="")
    {
    	$this->delete($key);
    	return $this->set($key, $var, $expire);
    }

    public function get($key)
    {
        $get = xcache_get($this->_getXcacheKey($key));
        return unserialize($get);
    }

    public function delete($key)
    {
        return xcache_unset($this->_getXcacheKey($key));
    }

    public function exists($key)
    {
        return xcache_isset($this->_getXcacheKey($key));
    }

    public function clean()
    {
        $cnt = xcache_count(XC_TYPE_VAR);
        for ($i=0; $i < $cnt; $i++)
        {
            xcache_clear_cache(XC_TYPE_VAR, $i);
        }
        return true;
    }
}
