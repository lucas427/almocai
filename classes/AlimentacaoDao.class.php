<?php
require_once("Alimentacao.class.php");
require_once("Conexao.class.php");

class AlimentacaoDao {


  public static function Popula ($row) {
    $alim = new Alimentacao;
    $alim->setCodigo( $row['codigo'] );
    $alim->setDescricao( $row['descricao'] );
    return $alim;
  }


  public static function PopulaVarias ($rows)
  {
    $alims = [];
    foreach ($rows as $row) {
      $alims[] = self::Popula($row);
    }
    return $alims;
  }


  public static function SelectTodas () {
    return self::PopulaVarias(
      StatementBuilder::select("SELECT * FROM Alimentacao")
    );
  }

  
}
?>