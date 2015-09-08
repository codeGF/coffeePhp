<?php


/**
 * Description of cache
 * 缓存控制器
 * 该缓存系统支持： apc、files、memcache、memcached、wincache、xcache
 * 请参考配置文件
 * @author Administrator
 * action 选择缓存类型
 * key 缓存key
 * expire 存活时间
 * flag 是否启用zlib压缩数据
 conf = array(
    "memcache"=>array(
        array("192.168.1.1", "11211"),
        array("192.168.1.1", "11211"),
        ...
    ),
    "files"=>array(
        "one", "two" ...
    ),
    "memcached"=>array(
        array("192.168.0.1", "11211", 50),
        array("192.168.0.1", "11211", 50),
        ...
    ),
    "apc"=>"", //可以不设置属性
    "wincache"=>"", //可以不设置属性
    "xcache"=>"", //可以不设置属性
    "action"=>"memcache", //支持缓存类型：apc、files、memcache、memcached、wincache、xcache
    "key"=>"test001",
    "expire"=>600,
    "flag"=>false,
 );
*/

class Cache
{

    private $_conf = array();
    private $_class = "";
    private $_systemImportClassPath = "";

    public function __construct(array $conf)
    {
        $this->_conf = $conf;
        $this->_systemImportClassPath = Pools::get("SYSTEMCONF@SYSTEM_IMPORT_PATH", true);
        $this->main();
    }

    public function loadFile($action)
    {
        $file = array
        (
            "memcache"=>array($this->_systemImportClassPath."/cache/memcache.php", "ExtMemcache"),
            "memcached"=>array($this->_systemImportClassPath."/cache/memcached.php", "ExtMemcached"),
            "apc"=>array($this->_systemImportClassPath."/cache/apc.php", "ExtApcCache"),
            "files"=>array($this->_systemImportClassPath."/cache/files.php", "ExtFilesCache"),
            "wincache"=>array($this->_systemImportClassPath."/cache/wincache.php", "ExtWinCache"),
            "xcache"=>array($this->_systemImportClassPath."/cache/xcache.php", "ExtXcache"),
        );
        if (isset($file["$action"]))
        {
            return $file["$action"];
        }
    }

    public function main()
    {
        $cache = $this->loadFile($this->_conf["action"]);
        if ($cache)
        {
            $class = $cache[1];
            require_cache($cache[0]);
            $this->_class = new $class($this->_conf);
        }
        return;
    }

    public function __call($fn, $data)
    {
        return call_user_func_array(array($this->_class, $fn), $data);
    }
}
