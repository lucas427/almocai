<?php

// Dao = Data Acess Object = Objeto de acesso à dados

require_once("Conexao.class.php");
require_once("Alimento.class.php");
require_once 'StatementBuilder.class.php';

class AlimentoDao
{


	////////////////////////
	// FUNÇÕES DE INSERIR //

	public static function Inserir(Alimento $alimento, $diaAlmoco_codigo)
	{
		$sql = "INSERT INTO Alimento (descricao, diaAlmoco_codigo, tipo) VALUES (:descricao, :diaAlmoco_codigo, :tipo)";
		$params = [
			'descricao' => $alimento->getDescricao(),
			'diaAlmoco_codigo' => $diaAlmoco_codigo,
			'tipo' => $alimento->getTipo()
		];

		return StatementBuilder::insert($sql, $params);
	}

	/**
	 * Insere um alimento em todos os dias em uma semana
	 */
	public static function InserirEmSemana(Alimento $alimento, $semana_codigo)
	{
		$dias_codigo = StatementBuilder::select(
			"SELECT codigo FROM DiaAlmoco WHERE semanaCardapio_codigo = :semana_codigo",
			['semana_codigo' => $semana_codigo]
		);

		foreach ($dias_codigo as $dia) {
			self::Inserir($alimento, $dia['codigo']);
		}
	}

	///////////////////////
	// FUNÇÕES DE SELECT //

	public static function Popula($row)
	{
		$alimento = new Alimento;
		$alimento->setCodigo($row['codigo']);
		$alimento->setDescricao($row['descricao']);
		$alimento->setTipo($row['tipo']);

		return $alimento;
	}

	public static function PopulaVarios($rows)
	{
		$alimentos = [];
		foreach ($rows as $row) {
			$alimentos[] = self::Popula($row);
		}
		return $alimentos;
	}

	public static function SelectPorDia($dia_codigo)
	{
		return self::PopulaVarios(
			StatementBuilder::select(
				"SELECT * FROM Alimento WHERE diaAlmoco_codigo = :dia_codigo",
				['dia_codigo' => $dia_codigo]
			)
		);
	}


	////////////////////////
	// FUNÇÕES DE DELETAR //

	public static function Deletar(Alimento $alimento)
	{
		return StatementBuilder::delete(
			"DELETE FROM Alimento WHERE codigo = :codigo",
			['codigo' => $alimento->getCodigo()]
		);
	}

	// /**
	//  * Deleta todos os alimentos de um dia
	//  */
	// public static function DeletarPorDia($dia_cod)
	// {
	// 	$sql = "DELETE FROM Alimento WHERE diaAlmoco_codigo = :dia_cod";
	// 	try {
	// 		$stmt = Conexao::conexao()->prepare($sql);
	// 		$stmt->bindParam(":dia_cod", $dia_cod);
	// 	} catch (PDOException $e) {
	// 		echo "<b>Erro (AlimentoDao::DeletarPorDia): </b>" . $e->getMessages();
	// 	}
	// 	return $stmt->execute();
	// }

	// /**
	//  * Deleta todos os alimentos de todos os dias de uma semana
	//  */
	// public static function DeletarPorSemana($semana_cod)
	// {
	// 	$sql = "SELECT codigo FROM DiaAlmoco WHERE semanaCardapio_codigo = $semana_cod";
	// 	try {
	// 		$query = Conexao::conexao()->query($sql);
	// 		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	// 			self::DeletarPorDia($row['codigo']);
	// 		}
	// 	} catch (PDOException $e) {
	// 		echo "<b>Erro (AlimentoDao::DeletePorSemana): </b>" . $e->getMessage();
	// 	}
	// }
}
