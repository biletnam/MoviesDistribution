<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/controllers/izvestaji.php';

class FaktureIzvestaji extends Izvestaji
{
	
	protected $_sa_uplatama;
	protected $_sumarno_po_filmu;
	protected $_nefakturisano;
	
	
	public function __construct()
	{
		parent::__construct();

		$this->_producent = $this->input->post( "producent_filma" );
		$this->_sa_uplatama = $this->input->post( "sa_uplatama" );
		$this->_sumarno_po_filmu = $this->input->post( "sumarno_po_filmu" );
		$this->_nefakturisano = $this->input->post( "nefakturisano" );
		$this->_datum_od = $this->input->post( "datum_od" );
		$this->_datum_do = $this->input->post( "datum_do" );

		$this->_decimal_point = 2;

	}
	
	
	public function gledanosti()
	{
		
	}
	
	public function ne_i_fakturisano_PFK()
	{
		if( $this->_nefakturisano )
		{
			$this->_nefakturisano();	
		}
		else
		{
			$this->_fakturisano();
		}
	}
	
	
	protected function _fakturisano()
	{
		
		$this->_selectFakture();
		$qr1 = $this->db->_compile_select();
		
		$this->db->_reset_select();
			
		// second count select
		$this->_selectFakture( TRUE );
		$qr2 = $this->db->_compile_select();
	
		// total rows union
		$result = $this->db->query( $qr1 . " UNION " . $qr2 )->result_array();
		$this->db->_reset_select();
		
		$suma_fakturisano = 0;
		$suma_neto = 0;
		$suma_porez = 0;
		
		$data = array();
		$temp = NULL;
		
		$interval = date ( "d/m/Y", strtotime ( $this->_datum_od ) ) . ' - ' . date ( "d/m/Y", strtotime ( $this->_datum_do ) );
		
		$length = count( $result );
		
		$eur = 1;
		
		$stavke_tabela = "";
		$stavke_result = NULL;
		$kurs_result;
		
		$fakturisano_suma = 0;
		$neto_suma = 0;
		$porez_suma = 0;
		$naocare_suma = 0;
		$naocare_prihod_suma = 0;
		$uplate_total_suma = 0;
		$duguje_suma = 0;
		
		
		foreach ( $result as $row )
		{		
			
			if( $row[ 'valuta_fakture' ] == 2 )
			{
				$eur = $this->db->select( 'eur' )->where( 'datum_kursa', $row[ 'vdate' ] )->get( 'kursna_lista' )->row( 0 )->eur;
			}
			else 
			{
				$eur = 1;
			}
			
			$temp = array();
			
			$temp[ 'faktura_id' ] = $row[ 'faktura_id' ];
			$temp[ 'storno' ] = $row[ 'storno' ];
			
			$temp[ 'film_id' ] = $row[ 'film_id' ];
			$temp[ 'broj_dokumenta' ] = $row[ 'broj_dokumenta_fakture' ];
			$temp[ 'datum_unosa_fakture' ] = $row[ 'datum_unosa_fakture' ];
			$temp[ 'producent_filma' ] = $row[ 'producent_filma' ];
			$temp[ 'naziv_filma' ] = $row[ 'naziv_filma' ];
			$temp[ 'naziv_komitenta' ] = $row[ 'naziv_komitenta' ];
			
			
			$temp[ 'fakturisano' ] = $row[ 'za_placanje' ] * $eur;
			$temp[ 'neto' ] = $row[ 'osnovica' ] * $eur;
			$temp[ 'porez' ] = $row[ 'ukupan_pdv' ] * $eur;
			$temp[ 'naocare' ] = 0;
			$temp[ 'naocare_prihod' ] = 0;
			
			$row[ 'stornirana' ] == 1 ? $stavke_tabela = 'fakture_stavke_storno' : $stavke_tabela = 'fakture_stavke';
			
			$stavke_result = $this->db->where( array( 'redni_broj_stavke' => 2, 'faktura_id' => $row[ 'faktura_id' ] ) )->get( $stavke_tabela )->row( 0 );
			
			if( $stavke_result )
			{
				$temp[ 'naocare' ] = $stavke_result->broj_prodatih_naocara;
				$temp[ 'naocare_prihod' ] = $stavke_result->prihod * $eur;
			}
			else 
			{
				$temp[ 'naocare' ] = 0;
				$temp[ 'naocare_prihod' ] = 0;
			}
			
			$temp[ 'uplate_total' ] = $row[ 'uplate_total' ] * $eur;
			$temp[ 'duguje' ] = ( $row[ 'uplate_total' ] - $row[ 'uplate_total' ] ) * $eur;
			
			$data[] = $temp;
			
			$fakturisano_suma += $temp[ 'fakturisano' ];
			$neto_suma += $temp[ 'neto' ];
			$porez_suma += $temp[ 'porez' ];
			$naocare_suma += $temp[ 'naocare' ];
			$naocare_prihod_suma += $temp[ 'naocare_prihod' ];
			$uplate_total_suma += $temp[ 'uplate_total' ];
			$duguje_suma += $temp[ 'duguje' ];
			
		}
		
		$sumarno_data = $data;
		
		if( $this->_sumarno_po_filmu == 'da' )
		{
			$sumarno_data = array();
			
			foreach( $data as $row )
			{
				if( array_key_exists( $row[ 'film_id' ], $sumarno_data ) )
				{
					$temp = $sumarno_data[ $row[ 'film_id' ] ];
					
					$temp[ 'fakturisano' ] += $row[ 'fakturisano' ];
					$temp[ 'neto' ] += $row[ 'neto' ];
					$temp[ 'porez' ] += $row[ 'porez' ];
					$temp[ 'naocare' ] += $row[ 'naocare' ];
					$temp[ 'naocare_prihod' ] += $row[ 'naocare_prihod' ];
					 
					$sumarno_data[ $row[ 'film_id' ] ] = $temp;
				}
				else
				{
					$sumarno_data[ $row[ 'film_id' ] ] = $row;
				}
			}
		}
		
		$formated_data = array();
		
		foreach( $sumarno_data as $row )
		{
			$row[ 'fakturisano' ] = number_format( $row[ 'fakturisano' ], $this->_decimal_point );
			$row[ 'neto' ] = number_format(  $row[ 'neto' ], $this->_decimal_point );
			$row[ 'porez' ] = number_format( $row[ 'porez' ], $this->_decimal_point );
			$row[ 'naocare' ] = number_format( $row[ 'naocare' ], $this->_decimal_point );
			$row[ 'naocare_prihod' ] = number_format( $row[ 'naocare_prihod' ], $this->_decimal_point );
			$row[ 'uplate_total' ] = number_format( $row[ 'uplate_total' ], $this->_decimal_point );
			$row[ 'duguje' ] = number_format( $row[ 'duguje' ], $this->_decimal_point );
			
			array_push( $formated_data, $row );
		}
		
		$this->_decimal_point = 2;
		
		$view_data = array( 'data' => $formated_data, 
							'sa_uplatama' => $this->_sa_uplatama,
							'sumarno_po_filmu' => $this->_sumarno_po_filmu,
							'datumski_interval' => $interval,
							'naziv_komitenta' => $this->_naziv_komitenta,
							'naziv_producenta' => $this->_producent,

							'fakturisano_suma' => number_format( $fakturisano_suma, $this->_decimal_point	 ),
							'neto_suma' => number_format( $neto_suma, $this->_decimal_point	 ),
							'porez_suma' => number_format( $porez_suma, $this->_decimal_point	 ),
							'naocare_suma' => number_format( $naocare_suma, $this->_decimal_point	 ),
							'naocare_prihod_suma' => number_format( $naocare_prihod_suma, $this->_decimal_point	 ),
							'uplate_total_suma' => number_format( $uplate_total_suma, $this->_decimal_point	 ),
							'duguje_suma' => number_format( $duguje_suma, $this->_decimal_point	 )
					
		);
		
		
		
		
		set_time_limit ( 5000 );
		ini_set ( 'memory_limit', '1024M' );
	

		
		$colwidths = array();

		$error_level = error_reporting();

		require_once 'pdf/mpdf.php';

		error_reporting(0);

		$mpdf = new mPDF('utf-8',    // mode - default ''
		'A4',    // format - A4, for example, default ''
		0,     // font size - default 0
		'Verdana',    // default font family
		5,    // margin_left
		5,    // margin right
		21,     // margin top
		9,    // margin bottom
		3,     // margin header
		3,     // margin footer
		'L');  // L - landscape, P - portrait 
		
		$view_parsed = $this->load->view( 'fakturisanoPFK', $view_data, true );

		$header = $this->load->view( 'fakturisanoPFKHeader', $view_data, true );
		$footer = $this->load->view( 'finPrometFooter', null, true );


		$mpdf->SetHTMLHeader($header);
		$mpdf->SetHTMLFooter($footer);

		$mpdf->AddPage('L');

		if( $this->_sa_uplatama == 'da')
		{
			$mpdf->WriteHTML( file_get_contents('resources/css/fakturisanoUplatePFKIzvestaj.css'), 1 );
		}
		else if( $this->_sumarno_po_filmu == 'da' )
		{
			$mpdf->WriteHTML( file_get_contents('resources/css/fakturisanoSumarnoPFKIzvestaj.css'), 1 );	
		}
		else
		{
			$mpdf->WriteHTML( file_get_contents('resources/css/fakturisanoPFKIzvestaj.css'), 1 );	
		}
		
		$mpdf->WriteHTML( $this->load->view( 'fakturisanoPFK', $view_data, true ) );
		$mpdf->Output();

		error_reporting( $error_level );
		
	}
	
