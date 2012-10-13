<?php if ( !defined('BASEPATH')) die();

require_once 'application/library/utility/ErrorCodes.php';
require_once 'application/library/utility/ServerMessages.php';


class PreController extends CI_Controller
{
	const SYSTEM_SESSION_NAME_SPACE = "SYSTEM_SESSION_NS";
	
	const SYSTEM_SESSION_LOGIN_STATE_NAME = "SYSTEM_LOGIN_STATE";
	
	CONST SYSTEM_USER_DATABASE_SESSION_NAME = "CLIENT_DATABASE_NAME";
	
	const SYSTEM_LOGIN_STATE_LOGIN_FAILED 		= -1;
	const SYSTEM_LOGIN_STATE_AUTHENTICATED 		= 1;
	const SYSTEM_LOGIN_STATE_LOCKED 			= 2;
	const SYSTEM_LOGIN_STATE_PUBLIC_MODULES 	= 3;
	
	
	private static $__controllerSegment = '';

	private static $__methodSegment = '';
	
	protected static $__session;
	
	protected $_page = 0;
	
	protected $_limit = 0;
	
	protected $_sort_col_name = '';

	protected $_sort_order_name = '';
	
	protected $_indexedValues;
	
	protected $_prefixedValues;
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->library('session');
		
		/*************************************
		 * 
		 * Init MODULE and ACTION SEGMENTS
		 *
		 ************************************/
		self::$__controllerSegment = $this->uri->segment( 1, 0 );
		
		self::$__methodSegment     = $this->uri->segment( 2, 0 );
 
		$this->setPrefixedPostValues();
		
		
		// grid page number
		$this->_page = isset( $this->_indexedValues[ "page_number" ] ) ? $this->_indexedValues[ "page_number" ] : 1;
		
		// get index row - i.e. user click to sort. At first time sortname parameter -
		// 	after that the index from colModel
		$this->_sort_col_name = isset( $this->_indexedValues[ "sort_col_name" ] ) ? $this->_indexedValues[ "sort_col_name" ] : '';
		
		//get how many rows we want to have into the grid - rowNum parameter in the grid 
		$this->_limit = isset( $this->_indexedValues[ "rows_per_page" ] ) ? $this->_indexedValues[ "rows_per_page" ] : 30;
 
		//sorting order - at first time sortorder
		$this->_sort_order_name = isset( $this->_indexedValues[ "sort_order_name" ] ) ? $this->_indexedValues[ "sort_order_name" ] : 'desc';
		
		if( strstr( $this->_sort_col_name, "_stampa" ) !== FALSE )
		{
			$this->_sort_col_name = substr( $this->_sort_col_name, 0, strlen( "_stampa") * -1 );
		}
		
		if( strstr( $this->_sort_col_name, SAVE_CELL_PREFIX_NAME ) !== FALSE )
		{
			$this->_sort_col_name = substr( $this->_sort_col_name, strlen( SAVE_CELL_PREFIX_NAME ) );
		}
		
