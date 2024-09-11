<?php

namespace Elbrus\Framework\registry\_inf;

interface inf_registry_cmd {

	/** Заполняет/обновляет ключ регистра
	 * @param string/array $set Настройки
	 * @return array Набор ключей со значениями
	 */
	public function execute($set);

/**/
}
