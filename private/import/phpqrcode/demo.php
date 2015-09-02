<?php


error_reporting(E_ERROR);
require_once 'phpqrcode/phpqrcode.php';
$url = urldecode("http://www.baidu.com");
QRcode::png($url);