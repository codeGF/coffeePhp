<?php


class ExtMemcached
{

    private $_conf = array();
    private static $_conn = "";

    public function __construct($conf)
    {
        $this->_conf = $conf;
        $this->_memdConn();
    }

    private function _memdConn()
    {
        if (!self::$_conn)
        {
            self::$_conn = new Memcached;
            $this->_addServer($this->_conf["memcached"]);
        }
        return;
    }

    //$server["0"] 服务器IP
    //$server["1"] 服务器端口
    //$server["2"] 被选种的概率
    private function _addServer(array $server)
    {
        self::$_conn->addServers($server);
        return;
    }

    private function _getMemdKey($key)
    {
        if (is_array($key))
        {
            foreach ($key as $k => $v)
            {
                $key["$k"] = System::hash($this->_conf["key"].$v);
            }
        }
        else
       {
            $key = System::hash($this->_conf["key"].$key);
        }
        return $key;
    }

    private function _getMemdVar($var)
    {
        return serialize($var);
    }

    public function add($key, $var, $expire="")
    {
        return self::$_conn->add
        (
        		$this->_getMemdKey($key),
        		$this->_getMemdVar($var),
        		$expire ? $expire : $this->_conf["expire"]
        );
    }

    public function set($key, $var, $expire="")
    {
        return self::$_conn->set
        (
        		$this->_getMemdKey($key),
        		$this->_getMemdVar($var),
        		$expire ? $expire : $this->_conf["expire"]
        );
    }

    public function replace($key, $var, $expire="")
    {
    	return self::$_conn->replace
    	(
    			$this->_getMemdKey($key),
    			$this->_getMemdVar($var),
    			$expire ? $expire : $this->_conf["expire"]
    	);
    }

    public function get($key)
    {
        $get = self::$_conn->get($this->_getMemdKey($key));
        return unserialize($get);
    }

    public function delete($key)
    {
        return self::$_conn->delete($this->_getMemdKey($key));
    }

    public function clean()
    {
        return self::$conn->flush();
    }

    public function exists($key)
    {
        if ($this->get($key))
        {
            $result = true;
        }else
       {
            $result = false;
        }
        return $result;
    }
}
