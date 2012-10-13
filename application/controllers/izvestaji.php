<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/hooks/PreController.php';

class Izvestaji extends PreController 
{
	protected $_komitent_id;
	protected $_datum_od;
	protected $_datum_do;	
	protected $_film_id;
    protected $_bioskop_id;
    protected $_bioskop_alias_id;
	protected $_sub_sume;
	protected $_pf;
	protected $_decimal_point;
	protected $_naziv_komitenta;
	protected $_naziv_filma;
	protected $_producent;
	protected $_tip_komitenta;
	protected $_tehnika_kopije_filma;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->_decimal_point = 4;
		
		$this->_naziv_komitenta = $this->input->post( "naziv_komitenta" );
		$this->_tip_komitenta = $this->input->post( "tip_komitenta" );
		$this->_naziv_filma = $this->input->post( "naziv_filma" );
		
		$this->_komitent_id = $this->input->post( "komitent_id" );
		$this->_datum_od = $this->input->post( "datum_kopije_od" );
		$this->_datum_do = $this->input->post( "datum_kopije_do" );		
		$this->_film_id = $this->input->post( "film_id" );
		
		$this->_bioskop_alias_id = $this->input->post( "izvestaj_lk_bioskop_select" );
		$this->_sub_sume = $this->input->post( "sub_sume" );
		$this->_tehnika_kopije_filma = $this->input->post( 'tehnika_kopije_filma' );
		
		$this->_pf = 0;
		
