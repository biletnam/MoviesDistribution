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
	
	public function __construct()
	{
		parent::__construct();
		
		$this->_komitent_id = $this->input->post( "komitent_id" );
		$this->_datum_od = $this->input->post( "datum_kopije_od" );
		$this->_datum_do = $this->input->post( "datum_kopije_do" );		
		$this->_film_id = $this->input->post( "film_id" );
		$this->_bioskop_id = $this->input->post( "izvestaj_lk_bioskop_select" );
		$this->_sub_sume = $this->input->post( "sub_sume" );
		
		
		$this->load->database();
	}

	public function index()
	{
		$this->load->view( "izvestaji" );
	}
	
	public function lkOdigraniFilmoviRead()
	{
		$this->db->where( "rokovnici.datum_kopije_od >=", $this->_datum_od );
		$this->db->where( "rokovnici.datum_kopije_do <=", $this->_datum_do );
		
		$totalRows = $this->db->get('rokovnici' )->num_rows();
		
		$this->db->select( 'filmovi.film_id, filmovi.naziv_filma' );

		$this->db->join( 'filmovi', 'filmovi.film_id = rokovnici.film_id', 'inner' );
			
		$this->db->where( "rokovnici.komitent_id", $this->_komitent_id );
		$this->db->where( "rokovnici.datum_kopije_od >=", $this->_datum_od );
		$this->db->where( "rokovnici.datum_kopije_do <=", $this->_datum_do );
		
		$this->db->group_by(  'filmovi.film_id' );
		$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
		
		$query = $this->db->get( 'rokovnici', $this->_limit, $this->getRowsOffset( $this->_page, $this->_limit ) );
		
		$this->dispatchResultXml( $query->result_array(), $totalRows  );
	}	
	
	public function lkFilmoviBezIzvestajaRead()
	{
		
		//$this->db->where( "rokovnici.datum_kopije_od >=", $this->_datum_od );
		//$this->db->where( "rokovnici.datum_kopije_do <=", $this->_datum_do );
		
		//$totalRows = $this->db->get('rokovnici' )->num_rows();
		
		$this->db->select( 'filmovi.film_id, filmovi.naziv_filma, rokovnici.rokovnik_id' );
		
		$this->db->join( 'rokovnici', 'rokovnici.rokovnik_id = gledanost.rokovnik_id', 'inner' );
		$this->db->join( 'filmovi', 'filmovi.film_id = rokovnici.film_id', 'inner' );
			
		$this->db->where( "rokovnici.komitent_id", $this->_komitent_id );
		$this->db->where( "gledanost.datum_gledanosti >=", $this->_datum_od );
		$this->db->where( "gledanost.datum_gledanosti <=", $this->_datum_do );
		
		$this->db->group_by( "filmovi.film_id" );
		
		$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
		
		//$query = $this->db->get( 'rokovnici', $this->_limit, $this->getRowsOffset( $this->_page, $this->_limit ) );
		
		$gledanost_data = $this->db->get( 'gledanost' )->result_array();
	
		$final_data = array();
		
		foreach( $gledanost_data as $gd )
		{
			$this->db->where( "rokovnik_id", $gd[ "rokovnik_id"] );
			
			if( $this->db->get( "zvanicna_gledanost" )->num_rows() == 0 )
			{
				array_push( $final_data, $gd );
			}
		}
		
		$this->dispatchResultXml( $final_data, count( $final_data )  );
	}
	
	public function lkTopListaFilmovaRead()
	{
		$this->db->where( "rokovnici.datum_kopije_od >=", $this->_datum_od );
		$this->db->where( "rokovnici.datum_kopije_do <=", $this->_datum_do );
		$this->db->group_by(  "rokovnici.film_id" );
		
		$totalRows = $this->db->get('rokovnici' )->num_rows();
		
		$this->db->select( 'filmovi.film_id, filmovi.naziv_filma, rokovnici.suma_gledanosti_kopije' );

		$this->db->join( 'filmovi', 'filmovi.film_id = rokovnici.film_id', 'inner' );
			
		$this->db->where( "rokovnici.komitent_id", $this->_komitent_id );
		$this->db->where( "rokovnici.datum_kopije_od >=", $this->_datum_od );
		$this->db->where( "rokovnici.datum_kopije_do <=", $this->_datum_do );
		
		$this->db->order_by(  "rokovnici.suma_gledanosti_kopije", "DESC" );
		$this->db->group_by(  "rokovnici.film_id" );
		
		$query = $this->db->get( 'rokovnici', $this->_limit, $this->getRowsOffset( $this->_page, $this->_limit ) );
		
		$this->dispatchResultXml( $query->result_array(), $totalRows  );
	}
	
	public function lkFilmoviSaIzvestajimaRead()
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
		
		// first result select
	 	$this->_selectFilmoviSaIzvestajima( false );
		$qr1 = $this->db->_compile_select();
		
		$this->db->_reset_select();
		
		//second result select
		$this->_selectFilmoviSaIzvestajima( false, true );
		$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
		$this->db->limit( $this->_limit, $this->getRowsOffset( $this->_page, $this->_limit ) );
		
		$qr2 = $this->db->_compile_select();
		
		// result union
		$query_result = $this->db->query( $qr1 . " UNION " . $qr2 );
		
		// dispatch
		$this->dispatchResultXml( $query_result->result_array(), $totalRows  );	
	}
	
	protected function _selectFilmoviSaIzvestajima( $count_only = true, $storno = NULL )
	{
		$td = "fakture";
		if( $storno ) $td = "fakture_storno";
		
		if( $count_only )
		{
			$this->db->select( "$td.*" );
		}
		else
		{
			$this->db->select( "$td.*, 
								CURDATE() as danasnji_datum,
								( $td.za_placanje - $td.uplate_total ) AS dugovna_strana,
								DATEDIFF( CURDATE(), $td.rok_placanja  ) AS kasnjenje,
								zvanicna_gledanost.*,
								filmovi.naziv_filma" 
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
			$d[0][ $k ] = number_format( $v, 2, ',', '.' );
		}
		
		$this->dispatchResultXml( $d, count( $d )  );
	} 
	
	public function lkNefakturisaniFilmoviRead()
	{
		$pf = round( ( 8 * 100 ) / ( 8 + 100 ), 4, PHP_ROUND_HALF_UP );
		
		$this->db->select( "filmovi.film_id, 
							filmovi.naziv_filma, 
							zvanicna_gledanost.z_gledanost_id, 
							zvanicna_gledanost.ukupan_prihod AS bruto_zarada,
							( zvanicna_gledanost.ukupan_prihod - ( zvanicna_gledanost.ukupan_prihod * $pf / 100 ) ) AS neto_zarada" );
		
		$this->db->join(  'filmovi', 'filmovi.film_id = zvanicna_gledanost.film_id', 'inner' );	
		$this->db->where( "zvanicna_gledanost.datum_z_gledanost_od >=", $this->_datum_od );
		$this->db->where( "zvanicna_gledanost.datum_z_gledanost_do <=", $this->_datum_do );
		$this->db->where( "zvanicna_gledanost.stornirana", 0 );
		
		$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
		
		$gledanost_data = $this->db->get( 'zvanicna_gledanost' )->result_array();
		
		$final_data = array();
		
		foreach( $gledanost_data as $fd )
		{
			$this->db->select( 'fakture.faktura_id');
			
			$this->db->where( "fakture.z_gledanost_id", $fd[ "z_gledanost_id"] );
			
			if( $this->db->get( "fakture" )->num_rows() == 0 )
			{
				$fd[ 'neto_zarada' ] = round( $fd[ 'neto_zarada' ], 2, PHP_ROUND_HALF_UP );
				$fd[ 'bruto_zarada' ] = round( $fd[ 'bruto_zarada' ], 2, PHP_ROUND_HALF_UP );
				
				array_push( $final_data, $fd );
			}
		}
		
		$this->dispatchResultXml( $final_data, count( $final_data )  );
	}
	
	public function lkNebukiraniFilmoviRead()
	{
		$this->db->select( 'film_id, naziv_filma, start_filma' );
			
		$this->db->where( "start_filma >=", $this->_datum_od );
		$this->db->where( "start_filma <=", $this->_datum_do );
		
		$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
		
		$gledanost_data = $this->db->get( 'filmovi' )->result_array();
		
		$final_data = array();
		
		foreach( $gledanost_data as $fd )
		{
			$this->db->select( 'kopije_zakljucnice.film_id');
			$this->db->from( 'kopije_zakljucnice');
			
			$this->db->where( "kopije_zakljucnice.film_id", $fd[ "film_id"] );
			$this->db->where( "zakljucnice.komitent_id", $this->_komitent_id );
			
			$this->db->group_by( "kopije_zakljucnice.film_id" );
			
			if( $this->db->get( "zakljucnice" )->num_rows() == 0 )
			{
				array_push( $final_data, $fd );
			}
		}
		
		$this->dispatchResultXml( $final_data, count( $final_data )  );
	}
	
	public function lkIzvestajPoBioskopu()
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
		
		
		$this->db->_reset_select();
		
		// first result select
	 	$this->_selectIzvestajiPoBioskpu( false );
		$qr1 = $this->db->_compile_select();
		
		$this->db->_reset_select();
		
		//second result select
		$this->_selectIzvestajiPoBioskpu( false, true );
		
		
		
		$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
		
		
		$this->db->limit( $this->_limit, $this->getRowsOffset( $this->_page, $this->_limit ) );
		
		$qr2 = $this->db->_compile_select();
		
		
		// result union
		$query_result = $this->db->query( $qr1 . " UNION " . $qr2 );
		
		$suma = array();
		$keys = array();
		
		$result = $query_result->result_array();
		
		/**
		foreach( $result as $fdata )
		{
			if( in_array( $fdata[ "" ], $keys ) )
			{
				
			}
		}
		**/
		
		// dispatch
		$this->dispatchResultXml( $result, $totalRows  );	
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
			
			$pf = round( ( 8 * 100 ) / ( 8 + 100 ), 4, PHP_ROUND_HALF_UP );
			
			if( $this->_sub_sume )
			{
				$this->db->select( "$td.*, 
									CURDATE() as danasnji_datum,
									filmovi.naziv_filma,
									bioskopi.bioskop_id,
									bioskop_aliases.bioskop_alias_name,
									SUM( $td.ukupan_prihod ) AS bruto_zarada,
									SUM( za_placanje ) AS neto_zarada" 
						 	 	 );
			}
			else
			{
				$this->db->select( "$td.*, 
									CURDATE() as danasnji_datum,
									filmovi.naziv_filma,
									bioskopi.bioskop_id,
									bioskop_aliases.bioskop_alias_name,
									$td.ukupan_prihod AS bruto_zarada,
									za_placanje AS neto_zarada"	 
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
		$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
		$this->db->join( 'bioskop_aliases', 'bioskop_aliases.bioskop_alias_id = bioskopi.alias_bioskopa', 'inner' );
			
		$this->db->where( "zvanicna_gledanost.komitent_id", $this->_komitent_id );
		
		//$this->db->where( "zvanicna_gledanost.datum_z_gledanost_od >=", $this->_datum_od );
		//$this->db->where( "zvanicna_gledanost.datum_z_gledanost_od <=", $this->_datum_do );
		
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
							SUM( fakture.za_placanje ) AS neto_zarada,"
						 );
		
		$this->_joinIzvestajiPoBioskopu();
		
		$d = $this->db->get( 'fakture' )->result_array();
		
		
		$this->db->select( "SUM( fakture_storno.ukupan_prihod ) AS bruto_zarada,
							SUM( fakture_storno.za_placanje ) AS neto_zarada,"
						 );
		
		$this->_joinIzvestajiPoBioskopu( true );
		
		$ds = $this->db->get( 'fakture_storno' )->result_array();
		
		if( $d && $ds )
		{
			$d[ 0 ][ 'bruto_zarada' ] = $d[ 0 ][ 'bruto_zarada' ] + $ds[ 0 ][ 'bruto_zarada' ];
			$d[ 0 ][ 'neto_zarada' ] = $d[ 0 ][ 'neto_zarada' ] + $ds[ 0 ][ 'neto_zarada' ];
		}
		
		
		foreach( $d[0] as $k => $v )
		{
			$d[0][ $k ] = number_format( $v, 2, ',', '.' );
		}
		
		$this->dispatchResultXml( $d, count( $d )  );
	}
	
	public function finansijskiPrometFilma()
	{
		
		$this->_prepareFinansijskiPrometSql();
		
		$totalRows = $this->db->get('zvanicna_gledanost' )->num_rows();		
		
		$this->_prepareFinansijskiPrometSql();

		
		$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
		
		$this->dispatchResultXml( $this->db->get( 'zvanicna_gledanost' )->result_array(), $totalRows  );
	}
	
	
	protected function _prepareFinansijskiPrometSql()
	{
	
		if ($this->_film_id =='1') {
	
	
			$this->db->select( 'zvanicna_gledanost.*,
					rokovnici.*,
					filmovi.*,
					kopije_filma.*,
					komitenti.naziv_komitenta,
					fakture.*,
					bioskopi.naziv_bioskopa,
					zvanicna_gledanost.ukupan_prihod as bruto,
					zvanicna_gledanost.za_distributera_rsd as sapdv,
					(zvanicna_gledanost.za_distributera_rsd - zvanicna_gledanost.iznos_pdv_rsd ) as bezpdv' );
	
			$this->db->join( 'rokovnici', 'rokovnici.rokovnik_id = zvanicna_gledanost.rokovnik_id', 'inner' );
			$this->db->join( 'fakture', 'fakture.z_gledanost_id = zvanicna_gledanost.z_gledanost_id', 'inner' );
			$this->db->join( 'komitenti', 'komitenti.komitent_id = rokovnici.komitent_id', 'inner' );
			$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
			$this->db->join( 'filmovi', 'filmovi.film_id = rokovnici.film_id', 'inner' );
			$this->db->join( 'kopije_filma', 'kopije_filma.kopija_id = rokovnici.kopija_id', 'inner' );
	
	
			$this->db->where( "zvanicna_gledanost.datum_z_gledanost_od >=", $this->_datum_od );
			$this->db->where( "zvanicna_gledanost.datum_z_gledanost_do <=", $this->_datum_do );
	
	
		}
		if ($this->_film_id !='1') {
	
			$this->db->select( 'zvanicna_gledanost.*,
					rokovnici.*,
					filmovi.*,
					kopije_filma.*,
					komitenti.naziv_komitenta,
					fakture.*,
					bioskopi.naziv_bioskopa,
				    zvanicna_gledanost.ukupan_prihod as bruto,
					zvanicna_gledanost.za_distributera_rsd as sapdv,
					(zvanicna_gledanost.za_distributera_rsd - zvanicna_gledanost.iznos_pdv_rsd ) as bezpdv' );
	
			$this->db->join( 'rokovnici', 'rokovnici.rokovnik_id = zvanicna_gledanost.rokovnik_id', 'inner' );
			$this->db->join( 'fakture', 'fakture.z_gledanost_id = zvanicna_gledanost.z_gledanost_id', 'inner' );
			$this->db->join( 'komitenti', 'komitenti.komitent_id = rokovnici.komitent_id', 'inner' );
			$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
			$this->db->join( 'filmovi', 'filmovi.film_id = rokovnici.film_id', 'inner' );
			$this->db->join( 'kopije_filma', 'kopije_filma.kopija_id = rokovnici.kopija_id', 'inner' );
	
	
			$this->db->where( "zvanicna_gledanost.datum_z_gledanost_od >=", $this->_datum_od );
			$this->db->where( "zvanicna_gledanost.datum_z_gledanost_do <=", $this->_datum_do );
			$this->db->where( "zvanicna_gledanost.film_id =", $this->_film_id  );
	
	
	
		}
	
	}	
	
	
	public function finansijskiPrometFilmaS()
	{
	
		$this->_prepareFinansijskiPrometSqlS();
	
		$totalRows = $this->db->get('zvanicna_gledanost' )->num_rows();
	
		$this->_prepareFinansijskiPrometSqlS();
	
	
		$this->db->order_by(  $this->_sort_col_name, $this->_sort_order_name );
	
		$this->dispatchResultXml( $this->db->get( 'zvanicna_gledanost' )->result_array(), $totalRows  );
	}
	
	
	protected function _prepareFinansijskiPrometSqlS()
	{
	
		if ($this->_film_id =='1') {
	
	
			$this->db->select( 'zvanicna_gledanost.*,
					rokovnici.*,
					filmovi.*,
					kopije_filma.*,
					komitenti.naziv_komitenta,
					fakture.*,
					bioskopi.naziv_bioskopa,
					SUM(zvanicna_gledanost.ukupan_prihod ) as brutosume,
					SUM(zvanicna_gledanost.za_distributera_rsd ) as sapdvsume,					
					SUM(ukupno_gledalaca) as suma_gledanosti_kopijesume,
					SUM(zvanicna_gledanost.za_distributera_rsd - zvanicna_gledanost.iznos_pdv_rsd ) as bezpdvsume'
					 );
	
			$this->db->join( 'rokovnici', 'rokovnici.rokovnik_id = zvanicna_gledanost.rokovnik_id', 'inner' );
			$this->db->join( 'fakture', 'fakture.z_gledanost_id = zvanicna_gledanost.z_gledanost_id', 'inner' );
			$this->db->join( 'komitenti', 'komitenti.komitent_id = rokovnici.komitent_id', 'inner' );
			$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
			$this->db->join( 'filmovi', 'filmovi.film_id = rokovnici.film_id', 'inner' );
			$this->db->join( 'kopije_filma', 'kopije_filma.kopija_id = rokovnici.kopija_id', 'inner' );
	                
			$this->db->where( "`zvanicna_gledanost`.`stornirana` =", '0');
	
			//$this->db->where( "zvanicna_gledanost.datum_z_gledanost_od >=", $this->_datum_od );
			//$this->db->where( "zvanicna_gledanost.datum_z_gledanost_do <=", $this->_datum_do );
			
			$this->db->where( "fakture.datum_prometa >=", $this->_datum_od );
			$this->db->where( "fakture.datum_prometa <=", $this->_datum_do );
	
			
		}
		if ($this->_film_id !='1') {
	
			$this->db->select( 'zvanicna_gledanost.*,
					rokovnici.*,
					filmovi.*,
					kopije_filma.*,
					komitenti.naziv_komitenta,
					fakture.*,
					bioskopi.naziv_bioskopa,
					SUM(zvanicna_gledanost.ukupan_prihod) as brutosume,
					SUM(zvanicna_gledanost.za_distributera_rsd ) as sapdvsume,					
					SUM(ukupno_gledalaca) as suma_gledanosti_kopijesume,
					SUM(zvanicna_gledanost.za_distributera_rsd - zvanicna_gledanost.iznos_pdv_rsd ) as bezpdvsume' );
	
			$this->db->join( 'rokovnici', 'rokovnici.rokovnik_id = zvanicna_gledanost.rokovnik_id', 'inner' );
			$this->db->join( 'fakture', 'fakture.z_gledanost_id = zvanicna_gledanost.z_gledanost_id', 'inner' );
			$this->db->join( 'komitenti', 'komitenti.komitent_id = rokovnici.komitent_id', 'inner' );
			$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
			$this->db->join( 'filmovi', 'filmovi.film_id = rokovnici.film_id', 'inner' );
			$this->db->join( 'kopije_filma', 'kopije_filma.kopija_id = rokovnici.kopija_id', 'inner' );
	
	        $this->db->where( "`zvanicna_gledanost`.`stornirana` =", '0');
			
	        //$this->db->where( "zvanicna_gledanost.datum_z_gledanost_od >=", $this->_datum_od );
			//$this->db->where( "zvanicna_gledanost.datum_z_gledanost_do <=", $this->_datum_do );
			
	       	$this->db->where( "fakture.datum_prometa >=", $this->_datum_od );
			$this->db->where( "fakture.datum_prometa <=", $this->_datum_do );
		
			$this->db->where( "zvanicna_gledanost.film_id =", $this->_film_id  );
			
	
	
		}
	
	}
	
	
	

				


}

/* End of file izvestaji.php */
/* Location: ./application/controllers/izvestaji.php */
