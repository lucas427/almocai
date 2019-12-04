<?php
require_once("AbsCodigo.class.php");

abstract class AbsCodigoDescricao extends AbsCodigo {
	private $descricao;

	public function setDescricao ($d) {
		$this->descricao = $d;
	}

	public function getDescricao () {
		return $this->descricao;
	}
}

?>
