<?php

// Uso: $pdo = Conexao::conexao();
// $pdo->prepare("SELECT * FROM tabela"); etc
class Conexao {
	protected static $conexao;


	private function __construct ()	{
		$db_host = "****";
		$db_nome = "****";
		$db_usuario = "****";
		$db_senha = "****";
		$db_driver = "****";

		try	{
			self::$conexao = new PDO("$db_driver:host=$db_host; dbname=$db_nome", $db_usuario, $db_senha);
			self::$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$conexao->exec("SET NAMES utf8");
		}
		catch (PDOException $e) {
			die("Erro de conexão: ".$e->getMessage());
		}
	}

	public static function conexao() {
		if (!isset(self::$conexao)) {
			new self;
		}
		return self::$conexao;
	}
}

?>
