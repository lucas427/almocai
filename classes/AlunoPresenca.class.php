<?php

require_once("Usuario.class.php");

// continua sendo "Aluno" porque só os alunos irão marcar presença no almoço
class AlunoPresenca {
	private $aluno;
	private $presenca; // campo binário: presente (1) ou ausente (0)

	public function getPresenca() {
		return $this->presenca;
	}
	public function setPresenca($presenca) {
		$this->presenca = $presenca;
	}

	public function getAluno() {
		return $this->aluno;
	}
	public function setAluno($aluno) {
		if ($aluno instanceof Usuario) {
			$this->aluno = $aluno;
		}
	}
}
