<?php

/**
 * @see Artisan_Customer
 */
//require_once 'Artisan/Customer.php';
require_once 'Artisan/User/Db.php';

require_once 'Artisan/Customer/Interface.php';

class Artisan_Customer_Operator_Db extends Artisan_User_Db {
	// no constructor, use default
	
	// overwrite _update() and _insert() to take history into account
	// comment_history management
	
}