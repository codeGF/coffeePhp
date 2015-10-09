<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

class Code extends Base
{

    /**
     * 图片宽度
     *
     * @var int
     */
    public $width = 120;

    /**
     * 图片高度
     *
     * @var int
     */
    public $height = 40;

    /**
     * 倾斜角度
     *
     * @var int
     */
    private $angle = NULL;

    /**
     * 倾斜方向.
     *
     * @var int
     */
    private $rotation_direction = 0;

    /**
     * 验证码字符数量.
     *
     * @var int
     */
    private $vcodeCount = 4;

    /**
     * 背景颜色
     *
     * @var array
     */
    private $allBgColor = array(
        array('r' => 237, 'g' => 247, 'b' => 255), //淡蓝绿背景
        array('r' => 248, 'g' => 248, 'b' => 248), //淡红色背景
        array('r' => 247, 'g' => 254, 'b' => 236) //淡黄绿色背景
    );

    /**
     * 字体颜色
     *
     * @var array
     */
    private $allFontColor = array(
        array('r' => 30, 'g' => 85, 'b' => 145), //蓝色系
        array('r' => 199, 'g' => 71, 'b' => 22), //红色系
        array('r' => 80, 'g' => 120, 'b' => 30)  //绿色系
    );
    private $fontDir = '';
    private $fontConfig = array
    (
        array('name' => 'manshaded.ttf', 'min_size' => 18, 'max_size' => 38,),
        array('name' => 'bronics.ttf', 'min_size' => 20, 'max_size' => 30),
        array('name' => 'cloud2.ttf', 'min_size' => 15, 'max_size' => 38),
        array('name' => 'DISTROBV.ttf', 'min_size' => 19, 'max_size' => 30),
        array('name' => 'shaded.ttf', 'min_size' => 15, 'max_size' => 32),
        array('name' => 'SCRAWL.ttf', 'min_size' => 13, 'max_size' => 28, 'min_angle' => 5, 'max_angle' => 30),
        array('name' => 'actionj.ttf', 'min_size' => 18, 'max_size' => 33),
        array('name' => 'breakfast.ttf', 'min_size' => 16, 'max_size' => 38),
        array('name' => 'VINETAN.ttf', 'min_size' => 14, 'max_size' => 25, 'min_angle' => 5, 'max_angle' => 27),
        array('name' => 'UniTortred.ttf', 'min_size' => 15, 'max_size' => 24, 'min_angle' => 7, 'max_angle' => 18),
        array('name' => 'ALTEA.ttf', 'min_size' => 14, 'max_size' => 24, 'min_angle' => 7, 'max_angle' => 34),
    );

    /**
     * 验证码字符间距离.
     *
     * @var int
     */
    private $font_space = 0;

    /**
     * 当前字体配置.
     *
     * @var array
     */
    private $font = null;

    /**
     * 背景颜色
     *
     * @var int
     */
    private $bgColor = 0;

    /**
     * 字体颜色
     *
     * @var int
     */
    private $fontColor = 0;

    /**
     * 验证码字符.
     */
    private $charPool = "ABCDEFGHJKLMNOPQRSTUVWXYZabcdefghjkmnopqrstuvwxyz092zq";

    /**
     * session 键名.
     *
     * @var string
     */
    private $session_name = NULL;

    /**
     * 验证码字符串.
     *
     * @var string
     */
    private $vcode_str = '';

    /**
     * 图像资源句柄
     *
     * @var resource
     */
    private $im = NULL;

    /**
     * 存储验证码子图像句柄.每一个字符均会生成一个图像资源句柄
     *
     * @var array
     */
    private $sub_im = array();

    public function __construct($session_key)
    {
        parent::__construct();
        $this->session_name = $session_key;
    }

