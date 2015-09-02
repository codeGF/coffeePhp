<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/8/11 17:19
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

class Index extends Controller
{

    /**
     * 继承父类，初始化全局变量
     */
    public function __construct()
    {
        parent::__construct();
        $this->view_->title = $this->auto_->config->public_system->title;
        $this->view_->version = $this->auto_->config->public_system->version;
    }

    /**
     * 显示首页板块
     */
    public function main()
    {
        $this->auto_->model->tm->s1();
        $this->display_();
    }

    /**
     * 显示资讯板块页面
     */
    public function zixun()
    {}

    /**
     * 显示单击板块页面
     */
    public function danji()
    {}

    /**
     * 显示网游板块页面
     */
    public function wangyou()
    {}

    /**
     * 显示手游板块页面
     */
    public function shouyou()
    {}
}