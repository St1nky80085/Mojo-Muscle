<?php
// =============================================
// THE MOJO MUSCLE - Logout Handler
// File: handlers/logout.php
// =============================================

session_start();
session_unset();
session_destroy();

header('Location: ../HOME.php');
exit;
?>