    //初始化一些参数
    protected function init()
    {
        //图像放大倍数，测试是时使用.
        $scal = 1;
        $this->width *= $scal;
        $this->height *= $scal;

        $this->bgColor = $this->allBgColor[array_rand($this->allBgColor)];
        $this->fontColor = $this->allFontColor[array_rand($this->allFontColor)];
        $this->fontDir = Pools::get("SYSTEMCONF@SYSTEM_IMPORT_PATH", true) . '/code/font/';
        $this->font = $this->fontConfig[array_rand($this->fontConfig)];

        $min_angle = 7;
        $max_angle = 35;
        //旋转方向
        $this->rotation_direction = rand(0, 1) == 1 ? 1 : -1;
        if (isset($this->font['max_angle']) && isset($this->font['min_angle']) && $this->font['max_angle'] > 0 && $this->font['min_angle'] > 0) {
            $max_angle = max($this->font['min_angle'], $this->font['max_angle']);
            $min_angle = min($this->font['min_angle'], $this->font['max_angle']);
        }
        $this->angle = rand(min($max_angle, $min_angle), max($max_angle, $min_angle));
        //字体间距离，数字越大，距离越小
        $this->font_space = isset($this->font['space']) ? $this->font['space'] : rand(3.5, 5);
    }

    /**
     * 生成验证码
     *
     */
    public function genValidImage()
    {
        $this->init();
        $this->im = imagecreatetruecolor($this->width, $this->height);

        $this->bgColor = imagecolorallocate($this->im, $this->bgColor['r'], $this->bgColor['g'], $this->bgColor['b']);

        $this->fontColor = imagecolorallocate($this->im, $this->fontColor['r'] + rand(-5, 5), $this->fontColor['g'] + rand(-5, 5), $this->fontColor['b'] + rand(-5, 5));
        imagefilledrectangle($this->im, 0, 0, $this->width - 1, $this->height - 1, $this->bgColor);

        $char_pool_len = strlen($this->charPool);
        for ($i = 0; $i < $this->vcodeCount; $i++) {
            $rand_char = $this->charPool{rand(0, $char_pool_len - 1)};
            $this->vcode_str .= $rand_char;
            $this->sub_im[] = $this->genSubCharImg($rand_char);
        }
        //设置session
        $this->auto->helpers->session->delete($this->session_name);
        $this->auto->helpers->session->set($this->session_name, $this->vcode_str);
        $this->mergSubImage($this->sub_im);
        $this->outputImg();
        return $this->vcode_str;
    }

    /**
     * 将单个字符生成透明背景图片.
     *
     * @param string $char 需要生成图片的字符串
     * @return resource         返回图像资源句柄.
     */
    protected function genSubCharImg($char)
    {
        $char_img_width = $char_img_height = $this->height;
        $char_img = imagecreatetruecolor($this->height, $this->height);
        //透明处理，将背景颜色透明度设置为127 最大.
        imagealphablending($char_img, false);
        imagesavealpha($char_img, true);
        $white_color = imagecolorallocatealpha($char_img, 255, 255, 255, 127);
        imagefill($char_img, 0, 0, $white_color);

        $font_name = $this->fontDir . $this->font['name'];

        //如果字体配置中增加了 字体大小的配置，这里在设置字体大小时会首先 使用配置的字体大小，如果没有设置，则使用下面的计算方式计算字体大小.
        if (isset($this->font['max_size']) && isset($this->font['min_size']) && $this->font['max_size'] > 0 && $this->font['min_size'] > 0) {
            $font_size = rand(min($this->font['max_size'], $this->font['min_size']), max($this->font['max_size'], $this->font['min_size']));
        } else {
            $font_size = rand($char_img_width * 2 / 5, $char_img_width * 1 / 2);
        }
        $angle = $this->angle * $this->rotation_direction;
        $box = $this->imagettfbbox_fixed($font_size, $angle, $font_name, $char);
        $font_width = max($box[2], $box[4]) - min($box[0], $box[6]);
        $font_height = max($box[1], $box[3]) - min($box[5], $box[7]);

        //调整字体大小，防止字符出界 左右方向判定.
        while (($font_width > $char_img_width * 0.90 || $font_width * $this->vcodeCount > $this->width) && $font_size > 1) {
            $font_size--;
            $box = $this->imagettfbbox_fixed($font_size, $angle, $font_name, $char);
            $font_width = max($box[2], $box[4]) - min($box[0], $box[6]);
            $font_height = max($box[1], $box[3]) - min($box[5], $box[7]);
            if ($font_width <= $char_img_width) {
                $font_size = rand(floor($font_size * 0.75), $font_size);
                break;
            }
        }
        //调整字体大小，防止字符出界在上下方向判定
        while ($font_height > $char_img_height * 0.95 && $font_size > 1) {
            $font_size--;
            $box = $this->imagettfbbox_fixed($font_size, $angle, $font_name, $char);
            $font_width = max($box[2], $box[4]) - min($box[0], $box[6]);
            $font_height = max($box[1], $box[3]) - min($box[5], $box[7]);
            if ($font_height <= $char_img_height) {
                $font_size = rand(floor($font_size * 0.75), $font_size);
                break;
            }
        }
        $this->drawCenterImage($char_img, $font_size, $angle, $this->fontColor, $font_name, $char);
        return $char_img;
    }

