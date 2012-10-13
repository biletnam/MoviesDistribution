<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/hooks/PreController.php';

class KursnaLista extends PreController 
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
		
		$this->load->view( "kursna_lista", $data );
	}
	
	public function read()
	{

		$this->_setAdvancedSearch();
		
		$totalRows = $this->db->get( 'kursna_lista' )->num_rows();
		
		$this->db->select( 'kursna_lista.*' );
		
		$this->_setAdvancedSearch();
		
		$this->db->order_by( $this->_sort_col_name, @$this->_sort_order_name );
		
		$query = $this->db->get('kursna_lista', $this->_limit, $this->getRowsOffset( $this->_page, $this->_limit ) );
	
		$this->dispatchResultXml( $query->result_array(), $totalRows  );
		
	}
	
	protected function _setAdvancedSearch()
	{	
		if( strlen( $this->_prefixedValues[ "datum_kursa" ] ) > 0 )
			$this->db->where('datum_kursa', $this->_prefixedValues[ "datum_kursa" ] );
			
		if( strlen( $this->_prefixedValues[ "rsd" ] ) > 0 )
			$this->db->where('rsd', $this->_prefixedValues[ "rsd" ] );
			
		if( strlen( $this->_prefixedValues[ "km" ] ) > 0 )
			$this->db->where('km', $this->_prefixedValues[ "km" ] );
			
		if( strlen( $this->_prefixedValues[ "eur" ] ) > 0 )
			$this->db->where('eur', $this->_prefixedValues[ "eur" ] );
	}
	
	public function createKurs()
	{
		
	   $ac = $this->db->select( "*" )->where( array( "datum_kursa"=> $this->_prefixedValues[ "datum_kursa" ] ) )->get("kursna_lista")->num_rows();
	    
	   if( ! $this->db->_error_number()  )
	   {
	   	
	   		if( $ac == 0 )
	   		{
	   		   $this->_prefixedValues[ "rsd" ] = 1;
	   		   $this->_prefixedValues[ "faktor_rsd" ] = 1;
	   		   $this->_prefixedValues[ "faktor_eur" ] = 1 / $this->_prefixedValues[ "eur" ];
	   		   $this->_prefixedValues[ "faktor_km" ]  = 1 / $this->_prefixedValues[ "km" ];
	   		  
			   $this->db->insert( 'kursna_lista', $this->_prefixedValues );
			   
			   if( $this->db->affected_rows() == 1 )
			   {
			   		echo 0;
			   }
			   else
			   {
			   		ErrorCodes::DATABASE_ERROR;
			   }
	   	   }
		   else
		   {
	   			echo ErrorCodes::ALREADY_EXISTS;
	   	   }
	   }
	   else
	   {
	   		ErrorCodes::DATABASE_ERROR;
	   }
	   
		   
	}
	
	public function updateKurs()
	{
		foreach( $this->_prefixedValues as $k => $v )
		{
			if( $k == "datum_kursa" )
			{
				echo ErrorCodes::INVALID_INPUT;
				die();
			} 
			 
		}
		
		
		$id = @$_REQUEST[ 'id' ];
		
		if( isset(  $id ) && $id > 0 )
		{
			 $this->_prefixedValues[ "faktor_rsd" ] = 1;
			 
			 if( @$this->_prefixedValues[ "eur" ] > 0 )
	   		 	$this->_prefixedValues[ "faktor_eur" ] = 1 / $this->_prefixedValues[ "eur" ];
	   		 	
	   		 if( @$this->_prefixedValues[ "km" ] > 0 )	
	   		    $this->_prefixedValues[ "faktor_km" ]  = 1 / $this->_prefixedValues[ "km" ];
	   		   
			$this->db->where( 'kurs_id', $id );
			$this->db->update( 'kursna_lista', $this->_prefixedValues );
			
		   if( $this->db->affected_rows() == 1 )
		   {
		   		echo 0;
		   }
		   else
		   {
		   		ErrorCodes::DATABASE_ERROR;
		   }
	   
		}
		else 
		{
			echo ErrorCodes::INVALID_INPUT;
		} 
	}
}

/* End of file konkurentskiFilmovi.php */
/* Location: ./application/controllers/konkurentskiFilmovi.php */