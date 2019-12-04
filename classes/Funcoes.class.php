<?php
require_once("Conexao.class.php");

class Funcoes
{

	public static function ProximoCod($tabela) {
		return StatementBuilder::select(
			"SELECT Auto_Increment FROM Information_schema.Tables WHERE Table_name = '{$tabela}'"
		)[0]['Auto_Increment'];
	}

	public static function GerarSelectHTML($tabela, $selectName, $selecionado, $value, $texto)
	{
		$txt = '';

		$sql = "SELECT * FROM $tabela";

		$query = Conexao::conexao()->query($sql);

		$txt .= "<select name='" . $selectName . "'>";
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			if ($selecionado == $row["$value"]) {
				$selected = "selected";
			} else {
				$selected = "";
			}

			$txt .= "<option value='" . $row["$value"] . "' " . $selected . ">" . $row["$texto"] . "</option>";
		}
		$txt .= "</select>";

		return $txt;
	}

	public static function DataUserParaBD($data)
	{
		// Recebe uma data no formato que o usuário digita ("DD/MM/AAAA")
		// e retorna uma data que o BD entende ("AAAA-MM-DD")

		$data = str_replace('/', '-', $data);
		return date('Y-m-d', strtotime($data));
	}

	public static function DataBDParaUser($data)
	{
		return date('d/m/Y', strtotime($data));
	}

/**
 * Os únicos dias cadastrados no BD são seg, ter, qua e qui. Quando 
 * um aluno vai para a página do cardápio, ele carrega o cardápio conforme o dia em que o aluno 
 * está. Se o aluno, então, entrar no final de semana, ocorrerrá um erro. Essa função corrige 
 * esse problema: ela recebe uma data e, se for um final de semana, retorna a data do 
 * intervalo de 4 dias mais próxima.
 * 
 * @param string $data a data a ser verificada
 * 
 * @return string a data corrigida
 */
public static function CorrigeData(string $data)
{
	$dias_com_almoco = array(1, 2, 3, 4); // 0 é domingo e 6 sábado. 1, 2, 3 e 4 são seg, ter, qua e qui
	if (in_array(date("w", strtotime("$data")), $dias_com_almoco)) { // se o dia da semana da data informada é um desses quatro
		return date("Y-m-d", strtotime("$data")); // retorna a data normalmente
	} else {
		switch (date("w", strtotime("$data"))) {
			case 5: // sexta
				return date("Y-m-d", strtotime("$data -1 day")); // quinta
				break;
			case 6: // sábado
				return date("Y-m-d", strtotime("$data -2 days")); // quinta
				break;
			case 0: // domingo
				return date("Y-m-d", strtotime("$data +1 day")); // segunda da próx semana, que no domingo é p/ ja estar cadastrada
				break;
		}
	}
}
}
