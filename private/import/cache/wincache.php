<?php


class ExtWinCache
{

    private $_conf = array();

    public function __construct($conf)
    {
        $this->_conf = $conf;
    }

    private function _getWinKey($key)
    {
        return System::hash($this->_conf["key"].$key);
    }

    private function _getWinVar($var)
    {
        return serialize($var);
    }

    public function add($key, $var, $expire="")
    {
        return wincache_ucache_add
        (
        		$this->_getWinKey($key),
        		$this->_getWinVar($var),
        		$expire ? $expire : $this->_conf["expire"]
        );
    }

    public function set($key, $var, $expire="")
    {
        return wincache_ucache_set
        (
        		$this->_getWinKey($key),
        		$this->_getWinVar($var),
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
        $get = wincache_ucache_get($this->_getWinKey($key));
        return unserialize($get);
    }

    public function delete($key)
    {
        return wincache_ucache_delete($this->_getWinKey($key));
    }

    public function clean()
    {
        return wincache_ucache_clear();
    }

    public function exists($key)
    {
        return wincache_ucache_exists($this->_getWinKey($key));
    }
}