    /**
     * 合成图像，拼接验证码.
     *
     */
    protected function mergSubImage($sub_images)
    {
        $index = 0;
        $next_image_x = 0;
        $sub_images_len = count($sub_images);
        if ($sub_images_len > 0) {
            $sub_img_width = imagesx($sub_images[0]);
            $sub_img_height = imagesy($sub_images[0]);
            imagecopy($this->im, $sub_images[0], $next_image_x, 0, 0, 0, $sub_img_width, $sub_img_height);
            $next_image_x += imagesx($sub_images[0]);
            $index++;
        }
        while ($index < $sub_images_len) {
            $sub_img_width = imagesx($sub_images[$index]);
            $sub_img_height = imagesy($sub_images[$index]);
            $distance = $this->calc2CharDistance($sub_images[$index - 1], $sub_images[$index]);
            $distance += $this->font_space;
            imagecopy($this->im, $sub_images[$index], $next_image_x - $distance, 0, 0, 0, $sub_img_width, $sub_img_height);
            $next_image_x += imagesx($sub_images[$index]) - $distance;
            $index++;
        }
    }

    /**
     * 计算两幅字符图像间的距离.
     *
     */
    protected function calc2CharDistance($left_im, $right_im)
    {
        $left = $this->calcBlankNum($left_im);
        $right = $this->calcBlankNum($right_im);

        $tmp = array();
        $left_len = count($left);
        for ($i = 0; $i < $left_len; $i++) {
            $tmp[$i] = $left[$i][1] + $right[$i][0];
        }
        return min($tmp);
    }

    /**
     *  计算一幅图像左右两边的空白距离.
     *  这里计算的是透明图像，依据图像透明度来计算.
     *
     */
    protected function calcBlankNum($img)
    {
        $width = imagesx($img);
        $height = imagesy($img);
        $result = array();
        for ($i = 0; $i < $height; $i++) {
            $result[$i] = array($width, $width);
        }
        for ($i = 0; $i < $height; $i++) {
            for ($j = 0; $j < $width; $j++) {
                $pixel = imagecolorat($img, $j, $i);
                // 透明颜色使用32位整形存储，非透明颜色使用 24位整形存储，
                // 透明颜色构成 ARGB ，A : alpha值（透明度，），每一种颜色使用二进制存储时均为8个bit长度(最大值255).
                //位移运算，找出颜色透明度
                $opacity = $pixel >> 24;
                //颜色透明度范围0-127，127 表示完全透明,0表示完全不透明.
                if ($opacity < 127) {
                    if ($result[$i][0] == $width) {
                        $result[$i][0] = $j;
                    }
                    $result[$i][1] = $width - $j - 1;
                }
            }
        }
        return $result;
    }

