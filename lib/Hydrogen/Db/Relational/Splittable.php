<?php

namespace Hydrogen\Db\Relational;

use Hydrogen\Db\Exception\UndefinedClassAttributeException;

interface Splittable
{
	/**
	 * get splitted table postfix
	 * 
	 * @return int
	 */
	/*protected function getTableName($split_id)
	{
		if (!isset($this->_split_count)) {

			throw new UndefinedClassAttributeException('attr: _split_count
			 must be defined at model class');

		}

		return intavl(fmod($split_id, $this->_split_count));
	}*/
}