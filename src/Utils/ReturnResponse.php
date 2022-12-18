<?php
namespace MichaelRamirezApi\Utils;
use InvalidArgumentException;
use stdClass;


class ReturnResponse
{
    private $data;
    private $status;
    private $message;

    public function __construct(){
        $this->data = array();
        $this->status = '';
        $this->message = '';
    }

    /**
	 * @return mixed
	 */
	public function getData($json_format = false) {
        if($json_format)
            return json_encode($this->data);
        else
		    return $this->data;
	}
	
	/**
	 * @param mixed $status 
	 * @return self
	 */
	public function setData($data): self {
        if(!is_array($data))
            throw new InvalidArgumentException('The value must be an array.');

        $this->data = $data;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 * @param mixed $status 
	 * @return self
	 */
	public function setStatus($status): self {
        if(!is_string($status))
            throw new InvalidArgumentException('The value must be a string.');
        if(!in_array($status, ['error', 'success', 'fail']))
            throw new InvalidArgumentException('Incorrect parameter value. Status accepted: error, fail, success.');

		$this->status = strtolower($status);
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getMessage() {
		return $this->message;
	}
	
	/**
	 * @param mixed $message 
	 * @return self
	 */
	public function setMessage($message): self {
        if(!is_string($message))
            throw new InvalidArgumentException('The value must be a string.');

		$this->message = $message;
		return $this;
	}

    /**
	 * @return mixed
	 */
	public function getObject($json_format = false) {
        $returnResponse = new stdClass();
        $returnResponse->data = $this->getData();
        $returnResponse->status = $this->getStatus();
        $returnResponse->message = $this->getMessage();
        if($json_format)
            return json_encode($returnResponse);
        else
		    return $returnResponse;
	}
	
	/**
	 * @return mixed
	 */
	public function success() {
		return $this->status == 'success' ? true : false;
	}

	/**
	 * @return mixed
	 */
	public function error() {
		return $this->status == 'error' ? true : false;
	}

	/**
	 * @return mixed
	 */
	public function fail() {
		return $this->status == 'fail' ? true : false;
	}
}