    /**
     * 在图像上绘制居中文本
     * 说明：
     * 这个图像居中算法是借鉴 php.net 上的用户评论中的居中算法.
     *
     * @link http://cn.php.net/manual/en/function.imagettfbbox.php#43523
     *
     * @param resource $image 图像资源句柄
     * @param int $font_size 字体大小
     * @param int $angle 倾斜角度
     * @param int $font_color 字体颜色
     * @param string $font_path 字体路径(绝对路径)
     * @param string $text 需要绘制的字符
     * @return 绘制字符串的在图像上四个顶点坐标
     */
    protected function drawCenterImage($image, $font_size, $angle, $font_color, $font_path, $text)
    {
        $bbox = imagettfbbox($font_size, 0, $font_path, $text);
        // baseline point for drawing non-rotated text.
        $x0 = $bbox[6];
        $y0 = -$bbox[7];
        // fixes bounding box w.r.t. image coordinate.
        $bbox[5] = -$bbox[5] + $bbox[1];
        $bbox[7] = -$bbox[7] + $bbox[3];
        $bbox[1] = 0;
        $bbox[3] = 0;

        // get the size of image.
        $sx = imagesx($image);
        $sy = imagesy($image);
        // center of bounding box (xc,yc);
        $xc = ($bbox[0] + $bbox[2]) / 2.0;
        $yc = ($bbox[1] + $bbox[7]) / 2.0;
        //弧度转换
        $rad = $angle * pi() / 180.0;
        $sa = sin($rad);
        $ca = cos($rad);
        $x1 = $x0 - $xc;
        $y1 = $y0 - $yc;
        //pivot point(here, we take the center of image)
        $px = $sx / 2.0;
        $py = $sy / 2.0;
        // new baseline point for rotated text.
        $x2 = intval($x1 * $ca + $y1 * $sa + $px + 0.5);
        $y2 = intval(-$x1 * $sa + $y1 * $ca + $py + 0.5);

        return imagettftext($image, $font_size, $angle, $x2, $y2, $font_color, $font_path, $text);
    }

    /**
     * 输出图像
     *
     */
    protected function outputImg()
    {
        ob_clean();
        System::header("Cache-Control: no-cache, must-revalidate");
        System::header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        System::header("Pragma: no-cache");
        System::header("Cache-control: private");
        System::header('Content-Type: image/jpeg');
        imagejpeg($this->im);
        imagedestroy($this->im);
    }

    /**
     * 修复php内置的imagettfbbox 方法.便于精确计算 字符在图像上的宽度、高度
     *
     * @param int $size 字体尺寸
     * @param int $angle 倾斜角度
     * @param string $fontfile 字体路径
     * @param string $text 字符
     * @return array
     */
    protected function imagettfbbox_fixed($size, $angle, $fontfile, $text)
    {
        if ($angle == 0) {
            return imagettfbbox($size, 0, $fontfile, $text);
        }
        // compute size with a zero angle
        $coords = imagettfbbox($size, 0, $fontfile, $text);
        //弧度转换
        $a = deg2rad($angle);
        // compute some usefull values
        $ca = cos($a);
        $sa = sin($a);
        $ret = array();
        // 执行转换
        for ($i = 0; $i < 7; $i += 2) {
            $ret[$i] = round($coords[$i] * $ca + $coords[$i + 1] * $sa);
            $ret[$i + 1] = round($coords[$i + 1] * $ca - $coords[$i] * $sa);
        }
        return $ret;
    }

    /**
     * 释放资源
     *
     */
    protected function freeResource()
    {
        if (!empty($this->sub_im)) {
            foreach ($this->sub_im as $im) {
                if ($im) {
                    @imagedestroy($im);
                    $im = null;
                }
            }
        }
        if (!empty($this->im)) {
            @imagedestroy($this->im);
            $this->im = null;
        }
    }

    /**
     * 析构.
     *
     */
    public function __destruct()
    {
        @$this->freeResource();
    }
}