<?php

namespace MichaelRamirezApi\Model;
use MichaelRamirezApi\App;
use MichaelRamirezApi\Utils\ReturnResponse;

class BaseModel{
	/**  @var Config */
    protected $config;
    
    /**  @var DB */
    protected $db;
    /**  @var ReturnResponse */
    protected $returnResponse;

    /**  @var string */
    protected $table;

    /**  @var string/array */
    protected $columns;
    /**  @var array */
    protected $where;

    public function __construct()
    {
        $app = App::create();
        $this->db = $app->getConnection2();
		$this->config = $app->getConfig();
        $this->returnResponse = new ReturnResponse();
        $this->table = "";
        $this->columns = "";
        $this->where = [];
    }

	/**
	 * 
	 * @return string
	 */
	public function getTable() {
		return $this->table;
	}
	
	/**
	 * 
	 * @param string $table 
	 * @return self
	 */
	public function setTable($table): self {
		$this->table = $table;
		return $this;
	}

	/**
	 * 
	 * @return 
	 */
	public function getColumns() {
		return $this->columns;
	}
	
	/**
	 * 
	 * @param  $columns 
	 * @return self
	 */
	public function setColumns($columns): self {
		$this->columns = $columns;
		return $this;
	}

	/**
	 * 
	 * @return array
	 */
	public function getWhere() {
		return $this->where;
	}
	
	/**
	 * 
	 * @param array $where 
	 * @return self
	 */
	public function setWhere($where): self {
		$this->where = $where;
		return $this;
	}
}