<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/hooks/PreController.php';
require_once 'application/vos/ZvanicnaGledanostVo.php';
require_once 'application/vos/RokovnikVo.php';

class ZvanicnaGledanost extends PreController 
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
		
		$this->load->view( "zvanicna_gledanost", $data );
	}

		
	public function read()
	{
		$ad = $this->input->post( 'advanced_search' ) == "true";
		$this->_readSelect();
			
		if( $ad )
		{
			$this->_setAdvancedSearch();
			$this->db->group_by( 'zvanicna_gledanost.z_gledanost_id' );
		}
			
		$totalRows = $this->db->get('zvanicna_gledanost' )->num_rows();
		
		$this->_readSelect( $ad );
		
		if( $ad )
		{
			$this->_setAdvancedSearch();
			$this->db->group_by( 'zvanicna_gledanost.z_gledanost_id' );
		}
		
		$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
		
		$query = $this->db->get('zvanicna_gledanost', $this->_limit, $this->getRowsOffset( $this->_page, $this->_limit ) );
		$this->dispatchResultXml( $query->result_array(), $totalRows  );	
	}
	
	public function create()
	{
		$data =  $this->_getGledanostData();
		
		if( $data )
		{
			$this->db->trans_start();
			
			$zdi = $this->db->insert( 'zvanicna_gledanost', $data[ 'gledanost_insert_data' ] );
			
			$zg_id = $this->db->insert_id();
			
			foreach( $data[ 'gledanosti_id'] as $v )
			{
				$this->db->insert( 'z_gledanost_detalji', array( 'z_gledanost_id' => $zg_id, 'gledanost_id' => $v ) );
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
	}
	
	public function encodeIds()
	{
		if( isset( $_POST[ 'zGledanostIds' ] ) && strlen( $_POST[ 'zGledanostIds' ] ) > 0 )
		{
			echo base64_encode( $_POST[ 'zGledanostIds' ] );
		}
		else
		{
			echo ErrorCodes::INVALID_INPUT;
		}
	}
	
	public function prikaziGledanost()
	{
		$this->db->select( "zvanicna_gledanost.*,
							DATE_FORMAT( zvanicna_gledanost.datum_unosa, '%d/%m/%Y' ) AS datum_unosa_stampa,	 
							komitenti.*,
							filmovi.*,
							bioskopi.*,
							kopije_filma.*,
							rokovnici.primenjen_porez_komitenta AS POREZ_KOMITENTA,
							rokovnici.status_kopije,
							rokovnici.tip_raspodele,
							rokovnici.raspodela_iznos",
							false
						 );

		$this->db->join( 'komitenti', 'komitenti.komitent_id = zvanicna_gledanost.komitent_id', 'inner' );
		$this->db->join( 'filmovi', 'filmovi.film_id = zvanicna_gledanost.film_id', 'inner' );
		$this->db->join( 'rokovnici', 'rokovnici.rokovnik_id = zvanicna_gledanost.rokovnik_id', 'inner' );
		$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
		$this->db->join( 'kopije_filma', 'kopije_filma.kopija_id = rokovnici.kopija_id', 'inner' );
		
		$ids = $this->commaDelimitedToArray( $_GET['gledanost' ] );
		
		foreach( $ids as $v )
		{
			$this->db->or_where( "z_gledanost_id", $v );
		}
		
		$zgledanosti = $this->db->get( "zvanicna_gledanost" )->result_array();
		
		$m = $this->db->get('maticna_firma')->result_array();
		
		$gledanosti_arr = array();
		$gledanost_data = array();
		$gled_ids = array();
		
		
		$gtmp = array();
		
		$gt = array();
		
		foreach( $zgledanosti as $gled )
		{			
			$this->db->select( "z_gledanost_detalji.gledanost_id" );
			$this->db->where( 'z_gledanost_id', $gled['z_gledanost_id'] );
			$gled_ids = $this->db->get( "z_gledanost_detalji" )->result_array(); 
			$gledanost_data = array();
			
			foreach( $gled_ids as $gid )
			{
				$gtmp = array();
				
				
				$this->db->select( "gledanost.*,
									DATE_FORMAT( gledanost.datum_gledanosti, '%d/%m/%Y' ) AS datum_gledanosti_stampa,",
									false
				);
				
				$this->db->where( 'gledanost_id', $gid[ 'gledanost_id' ] );
				$this->db->order_by( 'datum_gledanosti', 'ASC' );
				
				$gt = $this->db->get( "gledanost" )->result_array();
				$gtmp[ 'gledanost' ] = $gt[ 0 ];
				
				
				$this->db->select( "gledanost_termini.*" );	
				$this->db->where( 'gledanost_id', $gid[ 'gledanost_id' ] );
				
				$gtmp[ 'termini' ] = $this->db->get( "gledanost_termini" )->result_array();

				$gledanost_data[] = $gtmp;
			}
						
			$gledanosti_arr[] = $this->load->view( 'zvanicna_gledanost_strana', 
													array( 'm' => $m[0], 
														   'rd' => $gled, 
														   'gledanost_data' => $gledanost_data 
 														 ), 
 													true 
 												  );
			$termini = array();
		}
		
		$view_data = $this->load->view( 'pregled_zvanicne_gledanosti', array( 'gledanosti' => $gledanosti_arr ), true );
		
		//echo $view_data;
		//return;
		
		//==============================================================
		//==============================================================
		//==============================================================
		
		require_once 'pdf/mpdf.php';
		
		$mpdf = new mPDF( 'utf-8',    // mode - default ''
		'A4-L',    // format - A4, for example, default ''
		0,     // font size - default 0
		'',    // default font family
		2,    // margin_left
		2,    // margin right
		2,     // margin top
		2,    // margin bottom
		9,     // margin header
		9,     // margin footer
		'L');  // L - landscape, P - portrait 
		
		$mpdf->WriteHTML( $view_data );
		
		$mpdf->Output();
	}
	
	protected function _getGledanostData()
	{
		
		// DNEVNA GLEDANOST
		$this->db->where( "gledanost.datum_gledanosti >=" , $this->input->post( 'datum_od' ) );
		$this->db->where( "gledanost.datum_gledanosti <=", $this->input->post( 'datum_do' ) ); 		
		$this->db->where( "gledanost.rokovnik_id", $this->input->post( 'rokovnik_id' ) );
		
		$dnevna_gledanost = $this->db->get( 'gledanost' )->result_array();
		
		// PRINT DATA
		$this->db->select( "rokovnici.*,
							komitenti.naziv_komitenta,
							rokovnici.primenjen_porez_komitenta,
							kopije_zakljucnice.tip_raspodele,
							kopije_zakljucnice.raspodela_iznos,
							kopije_zakljucnice.raspodela_prikazivac,
							filmovi.naziv_filma,
							bioskopi.naziv_bioskopa");
		
		
		
		$this->db->join( 'kopije_zakljucnice', 'kopije_zakljucnice.kopije_zakljucnice_id = rokovnici.kopije_zakljucnice_id', 'inner' );
		$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
		$this->db->join( 'filmovi',  'filmovi.film_id = rokovnici.film_id', 'inner' );
		$this->db->join( 'komitenti', 'komitenti.komitent_id = rokovnici.komitent_id', 'inner' );
		
		
		$this->db->where( "rokovnik_id", $this->input->post( 'rokovnik_id' ) );
		
		
		$rokovnik = $this->db->get( 'rokovnici' )->row( 0, 'RokovnikVo' );
		
		if( ! $rokovnik )
		{
			echo ErrorCodes::APP_ERROR;
			die();
		}
		
		/*********************
		 * rokovnik_id
		 * broj_dokumenta
		 * broj_dokumenta_z_gledanosti
		 * datum_z_gledanost_od
		 * datum_z_gledanost_do
		 * ukupno_gledalaca
		 * ukupan_prihod
		 * ukupan_prihod_eur
		 * za_raspodelu
		 * za_distributera
		 * 
		 ************************/
		
		$st = $this->db->get('settings')->row(0);
		
		$zg = new ZvanicnaGledanostVo();
		$zg->broj_dokumenta_z_gledanosti = $this->brojDokumenta( "zvanicna_gledanost", "broj_dokumenta_z_gledanosti", '862', $rokovnik->tip, $st->godina_poslovanja );
		$zg->ukupan_prihod_karte = 0;
		$zg->ukupan_prihod_karte_eur = 0;
		$zg->ukupan_prihod = 0;
		$zg->ukupan_prihod_eur = 0;
		$zg->ukupan_prihod_naocare = 0;		
		$zg->ukupan_prihod_naocare_eur = 0;
		$zg->ukupno_gledalaca = 0;
		$zg->ukupno_prodato_naocara = 0;
		$zg->ukupan_prihod_bez_smanjenja_rsd = 0;
		$zg->ukupan_prihod_bez_smanjenja_eur = 0;
		
		
		$gledanosti_id = array();
		
		foreach( $dnevna_gledanost as $v )
		{
			
			$zg->ukupan_prihod_karte += @$v[ 'suma_zarada_karte_rsd' ];
			$zg->ukupan_prihod_karte_eur += @$v[ 'suma_zarada_karte_eur' ];
		
			$zg->ukupan_prihod += @$v[ 'suma_zarada_karte_rsd' ] + @$v[ 'suma_zarada_naocare_rsd' ];
			$zg->ukupan_prihod_eur += @$v[ 'suma_zarada_karte_eur' ] + @$v[ 'suma_zarada_naocare_eur' ];
			
			$zg->ukupan_prihod_naocare += @$v[ 'suma_zarada_naocare_rsd' ];
			$zg->ukupan_prihod_naocare_eur += @$v[ 'suma_zarada_naocare_eur' ];

			$zg->ukupno_gledalaca += @$v[ 'suma_gledanosti' ];
			$zg->ukupno_prodato_naocara += @$v[ 'suma_prodatih_naocara' ];
			
			array_push( $gledanosti_id, @$v[ 'gledanost_id' ] );
		}
		
		$zg->ukupan_prihod_bez_smanjenja_rsd = $zg->ukupan_prihod;
		$zg->ukupan_prihod_bez_smanjenja_eur = $zg->ukupan_prihod_eur;
		
		if( ! $zg->ukupno_prodato_naocara )
			$zg->ukupno_prodato_naocara = 0;
			
		if( $this->db->_error_number() )
		{
			return null;
		}
		else 
		{
		
			
			$zg->rokovnik_id = $this->input->post( 'rokovnik_id' );
			$zg->komitent_id = $this->input->post( 'komitent_id' );
			$zg->film_id = $this->input->post( 'film_id' );
			
			$zg->datum_z_gledanost_od = $this->input->post( 'datum_od' );
			$zg->datum_z_gledanost_do = $this->input->post( 'datum_do' );
			$zg->zadnji_dan_gledanosti = $this->input->post( 'datum_do' );
			
			
			$this->_calculateZvanicnaGledanost( $zg, $rokovnik, $st );
			
			// dodati ukupan prihod naocara odvojeno
			return array( 'dnevna_gledanost' => $dnevna_gledanost, 
					  	  'gledanosti_id' => $gledanosti_id,
		              	  'print_data' => $rokovnik,
					  	  'gledanost_insert_data' => array( 
							  'rokovnik_id' => $zg->rokovnik_id,
							  'komitent_id' => $zg->komitent_id,
							  'film_id' => $zg->film_id,
							  'datum_unosa' => date("Y-m-d"),
							  'broj_dokumenta_z_gledanosti' => $zg->broj_dokumenta_z_gledanosti,
							  'datum_z_gledanost_od' => $zg->datum_z_gledanost_od,
							  'datum_z_gledanost_do' => $zg->datum_z_gledanost_do,
							  'ukupno_gledalaca' => $zg->ukupno_gledalaca,
							  'ukupan_prihod_karte' => $zg->ukupan_prihod_karte,
							  'ukupan_prihod_karte_eur' => $zg->ukupan_prihod_karte_eur,  	
							  'ukupan_prihod' => $zg->ukupan_prihod,
							  'ukupan_prihod_bez_smanjenja_rsd' => $zg->ukupan_prihod_bez_smanjenja_rsd,
							  'ukupan_prihod_bez_smanjenja_eur' => $zg->ukupan_prihod_bez_smanjenja_eur,
							  'ukupan_prihod_eur' => $zg->ukupan_prihod_eur,
							  'ukupno_prodato_naocara' => $zg->ukupno_prodato_naocara,
							  'ukupan_prihod_naocare' => $zg->ukupan_prihod_naocare,
							  'ukupan_prihod_naocare_eur' => $zg->ukupan_prihod_naocare_eur,
							  'crveni_krst' => 0,
							  'ostalo' => 0,	
							  'tip'=>$rokovnik->tip,	
							  'za_raspodelu_rsd' => $zg->za_raspodelu_rsd,
							  'za_distributera_rsd' => $zg->za_distributera_rsd,
							  'za_raspodelu_eur' => $zg->za_raspodelu_eur,
							  'za_distributera_eur' => $zg->za_distributera_eur,
							  'zadnji_dan_gledanosti' => $zg->zadnji_dan_gledanosti,
							  'iznos_pdv_rsd' => $zg->iznos_pdv_rsd,	
							  'pdv_procenat_rsd' => $zg->pdv_procenat_rsd,
							  'iznos_pdv_eur' => $zg->iznos_pdv_eur,	
							  'pdv_procenat_eur' => $zg->pdv_procenat_eur
							  
		 	));
		}
	}
	
	
	protected function _calculateZvanicnaGledanost( ZvanicnaGledanostVo $zg, RokovnikVo $rokovnik, $st )
	{
		$preracunat_porez = 0;
		$vrednost_preracunatog_poreza = 0;
		
		$neto = 0;
		$za_raspodelu_rsd = 0;
		$za_distributera_rsd = 0;
		$pdv = 0;
		
		
		
		if( $rokovnik->porez_inostranstvo > 0 )
		{
			$procenat_umanjenja = round( ( $rokovnik->porez_inostranstvo * 100 ) / ( $rokovnik->porez_inostranstvo + 100 ), 4, PHP_ROUND_HALF_UP );
			
			$zg->ukupan_prihod_karte -= $zg->ukupan_prihod_karte * $procenat_umanjenja / 100;
			$zg->ukupan_prihod_karte_eur -= $zg->ukupan_prihod_karte_eur * $procenat_umanjenja / 100;
			$zg->ukupan_prihod -= $zg->ukupan_prihod * $procenat_umanjenja / 100;
			
			$zg->ukupan_prihod_eur -= $zg->ukupan_prihod_eur * $procenat_umanjenja / 100;
			$zg->ukupan_prihod_naocare -= $zg->ukupan_prihod_naocare * $procenat_umanjenja / 100;
			$zg->ukupan_prihod_naocare_eur -= $zg->ukupan_prihod_naocare_eur * $procenat_umanjenja / 100;
			
			$neto = $zg->ukupan_prihod_karte;
			
		}
		else 
		{
			// vrednost preracunatog poreza ostaje 8
			$ppk = 8;
			
			$pf = round( ( $ppk * 100 ) / ( $ppk + 100 ), 4, PHP_ROUND_HALF_UP ); 
			
			$vrednost_preracunatog_poreza = $zg->ukupan_prihod_karte * $pf / 100;
			$neto = $zg->ukupan_prihod_karte - $vrednost_preracunatog_poreza;
		}
		
		switch( $rokovnik->tip_raspodele )
		{
			// Minimalna Garancija
			case 1:
				
				$min_gar = $rokovnik->raspodela_iznos;
				
				/***
					0				  100	
					-------------------
						     50MIN	   UK 			
				***/

				if( $neto <= ( $min_gar * 2 )  )
				{
					$zg->za_distributera_rsd = $min_gar;
				}
				else if( $neto  > ( $min_gar * 2 ) + 1 )
				{
					$zg->za_distributera_rsd = $neto / 2;
				}
				
				if( $zg->ukupan_prihod_eur <= ( $min_gar * 2 )  )
				{
					$zg->za_distributera_eur = $min_gar;
				}
				else if( $zg->ukupan_prihod_eur  > ( $min_gar * 2 ) + 1 )
				{
					$zg->za_distributera_eur = $neto / 2;
				}
				
			break;
			
			// Ugovoreni iznos
			case 2:
				$zg->za_distributera_rsd = $rokovnik->raspodela_iznos;
				$zg->za_distributera_eur = $rokovnik->raspodela_iznos;
			break;
			
			// Raspodela
			case 3:
				$zg->za_distributera_rsd = $neto * $rokovnik->raspodela_iznos / 100;
				$zg->za_distributera_eur = $zg->ukupan_prihod_eur * $rokovnik->raspodela_iznos / 100;
			break;
		}
	
		$zg->za_raspodelu_eur = $zg->ukupan_prihod_eur;
		$zg->za_raspodelu_rsd = $neto;
		
		$zg->iznos_pdv_rsd = round( ( $zg->za_distributera_rsd * $st->porez_rsd / 100 ), 2, PHP_ROUND_HALF_UP );
		$zg->pdv_procenat_rsd = $st->porez_rsd; //zato sto je zvanicna gledanost uvek u dinarima
		
		$zg->iznos_pdv_eur = 0;
		$zg->pdv_procenat_eur = 0;
		
	}
	
	
	public function obrisiZvanicnuGledanost( $z_gledanost_id )
	{
		echo ErrorCodes::ACCESS_DENIED;
		return;
		
		if( $z_gledanost_id )
		{
			$this->db->trans_start();
			
			$this->db->where( "z_gledanost_id", $z_gledanost_id );
			$this->db->delete( "zvanicna_gledanost" );
			
			$this->db->where( "z_gledanost_id", $z_gledanost_id );
			$this->db->delete( "z_gledanost_detalji" );
			
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
	
	public function stornirajZvanicnuGledanost( $z_gledanost_id )
	{
		if( $z_gledanost_id )
		{
			
			$this->db->where( "z_gledanost_id", $z_gledanost_id );
			$this->db->update( 'zvanicna_gledanost', array( "stornirana" => 1 ) );
			
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
	
	public function povratiZvanicnuGledanost( $z_gledanost_id )
	{
		if( $z_gledanost_id )
		{
			
			$this->db->where( "z_gledanost_id", $z_gledanost_id );
			$this->db->update( 'zvanicna_gledanost', array( "stornirana" => 0 ) );
			
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

	protected function _readSelect()
	{
		$this->db->select( "zvanicna_gledanost.*,
							
							FORMAT( zvanicna_gledanost.ukupan_prihod_karte, 4 ) AS ukupan_prihod_karte_stampa, 	
							FORMAT( zvanicna_gledanost.ukupan_prihod_karte_eur, 4 ) AS ukupan_prihod_karte_eur_stampa, 	
							FORMAT( zvanicna_gledanost.ukupan_prihod, 4 ) AS 	ukupan_prihod_stampa, 	
							FORMAT( zvanicna_gledanost.ukupan_prihod_bez_smanjenja_rsd, 4 ) AS ukupan_prihod_bez_smanjenja_rsd_stampa, 	
							FORMAT( zvanicna_gledanost.ukupan_prihod_bez_smanjenja_eur, 4 ) AS ukupan_prihod_bez_smanjenja_eur_stampa, 	
							FORMAT( zvanicna_gledanost.ukupan_prihod_eur, 4 ) AS ukupan_prihod_eur_stampa, 	
							FORMAT( zvanicna_gledanost.ukupan_prihod_naocare, 4 ) AS ukupan_prihod_naocare_stampa, 	
							FORMAT( zvanicna_gledanost.ukupan_prihod_naocare_eur, 4 ) AS 	ukupan_prihod_naocare_eur_stampa, 	
							FORMAT( zvanicna_gledanost.za_raspodelu_rsd, 4 ) AS za_raspodelu_rsd_stampa, 	
							FORMAT( zvanicna_gledanost.za_distributera_rsd, 4 ) AS za_distributera_rsd_stampa, 	
							FORMAT( zvanicna_gledanost.za_raspodelu_eur, 4 ) AS 	za_raspodelu_eur_stampa, 	
							FORMAT( zvanicna_gledanost.za_distributera_eur, 4 ) AS 	za_distributera_eur_stampa, 	
							FORMAT( zvanicna_gledanost.iznos_pdv_rsd, 4 ) AS 	iznos_pdv_rsd_stampa, 	
							FORMAT( zvanicna_gledanost.iznos_pdv_eur, 4 ) AS 	iznos_pdv_eur_stampa, 	
							
							rokovnici.datum_kopije_od, 
							rokovnici.datum_kopije_do,
							rokovnici.raspodela_iznos,
							rokovnici.tip_raspodele,
							filmovi.naziv_filma,
							kopije_filma.tehnika_kopije_filma, 
							komitenti.naziv_komitenta, 
							bioskopi.naziv_bioskopa", 
							false
		 );

		$this->db->join( 'rokovnici', 'rokovnici.rokovnik_id = zvanicna_gledanost.rokovnik_id', 'inner' );
		$this->db->join( 'kopije_filma', 'kopije_filma.kopija_id = rokovnici.kopija_id', 'inner' );
		
		$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
		$this->db->join( 'filmovi',  'filmovi.film_id = rokovnici.film_id', 'inner' );
		$this->db->join( 'komitenti', 'komitenti.komitent_id = rokovnici.komitent_id', 'inner' );
	}
	
	protected function _setAdvancedSearch()
	{
			
		if( strlen( $this->_prefixedValues[ "datum_z_gledanost_od" ] ) > 0 )
			$this->db->where('datum_z_gledanost_od >=', $this->_prefixedValues[ "datum_z_gledanost_od" ] );
		
		if( strlen( $this->_prefixedValues[ "datum_z_gledanost_do" ] ) > 0 )
			$this->db->where('datum_z_gledanost_do <=', $this->_prefixedValues[ "datum_z_gledanost_do" ] );	
		
		if( strlen( $this->_prefixedValues[ "naziv_filma" ] ) > 0 )
			$this->db->like('naziv_filma', $this->_prefixedValues[ "naziv_filma" ] );
		
		if( strlen( $this->_prefixedValues[ "naziv_komitenta" ] ) > 0 )
			$this->db->like('naziv_komitenta', $this->_prefixedValues[ "naziv_komitenta" ] );
	
		if( strlen( $this->_prefixedValues[ "naziv_bioskopa" ] ) > 0 )
			$this->db->like('naziv_bioskopa', $this->_prefixedValues[ "naziv_bioskopa" ] );
		
		if( strlen( $this->_prefixedValues[ "stornirana" ] ) > 0 )
			$this->db->where('stornirana', $this->_prefixedValues[ "stornirana" ] );
		
		if( strlen( $this->_prefixedValues[ "broj_dokumenta_z_gledanosti" ] ) > 0 )
			$this->db->like('broj_dokumenta_z_gledanosti', $this->_prefixedValues[ "broj_dokumenta_z_gledanosti" ] );
			
		if( strlen( $this->_prefixedValues[ "tip" ] ) > 0 )
			$this->db->where('zvanicna_gledanost.tip', $this->_prefixedValues[ "tip" ] );
			
		if( strlen( $this->_prefixedValues[ "tehnika_kopije_filma" ] ) > 0 )
			$this->db->where('tehnika_kopije_filma', $this->_prefixedValues[ "tehnika_kopije_filma" ] );
			
	}
	
}

/**
		 
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
		
		*/		


/* End of file gledanost.php */
/* Location: ./application/controllers/gledanost.php */