<?php
session_start();
session_unset();
session_destroy(); // Destroi a sessão
header("Location: index.php");
exit();
?>