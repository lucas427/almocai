<?php

require_once 'Conexao.class.php';

/**
 * Classe com métodos estáticos para comandos simples em PDOStatements
 */
class StatementBuilder
{


	/**
	 * Cria, associa parâmetros, executa e retorna os resultados de um PDOStatement de comando SELECT em um array associativo
	 * 
	 * @param string $sql Código SQL a ser executado -- p.ex. "SELECT nome FROM Usuario WHERE id = :id"
	 * @param array $colvals Colunas referentes aos parâmetros do $sql (:nome, :id etc.) e valores correspondentes -- p.ex. ['id' => 2, 'nome' => 'Fulano']
	 * 
	 * @return array Conjunto de arrays associativos (cada unidade do conjunto sendo um registro do BD) -- resultado do SELECT 
	 */
	public static function select(string $sql, array $colvals = [])
	{
		try {
			$statement = Conexao::conexao()->prepare($sql);

			$statement = self::bindParams($statement, $colvals);

			$statement->execute();

			return $statement->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			self::mensagemErro($e, $sql, $colvals);
		}
	}



	/**
	 * iud = acrônimo para insert, update, delete -- o método serve para os três comandos
	 * 
	 * @param string $sql Código SQL a ser executado -- p.ex. "INSERT INTO Usuario VALUES (:id, :nome)"
	 * @param array $colvals Colunas referentes aos parâmetros do $sql (:id, :nome etc.) e valores correspondentes -- p.ex. ['id' => 2, 'nome' => 'Fulano']
	 */
	protected static function iud(string $sql, array $colvals)
	{
		try {
			$statement = Conexao::conexao()->prepare($sql);

			$statement = self::bindParams($statement, $colvals);

			return $statement->execute();
		} catch (PDOException $e) {
			self::mensagemErro($e, $sql, $colvals);
		}
	}



	/**
	 * Cria, associa parâmetros e executa PDOStatement com comando SELECT com os valores informados
	 *  
	 * @param string $sql Código SQL a ser executado -- p.ex. "INSERT INTO Usuario VALUES (:id, :nome)"
	 * @param array $colvals Colunas referentes aos parâmetros do $sql (:id, :nome etc.) e valores correspondentes -- p.ex. ['id' => 2, 'nome' => 'Fulano']
	 */
	public static function insert(string $sql, array $colvals)
	{
		return self::iud($sql, $colvals);
	}



	/**
	 * Cria, associa parâmetros e executa PDOStatement com comando UPDATE com os valores informados
	 *  
	 * @param string $sql Código SQL a ser executado -- p.ex. "UPDATE Usuario SET nome = :nome WHERE id = :id"
	 * @param array $colvals Colunas referentes aos parâmetros do $sql (:id, :nome etc.) e valores correspondentes -- p.ex. ['id' => 2, 'nome' => 'Fulano']
	 */
	public static function update(string $sql, array $colvals)
	{
		return self::iud($sql, $colvals);
	}



	/**
	 * Cria, associa parâmetros e executa PDOStatement com comando DELETE com os valores informados
	 * 
	 * @param string $sql Código SQL a ser executado -- p.ex. "DELETE FROM Usuario WHERE id = :id"
	 * @param array $colvals Colunas referentes aos parâmetros do $sql (:id etc.) e valores correspondentes -- p.ex. ['id' => 2]
	 */
	public static function delete(string $sql, array $colvals)
	{
		return self::iud($sql, $colvals);
	}



	/**
	 * Executa bindParam para vários parâmetros em um PDOStatement
	 * 
	 * @param PDOStatement $stmt sem parâmetros
	 * @param array $paramBinds array associativo de 'parâmetro' => 'valor' (ex: ['nome' => 'Fulano', 'dataNasc' => '1990-01-01'])
	 * 
	 * @return PDOStatement com parâmetros
	 */
	protected static function bindParams(PDOStatement $stmt, array $paramBinds)
	{
		foreach ($paramBinds as $param => &$bind) {
			$stmt->bindParam(":{$param}", $bind);
		}

		return $stmt;
	}



	/**
	 * Mostra mensagem de erro com código SQL que tentou-se executar e parâmetros passados
	 */
	protected static function mensagemErro(
		PDOException $e,
		string $sql,
		array $paramBinds
	) {
		$txt = "<b> Não foi possível executar a query. </b>";
		$txt .= "<br> <b>SQL: </b> {$sql}";
		$txt .= "<br> <b>Parâmetros: </b>";
		foreach ($paramBinds as $bind => $param) {
			$txt .= "<li><b> {$bind}</b> => {$param}</li>";
		}
		$txt .= "<br> <b><i>Erro: </i></b>" . $e->getMessage();
		die($txt);
	}
}
