<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/hooks/PreController.php';

class Komitenti extends PreController 
{
	private $__suggestCols = array( "komitent_id", "naziv_komitenta" );
	
	private $__suggestOptCols = array( "primenjen_porez_komitenta", 
										"raspodela_maticna_firma", 
										"raspodela_prikazivac", 
										"tip_raspodele_komitenta" );
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function index()
	{
		$data = array();
		$data[ 'lang' ] = $this->lang->language;
		
		$this->load->view( "komitenti", $data );
	}
	
	public function createKomitent()
	{
	   $this->db->insert( 'komitenti', $this->_prefixedValues );
	   
	   if( $this->db->affected_rows() == 1 )
	   {
	   		echo 0;
	   }
	   else
	   {
	   		ErrorCodes::DATABASE_ERROR;
	   }
		  
	}
	
	public function createKomitentBioskop( $komitent_id = NULL )
	{
	 	if( ! $komitent_id )
	    	$komitent_id = $this->uri->segment( 3, 0 );
	 
	   if( $komitent_id ) $this->_prefixedValues[ "komitent_id" ] = $komitent_id;

	   
	   if( is_numeric( $this->_prefixedValues[ "komitent_id" ]  ) && $this->_prefixedValues[ "komitent_id" ] > 0 )
	   {	   
		   $this->db->insert( 'bioskopi', $this->_prefixedValues );
		   
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
	   		ErrorCodes::INVALID_INPUT;
	   	}
	}
	
