<?php
require_once("AbsCodigo.class.php");
require_once("Alimento.class.php");
require_once("AlunoPresenca.class.php");

class DiaAlmoco extends AbsCodigo {
	private $alimentos = array();
	private $data;
	private $diaSemana;
	private $presencas = array();

	/*
	public function __construct ($data)
	{
		$this->setData($data);
	}
	*/

	public function getAlimentos ()	{
		return $this->alimentos;
	}

	public function setAlimento ($alimento)	{
		if ($alimento instanceof Alimento) {
			array_push ($this->alimentos, $alimento);
		}
	}

	public function getData () {
		return $this->data;
	}

	public function setData ($data) {
		$this->data = $data;
	}

	public function getDiaSemana () {
		return $this->diaSemana;
	}

	public function setDiaSemana ($diaSemana) {
		$this->diaSemana = $diaSemana;
	}

	public function getPresencas() {
		return $this->presencas;
	}

	public function setPresenca($aluno_p) {
		if ($aluno_p instanceof AlunoPresenca) {
			array_push($this->presencas, $aluno_p);
		}
	}

	/**
	 * Recebe a data a ser verificada e retorna se o aluno ainda pode mudar sua presença
	 * . A regra de negócio é que o aluno não pode mudar sua presença em dias anteriores
	 * e não pode mudar sua presença no mesmo dia depois das 10h, pois esse é o horário
	 * em que a quantidade de comida a trazer é definida
	 * 
	 * @param string $data_hoje no formato Y-m-d H:i:s
	 * 
	 * @return bool se o aluno pode ou não mudar sua presença no dia
	 */
	public function alunoPodeMudarPresenca($data_hoje)
	{
		$diaSemana_almoco = date("w", strtotime($this->data));
		$diaSemana_hoje = date("w", strtotime($data_hoje));
		$hora_hoje = date("H", strtotime($data_hoje));

		if ($diaSemana_almoco >= $diaSemana_hoje) {
			if ($diaSemana_hoje == $diaSemana_almoco)
				return $hora_hoje <= 9;
				/* Se se considera apenas a hora nessa verificação,
				ela será verdadeira para qualquer momento em que é 9 horas
				incluindo 9:59 - assim, a regra de negócio é satisfeita corretamente,
				pois o usuário pode mudar a presença ATÉ 10h.
				
				Se fosse considerado $hora_hoje <= 10, a função retornaria
				true inclusive para 10:59, o que não satisfaz a regra. */
			else 
				return true;
		} else 
			return false;

	}
}

?>
