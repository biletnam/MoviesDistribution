<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/hooks/PreController.php';

class Fakture extends PreController 
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
		
		$this->load->view( "fakture", $data );
	}

		
	public function read( $storno = NULL )
	{
		$this->_selectFakture( $storno );
		$this->_setAdvancedSearch( $storno );
		
		$tb = "fakture";
		if( $storno ) $tb = "fakture_storno";
		
		$totalRows = $this->db->get( $tb )->num_rows();
		
		$this->_selectFakture( $storno );
		$this->_setAdvancedSearch( $storno );
					
		$this->db->order_by( $this->_sort_col_name, $this->_sort_order_name );
		
		$query = $this->db->get( $tb, $this->_limit, $this->getRowsOffset( $this->_page, $this->_limit ) );
		$this->dispatchResultXml( $query->result_array(), $totalRows  );	
		
	}

	public function readUplateFakture( $faktura_id = NULL )
	{
		if( isset( $faktura_id ) && is_numeric( $faktura_id ) && $faktura_id > 0 )
		{
			$this->db->select( "fakture_uplate.*");
			
			$this->db->where('faktura_id', $faktura_id );
			
			$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
			
			$query = $this->db->get('fakture_uplate');
			
			$this->dispatchResultXml( $query->result_array(), $query->num_rows()  );
		}
		else
		{
			echo ErrorCodes::INVALID_INPUT;
		}
	}
	
	public function createUplatuFakture( $faktura_id = NULL )
	{
		if( ! $faktura_id )
	    	$faktura_id = $this->uri->segment( 3, 0 );
	 
	   if( $faktura_id ) $this->_prefixedValues[ "faktura_id" ] = $faktura_id;
	    	
	   if( is_numeric( $this->_prefixedValues[ "faktura_id" ]  ) && $this->_prefixedValues[ "faktura_id" ] > 0 )
	   {	   
	   	   $this->db->trans_start();
	   		
		   $this->db->insert( 'fakture_uplate', $this->_prefixedValues );
		   
		   $this->db->where( 'faktura_id', $this->_prefixedValues[ "faktura_id" ]);
		   $f = $this->db->get( 'fakture' )->row();
		   
		   $uplaceno_total = ( $this->input->post( "uplaceno_total" ) + $this->_prefixedValues[ "vrednost_uplate_fakture" ] );
		   $za_placanje = $f->ukupan_prihod - $uplaceno_total;
		   
		   $this->db->where( 'faktura_id', $this->_prefixedValues[ "faktura_id" ] );
		   $this->db->update( 'fakture', array( "uplate_total" => $uplaceno_total,
		   										 "za_placanje" => $za_placanje
		   									  ) 
		   					);
		    					
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
	
	public function deleteUplatuFakture()
	{
	   $faktura_id = $this->input->post( "faktura_id" );
	   $uplata_id = $this->input->post( "uplata_id" );
	   
	   //$vrednost_uplate = $this->input->post( "vrednost_uplate" );
	   //$uplaceno_total = $this->input->post( "uplaceno_total" );
	   
	   
	   $this->db->where( 'uplata_id', $uplata_id );
	   $uplata = $this->db->get( 'fakture_uplate' )->row();
	   
	   
	   
	   $this->db->where( 'faktura_id', $faktura_id );
	   $f = $this->db->get( 'fakture' )->row();
	   
	   $uplate_total = $f->uplate_total - $uplata->vrednost_uplate_fakture;
	   $za_placanje = $f->ukupan_prihod - $uplate_total;
	   
	   if( $faktura_id && $uplata_id )
	   {
		   $this->db->trans_start();
		   		
		   $this->db->where( 'uplata_id', $uplata_id );
		   $this->db->delete( 'fakture_uplate' );
		   
		   $this->db->where( 'faktura_id', $faktura_id );
		   $this->db->update( 'fakture', array( "uplate_total" => $uplate_total,
		   										 "za_placanje" => $za_placanje 
		   									  ) 
		   					);
		    					
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
	
	public function create()
	{
	
		$this->db->select( 'tip' );
		$this->db->where( 'z_gledanost_id', $this->_prefixedValues[ 'z_gledanost_id' ] );
		$query = $this->db->get( 'zvanicna_gledanost' );
		$sa_porezom_naocare = $this->input->post( 'sa_porezom_naocare' );
		
		if( ! $query )
		{
		    echo ErrorCodes::DATABASE_ERROR;
		    return;
		}
		else
		{
			$tip = $query->row( 0 )->tip;
		}
		
		if( $tip == 'cg' ) 
		{
			$ppk = 7;
			
			$pf = round( ( $ppk * 100 ) / ( $ppk + 100 ), 4 );
			$procenat_pdv = 17;
			
		}
		else
		{
			
			$ppk = 8;
			
			$pf = round( ( $ppk * 100 ) / ( $ppk + 100 ), 4 );
		    $procenat_pdv = 18;
		}
		// 0, 8 18 %
		//$ppk = $this->_prefixedValues['pdv'];

		
		$vrednost_preracunatog_poreza_karte = 0;
		$vrednost_preracunatog_poreza_naocare = 0;
			
		$neto_karte = 0;
		$neto_naocare = 0;
		
		$osnovica = 0;		
		$ukupan_pdv = 0;
		$vrednost = 0;
		$uplaceno = 0;
		$ukupan_prihod = 0;
		$za_placanje = 0;
		
		$pdv_film = 0;
		$pdv_naocare = 0;
		
		$za_distributera_film = 0;
		$za_distributera_naocare = 0;
		
		$stavke_fakture = array();
		
		$vrednost_preracunatog_poreza_filma = 0;
		$neto_filma = 0;
		
		
		// ako je film 3D i tip raspodele je
		if( $this->_prefixedValues[ 'tehnika_kopije_filma' ] == 2 && $this->_prefixedValues[ 'tip_raspodele' ] == 3 )
		{
			
			if( $this->_indexedValues[ 'valuta_fakture' ] == 1 )
			{
				$ukupan_prihod_filma = $this->_prefixedValues[ 'ukupan_prihod_karte' ];
				$ukupan_prihod_naocara = $this->_prefixedValues[ 'ukupan_prihod_naocare' ];
				
				$vrednost_preracunatog_poreza_karte = $ukupan_prihod_filma * $pf / 100;
				$vrednost_preracunatog_poreza_naocare = $ukupan_prihod_naocara *  $pf / 100;
			}
			else
			{
				$ukupan_prihod_filma = $this->_prefixedValues[ 'ukupan_prihod_karte_eur' ];
				$ukupan_prihod_naocara = $this->_prefixedValues[ 'ukupan_prihod_naocare_eur' ];
				
				$procenat_pdv = 0;
				if ($tip=='cg') {
					$procenat_pdv = 17;
					$vrednost_preracunatog_poreza_karte = $ukupan_prihod_filma * $pf / 100;
					$vrednost_preracunatog_poreza_naocare = $ukupan_prihod_naocara *  $pf / 100;
				}
			}
		
			$neto_karte = $ukupan_prihod_filma - $vrednost_preracunatog_poreza_karte;
			
			if( $sa_porezom_naocare == true )
			{
				$neto_naocare = $ukupan_prihod_naocara - $vrednost_preracunatog_poreza_naocare;
			}
			else
			{
				$neto_naocare = $ukupan_prihod_naocara;
			}
			
			
			$za_distributera_film = $neto_karte * $this->_prefixedValues[ 'raspodela_iznos' ] / 100;
			$za_distributera_naocare = $neto_naocare * $this->_indexedValues[ 'raspodela_naocare' ] / 100;

			
			if( $this->_indexedValues[ 'valuta_fakture' ] == 1 )
			{
				$pdv_film = round( ( $za_distributera_film * $procenat_pdv / 100 ), 2 );
				$pdv_naocare = round( ( $za_distributera_naocare * $procenat_pdv / 100 ), 2 );
			}
			
			if( $tip == 'cg') 
			{
				$pdv_film = round( ( $za_distributera_film * $procenat_pdv / 100 ), 2 );
				$pdv_naocare = round( ( $za_distributera_naocare * $procenat_pdv / 100 ), 2 );
			}
			
			$stavke_fakture[] = array(  'redni_broj_stavke' => 1,
									    'z_gledanost_id' => $this->_prefixedValues[ 'z_gledanost_id' ],
										'artikal_id' => 1,
										'broj_gledalaca' => $this->_prefixedValues[ 'ukupno_gledalaca' ],
										'prihod' => $ukupan_prihod_filma,
										'tip_raspodele' => $this->_prefixedValues[ 'tip_raspodele' ],
										'procenat' =>  $this->_prefixedValues[ 'raspodela_iznos' ],
										'za_raspodelu' => $ukupan_prihod_filma,
										'za_distributera' => $za_distributera_film, 
										'primenjen_porez_komitenta' => $procenat_pdv,
										'iznos_pdv' => $pdv_film
									);
									
			$stavke_fakture[] = array(  'redni_broj_stavke' => 2,
									    'z_gledanost_id' => $this->_prefixedValues[ 'z_gledanost_id' ],
										'artikal_id' => 2,
										'broj_prodatih_naocara' => $this->_prefixedValues[ 'ukupno_prodato_naocara' ],
										'prihod' => $ukupan_prihod_naocara,
										'tip_raspodele' => $this->_prefixedValues[ 'tip_raspodele' ],
										'procenat' =>  $this->_indexedValues[ 'raspodela_naocare' ],
										'za_raspodelu' => $ukupan_prihod_naocara,
										'za_distributera' => $za_distributera_naocare,
										'primenjen_porez_komitenta' => $procenat_pdv,
										'iznos_pdv' => $pdv_naocare
									);						
									
		}
		else
		{
			
			
			if( $this->_indexedValues[ 'valuta_fakture' ] == 1 )
			{
				$ukupan_prihod_filma = $this->_prefixedValues[ 'ukupan_prihod' ];	
				$vrednost_preracunatog_poreza_filma = $ukupan_prihod_filma * $pf / 100;	
			}
			else
			{
				$ukupan_prihod_filma = $this->_prefixedValues[ 'ukupan_prihod_eur' ];
				if ($tip=='cg') {
					$vrednost_preracunatog_poreza_filma = $ukupan_prihod_filma * $pf / 100;
				}
				
				
			}
			 
			$za_raspodelu = $this->_prefixedValues[ 'raspodela_iznos' ];
			$procenat = $za_raspodelu;
			
			$neto_filma = $ukupan_prihod_filma - $vrednost_preracunatog_poreza_filma;
			
			
			switch( $this->_prefixedValues[ 'tip_raspodele' ] )
			{
				// min garancija
				case 1:
					$min_gar = $this->_prefixedValues[ 'raspodela_iznos' ];
					
					/***
						0				  100	
						-------------------
							     50MIN	   UK 			
					***/

					if( $neto_filma <= ( $min_gar * 2 )  )
					{
						$za_distributera_film = $min_gar;
					}
					else if( $neto_filma  > ( $min_gar * 2 ) + 1 )
					{
						$za_distributera_film = $neto_filma / 2;
					}
					
					$procenat = 0;
				break;
				
				// ugovoreni iznos
				case 2:
					$za_distributera_film =  $this->_prefixedValues[ 'raspodela_iznos' ];
					$procenat = 0;
				break;
				
				// raspodela
				case 3;
					$za_distributera_film = $neto_filma * $this->_prefixedValues[ 'raspodela_iznos' ] / 100;
					$za_raspodelu = $neto_filma;
				break;
			}	

			// ako je valuta rsd onda racunaj pdv ako nije onda je pdv 0
			if( $this->_indexedValues[ 'valuta_fakture' ] == 1 )
			{
				$pdv_film = round( ( $za_distributera_film * $procenat_pdv / 100 ), 2 );
			}	
			else
			{
				$pdv_film = 0;
				$procenat_pdv = 0;
				
				if( $tip == 'cg' ) 
				{
					$procenat_pdv = 17;
					$pdv_film = round( ( $za_distributera_film * $procenat_pdv / 100 ), 2 );
				}
			}		
			
			
			$stavke_fakture[] = array(  'redni_broj_stavke' => 1,
									    'z_gledanost_id' => $this->_prefixedValues[ 'z_gledanost_id' ],
										'artikal_id' => 1,
										'broj_gledalaca' => $this->_prefixedValues[ 'ukupno_gledalaca' ],
										'prihod' => $ukupan_prihod_filma,
										'tip_raspodele' => $this->_prefixedValues[ 'tip_raspodele' ],
										'procenat' =>  $procenat,
										'za_raspodelu' => $za_raspodelu,
										'za_distributera' => $za_distributera_film, 
										'primenjen_porez_komitenta' => $procenat_pdv,
										'iznos_pdv' => $pdv_film
									 );
		}

		
		$osnovica += $za_distributera_film;
		$osnovica += $za_distributera_naocare;
		
		$osnovica = round( $osnovica, 2 );
			
	    if( $this->_indexedValues[ 'valuta_fakture' ] == 1 )
		{
			$ukupan_pdv += $pdv_film;
			$ukupan_pdv += $pdv_naocare;
		
			$ukupno = $osnovica + $ukupan_pdv;
		}
		else
		{
			$ukupno = $osnovica;
			
			if ($tip=='cg') {
				$ukupan_pdv += $pdv_film;
				$ukupan_pdv += $pdv_naocare;
				
				$ukupno = $osnovica + $ukupan_pdv;
			}
		}

		$za_placanje = $ukupno;
		
		
		//for getdate to work default_time_zone need to be set. look at ./index.php
		$rpf = getdate( strtotime( $this->_indexedValues[ 'datum_prometa_fakture' ] ) + ( $this->_indexedValues[ 'rok_placanja_fakture' ] * 24 * 60 * 60 ) );	
			
		$rok_placanja = $rpf[ "year" ] . "-" . $rpf[ "mon" ] . "-" . $rpf[ "mday" ]; 
		
		$datum_unosa = getdate();
		
		$this->db->trans_start();
		
		$this->db->where( "YEAR(datum_unosa_fakture) = ", DOC_YEAR );
	
		$broj_dokumenta_result = $this->db->select_max( "redni_broj_u_godini", "MAX_ID" )->get( "fakture" )->result_array();
		
		//echo $this->db->last_query();
		
		$broj_faktura = $broj_dokumenta_result[0][ "MAX_ID" ];
		
		if( ! $broj_faktura ) $broj_faktura = 0;
	
		$prefiks = '';
		
		$broj_faktura += 1;
		
		$doc = '';
		
		if( $tip == 'cg' ) 
		{
			$this->db->where( 'tip', $tip );
			$broj = $this->db->get( 'fakture' )->num_rows();
			
			if( $broj > 0 ) 
			{
				$broj_faktura = $broj + 1;
			}
			else 
			{
				$broj_faktura = 1;
			}
			
			$doc = 'cgifb';
			$prefiks= "cg";
		}
		else
		{
			$doc = 'ifb';
			$sufiks = '';
		}
		
		 
	
		$fdata = array( 
						'z_gledanost_id' => $this->_prefixedValues[ 'z_gledanost_id' ],
						'storno' => 0,
						'doc' => $doc,
						'redni_broj_u_godini' => $broj_faktura,
						'broj_dokumenta_fakture' => $prefiks.'ifb-' . $broj_faktura,
						'ver' => 0.9,
						'idb' => $broj_faktura,
						'datum_unosa_fakture' => DOC_YEAR . substr( date("Y-m-d"), 4, 6 ),
						'vdate' => $this->_prefixedValues[ 'zadnji_dan_gledanosti' ],
						'from' => '',
						'to' => $this->_prefixedValues[ 'naziv_komitenta' ],
						'rok' => $this->_indexedValues[ 'rok_placanja_fakture' ],
						'valuta_fakture' => $this->_indexedValues[ 'valuta_fakture' ],
						'osnovica' => $osnovica,
						'raspodela_naocare' => $this->_indexedValues[ 'raspodela_naocare' ],
						'ukupan_pdv' => $ukupan_pdv,
						'ukupan_prihod' => $ukupno,
						'uplaceno' => 0,
						'za_placanje' => $za_placanje,
						'stari_broj' => 0,
						'datum_prometa' => $this->_indexedValues[ 'datum_prometa_fakture' ],
						'rok_placanja' => $rok_placanja,				        
						'poziv_na_broj' =>  '97-14-ifb-' . $broj_faktura,
				        'tip' => $tip,
						'sa_porezom_naocare' => ( $sa_porezom_naocare == true ) ? 1 : 0
				 	);


		if( $this->_indexedValues[ 'valuta_fakture' ] == 2 )
		{
			$fdata[ 'napomena' ] = 'OSLOBODJEN OD PLACANJA PDVa PO CLANU 12 ZAKONA O PDVu. MESTO PROMETA DOBARA:';
		}
		
		
		$fdi = $this->db->insert( 'fakture', $fdata );
		
		$fg_id = $this->db->insert_id();
		
		foreach ( $stavke_fakture as $fs )
		{
			$fs[ 'faktura_id' ] = $fg_id;
			$this->db->insert( 'fakture_stavke', $fs );
		}	
		
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
	
	protected function _calculateRSDFakturu()
	{
		
	}
	
	protected function _calculateEURFakturu()
	{
		
	}
	
	public function updateUplateFakture()
	{
		$id = @$_REQUEST[ 'id' ];
		
		if( isset(  $id ) && $id > 0 )
		{
			$this->db->where( 'uplata_id', $id );
			$this->db->update( 'fakture_uplate', $this->_prefixedValues );
			
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
	   		ErrorCodes::INVALID_INPUT;
	   	} 
	}
	
	public function updateFakture()
	{
		$id = @$_REQUEST[ 'id' ];
		
		if( isset(  $id ) && $id > 0 )
		{
			$this->db->where( 'faktura_id', $id );
			$this->db->update( 'fakture', $this->_prefixedValues );
			
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
	   		ErrorCodes::INVALID_INPUT;
	   	} 
	}
	
	public function updateFaktureStorno()
	{
		$id = @$_REQUEST[ 'id' ];
		
		if( isset(  $id ) && $id > 0 )
		{
			$this->db->where( 'faktura_id', $id );
			$this->db->update( 'fakture_storno', $this->_prefixedValues );
			
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
	   		ErrorCodes::INVALID_INPUT;
	   	} 
	}
	
	public function updateRaspodelaNaocareFakture()
	{
		$this->db->select( 'tip, sa_porezom_naocare' );
		$this->db->where( 'faktura_id', $this->input->post( 'faktura_id' ) );
		
		$query = $this->db->get( 'fakture' );
		$sa_porezom_naocare = 1;
		
		if( ! $query )
		{
		    echo ErrorCodes::DATABASE_ERROR;
		    return;
		}
		else
		{
			$row = $query->row( 0 );
			$sa_porezom_naocare = $row->sa_porezom_naocare;
			$tip = $row->tip;
		}
		
		
		$raspodela_naocare = $this->input->post( "raspodela_naocare" );
		$faktura_id = $this->input->post( "faktura_id" );
		
		
		$this->db->select( "fakture.*, zvanicna_gledanost.*, rokovnici.*" );
		$this->db->join( 'zvanicna_gledanost', 'zvanicna_gledanost.z_gledanost_id = fakture.z_gledanost_id', 'inner' );
		$this->db->join( 'rokovnici', 'rokovnici.rokovnik_id = zvanicna_gledanost.rokovnik_id', 'inner' );
		
		$this->db->where( "faktura_id", $faktura_id );
		$fd = $this->db->get( "fakture" )->result_array();
		$fd = $fd[0];
		
		$this->db->where( "faktura_id", $faktura_id );
		$this->db->where( "artikal_id", 1 );
		
		$fs = $this->db->get( "fakture_stavke" )->result_array();
		$fs = $fs[0];
		
	
		//$ppk = $fd['pdv'];
		if( $tip == 'cg' ) 
		{
			$ppk = 7;				
			$pf = round( ( $ppk * 100 ) / ( $ppk + 100 ), 4 );
		}
		else
		{
			$ppk = 8;
			$pf = round( ( $ppk * 100 ) / ( $ppk + 100 ), 4 );
		}
		
		$vrednost_preracunatog_poreza_karte = 0;
		$vrednost_preracunatog_poreza_naocare = 0;
			
		$ukupan_prihod_filma = 0;
		$ukupan_prihod_naocara = 0;
			
		$za_distributera_film = 0;
		$za_distributera_naocare = 0;
		
		$pdv_film = 0;
		$pdv_naocare = 0;
		
		$osnovica = 0;
		$ukupno = 0;
		$za_placanje = 0;
		
		$ukupan_pdv = 0;
		
		if( $tip == 'cg' ) 
		{
			$procenat_pdv = 17;
		}
		else
		{
			$procenat_pdv = 18;
		}
		
		if( $fd[ 'valuta_fakture' ] == 1 )
		{
			$ukupan_prihod_filma = $fd[ 'ukupan_prihod_karte' ];
			$ukupan_prihod_naocara = $fd[ 'ukupan_prihod_naocare' ];
			
			$vrednost_preracunatog_poreza_karte = $ukupan_prihod_filma * $pf / 100;
			$vrednost_preracunatog_poreza_naocare = $ukupan_prihod_naocara *  $pf / 100;
		}
		else
		{
			$ukupan_prihod_filma = $fd[ 'ukupan_prihod_karte_eur' ];
			$ukupan_prihod_naocara = $fd[ 'ukupan_prihod_naocare_eur' ];
			
			if( $tip == 'cg' ) 
			{
				$vrednost_preracunatog_poreza_karte = $ukupan_prihod_filma * $pf / 100;
				$vrednost_preracunatog_poreza_naocare = $ukupan_prihod_naocara *  $pf / 100;
			}
		}
		
		$neto_karte = $ukupan_prihod_filma - $vrednost_preracunatog_poreza_karte;
		
		if( $sa_porezom_naocare == 1 )
		{
			$neto_naocare = $ukupan_prihod_naocara - $vrednost_preracunatog_poreza_naocare;
		}
		else
		{
			$neto_naocare = $ukupan_prihod_naocara;
		}
		
		$za_distributera_film = $neto_karte * $fd[ 'raspodela_iznos' ] / 100;
		$za_distributera_naocare = $neto_naocare * $raspodela_naocare / 100;

		$pdv_film = round( ( $za_distributera_film * $procenat_pdv / 100 ), 2 );
		$pdv_naocare = round( ( $za_distributera_naocare * $procenat_pdv / 100 ), 2 );
			
		
		$osnovica += $za_distributera_film;
		$osnovica += $za_distributera_naocare;

		$osnovica = round( $osnovica, 2 );
			
	    if( $fd[ 'valuta_fakture' ] == 1 )
		{
			$ukupan_pdv += $pdv_film;
			$ukupan_pdv += $pdv_naocare;
		
			$ukupno = $osnovica + $ukupan_pdv;
		}
		else
		{
			$ukupno = $osnovica;
			
			if( $tip == 'cg' ) 
			{
				$ukupan_pdv += $pdv_film;
				$ukupan_pdv += $pdv_naocare;
				
				$ukupno = $osnovica + $ukupan_pdv;
			}
		}

		$za_placanje = $ukupno;
		
		
		$stavka_naocare = array(  
									'prihod' => $ukupan_prihod_naocara,
									'procenat' =>  $raspodela_naocare,
									'za_raspodelu' => $ukupan_prihod_naocara,
									'za_distributera' => $za_distributera_naocare,
									'iznos_pdv' => $pdv_naocare
								  );	
	
		$fdata = array( 
						'osnovica' => $osnovica,
						'raspodela_naocare' => $raspodela_naocare,
						'ukupan_pdv' => $ukupan_pdv,
						'ukupan_prihod' => $ukupno,
						'za_placanje' => $za_placanje,
				 	);
		
		$this->db->trans_start();
		
		$this->db->where( "faktura_id", $faktura_id );
		$this->db->update( "fakture", $fdata );
		
		
		$this->db->where( "faktura_id", $faktura_id );
		$this->db->where( "artikal_id", 2 );
		
		$this->db->update( "fakture_stavke", $stavka_naocare );
		
		
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
	
	public function stornirajFakturu( $faktura_id )
	{
		
		if( $faktura_id )
		{
			
			$this->db->trans_start();
			
			$this->db->where( "faktura_id", $faktura_id );
			$fd = $this->db->get( "fakture" )->result_array();
			
			if( $fd )
			{
				
				$fd[ 0 ][ "osnovica" ] *= -1;
				$fd[ 0 ][ "ukupan_pdv" ] *= -1;
				$fd[ 0 ][ "ukupan_prihod" ] *= -1;
				$fd[ 0 ][ "za_placanje" ] *= -1;
				$fd[ 0 ][ "datum_unosa_fakture" ] = date( 'Y-m-d' );
				
				$this->db->where( "faktura_id", $faktura_id );
				$fsd = $this->db->get( "fakture_stavke" )->result_array();
				
				$this->db->insert( "fakture_storno", $fd[0] );
				
				foreach( $fsd as $v )
				{
					$v[ "broj_gledalaca" ] *= -1;
					$v[ "broj_prodatih_naocara" ] *= -1;
					$v[ "za_distributera" ] *= -1;
					$v[ "iznos_pdv" ] *= -1;
					$v[ "za_raspodelu" ] *= -1;
					$v[ "prihod" ] *= -1;
					
					$this->db->insert( "fakture_stavke_storno", $v );
				}
				
				$this->db->where( "faktura_id", $faktura_id );
				$this->db->update( 'fakture', array( "stornirana" => 1 ) );
			
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
				echo ErrorCodes::DATABASE_ERROR;
			}
		}
		else
		{
			echo ErrorCodes::INVALID_INPUT;
		}
	}
	
	public function povratiStornoFakture( $faktura_id )
	{
		if( $faktura_id )
		{
			
			$this->db->trans_start();
			
			$this->db->where( "faktura_id", $faktura_id );
			$this->db->update( 'fakture', array( "stornirana" => 0 ) );
				
			$this->db->where( "faktura_id", $faktura_id );
			$this->db->delete( "fakture_storno" );
			
			$this->db->where( "faktura_id", $faktura_id );
			$this->db->delete( "fakture_stavke_storno" );
			
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
	
	
	public function prikaziFakture( $storno = NULL )
	{
		
		$this->_selectFakture( $storno );
		
		$ids = $this->commaDelimitedToArray( $_GET['fakture' ] );
		
		foreach( $ids as $v )
		{
			$this->db->or_where( "faktura_id", $v );
		}
		
		$tb = "fakture";
		$tbs = "fakture_stavke";
		
		if( $storno ) 
		{
			$tb = "fakture_storno";
			$tbs = "fakture_stavke_storno";
		}
		
		 
		
		$fakture = $this->db->get( $tb )->result_array();
		
		$m = $this->db->get('maticna_firma')->result_array();
		$n = $this->db->get('maticna_firma_cg')->result_array();
		$fakture_arr = array();
		
		foreach( $fakture as $faktura )
		{
			$this->db->select( "$tbs.*, artikli.*" );
			
			$this->db->join( 'artikli', "artikli.artikal_id = $tbs.artikal_id", 'inner' );
			
			$this->db->where( 'faktura_id', $faktura['faktura_id'] );
			
			
			$faktura[ "referent" ] = self::$__session->userdata('ime_korisnika'); 
			
			$fakture_arr[] = $this->load->view( 'faktura_strana', array( 'm' => $m[0], 
																	'rd' => $faktura, 
																	'fakture_stavke' => $this->db->get( $tbs )->result_array(),
																	'storno' => $storno,
					                                                'n' => $n[0]), 
											true );
		}
		
		$view_data = $this->load->view( 'pregled_faktura', array( 'fakture' => $fakture_arr  ), true );
		
		
		//echo $view_data;
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
		
		$mpdf->WriteHTML( $view_data );
		
		$mpdf->Output(); 
	}
	
	public function encodeIds()
	{
		if( isset( $_POST[ 'faktureIds' ] ) && strlen( $_POST[ 'faktureIds' ] ) > 0 )
		{
			echo base64_encode( $_POST[ 'faktureIds' ] );
		}
		else
		{
			echo ErrorCodes::INVALID_INPUT;
		}
	}
	
	public function exportFakture( $faktura_id, $storno = NULL )
	{
		if( $faktura_id )
		{
			
			$tb = "fakture";
			$tbs = "fakture_stavke";
			
			if( $storno ) 
			{
				$tb = "fakture_storno";
				$tbs = "fakture_stavke_storno";
			}
			
			$this->_selectFakture( $storno );
			
			$this->db->where( "faktura_id", $faktura_id );
			
			$f = $this->db->get( $tb )->result_array();
			$f = $f[0];
			
			
			
			$this->db->where( "faktura_id", $faktura_id );
			
			$this->db->select( "$tbs.*, artikli.*" );
			$this->db->join( 'artikli', "artikli.artikal_id = $tbs.artikal_id", 'inner' );
			
			$artikli = $this->db->get( $tbs )->result_array();
			
			if( $f && $artikli )
			{
				
				$du = date( 'd.m.Y', strtotime( $f[ "datum_unosa_fakture" ] ) );
				$vd = date( 'd.m.Y', strtotime( $f[ "vdate" ] ) );
				$dpod = date( 'd.m.Y', strtotime( $f[ "datum_prometa" ] ) );
				
				
				$fs  = "DOC" . chr( 179 ) . "FAK\r\nVER" . chr( 179 ) . "0.9\r\nID";

				if( $storno )
				{
					$fs .= chr( 179 ) . $f[ "idb" ] . "s";
				}
				else
				{
					$fs .= chr( 179 ) . $f[ "idb" ];
				}
				
				 
				$fs .= "\r\nDATE" . chr( 179 ) . $du . "\r\nVDAT" . chr( 179 ) . $vd . "\r\nDPO" . chr( 179 ) . $dpod . "\r\n";
				
				
				
				$fs .= "IDN" . chr( 179 ) . $f[ "idn" ] . "\r\nNI" . chr( 179 ) . $f[ "nacin_isporuke" ] . "\r\nREG" . chr( 179 );

				if( $f[ "valuta_fakture"] == 1 )
				{
					$fs .="ifb-\r\n";
				}
				else
				{
					$fs .="ifdb-\r\n";
					$fs .= "DEV". chr( 179 ) ."EUR". chr( 179 ) . $f[ "eur" ] . "\r\n";
				}
				
				
				
				$fs .= "FROM" . chr( 179 ) . $f[ "naziv_maticne_firme" ] . chr( 179 ) . $f[ "pib_maticne_firme" ] . chr( 179 ) . $f[ "adresa_maticne_firme" ] . chr( 179 ) . $f[ "mesto_maticne_firme" ] . "\r\n";
				$fs .= "TO" . chr( 179 ) . $f[ "naziv_komitenta" ] . chr( 179 ) . $f[ "pib_komitenta" ] . chr( 179 ) . $f[ "adresa_komitenta" ] . chr( 179 ) . $f[ "zip_komitenta" ] . chr( 179 ) . $f[ "mesto_komitenta" ] . chr( 179 ) . $f[ "tel1_komitenta" ] . "\r\n";

				foreach( $artikli as $art )
				{
					$fs .= "USL" . chr( 179 ) . $art[ 'naziv_artikla' ] . chr( 179 ) . "1" . chr( 179 ) . $art[ "za_distributera" ] . chr( 179 ) . "0" . chr( 179 ) . $art[ 'primenjen_porez_komitenta' ] . chr( 179 ) . "\r\n";				
				}	
				
				$fs .= "POR" . chr( 179 ) . "Porez na dodatu vrednost" . chr( 179 ) . $f[ 'primenjen_porez_komitenta' ] . chr( 179 ) . $f[ 'osnovica' ] . chr( 179 ) . $f[ 'ukupan_pdv'] . chr( 179 ) . "\r\n";
				$fs .= "TOT" . chr( 179 ) . ( $f[ 'osnovica' ] + $f[ 'ukupan_pdv'] ) . "\r\n";
 
				$ime = $f[ "broj_dokumenta_fakture" ];
				
				if( $storno )
					$ime .= "-s";
					
				$ime .= '.fak';
				
				$this->db->where( "faktura_id", $faktura_id );
				$this->db->update( $tb, array( "exportovana" => 1 ) );
				
				
				echo $fs;
				
				
				header( "Cache-Control: public" );
	    		header( "Content-Description: File Transfer" );
	    		header( "Content-Disposition: attachment; filename=$ime" );
	   	 		header( "Content-Type: application/octet-stream" );
	    		header( "Content-Transfer-Encoding: binary" );
			}
			else
			{
				echo "Database error! Please try again or contact administrator.";
			}
			
			
		}
		else
		{
			//echo ErrorCodes::INVALID_INPUT;
		}
	}
	
	
	protected function _selectFakture( $storno = NULL )
	{
		$tb = "fakture";
		if( $storno ) $tb = "fakture_storno";
		
		$this->db->select( "$tb.*, 
							DATE_FORMAT( $tb.datum_unosa_fakture, '%d/%m/%Y' ) AS datum_unosa_fakture_stampa,
							DATE_FORMAT( $tb.vdate, '%d/%m/%Y' ) AS vdate_stampa,
							DATE_FORMAT( $tb.rok_placanja, '%d/%m/%Y' ) AS rok_placanja_stampa,
							DATE_FORMAT( $tb.datum_prometa, '%d/%m/%Y' ) AS datum_prometa_stampa,
							FORMAT( $tb.osnovica, 4 ) AS osnovica_stampa,
							FORMAT( $tb.ukupan_pdv, 4 ) AS ukupan_pdv_stampa,
							FORMAT( $tb.za_placanje, 4 ) AS za_placanje_stampa,
							FORMAT( $tb.ukupan_prihod, 4 ) AS ukupan_prihod_stampa,
							komitenti.*,
							filmovi.*,
							bioskopi.*,
							maticna_firma.*,
							zvanicna_gledanost.stornirana AS z_gledanost_storno,
							zvanicna_gledanost.broj_dokumenta_z_gledanosti,
							zvanicna_gledanost.pdv_procenat_rsd,
							DATE_FORMAT( zvanicna_gledanost.datum_z_gledanost_od, '%d/%m/%Y' ) AS datum_z_gledanost_od,
							DATE_FORMAT( zvanicna_gledanost.datum_z_gledanost_do, '%d/%m/%Y' ) AS datum_z_gledanost_do,
							rokovnici.datum_kopije_od,
							rokovnici.datum_kopije_do,
							rokovnici.tip_raspodele,
							kopije_filma.*,
							rokovnici.primenjen_porez_komitenta,
							kursna_lista.eur", false );

		
		$this->db->join( 'zvanicna_gledanost', "zvanicna_gledanost.z_gledanost_id = $tb.z_gledanost_id", 'inner' );
		$this->db->join( 'komitenti', 'komitenti.komitent_id = zvanicna_gledanost.komitent_id', 'inner' );
		$this->db->join( 'filmovi', 'filmovi.film_id = zvanicna_gledanost.film_id', 'inner' );
		$this->db->join( 'rokovnici', 'rokovnici.rokovnik_id = zvanicna_gledanost.rokovnik_id', 'inner' );
		$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
		$this->db->join( 'maticna_firma', "maticna_firma.maticna_id = $tb.maticna_id", 'inner' );
		$this->db->join( 'kopije_filma', 'kopije_filma.kopija_id = rokovnici.kopija_id', 'inner' );
		$this->db->join( 'kursna_lista', 'kursna_lista.datum_kursa = zvanicna_gledanost.zadnji_dan_gledanosti', 'left' );
	}
	
	
	protected function _setAdvancedSearch( $storno = NULL )
	{
		$tb = "fakture";
		if( $storno ) $tb = "fakture_storno";
			
		if( strlen( @$this->_prefixedValues[ "redni_broj_u_godini" ] ) > 0 )
			$this->db->where( "$tb.redni_broj_u_godini", @$this->_prefixedValues[ "redni_broj_u_godini" ] );		
		
		if( strlen( @$this->_prefixedValues[ "datum_unosa_fakture" ] ) > 0 )
			$this->db->where( "$tb.datum_unosa_fakture", @$this->_prefixedValues[ "datum_unosa_fakture" ] );
			
		if( strlen( @$this->_prefixedValues[ "naziv_komitenta" ] ) > 0 )
			$this->db->like('komitenti.naziv_komitenta', @$this->_prefixedValues[ "naziv_komitenta" ] );
		
		if( strlen( @$this->_prefixedValues[ "naziv_bioskopa" ] ) > 0 )
			$this->db->like('bioskopi.naziv_bioskopa', @$this->_prefixedValues[ "naziv_bioskopa" ] );	
		
		if( strlen( @$this->_prefixedValues[ "naziv_filma" ] ) > 0 )
			$this->db->like('filmovi.naziv_filma', @$this->_prefixedValues[ "naziv_filma" ] );

		if( ! $storno && strlen( @$this->_prefixedValues[ "stornirana" ] ) > 0 )
			$this->db->where("$tb.stornirana", @$this->_prefixedValues[ "stornirana" ] );
		
		if( strlen( @$this->_prefixedValues[ "tip" ] ) > 0 )
			$this->db->where('fakture.tip', @$this->_prefixedValues[ "tip" ] );
	
		if( strlen( $this->_prefixedValues[ "tehnika_kopije_filma" ] ) > 0 )
			$this->db->where('tehnika_kopije_filma', $this->_prefixedValues[ "tehnika_kopije_filma" ] );	
					
	}	
}

		/***
		 * 
		 
		$days_num = $rr[ $i ][ "daysNum" ];
		$first_date = $rr[ $i ][ "firstDate" ];
		for( $td = 0; $td < $days_num; $td++ )
		{
			//ensure first date is intact.
			$td == 0 ? $datum_gledanosti = $first_date
				:
			$datum_gledanosti = ( ($td + 1) * 23 * 60 * 60 ) + $first_date;// 23 because 24 will move it to next day 00:00 in the morning
			
			//for getdate to work default_time_zone need to be set. look at ./index.php
			$d_gl_ar = getdate( $datum_gledanosti );	
			
			$datum_gledanosti_str = $d_gl_ar[ "year" ] . "-" . $d_gl_ar[ "mon" ] . "-" . $d_gl_ar[ "mday" ]; 
			
			$this->db->where( "rokovnik_id", $rr[ $i ][ "rokovnik_id" ] );
			$this->db->where( "datum_gledanosti", $datum_gledanosti_str );
				
			$gledanost_d = $this->db->get( "gledanost" )->result_array();
			
			if( $gledanost_d )
			{
				$this->db->where( "gledanost_id", $gledanost_d[ "gledanost_id" ] );
				$termini_d = $this->db->get( "gledanost_termini" )->result_array();
			}
			
			$tabele .= $this->load->view( "gledanost_tabela", 
										   array( "g_data" => $gledanost_d,
										   		  "t_data" => $termini_d,
										   		  "tehnika" => $rr[ $i ][ "tehnika_kopije_filma" ],
										   		  "datum" => $datum_gledanosti_str,
										   		  "serijski_broj_kopije" => $rr[ $i ][ "serijski_broj_kopije" ],
										   		  "naziv_filma" => $rr[ $i ][ "naziv_filma" ],
										   		  "naziv_bioskopa" => $rr[ $i ][ "naziv_bioskopa" ],
										   		  "naziv_komitenta" => $rr[ $i ][ "naziv_komitenta" ]
										   ), 
										   true 
										 );
		}
		
		*
		***/		



/* End of file fakture.php */
/* Location: ./application/controllers/fakture.php */