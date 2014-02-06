<?php
class Config {
	public static function get($path = null) {
		if($path) {
			$config = $GLOBALS['config'];
			$path = explode('/', $path);
			if(!isset($config[$path[0]])) {
				return false;	
			}
			foreach($path as $bit) {
				if(isset($config[$bit])) {
					$config = $config[$bit];
				}
			}
			return $config;
		}
		return false;
	}
}