<?php

if (class_exists('Elbrus', false)) {
	return;
}

$classMap = [
	'Elbrus\\Framework\\core'       => 'Elbrus',
];

foreach ($classMap as $class => $alias) {
	class_alias($class, $alias);
}
