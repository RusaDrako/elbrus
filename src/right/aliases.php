<?php

if (class_exists('right', false)) {
	return;
}

$classMap = [
	'Elbrus\\Framework\\right\\right'       => 'right',
];

foreach ($classMap as $class => $alias) {
	class_alias($class, $alias);
}