		if( strstr( $this->_sort_col_name, INDEX_CELL_PREFIX_NAME ) !== FALSE )
		{
			$this->_sort_col_name = substr( $this->_sort_col_name, strlen( INDEX_CELL_PREFIX_NAME ) );
		}
	}

	public function init()
	{	
		// for use with other controllers ?????????
		self::$__session = $this->session;
		
		if( $this->isUserLoggedIn() === TRUE  )
		{
			log_message( "debug", "User IS logged in at PreController.");
			// user is logged in let them pass to home page.
			if( self::$__controllerSegment == "" || ( self::$__controllerSegment != "" && self::$__methodSegment == "" ) )
			{
				log_message( "debug", "User IS logged in at PreController. No Controllers requested" );
				
				// module index page requested. load language automatically
				$this->_loadLang();
				
				// no controllers requested. display home screen
				if( self::$__controllerSegment == "" )
					$this->load->view( "homepage", array( "language" => $this->lang->language ) );
			}
				
		}
		else
		{
			if( self::$__controllerSegment === "users" && self::$__methodSegment === "authenticate" )
			{
				//user is trying to authenticate. let him through
				log_message( "debug", "User is traying to log in.");
			}
			else if( self::$__controllerSegment || self::$__methodSegment )
			{
				//user is not logged in and has requested resources. redirect him to home page.
				//header( "Location: " . BASEURI );
				die( "Access Denied. You are not aouthorized to view this content!" );
			}
			else 
			{
				log_message( "debug", "User is NOT logged in and didn't attend to");
				//user is not logged in and didn't attend to, so display authentication view again.
				$this->load->view( "authentication" );
			}
		}

	}
	
	protected function _loadLang()
	{
		$this->lang->load( 'main', 'serbian_latin' );
	}
	
	public function isUserLoggedIn()
	{
		
		$username = self::$__session->userdata( "username" );
		$user_id  = self::$__session->userdata( "user_id" );

		if( strlen( $username ) > 3 && is_numeric( $user_id ) && $user_id > 0 )
		{
			log_message( "debug", "User is logged in at PreController::isUserLoggedIn()");
			return TRUE;
		}
		else
		{
			log_message( "debug", "User is NOT logged in at PreController::isUserLoggedIn()");
			return FALSE;
		}
	}

	
	public function dispatchResultXml( $r_array, $totalRows, $metadata = NULL )
	{
		if(  $this->_limit > 0 ) 
		{ 
		     $totalPages = ceil( $totalRows / $this->_limit ); 
		} 
		else 
		{ 
		     $totalPages = 1; 
		} 
		 
		// if for some reasons the requested page is greater than the total 
		// set the requested page to total page 
		if ( $this->_page > $totalPages ) $this->_page = $totalPages;
		
		$s = "<?xml version='1.0' encoding='utf-8'?>";
		$s .= "<rows>";
		$s .= "<limit>".$this->_limit."</limit>";
		$s .= "<page>".$this->_page."</page>";
		$s .= "<total>".$totalPages."</total>";
		$s .= "<records>". $totalRows."</records>";
 		$s .= "<metadata>" . $this->convertToXml( $metadata ) . "</metadata>";
		
		$s .= $this->convertToXml( $r_array );
		
		$s .= "</rows>";
		
		$this->dispatchXml( $s );
	}
	
	public function convertToXml( $r_array )
	{
		$s = '';
		
		if( ! is_array( $r_array ) ) 
			return $s;
		
		(int)$c = 0;
		
		foreach( $r_array as $rowid => $r ) 
		{
			
			if( is_array( $r ) )
			{
				$c++;
				$s .= "<row id='". $c ."'>";
				
				foreach( $r as $col_key => $col_val )
				{
					
					if( $this->isValueValidForXmlTag( $col_val ) === true )
					{
						$s .= "<".$col_key.">". $col_val."</".$col_key.">";
					}   
					else
					{
						$s .= "<".$col_key."><![CDATA[". $col_val ."]]></".$col_key.">";
					}   
					     
				}
				
				$s .= "</row>";
			}
			else 
			{
					if( $this->isValueValidForXmlTag( $r ) === true )
					{
						$s .= "<".$rowid.">". $r."</".$rowid.">";
					}   
					else
					{
						$s .= "<".$rowid."><![CDATA[". $rl ."]]></".$rowid.">";
					}
				
			}
		}
		
		return $s;
	}
	
	public function isValueValidForXmlTag( &$value )
	{
		if( strlen( $value ) == 0 )
		{
			return true;
		}
		
		if( preg_match( '/<|&|<\!\[CDATA\[|\]\]>/i', $value ) === 0 )
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	public function dispatchXml( $xml )
	{
		header("Content-type: text/xml;charset=utf-8");
		echo $xml;
	}
	
	
	public function buildSelectElement( $result, $id_f, $value_f, $firstOptEmpty = false, $id = "", $name = "", $class = "" )
	{
		$s =  "<select id='". $id ."' class='". $class ."' name='".$name."'>";
		
		if( $firstOptEmpty )
			$s .= "<option value=''>--</option>";	
		
		foreach( $result as $v  )
		{
			$s.= "<option value='" . $v[ $id_f ] . "'>" . $v[ $value_f ] ."</option>"; 
		}
		
		$s .= "</select>";
		
		return $s;
	}
	
	
	public function buildSelectElementRamo( $result, $id_f, $value_f, $value_s, $firstOptEmpty = false, $id = "", $name = "", $class = "" )
	{
		$s =  "<select id='". $id ."' class='". $class ."' name='".$name."'>";
		
		if( $firstOptEmpty )
			$s .= "<option value=''>--</option>";	
		
		foreach( $result as $v  )
		{
			$s.= "<option value='" . $v[ $id_f ] . "'>" . $v[ $value_f ] ." " . $v[ $value_s ] ."</option>"; 
		}
		
		$s .= "</select>";
		
		return $s;
	}
	
	public function getRowsOffset( $page, $rowsPerPage )
	{
		if( $page < 0 || $rowsPerPage < 0 ) return 0;
		
		
		return $rowsPerPage * $page - $rowsPerPage;
	}
	
	
	public function setPrefixedPostValues()
	{	
		if( ! defined( "SAVE_CELL_PREFIX_NAME" ) || ! defined( "INDEX_CELL_PREFIX_NAME" ) )
			return;
		
		$ok = "";
		
		$this->_indexedValues = array();
		$this->_prefixedValues = array();
		
		foreach( $_POST as $k => $v )
		{
			if( strstr( $k, SAVE_CELL_PREFIX_NAME ) !== FALSE )
			{
				if( $ok = preg_replace( "/".SAVE_CELL_PREFIX_NAME."/", "", $k ) )
				{
					$this->_prefixedValues[ $ok ] = $v;
				}
			}
			else if( strstr( $k, INDEX_CELL_PREFIX_NAME ) !== FALSE )
			{
				if( $ok = preg_replace( "/".INDEX_CELL_PREFIX_NAME."/", "", $k ) )
				{
					$this->_indexedValues[ $ok ] = $v;
				}
			}
		}
	}
	
	public function postArrayParser( $array = NULL, $separator = NULL )
	{
		if( $array == NULL)
			$array = $_POST;

		if( $separator == NULL )
			$separator = POST_ARRAY_DELIMITER;

		$formated = array();
		(int) $index = 0;
		
		foreach( $array as $k => $v )
		{
			if( preg_match( '/^([a-zA-Z_-]+)([0-9]+)' . $separator . '([a-zA-Z0-9_]+)$/', $k, $m ) )
			{		
				if( count( $m ) == 4 )
				{
					$index = $m[2];
					
					if( array_key_exists( $m[1], $formated ) )
					{
						$formated[ $m[1] ][ $index ][ $m[3] ] = $v;
					}
					else
					{
						
						$formated[ $m[1] ][$index] = array( $m[3] => $v );
					}
				}
			}
		}
		
		return $formated;
	}
		
	
	public function dispatchResultAutoComplete( $r_array, $idf, $lf, $vf, $opt_data_keys = array() )
	{
		if( is_array( $r_array ) && count( $r_array ) > 0 )
		{
			$data = "[";
		
			$rs = "";
			foreach ( $r_array as $k => $r )
			{ 
				$data .= '{';
				
				$rs .= '"id":"'.$r[$idf].'", "label":"'.$r[$lf].'", "value":"'.$r[$vf].'"';

				 
				 foreach( $opt_data_keys as $v )
				 {
				 	$rs .= ',"'.$v.'":"'.@$r[$v].'"';
				 }
				
				 
				$data .= $rs;
				$data .= '},';
				$rs = "";
				
			}
	
			$data  = substr( $data, 0, -1 );
			$data .= "]";
			
			echo $data;
		}
	}
	
	public function dispatchResultJson( $data )
	{
		
		echo json_encode($data);
	}
	
	public function brojDokumenta( $table_name, $auto_inc_id, $id, $tip )
	{
		$this->db->where( "YEAR( datum_unosa ) = ", DOC_YEAR );
		$this->db->where( "tip",  $tip );
		$query = $this->db->get( $table_name );
		
		if( $query )
		{
	     	$dokBroj =  $query->num_rows();
	     	
	     	if( ! $dokBroj  )
	     	{ 
	     		$dokBroj = 1; 
	     	}
	     	else 
	     	{
	     		$dokBroj++;
	     	}
		}
		else
		{
	  		echo ErrorCodes::DATABASE_ERROR;
	  		exit();
		}
		
	   $year = DOC_YEAR;	
	   $leadingZeros = 6 - strlen( $dokBroj );
	   
	   $brojDokumenta = "";
	   
	   for( $l = 0; $l < $leadingZeros - 1; $l++ )
	   	$dokBroj = '0' . $dokBroj;
	   
	   $brojDokumenta = $year . $id . $dokBroj;
	   
	   if( $tip == 'cg') 
	   {
	   		$brojDokumenta = $brojDokumenta . "-CG";
	   }
	   
	   return $brojDokumenta;
	}
	
	public function commaDelimitedToArray( $s )
	{
		return array_reverse( explode( ',', base64_decode( $s ) ) );
	}
	
	
	
}
?>