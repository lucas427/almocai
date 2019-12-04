<?php
require_once("Conexao.class.php");
require_once("Frequencia.class.php");
require_once("StatementBuilder.class.php");

class FrequenciaDao {
  public static function Popula ($row) {
    $freq = new Frequencia;
    $freq->setCodigo($row['codigo']);
    $freq->setDescricao($row['descricao']);
    return $freq;
  }

  public static function PopulaVarias ($rows)
  {
    $freqs = [];
    foreach ($rows as $row) {
      $freqs[] = self::Popula($row);
    }
    return $freqs;
  }

  public static function SelectTodas () {
    return self::PopulaVarias(
      StatementBuilder::select("SELECT * FROM Frequencia")
    );
  }
  
}
?>