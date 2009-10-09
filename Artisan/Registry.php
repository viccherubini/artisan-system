<?php

class Artisan_Registry {
	private static $_initialized = false;
	
	private static $_object_list = array();
	
	private static $_model_list = array();
	
	
	public static function push($name, $item, $overwrite=true) {
		$exists = asfw_exists($name, self::$_object_list);
		if ( false === $exists || ( true === $exists && true === $overwrite ) ) {
			self::$_object_list[$name] = $item;
		}
		return true;
	}
	
	public static function pop($name, $delete=false) {
		$item = asfw_exists_return($name, self::$_object_list);
		if ( true === $delete ) {
			unset(self::$_object_list[$name]);
		}
		return $item;
	}
	
	
	public static function pushModel(Artisan_Controller_Model $model, $overwrite=true) {
		$name = $model->getRegistryName() . '.' . $model->getId();
		$exists = asfw_exists($name, self::$_model_list);
		
		if ( false === $exists || ( true === $exists && true === $overwrite ) ) {
			self::$_model_list[$name] = $model;
		}
		return true;
	}
	
	public static function popModel($model, $id, $delete=false) {
		
	}
	
	
	public function build($model, $pkey=NULL) {
		
	}
}