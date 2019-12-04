<?php
require_once("AbsCodigo.class.php");
require_once("Alimentacao.class.php");
require_once("Frequencia.class.php");

class Usuario extends AbsCodigo
{
    private $nome;
    private $username;
    private $senha;
    private $tipo;
    private $email;
    private $alimentacao; // vegetariano, vegano ou nenhum dos dois
    private $frequencia; // se almoça sempre no if, nunca, as vezes etc.
    private $intolerancias = array();
    private $token;

    public function setUsername($username)
    {
        $this->username = $username;
    }
    public function getUsername()
    {
        return $this->username;
    }
    
    public function setSenha($senha)
    {
        $this->senha = $senha;
    }
    public function getSenha()
    {
        return $this->senha;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setTipo($t)
    {
        $this->tipo = $t;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getEmail()
    {
        return $this->email;
    }

    public function setAlimentacao($a)
    {
        if ($a instanceof Alimentacao) {
            $this->alimentacao = $a;
        }
    }

    public function getAlimentacao()
    {
        return $this->alimentacao;
    }

    public function setFrequencia($f)
    {
        if ($f instanceof Frequencia) {
            $this->frequencia = $f;
        }
    }

    public function getFrequencia()
    {
        return $this->frequencia;
    }

    public function setIntolerancia($intol)
    {
        if ($intol instanceof IntoleranciaUsuario) {
            $this->intolerancias[] = $intol;
        }
    }

    public function getIntolerancias()
    {
        return $this->intolerancias;
    }


    public function hash()
    {
        return null;
    }


    public function gerarToken()
    {
        $tamanho = 192;
        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%&*()';
        
        $token = '';
        for ($i = 0; $i < $tamanho; $i++) {
            $token .= $caracteres[mt_rand(0, (strlen($caracteres) - 1))];
        }
        
        $this->token = $token;        
    }


    public function token()
    {
        return $this->token;
    }
}
