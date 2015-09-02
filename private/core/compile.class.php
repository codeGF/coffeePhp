<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class Compile
{

    private $_compilefile = array(); //需要编译文件列表
    private $_compile = null; //编译字符串
    private $_systemConfPath = null; //配置文件存放目录
    private $_systemCorePath = null; //框架核心文件
    private $_commonPath = null; //系统函数库存放路径
    public  $compileFilePath = null; //编译后文件存放路径
    public  $compileFileSave = null; //编译后文件保存类型
    
    private function setPath()
    {
        $this->_systemConfPath = ServiceManager::get("SYSTEMCONF@SYSTEM_CONF_PATH", true);
        $this->_systemCorePath = ServiceManager::get("SYSTEMCONF@SYSTEM_CORE_PATH", true);
        $this->_commonPath = ServiceManager::get("SYSTEMCONF@SYSTEM_COMMON_PATH", true);
        return;
    }

    private function _compileFile()
    {
        $this->_compilefile = array
        (
            $this->_systemCorePath."/privateexception.class.php", //加载错误处理类
            $this->_commonPath."/commons.php", //加载系统函数库
            $this->_systemCorePath."/auto.class.php", //加载自动加载类
            $this->_systemCorePath."/base.class.php", //加载框架基类
            $this->_systemCorePath."/controller.class.php", //加载框架控制器
            $this->_systemConfPath."/errorcode.php", //加载错误提示配置
            $this->_systemCorePath."/hook.class.php", //加载框架钩子机制
            $this->_systemCorePath."/router.class.php", //加载框架路由
            $this->_systemCorePath."/app.class.php" //加载应用基类
        );
        return;
    }

    private function _load() //加载框架运行所需文件
    {
        foreach ($this->_compilefile as $path)
        {
            require $path;
        }
        return;
    }

    private function _generateCompile()
    {
        if (is_writable(ROOT))
        {
            $con = "";
            foreach ($this->_compilefile as $path)
            {
                $con = $this->_compress(trim(file_get_contents($path)));
                $this->_compile .= substr($con, -2) == '?>' ? trim(substr($con, 5, -2)) : trim(substr($con, 5));
            }
            if (is_dir($this->compileFilePath) == false)
            {
                mkdir($this->compileFilePath, 0777, true);
            }
            return file_put_contents(sprintf("%s/%s", $this->compileFilePath, $this->compileFileSave), sprintf("<?php %s ?>", $this->_compile));
        }else
		{
			System::error(11131, ROOT);
		}
        return false;
    }

    public static function run($isCompile=false, $name)
    {
        $compile = new Compile;
        if ($isCompile == true)
        {
            $compile->compileFilePath = ServiceManager::get("SYSTEMCONF@APP_COMPILE_FILE_PATH", true);
            $compile->compileFileSave =  sprintf(ServiceManager::get("SYSTEMCONF@APP_COMPILE_FILE_SAVE", true), $name);
            if (file_exists(sprintf("%s/%s", $compile->compileFilePath, $compile->compileFileSave)))
            {
            	require sprintf("%s/%s", $compile->compileFilePath, $compile->compileFileSave);
			}else
			{
			    $compile->setPath();
            	$compile->_compileFile();
            	$compile->_generateCompile();
            	require sprintf("%s/%s", $compile->compileFilePath, $compile->compileFileSave);
            }
		}else
		{
		    $compile->setPath();
		    $compile->_compileFile();
        	$compile->_load();
        }
        return;
    }

    private function _compress($content)
    {
        $str = "";
        $data = token_get_all($content);
        $end = false;
        $inum = count($data);
        for ($i = 0, $count = $inum; $i < $count; $i++)
        {
            if (is_string($data[$i]))
            {
                $end = false;
                $str .= $data[$i];
            }else
           {
                switch ($data[$i][0])
                { //检测类型
                    //忽略单行多行注释
                    case T_COMMENT:
                    case T_DOC_COMMENT:
                        break;
                    //去除格
                    case T_WHITESPACE:
                        if (!$end)
                        {
                            $end = true;
                            $str .= " ";
                        }
                        break;
                    //定界符开始
                    case T_START_HEREDOC:
                        $str .= "<<<DELIMITER\n";
                        break;
                    //定界符结束
                    case T_END_HEREDOC:
                        $str .= "DELIMITER;\n";
                        //类似str;分号前换行情况
                        for ($m = $i + 1; $m < $count; $m++) {
                            if (is_string($data[$m]) && $data[$m] == ';')
                            {
                                $i = $m;
                                break;
                            }
                            if ($data[$m] == T_CLOSE_TAG)
                            {
                                break;
                            }
                        }
                        break;
                    default:
                        $end = false;
                        $str .= $data[$i][1];
                }
            }
        }
        return $str;
    }
}