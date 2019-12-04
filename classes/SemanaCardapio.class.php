<?php
require_once("AbsCodigo.class.php");
require_once("DiaAlmoco.class.php");

class SemanaCardapio extends AbsCodigo
{
	private $data_inicio;
	private $dias = array();

	/*
	public function __construct ($data) {
		$this->setData_inicio($data);
	}
	*/

	public function getData_inicio () {
		return $this->data_inicio;
	}

	public function setData_inicio ($data) {
		$this->data_inicio = $data;
	}

	public function getDias () {
		return $this->dias;
	}

	public function setDia ($dia) {
		if ($dia instanceof DiaAlmoco) {
			array_push ($this->dias, $dia);
		}
	}
}

?>