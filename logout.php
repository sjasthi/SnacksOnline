<?php
require_once __DIR__ . '/includes/auth.php';
session_destroy();
header('Location: /snacksonline/index.php');
exit;
