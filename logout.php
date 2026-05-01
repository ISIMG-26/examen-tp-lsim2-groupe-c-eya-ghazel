<?php
require_once "auth.php";

$_SESSION = [];
session_destroy();

header("Location: veloura.php");
exit;
?>
