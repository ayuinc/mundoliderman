<?php 

class AuthTest {
	
	protected $dni;
	protected $codigo;
	protected $token;

	public function setDNI($_dni)
	{
		$this->dni = $_dni;
	}

	public function setCodigo($_codigo)
	{
		$this->codigo = $_codigo;
	}

	public function setToken($_token)
	{
		$this->token = $_token;
	}

	public function getDNI()
	{
		return $this->dni;
	}

	public function getCodigo()
	{
		return $this->codigo;
	}

	public function getToken()
	{
		return $this->token;
	}

}