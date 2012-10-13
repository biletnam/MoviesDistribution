<?php

if( ! function_exists( "getGledanostInputElement" ) )
{
	function &getGledanostInputElement( &$data, &$tdi, &$i, $name, $size = 8, $disabled = false )
	{	
		(string)$in = SAVE_CELL_PREFIX_NAME . "termini_" . $i . POST_ARRAY_DELIMITER . $name;
		(string)$is = '';
		
		$td = @$data[ $tdi ];
		
		$is .= '<input type="text" name="' . $in . '" size="' . $size . '" class="'.$name.'"';
	
		if( $td && (int)@$td[ 'redni_broj_termina' ] === ($i + 1) )
		{
			$is .= ' value="'. @$td[ $name ] . '"';
		}
				
		if( $disabled ) $is .= ' disabled="disabled"';
		
		$is .=  ' />';
		
		return $is;
	} 
	
}


if( ! function_exists( "getTehnikaKopijeFilma" ) )
{
	function getTehnikaKopijeFilma( $id )
	{	
		$tehnika = "";
		
		switch( $id )
		{
			case 1:
			return '35mm';
			break;
			
			case 2:
			return '3D';
			break;
			
			case 3:
			return '2D';
			break;
		}
	} 
	
}


?>