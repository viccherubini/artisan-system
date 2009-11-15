<?php

class Artisan_Db_Builder {
	private $_dbConn = NULL;
	private $_table_name = '_sql_schema';
	private $_build_dir = NULL;

	private $_built_script_list = array();
	private $_script_list = array();
	private $_to_build_list = array();

	public function __construct(Artisan_Db $dbConn, $build_dir) {
		$this->_dbConn = $dbConn;
		$this->_build_dir = rtrim($build_dir, DIRECTORY_SEPARATOR);
	}
	
	public function __destruct() {
		
		
	}
	
	public function run() {
		$this->_init();
		$this->_load();
		$this->_execute();
	}
	
	public function setTableName($table_name) {
		$this->_table_name = $table_name;
		return $this;
	}
	
	private function _load() {
		$result_schema = $this->_dbConn->select()
			->from($this->_table_name)
			->orderBy('script_name')
			->query();
		while ( $schema = $result_schema->fetch() ) {
			$this->_built_script_list[] = $schema['script_name'];
		}
		
		if ( true === is_dir($this->_build_dir) ) {
			$dir = $this->_build_dir . DIRECTORY_SEPARATOR . '*.sql';
			foreach ( glob($dir) as $file ) {
				$this->_script_list[] = trim(basename($file));
			}
		}
		
		$this->_to_build_list = array_diff($this->_script_list, $this->_built_script_list);
	}
	
	private function _execute() {
		if ( count($this->_to_build_list) > 0 ) {
			foreach ( $this->_to_build_list as $file ) {
				$sql_file = $this->_build_dir . DIRECTORY_SEPARATOR . $file;
				if ( true === is_file($sql_file) ) {
					$sql_string = file_get_contents($sql_file);
					if ( false === empty($sql_string) ) {
						try {
							$this->_dbConn->multiQuery($sql_string);
							
							$sql_schema = array('date_create' => time(), 'script_name' => $file);
							$this->_dbConn->insert()->into($this->_table_name)->values($sql_schema)->query();
						} catch ( Artisan_Exception $e ) {
							echo '**** ERROR ****' . PHP_EOL;
							echo 'Failed On: ' . $file . PHP_EOL . PHP_EOL;
							echo $e . PHP_EOL;
						}
					}
				}
			}
		}
	}
	
	private function _init() {
		$result_table = $this->_dbConn->query('SHOW TABLES');
		
		// Nice fresh clean database, create SQL schema.
		if ( 0 == $result_table->numRows() ) {
			$table_sql = 'DROP TABLE IF EXISTS `_sql_schema`;
				CREATE TABLE IF NOT EXISTS `_sql_schema` (
					`change_id` int(11) NOT NULL auto_increment,
					`date_create` int(11) NOT NULL,
					`script_name` varchar(50) NOT NULL,
					PRIMARY KEY  (`change_id`),
					KEY `script_name` (`script_name`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;';
			$this->_dbConn->multiQuery($table_sql);
		}
	}
}