<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/hooks/PreController.php';

class Gledanost extends PreController 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('gledanost_view_helper.php');
	}

	public function index()
	{
		$data = array();
		$data[ 'lang' ] = $this->lang->language;
		
		$this->load->view( "gledanost", $data );
	}
	
	public function getGledanostByDate()
	{
		$this->_loadLang();
		
		$datum = @$_POST[ "datum" ];
		
		$this->db->select( "rokovnici.rokovnik_id,
							rokovnici.status_kopije,
							kopije_filma.tehnika_kopije_filma,
							kopije_filma.serijski_broj_kopije,
							filmovi.naziv_filma,
							bioskopi.naziv_bioskopa,
							komitenti.naziv_komitenta" 
		);
		
		$this->db->join( 'kopije_filma', 'kopije_filma.kopija_id = rokovnici.kopija_id', 'inner' );
		$this->db->join( 'filmovi', 'filmovi.film_id = rokovnici.film_id', 'inner' );
		$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
		$this->db->join( 'komitenti', 'komitenti.komitent_id = rokovnici.komitent_id', 'inner' );
		
		$this->db->where( "datum_kopije_od <=", $datum );
		$this->db->where( "datum_kopije_do >=", $datum );
		
		$this->_setAdvancedSearch();
		
		$this->db->order_by( "kopije_filma.tehnika_kopije_filma", "ASC" );
		$this->db->order_by( "filmovi.start_filma", "DESC" );
		
		$rr = $this->db->get( "rokovnici" )->result_array();
		$l = count( $rr );
		
		$datum_gledanosti = 0;
		$d_gl_ar;
		$datum_gledanosti_str = "";
		
		$gledanost_d = null;
		$termini_d = null;
		
		$tabele = "";
		$days_num = 0;
		$first_date = 0;
		
		(int)$ukupna_zarada = 0;
		
		
		for( $i = 0; $i < $l; $i++ )
		{
			$ukupna_zarada = 0;
			
			$this->db->where( "rokovnik_id", $rr[ $i ][ "rokovnik_id" ] );
			$this->db->where( "datum_gledanosti", $datum );
				
			$gledanost_d = $this->db->get( "gledanost" )->result_array();
			
			if( $gledanost_d && count( $gledanost_d ) > 0 )
			{
				$this->db->where( "gledanost_id", $gledanost_d[0][ "gledanost_id" ] );
				$this->db->order_by( "redni_broj_termina", "ASC" );
				
				$this->db->select( "gledanost_termini.*, HOUR( gledanost_termini.vreme ) AS sat, MINUTE( gledanost_termini.vreme ) AS minut" );
				$termini_d = $this->db->get( "gledanost_termini" )->result_array();
				
				if( count( $termini_d ) < 1 ) 
					$termini_d = null;
			}
			else
			{
				$termini_d = null;
			}
		
			$this->db->select( "SUM( gledanost.suma_zarada_karte_rsd ) AS suma_zarada_rsd" );
			$this->db->where( "rokovnik_id", $rr[ $i ][ "rokovnik_id" ] );
			
			$gsd = $this->db->get( "gledanost" )->result_array();
			
			$tabele .= $this->load->view( "gledanost_tabela", 
											   array( "g_data" => @$gledanost_d[0],
											   		  "t_data" => $termini_d,
											   		  "tehnika" => $rr[ $i ][ "tehnika_kopije_filma" ],
											   		  "serijski_broj_kopije" => $rr[ $i ][ "serijski_broj_kopije" ],
											   		  "naziv_filma" => $rr[ $i ][ "naziv_filma" ],
											   		  "naziv_bioskopa" => $rr[ $i ][ "naziv_bioskopa" ],
											   		  "naziv_komitenta" => $rr[ $i ][ "naziv_komitenta" ],
											   		  "status_kopije" => $rr[ $i ][ "status_kopije" ],
											   		  "form_id" => "save_gledanost_form_" . $i,
											   		  "tehnika_kopije_filma" => $rr[ $i ][ "tehnika_kopije_filma" ],
													  "rokovnik_id" => $rr[ $i ][ "rokovnik_id" ],
											   		  "ukupna_zarada" => $gsd[0],
											   		  "lang" => $this->lang->language,										   
											   ), 
											   true 
											 );
			
			
		}
		
		echo $tabele;
	}
	
	public function getAvailableGledanost()
	{
		$this->_loadLang();
		
		$kurs_q = $this->db->where( "datum_kursa", @$_POST[ "datum" ] )->get( 'kursna_lista' );

		if( $kurs_q->num_rows() == 1 )
		{
			$datum = $this->input->post( "datum" );
			
			$kd = $kurs_q->result_array();
			
			$_35 = $this->_getSumFromCopyType( 1, $datum );
			$_3d = $this->_getSumFromCopyType( 2, $datum );
			$_2d = $this->_getSumFromCopyType( 3, $datum );
			
			
			$sum_gledanosti = 0;
			$sum_naocare = 0;
			
			$this->db->select( "SUM( suma_gledanosti ) AS suma_gledanosti, SUM( gledanost.suma_prodatih_naocara ) AS suma_naocara" );	
			$this->db->where( "gledanost.datum_gledanosti", $datum );
			$q = $this->db->get( "gledanost" );
			
			if( $q->num_rows() == 1 )
			{
				$data = $q->result_array();
				
				$sum_gledanosti = $data[0][ "suma_gledanosti" ];
				$sum_naocare = $data[0][ "suma_naocara" ];
			}
			
			
			$this->load->view( "gledanost_cnt", array( 'filter_data' => $this->_getFilterData( $datum ), 
													   'kurs_data' => $kd[ 0 ],
													   'lang' => $this->lang->language,
													   's_data' => array( "sum_35mm" => $_35, 
													   					  "sum_3d" => $_3d, 
													   					  "sum_2d" => $_2d,
																		  "suma_gledanosti" => $sum_gledanosti,
																		  "suma_naocara" => $sum_naocare
																		 )  
													 ) 
							 );
		}
		else
		{
			echo "<br /><h2 style='color:#FFFFFF'>" . $this->lang->language[ 'ne_postoji_kurs' ] . "</h2>";
		}
	}
	
	protected function _getSumFromCopyType( $copy_type, $datum )
	{
		$this->db->select( "SUM( gledanost.suma_zarada_karte_rsd ) AS suma_zarada_rsd" );
			
		$this->db->join( 'rokovnici', 'rokovnici.rokovnik_id = gledanost.rokovnik_id', 'inner' );
		$this->db->join( 'kopije_filma', 'kopije_filma.kopija_id = rokovnici.kopija_id', 'inner' );
		$this->db->where( "kopije_filma.tehnika_kopije_filma", $copy_type );
		$this->db->where( "gledanost.datum_gledanosti", $datum );
		
		$q = $this->db->get( "gledanost" );
		
		if( $q->num_rows() == 1 )
		{
			$data = $q->result_array();
			
			return $data[0][ "suma_zarada_rsd" ];
		}
		
		return 0;
		
	}
	
	public function saveGledanost()
	{
		
		$data =  $this->postArrayParser( $this->_prefixedValues );
		$data = $data[ "termini_" ];
		
		$rokovnik_id = $this->input->post( "rokovnik_id" );
		
		$gledanost_id = null;
		
		$g_data = array( 
						"rokovnik_id" => $rokovnik_id,
						"datum_gledanosti" => $this->input->post( "datum_gledanosti" ),

						"suma_zarada_karte_rsd" => $this->input->post( "suma_zarada_karte_rsd" ),
						"suma_zarada_karte_eur" => $this->input->post( "suma_zarada_karte_eur" ),
						"suma_zarada_karte_km" => $this->input->post( "suma_zarada_karte_km" ),
						
						"suma_zarada_naocare_rsd" => $this->input->post( "suma_zarada_naocare_rsd" ),
						"suma_zarada_naocare_eur" => $this->input->post( "suma_zarada_naocare_eur" ),
						"suma_zarada_naocare_km" => $this->input->post( "suma_zarada_naocare_km" ),
										
						"suma_prodatih_naocara" => $this->input->post( "suma_prodatih_naocara" ),
						"suma_gledanosti" => $this->input->post( "suma_gledanosti" ),
						"status_gledanosti" => $this->input->post( "status_gledanosti" )
 					 );
							 					 
		
 		$this->db->where( "rokovnik_id", $rokovnik_id );
		$this->db->where( "datum_gledanosti", $this->input->post( "datum_gledanosti" ) );
 		$this->db->limit( 1 );
		
		$g_check_data = $this->db->get( "gledanost" )->result_array();
		$g_exists = count( $g_check_data );
		
 		$this->db->trans_start();
 		
 		
		if( $g_exists == 0 )		
		{
			$this->db->insert( "gledanost", $g_data );
							 
			if( $this->db->affected_rows() == 1 )
			{
				$gledanost_id = $this->db->insert_id();
			}					 
		}
		else
		{
			$gledanost_id = $g_check_data[ 0 ][ "gledanost_id" ];
			
			$this->db->where( "gledanost_id", $gledanost_id );
			$this->db->update( "gledanost", $g_data );
		}
		
		if( $gledanost_id )
		{
			
			$dl = count( $data );
			
			$termin_data = array();
			
			$t_result = false;
			
			for( $i = 0; $i < $dl; $i++ )
			{
				
				if( strlen( $data[ $i ][ "sat" ] ) < 1 ||  strlen( $data[ $i ][ "minut" ] ) < 1 )
					continue;
				
				$termin_data[ "vreme" ] = $data[ $i ][ "sat" ] . ":" . $data[ $i ][ "minut" ]; 
				$termin_data[ "broj_gledalaca" ] = $data[ $i ][ "broj_gledalaca" ];
				
				$termin_data[ "cena_karte_rsd" ] = $data[ $i ][ "cena_karte_rsd" ];
				$termin_data[ "cena_karte_eur" ] = $data[ $i ][ "cena_karte_eur" ];
				$termin_data[ "cena_karte_km" ] = $data[ $i ][ "cena_karte_km" ];
			
				$termin_data[ "zarada_po_terminu_rsd" ] = $data[ $i ][ "zarada_po_terminu_rsd" ];
				$termin_data[ "zarada_po_terminu_eur" ] = $data[ $i ][ "zarada_po_terminu_eur" ];
				$termin_data[ "zarada_po_terminu_km" ] = $data[ $i ][ "zarada_po_terminu_km" ];
	
				
				if( $this->input->post( 'tehnika_kopije_filma') == 2 )
				{
					$termin_data[ "broj_prodatih_naocara" ] = $data[ $i ][ "broj_prodatih_naocara" ];
				
					$termin_data[ "cena_naocara_rsd" ] = $data[ $i ][ "cena_naocara_rsd" ];
					$termin_data[ "cena_naocara_eur" ] = $data[ $i ][ "cena_naocara_eur" ];
					$termin_data[ "cena_naocara_km" ] = $data[ $i ][ "cena_naocara_km" ];
					
					$termin_data[ "zarada_naocara_po_terminu_rsd" ] = $data[ $i ][ "zarada_naocara_po_terminu_rsd" ];
					$termin_data[ "zarada_naocara_po_terminu_eur" ] = $data[ $i ][ "zarada_naocara_po_terminu_eur" ];
					$termin_data[ "zarada_naocara_po_terminu_km" ] = $data[ $i ][ "zarada_naocara_po_terminu_km" ];
				}
				
				
				$termin_data[ "redni_broj_termina" ] = $data[ $i ][ "redni_broj" ];
				$termin_data[ "gledanost_id" ] = $gledanost_id;
					
				$this->saveTermin( $termin_data, $gledanost_id );
		
			}//END FOR LOOP
		}
		else
		{
			$this->db->trans_complete();
			echo ErrorCodes::DATABASE_ERROR;
			return;
		}
		
		$termin_prosek_data = $this->_getProsekTerminaGledanosti( $gledanost_id );
		
		$this->db->where( 'gledanost_id', $gledanost_id );
		$this->db->update( 'gledanost', $termin_prosek_data );
		
		$sume_data = $this->_getSumeGledanosti( $rokovnik_id );
		
		$gledanost_prosek_data = $this->_getProsekGledanosti( $rokovnik_id ); 
		
		$gledanost_additional_data = array_merge( $sume_data, $gledanost_prosek_data );
		
		$this->db->where( 'rokovnik_id', $rokovnik_id );
		$this->db->update( 'rokovnici', $gledanost_additional_data );
		
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
	
	protected function saveTermin( $data, $g_id )
	{
		$this->db->select( "gledanost_termin_id" );
		$this->db->where( "redni_broj_termina", $data[ "redni_broj_termina"] );
		$this->db->where( "gledanost_id", $g_id );
		
		$q = $this->db->get( "gledanost_termini" );
		
		// termin vec postoji
		if( $q->num_rows() > 0 )
		{
			$tdata = $q->result_array();
			$this->_updateTermin( $tdata[0][ "gledanost_termin_id" ], $data );
			return true;
		}
		
		$this->db->insert( "gledanost_termini", $data );
		
		if( $this->db->affected_rows() == 1 )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	protected function _updateTermin( $id, $data )
	{
		$this->db->where( "gledanost_termin_id", $id );
		$this->db->update( "gledanost_termini", $data );
		
		
		if( $this->db->_error_number() != 0 )
		{
			return false;
		}
		else
		{
			return true;
		}
		
	}
	
	protected function _getSumeGledanosti( $rokovnik_id )
	{
		
		$this->db->select( 'SUM( suma_zarada_karte_rsd) AS suma_zarada_rsd,
							SUM( suma_zarada_karte_eur) AS suma_zarada_eur,
							SUM( suma_zarada_karte_km) AS suma_zarada_km,
							SUM( suma_gledanosti) AS suma_gledanosti_kopije,
							SUM( suma_zarada_naocare_rsd) AS suma_zarada_naocare_rsd,
							SUM( suma_zarada_naocare_eur) AS suma_zarada_naocare_eur,
							SUM( suma_zarada_naocare_km) AS suma_zarada_naocare_km,
							SUM( suma_prodatih_naocara) AS suma_prodatih_naocara_kopije' );
		
		$this->db->where( 'rokovnik_id', $rokovnik_id );
		
		$sume = $this->db->get( 'gledanost' )->result_array();
		
		return $sume[0];
		
	}

	protected function _getProsekGledanosti( $rokovnik_id )
	{
		$this->db->select( "gledanost_id" );
		$this->db->where( "rokovnik_id", $rokovnik_id );
		
		$gdis = $this->db->get( "gledanost" )->result_array();
		
		$this->db->select( 'AVG( prosek_cena_karte_rsd ) AS prosek_cena_karte_rsd,
							AVG( prosek_cena_karte_eur ) AS prosek_cena_karte_eur,
							AVG( prosek_cena_karte_km ) AS prosek_cena_karte_km,
							AVG( prosek_broj_gledalaca ) AS prosek_broj_gledalaca,
							AVG( prosek_broj_prodatih_naocara ) AS prosek_broj_prodatih_naocara' );
		
		foreach( $gdis as $v )
		{
			$this->db->or_where( 'gledanost_id', $v[ "gledanost_id" ] );
		}
		
		$sume = $this->db->get( 'gledanost' )->result_array();
		
		return $sume[0];
	}
	
	protected function _getProsekTerminaGledanosti( $gledanost_id )
	{
		
		$this->db->select( 'AVG( cena_karte_rsd ) AS prosek_cena_karte_rsd,
							AVG( cena_karte_eur ) AS prosek_cena_karte_eur,
							AVG( cena_karte_km ) AS prosek_cena_karte_km,
							AVG( broj_gledalaca ) AS prosek_broj_gledalaca,
							AVG( broj_prodatih_naocara ) AS prosek_broj_prodatih_naocara' );
		
		$this->db->where( "gledanost_id", $gledanost_id );
		
		$sume = $this->db->get( 'gledanost_termini' )->result_array();
		
		return $sume[0];
		
	}
	
	protected function _getFilterData( $datum )
	{
		$data = array();
		
		
		$this->db->select( 'komitenti.komitent_id, komitenti.naziv_komitenta' );
		$this->db->join( 'komitenti', 'komitenti.komitent_id = rokovnici.komitent_id', 'inner' );
		
		$this->db->where( "rokovnici.datum_kopije_od <=", $datum );
		$this->db->where( "rokovnici.datum_kopije_do >=", $datum );
		$this->db->group_by( 'rokovnici.komitent_id' );

		$data[ 'komitenti' ] = $this->db->get( 'rokovnici' )->result_array();

		$this->db->select( 'filmovi.film_id, filmovi.naziv_filma' );
		$this->db->join( 'filmovi', 'filmovi.film_id = rokovnici.film_id', 'inner' );
		
		$this->db->where( "rokovnici.datum_kopije_od <=", $datum );
		$this->db->where( "rokovnici.datum_kopije_do >=", $datum );
		$this->db->group_by( 'rokovnici.film_id' );

		$data[ 'filmovi' ] = $this->db->get( 'rokovnici' )->result_array();
		
		
		$this->db->select( 'kopije_filma.kopija_id, kopije_filma.serijski_broj_kopije' );
		$this->db->join( 'kopije_filma', 'kopije_filma.kopija_id = rokovnici.kopija_id', 'inner' );
		
		$this->db->where( "rokovnici.datum_kopije_od <=", $datum );
		$this->db->where( "rokovnici.datum_kopije_do >=", $datum );
		$this->db->group_by( 'rokovnici.kopija_id' );

		$data[ 'kopije' ] = $this->db->get( 'rokovnici' )->result_array();
		
						
		$this->db->select( 'bioskopi.bioskop_id, bioskopi.naziv_bioskopa' );
		$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
		
		$this->db->where( "rokovnici.datum_kopije_od <=", $datum );
		$this->db->where( "rokovnici.datum_kopije_do >=", $datum );
		$this->db->group_by( 'rokovnici.bioskop_id' );

		$data[ 'bioskopi' ] = $this->db->get( 'rokovnici' )->result_array();
		

		return $data;
	}
	
	protected function _setAdvancedSearch()
	{
		if( strlen( $this->input->post( 'filmovi_filter' ) ) > 0 )
			$this->db->where( 'rokovnici.film_id', $this->input->post( 'filmovi_filter' ) ); 
		
		if( strlen( $this->input->post( 'kopije_filter' ) ) > 0 )
			$this->db->where( 'rokovnici.kopija_id', $this->input->post( 'kopije_filter' ) );
		
		if( strlen( $this->input->post( 'komitenti_filter' ) ) > 0 )
			$this->db->where( 'rokovnici.komitent_id', $this->input->post( 'komitenti_filter' ) );
			
		if( strlen(  $this->input->post( 'bioskopi_filter' ) ) > 0 )
			$this->db->where( 'rokovnici.bioskop_id', $this->input->post( 'bioskopi_filter' ) );
			
		if( strlen(  $this->input->post( 'bioskopi_filter' ) ) > 0 )
			$this->db->where( 'rokovnici.bioskop_id', $this->input->post( 'bioskopi_filter' ) );

		if( strlen(  $this->input->post( 'tehnika_kopije_filter' ) ) > 0 )
			$this->db->where( 'kopije_filma.tehnika_kopije_filma', $this->input->post( 'tehnika_kopije_filter' ) );	

		/*	
		if( strlen(  $this->input->post( 'status_gledanosti_filter' ) ) > 0 )
			$this->db->where( 'gledanost.status_gledanosti', $this->input->post( 'status_gledanosti_filter' ) );
		*/	
					
	}
	
	public function prikaziDnevnuGledanost()
	{
		$this->db->select( 'gledanost.*, 
							filmovi.film_id, 
							filmovi.naziv_filma, 
							rokovnici.*,
							kopije_filma.tehnika_kopije_filma,
							kopije_filma.kopija_id,
							komitenti.naziv_komitenta,
							bioskopi.naziv_bioskopa' );
		
		
		$this->db->join( 'rokovnici', 'rokovnici.rokovnik_id = gledanost.rokovnik_id', 'inner' );
		$this->db->join( 'komitenti', 'komitenti.komitent_id = rokovnici.komitent_id', 'inner' );
		$this->db->join( 'filmovi', 'filmovi.film_id = rokovnici.film_id', 'inner' );
		$this->db->join( 'kopije_filma', 'kopije_filma.kopija_id = rokovnici.kopija_id', 'inner' );
		$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
		
		$this->db->where( "datum_gledanosti", $_GET[ "datum_gledanosti" ] );
		$this->db->group_by( "gledanost.rokovnik_id" );
		$this->db->order_by( "filmovi.film_id" );
		
		$gd = $this->db->get( "gledanost" )->result_array();
		
		$ft = array();
		$gt = null;
		
		$film_id = 0;
		$p = false;
		
		$kopije_group = array();
		foreach( $gd as $g )
		{
			$this->db->where( "gledanost_id", $g[ "gledanost_id" ] );
			$this->db->limit( 12 );
			$gt = $this->db->get( "gledanost_termini" )->result_array();
			
			if( $film_id == $g[ "film_id" ] )
			{
				array_push( $kopije_group[ $film_id ], array( "g_data" => $g, "termini" => $gt ) );
			}
			else
			{
				$film_id = $g[ "film_id" ];
				$kopije_group[ $film_id ] = array( array( "g_data" => $g, "termini" => $gt ) );
			}
		}

		$gv = "";
		$gdt_len = 0;
		$gva = array();
		
		foreach( $kopije_group as $v )
		{
			$gdt_len = count( $v );
			$gv = "<p style='margin:0px;'><b>Datum:</b> " . $v[0]["g_data"]["datum_gledanosti"] . " <b> &nbsp;&nbsp;&nbsp; Ime Filma:</b> ". $v[0]["g_data"]["naziv_filma"] ."</b></p>";
			
			for( $i = 0; $i < $gdt_len; $i++ )
			{
				$gv .= $this->load->view( "d_gledanost_tabela", array( "t" => $v[ $i ] ), true );
				
				if( ( $i + 1 ) % 5 == 0 )
				{
					if( $i != 0 )
					{
						
						array_push( $gva, $gv );
						$gv = "<p style='margin:0px;'><b>Datum:</b> " . $v[0][ "g_data" ]["datum_gledanosti"] . " <b> &nbsp;&nbsp;&nbsp; Ime Filma:</b> ". $v[0][ "g_data" ]["naziv_filma"] ."</b></p>";
					}
				}
				else if( $i == $gdt_len - 1 )
				{
					array_push( $gva, $gv );
				}
			}
		}
	
		
		$this->load->view( "pregled_d_gledanosti", array( "gledanosti" => $gva ) );
	}
	
}

/* End of file gledanost.php */
/* Location: ./application/controllers/gledanost.php */