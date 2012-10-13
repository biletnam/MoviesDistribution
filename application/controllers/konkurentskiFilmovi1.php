<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/hooks/PreController.php';

class KonkurentskiFilmovi extends PreController 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function index()
	{
		$this->load->view( "konkurentski_filmovi" );
		
	}
	
	public function read()
	{

		$this->_setAdvancedSearch();
		
		$totalRows = $this->db->get( 'konkurentski_filmovi' )->num_rows();
		
		$this->db->select( 'konkurentski_filmovi.*' );
		
		$this->_setAdvancedSearch();
		
		$this->db->order_by( $this->_sort_col_name, @$this->_sort_order_name );
		
		$query = $this->db->get('konkurentski_filmovi', $this->_limit, $this->getRowsOffset( $this->_page, $this->_limit ) );
	
		$this->dispatchResultXml( $query->result_array(), $totalRows  );
		
	}
	
	protected function _setAdvancedSearch()
	{	
		if( strlen( $this->_prefixedValues[ "id_konkurentskog_filma" ] ) > 0 )
			$this->db->where('id_konkurentskog_filma', $this->_prefixedValues[ "id_konkurentskog_filma" ] );		
		
		
		if( strlen( $this->_prefixedValues[ "naziv_konkurentskog_filma" ] ) > 0 )
			$this->db->like('naziv_konkurentskog_filma', $this->_prefixedValues[ "naziv_konkurentskog_filma" ] );
			
		if( strlen( $this->_prefixedValues[ "originalni_naziv_konkurentskog_filma" ] ) > 0 )
			$this->db->like('originalni_naziv_konkurentskog_filma', $this->_prefixedValues[ "originalni_naziv_konkurentskog_filma" ] );
	}
	
	public function createFilm()
	{
	   $this->db->insert( 'konkurentski_filmovi', $this->_prefixedValues );
	   
	   if( $this->db->affected_rows() == 1 )
	   {
	   		echo 0;
	   }
	   else
	   {
	   		ErrorCodes::DATABASE_ERROR;
	   }
		   
	}
	
	public function updateFilm()
	{
		$id = @$_REQUEST[ 'id' ];
		
		if( isset(  $id ) && $id > 0 )
		{
			$this->db->where( 'id_konkurentskog_filma', $id );
			$this->db->update( 'konkurentski_filmovi', $this->_prefixedValues );
		} 
	}
}

/* End of file konkurentskiFilmovi.php */
/* Location: ./application/controllers/konkurentskiFilmovi.php */