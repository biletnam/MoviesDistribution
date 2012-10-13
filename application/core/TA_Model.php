<?php

class TA_Model extends CI_Model {
	
	public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    public function getRowsOffset( $page, $rowsPerPage )
	{
		if( $page < 0 || $rowsPerPage < 0 ) return 0;
		
		
		return $rowsPerPage * $page - $rowsPerPage;
	}

	public function brojDokumenta( $table_name, $auto_inc_id, $id, $tip )
	{
		$this->db->where( "YEAR( datum_unosa ) = ", DOC_YEAR );
		$this->db->where( "tip",  $tip );
		$query = $this->db->get( $table_name );
		
		if( $query )
		{
	     	$dokBroj =  $query->num_rows();
	     	
	     	if( ! $dokBroj  )
	     	{ 
	     		$dokBroj = 1; 
	     	}
	     	else 
	     	{
	     		$dokBroj++;
	     	}
		}
		else
		{
	  		echo ErrorCodes::DATABASE_ERROR;
	  		exit();
		}
		
	   $year = DOC_YEAR;	
	   $leadingZeros = 6 - strlen( $dokBroj );
	   
	   $brojDokumenta = "";
	   
	   for( $l = 0; $l < $leadingZeros - 1; $l++ )
	   	$dokBroj = '0' . $dokBroj;
	   
	   $brojDokumenta = $year . $id . $dokBroj;
	   
	   if( $tip == 'cg') 
	   {
	   		$brojDokumenta = $brojDokumenta . "-CG";
	   }
	   
	   return $brojDokumenta;
	}

	public function getDateWithoutWeekend( $date, $add_to_date = 0 )
	{
		//for getdate to work default_time_zone need to be set. look at ./index.php
		$date_unix = strtotime( $date ) + $add_to_date;
		$date = getdate(  $date_unix );	
		
		$wday = $date[ "wday" ];
		$mon = $date[ "mon" ];
		$year = $date[ "year" ];
		
		$date_without_weekend = $date;
		
		$new_date_string = "";
		
		if( $wday == 6 || $wday == 0 )
		{
			// its Saturday
			if( $wday == 6 )
			{
				$date_without_weekend = getdate( $date_unix + ( 2 * 24 * 60 * 60 ) );
			}
			else
			{
				//its Sunday
				$date_without_weekend = getdate( $date_unix + ( 24 * 60 * 60 ) );
			}
		}
		
		$m = $date_without_weekend[ "mon" ];
		if( strlen( $m ) == 1 ) $m = "0" . $m;
		
		$d = $date_without_weekend[ "mday" ];
		if( strlen( $d ) == 1 ) $d = "0" . $d;
		
		return $date_without_weekend[ "year" ] . "-" . $m . "-" . $d;
	}	

}

?>