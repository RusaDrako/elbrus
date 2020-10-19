<?php
session_start();

define('_ELBRUS_START', microtime(true));



error_reporting(E_ALL);
ini_set("display_errors", true);



# Подгружаем ядро
require_once('../core/core.php');
# Запуск ядра и всех переменных
\core\core::call(/*Берём настройки по умолчанию*/);



# Проходим по маршрутам
require_once(\registry::call()->get('FOLDER_ROOT') . 'routers/_index.php');
