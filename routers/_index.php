<?php

use \resources\router\router			as router;


\view::call(resources\template_native\view_template_native::call());





# Файл группы маршрутов
$file_name = \registry::call()->get('FOLDER_ROOT') . 'routers/' . router::call()->get_group() . '.php';
# Если файл группы маршрутов существует
if (file_exists($file_name)) {
	# Подгружаем маршруты
	require_once($file_name);
} else {
	$func = function () {
		$content = \registry::call()->get('_ELBRUS_TITLE') . ' (v' . \registry::call()->get('_ELBRUS_VERSION') . ')';

		\view::call()->variable('title', $content)
				->display('index');
	};
	# Заглавная страница
	router::call()->any('/', $func);
}






# Вызов обработчика маршрутизатора (перед этим указываем страницу по умолчанию)
$content = router::call()->router();



?>
