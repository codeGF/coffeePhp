<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

// 滤镜图片
// class->apply_filter(array(array('grayscale'),array('colorize', 90, 60, 40),));

/**
 * $img = new ImgProcess;
 * $img->save = "/xxx/xxx"; //设置保存路径
 * $img->source = "/xxx/xxx"; //设置原始图片路径
 * $img->slit(); //调用某个方法
 * 图片操作
 * 缩放
 * 指定X、Y轴缩放
 * 图片翻转
 * 图片滤镜
 */

//加载类
import("imgprocess/zebra_Image.class.php");

class ImgProcess
{

    public $save = ""; //保存路径
	public $source = ""; //原始图片路径
	private static $class = ""; //对象资源

    public function __construct()
	{
		if (empty(self::$class))
		{
			self::$class = new Zebra_Image;
		}
    }

    //删除指定图片
    private function deleteImg()
    {
        if (file_exists($this->source))
        {
            return unlink($this->source);
        }
        return false;
    }

    //获取图片保存文件类型
    private function getFormat($name)
    {
        $format = substr($this->source, strrpos($this->source, '.') + 1);
		self::$class->target_path = sprintf("%s/%s.%s", trim($this->save, "/"), $name, $format); //保存图片路径
		self::$class->source_path = $this->source; //原始图片路径
		return;
    }

    private function isData($name)
    {
        if (!$this->save || !$this->source)
        {
            System::error(11125, "this->save this->source");
        }
        $this->getFormat($name);
        return;
    }

    /**
     * 缩放同时裁掉多余尺寸素材
     * @var width 转换后高度
     * @var height 转换后宽度
     * @var name 保存文件名称（不可以指定文件格式）
     * @var delete 是否删除原始图片
     * @return true || false
     */
    public function slit($width, $height, $name, $delete="")
    {
        $this->isData($name);
        $results = self::$class->resize($width, $height, ZEBRA_IMAGE_CROP_CENTER);
        $delete ? $this->deleteImg() : "";
        return $results;
    }

    /**
     * 整体缩放图片
     * @var width 转换后高度
     * @var height 转换后宽度
     * @var name 保存文件名称（不可以指定文件格式）
     * @var delete 是否删除原始图片
     * @return true || false
     */
    public function resize($width, $height, $name, $delete="")
    {
        $this->isData($name);
        $results = self::$class->resize($width, $height, ZEBRA_IMAGE_BOXED);
        $delete ? $this->deleteImg() : "";
        return $results;
    }

    /**
     * 横向翻转图片
     * @var name 保存文件名称（不可以指定文件格式）
     * @var delete 是否删除原始图片
     * @return true || false
     */
    public function horizontal($name, $delete="")
    {
        $this->isData($name);
        $results = self::$class->_flip("horizontal");
        $delete ? $this->deleteImg() : "";
        return $results;
    }

    /**
     * 垂直翻转图片
     * @var name 保存文件名称（不可以指定文件格式）
     * @var delete 是否删除原始图片
     * @return true || false
     */
    public function vertical($name, $delete="")
    {
        $this->isData($name);
        $results = self::$class->_flip("vertical");
        $delete ? $this->deleteImg() : "";
        return $results;
    }

    /**
     * 横向+垂直翻转图片
     * @var name 保存文件名称（不可以指定文件格式）
     * @var delete 是否删除原始图片
     * @return true || false
     */
    public function both($name, $delete="")
    {
        $this->isData($name);
        $results = self::$class->_flip("both");
        $delete ? $this->deleteImg() : "";
        return $results;
    }

    /**
     * 裁剪图片
     * @var startX 开始截取x轴
     * @var startY 开始截取y轴
     * @var endX 结束截取x轴
     * @var endY 结束截取y轴
     * @var name 保存文件名称（不可以指定文件格式）
     * @var delete 是否删除原始图片
     * @return false || true
     */
    public function crop($startX, $startY, $endX, $endY, $name, $delete="")
    {
        $this->isData($name);
        $results = self::$class->crop($startX, $startY, $endX, $endY);
        $delete ? $this->deleteImg() : "";
        return $results;
    }

    /**
     * 按指定角度旋转图片
     * @var angle 转动角度
     * @var name 保存文件名称（不可以指定文件格式）
     * @var delete 是否删除原始图片
     * @return true || false
     */
    public function rotate($angle, $name, $delete="")
    {
        $this->isData($name);
        $results = self::$class->rotate($angle);
        $delete ? $this->deleteImg() : "";
        return $results;
    }
}