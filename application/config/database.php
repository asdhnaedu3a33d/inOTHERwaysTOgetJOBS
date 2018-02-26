<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
// $active_group = 'production';
$active_record = TRUE;

$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'root';
$db['default']['password'] = '';
$db['default']['database'] = 'sirenbangda_db_sirenbangda_ng_new';
$db['default']['dbdriver'] = 'mysqli';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

$db['group_2']['hostname'] = 'localhost';
$db['group_2']['username'] = 'root';
$db['group_2']['password'] = '';
$db['group_2']['database'] = 'sirenbangda_db_sirenbangda_ng_1923';
$db['group_2']['dbdriver'] = 'mysqli';
$db['group_2']['dbprefix'] = '';
$db['group_2']['pconnect'] = TRUE;
$db['group_2']['db_debug'] = TRUE;
$db['group_2']['cache_on'] = FALSE;
$db['group_2']['cachedir'] = '';
$db['group_2']['char_set'] = 'utf8';
$db['group_2']['dbcollat'] = 'utf8_general_ci';
$db['group_2']['swap_pre'] = '';
$db['group_2']['autoinit'] = TRUE;
$db['group_2']['stricton'] = FALSE;

$db['devserver']['hostname'] = 'devserver1.unud.ac.id';
$db['devserver']['username'] = 'sirenbangda';
$db['devserver']['password'] = 's1r3nb4ngd4';
$db['devserver']['database'] = 'sirenbangda_db_sirenbangda';
$db['devserver']['dbdriver'] = 'mysqli';
$db['devserver']['dbprefix'] = '';
$db['devserver']['pconnect'] = TRUE;
$db['devserver']['db_debug'] = TRUE;
$db['devserver']['cache_on'] = FALSE;
$db['devserver']['cachedir'] = '';
$db['devserver']['char_set'] = 'utf8';
$db['devserver']['dbcollat'] = 'utf8_general_ci';
$db['devserver']['swap_pre'] = '';
$db['devserver']['autoinit'] = TRUE;
$db['devserver']['stricton'] = FALSE;

$db['development']['hostname'] = 'sirenbangda-bappeda.klungkungkab.go.id';
$db['development']['username'] = 'sirenbangda';
$db['development']['password'] = 's1r3nb4ngd4';
$db['development']['database'] = 'sirenbangda_db_development';
$db['development']['dbdriver'] = 'mysqli';
$db['development']['dbprefix'] = '';
$db['development']['pconnect'] = TRUE;
$db['development']['db_debug'] = TRUE;
$db['development']['cache_on'] = FALSE;
$db['development']['cachedir'] = '';
$db['development']['char_set'] = 'utf8';
$db['development']['dbcollat'] = 'utf8_general_ci';
$db['development']['swap_pre'] = '';
$db['development']['autoinit'] = TRUE;
$db['development']['stricton'] = FALSE;

$db['production']['hostname'] = 'sirenbangda-bappeda.klungkungkab.go.id';
$db['production']['username'] = 'sirenbangda';
$db['production']['password'] = 's1r3nb4ngd4';
$db['production']['database'] = 'sirenbangda_db_sirenbangda_ng';
$db['production']['dbdriver'] = 'mysqli';
$db['production']['dbprefix'] = '';
$db['production']['pconnect'] = TRUE;
$db['production']['db_debug'] = TRUE;
$db['production']['cache_on'] = FALSE;
$db['production']['cachedir'] = '';
$db['production']['char_set'] = 'utf8';
$db['production']['dbcollat'] = 'utf8_general_ci';
$db['production']['swap_pre'] = '';
$db['production']['autoinit'] = TRUE;
$db['production']['stricton'] = FALSE;

$db['production_2']['hostname'] = 'sirenbangda-bappeda.klungkungkab.go.id';
$db['production_2']['username'] = 'sirenbangda';
$db['production_2']['password'] = 's1r3nb4ngd4';
$db['production_2']['database'] = 'sirenbangda_db_sirenbangda_ng_1923';
$db['production_2']['dbdriver'] = 'mysqli';
$db['production_2']['dbprefix'] = '';
$db['production_2']['pconnect'] = TRUE;
$db['production_2']['db_debug'] = TRUE;
$db['production_2']['cache_on'] = FALSE;
$db['production_2']['cachedir'] = '';
$db['production_2']['char_set'] = 'utf8';
$db['production_2']['dbcollat'] = 'utf8_general_ci';
$db['production_2']['swap_pre'] = '';
$db['production_2']['autoinit'] = TRUE;
$db['production_2']['stricton'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */
?>