	protected function _nefakturisano()
	{
		$result = $this->lkNefakturisaniFilmoviRead( true );
		
		if( $this->_sumarno_po_filmu == 'da' )
		{
			$sumarno_data = array();
			
			foreach( $result as $row )
			{
				if( array_key_exists( $row[ 'film_id' ], $sumarno_data ) )
				{
					$temp = $sumarno_data[ $row[ 'film_id' ] ];
					
					$temp[ 'neto_zarada' ] += $row[ 'neto_zarada' ];
					$temp[ 'bruto_zarada' ] += $row[ 'bruto_zarada' ];
					$temp[ 'porez' ] += $row[ 'porez' ];
					 
					$sumarno_data[ $row[ 'film_id' ] ] = $temp;
				}
				else
				{
					$sumarno_data[ $row[ 'film_id' ] ] = $row;
				}
			}
		}
		else
		{
			$sumarno_data = $result;
		}
		
		$bruto_suma = 0;
		$neto_suma = 0;
		$pdv_suma = 0;
		
		$formated_data = array();
		
		foreach( $sumarno_data as $row )
		{
			$bruto_suma += $row[ 'bruto_zarada' ];
			$neto_suma += $row[ 'neto_zarada' ];
			$pdv_suma += $row[ 'porez' ];
		
			$row[ 'bruto_zarada' ] = number_format(  $row[ 'bruto_zarada' ], $this->_decimal_point );
			$row[ 'neto_zarada' ] = number_format( $row[ 'neto_zarada' ], $this->_decimal_point );
			$row[ 'porez' ] = number_format( $row[ 'porez' ], $this->_decimal_point );
			
			array_push( $formated_data, $row );
		}
		
		$interval = date ( "d/m/Y", strtotime ( $this->_datum_od ) ) . ' - ' . date ( "d/m/Y", strtotime ( $this->_datum_do ) );
		
		$view_data = array( 'data' => $formated_data, 
							 
							'sumarno_po_filmu' => $this->_sumarno_po_filmu,
							'datumski_interval' => $interval,
							'naziv_komitenta' => $this->_naziv_komitenta,

							'bruto_suma' => number_format( $bruto_suma, $this->_decimal_point ),
							'neto_suma' => number_format( $neto_suma, $this->_decimal_point ),
							'pdv_suma' => number_format( $pdv_suma, $this->_decimal_point )
							
					
		);
		
		
		$view_parsed = $this->load->view( 'nefakturisanoPFK', $view_data, true );
		
		//echo $view_parsed;
		//return;
		$error_level = error_reporting();
		

		require_once 'pdf/mpdf.php';
		
		error_reporting(0);

		$mpdf = new mPDF('utf-8',    // mode - default ''
		'',    // format - A4, for example, default ''
		0,     // font size - default 0
		'Verdana',    // default font family
		5,    // margin_left
		5,    // margin right
		5,     // margin top
		5,    // margin bottom
		9,     // margin header
		9,     // margin footer
		'L');  // L - landscape, P - portrait 
		
		
		$mpdf->setHeader();
		$mpdf->AddPage('L','','','','',5,5,5,5,5,5);
		$mpdf->WriteHTML( $view_parsed );
		$mpdf->Output();

		error_reporting($error_level);
	}
	
