<?php
/**
* 
*/
class Utility
{
	static function ensure($param, $type = 'str')
	{
		switch($type) {
			case 'int': break;
			default:
				if(!isset($param) )
					return '';
				//else if (is_null($param))
				//	return '';
				//else if (empty($param))
				//	return '';
				else
					return $param;
		}
	}
}
?>