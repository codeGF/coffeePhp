<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */
class Dir
{

    public function isEmptyDir($dir)
    {
        return (($files = scandir($dir)) && count($files) <= 2);
    }

    public function dirre_name($path) //获取目录下面的所有文件，包括子目录
    {
        $temp_arr = $arr = array();
        $dh = opendir($path);
        if ($dh) {
            while ($filename = readdir($dh)) {
                if ($filename != '.' && $filename != '..') {
                    if (is_dir("{$path}/{$filename}")) {
                        $temp_arr[] = $this->dirre_name("{$path}/{$filename}");
                        if (is_array($temp_arr)) {
                            foreach ($temp_arr as $key) {
                                foreach ($key as $value) {
                                    $arr [] = $value;
                                }
                            }
                        }
                    } else {
                        $arr [] = "{$path}/{$filename}";
                    }
                }
            }
        }
        $arr = array_values(array_unique($arr));
        closedir($dh);
        return $arr;
    }

    public function delete_folder($dir) //删除目录
    {
        $dh = opendir($dir);
        if ($dh) {
            while (($file = readdir($dh)) != false) {
                if (($file == '.') || ($file == '..')) {
                    continue;
                }
                if (is_dir($dir . '/' . $file)) {
                    $this->delete_folder($dir . '/' . $file);
                } else {
                    unlink($dir . '/' . $file);
                }
            }
            closedir($dh);
            rmdir($dir);
        }
    }

    public function directorysize($directory) //计算目录总大小
    {
        $directorysize = 0;
        $dh = opendir($directory);
        if ($dh) {
            while (($filename = readdir($dh))) {
                if ($filename != '.' && $filename != '..') {
                    if (is_file($directory . '/' . $filename)) {
                        $directorysize += filesize($directory . '/' . $filename);
                    }
                    if (is_dir($directory . '/' . $filename)) {
                        $directorysize += filesize($directory . '/' . $filename);
                    }
                }
            }
        }
        closedir($dh);
        return $directorysize;
    }
}