	public function createBioskopAlias( $komitent_id = NULL )
	{
		if( ! $komitent_id )
	    	$komitent_id = $this->uri->segment( 3, 0 );

	   

	     
	   if( is_numeric( $this->_prefixedValues[ "komitent_id" ]  ) && $this->_prefixedValues[ "komitent_id" ] > 0 )
	   {
		   	$ac = 
		    
		    $this->db->select( "*" )->where( array( 
		    						
		    	"bioskop_alias_name"=> $this->_prefixedValues[ "bioskop_alias_name" ],
		    	"komitent_id" => $this->_prefixedValues[ "komitent_id" ]
		    				
		     ) )->get("bioskop_aliases")->num_rows();
	     
		     
		   	 if( ! $this->db->_error_number() && $ac == 0 )
		   	 {	   
				   $this->db->insert( 'bioskop_aliases', $this->_prefixedValues );
				   
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
	   		ErrorCodes::INVALID_INPUT;
	   	}	
	    	
	}
	
	public function updateBioskop()
	{
		$id = @$_REQUEST[ 'id' ];
		
		if( isset(  $id ) && $id > 0 )
		{
			$this->db->where( 'bioskop_id', $id );
			$this->db->update( 'bioskopi', $this->_prefixedValues );
			
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
	   		ErrorCodes::INVALID_INPUT;
	   	} 
	}
	
	public function updateBioskopAlias()
	{
		$id = @$_REQUEST[ 'id' ];
		
		if( isset(  $id ) && $id > 0 )
		{
			$this->db->where( 'bioskop_alias_id', $id );
			$this->db->update( 'bioskop_aliases', $this->_prefixedValues );
			
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
	   		ErrorCodes::INVALID_INPUT;
	   	} 
	}
	
	public function readBioskopiKomitenta( $komitent_id )
	{
		if( isset( $komitent_id ) && is_numeric( $komitent_id ) && $komitent_id > 0 )
		{
			$this->db->select( "bioskopi.*, bioskop_aliases.bioskop_alias_name");
			
			$this->db->join( 'bioskop_aliases', 'bioskop_aliases.bioskop_alias_id = bioskopi.alias_bioskopa', 'left');
			
			$this->db->where('bioskopi.komitent_id', $komitent_id );
			
			$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
			
			$query = $this->db->get('bioskopi');
			
			$this->dispatchResultXml( $query->result_array(), $query->num_rows()  );
		}
		else
		{
			echo ErrorCodes::INVALID_INPUT;
		}
		
	}
	
	public function readBioskopAliases( $komitent_id )
	{
		if( isset( $komitent_id ) && is_numeric( $komitent_id ) && $komitent_id > 0 )
		{
			$this->db->select( "bioskop_aliases.*");
			
			$this->db->where('komitent_id', $komitent_id );
			
			$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
			
			$query = $this->db->get('bioskop_aliases');
			
			$this->dispatchResultXml( $query->result_array(), $query->num_rows()  );
		}
		else
		{
			echo ErrorCodes::INVALID_INPUT;
		}
	}
	
	public function getBioskopiAliasesSelectOptions( $komitent_id, $return = false )
	{
		$this->db->select( '*' );
		
		$this->db->where( "komitent_id", $komitent_id );
		
		$query = $this->db->get('bioskop_aliases');
		
		$d =  $query->result_array();
		 
		if( $return )
		{
			return $d; 
		}
		else
		{
			$this->dispatchXml( $this->buildSelectElement( $d, "bioskop_alias_id", "bioskop_alias_name" ) );
		}
	}
	
	
	public function suggestFromId()
	{
		
		$this->db->select(  array_merge( $this->__suggestCols, $this->__suggestOptCols ) , FALSE );
		
		$this->db->where( "komitent_id", @$_GET[ "term" ] );
		$this->db->limit( 30 );
		
		$this->dispatchResultAutoComplete(  $this->db->get( "komitenti")->result_array(), 
											"komitent_id", 
											"naziv_komitenta", 
											"komitent_id",
											$this->__suggestOptCols
										 );
	}
	
	
	public function suggestFromName()
	{
		
		$this->db->select( array_merge( $this->__suggestCols, $this->__suggestOptCols ), FALSE );
		$this->db->like( "naziv_komitenta", @$_GET[ "term" ] );
		$this->db->limit( 30 );
		
		$this->dispatchResultAutoComplete(  $this->db->get( "komitenti")->result_array(), 
											"komitent_id", 
											"naziv_komitenta", 
											"naziv_komitenta",
											$this->__suggestOptCols
										 );
	}
	
	public function getBioskopiSelectOptions( $komitent_id, $return = false )
	{
		$this->db->select( 'bioskop_id, naziv_bioskopa' );
		
		$this->db->where( "komitent_id", $komitent_id );
		
		$query = $this->db->get('bioskopi');
		
		$d =  $query->result_array();
		 
		if( $return )
		{
			return $d; 
		}
		else
		{
			$this->dispatchXml( $this->buildSelectElement( $d, "bioskop_id", "naziv_bioskopa" )  );
		}
	}
	
	public function updateKomitent()
	{
		$id = @$_REQUEST[ 'id' ];
		
		if( isset(  $id ) && $id > 0 )
		{
			$this->db->where( 'komitent_id', $id );
			$this->db->update( 'komitenti', $this->_prefixedValues );
			
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
	   		ErrorCodes::INVALID_INPUT;
	   	}
		
	}
	
	public function read()
	{
		
		$this->_setAdvancedSearch();
		
		$totalRows = $this->db->get('komitenti' )->num_rows();
		
		$this->db->select( 'komitenti.*' );

		$this->_setAdvancedSearch();	

		$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
		
		$query = $this->db->get( 'komitenti', $this->_limit, $this->getRowsOffset( $this->_page, $this->_limit ) );
		
		$this->dispatchResultXml( $query->result_array(), $totalRows  );
	}
	
	protected function _setAdvancedSearch()
	{
		
		if( strlen( $this->_prefixedValues[ "komitent_id" ] ) > 0 )
			$this->db->where('komitent_id', $this->_prefixedValues[ "komitent_id" ] );		
		
		if( strlen( $this->_prefixedValues[ "naziv_komitenta" ] ) > 0 )
			$this->db->like('naziv_komitenta', $this->_prefixedValues[ "naziv_komitenta" ] );
			
		if( strlen( $this->_prefixedValues[ "adresa_komitenta" ] ) > 0 )
			$this->db->like('adresa_komitenta', $this->_prefixedValues[ "adresa_komitenta" ] );
		
		if( strlen( $this->_prefixedValues[ "p_broj" ] ) > 0 )
			$this->db->like('p_broj', $this->_prefixedValues[ "p_broj" ] );	
		
		if( strlen( $this->_prefixedValues[ "mesto_komitenta" ] ) > 0 )
			$this->db->like('mesto_komitenta', $this->_prefixedValues[ "mesto_komitenta" ] );
		
		if( strlen( $this->_prefixedValues[ "tel1_komitenta" ] ) > 0 )
			$this->db->like('tel1_komitenta', $this->_prefixedValues[ "tel1_komitenta" ] );
		
		if( strlen( $this->_prefixedValues[ "tel2_komitenta" ] ) > 0 )
			$this->db->like('tel2_komitenta', $this->_prefixedValues[ "tel2_komitenta" ] );
		
		if( strlen( $this->_prefixedValues[ "email_komitenta" ] ) > 0 )
			$this->db->like('email_komitenta', $this->_prefixedValues[ "email_komitenta" ] );
		
		if( strlen( $this->_prefixedValues[ "pib_komitenta" ] ) > 0 )
			$this->db->like('pib_komitenta', $this->_prefixedValues[ "pib_komitenta" ] );
		
		if( strlen( $this->_prefixedValues[ "gledanost_komitenta" ] ) > 0 )
			$this->db->where('gledanost_komitenta', $this->_prefixedValues[ "gledanost_komitenta" ] );
		
		if( strlen( $this->_prefixedValues[ "kontakt_osoba_komitenta" ] ) > 0 )
			$this->db->like('kontakt_osoba_komitenta', $this->_prefixedValues[ "kontakt_osoba_komitenta" ] );
			
		if( strlen( $this->_prefixedValues[ "primenjen_porez_komitenta" ] ) > 0 )
			$this->db->like('primenjen_porez_komitenta', $this->_prefixedValues[ "primenjen_porez_komitenta" ] );

		if( strlen( $this->_prefixedValues[ "sifra_delatnosti_komitenta" ] ) > 0 )
			$this->db->like('sifra_delatnosti_komitenta', $this->_prefixedValues[ "sifra_delatnosti_komitenta" ] );	
		
	}
	
	
	
}

/* End of file komitenti.php */
/* Location: ./application/controllers/komitenti.php */