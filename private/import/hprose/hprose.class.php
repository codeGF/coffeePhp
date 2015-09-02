<?php


class Hprose
{

    protected $allowMethodList_  =   "";
    protected $crossDomain_      =   false;
    protected $P3P_              =   false;
    protected $get_              =   true;
    protected $debug_            =   0;

    /**
     * 架构函数
     * @access public
     */
    public function __construct()
    {
        //控制器初始化
        if(method_exists($this, "_initialize"))
            $this->_initialize();
        import("hporse/HproseHttpServer.php");
        //实例化HproseHttpServer
        $server     =   new \HproseHttpServer();
        if($this->allowMethodList){
            $methods    =   $this->allowMethodList_;
        }else
       {
            $methods    =   get_class_methods($this);
            $methods    =   array_diff($methods,array("__construct","__call","_initialize"));
        }
        $server->addMethods($methods,$this);
        if($this->debug_)
        {
            $server->setDebugEnabled(true);
        }
        // Hprose设置
        $server->setCrossDomainEnabled($this->crossDomain_);
        $server->setP3PEnabled($this->P3P_);
        $server->setGetEnabled($this->get_);
        // 启动server
        $server->start();
    }

    /**
     * 魔术方法 有不存在的操作的时候执行
     * @access public
     * @param string $method 方法名
     * @param array $args 参数
     * @return mixed
     */
    public function __call($method,$args){}
}