		$this->load->database();
	
		
		if( ! $this->_sort_order_name )
			$this->_sort_order_name = 'asc';
	}

	public function index()
	{
		$data = array();
		$data[ 'lang' ] = $this->lang->language;
		
		$this->load->view( "izvestaji", $data );
	}
	
	public function getPf( $komitent_id )
	{
		if( $komitent_id )
		{
			$k = $this->db->select( 'gledanost_komitenta' )->where( 'komitent_id',  $komitent_id )->get( 'komitenti' )->row( 0 );
			$percent = 0;
			
			if( $k->gledanost_komitenta == 1 )
			{
				$percent = 8; // RSD
			}
			else
			{
				$percent = 7; // KM EUR
			}
			
			$this->_pf = round( ( $percent * 100 ) / ( $percent + 100 ), 4, PHP_ROUND_HALF_UP );
		}
		
		return $this->_pf;
	}
	
	public function lkOdigraniFilmoviRead( $return = FALSE )
	{
		if( ! $this->_sort_col_name )
			$this->_sort_col_name = 'filmovi.film_id';	
		
		$this->_selectOdigraniFilmovi( true );
		$totalRows = $this->db->get('gledanost' )->num_rows();
		
		$this->_selectOdigraniFilmovi( false );
		
		if( $return )
		{
			return $this->db->get( 'gledanost' )->result_array();
		}
		else
		{
			$this->db->limit( $this->_limit, $this->getRowsOffset( $this->_page, $this->_limit ) );
			$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
			
			$query = $this->db->get( 'gledanost' );
			$this->dispatchResultXml( $query->result_array(), $totalRows  );
		}
		
	}	
	
	protected function _selectOdigraniFilmovi( $count = false )
	{
		if( $count == false )
		{
			$this->db->select( "filmovi.film_id, 
								filmovi.naziv_filma," );
		}
		
		$this->db->join( 'rokovnici', 'rokovnici.rokovnik_id = gledanost.rokovnik_id', 'inner' );
		$this->db->join( 'filmovi', 'filmovi.film_id = rokovnici.film_id', 'inner' );
		
		if( $this->_bioskop_alias_id )
		{
			$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
			$this->db->join( 'bioskop_aliases', 'bioskop_aliases.bioskop_alias_id = bioskopi.alias_bioskopa', 'left' );
		
			$this->db->where( "bioskopi.alias_bioskopa", $this->_bioskop_alias_id );
		}
		
		if( $this->_tip_komitenta )
		{
			$this->db->where( "rokovnici.tip", $this->_tip_komitenta );
		}
		
		
		$this->db->where( "gledanost.datum_gledanosti >=", $this->_datum_od );
		$this->db->where( "gledanost.datum_gledanosti <=", $this->_datum_do );
		
		
		$this->db->group_by(  'rokovnici.film_id' );
	}
	
	public function lkFilmoviBezIzvestajaRead( $return = FALSE )
	{
		if( ! $this->_sort_col_name )
			$this->_sort_col_name = 'kopije_zakljucnice.kopije_zakljucnice_id';
			
		$this->db->select( 'kopije_zakljucnice.kopije_zakljucnice_id,
							kopije_filma.serijski_broj_kopije,
							rokovnici.rokovnik_id,
						    filmovi.naziv_filma,
						    zakljucnice.zakljucnica_id,
						    kopije_zakljucnice.datum_kopije_do, 
						    kopije_zakljucnice.datum_kopije_od,
						    bioskop_aliases.bioskop_alias_name AS bioskop, 
						    DATEDIFF( kopije_zakljucnice.datum_kopije_do, kopije_zakljucnice.datum_kopije_od ) AS broj_dana_kopije
						    ' );
		
		$this->db->join( 'zakljucnice', 'zakljucnice.zakljucnica_id = kopije_zakljucnice.zakljucnica_id', 'inner' );
		$this->db->join( 'kopije_filma', 'kopije_filma.kopija_id = kopije_zakljucnice.kopija_id', 'inner' );
		$this->db->join( 'filmovi', 'filmovi.film_id = kopije_zakljucnice.film_id', 'inner' );
		$this->db->join( 'rokovnici', 'rokovnici.kopije_zakljucnice_id = kopije_zakljucnice.kopije_zakljucnice_id', 'inner' );
		$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
		$this->db->join( 'bioskop_aliases', 'bioskop_aliases.bioskop_alias_id = bioskopi.alias_bioskopa', 'left' );
			
		$this->db->where( "zakljucnice.komitent_id", $this->_komitent_id );
		$this->db->where( "kopije_zakljucnice.datum_kopije_od >=", $this->_datum_od );

		// this is commented to show movies that are inside interval of datum_od
		//$this->db->where( "kopije_zakljucnice.datum_kopije_do <=", $this->_datum_do );
		
		if( $this->_bioskop_alias_id )
		{
			
			$this->db->where( "bioskopi.alias_bioskopa", $this->_bioskop_alias_id );
		}
		
		if( $this->_tip_komitenta )
		{
			$this->db->where( "rokovnici.tip", $this->_tip_komitenta );
		}
		
		
		$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
		
		$kopije_data = $this->db->get( 'kopije_zakljucnice' )->result_array();
		
		$final_data = array();
		
		$datum_od = NULL;		
		$datum_do = NULL;
		
		$i = 0;
		
		$datum_gledanosti = NULL;
		$datum_gledanost_string = '';
		
		$datum_do_limit = null;
		
		foreach( $kopije_data as $kd )
		{
			$datum_od = strtotime( $kd[ 'datum_kopije_od' ] );
			$datum_do = strtotime( $kd[ 'datum_kopije_do' ] );
			
			$datum_do_limit = new DateTime($kd[ 'datum_kopije_od' ]);
			$datum_do_limit = $datum_do_limit->diff( new DateTime( $this->_datum_do ) )->d + 1;
			
			// get the days that are not in dnevna_gledanost for each movie - rokovnik
			for( $i = 0; $i < $datum_do_limit; $i++ )
			{
				if( $i == 0 )
				{
					$datum_gledanosti = $datum_od;
				}
				else if( $i == $kd[ 'broj_dana_kopije' ] - 1 )
				{
					$datum_gledanosti = $datum_do;
				}
				else 
				{
					$datum_gledanosti += 24 * 60 * 60;
				}	 
				
				$datum_gledanost_string = date( "Y-m-d", $datum_gledanosti );
				
				$this->db->where( "rokovnik_id", $kd[ 'rokovnik_id' ] );
				$this->db->where( "datum_gledanosti", $datum_gledanost_string );
				
				if( $this->db->get( "gledanost" )->num_rows() == 0 )
				{
					$kd[ 'datum' ] = date( "d/m/Y", $datum_gledanosti );
					array_push( $final_data, $kd );
				}
			}
			
		}
		
		if( $return )
		{
			return $final_data;
		}
		else
		{			
			$total_records = count( $final_data );
			
			$this->dispatchResultXml( array_slice( $final_data, $this->getRowsOffset( $this->_page, $this->_limit ), $this->_limit ), $total_records );
		}
	}
	
	public function lkTopListaFilmovaRead( $return = FALSE )
	{
		
		$this->db->select( "filmovi.film_id, 
							filmovi.naziv_filma,
							fakture.valuta_fakture, 
							fakture.vdate, 
							fakture.osnovica + fakture.ukupan_pdv AS bruto_zarada, 
							fakture.osnovica  AS neto_zarada,
							fakture_stavke.broj_gledalaca AS ukupno_gledalaca"

						 );

		$this->db->join( 'zvanicna_gledanost', "zvanicna_gledanost.z_gledanost_id = fakture.z_gledanost_id", 'inner' );
		$this->db->join( 'rokovnici', "rokovnici.rokovnik_id = zvanicna_gledanost.rokovnik_id", 'inner' );
		$this->db->join( 'filmovi', "filmovi.film_id = zvanicna_gledanost.film_id", 'inner' );
		$this->db->join( 'fakture_stavke', "fakture_stavke.faktura_id = fakture.faktura_id", 'inner' );

		if( $this->_bioskop_alias_id )
		{
			$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
			$this->db->join( 'bioskop_aliases', 'bioskop_aliases.bioskop_alias_id = bioskopi.alias_bioskopa', 'left' );
		
			$this->db->where( "bioskopi.alias_bioskopa", $this->_bioskop_alias_id );
		}
		
		$this->db->where( "fakture.datum_unosa_fakture >=", $this->_datum_od );
		$this->db->where( "fakture.datum_unosa_fakture <=", $this->_datum_do );
		
		$this->db->where( "fakture.stornirana", 0 );
		$this->db->where( "fakture_stavke.artikal_id", 1 );

		$this->db->where( "zvanicna_gledanost.komitent_id", $this->_komitent_id );
		
		if( $this->_tip_komitenta )
		{
			$this->db->where( "fakture.tip", $this->_tip_komitenta );
		}
		
		
		$result = $this->db->get( 'fakture' )->result_array();
		
		
		// convert to RSSD
		$converted = array();
		$kurs = NULL;
		$temp = NULL;
		
		foreach( $result as $row )
		{
			// EUR FAKTURE
			if( $row[ 'valuta_fakture' ] == 2 )
			{
				$kurs = $this->db->select( 'eur' )->where( 'datum_kursa', $row[ 'vdate' ] )->get( 'kursna_lista' )->row( 0 );
				
				$row[ 'bruto_zarada' ] = $row[ 'bruto_zarada' ] * $kurs->eur;
				$row[ 'neto_zarada' ] = $row[ 'neto_zarada' ] * $kurs->eur;
			}
			
			array_push( $converted, $row );
		}
		
		// group by movies
		$grouped = array();
		
		foreach( $converted as $row )
		{
			if( array_key_exists( $row[ 'film_id' ], $grouped ) )
			{
				$temp = $grouped[ $row[ 'film_id' ] ];
				
				$temp[ 'bruto_zarada' ] = $temp[ 'bruto_zarada' ] + $row[ 'bruto_zarada' ]; 
				$temp[ 'neto_zarada' ] = $temp[ 'neto_zarada' ] + $row[ 'neto_zarada' ];
				$temp[ 'ukupno_gledalaca' ] = $temp[ 'ukupno_gledalaca' ] + $row[ 'ukupno_gledalaca' ];
				 
				$grouped[ $row[ 'film_id' ] ] = $temp;
			}
			else
			{
				$grouped[ $row[ 'film_id' ] ] = $row;
			}
		}
		
		
		// sort. cant sort in first iteration because of conversion
		$sorted_data = array();
			
		foreach( $grouped as $row ) 
		{
	        $sorted_data[] = $row[ $this->_sort_col_name ];
    	}
    	
		$sort_order = $this->_sort_order_name == 'asc' ? SORT_ASC : SORT_DESC;
	    	
		array_multisort( $sorted_data, $sort_order, $grouped );
		
		$formated_data = array();
		
		// format data

		if( ! $return )
		{
			foreach( $grouped as $row )
			{
				$row[ 'bruto_zarada' ] = number_format( $row[ 'bruto_zarada' ], $this->_decimal_point ); 
				$row[ 'neto_zarada' ] = number_format( $row[ 'neto_zarada' ], $this->_decimal_point );
				
				array_push( $formated_data, $row );
			}
		}
		else
		{
			$formated_data = $grouped;
		}


		if( $return )
		{
			return $formated_data;
		}
		else
		{
			// limit. set paging manually
			$total_records = count( $formated_data );
			$this->dispatchResultXml( array_slice( $formated_data, $this->getRowsOffset( $this->_page, $this->_limit ), $this->_limit ), $total_records );
		}
	}
	
	public function lkFilmoviSaIzvestajimaRead( $return = FALSE )
	{		
		if( ! $this->_sort_col_name )
			$this->_sort_col_name = 'naziv_filma';
			
		if( $return == FALSE )
		{
			//first count select
			$this->_selectFilmoviSaIzvestajima( true );
			$qc1 = $this->db->_compile_select();
			
			$this->db->_reset_select();
			
			//second count select
			$this->_selectFilmoviSaIzvestajima( true, true );
			$qc2 = $this->db->_compile_select();
			
			// total rows union
			$totalRows = $this->db->query( $qc1 . " UNION " . $qc2 )->num_rows();
			$this->db->_reset_select();
				
		}
		
		// first result select
	 	$this->_selectFilmoviSaIzvestajima( false, false );
		$qr1 = $this->db->_compile_select();
		
		$this->db->_reset_select();
		
		//second result select
		$this->_selectFilmoviSaIzvestajima( false, true );
			
		$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
		
		if( ! $return )
			$this->db->limit( $this->_limit, $this->getRowsOffset( $this->_page, $this->_limit ) );
			
		$qr2 = $this->db->_compile_select();
		
		// result union
		$query_result = $this->db->query( $qr1 . " UNION " . $qr2 );
		
		$total_result = array();

		if( $query_result )
			$total_result = $query_result->result_array();

		if( $return )
		{
			return $total_result;
		}
		else
		{

			foreach( $total_result as $v )
			{
				$v[ 'za_placanje' ] = number_format( $v[ 'za_placanje' ], $this->_decimal_point );
				$v[ 'osnovica' ] = number_format( $v[ 'osnovica' ], $this->_decimal_point );
				$v[ 'uplate_total' ] = number_format( $v[ 'uplate_total' ], $this->_decimal_point );

				$v[ 'ukupan_prihod' ] = number_format( $v[ 'ukupan_prihod' ], $this->_decimal_point );
				$v[ 'ukupan_prihod_eur' ] = number_format( $v[ 'ukupan_prihod_eur' ], $this->_decimal_point );
				$v[ 'ukupan_prihod_karte' ] = number_format( $v[ 'ukupan_prihod_karte' ], $this->_decimal_point );
				$v[ 'ukupan_prihod_karte_eur' ] = number_format( $v[ 'ukupan_prihod_karte_eur' ], $this->_decimal_point );
				$v[ 'ukupan_prihod_naocare' ] = number_format( $v[ 'ukupan_prihod_naocare' ], $this->_decimal_point );
				$v[ 'ukupan_prihod_naocare_eur' ] = number_format( $v[ 'ukupan_prihod_naocare_eur' ], $this->_decimal_point );

				$v[ 'euro_centi' ] = number_format( $v[ 'euro_centi' ], $this->_decimal_point );
				$v[ 'prosek_cena_karte_rsd' ] = number_format( $v[ 'prosek_cena_karte_rsd' ], $this->_decimal_point );
			}

			// dispatch
			$this->dispatchResultXml( $total_result, $totalRows  );				
		}
	}
	
	protected function _selectFilmoviSaIzvestajima( $count_only = true, $storno = NULL )
	{
		$td = "fakture";
		if( $storno == true ) $td = "fakture_storno";
		
		if( $count_only )
		{
			$this->db->select( "$td.*" );
		}
		else
		{
			
			$this->db->select( "
								$td.za_placanje AS za_placanje,
								$td.osnovica AS osnovica,
								$td.uplate_total AS uplate_total,
			
								DATE_FORMAT( CURDATE(), '%d/%m/%Y' ) AS danasnji_datum,
								( $td.za_placanje - $td.uplate_total ) AS dugovna_strana,
								DATEDIFF( CURDATE(), $td.rok_placanja  ) AS kasnjenje,
								
								DATE_FORMAT( zvanicna_gledanost.datum_z_gledanost_od, '%d/%m/%Y' ) AS datum_z_gledanost_od,
								DATE_FORMAT( zvanicna_gledanost.datum_z_gledanost_do, '%d/%m/%Y' ) AS datum_z_gledanost_do ,
								zvanicna_gledanost.ukupno_gledalaca,
								
								zvanicna_gledanost.ukupan_prihod AS ukupan_prihod,
								zvanicna_gledanost.ukupan_prihod_eur AS ukupan_prihod_eur,
								zvanicna_gledanost.ukupan_prihod_karte AS ukupan_prihod_karte,
								zvanicna_gledanost.ukupan_prihod_karte_eur AS ukupan_prihod_karte_eur,
								zvanicna_gledanost.ukupan_prihod_naocare AS ukupan_prihod_naocare,
								zvanicna_gledanost.ukupan_prihod_naocare_eur AS ukupan_prihod_naocare_eur,
								
								
								rokovnici.naocare_eurocent_rsd AS euro_centi,
								rokovnici.prosek_cena_karte_rsd AS prosek_cena_karte_rsd,

								filmovi.film_id,
								filmovi.naziv_filma", 
								false
						 	 );
						 	 
						 	 
		}
		
		$this->db->from( $td );
		
		$this->_joinFilmoviSaIzvestajima( $storno );
	}
	
	protected function _joinFilmoviSaIzvestajima( $storno = NULL )
	{
		$td = "fakture";
		if( $storno ) $td = "fakture_storno";
		
		
		$this->db->join( 'zvanicna_gledanost', "zvanicna_gledanost.z_gledanost_id = $td.z_gledanost_id", 'inner' );
		$this->db->join( 'filmovi', 'filmovi.film_id = zvanicna_gledanost.film_id', 'inner' );
		$this->db->join( 'rokovnici', 'rokovnici.rokovnik_id = zvanicna_gledanost.rokovnik_id', 'inner' );
		
			
		if( $this->_bioskop_alias_id )
		{
			$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
			$this->db->join( 'bioskop_aliases', 'bioskop_aliases.bioskop_alias_id = bioskopi.alias_bioskopa', 'inner' );
			$this->db->where( "bioskopi.alias_bioskopa", $this->_bioskop_alias_id );
		}
		
		if( $this->_tip_komitenta )
		{
			$this->db->where( "$td.tip", $this->_tip_komitenta );
		}
		
		
		$this->db->where( "zvanicna_gledanost.komitent_id", $this->_komitent_id );
		
		//$this->db->where( "zvanicna_gledanost.datum_z_gledanost_od >=", $this->_datum_od );
		//$this->db->where( "zvanicna_gledanost.datum_z_gledanost_od <=", $this->_datum_do );
		
		$this->db->where( "$td.datum_unosa_fakture >=", $this->_datum_od );
		$this->db->where( "$td.datum_unosa_fakture <=", $this->_datum_do );
		
	}
	
	public function getSumeFilmovaSaIzvestajima()
	{
		$this->db->select( "SUM( zvanicna_gledanost.ukupno_gledalaca ) AS ukupno_gledalaca,
							SUM( zvanicna_gledanost.ukupno_prodato_naocara ) AS ukupno_prodato_naocara,
							SUM( zvanicna_gledanost.ukupan_prihod ) AS ukupan_prihod,
							SUM( zvanicna_gledanost.ukupan_prihod_eur ) AS ukupan_prihod_eur,
							SUM( zvanicna_gledanost.ukupan_prihod_karte ) AS ukupan_prihod_karte,
							SUM( zvanicna_gledanost.ukupan_prihod_karte_eur ) AS ukupan_prihod_karte_eur,
							SUM( zvanicna_gledanost.ukupan_prihod_naocare ) AS ukupan_prihod_naocare,
							SUM( zvanicna_gledanost.ukupan_prihod_naocare_eur ) AS ukupan_prihod_naocare_eur,
							SUM( fakture.za_placanje ) AS za_placanje,
							SUM( fakture.osnovica ) AS najamnina_bez_pdv,
							SUM( fakture.uplate_total ) AS uplate_total,
							SUM( fakture.za_placanje ) - SUM( fakture.uplate_total ) AS dugovna_strana" 
						 );
		
		$this->_joinFilmoviSaIzvestajima();
		
		$d = $this->db->get( 'fakture' )->result_array();
		
		
		$this->db->select( "SUM( fakture_storno.za_placanje ) AS za_placanje,
							SUM( fakture_storno.osnovica ) AS najamnina_bez_pdv,
							SUM( fakture_storno.uplate_total ) AS uplate_total,
							SUM( fakture_storno.za_placanje ) - SUM( fakture_storno.uplate_total ) AS dugovna_strana" );
		
		
		$this->_joinFilmoviSaIzvestajima( true );
		
		$ds = $this->db->get( 'fakture_storno' )->result_array();
		
		if( $d && $ds )
		{
			$d[ 0 ][ 'za_placanje' ] = $d[ 0 ][ 'za_placanje' ] + $ds[ 0 ][ 'za_placanje' ];
			$d[ 0 ][ 'najamnina_bez_pdv' ] = $d[ 0 ][ 'najamnina_bez_pdv' ] + $ds[ 0 ][ 'najamnina_bez_pdv' ];
			$d[ 0 ][ 'uplate_total' ] = $d[ 0 ][ 'uplate_total' ] +- $ds[ 0 ][ 'uplate_total' ];
			$d[ 0 ][ 'dugovna_strana' ] = ( $d[ 0 ][ 'dugovna_strana' ] )  + ( $ds[ 0 ][ 'dugovna_strana' ] );
		}
		
		
		foreach( $d[0] as $k => $v )
		{
			$d[0][ $k ] = number_format( $v, $this->_decimal_point, '.', ',' );
		}
		
		$this->dispatchResultXml( $d, count( $d )  );
	} 
	
	public function lkNefakturisaniFilmoviRead( $return = FALSE )
	{
		
		if( ! $this->_sort_col_name )
			$this->_sort_col_name = 'filmovi.film_id';
			
		$this->db->select( "filmovi.film_id, 
							filmovi.naziv_filma,
							rokovnici.komitent_id,
							rokovnici.rokovnik_id,
							rokovnici.tip_raspodele,
							rokovnici.raspodela_iznos,
							rokovnici.suma_zarada_rsd as bruto_zarada" );
		
		$this->db->join( 'rokovnici', 'rokovnici.rokovnik_id = gledanost.rokovnik_id', 'inner' );
		$this->db->join( 'filmovi', 'filmovi.film_id = rokovnici.film_id', 'inner' );
		
		if( $this->_bioskop_alias_id )
		{
			$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
			$this->db->join( 'bioskop_aliases', 'bioskop_aliases.bioskop_alias_id = bioskopi.alias_bioskopa', 'inner' );
			$this->db->where( "bioskopi.alias_bioskopa", $this->_bioskop_alias_id );
		}
		
		$this->db->where( "gledanost.datum_gledanosti >=", $this->_datum_od );
		$this->db->where( "gledanost.datum_gledanosti <=", $this->_datum_do );
		
		if( $this->_producent )
		{
			$this->db->where( "filmovi.producent_filma", $this->_producent );
		}
			
		if( $this->_komitent_id )
		{
			$this->db->where( "rokovnici.komitent_id", $this->_komitent_id );
		}
		
		if( $this->_tip_komitenta )
		{
			$this->db->where( "rokovnici.tip", $this->_tip_komitenta );
		}
		
		if( $this->_film_id )
		{
			$this->db->where( "rokovnici.film_id", $this->_film_id );
		}
		
		
		$this->db->group_by( "gledanost.rokovnik_id" );
		
		$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
		
		$gledanost_data = $this->db->get( 'gledanost' )->result_array();
		
		$final_data = array();
		
		$neto = 0;
		
		foreach( $gledanost_data as $fd )
		{
			$this->db->select( 'zvanicna_gledanost.z_gledanost_id');
			
			$this->db->where( "zvanicna_gledanost.rokovnik_id", $fd[ "rokovnik_id"] );
			
			if( $this->db->get( "zvanicna_gledanost" )->num_rows() == 0 )
			{
				
				if( $fd[ 'tip_raspodele' ] == 3 )
				{
					$neto = $fd[ 'bruto_zarada' ] * $fd[ 'raspodela_iznos' ] / 100;
				}
				else
				{
					$neto = $fd[ 'raspodela_iznos' ];
				}
				
				if( ! $return )
				{
					$fd[ 'neto_zarada' ] = number_format( $neto, $this->_decimal_point, '.', ',' );
					$fd[ 'bruto_zarada' ] = number_format( $fd[ 'bruto_zarada' ], $this->_decimal_point, '.', ',' );
				}
				else
				{
					$fd[ 'neto_zarada' ] = $neto;
					$fd[ 'bruto_zarada' ] = $fd[ 'bruto_zarada' ];
				}
				
					
				array_push( $final_data, $fd );
			}
		}
		
		if( $return )
		{
			return $final_data;
		}
		else
		{
			$this->dispatchResultXml( $final_data, count( $final_data )  );
		}
		
	}
	
	public function lkNebukiraniFilmoviRead( $return = FALSE )
	{
		if( ! $this->_sort_col_name )
			$this->_sort_col_name = 'naziv_bioskopa';
			
		$this->db->select( "film_id, 
							naziv_filma, 
							DATE_FORMAT( start_filma, '%d/%m/%Y' ) AS start_filma", false );
			
		$this->db->where( "start_filma >=", $this->_datum_od );
		$this->db->where( "start_filma <=", $this->_datum_do );
		
		if( $this->_sort_col_name != 'naziv_bioskopa' )
			$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
		
		$filmovi_data = $this->db->get( 'filmovi' )->result_array();
		
		$final_data = array();
		
		$result = NULL;
		
		$bioskopi_aliases = $this->db->where( 'komitent_id', $this->_komitent_id )->get( 'bioskop_aliases' )->result_array();
		$bioskopi_sale = $this->db->where( 'komitent_id', $this->_komitent_id )->get( 'bioskopi' )->result_array();

		$bioskop_alias_result = null;
		
		if( $this->_komitent_id > 0 )
		{
			foreach( $filmovi_data as $fd )
			{
				
				if( $this->_bioskop_alias_id )
				{
					$this->_joinNebukiraniFilmovi( $fd[ "film_id"] );
					
					$this->db->where( "bioskopi.alias_bioskopa", $this->_bioskop_alias_id );
					$result = $this->db->get( "kopije_zakljucnice" );
					
					if( $result->num_rows() == 0 )
					{
						$fd[ 'naziv_bioskopa' ] = $this->_getBioskopAliasName( $this->_bioskop_alias_id );
						array_push( $final_data, $fd );
					}
					
					$result->free_result();
				}
				else 
				{
					if( is_array( $bioskopi_aliases ) && count( $bioskopi_aliases ) > 0 )
					{
						foreach ( $bioskopi_aliases as $a )
						{
							$this->_joinNebukiraniFilmovi( $fd[ "film_id"] );
							
							$this->db->where( "bioskopi.alias_bioskopa", $a[ 'bioskop_alias_id' ] );
							
							if( $this->db->get( "kopije_zakljucnice" )->num_rows() == 0 )
							{
								$fd[ 'naziv_bioskopa' ] = $this->_getBioskopAliasName( $a[ 'bioskop_alias_id' ] );
								array_push( $final_data, $fd );
							}
						}
					}
					else if( is_array( $bioskopi_sale ) && count( $bioskopi_sale ) > 0  )
					{
						foreach ( $bioskopi_sale as $a )
						{
							$this->_joinNebukiraniFilmovi( $fd[ "film_id"] );
							
							$this->db->where( "bioskopi.bioskop_id", $a[ 'bioskop_id' ] );
							
							if( $this->db->get( "kopije_zakljucnice" )->num_rows() == 0 )
							{
								$fd[ 'naziv_bioskopa' ] = $a[ 'naziv_bioskopa' ];
								array_push( $final_data, $fd );
							}
						}
					}
				}
			}
		}
		
		$total_records = count( $final_data );
		
		$sorted_data = $final_data;
		
		if( $this->_sort_col_name == 'naziv_bioskopa' && $this->_sort_col_name && $this->_sort_order_name )
		{
			$sort_bioskope = array();
			
			foreach( $sorted_data as $val ) 
			{
		        $sort_bioskope[] = $val['naziv_bioskopa'];
	    	}
    
	    	$sort_order = $this->_sort_order_name == 'asc' ? SORT_ASC : SORT_DESC;
	    	
			array_multisort( $sort_bioskope, $sort_order, $sorted_data );
		}
		
		
		if( $return )
		{
			return $sorted_data;
		}
		else
		{
			$paged_data = array_slice( $sorted_data, $this->getRowsOffset( $this->_page, $this->_limit ), $this->_limit );
			$this->dispatchResultXml( $paged_data, $total_records );			
		}
		
	}
	
	protected function _getBioskopAliasName( $id )
	{
		$this->db->select( 'bioskop_aliases.bioskop_alias_name' );
		$this->db->where( "bioskop_alias_id", $id );
		
		$bioskop_alias_result = $this->db->get( 'bioskop_aliases' );
					
		if( $bioskop_alias_result && $bioskop_alias_result->num_rows() > 0 )
		{
			return $bioskop_alias_result->row( 0 )->bioskop_alias_name;
		}
		else
		{
			return "";
		}
							
	}
	
	protected function _joinNebukiraniFilmovi( $film_id )
	{
		$this->db->join( 'zakljucnice', 'zakljucnice.zakljucnica_id = kopije_zakljucnice.zakljucnica_id', 'inner' );
		$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = kopije_zakljucnice.bioskop_id', 'inner' );
		$this->db->join( 'bioskop_aliases', 'bioskop_aliases.bioskop_alias_id = bioskopi.alias_bioskopa', 'left' );
		$this->db->where( "kopije_zakljucnice.film_id", $film_id );
		$this->db->where( "zakljucnice.komitent_id", $this->_komitent_id );
	}
	
	public function lkIzvestajPoBioskopu( $return = FALSE )
	{
		if( ! $this->_sub_sume )
		{
			//first count select
			$this->_selectIzvestajiPoBioskpu( true );
			
			
			$qc1 = $this->db->_compile_select();
			
			$this->db->_reset_select();
			
			//second count select
			$this->_selectIzvestajiPoBioskpu( true, true );
			
			//$this->db->group_by( "bioskop_id" );
			
			$qc2 = $this->db->_compile_select();
			
			// total rows union
			$totalRows = $this->db->query( $qc1 . " UNION " . $qc2 )->num_rows();
		}
		
		
		$this->db->_reset_select();
		
		// first result select
	 	$this->_selectIzvestajiPoBioskpu( false );
		$qr1 = $this->db->_compile_select();
		
		$this->db->_reset_select();
		
		//second result select
		$this->_selectIzvestajiPoBioskpu( false, true );
		
		
		$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
		
		
		$qr2 = $this->db->_compile_select();
		
		
		// result union
		$query_result = $this->db->query( $qr1 . " UNION " . $qr2 );
		
		$suma = array();
		$keys = array();
		
		$result = $query_result->result_array();
		
		$formated_result = array();
		$grouped_result = array();
		$temp = null;
		
		if( $this->_sub_sume )
		{
			foreach ( $result as $row ) 
			{
				if( array_key_exists( $row[ 'bioskop_alias_id' ], $grouped_result ) )
				{
					$temp = $grouped_result[ $row[ 'bioskop_alias_id' ] ];
					
					$temp[ 'bruto_zarada' ] = $temp[ 'bruto_zarada' ] +  $row[ 'bruto_zarada' ];
					$temp[ 'neto_zarada' ] = $temp[ 'neto_zarada' ] +  $row[ 'neto_zarada' ];
					$temp[ 'broj_gledalaca' ] = $temp[ 'broj_gledalaca' ] +  $row[ 'broj_gledalaca' ];
					
					$grouped_result[ $row[ 'bioskop_alias_id' ] ] = $temp;
					
				}
				else 
				{
					$grouped_result[ $row[ 'bioskop_alias_id' ] ] = $row;
				}
			}
			
			
			
			foreach ( $grouped_result as $row )
			{
				$row[ 'bruto_zarada' ] = number_format( $row[ 'bruto_zarada' ], $this->_decimal_point, '.', ',' );
				$row[ 'neto_zarada' ] = number_format( $row[ 'neto_zarada' ], $this->_decimal_point, '.', ',' );
				
				$formated_result[ $row[ 'bioskop_alias_id' ] ] = $row;
			}
			
			// dispatch
			$this->dispatchResultXml( $formated_result, count( $formated_result )  );	
		} 
		// end if sume was requested
		else
		{
			
			$key = '';
			
			// group  movies for one bioskop
			foreach( $result as $row ) 
			{
				$key = $row[ 'bioskop_alias_id'] . $row[ 'film_id' ];
				
				if( array_key_exists( $key, $grouped_result ) )
				{
					$temp = $grouped_result[ $key ];
					
					$temp[ 'bruto_zarada' ] = $temp[ 'bruto_zarada' ] +  $row[ 'bruto_zarada' ];
					$temp[ 'neto_zarada' ] = $temp[ 'neto_zarada' ] +  $row[ 'neto_zarada' ];
					$temp[ 'broj_gledalaca' ] = $temp[ 'broj_gledalaca' ] +  $row[ 'broj_gledalaca' ];
					
					$grouped_result[ $key ] = $temp;
				}
				else 
				{					
					$grouped_result[ $key ] = $row;
				}
			}
			
			
			$formated_result = array();
			
			foreach ( $grouped_result as $row )
			{
				$row[ 'bruto_zarada' ] = number_format( $row[ 'bruto_zarada' ], $this->_decimal_point, '.', ',' );
				$row[ 'neto_zarada' ] = number_format( $row[ 'neto_zarada' ], $this->_decimal_point, '.', ',' );
				
				$formated_result[] = $row;
			}
			
			$total_records = count( $formated_result );
			
			$this->dispatchResultXml( array_slice( $formated_result, $this->getRowsOffset( $this->_page, $this->_limit ), $this->_limit ), $total_records );
			
		}
		
		
	}
	
	protected function _selectIzvestajiPoBioskpu( $count_only = true, $storno = NULL )
	{
		$td = "fakture";
		if( $storno ) $td = "fakture_storno";
		
		if( $count_only )
		{
			$this->db->select( "$td.*" );
		}
		else
		{
			
			
			if( $this->_sub_sume )
			{
				$this->db->select( "CURDATE() as danasnji_datum,
				
									SUM( zvanicna_gledanost.ukupno_gledalaca ) AS broj_gledalaca,
									
									filmovi.film_id,
									filmovi.naziv_filma,
									bioskopi.bioskop_id,
									bioskop_aliases.*,
									$td.faktura_id,
									
									SUM( $td.ukupan_prihod ) AS bruto_zarada,
									SUM( $td.osnovica ) AS neto_zarada",
				
									false 
						 	 	 );
			}
			else
			{
				$this->db->select( "$td.*, 
									CURDATE() as danasnji_datum,
									zvanicna_gledanost.ukupno_gledalaca AS broj_gledalaca,
									filmovi.naziv_filma,
									filmovi.film_id,
									bioskopi.bioskop_id,
									bioskop_aliases.*,
									$td.ukupan_prihod AS bruto_zarada,
									$td.osnovica AS neto_zarada",
									false	 
						 	 	 );
			}
		}
		
		
		$this->db->from( $td );
		
		$this->_joinIzvestajiPoBioskopu( $storno );
		
		if( $this->_sub_sume )
		{
			$this->db->group_by( "bioskopi.alias_bioskopa" );
		}
		
	}
	
	protected function _joinIzvestajiPoBioskopu( $storno = NULL )
	{
		$td = "fakture";
		if( $storno ) $td = "fakture_storno";
		
		$this->db->join( 'zvanicna_gledanost', "zvanicna_gledanost.z_gledanost_id = $td.z_gledanost_id", 'inner' );
		$this->db->join( 'filmovi', 'filmovi.film_id = zvanicna_gledanost.film_id', 'inner' );
		$this->db->join( 'rokovnici', 'rokovnici.rokovnik_id = zvanicna_gledanost.rokovnik_id', 'inner' );
		$this->db->join( 'kopije_filma', 'kopije_filma.kopija_id = rokovnici.kopija_id', 'inner' );
		$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
		$this->db->join( 'bioskop_aliases', 'bioskop_aliases.bioskop_alias_id = bioskopi.alias_bioskopa', 'inner' );
			
		$this->db->where( "zvanicna_gledanost.komitent_id", $this->_komitent_id );
		
		if( $this->_tip_komitenta )
		{
			$this->db->where( "fakture.tip", $this->_tip_komitenta );
		}
		
		if( $this->_tehnika_kopije_filma )
		{
			$this->db->where( "kopije_filma.tehnika_kopije_filma", $this->_tehnika_kopije_filma );
		}
		
		$this->db->where( "$td.datum_unosa_fakture >=", $this->_datum_od );
		$this->db->where( "$td.datum_unosa_fakture <=", $this->_datum_do );
		
		if( $this->_bioskop_alias_id )
		{
			$this->db->where( "bioskopi.alias_bioskopa", $this->_bioskop_alias_id );
		}
		
	}
	
	public function getSumeIzvestajiPoBioskopu()
	{
		$this->db->select( "SUM( fakture.ukupan_prihod ) AS bruto_zarada,
							SUM( zvanicna_gledanost.ukupno_gledalaca ) AS broj_gledalaca, 
							SUM( fakture.osnovica ) AS neto_zarada,"
						 );
		
		$this->_joinIzvestajiPoBioskopu();
		
		$d = $this->db->get( 'fakture' )->result_array();
		
		
		$this->db->select( "SUM( fakture_storno.ukupan_prihod ) AS bruto_zarada,
						    SUM( zvanicna_gledanost.ukupno_gledalaca ) AS broj_gledalaca,	
							SUM( fakture_storno.osnovica ) AS neto_zarada,"
						 );
		
		$this->_joinIzvestajiPoBioskopu( true );
		
		$ds = $this->db->get( 'fakture_storno' )->result_array();
		
		if( $d && $ds )
		{
			$d[ 0 ][ 'bruto_zarada' ] = $d[ 0 ][ 'bruto_zarada' ] + $ds[ 0 ][ 'bruto_zarada' ];
			$d[ 0 ][ 'neto_zarada' ] = $d[ 0 ][ 'neto_zarada' ] + $ds[ 0 ][ 'neto_zarada' ];
			$d[ 0 ][ 'broj_gledalaca' ] = $d[ 0 ][ 'broj_gledalaca' ] - $ds[ 0 ][ 'broj_gledalaca' ];
		}
		
		
		foreach( $d[0] as $k => $v )
		{
			$d[0][ $k ] = number_format( $v, $this->_decimal_point, '.', ',' );
		}
		
		$this->dispatchResultXml( $d, count( $d )  );
	}
	
	public function finansijskiPrometFilma( $return = FALSE )
	{
		if( ! $this->_sort_col_name )
			$this->_sort_col_name = 'naziv_filma';

		if( ! $this->_sort_order_name )
			$this->_sort_order_name = 'asc';
				
		if( $return == FALSE )
		{
			//first count select
			$this->_selectFinansijskiPromet( true );
			$qc1 = $this->db->_compile_select();
			
			$this->db->_reset_select();
			
			//second count select
			$this->_selectFinansijskiPromet( true, true );
			$qc2 = $this->db->_compile_select();
			
			// total rows union
			$totalRows = $this->db->query( $qc1 . " UNION " . $qc2 )->num_rows();
			$this->db->_reset_select();
		}
		
		// first result select
	 	$this->_selectFinansijskiPromet( false, false );
		$qr1 = $this->db->_compile_select();
		
		$this->db->_reset_select();
		
		//second result select
		$this->_selectFinansijskiPromet( false, true );

		
		$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
		
		if( $return == FALSE && ! $this->_sub_sume )
			$this->db->limit( $this->_limit, $this->getRowsOffset( $this->_page, $this->_limit ) );
			
		$qr2 = $this->db->_compile_select();
		
		// result union
		$query_result = $this->db->query( $qr1 . " UNION " . $qr2 );
		
		$result = $query_result->result_array();
		
		
		$converted_data = array();
		$eur = 1;
		$gledanost = NULL;
		
		// BUG IN CI WHEN USING COMPILE SELECT
		$this->db->close();
		$this->db = NULL;
		$this->load->database( 'default' );
		
		
		$suma_neto = 0;
		$suma_neto_sa_pdv = 0;
		$suma_bruto = 0;
		$suma_gledalaca = 0;
		$suma_raspodela = 0;
																											
		foreach( $result as $row )
		{
			
			
			if( $row[ 'valuta_fakture' ] == 2 )
			{
				$eur = $this->db->select( 'eur' )->where( 'datum_kursa', $row[ 'vdate' ] )->get( 'kursna_lista' )->row( 0 )->eur;
			}
			else 
			{
				$eur = 1;
			}
			
			
			$row[ 'neto' ] = $row[ 'neto' ] * $eur; 
			$row[ 'neto_sa_pdv' ] = $row[ 'neto_sa_pdv' ] * $eur;
			$row[ 'bruto' ] = $row[ 'bruto' ] * $eur;
			
			
			$this->db->where( 'gledanost.datum_gledanosti >=', $row[ 'datum_z_gledanost_od' ] );
			$this->db->where( 'gledanost.datum_gledanosti <=', $row[ 'datum_z_gledanost_do' ] );
			$this->db->where( 'gledanost.rokovnik_id', $row[ 'rokovnik_id' ] );
			
			$this->db->join( 'gledanost_termini', 'gledanost_termini.gledanost_id = gledanost.gledanost_id', 'inner' );
	
			
			$row[ 'broj_termina' ] = $this->db->count_all_results( 'gledanost' );
			
			$suma_neto = $suma_neto + $row[ 'neto' ];
			$suma_neto_sa_pdv = $suma_neto_sa_pdv + $row[ 'neto_sa_pdv' ];
			$suma_bruto = $suma_bruto + ( $row[ 'bruto' ] );
			$suma_gledalaca = $suma_gledalaca + $row[ 'broj_gledalaca' ];
			
			if( $row[ 'tip_raspodele' ] != 3 )
			{
				$suma_raspodela += $row[ 'raspodela_iznos' ]; 
			}
			
			array_push( $converted_data, $row );
			
		}
		
		if( $return )
		{
			return array(  'data' => $converted_data,
						   'naziv_filma' => $this->_naziv_filma,
						   'suma_neto' => $suma_neto,	
						   'suma_neto_sa_pdv' => $suma_neto_sa_pdv,	
						   'suma_bruto' => $suma_bruto,	
						   'suma_raspodela' => $suma_raspodela,
						   'suma_gledalaca' => $suma_gledalaca );
		}
		else
		{
			// dispatch
			if( $this->_sub_sume == 'true' )
			{
				$sume = array( 'suma_neto' => number_format( $suma_neto, $this->_decimal_point ),	
							   'suma_neto_sa_pdv' => number_format( $suma_neto_sa_pdv, $this->_decimal_point ),	
							   'suma_bruto' => number_format( $suma_bruto, $this->_decimal_point ),	
							   'suma_gledalaca' => number_format( $suma_gledalaca, 0 ) );
				
				$this->dispatchResultXml( $sume, 4  );
			}
			else
			{
				$this->dispatchResultXml( $converted_data, $totalRows  );
			}				
		}
	}
	
	protected function _selectFinansijskiPromet( $count_only = true, $storno = NULL )
	{
		$td = "fakture";
		$td_stavke = "fakture_stavke";
		
		if( $storno == true ) 
		{
			$td = "fakture_storno";
			$td_stavke = "fakture_stavke_storno";
		}
		
		if( $count_only )
		{
			$this->db->select( "$td.*" );
		}
		else
		{
			
			$this->db->select( "zvanicna_gledanost.datum_z_gledanost_od,
								zvanicna_gledanost.datum_z_gledanost_do,
								DATE_FORMAT( zvanicna_gledanost.datum_z_gledanost_od, '%d/%m/%Y'  ) AS datum_z_gledanost_od_stampa,
								DATE_FORMAT( zvanicna_gledanost.datum_z_gledanost_do, '%d/%m/%Y'  ) AS datum_z_gledanost_do_stampa,
								filmovi.film_id,
								filmovi.naziv_filma,
								filmovi.producent_filma,
								DATE_FORMAT( filmovi.start_filma, '%d/%m/%Y' ) AS start_filma,
								komitenti.naziv_komitenta,
								bioskopi.naziv_bioskopa,
								kopije_filma.oznaka_kopije_filma,
								kopije_filma.serijski_broj_kopije,
								
								$td.vdate,
								$td_stavke.broj_gledalaca,
								$td.valuta_fakture,
								$td.osnovica AS neto,
								$td.za_placanje AS neto_sa_pdv,
								$td.ukupan_prihod AS bruto,
								DATE_FORMAT( $td.datum_prometa, '%d/%m/%Y' ) AS datum_prometa,
								$td.broj_dokumenta_fakture,
								
								rokovnici.tip_raspodele,
								rokovnici.rokovnik_id,
								rokovnici.raspodela_iznos,
								rokovnici.broj_dokumenta_rokovnika",
			 
								false
						 	 );
						 	 
						 	 
		}
		
		$this->db->from( $td );
		
		$this->_joinFinansijskiPrometFilma( $storno );
	}	
	
	public function _joinFinansijskiPrometFilma( $storno )
	{
		$td = "fakture";
		$td_stavke = "fakture_stavke";
		
		if( $storno ) 
		{
			$td = "fakture_storno";
			$td_stavke = "fakture_stavke_storno";
		}
		
		
		$this->db->join( 'zvanicna_gledanost', "zvanicna_gledanost.z_gledanost_id = $td.z_gledanost_id", 'inner' );
		$this->db->join( 'filmovi', 'filmovi.film_id = zvanicna_gledanost.film_id', 'inner' );
		$this->db->join( 'rokovnici', 'rokovnici.rokovnik_id = zvanicna_gledanost.rokovnik_id', 'inner' );
		$this->db->join( 'kopije_filma', 'kopije_filma.kopija_id = rokovnici.kopija_id', 'inner' );
		$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );	
		$this->db->join( 'komitenti', 'komitenti.komitent_id = zvanicna_gledanost.komitent_id', 'inner' );
		$this->db->join( $td_stavke, "$td_stavke.faktura_id = $td.faktura_id", 'inner' );
		
		if( $this->_tip_komitenta )
		{
			$this->db->where( "$td.tip", $this->_tip_komitenta );
		}
		
		
		if( $this->_film_id  && $this->_film_id != 1 )
		{
			$this->db->where( 'filmovi.film_id', $this->_film_id );
		}
		
		$this->db->where( 'zvanicna_gledanost.stornirana', 0 );
		$this->db->where( "$td_stavke.redni_broj_stavke", 1 );
			
		$this->db->where( "$td.datum_unosa_fakture >=", $this->_datum_od );
		$this->db->where( "$td.datum_unosa_fakture <=", $this->_datum_do );
	}

	
	public function prikaziLicnuKartu()
	{
		set_time_limit ( 5000 );
		ini_set ( 'memory_limit', '1024M' );
		$error_level = error_reporting();

		
		require_once 'pdf/mpdf.php';
		
		$mpdf = new mPDF('utf-8',    // mode - default ''
		'',    // format - A4, for example, default ''
		0,     // font size - default 0
		'Verdana',    // default font family
		5,    // margin_left
		5,    // margin right
		5,     // margin top
		5,    // margin bottom
		9,     // margin header
		5,     // margin footer
		'L');  // L - landscape, P - portrait 

		
		error_reporting( 0 );


		$mpdf->SetHTMLFooter( $this->load->view( 'finPrometFooter', null, true ) );
		$mpdf->WriteHTML( file_get_contents('resources/css/finPrometIzvestaj.css'), 1 );

		$html = '';
		$this->_decimal_point = 2;
		
		$bioskop = 'n/a';
		if( $this->_bioskop_alias_id )
			$bioskop = $this->_getBioskopAliasName( $this->_bioskop_alias_id );
			
			
		//$html .= "<p>Datum: " . $this->_datum_do . " - " . $this->_datum_do . " Bioskop: $bioskop<h2>" . $_POST['naziv_komitenta']."</h2></p>";
		//$html .=  "<p> - Tip: " . $this->_tip_komitenta . "</p>";
		
		

		// ODIGRANI FILMOVI
		$html .= "<table class='tableizer-table' width='100%'>";
		$html .= "<tr>
					<th align='center'><b>Odigrani filmovi</b></th>
					<th align='center'><b>Komitent: $this->_naziv_komitenta</b></th>
					<th align='center'><b>Bioskop: $bioskop</b></th>
					<th align='center'><b>Datum: $this->_datum_do - $this->_datum_do</b></th>

				 </tr></table>";
		$html .= "<table class='tableizer-table' width='100%'><tr><th>Film</th></tr>";
		$html .= "</tr></table>";
		
		$mpdf->setHTMLHeader( $html );
		
		$html = '<table width="100%" class="tableizer-table">';

		$this->_sort_col_name = '';
		$odigrani_filmovi = $this->lkOdigraniFilmoviRead( TRUE );
		
		foreach( $odigrani_filmovi as $r )
		{
			$html .= '<tr><td>' . $r['naziv_filma'] . '</td></tr>'; 
		}
		
		$html .= "</table>";
	
		$mpdf->AddPage('L','','','','',5,5,20,10,5,5);
		$mpdf->WriteHTML( $html );
		

		// FILMOVI BEZ IZVESTAJA
		$html = "<table class='tableizer-table' width='100%'>";
		$html .= "<tr>
					<th align='center'><b>Filmovi bez izvestaja</b></th>
					<th align='center'><b>Komitent: $this->_naziv_komitenta</b></th>
					<th align='center'><b>Bioskop: $bioskop</b></th>
					<th align='center'><b>Datum: $this->_datum_do - $this->_datum_do</b></th>
			     </tr></table>";
		$html .= "<table class='tableizer-table' width='100%'>
					<tr>
						<th width='30%'>Film</th>
						<th>Bioskop</th>
						<th>Rokovnik</th>
						<th>Kopija</th>
						<th>Datum</th>
					</tr></table>";
		
		
		$mpdf->setHTMLHeader( $html );
		
		$html = '<table width="100%" class="tableizer-table">';

		$this->_sort_col_name = '';
		$filmovi_bez_izvestaja = $this->lkFilmoviBezIzvestajaRead( TRUE );
		
		foreach( $filmovi_bez_izvestaja as $r )
		{
			
			$html .= '<tr><td width="30%">' . $r['naziv_filma'] . '</td>'; 
			$html .= '<td>' . $r['bioskop'] . '</td>';
			$html .= '<td>' . $r['rokovnik_id'] . '</td>';
			$html .= '<td width="15%">' . $r['serijski_broj_kopije'] . '</td>';
			$html .= '<td>' . $r['datum']. '</td></tr>';
		}
		
		$html .= "</table>";
	
		$mpdf->AddPage('L','','','','',5,5,20,10,5,5);
		$mpdf->WriteHTML( $html );


		// TOP LISTA FILMOVA
		$html = "<table class='tableizer-table' width='100%'>";
		$html .= "<tr>
					<th colspan='4' align='center'><b>Top lista filmova</b></th>
					<th align='center'><b>Komitent: $this->_naziv_komitenta</b></th>
					<th align='center'><b>Bioskop: $bioskop</b></th>
					<th align='center'><b>Datum: $this->_datum_do - $this->_datum_do</b></th>
				  </tr></table>";
		$html .= "<table class='tableizer-table' width='100%'>
				  <tr>
				  	<th width='40%'>Film</th>
				  	<th>Gledalaca</th>
				  	<th>Bruto</th>
				  	<th>Neto</th>
				  </tr></table>";
	
		$mpdf->setHTMLHeader( $html );
		
		$html = '<table width="100%" class="tableizer-table">';

		$this->_sort_col_name = 'ukupno_gledalaca';
		$this->_sort_order_name = "desc";
		
		$top_lista_filmova = $this->lkTopListaFilmovaRead( TRUE );
		
		$gledalaca = 0;
		$bruto_zarada = 0;
		$neto_zarada = 0;

		foreach( $top_lista_filmova as $r )
		{
			
			$html .= '<tr><td width="40%">' . $r['naziv_filma'] . '</td>'; 
			$html .= '<td>' . number_format( $r['ukupno_gledalaca'], $this->_decimal_point ) . '</td>';
			$html .= '<td>' . number_format( $r['bruto_zarada'], $this->_decimal_point ) . '</td>';
			$html .= '<td>' . number_format( $r['neto_zarada'], $this->_decimal_point ) . '</td></tr>';

			$gledalaca += $r['ukupno_gledalaca'];
			$bruto_zarada += $r['bruto_zarada'];
			$neto_zarada += $r['neto_zarada'];
		}
		
		$gledalaca = number_format( $gledalaca, $this->_decimal_point );
		$bruto_zarada = number_format( $bruto_zarada, $this->_decimal_point );
		$neto_zarada = number_format( $neto_zarada, $this->_decimal_point );

		$html .= "<tr class='sume-row'><td>Ukupno</td><td>$gledalaca</td><td>$bruto_zarada</td><td>$neto_zarada</td></tr>";

		$html .= "</table>";
		

		$mpdf->AddPage('L','','','','',5,5,20,10,5,5);
		$mpdf->WriteHTML( $html );
		

		// FILMOVI SA IZVESTAJEM
		$html = "<table class='tableizer-table' width='100%'>";
		$html .= "<tr>
					<th colspan='9' align='center'><b>Filmovi sa izvestajem</b></th>
					<th align='center'><b>Komitent: $this->_naziv_komitenta</b></th>
					<th align='center'><b>Bioskop: $bioskop</b></th>
					<th align='center'><b>Datum: $this->_datum_do - $this->_datum_do</b></th>
				 </tr></table>";
		$html .= "<table class='tableizer-table' width='100%'>
					<tr>
					  <th width='20%'>Film</th>
					  <th>Broj Gledalaca</th>
					  <th>Bruto</th>
					  <th>Zarada Filma</th>
					  <th>Zarada Naocara</th>
					  <th>Neto Zarada</th>
					  <th>Placeno</th>
					  <th>Dugovna strana</th>
					  <th>Kasnjenje</th>";
		$html .= "</tr></table>";
		
		$mpdf->setHTMLHeader( $html );
		
		$html = '<table width="100%" class="tableizer-table">';

		$this->_sort_col_name = '';
		$filmovi_sa_izvestajem = $this->lkFilmoviSaIzvestajimaRead( TRUE );
		
		$gledalaca = 0;
		$prihod = 0;
		$prihod_karte = 0;
		$prihod_naocare = 0;
		$za_placanje = 0;
		$uplate_total = 0;
		$dugovna_strana = 0;
		

		foreach( $filmovi_sa_izvestajem as $r )
		{
			
			$html .= '<tr><td width="20%">' . $r['naziv_filma'] . '</td>'; 
			$html .= '<td>' . number_format( $r['ukupno_gledalaca'], $this->_decimal_point ) . '</td>';
			$html .= '<td>' . number_format( $r['ukupan_prihod'], $this->_decimal_point ) . '</td>';
			$html .= '<td>' . number_format( $r['ukupan_prihod_karte'], $this->_decimal_point ) . '</td>';
			$html .= '<td>' . number_format( $r['ukupan_prihod_naocare'], $this->_decimal_point ) . '</td>';
			$html .= '<td>' . number_format( $r['za_placanje'], $this->_decimal_point ) . '</td>';
			$html .= '<td>' . number_format( $r['uplate_total'], $this->_decimal_point ) . '</td>';
			$html .= '<td>' . number_format( $r['dugovna_strana'], $this->_decimal_point ) . '</td>';
			$html .= '<td>' . number_format( $r['kasnjenje'], $this->_decimal_point ) . '</td></tr>';

			$gledalaca += $r['ukupno_gledalaca'];
			$prihod += $r['ukupan_prihod'];
			$prihod_karte += $r['ukupan_prihod_karte'];
			$prihod_naocare += $r['ukupan_prihod_naocare'];
			$za_placanje += $r['za_placanje'];
			$uplate_total += $r['uplate_total'];
			$dugovna_strana += $r['dugovna_strana'];
		}
		
		$gledalaca = number_format( $gledalaca, $this->_decimal_point );
		$prihod = number_format( $prihod, $this->_decimal_point );
		$prihod_karte = number_format( $prihod_karte, $this->_decimal_point );
		$prihod_naocare = number_format( $prihod_naocare, $this->_decimal_point );
		$za_placanje = number_format( $za_placanje, $this->_decimal_point );
		$uplate_total = number_format( $uplate_total, $this->_decimal_point );
		$dugovna_strana = number_format( $dugovna_strana, $this->_decimal_point );

		$html .= "<tr class='sume-row'>
					<td>Ukupno</td>
					<td>$gledalaca</td>
					<td>$prihod</td>
					<td>$prihod_karte</td>
					<td>$prihod_naocare</td>
					<td>$za_placanje</td>
					<td>$uplate_total</td>
					<td>$dugovna_strana</td>
					<td></td></tr>";

		$html .= "</table>";
		
		$mpdf->AddPage('L','','','','',5,5,20,10,5,5);
		$mpdf->WriteHTML( $html );

		// BUUUUUUUUUUUUG
		$this->db->close();
		$this->db = null;
		$this->load->database('default');
		
		// NEFAKTURISANI FILMOVI
		$html = "<table class='tableizer-table' width='100%'>";
		$html .= "<tr>
					<th colspan='3' align='center'><b>Nefakturisani filmovi</b></th>
					<th align='center'><b>Komitent: $this->_naziv_komitenta</b></th>
					<th align='center'><b>Bioskop: $bioskop</b></th>
					<th align='center'><b>Datum: $this->_datum_do - $this->_datum_do</b></th>
					</tr></table>";
		$html .= "<table class='tableizer-table' width='100%'>
					<tr>
						<th width='50%'>Film</th>
						<th width='25%'>Bruto</th>
						<th width='25%'>Neto</th>";
		$html .= "</tr></table>";
		
		$mpdf->setHTMLHeader( $html );
		
		$html = '<table width="100%" class="tableizer-table">';

		$this->_sort_col_name = '';
		$nefakturisani_filmovi = $this->lkNefakturisaniFilmoviRead( TRUE );
		
		
		$bruto_zarada = 0;
		$neto_zarada = 0;

		foreach( $nefakturisani_filmovi as $r )
		{
			
			$html .= '<tr><td width="50%">' . $r['naziv_filma'] . '</td>'; 
			$html .= '<td width="25%">' . number_format( $r['bruto_zarada'], $this->_decimal_point ) . '</td>';
			$html .= '<td width="25%">' . number_format( $r['neto_zarada'], $this->_decimal_point ) . '</td></tr>';

			$bruto_zarada += $r['bruto_zarada'];
			$neto_zarada += $r['neto_zarada'];
		}
		
		$bruto_zarada = number_format( $bruto_zarada, $this->_decimal_point );
		$neto_zarada = number_format( $neto_zarada, $this->_decimal_point );

		$html .= "<tr class='sume-row'><td>Ukupno</td><td>$bruto_zarada</td><td>$neto_zarada</td></tr>";
		$html .= "</table>";
		
		$mpdf->AddPage('L','','','','',5,5,20,10,5,5);
		$mpdf->WriteHTML( $html );		
		
		// NEBUKIRANI FILMOVI
		$html = "<table class='tableizer-table' width='100%'>";
		$html .= "<tr>
					<th align='center'><b>Nebukirani filmovi</b></th>
					<th align='center'><b>Odigrani filmovi</b></th>
					<th align='center'><b>Komitent: $this->_naziv_komitenta</b></th>
					<th align='center'><b>Bioskop: $bioskop</b></th>
					<th align='center'><b>Datum: $this->_datum_do - $this->_datum_do</b></th>
				  </tr></table>";
		$html .= "<table class='tableizer-table' width='100%'>
				  <tr>
				  	<th width='50%'>Film</th>
				  	<th width='25%'>Bioskop</th>
				  	<th width='25%'>Start</th>
				  </tr>
				 </table>";
		

		$mpdf->setHTMLHeader( $html );
		
		$html = '<table width="100%" class="tableizer-table">';

		$this->_sort_col_name = '';
		$nebukirani_filmovi = $this->lkNebukiraniFilmoviRead( TRUE );
		
		foreach( $nebukirani_filmovi as $r )
		{
			
			$html .= '<tr><td width="50%">' . $r['naziv_filma'] . '</td>'; 
			$html .= '<td width="25%">' . $r['naziv_bioskopa'] . '</td>'; 
			$html .= '<td width="25%">' . $r['start_filma'] . '</td></tr>';
		}
		
		$html .= "</table>";
		
		$mpdf->AddPage('L','','','','',5,5,20,10,5,5);
		$mpdf->WriteHTML( $html );

		//echo $html;
		//return;
		
		$mpdf->Output();

		error_reporting( $error_level );

	}

	public function prikaziFinansijskiIzvestaj()
	{
		set_time_limit ( 5000 );
		ini_set ( 'memory_limit', '1024M' );
	
		$this->_decimal_point = 2;
		$data = $this->finansijskiPrometFilma( true );
		$data[ 'datum_od' ] = $this->_datum_od;
		$data[ 'datum_do' ] = $this->_datum_do;
		
		$header = $this->load->view( 'finPrometHeader', $data, true );
		$footer = $this->load->view( 'finPrometFooter', null, true );

		$error_level = error_reporting();

		require_once 'pdf/mpdf.php';
		
		error_reporting(0);

		$mpdf = new mPDF('utf-8',    // mode - default ''
		'A4',    // format - A4, for example, default ''
		0,     // font size - default 0
		'Verdana',    // default font family
		5,    // margin_left
		5,    // margin right
		30,     // margin top
		9,    // margin bottom
		3,     // margin header
		3,     // margin footer
		'L');  // L - landscape, P - portrait 
		


		$mpdf->SetHTMLHeader($header);
		$mpdf->AddPage('L');
		$mpdf->WriteHTML( file_get_contents('resources/css/finPrometIzvestaj.css'), 1 );
		
		

		$mpdf->SetHTMLFooter($footer);
		$mpdf->WriteHTML( $this->load->view( 'finansijskiPrometStampa', $data, true ) );

		$mpdf->Output();

		error_reporting($error_level);

		return;
		
	}
}

/* End of file izvestaji.php */
/* Location: ./application/controllers/izvestaji.php */
