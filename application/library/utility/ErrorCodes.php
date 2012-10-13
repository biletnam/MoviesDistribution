<?php if ( ! defined('BASEPATH')) die();

class ErrorCodes
{
	const GENERAL_ERROR 					= 100;
	const SERVER_FAILED 					= 1000;
	const SESSION_EXPIRED 					= 1500;
	const SESSION_ERROR 					= 1600;
	const INVALID_INPUT 					= 3000;
	const ALREADY_EXISTS 					= 3100;
	const USER_NOT_FOUND				    = 3200;
	const USER_OR_PASS_WRONG 				= 3500;
	const ACCESS_DENIED 					= 1800;
	const APP_ERROR 						= 1200;
	const APP_PARTIALLY_INSTALL_ERROR 		= 4001;
	const DATABASE_ERROR 					= 1400;

	
	
	
	
	
	/********************************
	 * 
	 * MODULE ERROR CODES
	 * 
	 *******************************/
	
	const GLEDANOST_SAVED_TERMINI_FAILED = 6000;
	
}

?>