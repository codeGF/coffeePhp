<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class Auto
{
    public function __get($name)
    {
        if (Pools::get($name) == false) {
            Pools::set($name, $this->_main($name));
        }
        return Pools::get($name);
    }

    private function _main($name)
    {
        $name = sprintf("%s%s", __CLASS__, strtolower($name));
        if (class_exists($name)) {
            return new $name;
        }
        System::error(11117, $name);
    }
}

class AutoService //加载业务层，加载并且实例化，类似于：$this->auto->service->xxxx->xxxx();
{

    public function __get($name) //调用mvc/service业务类
    {
        if (Pools::get(sprintf("%s@%s", __CLASS__, $name)) == false) {
            $mvc = sprintf("%s/%s%s", Pools::get("SYSTEMCONF@APP_SERVICE_PATH", true), $name, Pools::get("SYSTEMCONF@SYSTEM_SUFFIX", true));
            require_cache($mvc);
            if (class_exists($name)) {
                Pools::set(sprintf("%s@%s", __CLASS__, $name), new $name);
            } else {
                System::error(11117, $name);
            }
        }
        return Pools::get(sprintf("%s@%s", __CLASS__, $name));
    }
}

class AutoExt //内部加载扩展层
{
    public function __get($name)
    {
        if (Pools::get(sprintf("%s@%s", __CLASS__, $name)) == false) {
            $mvc = sprintf("%s/%s%s", Pools::get("SYSTEMCONF@APP_EXT_PATH", true), $name, Pools::get("SYSTEMCONF@SYSTEM_SUFFIX", true));
            require_cache($mvc);
            if (class_exists($name)) {
                Pools::set(sprintf("%s@%s", __CLASS__, $name), new $name);
            } else {
                System::error(11117, $name);
            }
        }
        return Pools::get(sprintf("%s@%s", __CLASS__, $name));
    }
}

class AutoLib //外部扩展层 $this->auto->lib->test = ".class.php";  $this->auto->lib->test = "/abc.class.php";
{
    public function __set($name, $value)
    {
        if (Pools::get(sprintf("%s@%s", __CLASS__, $name)) == false) {
            $mvc = sprintf("%s/%s%s", Pools::get("SYSTEMCONF@APP_LIB_PATH", true), $name, $value);
            require_cache($mvc);
            if (class_exists($name)) {
                Pools::set(sprintf("%s@%s", __CLASS__, $name), true);
            } else {
                System::error(11117, $name);
            }
        }
        return Pools::get(sprintf("%s@%s", __CLASS__, $name));
    }
}

class AutoConfig //加载配置文件，加载并且输出object类型的数组对象
{

    public function __get($name)
    {
        $name = str_replace("_", "/", $name); //下划线转换成路径
        if (Pools::get(sprintf("%s@%s", __CLASS__, $name)) == false) {
            $file = sprintf("%s/%s.php", Pools::get("SYSTEMCONF@APP_CONFIG_PATH", true), $name);
            $conf = require_cache($file);
            Pools::set(sprintf("%s@%s", __CLASS__, $name), $conf);
        }
        return Pools::get(sprintf("%s@%s", __CLASS__, $name));
    }
}

class AutoModel //加载model数据资源，加载并且实例化，类似于：$this->auto->model->xxxx->xxxx();
{

    public $tabname = null;

    public function __construct()
    {
        require_cache(Pools::get("SYSTEMCONF@APP_DB_CONF", true));
        require_cache(sprintf("%s/dbmanagement%s", Pools::get("SYSTEMCONF@SYSTEM_CORE_PATH", true), Pools::get("SYSTEMCONF@SYSTEM_SUFFIX", true)));
        require_cache(sprintf("%s/ezsql/shared/ez_sql_core.php", Pools::get("SYSTEMCONF@SYSTEM_IMPORT_PATH", true)));
        require_cache(sprintf("%s/datadriven%s", Pools::get("SYSTEMCONF@SYSTEM_CORE_PATH", true), Pools::get("SYSTEMCONF@SYSTEM_SUFFIX", true)));
        require_cache(sprintf("%s/model%s", Pools::get("SYSTEMCONF@SYSTEM_CORE_PATH", true), Pools::get("SYSTEMCONF@SYSTEM_SUFFIX", true)));
    }

    public function __get($name) //调用model类
    {
        $this->dbname = null;
        $model = Pools::get(sprintf("%s@%s", __CLASS__, $name));
        if ($model == false) {
            $file = sprintf("%s/%s%s", Pools::get("SYSTEMCONF@APP_MODEL_PATH", true), $name, Pools::get("SYSTEMCONF@SYSTEM_SUFFIX", true));
            require_cache($file);
            if (class_exists($name)) {
                $model = new $name;
                $this->_setDBerrorData($model, $name);
                $model->tabname = $this->tabname;
                Pools::up(sprintf("%s@%s", __CLASS__, $name), $model);
            } else {
                System::error(11117, $name);
            }
        }
        return $model;
    }

    private function _setDBerrorData($model, $name) //错误处理设置
    {
        if (empty($model->sqlConf) == false){
            $this->tabname = explode("|", $model->sqlConf["table"]);
            if (isset($this->tabname[1]) == true) {
                $this->tabname = sprintf("%s_%s", $this->tabname[0], date($this->tabname[1]));
            }else{
                $this->tabname = $this->tabname[0];
            }
            $model->sqlConf["table"] = $this->tabname;
            Pools::up("DBmanagementConf", $model->sqlConf);
        }else{
            $this->tabname = $name;
        }
    }
}

class AutoController //controller资源，加载并且实例化，类似于：$this->auto->controller->xxxx->xxxx();
{

    public function __get($name)
    {
        if (Pools::get(sprintf("%s@%s", __CLASS__, $name)) == false) {
            $file = sprintf("%s/%s%s", Pools::get("SYSTEMCONF@APP_CONTROLLER_PATH", true), $name, Pools::get("SYSTEMCONF@SYSTEM_SUFFIX", true));
            require_cache($file);
            if (class_exists($name)) {
                Pools::set(sprintf("%s@%s", __CLASS__, $name), new $name);
            } else {
                System::error(11117, $name);
            }
        }
        return Pools::get(sprintf("%s@%s", __CLASS__, $name));
    }
}

class AutoHelpers //加载系统扩展，加载并且实例化，类似于：$this->auto->helpers->xxxx->xxxx();
{
    public function __get($name)
    {
        if (Pools::get(sprintf("%s@%s", __CLASS__, $name)) == false) {
            $file = sprintf("%s/%s%s", Pools::get("SYSTEMCONF@SYSTEM_HELPERS_PATH", true), $name, Pools::get("SYSTEMCONF@SYSTEM_SUFFIX", true));
            if (file_exists($file)) {
                require_cache($file);
            } else {
                System::error(11127, $name);
            }
            if (class_exists($name)) {
                Pools::set(sprintf("%s@%s", __CLASS__, $name), new $name);
            } else {
                System::error(11117, $name);
            }
        }
        return Pools::get(sprintf("%s@%s", __CLASS__, $name));
    }
}

class AutoImport //加载import类，加载但不实例化，类似于：$this->auto->import->load("xxx/xxx.php");
{
    public function load($name)
    {
        import($name);
    }
}