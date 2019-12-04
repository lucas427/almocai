<?php
    require_once("Upload.class.php");
    require_once("IntoleranciaDao.class.php");
    require_once("IntoleranciaUsuario.class.php");
    require_once("IntoleranciaEstadoDao.class.php");
    require_once("Conexao.class.php");
    require_once("StatementBuilder.class.php");
    class IntoleranciaUsuarioDao{

        /**
         * Recebe um objeto IntoleranciaUsuario e insere seus dados no banco de dados na tabela correspondente
         * @param IntoleranciaUsuario $intoleranciaUsuario
         * @param string $nome 
         * 
         * @return 
         */
        public static function Inserir(IntoleranciaUsuario $intoleranciaUsuario, $nome_arquivo, $matricula, $pasta_destino)
        {
            // pasta de destino do arquivo
            
            $Upload = new Upload;
            // faz o upload do arquivo e coloca o caminho no atributo documento
            $intoleranciaUsuario->setDocumento($Upload->uploadImagem($nome_arquivo, $pasta_destino));
            if(!is_array($intoleranciaUsuario->getDocumento()))
            return StatementBuilder::insert("INSERT INTO Usuario_intolerancia (usuario_cod,intolerancia_cod,arquivo) value (:usuario_cod, :intolerancia_cod, :arquivo)", 
            [
                'usuario_cod' => $matricula,
                'intolerancia_cod' => $intoleranciaUsuario->getIntolerancia()->getCodigo(),
                'arquivo' => $intoleranciaUsuario->getDocumento()
            ]);
        }


        /**
         * Atualiza apenas o estado da intolerânica (se foi validada ou rejeitada)
         * e, caso foi rejeitada, o motivo da rejeição
         */
        public static function UpdateEstado(IntoleranciaUsuario $intolUs)
        {
            $sql = "UPDATE Usuario_intolerancia SET estado_cod = :estado_cod, motivo_rejeicao = :motivo_rejeicao";
            $params = [ 'estado_cod' => $intolUs->getEstado()->getCodigo(),
                        'motivo_rejeicao' => $intolUs->getMotivo_rejeicao() ];
            if ($intolUs->getEstado()->getCodigo() == REJEITADA) {
                $sql .= ", motivo_rejeicao = :motivo_rejeicao";
                $params['motivo_rejeicao'] = $intolUs->getMotivo_rejeicao();
            }
            $sql .= " WHERE codigo = :codigo";
            $params['codigo'] = $intolUs->getCodigo();            

            return StatementBuilder::update($sql, $params);
        }


        public static function Popula($row)
        {
            $intol = IntoleranciaDao::SelectPorCodigo($row['intolerancia_cod']);
            $estado = IntoleranciaEstadoDao::SelectPorCodigo($row['estado_cod']);

            $intol_us = new IntoleranciaUsuario;
            $intol_us->setCodigo($row['codigo']);

            $intol_us->setIntolerancia($intol);
            $intol_us->setEstado($estado);
            
            $intol_us->setMotivo_rejeicao($row['motivo_rejeicao']);

            $intol_us->setDocumento($row['arquivo']);

            return $intol_us;
        }


        public static function PopulaVarias($rows)
        {  
            $intols_us = [];
            foreach($rows as $row) {
                $intols_us[] = self::Popula($row);
            }
            return $intols_us;
        }

        
        public static function SelectPorCodigo($codigo)
        {
            return self::Popula(
                StatementBuilder::select(
                    "SELECT * FROM Usuario_intolerancia WHERE codigo = :codigo",
                    ['codigo' => $codigo]
                )[0]
            );
        }


        public static function SelectTodas()
        {
            return self::PopulaVarias(
                StatementBuilder::select("SELECT * FROM Usuario_intolerancia")
            );
        }
    }
?>