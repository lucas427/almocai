<?php

require_once ("IntoleranciaEstado.class.php");

class IntoleranciaEstadoDao
{
	public static function Popula($row)
	{
		$est = new IntoleranciaEstado;
		$est->setCodigo($row['codigo']);
		$est->setDescricao($row['descricao']);
		return $est;
	}

	public static function PopulaVarios($rows)
	{
		$ests = [];
		foreach($rows as $row) {
			$ests[] = self::Popula($row);
		}
		return $ests;
	}

	public static function SelectTodos() 
	{
		return self::PopulaVarios(
			StatementBuilder::select(
				"SELECT * FROM Estado_intolerancia"
			)
		);
	}

	public static function SelectPorCodigo($cod)
	{
		return self::Popula(
			StatementBuilder::select(
				"SELECT * FROM Estado_intolerancia WHERE codigo = :codigo",
				['codigo' => $cod]
			)[0]
		);
	}
}