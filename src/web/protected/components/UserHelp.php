<?php
/*********************************************************
* Clase para ayuda de logeo y administracion de usuarios *
* @file UserHelp.php                                     *
*********************************************************/

class UserHelp extends CWebModule
{
	/**
	 * @var boolean
	 * @desc allow auth for is not active user
	 */
	public static $loginNotActiv=false;
	/**
	 * @return hash string.
	 */
	public static function encrypting($string="") {
		$hash = 'md5';
		if ($hash=="md5")
			return md5($string);
		if ($hash=="sha1")
			return sha1($string);
		else
			return hash($hash,$string);
	}
}
?>