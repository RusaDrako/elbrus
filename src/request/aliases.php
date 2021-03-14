<?php
if (class_exists('request', false)) {
	return;
}

$classMap = [
	'Elbrus\\Framework\\request\\request'       => 'request',
];

foreach ($classMap as $class => $alias) {
	class_alias($class, $alias);
}
