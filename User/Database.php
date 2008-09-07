<?php

/**
 * This class manipulates all of the user data in the scope of a database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_User_Database extends Artisan_User {
	
	private $DB = NULL;
	
	///< The name of the table that holds user data, this is also defined in Artisan/Auth/Database.php!
	const TABLE_USER = 'artisan_user';
	
	public function __construct(Artisan_Database &$db) {
		$this->DB = &$db;
	}
}

?>