	protected function _selectFakture( $storno = FALSE )
	{
		$td;
		
		$storno == FALSE ? $td = "fakture" : $td = "fakture_storno";
		
		$this->db->select( "$td.*, zvanicna_gledanost.*, komitenti.*, filmovi.*" );
		
		$this->db->from( $td );
		
		$this->db->join( 'zvanicna_gledanost', "zvanicna_gledanost.z_gledanost_id = $td.z_gledanost_id", 'inner' );
		$this->db->join( 'komitenti', "komitenti.komitent_id = zvanicna_gledanost.komitent_id", 'inner' );
		$this->db->join( 'filmovi', "filmovi.film_id = zvanicna_gledanost.film_id", 'inner' );
		
		if( $this->_komitent_id )
		{
			$this->db->where( "zvanicna_gledanost.komitent_id", $this->_komitent_id );
		}
		
		if( $this->_film_id )
		{
			$this->db->where( "zvanicna_gledanost.film_id", $this->_film_id );
		}
		
		if( $this->_producent )
		{
			$this->db->where( "filmovi.producent_filma", $this->_producent );
		}
		
		if( $this->_datum_od && $this->_datum_do )
		{
			$this->db->where( "$td.datum_unosa_fakture >=", $this->_datum_od );
			$this->db->where( "$td.datum_unosa_fakture <=", $this->_datum_do );
		}
		
	}
	
}

?>