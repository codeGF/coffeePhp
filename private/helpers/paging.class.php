<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 * @access 分页类
 * @prompt 可对数组（一维、二维）和普通文本进行分页
 * @prompt 传参变量名默认为'page'
 * @prompt 使用方法:
 * @var set('每页容纳信息条数(数组)或者每页容纳字体数(文本)', '分页内容');
 * @var pick($action);  //输出内容方法($action有俩个协议，str是获取内容，a是获取链接)
 */
class Paging
{

    private $size = 10; // 预计分页数
    private $content = ""; // 预分页内容
    private $page = ""; // 输入页数
    public $pageNums = "";
    private $error1 = 'Input parameter error or is empty.'; // 错误提示1
    private $error2 = 'This page can not be greater than the total page'; // 错误提示2

    #初始化参数 size 每页分页数，content 需要分页的内容
    public function set($size = "", $content)
    {
        $this->size = empty($size) ? 20 : $size;
        $this->content = $content;
    }

    public function get()
    {
        return $this->filter();
    }

    public function page($page = "")
    {
        //自动获取页数参数
        return $this->page = empty($page) ? 1 : $page;
    }

    private function filter()
    {
        // 对必须输入的参数进行检测，防止空参数造成方法抛出错误
        if (($this->size or $this->content) == NULL) {
            return System::error($this->error1); // 抛出错误
        } else {
            return $this->is_content();
        }
    }

    private function is_content()
    {
        return is_array($this->content) ? $this->arr() : $this->text();
        // 依据输入内容类型选择分页方法
    }

    private function arr()
    {
        // 对数组进行分页
        $pnum = ceil(count($this->content) / $this->size); // 计算每页大小
        $this->pageNums = $pnum;
        $page = $this->page > $pnum ? $pnum : $this->page;
        $newarr = array_slice($this->content, (($page - 1) * $this->size), $this->size); // 输出数组
        return $newarr;
    }

    private function text()
    {
        // 对文本进行分页
        $strlen = ceil(strlen($this->content) / $this->size); // 计算总页数
        $page = $this->page > $strlen ? $strlen : $this->page;
        $prePageLen = strlen($this->subStrs($page - 1)); // 截取字符起始数字
        $currentPageLen = strlen($this->subStrs($page)); // 截取字符结束数字
        $str = substr($this->content, $prePageLen, $currentPageLen - $prePageLen); // 截取字符
        return $str;
    }

    private function subStrs($page)
    {
        // 如果为汉字就截取俩个字符，如果为其他就截取一个字符
        $string = null;
        $len = $page * $this->size;
        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($this->content, $i, 1)) > 0xa0) {
                $string .= substr($this->content, $i, 2);
                $i++;
            } else {
                $string .= substr($this->content, $i, 1);
            }
        }
        return $string;
    }

    private function a($num)
    {
        //上一页计算:一：当前页数小于等于0，为1；二：当前页-1大于总页数，为1
        //下一页计算:一：当前页数+1大于总页数，为当前最大页数；
        // $num获取总分页数
        $url = $this->url();
        $end_page = ($this->page == 1) ? 1 : (($this->page - 1) > $num) ? 1 : ($this->page - 1); // 上一页
        $to_page = (($this->page + 1) > $num) ? $num : ($this->page + 1); // 下一页
        $page_str = "{$this->page}/{$num}
		<a href='{$url}&page=1'>首页</a>
		<a href='{$url}&page={$end_page}'>上一页</a>
		<a href='{$url}&page={$to_page}'>下一页</a>
		<a href='{$url}&page={$num}'>末页</a>";
        return $page_str;
    }

    public function __destruct()
    {
        unset($this->content, $this->page);
    }
}