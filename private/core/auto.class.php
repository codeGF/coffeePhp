<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class Auto
{
	public function __get($name)
	{
		if (ServiceManager::get($name) == false)
		{
			ServiceManager::set($name, $this->_main($name));
		}
		return ServiceManager::get($name);
	}

	private function _main($name)
	{
	    $name = sprintf("%s%s", __CLASS__, strtolower($name));
		if (class_exists($name))
		{
			return new $name;
		}
		System::error(11117, $name);
	}
}

class AutoService //加载业务层，加载并且实例化，类似于：$this->auto_->service->xxxx->xxxx();
{

    public function __get($name) //调用mvc/service业务类
    {
        if (ServiceManager::get(sprintf("%s@%s", __CLASS__, $name)) == false)
        {
            $mvc = sprintf("%s/%s.class.php", ServiceManager::get("SYSTEMCONF@APP_SERVICE_PATH"), $name);
            require_cache($mvc);
            if (class_exists($name))
            {
            	ServiceManager::set(sprintf("%s@%s", __CLASS__, $name), new $name);
			}else
			{
            	System::error(11117, $name);
            }
        }
        return ServiceManager::get(sprintf("%s@%s", __CLASS__, $name));
    }
}

class AutoExt //内部加载扩展层
{
	public function __get($name)
	{
		if (ServiceManager::get(sprintf("%s@%s", __CLASS__, $name)) == false)
		{
			$mvc = sprintf("%s/%s.class.php", ServiceManager::get("SYSTEMCONF@APP_EXT_PATH"), $name);
			require_cache($mvc);
			if (class_exists($name))
			{
				ServiceManager::set(sprintf("%s@%s", __CLASS__, $name), new $name);
			}else
			{
				System::error(11117, $name);
			}
		}
		return ServiceManager::get(sprintf("%s@%s", __CLASS__, $name));
	}
}

class AutoLib //外部扩展层 $this->auto_->lib->test = ".class.php";  $this->auto_->lib->test = "/abc.class.php";
{
    public function __set($name, $value)
    {
        if (ServiceManager::get(sprintf("%s@%s", __CLASS__, $name)) == false)
        {
            $mvc = sprintf("%s/%s%s", ServiceManager::get("SYSTEMCONF@APP_LIB_PATH"), $name, $value);
            require_cache($mvc);
            if (class_exists($name))
            {
                ServiceManager::set(sprintf("%s@%s", __CLASS__, $name), true);
            }else
           {
                System::error(11117, $name);
            }
        }
        return ServiceManager::get(sprintf("%s@%s", __CLASS__, $name));
    }
}

class AutoConfig //加载配置文件，加载并且输出object类型的数组对象
{

    public function __get($name)
    {
        if (ServiceManager::get(sprintf("%s@%s", __CLASS__, $name)) == false)
        {
            $arr = require_cache(ServiceManager::get("SYSTEMCONF@APP_CONFIG_PATH")."/".$name.".php");
            ServiceManager::set(sprintf("%s@%s", __CLASS__, $name), is_array($arr)? (object)$arr : false);
        }
        return ServiceManager::get(sprintf("%s@%s", __CLASS__, $name));
    }
}

class AutoModel //加载model数据资源，加载并且实例化，类似于：$this->auto_->model->xxxx->xxxx();
{

	public function __construct()
	{
	    require_cache(sprintf("%s/db.php", ServiceManager::get("SYSTEMCONF@SYSTEM_CONF_PATH")));
	    require_cache(sprintf("%s/dbmanagement.class.php", ServiceManager::get("SYSTEMCONF@SYSTEM_CORE_PATH")));
	    require_cache(sprintf("%s/ezsql/shared/ez_sql_core.php", ServiceManager::get("SYSTEMCONF@SYSTEM_IMPORT_PATH")));
		require_cache(sprintf("%s/datadriven.class.php", ServiceManager::get("SYSTEMCONF@SYSTEM_CORE_PATH")));
		require_cache(sprintf("%s/model.class.php", ServiceManager::get("SYSTEMCONF@SYSTEM_CORE_PATH")));
	}

    public function __get($name) //调用model类
    {
        $model = ServiceManager::get(sprintf("%s@%s", __CLASS__, $name));
        if ($model == false)
        {
            $file = sprintf("%s/%s.class.php", ServiceManager::get("SYSTEMCONF@APP_MODEL_PATH"), $name);
		    require_cache($file);
		    if (class_exists($name))
		    {
		        $this->_setDBerrorData($name, $name::$conf);
		        $model = new $name;
		    	ServiceManager::set(sprintf("%s@%s", __CLASS__, $name), $model);
			}else
			{
		    	System::error(11117, $name);
            }
        }
        return $model;
    }
    
    private function _setDBerrorData($name, $baseconf) //错误处理设置
    {
        $tname = $baseconf["name"];
        ServiceManager::set("DBErrorManagementBasename", $name);
        ServiceManager::set("DBErrorManagementBaseconf", $baseconf);
        if (empty($baseconf["expand"]) != true)
        {
            $tname = sprintf("%s_%s", $baseconf["name"], date($baseconf["expand"], ServiceManager::get("SYSTEMCONF@SYSTEM_TIME")));
        }
        ServiceManager::set("DBErrorManagementDbname", $tname);
        return;
    }
}

class AutoController //controller资源，加载并且实例化，类似于：$this->auto_->controller->xxxx->xxxx();
{

    public function __get($name)
    {
        if (ServiceManager::get(sprintf("%s@%s", __CLASS__, $name)) == false)
        {
            $file = sprintf("%s/%s.class.php", ServiceManager::get("SYSTEMCONF@APP_CONTROLLER_PATH"), $name);
		    require_cache($file);
            if (class_exists($name))
            {
            	ServiceManager::set(sprintf("%s@%s", __CLASS__, $name), new $name);
			}else
			{
            	System::error(11117, $name);
            }
        }
        return ServiceManager::get(sprintf("%s@%s", __CLASS__, $name));
    }
}

class AutoHelpers //加载系统扩展，加载并且实例化，类似于：$this->auto_->helpers->xxxx->xxxx();
{
    public function __get($name)
    {
        if (ServiceManager::get(sprintf("%s@%s", __CLASS__, $name)) == false)
        {
            $file = sprintf("%s/%s.class.php", ServiceManager::get("SYSTEMCONF@SYSTEM_HELPERS_PATH"), $name);
            if (file_exists($file))
			{
            	require_cache($file);
			}else
			{
                System::error(11127, $name);
            }
            if (class_exists($name))
            {
            	ServiceManager::set(sprintf("%s@%s", __CLASS__, $name), new $name);
			}else
			{
            	System::error(11117, $name);
            }
        }
        return ServiceManager::get(sprintf("%s@%s", __CLASS__, $name));
    }
}

class AutoImport //加载import类，加载但不实例化，类似于：$this->auto_->import->load("xxx/xxx.php");
{
    public function load($name)
    {
        import($name);
        return;
    }
}