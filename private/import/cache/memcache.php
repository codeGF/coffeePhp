<?php


class ExtMemcache
{

    private $_conf = array();
    public static $conn = "";

    public function __construct(array $conf)
    {
        $this->_conf = $conf;
        $this->_memConn();
    }

    public function __destruct()
    {
        self::$conn->close();
    }

    private function _addServer(array $server)
    {
        //$v[0]为缓存服务器ip，$v[1]为对应ip端口
        foreach ($server as $v)
        {
            self::$conn->addServer($v[0], $v[1]);
        }
    }

    private function _memConn()
    {
        if (!self::$conn)
        {
            self::$conn = new Memcache;
            $this->_addServer($this->_conf["memcache"]);
        }
    }

    private function _getMemKey($key)
    {
        if (is_array($key))
        {
            foreach ($key as $k => $v)
            {
                $key["$k"] = System::hash($this->_conf["key"].$v);
            }
        }else
       {
            $key = System::hash($this->_conf["key"].$key);
        }
        return $key;
    }

    private function _getMemVar($var)
    {
        return serialize($var);
    }

    public function add($key, $var, $expire="")
    {
        $add = self::$conn->add
        (
                $this->_getMemKey($key),
                $this->_getMemVar($var),
                $this->_conf["flag"],
                $expire ? $expire : $this->_conf["expire"]
            );
        return $add;
    }

    public function set($key, $var, $expire="")
    {
        $set = self::$conn->set
        (
                $this->_getMemKey($key),
                $this->_getMemVar($var),
                $this->_conf["flag"],
                $expire ? $expire : $this->_conf["expire"]
        );
        return $set;
    }

    public function get($key)
    {
        $get = self::$conn->get($this->_getMemKey($key));
        return unserialize($get);
    }

    public function exists($key)
    {
        $var = $this->get($key);
        if ($var)
        {
            $var = true;
        }else
       {
            $var = false;
        }
        return $var;
    }

    public function replace($key, $var, $expire="")
    {
    	return self::$conn->replace
    	(
    			$this->_getMemKey($key),
    			$this->_getMemVar($var),
    			$this->_conf["flag"],
    			$expire ? $expire : $this->_conf["expire"]
    	);
    }

    //timeout：删除该元素的执行时间。如果值为0,则该元素立即删除，如果值为30,元素会在30秒内被删除。
    public function delete($key, $timeout=0)
    {
        return self::$conn->delete($this->_getMemKey($key), $timeout);
    }

    public function clean()
    {
        return self::$conn->flush();
    }
}