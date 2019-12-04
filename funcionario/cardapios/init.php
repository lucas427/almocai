<?php

$root_path = "../../";

include "{$root_path}valida_secao.php";
valida_secao_tipo($root_path, ['FUNCIONARIO', 'ADMINISTRADOR']);

require_once "{$root_path}classes/SemanaCardapio.class.php";
require_once "{$root_path}classes/SemanaCardapioDao.class.php";
