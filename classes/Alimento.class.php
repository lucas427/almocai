<?php
require_once("AbsCodigo.class.php");

class Alimento extends AbsCodigo {
	private $descricao;
	private $tipo;

	public function getDescricao ()	{
		return $this->descricao;
	}

	public function setDescricao ($descricao) {
		$this->descricao = $descricao;
	}

	public function getTipo() {
		return $this->tipo;
	}

	public function setTipo($t) {
		$this->tipo = $t;
	}
}

?>
