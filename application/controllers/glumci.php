<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/hooks/PreController.php';

class Glumci extends PreController 
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
		$this->load->view( "glumci", $data );
		
	}
	
	public function read()
	{
		$this->_setAdvancedSearch();
		
		$totalRows = $this->db->get('glumci' )->num_rows();
		
		$this->db->select( 'glumci.*' );
		
		$this->_setAdvancedSearch();
		
		$this->db->order_by( $this->_sort_col_name, $this->_sort_order_name );
		
		$query = $this->db->get('glumci', $this->_limit, $this->getRowsOffset( $this->_page, $this->_limit ) );
		$this->dispatchResultXml( $query->result_array(), $totalRows  );
		
	}
	
	protected function _setAdvancedSearch()
	{	
		if( strlen( $this->_prefixedValues[ "glumac_id" ] ) > 0 )
			$this->db->where('glumac_id', $this->_prefixedValues[ "glumac_id" ] );		
		
		
		if( strlen( $this->_prefixedValues[ "ime_glumca" ] ) > 0 )
			$this->db->like('ime_glumca', $this->_prefixedValues[ "ime_glumca" ] );
			
		if( strlen( $this->_prefixedValues[ "prezime_glumca" ] ) > 0 )
			$this->db->like('prezime_glumca', $this->_prefixedValues[ "prezime_glumca" ] );
			
		if( strlen( $this->_prefixedValues[ "link_glumca" ] ) > 0 )
			$this->db->like('link_glumca', $this->_prefixedValues[ "link_glumca" ] );	
			
	}
	
	public function createGlumac()
	{	
	   $this->db->insert( 'glumci', $this->_prefixedValues );
	   
	   if( $this->db->affected_rows() == 1 )
	   {
	   		echo 0;
	   }
	   else
	   {
	   		ErrorCodes::DATABASE_ERROR;
	   }
		   
	}
	
	public function updateGlumca()
	{
		$id = @$_REQUEST[ 'id' ];
		
		if( isset(  $id ) && $id > 0 )
		{
			$this->db->where( 'glumac_id', $id );
			$this->db->update( 'glumci', $this->_prefixedValues );
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
	
	public function suggest()
	{
		
		$this->db->select( "glumac_id, CONCAT( glumci.ime_glumca, ' ',glumci.prezime_glumca ) AS punoIme", FALSE );
		$this->db->or_like( "glumci.ime_glumca", @$_GET[ "term" ] );
		$this->db->or_like( "glumci.prezime_glumca", @$_GET[ "term" ] );
		$this->db->limit( 30 );
		
		$this->dispatchResultAutoComplete(  $this->db->get( "glumci")->result_array(), "glumac_id", "punoIme", "punoIme" );
	}
}

/* End of file glumci.php */
/* Location: ./application/controllers/glumci.php */