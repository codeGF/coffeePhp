<?php


class Cookie
{

    private $_cookiek = "";

    public function __construct()
    {
    	$this->_cookiek = ServiceManager::get("SYSTEMCONF@SYSTEM_COOKIE_K", true);
    }

    private function _setKey($name)
    {
    	return System::hash(sprintf("%s@%s", $this->_cookiek, $name));
    }

    public function set($name, $value, $date=0, $path="")
    {
    	$name = $this->_setKey($name);
        if (isset($_COOKIE[$name]))
        {
            setcookie($name, "", ServiceManager::get("SYSTEMCONF@SYSTEM_TIME", true)-3600*24);
        }
        return setcookie($name, $value, $date, $path, "", "", true);
    }

    public function get($name)
    {
    	$name = $this->_setKey($name);
    	if (isset($_COOKIE[$name]))
        {
            return $_COOKIE[$name];
        }
        return false;
    }

    public function delete($name)
    {
    	$name = $this->_setKey($name);
        if (isset($_COOKIE[$name]))
        {
            unset($_COOKIE[$name]);
            return setcookie($name, "", ServiceManager::get("SYSTEMCONF@SYSTEM_TIME", true)-3600*24);
        }
        return true;
    }
}