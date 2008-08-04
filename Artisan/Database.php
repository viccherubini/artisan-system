<?php


Artisan_Library::load('Database/Monitor');
Artisan_Library::load('Database/Exception');

/**
 * The abstract Database class from which other database classes are extended.
 * Because this class is abstract and contains many abstract members, it is necessary
 * to extend it to use it. For example, if you want to connect to a MySQL database
 * using mysqli, you would use new Artisan_Database_Mysqli($config) and go from there.
 */
abstract class Artisan_Database {
	protected $CONFIG = NULL; ///< Holds the database configuration information, must be of type Artisan_Config.

	/**
	 * Default constructor.
	 * @param $C is an Artisan_Config configuration instance, optional.
	 * @return New database instance, ready for connecting to.
	 */
	public function __construct(Artisan_Config &$C = NULL) {
		if ( true === is_object($C) ) {
			$this->setConfig($C);
		}
	}

	/**
	 * Default destructor.
	 * @return Nothing, database and configuration destroyed.
	 */
	public function __destruct() {
		unset($this->_C);
	}


	public function setConfig(Artisan_Config &$C) {
		$this->CONFIG = $C;
	}

	public function &getConfig() {
		return $this->CONFIG;
	}

	abstract public function connect();

	abstract public function disconnect();

	abstract public function getNumRows();
	abstract public function getRowsAffected();

	abstract public function query(Artisan_Sql $sql);
	abstract public function queryFetch(Artisan_Sql $sql);

	abstract public function fetch();
	abstract public function free();

	abstract public function isConnected();

	abstract public function escape($string);

	abstract protected function _start();
	abstract protected function _cancel();
	abstract protected function _commit();

	abstract public function queue($query_list);
}

?>