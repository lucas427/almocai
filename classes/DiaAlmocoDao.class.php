<?php
require_once("AlimentoDao.class.php");
require_once("Conexao.class.php");
require_once("DiaAlmoco.class.php");

class DiaAlmocoDao {

	/////////////////////////
	// FUNÇÕES DE INSERÇÃO //

	public static function InserirAlimentos (DiaAlmoco $diaAlmoco) {
		$alimentos = $diaAlmoco->getAlimentos();
		for ($i=0; $i < count($alimentos); $i++) {
			AlimentoDao::Inserir($alimentos[$i], $diaAlmoco->getCodigo());
		}
	}

	/**
	 * Insire todos os objetos AlunoPresenca de um objeto DiaAlmoco no banco de dados
	 * @param DiaAlmoco $diaAlmoco o dia com os objetos presença a ser inseridos
	 */
	public static function InserirPresencas (DiaAlmoco $diaAlmoco) {
		$presencas = $diaAlmoco->getPresencas();

		for ($i=0; $i < count($presencas); $i++) {
			
			self::DeletarPresenca(
				$presencas[$i]->getAluno()->getCodigo(), 
				$diaAlmoco->getCodigo()
			);
			// Se já existe uma presença marcada pra esse dia, o sistema a deleta e insere uma nova
			// Se não existe, ele tenta deletar, mas não deleta nada (porque não existe), e simplesmente insere uma nova

			StatementBuilder::insert(
				"INSERT INTO Presenca (usuario_cod, diaAlmoco_codigo, presenca) VALUES (:usuario, :dia, :presenca)",
				[
					'usuario' => $presencas[$i]->getAluno()->getCodigo(),
					'dia' => $diaAlmoco->getCodigo(),
					'presenca' => $presencas[$i]->getPresenca()
				]
			);
		}
	}

	public static function DeletarPresenca($usuario, $dia) {
		try {
			$sql = "DELETE FROM Presenca WHERE usuario_cod = :usuario AND diaAlmoco_codigo = :dia";
			$stmt = Conexao::conexao()->prepare($sql);
			$stmt->bindParam(":usuario", $usuario);
			$stmt->bindParam(":dia", $dia);
			return $stmt->execute();
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}


	////////////////////////
	// FUNÇÕES DE SELEÇÃO //

	public static function Popula ($row) {
		$dia = new DiaAlmoco;
		$dia->setCodigo($row['codigo']);
		$dia->setData($row['data']);
		$dia->setDiaSemana($row['diaSemana']);

		return $dia;
	}

	public static function PopulaVarios ($rows) {
		$dias = [];
		foreach ($rows as $row) {
			$dias[] = self::Popula($row);
		}
		return $dias;
	}


	public static function SelectTodos () {
		$sql = "SELECT * FROM DiaAlmoco ORDER BY codigo";
		$query = Conexao::conexao()->query($sql);

		$dias = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			array_push ($dias, self::Popula($row));
		}

		return $dias;
	}

	public static function SelectPorSemana ($semana_codigo) {
		return self::PopulaVarios(
			StatementBuilder::select(
				"SELECT * FROM DiaAlmoco WHERE semanaCardapio_codigo = :codigo ORDER BY `data`",
				['codigo' => $semana_codigo]
			)
		);
	}

	public static function SelectUltimoCod () {
		$sql = "SELECT codigo FROM DiaAlmoco ORDER BY codigo DESC LIMIT 1";

		$query = Conexao::conexao()->query($sql);

		$row = $query->fetch(PDO::FETCH_ASSOC);

		return $row['codigo'];
	}

	public static function SelectAlimentos ($dia) {

		$alimentos = AlimentoDao::SelectPorDia($dia->getCodigo());

		for ($i=0; $i < count($alimentos); $i++) {
			$dia->setAlimento($alimentos[$i]);
		}

		return $dia;
	}

	public static function SelectPorData ($data) {
		$result = StatementBuilder::select(
			"SELECT * FROM DiaAlmoco WHERE `data` = :data",
			['data' => $data]
		);
		
		return self::Popula(
			$result[0]
		);
	}

	/**
	 * Retorna array com contagem de presenças, de vegetarianos e veganos etc.
	 * @param mixed $dia_id id do dia cujas presenças serão contadas
	 * @return array de contagens
	 */
	public static function ContagemPresencas ($dia_id) {
		$sql = "SELECT A.Codigo as 'alimentacao_id', count(*) as 'contagem'
		FROM Presenca P, Usuario U, Alimentacao A
		WHERE diaAlmoco_codigo = :diaAlmoco_codigo
		AND P.usuario_cod = U.codigo
		AND A.codigo = U.alimentacao
		AND P.presenca = :presenca
		GROUP BY U.alimentacao";

		$params = [
			'diaAlmoco_codigo' => $dia_id,
			'presenca' => 1
		];

		$contagens = StatementBuilder::select($sql, $params);

		$contagem = array(0=>0, 1=>0, 2=>0, 3=>0);
		foreach ($contagens as $row) {
			$contagem[$row['alimentacao_id']] = $row['contagem'];
		}

		$contagem[0] = array_sum($contagem);

		return $contagem;
	}


	
	// Comentado pois não será permitido ao usuário deletar dias
	// ////////////////////////
	// // FUNÇÕES DE DELETAR //

	// public static function Deletar (DiaAlmoco $dia) {
	// 	$alimentos = $dia->getAlimentos();
	// 	for ($i=0; $i < count($alimentos); $i++) {
	// 		AlimentoDao::Deletar($alimentos[$i]);
	// 	}

	// 	$sql = "DELETE FROM DiaAlmoco WHERE codigo = :codigo";
	// 	$stmt = Conexao::conexao()->prepare($sql);
	// 	$stmt->bindParam(":codigo", $codigo);
	// 	$codigo = $dia->getCodigo();

	// 	return $stmt->execute();
	// }
}
