<?php

$root_path = "../../";

include $root_path . "valida_secao.php";
valida_secao_tipo($root_path, ["FUNCIONARIO", "ADMINISTRADOR"]);

include $root_path . "config.php";
require_once $root_path . "classes/SemanaCardapio.class.php";
require_once $root_path . "classes/SemanaCardapioDao.class.php";
require_once $root_path . "classes/DiaAlmoco.class.php";
require_once $root_path . "classes/DiaAlmocoDao.class.php";