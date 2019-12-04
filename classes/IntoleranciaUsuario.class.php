<?php

    class IntoleranciaUsuario extends AbsCodigo {
        private $intolerancia;
        private $estado;
        private $documento;
        private $motivo_rejeicao;

        public function setMotivo_rejeicao($m) { $this->motivo_rejeicao = $m; }
        public function getMotivo_rejeicao() { return $this->motivo_rejeicao; }

        public function setIntolerancia(Intolerancia $intolerancia){
            $this->intolerancia = $intolerancia;
        }
        
        public function setDocumento($documento){
            $this->documento = $documento;
        }

        public function setEstado(IntoleranciaEstado $e) {
            $this->estado = $e;
        }
        public function getEstado() { return $this->estado; }

        public function getIntolerancia(){
            return $this->intolerancia;
        }

        public function getDocumento(){
            return $this->documento;
        }
        
    }
?>