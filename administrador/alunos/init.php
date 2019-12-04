<?php 

$root_path = '../../';

// Validar seção adm
include($root_path."valida_secao.php");
valida_secao_tipo($root_path, 'ADMINISTRADOR');

// Inclui objetos 
require_once($root_path."classes/UsuarioDao.class.php");
require_once($root_path."administrador/componentes/geradores.php");

// Inclui geradores.php, com funções para gerar o conteúdo (preencher o template) da página
include_once '../componentes/geradores.php';