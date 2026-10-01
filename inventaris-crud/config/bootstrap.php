<?php
session_start();
require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/../includes/functions.php";
$db = Database::getInstance()->getConnection();
