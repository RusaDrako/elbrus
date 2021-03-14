<?php

if (class_exists('view', false)) {
	return;
}

$classMap = [
	'Elbrus\\Framework\\view\\view'       => 'view',
];

foreach ($classMap as $class => $alias) {
	class_alias($class, $alias);
}
