<?php

if (class_exists('view', false)) {
	return;
}

$classMap = [
	'Elbrus\\Framework\\view\\view'                    => 'view',
	'Elbrus\\Framework\\view\\_abs\\abs_adapter'       => 'view_adapter',
];



foreach ($classMap as $class => $alias) {
	class_alias($class, $alias);
}
