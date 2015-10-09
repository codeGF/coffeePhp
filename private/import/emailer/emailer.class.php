<?php


(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

/**
 * 邮件发送类，基于PHPMmsiler制作
 * 输入一维邮件地址数组，抛出每个邮件地址发送情况
 * Emailer('收件人地址数组', '邮件内容数组', '是否HTML格式(默认空)')  //实例化类
 * email();  //实现发送方法
 */

/**
 * 使用例子：
 * $emailer_asddress = array('xx@xx.com','xx@xx.com');
 * $emailer_content = array('邮件标题','邮件内容');
 * $emailer = new Emailer($emailer_asddress, $emailer_content);
 * $access = $emailer->email();
 */

require_cache(sprintf("%s/emailer.php", Pools::get("SYSTEMCONF@SYSTEM_CONF_PATH", true)));
import("emailer/class.phpmailer.php");

class Emailer
{

    private $Host = null; // SMTP服务器
    private $SMTPAuth = null; // 开启SMTP认证
    private $Username = null; // SMTP用户名
    private $Password = null; // SMTP密码
    private $From = null; // 收发/发件人地址
    private $FromName = null; // 发件人
    private $WordWrap = null; // 设置每行字符长度
    private $to_emailer; // 发送状态
    private $emailer; // 用户email地址
    private $content; // 邮件标题和内容
    private $format; // 发送格式
    private $CharSet = null; //字符集

    public function __construct($emailer, $content, $format = null)
    {
        $conf = Pools::get("MAIL_CONF", true);
        $this->Host = $conf["MAIL_HOST"];
        $this->SMTPAuth = $conf["MAIL_SMTPAUTH"];
        $this->Username = $conf["MAIL_USERNAME"];
        $this->Password = $conf["MAIL_PSWD"];
        $this->From = $conf["MAIL_TOEMAILER"];
        $this->FromName = $conf["MAIL_FROMNAME"];
        $this->WordWrap = $conf["MAIL_WORDWRAP"];
        $this->CharSet = $conf["MAIL_ENCODING"];
        $this->emailer = $emailer;
        $this->content = $content;
        $this->format = $format;
    }

    private function arr_filer()
    {
        if (!is_array($this->emailer)) {
            return false;
        } elseif (!is_array($this->content)) {
            return false;
        } else {
            return;
        }
    }

    private function user_filer()
    {
        if ($this->arr_filer() == null) {
            $arr = array_unique($this->emailer); // 去除重复地址
            $arr = array_values($arr); // 重新排序
        }
        return $arr;
    }

    private function format_filer()
    {
        return $this->format == null ? false : true;
    }

    private function emailer_new()
    {
        $mail = new PHPMailer; // 实例化邮件类
        $mail->IsSMTP();
        $mail->Host = $this->Host;
        $mail->SMTPAuth = $this->SMTPAuth;
        $mail->Username = $this->Username;
        $mail->Password = $this->Password;
        $mail->From = $this->From;
        $mail->FromName = $this->FromName;
        $mail->CharSet = $this->CharSet;
        $user = $this->user_filer();
        foreach ($user as $value) {
            $this->to_emailer[$value] = $mail->AddAddress($value); // 将用户邮件地址写入发送地址方法中
        }
        $mail->AddReplyTo($this->From); // 回复地址
        $mail->WordWrap = $this->WordWrap; // 每行字体长度
        $mail->IsHTML($this->format_filer()); // 是否html格式
        $mail->Subject = $this->content['0']; // 邮件标题
        $mail->Body = $this->content['1']; // 邮件内容
        $to_emailer = $mail->Send(); // 发送邮件
        if (!$to_emailer) {
            return "Mailer Error:{$mail->ErrorInfo}"; // 发送不成功，返回错误报告
        }
        return $this->to_emailer; // 返回发送状态数组
    }

    public function email()
    {
        if ($this->arr_filer() == null) {
            return $this->emailer_new();
        } else {
            return $this->arr_filer();
        }
    }
}