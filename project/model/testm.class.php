<?php


class Test extends Model
{

	private $_conf = array();

    public function __construct()
    {
        parent::__construct();
        $this->_conf = $this->auto_->config->sql->CREATE_TABLE["test"];
        $this->dbname_ = sprintf("%s_%s", $this->_conf["name"], date($this->_conf["expand"], $this->system_->time));
    	$this->_isTableExists();
    }

    private function _isTableExists()
    {
    	$this->pdo_()->query(str_replace("{{table}}", $this->dbname_, $this->_conf["createTable"]));
    	return;
    }

    public function select($term="", $where="", $limit="", $order="")
    {
    	$sql = $this->selectSql($term, $where, $order, $limit);
    	$result = $this->pdo_("slave")->get_results($sql, "ARRAY_A");
    	return $result;
    }

    public function insert($data)
	{
		$sql = $this->insertSql($data);
		$this->pdo_()->query($sql);
		return $this->pdo_()->insert_id;
	}

	public function delete($where="", $limit="")
	{
		$sql = $this->deleteSql($where, $limit);
		return $this->pdo_()->query($sql);
	}
}
