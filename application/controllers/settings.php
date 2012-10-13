<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/hooks/PreController.php';

class Settings extends PreController 
{
public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function index()
	{
		$data = array();
		
		$data[ 'maticna_rs' ] = $this->db->get('maticna_firma' )->result_array();
		$data[ 'maticna_rs' ] = $data[ 'maticna_rs' ][0];
		
		$data[ 'maticna_cg' ] = $this->db->get('maticna_firma_cg' )->result_array();
		$data[ 'maticna_cg' ] = $data[ 'maticna_cg' ][0];
		
		$data[ 'settings' ] = $this->db->get('settings' )->result_array();
		$data[ 'settings' ] = $data[ 'settings' ][0];
		
		
		$data[ 'lang' ] = $this->lang->language;
		
		$this->load->view( "settings", $data );
	}
	
	public function sacuvajPodesavanja() {
		
		$this->db->update( "settings", $this->_indexedValues );
		
		if( $this->db->affected_rows() >= 0 )
		{
			echo 0;
		}
		else
		{
			echo ErrorCodes::DATABASE_ERROR;
		}
	}
	
	public function sacuvajMaticnuFirmu()
	{
		$copt = $this->db->count_all( "maticna_firma" );

		if( $copt == 1 )
		{
			$this->db->update( "maticna_firma", $this->_indexedValues );
		}
		else
		{
			$this->db->insert( "maticna_firma", $this->_indexedValues );
		}
		
		if( ! $this->db->_error_number() )
		{
			echo 0;
		}
		else
		{
			echo $this->db->_error_number();
		}
		
	}
	
	public function sacuvajMaticnuFirmucg()
	{
		$copt = $this->db->count_all( "maticna_firma_cg" );
	
		if( $copt == 1 )
		{
			$this->db->update( "maticna_firma_cg", $this->_indexedValues );
		}
		else
		{
			$this->db->insert( "maticna_firma_cg", $this->_indexedValues );
		}
	
		if( ! $this->db->_error_number() )
		{
			echo 0;
		}
		else
		{
			echo $this->db->_error_number();
		}
	
	}
	
}

/* End of file settings.php */
/* Location: ./application/controllers/settings.php */