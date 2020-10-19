<?php

use \resources\router\router			as router;

\view::call(resources\template_native\view_template_native::call());





# Формируем базовые переменные для шаблона
$header = [
	'title' => \registry::call()->get('_ELBRUS_TITLE') . ' (ver. ' . \registry::call()->get('_ELBRUS_VERSION') . ')',
];

\view::call()->variable('header', $header);





$func_def = function () {
	\view::call()
			->display('index');
};
# Заглавная страница (в случае необходимости можно переопределить далее)
router::call()->default_page($func_def);





# Получаем имя группы
$group = router::call()->get_group();
# Файл группы маршрутов
$file_name = \registry::call()->get('FOLDER_ROOT') . 'routers/' . $group . '.php';
# Если файл группы маршрутов существует
if (file_exists($file_name)) {
	# Подгружаем маршруты
	require_once($file_name);
} else {
	# Заглавная страница
	router::call()->any('/', $func_def);
}





# Вызов обработчика маршрутизатора (перед этим указываем страницу по умолчанию)
$content = router::call()->router();
