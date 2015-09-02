<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class Upload extends Base
{
	private $_files = array();
	private $_result = array();
	private $_rename = array();
	private $_output = null;
	private $_ercode = array
	(
			1=> "禁止上传为空",
			2=> "非法上传文件类型",
			3=> "上传文件超出了预设大小值",
			"system"=> array
			(
					1=> "文件大小超过了php.ini中的设置",
					2=> "文件大小超过了浏览器大小",
					3=> "文件部分被上传，请您重新上传",
					4=> "没有找到要上传的文件",
					5=> "服务器临时文件丢失",
					6=> "文件写入到临时文件夹出错"
			)
	);

	public function __construct()
	{
		parent::__construct();
		$this->_files();
	}

	private function _files()
	{
        $this->_files = array();
		if (!empty($_FILES))
		{
			foreach ($_FILES as $tmparr)
			{
				$this->_files = $tmparr;
			}
		}else
       {
			$this->auto_->helpers->output->set(false, $this->_ercode[1]);
		}
        return;
	}

	public function type(array $type)
	{
		if (is_array($type))
		{
			$type = implode("|", (array)$type);
			if (is_array($this->_files["type"]))
			{
		        foreach ($this->_files["type"] as $value)
		        {
			        if (!preg_match("/{$type}/", $value))
			        {
				        $this->auto_->helpers->output->set(false, $this->_ercode[2]);
			        }
			    }
			}else if (!preg_match("/{$type}/", $this->_files["type"]))
            {
				$this->auto_->helpers->output->set(false, $this->_ercode[2]);
			}
		}
        return;
	}

	public function size($size)
	{
		if (!empty($size))
		{
			if (is_array($this->_files["size"]))
			{
		        foreach ($this->_files["size"] as $value)
		        {
			        if ($value >= $size)
			        {
				        $this->auto_->helpers->output->set(false, $this->_ercode[3]);
			        }
			    }
			}else if ($this->_files["size"] >= $size)
            {
					$this->auto_->helpers->output->set(false, $this->_ercode[3]);
			}
		}
        return;
	}

	private function _detectError()
	{
		if (is_array($this->_files["error"]))
		{
			foreach ($this->_files["error"] as $value)
			{
				if ($value != 0)
				{
					$this->auto_->helpers->output->set(false, $this->_ercode["system"][$value]);
				}
			}
		}else if ($this->_files["error"] != 0)
        {
			$this->auto_->helpers->output->set(false, $this->_ercode["system"][$this->_files["error"]]);
		}
        return;
	}

	private function _detectRename()
	{
		$tmparr = array();
		if (is_array($this->_files["tmp_name"]) && is_array($this->_files["name"]))
		{
			foreach ($this->_files["name"] as $key => $value)
			{
				$filename = explode(".", $value);
				$tmparr["name"] = sprintf("%s.%s", System::hash(sprintf("%s@%s", ServiceManager::get("SYSTEMCONF@SYSTEM_TIME", true), $filename[0])), $filename[1]);
				$tmparr["tmp_name"] = $this->_files["tmp_name"][$key];
				$tmparr["original"] = $value;
				$this->_rename[] = $tmparr;
			}
		}else
       {
			$filename = explode(".", $this->_files["name"]);
			$tmparr["name"] = sprintf("%s.%s", System::hash(sprintf("%s@%s", ServiceManager::get("SYSTEMCONF@SYSTEM_TIME", true), $filename[0])), $filename[1]);
			$tmparr["tmp_name"] = $this->_files["tmp_name"];
			$tmparr["originalName"] = $this->_files["name"];
			$this->_rename[] = $tmparr;
		}
        return;
	}

	private function _dealUpload($path)
	{
		$tmparr = $arr = array();
		if (is_dir($path) == false) mkdir($path, 0777, true);
		foreach ($this->_rename as $key)
		{
			$tmpresults = move_uploaded_file($key["tmp_name"], rtrim($path, "/")."/".$key["name"]);
			$arr["code"] = $tmpresults == true ? "success" : "error";;
			$arr["newName"] = $key["name"];
			$arr["originalName"] = $key["originalName"];
			$tmparr[] = $arr;
		}
		$this->_result = $tmparr;
        return;
	}

	public function moveUpload($path="")
	{
		$this->_detectError();
		$this->_detectRename();
		$this->_dealUpload($path);
		return $this->_result;
	}
}

/*
$file = new Upload;
$file->type(array("png", "jpg", "txt", "html")); //允许上传的文件类型
$file->size(100); //上传大小限制
$result = $file->moveUpload(); //执行上传
*/