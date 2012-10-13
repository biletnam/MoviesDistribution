<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/hooks/PreController.php';

class Zakljucnice extends PreController 
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('Zakljucnice_model', 'zm');
	}

	public function index()
	{
		$data = array();
		$data[ 'lang' ] = $this->lang->language;
		
		$this->load->view( "zakljucnice", $data );
	}
	
	public function read()
	{

		$ad = $this->input->post( 'advanced_search' ) == 'false' ? false : true;
		$this->_readSelect( $ad );
			
		if( $ad )
		{
			$this->_setAdvancedSearch();
			$this->db->group_by( 'zakljucnice.zakljucnica_id' );
		}
			
		$totalRows = $this->db->get('zakljucnice' )->num_rows();
		
		$this->_readSelect( $ad );
		
		if( $ad )
		{
			$this->_setAdvancedSearch();
			$this->db->group_by( 'zakljucnice.zakljucnica_id' );
		}
	
		$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
		
		$query = $this->db->get('zakljucnice', $this->_limit, $this->getRowsOffset( $this->_page, $this->_limit ) );
		
		$result = $query->result_array();
		
		$data = array();
		
		foreach( $result as $row )
		{
			$this->db->where( 'zakljucnica_id', $row[ 'zakljucnica_id' ] );
			$row[ 'broj_kopija_zakljucnice' ] = $this->db->count_all_results( 'kopije_zakljucnice' );
			
			array_push( $data, $row );
		}
		
		$this->dispatchResultXml( $data, $totalRows  );
	}
	
	protected function _readSelect( $search = null )
	{
		$this->db->select( 'zakljucnice.*, 
							komitenti.naziv_komitenta,
							komitenti.primenjen_porez_komitenta, 
							komitenti.raspodela_maticna_firma, 
							komitenti.raspodela_prikazivac, 
							komitenti.tip_raspodele_komitenta'
						);
						
		$this->db->join( 'komitenti', 'komitenti.komitent_id = zakljucnice.komitent_id', 'inner' );
		
		if( $search )
		{
			$this->db->join( 'kopije_zakljucnice', 'kopije_zakljucnice.zakljucnica_id = zakljucnice.zakljucnica_id', 'inner' );
			$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = kopije_zakljucnice.bioskop_id', 'inner' );
			$this->db->join( 'filmovi', 'filmovi.film_id = kopije_zakljucnice.film_id', 'inner' );
		}
	}
	
	protected function _setAdvancedSearch()
	{
		if( strlen( $this->_prefixedValues[ "zakljucnica_id" ] ) > 0 )
			$this->db->where('zakljucnica_id', $this->_prefixedValues[ "zakljucnica_id" ] );

		if( strlen( $this->_prefixedValues[ "naziv_komitenta" ] ) > 0 )
			$this->db->like('naziv_komitenta', $this->_prefixedValues[ "naziv_komitenta" ] );		
		
		if( strlen( $this->_prefixedValues[ "naziv_bioskopa" ] ) > 0 )
			$this->db->like('naziv_bioskopa', $this->_prefixedValues[ "naziv_bioskopa" ] );
		
		if( strlen( $this->_prefixedValues[ "naziv_filma" ] ) > 0 )
			$this->db->like('naziv_filma', $this->_prefixedValues[ "naziv_filma" ] );
				
		if( strlen( $this->_prefixedValues[ "datum_zakljucnice" ] ) > 0 )
			$this->db->where('datum_zakljucnice', $this->_prefixedValues[ "datum_zakljucnice" ] );

		if( strlen( $this->_prefixedValues[ "tip_raspodele" ] ) > 0 )
			$this->db->where('tip_raspodele', $this->_prefixedValues[ "tip_raspodele" ] );	
		
		if( strlen( $this->_prefixedValues[ "tip" ] ) > 0 )
			$this->db->where('tip', $this->_prefixedValues[ "tip" ] );
	}
	
	public function createZakljucnica()
	{
	   
	   $this->_prefixedValues[ "broj_dokumenta_zakljucnice" ] = $this->brojDokumenta( 'zakljucnice', 'zakljucnica_id', '860', $this->_prefixedValues[ "tip" ] );
	   $this->_prefixedValues[ "datum_unosa" ] = DOC_YEAR . substr( date("Y-m-d"), 4, 6 );
	   
	   $this->db->insert( 'zakljucnice', $this->_prefixedValues );
	   
	   if( $this->db->affected_rows() == 1 )
	   {
	   		$this->dispatchResultXml( array( "zakljucnica_id" => $this->db->insert_id() ), 1 );
	   }
	   else
	   {
	   		ErrorCodes::DATABASE_ERROR;
	   }
			  
	}
	
	/***
	public function updateZakljucnica( $zakljucnica_id )
	{
		
		if( isset(  $zakljucnica_id ) && $zakljucnica_id > 0 )
		{
			$this->db->where( 'zakljucnica_id', $zakljucnica_id );
			$this->db->update( 'zakljucnice', $this->_prefixedValues );
		} 
		
	   if( $this->db->_error_number() == 0 )
	   {
	   		$this->db->where( 'zakljucnica_id', $zakljucnica_id );
	   		$this->db->update( 'rokovnici', array( 
	   													"bioskop_id" => $this->_prefixedValues["bioskop_id"], 
	   													"komitent_id" => $this->_prefixedValues[ "komitent_id" ]
	   												) 
	   						);
	   		
	   		if($this->db->_error_number() == 0 )
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
	   		echo ErrorCodes::DATABASE_ERROR;
	   }
	}
	**/
	
	public function createKopijaZakljucnice( $zakljucnica_id )
	{

		if( isset(  $zakljucnica_id ) && $zakljucnica_id > 0 )
		{
		   
		   $this->_prefixedValues[ "zakljucnica_id" ]  = $zakljucnica_id; 
		  
		   $tip = $this->db->where( 'zakljucnica_id', $zakljucnica_id )->get( 'zakljucnice' )->row( 0 )->tip;
		   
		   $this->db->insert( 'kopije_zakljucnice', $this->_prefixedValues );
		   
		   $kopijeZakId = $this->db->insert_id();
		   
		   if( $this->db->affected_rows() == 1 )
		   {
		   	
			   $brojDokumenta = $this->brojDokumenta( "rokovnici", 'rokovnik_id', '861', $tip ); 

	   		   $this->db->insert( 'rokovnici', array( 
	   		   											"datum_unosa" => DOC_YEAR . substr( date("Y-m-d"), 4, 6 ),
		   												"zakljucnica_id" => $zakljucnica_id,
	   		   											"kopije_zakljucnice_id" => $kopijeZakId,
		   												"broj_dokumenta_rokovnika " => $brojDokumenta,  
		   												"komitent_id" => $this->input->post( "komitent_id" ),
		   												"bioskop_id" => $this->_prefixedValues[ "bioskop_id" ],
		   		 										"film_id" => $this->_prefixedValues[ "film_id" ],
		   												"kopija_id" => $this->_prefixedValues[ "kopija_id" ],
		   												"datum_kopije_od" => $this->_prefixedValues[ "datum_kopije_od" ],
		   												"datum_kopije_do" => $this->_prefixedValues[ "datum_kopije_do" ],
	   		   											"datum_prijema_kopije" => $this->_prefixedValues[ "datum_kopije_od" ],
														"datum_otpreme_kopije" => $this->getDateWithoutWeekend( $this->_prefixedValues[ "datum_kopije_do"], 24 * 60 * 60 ),
	   		   											"primenjen_porez_komitenta" => $this->input->post( "primenjen_porez_komitenta" ),
		   												"tip_raspodele" => $this->_prefixedValues[ "tip_raspodele" ],
		   												"raspodela_iznos" => $this->_prefixedValues[ "raspodela_iznos" ],
	   		   		                                    "tip" => $tip,	   		
		   												"raspodela_prikazivac" => $this->_prefixedValues[ "raspodela_prikazivac" ]
	   											   ) 
	   						   );
		   		
	   						   
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
		   		echo ErrorCodes::DATABASE_ERROR;
		   }
			
		}
		else
		{
			echo ErrorCodes::INVALID_INPUT;
		}
	   
	}
	
	
	public function deleteKopijaZakljucnice( $kopija_zak_id )
	{
		if( $kopija_zak_id )
		{
		
			$this->db->trans_start();
			
			$this->db->where( "kopije_zakljucnice_id", $kopija_zak_id );
			$this->db->delete( "kopije_zakljucnice" );
			
			$this->db->where( "kopije_zakljucnice_id", $kopija_zak_id );
			$this->db->delete( "rokovnici" );
			
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
			echo ErrorCodes::INVALID_INPUT;
		}
	}
	
	public function readKopijaEvents( $kopija_id )
	{
		$this->db->select( 'unix_timestamp( kopije_zakljucnice.datum_kopije_od )  AS start, 
							unix_timestamp( kopije_zakljucnice.datum_kopije_do )  AS end, 
							CONCAT( filmovi.naziv_filma, " - ", kopije_filma.serijski_broj_kopije ) AS title,
							CONCAT( "#", LPAD( CONV( kopije_zakljucnice.boja_kopije_zakljucnice, 10, 16 ), 6, "0" ) ) AS backgroundColor'
					 	  , FALSE );
		
		$this->db->join( 'filmovi', 'filmovi.film_id = kopije_zakljucnice.film_id', 'inner' );
		$this->db->join( 'kopije_filma', 'kopije_filma.kopija_id = kopije_zakljucnice.kopija_id', 'inner' );
	
		$this->db->where( "kopije_zakljucnice.kopija_id", $kopija_id );
		
		
		if( isset( $_GET[ "start"] ) && strlen( $_GET[ "start"] ) > 0 )
			$this->db->where( "unix_timestamp( kopije_zakljucnice.datum_kopije_do ) >=", $_GET[ "start"] );
		
			
		if( isset( $_GET[ "end"] ) && strlen( $_GET[ "end"] ) > 0 )
			$this->db->where( "unix_timestamp( kopije_zakljucnice.datum_kopije_od ) <=", $_GET[ "end"] );
			
			
		$query = $this->db->get('kopije_zakljucnice');
		
		$this->dispatchResultJson( $query->result_array() );
	}
	
	public function readZakljucniceKopije( $zakljucnica_id )
	{
		
		if( ! $zakljucnica_id || ! is_numeric( $zakljucnica_id ) )
		{
			echo ErrorCodes::INVALID_INPUT;
			exit();	
		}
		
		
		$this->db->select( 'kopije_zakljucnice.*,
							bioskopi.bioskop_id,
							bioskopi.naziv_bioskopa,
							filmovi.naziv_filma,
							kopije_filma.serijski_broj_kopije' 
						);
		
		$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = kopije_zakljucnice.bioskop_id', 'inner' );
		$this->db->join( 'filmovi', 'filmovi.film_id = kopije_zakljucnice.film_id', 'inner' );
		$this->db->join( 'kopije_filma', 'kopije_filma.kopija_id = kopije_zakljucnice.kopija_id', 'inner' );
	
		$this->db->where( "zakljucnica_id", $zakljucnica_id );
		
		$query = $this->db->get('kopije_zakljucnice');
		$this->dispatchResultXml( $query->result_array(), $query->num_rows()  );
	}
	
	public function updateZakljucniceKopije()
	{
		
		$id = @$_REQUEST[ 'id' ];
		
		if( isset(  $id ) && $id > 0 )
		{
			$this->db->where( 'kopije_zakljucnice_id', $id );
			$this->db->update( 'kopije_zakljucnice', $this->_prefixedValues );
		} 
		
	   if( ! $this->db->_error_number() )
	   {
	   	
			//( 24 * 60 * 60 )
	   		 $this->db->where( 'kopije_zakljucnice_id', $id );
	   		 $this->db->update( 'rokovnici',array( 
	   		 											"film_id" => $this->_prefixedValues[ "film_id" ],
	   		 											"kopija_id" => $this->_prefixedValues[ "kopija_id" ],
		   												"datum_kopije_od" => $this->_prefixedValues[ "datum_kopije_od" ],
		   												"datum_kopije_do" => $this->_prefixedValues[ "datum_kopije_do" ],
		   												"tip_raspodele" => $this->_prefixedValues[ "tip_raspodele" ],
		   												"raspodela_iznos" => $this->_prefixedValues[ "raspodela_iznos" ],
		   												"raspodela_prikazivac" => $this->_prefixedValues[ "raspodela_prikazivac" ],
	   		 											"bioskop_id"=> $this->_prefixedValues[ "bioskop_id" ], 
														"datum_prijema_kopije" => $this->_prefixedValues[ "datum_kopije_od" ],
														"datum_otpreme_kopije" => $this->getDateWithoutWeekend( $this->_prefixedValues[ "datum_kopije_do"], 24 * 60 * 60 )	   		 
	   											   ) 
	   						   );

	   		
	   		if( ! $this->db->_error_number() )
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
	   		ErrorCodes::DATABASE_ERROR;
	   }
	   
	}
	
	public function encodeIds()
	{
		if( isset( $_POST[ 'zakljucniceiIds' ] ) && strlen( $_POST[ 'zakljucniceiIds' ] ) > 0 )
		{
			echo base64_encode( $_POST[ 'zakljucniceiIds' ] );
		}
		else
		{
			echo ErrorCodes::INVALID_INPUT;
		}
	}
	
	public function prikaziZakljucnice()
	{
		$this->db->select( "zakljucnice.*,
							DATE_FORMAT( zakljucnice.datum_zakljucnice, '%d/%m/%Y' ) AS datum_zakljucnice_stampa, 
							komitenti.*", 
							false
		);
														
		$this->db->join( 'komitenti', 'komitenti.komitent_id = zakljucnice.komitent_id', 'inner' );
		
		$ids = $this->commaDelimitedToArray( $_GET['zakljucnice' ] );
		
		foreach( $ids as $v )
		{
			$this->db->or_where( "zakljucnica_id", $v );
		}
		
		$zakljucnice = $this->db->get( "zakljucnice" )->result_array();
		
		$m = $this->db->get( 'maticna_firma' )->result_array();
		
		$zakarr = array();
		
		foreach( $zakljucnice as $zakljucnica )
		{
			$this->db->select( "kopije_zakljucnice.*,
								DATE_FORMAT( kopije_zakljucnice.datum_kopije_od, '%d/%m/%Y' ) AS datum_kopije_od_stampa,
								DATE_FORMAT( kopije_zakljucnice.datum_kopije_do, '%d/%m/%Y' ) AS datum_kopije_do_stampa, 
								bioskopi.naziv_bioskopa, 
								filmovi.*,
								DATE_FORMAT( filmovi.start_filma, '%d/%m/%Y' ) AS start_filma_stampa,",
								false
			);
			
			$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = kopije_zakljucnice.bioskop_id', 'inner' );
			$this->db->join( 'filmovi', 'filmovi.film_id = kopije_zakljucnice.film_id', 'inner' );
			
			$this->db->where( 'zakljucnica_id', $zakljucnica['zakljucnica_id'] );
			
			$zakljucnica[ "referent" ] = self::$__session->userdata('ime_korisnika');
			$zakarr[] = $this->load->view( 'zakljucnica_strana', array( 'm' => $m[0], 
																		'rd' => $zakljucnica, 
																		'kopije_zak' => $this->db->get( 'kopije_zakljucnice' )->result_array() ), 
											true );
		}
		
		$zakljucnice_view_data = $this->load->view( 'pregled_zakljucnica', array( 'zakljucnice' => $zakarr  ), true );

		//echo $zakljucnice_view_data;
		//return;
		
		//==============================================================
		//==============================================================
		//==============================================================
		require_once 'pdf/mpdf.php';
		
		$mpdf = new mPDF('utf-8',    // mode - default ''
		'',    // format - A4, for example, default ''
		0,     // font size - default 0
		'',    // default font family
		15,    // margin_left
		15,    // margin right
		2,     // margin top
		2,    // margin bottom
		9,     // margin header
		9,     // margin footer
		'P');  // L - landscape, P - portrait 
		
		$mpdf->WriteHTML( $zakljucnice_view_data );		
		$mpdf->Output();
		
		//==============================================================
		//==============================================================
		//==============================================================
		//==============================================================
		//==============================================================		
	}
}

/* End of file zakljucnice.php */
/* Location: ./application/controllers/zakljucnice.php */
