<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/hooks/PreController.php';

class Rokovnici extends PreController 
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
		
		$this->load->view( "rokovnici", $data );
	}
	
	public function updateRokovnik( $rokovnik_id, $update_euro_centi = NULL )
	{
		$id = @$_REQUEST[ 'id' ];
		
		if( ! $id )
			$id = $rokovnik_id;
		
			
		if( isset(  $id ) && $id > 0 )
		{
			$euro_cent = NULL;
			
			if( array_key_exists( "eurocent", $this->_prefixedValues ) )
			{
				$euro_cent = $this->_prefixedValues[ "eurocent" ];
			}
			
			
			// calculate euro cents for rsd and eur and append to prefixedValues for update	
			if( $euro_cent || $update_euro_centi  )
			{

				$this->db->select( "rokovnici.suma_prodatih_naocara_kopije, 
									rokovnici.kopija_id,
									rokovnici.eurocent, 
									kopije_zakljucnice.datum_kopije_do" );
				
				$this->db->join( 'kopije_zakljucnice', 'kopije_zakljucnice.kopije_zakljucnice_id = rokovnici.kopije_zakljucnice_id', 'inner' );
				$this->db->where( 'rokovnik_id', $id );
				
				$rdata = $this->db->get( "rokovnici" )->row( 0 );

				if( ! $rdata )
				{
					echo 0;
					return;
				}
				else
				{
					
					if( ! $euro_cent )
					{
						$euro_cent = $rdata->eurocent;
					}
					
					$this->db->where( "datum_kursa <=", $rdata->datum_kopije_do );
					$this->db->order_by( "datum_kursa", "DESC" );
					
					$this->db->limit( 1 );
					
					$kurs_data = $this->db->get( "kursna_lista")->row( 0 ); 
					
					$naocare_euro_cent_eur = $rdata->suma_prodatih_naocara_kopije * $euro_cent;
					$naocare_euro_cent_rsd = $naocare_euro_cent_eur * $kurs_data->eur;

					$this->_prefixedValues[ "naocare_eurocent_eur" ] = $naocare_euro_cent_eur;
					$this->_prefixedValues[ "naocare_eurocent_rsd" ] = $naocare_euro_cent_rsd;
				}
				
			}
			
			$this->db->where( 'rokovnik_id', $id );
			$this->db->update( 'rokovnici', $this->_prefixedValues );
			
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
	
	public function read()
	{
		$ad = $this->input->post( 'advanced_search' );
		
		$this->_readSelect();

		if( $ad )
			$this->_setAdvancedSearch();
			
		$totalRows = $this->db->get('rokovnici' )->num_rows();
		
		
		$this->_readSelect();
		
		if( $ad )
			$this->_setAdvancedSearch();
		
		$this->db->order_by( $this->_sort_col_name, $this->_sort_order_name );
		
		$query = $this->db->get('rokovnici', $this->_limit, $this->getRowsOffset( $this->_page, $this->_limit ) );
		
		$this->dispatchResultXml( $query->result_array(), $totalRows  );
	}
	
	protected function _readSelect()
	{
		$this->db->select( "rokovnici.*,
							
							FORMAT( rokovnici.suma_zarada_rsd, 4 ) AS suma_zarada_rsd,	 
							FORMAT( rokovnici.suma_zarada_eur, 4 ) AS suma_zarada_eur,	 
							FORMAT( rokovnici.suma_zarada_km, 4 ) AS suma_zarada_km,	 
							FORMAT( rokovnici.suma_zarada_naocare_rsd, 4 ) AS suma_zarada_naocare_rsd,	 
							FORMAT( rokovnici.suma_zarada_naocare_eur, 4 ) AS suma_zarada_naocare_eur,	 
							FORMAT( rokovnici.suma_zarada_naocare_km, 4 ) AS suma_zarada_naocare_km,
								 
							komitenti.naziv_komitenta,
							filmovi.naziv_filma, 
							bioskopi.naziv_bioskopa,
							kopije_filma.*",
							false
						);
						
						
		$this->db->join( 'komitenti', 'komitenti.komitent_id = rokovnici.komitent_id', 'inner' );
		$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
		$this->db->join( 'filmovi', 'filmovi.film_id = rokovnici.film_id', 'inner' );
		$this->db->join( 'kopije_filma', 'kopije_filma.kopija_id = rokovnici.kopija_id', 'inner' );
	}
	
	protected function _setAdvancedSearch()
	{
		
		if( strlen( $this->_prefixedValues[ "rokovnik_id" ] ) > 0 )
			$this->db->where('rokovnik_id', $this->_prefixedValues[ "rokovnik_id" ] );		
		
		if( strlen( $this->_prefixedValues[ "zakljucnica_id" ] ) > 0 )
			$this->db->where('zakljucnica_id', $this->_prefixedValues[ "zakljucnica_id" ] );
			
		if( strlen( $this->_prefixedValues[ "primiti_kopiju_od" ] ) > 0 )
			$this->db->where('primiti_kopiju_od', $this->_prefixedValues[ "primiti_kopiju_od" ] );	
		
		if( strlen( $this->_prefixedValues[ "nacin_prijema_kopije" ] ) > 0 )
			$this->db->like('nacin_prijema_kopije', $this->_prefixedValues[ "nacin_prijema_kopije" ] );
		
		if( strlen( $this->_prefixedValues[ "datum_prijema_kopije" ] ) > 0 )
			$this->db->where('datum_prijema_kopije', $this->_prefixedValues[ "datum_prijema_kopije" ] );
		
		if( strlen( $this->_prefixedValues[ "otpremiti_kopiju_od" ] ) > 0 )
			$this->db->where('otpremiti_kopiju_od', $this->_prefixedValues[ "otpremiti_kopiju_od" ] );
		
		if( strlen( $this->_prefixedValues[ "nacin_otpreme_kopije" ] ) > 0 )
			$this->db->like('nacin_otpreme_kopije', $this->_prefixedValues[ "nacin_otpreme_kopije" ] );
		
		if( strlen( $this->_prefixedValues[ "datum_otpreme_kopije" ] ) > 0 )
			$this->db->where('datum_otpreme_kopije', $this->_prefixedValues[ "datum_otpreme_kopije" ] );
		
		if( strlen( $this->_prefixedValues[ "tip_raspodele" ] ) > 0 )
			$this->db->where('tip_raspodele', $this->_prefixedValues[ "tip_raspodele" ] );
						
		if( strlen( $this->_prefixedValues[ "komitent_id" ] ) > 0 )
			$this->db->where('komitent_id', $this->_prefixedValues[ "komitent_id" ] );
		
		if( strlen( $this->_prefixedValues[ "naziv_komitenta" ] ) > 0 )
			$this->db->like('naziv_komitenta', $this->_prefixedValues[ "naziv_komitenta" ] );

		if( strlen( $this->_prefixedValues[ "naziv_bioskopa" ] ) > 0 )
			$this->db->like('naziv_bioskopa', $this->_prefixedValues[ "naziv_bioskopa" ] );
			
		if( strlen( $this->_prefixedValues[ "film_id" ] ) > 0 )
			$this->db->where('film_id', $this->_prefixedValues[ "film_id" ] );
		
		if( strlen( $this->_prefixedValues[ "naziv_filma" ] ) > 0 )
			$this->db->like('naziv_filma', $this->_prefixedValues[ "naziv_filma" ] );
				
		if( strlen( $this->_prefixedValues[ "serijski_broj_kopije" ] ) > 0 )
			$this->db->like('serijski_broj_kopije', $this->_prefixedValues[ "serijski_broj_kopije" ] );	

		if( strlen( $this->_prefixedValues[ "datum_kopije_od" ] ) > 0 )
			$this->db->where('datum_kopije_od', $this->_prefixedValues[ "datum_kopije_od" ] );	
			
		if( strlen( $this->_prefixedValues[ "datum_kopije_do" ] ) > 0 )
			$this->db->where('datum_kopije_do', $this->_prefixedValues[ "datum_kopije_do" ] );	
		
		if( strlen( $this->_prefixedValues[ "tip" ] ) > 0 )
			$this->db->where('tip', $this->_prefixedValues[ "tip" ] );
		
		if( strlen( $this->_prefixedValues[ "tehnika_kopije_filma" ] ) > 0 )
			$this->db->where('tehnika_kopije_filma', $this->_prefixedValues[ "tehnika_kopije_filma" ] );
			
			
	}
	
	public function encodeIds()
	{
		if( isset( $_POST[ 'rokovniciIds' ] ) && strlen( $_POST[ 'rokovniciIds' ] ) > 0 )
		{
			echo base64_encode( $_POST[ 'rokovniciIds' ] );
		}
		else
		{
			echo ErrorCodes::INVALID_INPUT;
		}
	}
	
	
	public function prikaziRokovnike()
	{
		
		$this->db->select( "rokovnici.*, 
							komitenti.*,
							filmovi.*, 
							bioskopi.*,
							kopije_filma.*,
							zanrovi_filma.*,
							DATE_FORMAT( rokovnici.datum_unosa, '%d/%m/%Y' ) AS datum_unosa_stampa,
							DATE_FORMAT( rokovnici.datum_kopije_od, '%d/%m/%Y' ) AS datum_kopije_od_stampa,
							DATE_FORMAT( rokovnici.datum_kopije_do, '%d/%m/%Y' ) AS datum_kopije_do_stampa,
							DATE_FORMAT( rokovnici.datum_prijema_kopije, '%d/%m/%Y' ) AS datum_prijema_kopije_stampa",
							false
							
						);
						
						
		$this->db->join( 'komitenti', 'komitenti.komitent_id = rokovnici.komitent_id', 'inner' );
		$this->db->join( 'bioskopi', 'bioskopi.bioskop_id = rokovnici.bioskop_id', 'inner' );
		$this->db->join( 'filmovi', 'filmovi.film_id = rokovnici.film_id', 'inner' );
		$this->db->join( 'zanrovi_filma', 'zanrovi_filma.zanr_filma_id = filmovi.zanr_filma', 'inner' );
		$this->db->join( 'kopije_filma', 'kopije_filma.kopija_id = rokovnici.kopija_id', 'inner' );
		
		
		$ids = $this->commaDelimitedToArray( $_GET['rokovnici' ] );
		
		foreach( $ids as $v )
		{
			$this->db->or_where( "rokovnik_id", $v );
		}
		
		
		$rokovnici = $this->db->get( "rokovnici" )->result_array();
		
		
		$m = $this->db->get( 'maticna_firma' )->result_array();
		
		$rokarr = array();
		
		foreach( $rokovnici as $rokovnik )
		{
			$rokovnik[ "referent" ] = self::$__session->userdata('ime_korisnika');
			$rokarr[] = $this->load->view( 'rokovnik_strana', array( 'm' => $m[0], 'rd' => $rokovnik ), true );
		}
		
		
		$rokovnici_all_view_data = $this->load->view( 'pregled_rokovnika', array( 'rokovnici' => $rokarr  ), true );
		
		
		//echo $rokovnici_all_view_data;
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
		
		$mpdf->WriteHTML( $rokovnici_all_view_data );
		
		$mpdf->Output();
		exit;
		//==============================================================
		//==============================================================
		//==============================================================
		//==============================================================
		//==============================================================
				
	}
	
	
}

/* End of file rokovnici.php */
/* Location: ./application/controllers/rokovnici.php */