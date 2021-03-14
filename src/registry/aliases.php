<?php

if (class_exists('registry', false)) {
	return;
}

$classMap = [
	'Elbrus\\Framework\\registry\\registry'       => 'registry',
];

foreach ($classMap as $class => $alias) {
	class_alias($class, $alias);
}
