<?php

require_once 'Library.php';

class Artisan_Db_Builder {
	private $db = NULL;
	private $table_name = '_sql_schema';
	private $build_dir = NULL;

	private $built_script_list = array();
	private $script_list = array();
	private $to_build_list = array();

	public function __construct(Artisan_Db $db, $build_dir) {
		$this->db = $db;
		$this->build_dir = rtrim($build_dir, DIRECTORY_SEPARATOR);
	}
	
	public function __destruct() {

	}
	
	public function run() {
		$this->init();
		$this->load();
		$this->execute();
	}
	
	public function setTableName($table_name) {
		$this->table_name = $table_name;
		return $this;
	}
	
	private function load() {
		$result_schema = $this->db->select()
			->from($this->table_name)
			->orderBy('script_name')
			->query();
		while ( $schema = $result_schema->fetch() ) {
			$this->built_script_list[] = $schema['script_name'];
		}
		
		if ( true === is_dir($this->build_dir) ) {
			$dir = $this->build_dir . DIRECTORY_SEPARATOR . '*.sql';
			foreach ( glob($dir) as $file ) {
				$this->script_list[] = trim(basename($file));
			}
		}
		
		$this->to_build_list = array_diff($this->script_list, $this->built_script_list);
	}
	
	private function execute() {
		if ( count($this->to_build_list) > 0 ) {
			foreach ( $this->to_build_list as $file ) {
				$sql_file = $this->build_dir . DIRECTORY_SEPARATOR . $file;
				if ( true === is_file($sql_file) ) {
					$sql_string = file_get_contents($sql_file);
					if ( false === empty($sql_string) ) {
						try {
							$this->db->multiQuery($sql_string);
							
							$sql_schema = array('date_create' => time(), 'script_name' => $file);
							$this->db->insert()
								->into($this->table_name)
								->values($sql_schema)
								->query();
						} catch ( Artisan_Exception $e ) {
							echo '**** ERROR ****' . PHP_EOL;
							echo 'Failed On: ' . $file . PHP_EOL;
							echo $e . PHP_EOL;
							break;
						}
					}
				}
			}
		}
	}
	
	private function init() {
		$result_table = $this->db->query('SHOW TABLES');
		
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
			$this->db->multiQuery($table_sql);
		}
	}
}