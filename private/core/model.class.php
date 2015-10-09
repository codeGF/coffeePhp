<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 */

(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

abstract class Model extends DataDriven
{

    public $tabname = null; //表名称
    public $sqlConf = array(); //sql配置
    protected $confPath_ = null; //sql配置路径

    public function __construct()
    {
        parent::__construct();
        if ($this->confPath_ != null) $this->sqlConf = $this->auto->config->{$this->confPath_};
    }

    /**
     * @param array term ["fuid", "faccount" ...]
     * @param array where ["faccount"=>"", "key3"=>array(1, 2, 3) ...]
     * @return string
     */
    final public function selectSql($term = "", $where = "", $order = "", $limit = "", $group = "")
    {
        $sql = trim(sprintf(
            "SELECT %s FROM `%s` %s %s %s %s",
            $this->auto->helpers->sqlstatement->term($term),
            $this->tabname,
            $this->auto->helpers->sqlstatement->where($where),
            $this->auto->helpers->sqlstatement->group($group),
            $this->auto->helpers->sqlstatement->order($order),
            $this->auto->helpers->sqlstatement->limit($limit)
        ));
        return $sql;
    }

    /**
     * @param array data ["k"=>"value", "k"=>"value" .....]
     * @return string
     */
    final public function insertSql($data)
    {
        $sql = trim(sprintf(
            "INSERT INTO `%s` SET %s",
            $this->tabname,
            $this->auto->helpers->sqlstatement->insertValue($data)
        ));
        return $sql;
    }

    /**
     * @param array data ["k"=>"value", "k"=>"value" ......]
     * @param array where ["k"=>"value", "k"=>"value" .......]
     * @param array string limit [1, 10] || 10
     * @return string
     */
    final public function updateSql($data, $where = "", $limit = "")
    {
        $sql = trim(sprintf(
            "UPDATE `%s` SET %s %s %s",
            $this->tabname,
            $this->auto->helpers->sqlstatement->insertValue($data),
            $this->auto->helpers->sqlstatement->where($where),
            $this->auto->helpers->sqlstatement->limit($limit)
        ));
        return $sql;
    }

    /**
     * @param array where ["k"=>"value", "k"=>"value" .......]
     * @param array string limit [1, 10] || 10
     * @return string
     */
    final public function deleteSql($where, $limit)
    {
        $sql = trim(sprintf(
            "DELETE FROM `%s` %s %s",
            $this->tabname,
            $this->auto->helpers->sqlstatement->where($where),
            $this->auto->helpers->sqlstatement->limit($limit)
        ));
        return $sql;
    }
}