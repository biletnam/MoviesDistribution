<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/hooks/PreController.php';

class Users extends PreController 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();	
	}
	
	public function authenticate()
	{	
		
		// if user is logged in already return him to home page
		if( $this->isUserLoggedIn() === TRUE )
		{
			log_message( "debug", "User is trying lo log in while is already logged in.");
			header( "Location: " . BASEURI );
		} 
		
		$username = trim( $this->input->post( "movies_admin_username" ) );
		$password = $this->input->post( "movies_admin_password" );
		
		if( strlen( $username ) > 3 && strlen( $password ) > 3 )
		{
			$this->db->select( 'user_id, username, email, ime_korisnika' );	
			$query = $this->db->get_where('users', array( 'username' => $username, "password" => $this->encodePassword( $password ) ) );
			
			// if we have found the user set session vars and go to home page
			if( $query->num_rows() == 1 )
			{
				log_message( "debug", "User IS LOGGED IN. Writing to SESSION and  REDIRECTING.");
				self::$__session->set_userdata( array( "username" => $username, 
													   "user_id" => $query->row( 0 )->user_id, 
													   "ime_korisnika" => $query->row( 0 )->ime_korisnika ) );
				header( "Location: " . BASEURI );
				
			}
			else
			{
				$this->load->view( "authentication", array( "errorMessage" => "Корисник са овим подацима не постоји!" ) );
			}
			
			
//			ACTIVE RECORD INSERT EXAMPLE		
//			
//			$data = array(
//			               'title' => $title,
//			               'name' => $name,
//			               'date' => $date
//			            );
//
//			$this->db->insert('mytable', $data); 


		}
		else
		{
			$this->load->view( "authentication", array( "errorMessage" => "Подаци за ауторизацију нису валидни!" ) );
		}
		
	}
	
	
	protected function _checkUsername( $un )
	{
		return $this->db->select( "*" )->where( "username", $un )->get("users")->num_rows();
	}	
	
	public function create()
	{
	    if( ! $this->db->_error_number() && $this->_checkUsername( $this->_prefixedValues[ "username" ] ) == 0 )
	    {
	    	if( $this->_prefixedValues[ 'sifra_korisnika' ] != $this->_prefixedValues[ 'sifra_korisnika_repeat' ] )
	    	{
	    		echo ErrorCodes::INVALID_INPUT;	
	    		return;
	    	}
	    	
	    	
	    	$this->_prefixedValues[ "password" ] = $this->encodePassword( $this->_prefixedValues[ 'sifra_korisnika' ] );
	    	
	    	unset( $this->_prefixedValues[ "sifra_korisnika" ] );
	    	unset( $this->_prefixedValues[ "sifra_korisnika_repeat" ] );
	    	
	    	$this->db->insert( 'users', $this->_prefixedValues );
	   
		   if( $this->db->affected_rows() == 1 )
		   {
		   		echo 0;
		   }
		   else
		   {
		   		echo ErrorCodes::DATABASE_ERROR;
		   }
	    }
	    else 
	    {
	    	echo ErrorCodes::ALREADY_EXISTS;
	    }
	}
	
	public function read()
	{
		$this->db->select( 'user_id, ime_korisnika, username, email, type' );
		
		$query = $this->db->get( 'users' );
		$this->dispatchResultXml( $query->result_array(), $query->num_rows() );
	}
	
	public function updateUserPassword()
	{
		$ss = $this->input->post( "stara_sifra" );
		$ns = $this->input->post( "nova_sifra" );
		$ps = $this->input->post( "ponovi_sifru" );
		
		$this->db->where( array( "password" => $this->encodePassword( $ss ), "user_id" => $this->input->post( "user_id" ) ) );
		
		if( $this->db->get( "users" )->num_rows() == 1 )
		{
			if( strlen( $ss ) > 0 && strlen( $ns ) > 0 && $ns == $ps )
			{
				
				$this->db->where( 'user_id', $this->input->post( "user_id" )  );
				$this->db->update( 'users', array( "password" => $this->encodePassword( $ns ) ) );
				
				if( ! $this->db->_error_number() )
				{
					echo 0;
				}
				else
				{
					echo ErrorCodes::DATABASE_ERROR;
				}
				
			}
			else
			{
				echo ErrorCodes::INVALID_INPUT;	
			}
		}
		else 
		{
			echo ErrorCodes::USER_NOT_FOUND;
		}
		
	}
	
	public function updateUser()
	{
		
		if( ! $this->db->_error_number() )
	    {
	    	if( isset( $this->_prefixedValues[ "username" ] ) )
	    	{
	    		if( $this->_checkUsername( $this->_prefixedValues[ "username" ] ) != 0 )
	    		{
	    			echo ErrorCodes::ALREADY_EXISTS;
	    			return;
	    		}	
	    	}
	    	
		    $id = @$_REQUEST[ 'id' ];
			
			if( isset(  $id ) && $id > 0 )
			{
				$this->db->where( 'user_id', $id );
				$this->db->update( 'users', $this->_prefixedValues );
			}
			
			if( ! $this->db->_error_number() )
			{
				echo 0;
			}
			else
			{
				echo ErrorCodes::DATABASE_ERROR;
			}	
	    }
	    
	}
	
	public function deleteUser()
	{
		$this->db->where( "user_id", $this->input->post( "user_id" ) );
		$this->db->delete( "users" );
		
		if( $this->db->affected_rows() == 1 )
		{
			echo 0;
		}
		else
		{
			echo ErrorCodes::DATABASE_ERROR;
		}
	}
	
	public function &encodePassword( $password )
	{	
		$encoded =  md5( md5( md5( $password . $this->config->item('encryption_key') ) ) );		
		return $encoded;
	}
	
	public function logout()
	{
		self::$__session->sess_destroy();
		header( "Location: " . BASEURI );
	}
}

/* End of file users.php */
/* Location: ./application/controllers/users.php */