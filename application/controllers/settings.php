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
		$data[ 'lang' ] = $this->lang->language;
		
		$this->load->view( "settings", $data );
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
	
	public function readMaticnaFirmacg()
	{
		$this->dispatchResultXml( $this->db->get('maticna_firma_cg' )->result_array(), 1 );
	}
	
	public function readMaticnaFirma()
	{
		$this->dispatchResultXml( $this->db->get('maticna_firma' )->result_array(), 1 );
	}
	
}

/* End of file settings.php */
/* Location: ./application/controllers/settings.php */