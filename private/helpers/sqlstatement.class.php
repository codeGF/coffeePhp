<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * ��Ȩ����: ����������չ����,�������⼰����ɷ�������,�ǳ���л :)
 */
class SqlStatement
{

    //@var term bool ""
    //@var term array array("a", "b", "c"...)
    //@var term char "a"
    public function term($term = "")
    {
        if (empty($term) == false){
            if (is_array($term) == true){
                $tmp = "";
                foreach ($term as $v) {
                    $tmp .= sprintf("`%s`,", $v);
                }
                $term = trim($tmp, ",");
            }
        }else{
            $term = "*";
        }
        return $term;
    }

    //@var data array array("a"=>"b", "b"=>"c"...)
    public function where($data)
    {
        if (empty($data) == false) {
            $tmp = " WHERE "; //char
            if (is_array($data)) {
                foreach ($data as $k => $v) {
                    if (is_array($v)) {
                        $or = "(";
                        foreach ($v as $vv) {
                            $tmp .= sprintf("%s`%s`='%s' OR ", $or, $k, $vv);
                            $or = "";
                        }
                        $tmp = sprintf("%s) AND ", trim($tmp, "OR "));
                    } else {
                        $tmp .= sprintf("`%s`='%s' AND ", $k, $v);
                    }
                }
                $tmp = trim($tmp, "AND ");
                $tmp = count($data, true) > 2 ? $tmp : str_replace(array("(", ")"), "", $tmp);
            } else {
                $tmp .= sprintf("%s ", $data);
            }
            return $tmp;
        }
        return false;
    }

    //@var data array array("a"=>"n", "b"=>"n"...)
    public function insertValue(array $data)
    {
        if (empty($data) == false){
            $tmp = "";
            foreach ($data as $k => $v) {
                $tmp .= "`$k`='$v',";
            }
            return trim($tmp, ",");
        }
        return null;
    }

    //@var data array array(1, 15)
    //@var data int 15
    //@var data char "1,15"
    public function limit($data)
    {
        if (empty($data) == false){
            if (is_array($data)) {
                return " LIMIT " . implode(",", $data) . " ";
            } else if (!empty($data)) {
                return " LIMIT {$data}";
            }
        }
        return null;
    }

    //@var order array array("desc"=> array("id", "time"...))
    //@var order array array("desc"=> "id")
    public function order($order = array())
    {
        if (empty($order) == false){
            $value = array_keys($order);
            $key = trim(implode(",", array_values($order)), ",");
            return " ORDER BY {$key} {$value[0]}";
        }
        return false;
    }

    //@var group array array("id", "name"....)
    //@var group char "id, name..."
    public function group($group = "")
    {
        if (empty($group) == false) {
            if (is_array($group)) {
                $key = trim(implode(",", $group), ",");
            } else {
                $key = $group;
            }
            return " GROUP BY {$key}";
        }
        return false;
    }
}