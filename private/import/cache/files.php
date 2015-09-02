<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class ExtFilesCache extends Base
{

    private $_cachePath = "";
	private static $_conn = false;
    private $_conf = array();
    private $_retyNum = 5; //失败最大操作次数

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
        $root = ServiceManager::get("SYSTEMCONF@APP_CACHE_PATH", true);
        foreach ($server as $v)
        {
            $path = sprintf("%s/%s", $root, System::hash($v));
            if (!file_exists($path))
            {
                mkdir($path, 0777, true);
            }
            self::$_conn = true;
        }
        return;
    }

	private function _getExpire($expire=0)
	{
        if ($expire == 0)
        {
            $expire = 0;
        }else{
            $expire = bcadd(ServiceManager::get("SYSTEMCONF@SYSTEM_TIME", true), (int)$expire);
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

    private function _fopen($file, $case)
    {
        $sc = md5($file.$case);
        static $fop = array();
        if (isset($fop[$file]) == false)
        {
            $fop[$sc] = fopen($file, $case);
        }
        return $fop[$sc];
    }

    public function add($key, $var, $expire=0)
    {
        return $this->set($key, $var, $expire);
    }

	public function set($key, $var, $expire=0)
	{
        $result = false; $root = ServiceManager::get("SYSTEMCONF@APP_CACHE_PATH", true);
        static $files = array(); $files = $this->_conf["files"];
        static $i = 0; if ($i++ >= $this->_retyNum) return $result;
        foreach ($files as $k => $v)
        {
            $filepath = sprintf("%s/%s/%s.php", $root, System::hash($v), $this->_getFilesKey($v.$key));
            $fp = $this->_fopen($filepath, "w+");
            //加写锁
            if ($fp && flock($fp, LOCK_EX + LOCK_NB))
            {
                fwrite($fp, $this->_getVar($var, $expire));
                //解除锁
                flock($fp, LOCK_UN);
                fclose($fp);
                $result = true;
                unset($files[$k]);
            }else
            {
                usleep(round(mt_rand(0, 100)*1000));
                $this->set($key, $var, $expire);
            }
        }
        return $result;
	}

	public function get($key)
	{
        $result = array(); static $files = array(); $files = $this->_conf["files"];
        static $i = 0; if ($i++ >= $this->_retyNum) return $result;
        $root = ServiceManager::get("SYSTEMCONF@APP_CACHE_PATH");
        foreach ($files as $k => $v)
        {
            $filepath = sprintf("%s/%s/%s.php", $root, System::hash($v), $this->_getFilesKey($v.$key));
            if (file_exists($filepath))
            {
                $fp = $this->_fopen($filepath, "r");
                //加读锁
                if ($fp && flock($fp, LOCK_SH + LOCK_NB))
                {
                    $data = require_cache($filepath);
                    //解除锁
                    flock($fp, LOCK_UN);
                    fclose($fp);
                    if ($data["date"] != 0)
                    {
                        if ($data["date"] > ServiceManager::get("SYSTEMCONF@SYSTEM_TIME", true))
                        {
                            $result = $data["data"];
                        }else{
                            $this->delete($key);
                        }
                    }else
                    {
                        $result = $data["data"];
                    }
                    unset($files[$k]);
                }else
                {
                    usleep(round(mt_rand(0, 100)*1000));
                    $this->get($key);
                }
            }
        }
        return $result;
	}

	//删除指定缓存
	public function delete($key)
	{
        static $i = 0; if ($i++ >= $this->_retyNum) return;
	    $root = ServiceManager::get("SYSTEMCONF@APP_CACHE_PATH", true);
        static $files = array(); $files = $this->_conf["files"];
        foreach ($files as $k => $v)
        {
            $filepath = sprintf("%s/%s/%s.php", $root, System::hash($v), $this->_getFilesKey($v.$key));
            if (file_exists($filepath))
            {
                $fp = $this->_fopen($filepath, "r+");
                if ($fp && LOCK_SH + LOCK_NB)
                {
                    flock($fp, LOCK_UN);
                    fclose($fp);
                    unlink($filepath);
                    unset($files[$k]);
                }else
                {
                    usleep(round(mt_rand(0, 100)*1000));
                    $this->delete($key);
                }
            }
        }
        return;
	}

	public function clean()
	{
	    $root = ServiceManager::get("SYSTEMCONF@APP_CACHE_PATH", true);
        foreach ($this->_conf["files"] as $v)
        {
            $this->auto_->helpers->dir->delete_folder(sprintf("%s/%s", $root, System::hash($v)));
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