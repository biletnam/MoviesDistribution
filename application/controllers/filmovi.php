<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/hooks/PreController.php';

class Filmovi extends PreController 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function index()
	{
		$zanrSelect = $this->getZanroviSelectOptions( true );
		
		$data = array( "zanrSelect" => $this->buildSelectElement( $zanrSelect, "zanr_filma_id", "naziv_zanra", true, "", SAVE_CELL_PREFIX_NAME . "zanr_filma", "pretraga_input" ) );
		$data[ 'lang' ] = $this->lang->language;
		
		$this->load->view( "filmovi", $data );		
	}
	
	public function read()
	{
	
		$ad = $this->input->post( 'advanced_search' ) == "true";
		$this->_readSelect( $ad );
			
		if( $ad )
		{
			$this->_setAdvancedSearch();
			$this->db->group_by( 'filmovi.film_id' );
		}
			
		$totalRows = $this->db->get('filmovi' )->num_rows();
		
		$this->_readSelect( $ad );
		
		if( $ad )
		{
			$this->_setAdvancedSearch();
			$this->db->group_by( 'filmovi.film_id' );
		}
		
		$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );

		$query = $this->db->get( 'filmovi', $this->_limit, $this->getRowsOffset( $this->_page, $this->_limit ) );
		
		$this->dispatchResultXml( $query->result_array(), $totalRows  );
	}
	
	protected function _readSelect( $search = null )
	{
		$this->db->select( 'filmovi.*, zanrovi_filma.naziv_zanra' );
		
		$this->db->join('zanrovi_filma', 'zanrovi_filma.zanr_filma_id = filmovi.zanr_filma', 'inner');
		
		if( $search )
		{
			$this->db->join( 'glumci_filma', 'glumci_filma.film_id = filmovi.film_id', 'left' );
			$this->db->join( 'glumci', 'glumci.glumac_id = glumci_filma.glumac_id', 'left' );
		}
		
		
	}
	
	public function suggestFromId()
	{
		
		$this->db->select( "film_id, naziv_filma", FALSE );
		$this->db->where( "film_id", @$_GET[ "term" ] );
		$this->db->limit( 30 );
		
		$this->dispatchResultAutoComplete(  $this->db->get( "filmovi")->result_array(), "film_id", "naziv_filma", "film_id" );
	}
	
	
	public function suggestFromName()
	{
		
		$this->db->select( "film_id, naziv_filma", FALSE );
		$this->db->like( "naziv_filma", @$_GET[ "term" ] );
		$this->db->limit( 30 );
		
		$this->dispatchResultAutoComplete(  $this->db->get( "filmovi")->result_array(), "film_id", "naziv_filma", "naziv_filma" );
	}
	
	public function suggestProducentFromName()
	{
	
		$this->db->select( "producent_filma, film_id ", FALSE );
		$this->db->like( "producent_filma", @$_GET[ "term" ] );
		$this->db->group_by( "producent_filma" );
		
		$this->db->limit( 30 );
	
		$this->dispatchResultAutoComplete(  $this->db->get( "filmovi")->result_array(), "film_id", "producent_filma", "producent_filma" );
	}
	
	protected function _setAdvancedSearch()
	{
		
		if( strlen( $this->_prefixedValues[ "broj_cinova_filma" ] ) > 0 )
			$this->db->where('broj_cinova_filma', $this->_prefixedValues[ "broj_cinova_filma" ] );		
		
		if( strlen( $this->_prefixedValues[ "film_id" ] ) > 0 )
			$this->db->where('film_id', $this->_prefixedValues[ "film_id" ] );
			
		if( strlen( $this->_prefixedValues[ "godina_filma" ] ) > 0 )
			$this->db->where('godina_filma', $this->_prefixedValues[ "godina_filma" ] );
		
		if( strlen( $this->_prefixedValues[ "zanr_filma" ] ) > 0 )
			$this->db->where('zanr_filma', $this->_prefixedValues[ "zanr_filma" ] );	
		
		if( strlen( $this->_prefixedValues[ "naziv_filma" ] ) > 0 )
			$this->db->like('naziv_filma', $this->_prefixedValues[ "naziv_filma" ] );
		
		if( strlen( $this->_prefixedValues[ "originalni_naziv_filma" ] ) > 0 )
			$this->db->like('originalni_naziv_filma', $this->_prefixedValues[ "originalni_naziv_filma" ] );
		
		if( strlen( $this->_prefixedValues[ "producent_filma" ] ) > 0 )
			$this->db->like('producent_filma', $this->_prefixedValues[ "producent_filma" ] );
		
		if( strlen( $this->_prefixedValues[ "studio_filma" ] ) > 0 )
			$this->db->like('studio_filma', $this->_prefixedValues[ "studio_filma" ] );
		
		if( strlen( $this->_prefixedValues[ "tehnika_filma" ] ) > 0 )
			$this->db->where('tehnika_filma', $this->_prefixedValues[ "tehnika_filma" ] );
		
		if( strlen( $this->_prefixedValues[ "trajanje_filma" ] ) > 0 )
			$this->db->like('trajanje_filma', $this->_prefixedValues[ "trajanje_filma" ] );
			
		if( strlen( $this->_prefixedValues[ "ime_glumca" ] ) > 0 )
			$this->db->like('ime_glumca', $this->_prefixedValues[ "ime_glumca" ] );
			
		if( strlen( $this->_prefixedValues[ "prezime_glumca" ] ) > 0 )
			$this->db->like('prezime_glumca', $this->_prefixedValues[ "prezime_glumca" ] );
			
		
	}
	
	public function readKopijeFilma( $film_id )
	{
		if( isset( $film_id ) && is_numeric( $film_id ) && $film_id > 0 )
		{
			$this->db->select( "kopije_filma.*");

			$this->db->where('film_id', $film_id );
			
			$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
			
			$query = $this->db->get('kopije_filma');
			
			$this->dispatchResultXml( $query->result_array(), $query->num_rows()  );
		}
	}
	
	public function getKopijeFilmaSelectOptions( $film_id, $return = false )
	{
		$this->db->select( 'kopija_id, serijski_broj_kopije ,oznaka_kopije_filma' );
		$this->db->where( 'film_id', $film_id );
		
		$query = $this->db->get('kopije_filma');
		
		$d =  $query->result_array();
		 
		if( $return )
		{
			return $d; 
		} 
		else
		{
			$this->dispatchXml( $this->buildSelectElementRamo( $d, "kopija_id", "serijski_broj_kopije","oznaka_kopije_filma" ) );
		}
	}
	
	public function readZanroviFilma()
	{
		$this->db->select( '*' );
		
		$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
		
		$query = $this->db->get('zanrovi_filma');
		
		
		
		$this->dispatchResultXml( $query->result_array(), $query->num_rows()  );
	}
	
	
	public function getZanroviSelectOptions( $return = false )
	{
		$this->db->select( '*' );
		
		$query = $this->db->get('zanrovi_filma');
		
		$d =  $query->result_array();
		 
		if( $return )
		{
			return $d; 
		}
		else
		{
			$this->dispatchXml( $this->buildSelectElement( $d, "zanr_filma_id", "naziv_zanra" ) );
		}
	}
	
	public function updateFilm()
	{
		$id = @$_REQUEST[ 'id' ];
		
		if( isset(  $id ) && $id > 0 )
		{
			$this->db->where( 'film_id', $id );
			$this->db->update( 'filmovi', $this->_prefixedValues );
		} 
	}
	
	public function createFilm()
	{
	   $this->db->insert( 'filmovi', $this->_prefixedValues );
	   
	   if( $this->db->affected_rows() == 1 )
	   {
	   		echo 0;
	   }
	   else
	   {
	   		ErrorCodes::DATABASE_ERROR;
	   }
		   
	}
	
	public function createFilmKopija( $film_id = NULL )
	{

	    if( ! $film_id )
	    	$film_id = $this->uri->segment( 3, 0 );
	 
	   if( $film_id ) $this->_prefixedValues[ "film_id" ] = $film_id;
	    	
	   if( is_numeric( $this->_prefixedValues[ "film_id" ]  ) && $this->_prefixedValues[ "film_id" ] > 0 )
	   {
	   	   $this->db->trans_start();
	   	   	   
		    $this->db->insert( 'kopije_filma', $this->_prefixedValues );
		    
		    $this->db->where( "film_id", $this->_prefixedValues[ "film_id" ] );
		    $this->db->update( 'filmovi', array( "broj_kopija" => $this->input->post( "broj_kopija" ) + 1 ) );
		    
	   		$this->db->trans_complete();
			
			if( $this->db->trans_status() === FALSE )
			{
			    echo ErrorCodes::DATABASE_ERROR;
			}
			else
			{
				echo 0;
			}
	   	}
	   	else
	   	{
	   		ErrorCodes::INVALID_INPUT;
	   	}
	}
	
	public function updateFilmKopijaColumn()
	{
		$id = @$_POST['id'];
		
		if( isset(  $id ) && $id > 0 )
		{
			$this->db->where( 'kopija_id', $id );
			$this->db->update( 'kopije_filma', $this->_prefixedValues );
				
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
	
	
	public function createFilmZanr()
	{

	   $this->db->insert( 'zanrovi_filma', $this->_prefixedValues );
	   
	   if( $this->db->affected_rows() == 1 )
	   {
	   		echo 0;
	   }
	   else
	   {
	   		ErrorCodes::DATABASE_ERROR;
	   }
	}
	
	public function updateZanroviColumn()
	{
		$id = @$_REQUEST[ 'id' ];

		if( isset(  $id ) && $id > 0 )
		{
			$this->db->where( 'zanr_filma_id', $id );
			$this->db->update( 'zanrovi_filma', $this->_prefixedValues );
		} 
		
	}
	
	public function dodajGlumca()
	{
		
	   $ac = $this->db->select( "*" )->where( array( "film_id"=> @$_POST[ "film_id"], "glumac_id" => @$_POST[ "glumac_id" ] ) )->get("glumci_filma")->num_rows();
	    
	   if( ! $this->db->_error_number() && $ac == 0 )
	   {
	  	   $this->db->insert( 'glumci_filma', array( "film_id" => @$_POST[ "film_id"],  "glumac_id" => @$_POST[ "glumac_id" ] ) );
	   
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
	
	public function getGlumciFilma( $film_id )
	{
		if( isset( $film_id ) && is_numeric( $film_id ) && $film_id > 0 )
		{
			$this->db->select( "glumci_filma.*, glumci.ime_glumca, glumci.prezime_glumca" );
			$this->db->join('glumci', 'glumci.glumac_id = glumci_filma.glumac_id', 'inner');
			
			$this->db->where('glumci_filma.film_id', $film_id );
			
			$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
			
			$query = $this->db->get( 'glumci_filma' );
			
			$this->dispatchResultXml( $query->result_array(), $query->num_rows()  );
		}
	}
}

/* End of file filmovi.php */
/* Location: ./application/controllers/filmovi.php */