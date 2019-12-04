<?php
$root_path = "../";

require_once $root_path."classes/UsuarioDao.class.php";
session_start();
if (isset($_SESSION['codigo'])) {
	UsuarioDao::ApagarToken($_SESSION['codigo']);
}
session_destroy();
header("location:".$root_path."entrar/");
?>
