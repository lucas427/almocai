<?php

abstract class AbsCodigo {
	private $codigo;

	/*
	public function __construct ($codigo)
	{
		$this->codigo = $codigo;
	}
	*/

	public function getCodigo () {
		return $this->codigo;
	}

	public function setCodigo ($codigo) {
		$this->codigo = $codigo;
	}
}

?>
