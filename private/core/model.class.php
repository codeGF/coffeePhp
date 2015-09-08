<?php


/**
 * Created by PhpStorm.
 * author: changguofeng <changguofeng3@163.com>.
 * createTime: 2015/9/8 14:14
 * 版权所有: 允许自由扩展开发,如有问题及建议可反馈与我,非常感谢 :)
 */

(defined("SYSTEM_ROUTER_RUN") && SYSTEM_ROUTER_RUN) or die;

abstract class Model extends DataDriven
{
    
    protected $tabname_ = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->tabname_ = $this->auto_->model->tabname;
    }

    /**
     * @param array term ["fuid", "faccount" ...]
     * @param array where ["faccount"=>"", "key3"=>array(1, 2, 3) ...]
     * @return string
     */
    final public function selectSql($term="", $where="", $order="", $limit="")
    {
        $sql = sprintf
        (
        		"SELECT %s FROM `%s` %s %s %s",
                $this->auto_->helpers->sqlstatement->term($term),
                $this->tabname_,
                $this->auto_->helpers->sqlstatement->where($where),
        		$this->auto_->helpers->sqlstatement->order($order),
                $this->auto_->helpers->sqlstatement->limit($limit)
        );
        return $sql;
    }

    /**
     * @param array data ["k"=>"value", "k"=>"value" .....]
     * @return string
     */
    final public function insertSql($data)
    {
        $sql = sprintf
        (
        		"INSERT INTO `%s` SET %s",
        		$this->tabname_,
        		$this->auto_->helpers->sqlstatement->insertValue($data)
        );
        return $sql;
    }

    /**
     * @param array data ["k"=>"value", "k"=>"value" ......]
     * @param array where ["k"=>"value", "k"=>"value" .......]
     * @param array string limit [1, 10] || 10
     * @return string
     */
    final public function updateSql($data, $where="", $limit="")
    {
        $sql = sprintf
        (
        		"UPDATE `%s` SET %s %s %s",
                $this->tabname_,
                $this->auto_->helpers->sqlstatement->insertValue($data),
                $this->auto_->helpers->sqlstatement->where($where),
        		$this->auto_->helpers->sqlstatement->limit($limit)
        );
        return $sql;
    }

    /**
     * @param array where ["k"=>"value", "k"=>"value" .......]
     * @param array string limit [1, 10] || 10
     * @return string
     */
    final public function deleteSql($where, $limit)
    {
        $sql = sprintf
        (
        		"DELETE FROM `%s` %s %s",
        		$this->tabname_,
        		$this->auto_->helpers->sqlstatement->where($where),
        		$this->auto_->helpers->sqlstatement->limit($limit)
        );
        return $sql;
    }
}