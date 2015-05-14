<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class ExtFilesCache extends Base
{

    private $_cachePath = "";
	private static $_conn = false;
    private $_conf = array();

	public function __construct(array $conf)
	{
		parent::__construct();
        $this->_conf = $conf;
        $this->_filesConn();
	}

    private function _filesConn()
    {
        if (!self::$_conn)
        {
            $this->_addServer($this->_conf["files"]);
        }
    }

    private function _addServer(array $server)
    {
        $root = ServiceManager::get("SYSTEMCONF@APP_CACHE_PATH");
        foreach ($server as $v)
        {
            $path = sprintf("%s/%s", $root, $v);
            if (!file_exists($path))
            {
                mkdir($path, 0777, true);
            }
            self::$_conn = true;
        }
        return;
    }

	private function _getExpire($expire=300)
	{
        if ($expire == 0)
        {
            $expire = "lasting";
        }else{
            $expire = bcadd(ServiceManager::get("SYSTEMCONF@SYSTEM_TIME"), (int)$expire);
        }
		return $expire;
	}

    private function _getFilesKey($key)
    {
        $key = System::hash($key.$this->_conf["key"]);
        return $key;
    }

	//设置数据格式
	private function _getVar($var, $expire)
	{
        $str = sprintf("<?php return array('setup'=>'%s', 'date'=>'%s', 'data'=>%s);",
                ServiceManager::get("SYSTEMCONF@SYSTEM_TIME"),
                $this->_getExpire($expire),
                var_export($var, true));
		return $str;
	}

    public function add($key, $var, $expire=0)
    {
        return $this->set($key, $var, $expire);
    }

	public function set($key, $var, $expire=0)
	{
        $result = false; $root = ServiceManager::get("SYSTEMCONF@APP_CACHE_PATH");
        foreach ($this->_conf["files"] as $v)
        {
            $filepath = sprintf("%s/%s/%s.php", $root, $v, $this->_getFilesKey($v.$key));
            $fp = fopen($filepath, "w+");
            //加写锁
            if ($fp && flock($fp, LOCK_EX + LOCK_NB))
            {
                fwrite($fp, $this->_getVar($var, $expire));
                //解除锁
                flock($fp, LOCK_UN);
                fclose($fp);
                $result = true;
            }
        }
        return $result;
	}

	public function get($key)
	{
        $result = array();
        $root = ServiceManager::get("SYSTEMCONF@APP_CACHE_PATH");
        foreach ($this->_conf["files"] as $v)
        {
            $filepath = sprintf("%s/%s/%s.php", $root, $v, $this->_getFilesKey($v.$key));
            if (file_exists($filepath))
            {
                $fp = fopen($filepath, "r");
                //加读锁
                if ($fp && flock($fp, LOCK_SH + LOCK_NB))
                {
                    $data =  require_cache($filepath);
                    if ($data["date"] > ServiceManager::get("SYSTEMCONF@SYSTEM_TIME") || $data["date"] == "lasting")
                    {
                        $result = $data["data"];
                    }else{
                        $this->delete($key);
                    }
                    //解除锁
                    flock($fp, LOCK_UN);
                    fclose($fp);
                }
            }
            if (!empty($result))
            {
                break;
            }
        }
        return $result;
	}

	//删除指定缓存
	public function delete($key)
	{
	    $root = ServiceManager::get("SYSTEMCONF@APP_CACHE_PATH");
        foreach ($this->_conf["files"] as $v)
        {
            $filepath = sprintf("%s/%s/%s.php", $root, $v, $this->_getFilesKey($v.$key));
            if (file_exists($filepath))
            {
                $fp = fopen($filepath, "r+");
                if ($fp && LOCK_SH + LOCK_NB)
                {
                    flock($fp, LOCK_UN);
                    fclose($fp);
                    unlink($filepath);
                }
            }
        }
        return;
	}

	public function clean()
	{
	    $root = ServiceManager::get("SYSTEMCONF@APP_CACHE_PATH");
        foreach ($this->_conf["files"] as $v)
        {
            $this->auto_->helpers->dir->delete_folder(sprintf("%s/%s", $root, $v));
        }
        return;
	}

    public function exists($key)
    {
        if ($this->get($key))
        {
            $result = true;
        }else{
            $result = false;
        }
        return $result;
    }
}