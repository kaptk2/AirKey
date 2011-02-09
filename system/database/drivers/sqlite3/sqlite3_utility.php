<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2010, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * SQLite Utility Class
 *
 * @category	Database
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/database/
 */
class CI_DB_sqlite3_utility extends CI_DB_utility {

	/**
	 * List databases
	 *
	 * There is only one database ever loaded, so we just return the currently
	 * loaded database.
	 *
	 * @access	private
	 * @return	bool
	 */
	function _list_databases()
	{
		return "SELECT db_name()";
	}

	// --------------------------------------------------------------------

	/**
	 * Optimize table query
	 *
	 * SQLite table optimization is the VACUUM command. See
	 * http://www.sqlite.org/lang_vacuum.html
	 *
	 * @access	private
	 * @param	string	the table name
	 * @return	object
	 */
	function _optimize_table($table)
	{
		return "VACUUM";
	}

	// --------------------------------------------------------------------

	/**
	 * Repair table query
	 *
	 * SQLite "repairs" automatically on opening the database.
	 *
	 * @access	private
	 * @param	string	the table name
	 * @return	object
	 */
	function _repair_table($table)
	{
		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * SQLite Export
	 *
	 * @access	private
	 * @param	array	Preferences
	 * @return	mixed
	 */
	function _backup($params = array())
	{
		$dumpresult = false;
		//It would be really, really nice if it worked this way.		
		//Sadly, .dump is a sqlite shell builtin, and I don't feel like copying
		//it, or shoehorning the mysql backup query.
/*		$sql = ".dump";
		$dumpresult = $this->db->query($sql);*/
		return $dumpresult;
	}

	/**
	 *
	 * The functions below have been deprecated as of 1.6, and are only here for backwards
	 * compatibility.  They now reside in dbforge().  The use of dbutils for database manipulation
	 * is STRONGLY discouraged in favour if using dbforge.
	 *
	 */

	/**
	 * Create database
	 *
	 * @access	public
	 * @param	string	the database name
	 * @return	bool
	 */
	function _create_database()
	{
		// In SQLite, a database is created when you connect to the database.
		// We'll return TRUE so that an error isn't generated
		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Drop database
	 *
	 * @access	private
	 * @param	string	the database name
	 * @return	bool
	 */
	function _drop_database($name)
	{
		if ( ! @file_exists($this->db->database) OR ! @unlink($this->db->database))
		{
			if ($this->db->db_debug)
			{
				return $this->db->display_error('db_unable_to_drop');
			}
			return FALSE;
		}
		return TRUE;
	}

}

/* End of file sqlite3_utility.php */
/* Location: ./system/database/drivers/sqlite3/sqlite3_utility.php */
