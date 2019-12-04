<?php
require_once("Conexao.class.php");
require_once("StatementBuilder.class.php");
require_once("SemanaCardapio.class.php");
require_once("Funcoes.class.php");
require_once("DiaAlmocoDao.class.php");

class SemanaCardapioDao
{

	/////////////////////////
	// FUNÇÕES DE INSERÇÃO //

	public static function Inserir(SemanaCardapio $semanaCardapio)
	{
		return StatementBuilder::insert(
			"INSERT INTO SemanaCardapio (data_inicio) VALUES (:data_inicio)",
			['data_inicio' => $semanaCardapio->getData_inicio()]
		);
	}

	////////////////////////
	// FUNÇÕES DE SELEÇÃO //

	public static function Popula($row)
	{
		$semana = new SemanaCardapio;
		$semana->setCodigo($row['codigo']);
		$semana->setData_inicio($row['data_inicio']);

		return $semana;
	}

	public static function PopulaVarias($rows)
	{
		$semanas = [];
		foreach ($rows as $row) {
			$semanas[] = self::Popula($row);
		}
		return $semanas;
	}

	public static function SelectPorCriterio($pesquisa, $criterio)
	{
		if ($criterio == 'data_inicio') {
			$pesquisa = Funcoes::DataUserParaBD($pesquisa);
		}

		$sql = "SELECT * FROM SemanaCardapio WHERE " . $criterio . " = '" . $pesquisa . "'";
		$query = Conexao::conexao()->query($sql);

		$semanas = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push($semanas, self::Popula($row));
		}

		return $semanas;
	}

	public static function SelectPorCodigo($codigo)
	{
		return self::Popula(
			StatementBuilder::select(
				"SELECT * FROM SemanaCardapio WHERE codigo = :codigo",
				['codigo' => $codigo]
			)[0]
		);
	}

	public static function SelectTodos()
	{
		return self::PopulaVarias(
			StatementBuilder::select(
				"SELECT * FROM SemanaCardapio ORDER BY data_inicio DESC"
			)
		);
	}

	/**
	 * Recebe um objeto semana sem dias
	 * Retorna um objeto semana com os dias devidos, conforme seu código
	 */
	public static function SelectDias(SemanaCardapio $semana)
	{

		$dias = DiaAlmocoDao::SelectPorSemana($semana->getCodigo());

		if (isset($dias)) {
			for ($i = 0; $i < count($dias); $i++) {
				$semana->setDia($dias[$i]);
			}
		}

		return $semana;
	}

	public static function SelectUltimoCod()
	{
		return StatementBuilder::select(
			"SELECT codigo FROM SemanaCardapio ORDER BY codigo DESC LIMIT 1"
		)[0]['codigo'];
	}

	public static function SelectPorData($data)
	{
		$sql = "SELECT semanaCardapio_codigo FROM DiaAlmoco WHERE `data` = :data";
		$params = ['data' => Funcoes::CorrigeData($data)];

		return self::SelectPorCodigo(
			StatementBuilder::select(
				"SELECT semanaCardapio_codigo FROM DiaAlmoco WHERE `data` = :data",
				['data' => $data]
			)[0]['semanaCardapio_codigo']
		);
	}


	public static function GerarSelectHTML()
	{
		return Funcoes::GerarSelectHTML("SemanaCardapio", "semanaCardapio_codigo", 0, "codigo", "codigo");
	}


	// Comentado pois não será permitido ao usuário deletar uma semana
	// ////////////////////////
	// // FUNÇÕES DE DELETAR //

	// public static function Deletar(SemanaCardapio $semana)
	// {
	// 	$dias = $semana->getDias();
	// 	for ($i = 0; $i < count($dias); $i++) {
	// 		DiaAlmocoDao::Deletar($dias[$i]);
	// 	}

	// 	$sql = "DELETE FROM SemanaCardapio WHERE codigo = :codigo";
	// 	$p_sql = Conexao::conexao()->prepare($sql);
	// 	$p_sql->bindParam(":codigo", $codigo);
	// 	$codigo = $semana->getCodigo();

	// 	return $p_sql->execute();
	// }


	// //////////////////// //
	// OUTROS MÉTODOS ÚTEIS //
	// //////////////////// //

	/**
	 * Verifica se uma semana está cadastrada no BD a partir da data informada
	 * @param string $data data
	 * @return bool true se existe, false se não
	 */
	public static function SemanaExisteData($data)
	{
		$sql = "SELECT * FROM DiaAlmoco WHERE `data` = :data";
		$params = ['data' => Funcoes::CorrigeData($data)];

		if (StatementBuilder::select($sql, $params) == []) {
			return false;
		} else {
			return true;
		}
	}

	public static function SemanaExisteCodigo($codigo)
	{
		$sql = "SELECT * FROM SemanaCardapio WHERE codigo = :codigo";
		$params = ['codigo' => $codigo];

		if (StatementBuilder::select($sql, $params) == []) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Retorna true se a data informada é da semana informada
	 * 
	 * @param string $data
	 * @param $semana_cod
	 * 
	 * @return bool
	 */
	public static function diaEhDaSemana(string $data, $semana_cod)
	{
		$sql = "SELECT * FROM DiaAlmoco WHERE `data` = :data";
		$params = ['data' => Funcoes::CorrigeData($data)];

		$result = StatementBuilder::select($sql, $params);

		if ($result == null) {
			return false;
		} else {
			$semana_cod_dia = $result[0]['semanaCardapio_codigo'];

			return $semana_cod_dia == $semana_cod;
		}		
	}
}
