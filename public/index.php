<?php
session_start();

define('_ELBRUS_START', microtime(true));



error_reporting(E_ALL);
ini_set("display_errors", true);



# Загружаем ядро и всех переменных
require_once('../core/core_con.php');




# Проходим по маршрутам
require_once(\registry::call()->get('FOLDER_ROOT') . 'routers/_index.php');


?>
