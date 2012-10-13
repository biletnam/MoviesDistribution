var Gledanost = function( app )
{
	var s = this;
	
	this.base = new ModuleBase( this );
	this.base.setApp( app );
	
	this.saveDialog = null;
	
	this.canDialogClose = false;
	
	this.zaradaRSDFieldClass = "";
	this.zaradaEURFieldClass = "";
	this.zaradaKMFieldClass = "";
	this.amountFieldClass = "";
	
	this.cenaRSDClass = "";
	this.cenaEURClass = "";
	this.cenaKMClass = "";
	
	this.calculateType = null;
	
	this.kursData = {};

	this.calculateTypes = { KARTE:-1, NAOCARI:1 }
	
	this.getDnevnaGledanostForms = function(e){
	
		s.canDialogClose = false;
		
		
		$( "#save-gledanost-dialog .text-message" ).empty().text( s.base.app.config.lang.formira_dnevna_gledanost + "..." );
		s.saveDialog.dialog( "open" );
		
		$.ajax({
				type: 'post',
				data:$( this ).serialize() + "&datum=" + $( "#datum-gledanosti-textfield-datepicker" ).val(),
				url: s.base.config.baseUri + "gledanost/getGledanostByDate/",
				success:function(data){
					
					s.canDialogClose = true;
					s.saveDialog.dialog( "close" );
					
					$( ".gledanost-tabela-form" ).unbind( "submit", s.submitGledanostForm );
					
					$( "input:not(:disabled)" ).each(function(index, element) {
						 $( element ).unbind("keydown", s.handleKeyUp );
						 $( element ).unbind("keypress", s.handleKeyPress );
					});
					
					$( "#gledanost-forms-cnt" ).empty().append( data );
					
					$( "#gledanost-kurs-data-form input" ).each(function(index, element) {    
						s.kursData[ element.id ] = element.value; 
					});
					
					$( "input:not(:disabled)" ).each(function(index, element) {
					   $( element ).attr( "autocomplete", "off" );
					   $( element ).bind("keydown", s.handleKeyPress ); 
					   $( element ).bind("keyup", s.handleKeyUp ); 
					});
					
					$( ".gledanost-tabela-form" ).bind( "submit", s.submitGledanostForm );
					
				}	
		});
							
		return false;						
	}

	this.handleKeyUp = function( e ){

		//console.log( 'Key code: ' + 	e.keyCode + '. Class: ' + $( e.target ).attr( "class" ) );

		// tab or shift pressed
		if( e.keyCode == 9 ||  e.keyCode == 16 ) return;
		
		var c = $( e.target ).attr( "class" );
		
		if( c.indexOf( "naocar" ) == -1 )
		{
			s.calculateType = s.calculateTypes.KARTE;
			
			s.zaradaRSDFieldClass  = "zarada_po_terminu_rsd";
			s.zaradaEURFieldClass  = "zarada_po_terminu_eur";
			s.zaradaKMFieldClass  = "zarada_po_terminu_km";
			s.amountFieldClass = "broj_gledalaca";
			
			s.cenaRSDClass = "cena_karte_rsd";
			s.cenaEURClass = "cena_karte_eur";
			s.cenaKMClass = "cena_karte_km";
		}
		else
		{
			s.calculateType = s.calculateTypes.NAOCARI;
			
			s.zaradaRSDFieldClass  = "zarada_naocara_po_terminu_rsd";
			s.zaradaEURFieldClass  = "zarada_naocara_po_terminu_eur";
			s.zaradaKMFieldClass  = "zarada_naocara_po_terminu_km";
			s.amountFieldClass = "broj_prodatih_naocara";
			
			s.cenaRSDClass = "cena_naocara_rsd";
			s.cenaEURClass = "cena_naocara_eur";
			s.cenaKMClass = "cena_naocara_km";	
		}
		
		if( c.indexOf( "rsd"  ) != -1 )
		{
			s.calculateRSDField( e.target );
		}
		else if( c.indexOf( "eur"  ) != -1 )
		{
			s.calculateEURField( e.target );
		}
		else if( c.indexOf( "km"  ) != -1 )
		{
			 s.calculateKMField( e.target );
		}
		else if( c.indexOf( "broj"  ) != -1 )
		{
			s.calcluateAll( e.target );
		}
		
		s.calculateSume( e.target );
	}
	
	this.handleKeyPress = function( e ){
		
		if( e.charCode == 0 ) return true;
		return s.allowedChar( e.charCode );
	}

	this.submitGledanostForm = function(e){
		
		s.canDialogClose = false;
		
		$( "#save-gledanost-dialog .text-message" ).empty().text( "Молимо сачекајте док се сачува дневна гледаност..." );
		s.saveDialog.dialog( "open" );
		
		var d = {};
		$( this ).find( "input" ).each(function(index, element) {
           d[ $( this ).attr( "name" ) ] = $( this ).val();
        });
		
		d.status_gledanosti = $( this ).find('input:radio[name="status_gledanosti"]:checked').val() || "";
		d.datum_gledanosti = $( "#datum-gledanosti-textfield-datepicker" ).val();
		
		$.ajax({
					type: 'post',
					data: d,
					url: s.base.config.baseUri + "gledanost/saveGledanost/",
					success:function(data){
						s.canDialogClose = true;
						s.saveDialog.dialog( "close" );
				}
		});
		
		$.ajax({
					type: 'get',
					url: s.base.config.baseUri + "rokovnici/updateRokovnik/" + d['rokovnik_id'] + "/true/",
					success:function(data){
				}
		});
		
		return false;
	};
}

Gledanost.prototype.allowedChar = function( code ){
			
	if( code == 8 ) return true;
	
	var reg = new RegExp("[0-9\.]+");
	return reg.test( String.fromCharCode( code ) );
}

Gledanost.prototype.init = function()
{
	var s = this;
	
	$.ajax({
 		type: 'get',
 		url: this.base.config.baseUri + "gledanost/",
 		success: function(data)
 		{
			s.base.mainView = data;
			s.base.app.moduleReadyHandler( s );
			
			$( "#datum-gledanosti-textfield-datepicker" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } ).attr( "autocomplete", "off" );
			
			s.saveDialog = $( "#save-gledanost-dialog" ).dialog({
								tite:"Учитавање",
								autoOpen: false,
								height: 250,
								width: 700,
								modal: true,
								draggable:false,
								beforeClose: function(event, ui){
									return s.canDialogClose;
								}
					});
			
			
			$( "#nadji-gledanost-button" ).click(function(){
				
				$( "#dnevna-gledanost-pretraga-form" ).unbind( "submit", s.getDnevnaGledanostForms );
				
				var b = this;
				
				$.ajax({
						type: 'post',
						data:{ datum: $( "#datum-gledanosti-textfield-datepicker" ).val() },
						url: s.base.config.baseUri + "gledanost/getAvailableGledanost/",
						success:function(data){
						
							$( "#gledanost-content" ).empty().append( data );
							
							$( "#prikazi-gledanosti-button" ).click(function(e){
				
								window.open( s.base.config.baseUri + "gledanost/prikaziDnevnuGledanost/?datum_gledanosti=" + $( "#datum-gledanosti-textfield-datepicker" ).val() );
								
								return false;
								
							});
							
							$( "#dnevna-gledanost-pretraga-form" ).bind( "submit", s.getDnevnaGledanostForms );
							
						},
						
						onerror:function( e ){
							
						}
						
				});
			});
		}
	});
}

Gledanost.prototype.calculateRSDField = function( el, g ){
	
	//gledanost * cena / kurs_e = 1 * 1 / 99 = 0.01 eur
	if( ! g )
		g = $( el ).parent().parent().find( "." + this.amountFieldClass + ":eq(0)" ).val() || 0;
	
	var v = $( el ).val() || 0;
	
	 $( el ).parent().parent().find( "." + this.cenaEURClass + ":eq(0)" ).val( ( v * this.kursData.kurs_data_faktor_eur ).toFixed( 4 ) );
	 $( el ).parent().parent().find( "." + this.cenaKMClass + ":eq(0)" ).val( ( v * this.kursData.kurs_data_faktor_km ).toFixed( 4 ) );
	
	this.calculateZaradaRSD( el, v, g );
};

Gledanost.prototype.calculateEURField = function( el, g ){
	
	if( ! g )
		g = $( el ).parent().parent().find( "." + this.amountFieldClass + ":eq(0)" ).val() || 0;
		
	var v = $( el ).val() || 0;	
	
	var crsd  = v * this.kursData.kurs_data_eur;
	
	$( el ).parent().parent().find( "." + this.cenaRSDClass + ":eq(0)" ).val( crsd.toFixed( 4 ) );
	$( el ).parent().parent().find( "." + this.cenaKMClass + ":eq(0)" ).val( ( crsd * this.kursData.kurs_data_faktor_km ).toFixed( 4 ) );
	 
	this.calculateZaradaEUR( el, v, g );	
	
};

Gledanost.prototype.calculateKMField = function( el, g ){
	
	if( ! g )
		g = $( el ).parent().parent().find( "." + this.amountFieldClass + ":eq(0)" ).val() || 0;
		
	var v = $( el ).val() || 0;
	
	var crsd  = v * this.kursData.kurs_data_km;
	
	$( el ).parent().parent().find( "." + this.cenaRSDClass + ":eq(0)" ).val( crsd.toFixed( 4 ) );
	
	var eur = v * this.kursData.kurs_data_km_eur;
	$( el ).parent().parent().find( "." + this.cenaEURClass + ":eq(0)" ).val( eur.toFixed( 4 ) );
	
	this.calculateZaradaKM( el, v, g );
}


Gledanost.prototype.calculateZaradaRSD = function( el, v, g ){
	
	var ef = $( el ).parent().parent().find( '.' + this.cenaEURClass + ":eq(0)" ).val();
	var kf = $( el ).parent().parent().find( '.' + this.cenaKMClass + ":eq(0)" ).val();
	
	var zr = v * g;
	var ze = ef * g;
	var zk = kf * g;
		
	$( el ).parent().parent().find( "." + this.zaradaRSDFieldClass + ":eq(0)" ).val( zr.toFixed( 4 ) );
	$( el ).parent().parent().find( "." + this.zaradaEURFieldClass + ":eq(0)" ).val( ze.toFixed( 4 ) );
	$( el ).parent().parent().find( "." + this.zaradaKMFieldClass + ":eq(0)" ).val( zk.toFixed( 4 ) );
}

Gledanost.prototype.calculateZaradaEUR = function( el, v, g )
{
	
	var rf = $( el ).parent().parent().find( '.' + this.cenaRSDClass + ":eq(0)" ).val();
	var kf = $( el ).parent().parent().find( '.' + this.cenaKMClass + ":eq(0)" ).val();
	
	//	( gledanost * cena ) * EUR; 1G * 1EUR * 99 = 99RSD 
	//( gledanost * cena )  1G * 1E = ?KM  1E * 99RSD = 99RSD * FACT_KM = 99 * 0.02 = 1.98 KM 
	var zr =  rf * g;
	var ze = v * g;
	var zk = kf * g;
	
	$( el ).parent().parent().find( "." + this.zaradaRSDFieldClass + ":eq(0)" ).val( zr.toFixed( 4 ) );
	$( el ).parent().parent().find( "." + this.zaradaEURFieldClass + ":eq(0)" ).val( ze.toFixed( 4 ) );
	$( el ).parent().parent().find( "." + this.zaradaKMFieldClass + ":eq(0)" ).val( zk.toFixed( 4 ) );
	
}

Gledanost.prototype.calculateZaradaKM = function( el, v, g ){
	
	var rf = $( el ).parent().parent().find( '.' + this.cenaRSDClass + ":eq(0)" ).val();
	var ef = $( el ).parent().parent().find( '.' + this.cenaEURClass + ":eq(0)" ).val();
	
	//( gledanost * cena ) 5KM * 50RSD = 250 RSD * 0.01FACT_EUR = 2.5 EUR
	
	var zr = rf * g;
	var ze = ef * g;
	var zk = v * g;
	
	$( el ).parent().parent().find( "." + this.zaradaRSDFieldClass + ":eq(0)" ).val( zr.toFixed( 4 ) );
	$( el ).parent().parent().find( "." + this.zaradaEURFieldClass + ":eq(0)" ).val( ze.toFixed( 4 ) );
	$( el ).parent().parent().find( "." + this.zaradaKMFieldClass + ":eq(0)" ).val( zk.toFixed( 4 ) );
}

Gledanost.prototype.calcluateAll = function( el )
{		
	this.calculateZaradaRSD( el, $( el ).parent().parent().find( "." + this.cenaRSDClass + ":eq(0)" ).val() || 0, el.value );
	this.calculateZaradaEUR( el, $( el ).parent().parent().find( "." + this.cenaEURClass + ":eq(0)" ).val() || 0, el.value );
	this.calculateZaradaKM( el,  $( el ).parent().parent().find( "." + this.cenaKMClass  + ":eq(0)" ).val() || 0, el.value );
};


Gledanost.prototype.calculateSume = function( el )
{
	var s = this;
	
	var zur = 0;
	var zue = 0;
	var zuk = 0;
	var sauk = 0;
	
	var szurName = "";
	var szueName = "";
	var szukName = "";
	var saukName = "";
	
	if( this.calculateType == this.calculateTypes.KARTE )
	{
		szurName = "suma_zarada_karte_rsd";
		szueName = "suma_zarada_karte_eur";
		szukName = "suma_zarada_karte_km";
		saukName = "suma_gledanosti";

	}
	else
	{
		szurName = "suma_zarada_naocare_rsd";
		szueName = "suma_zarada_naocare_eur";
		szukName = "suma_zarada_naocare_km";
		saukName = "suma_prodatih_naocara";
	}
	
	$( el ).parent().parent().parent().find( "." + this.zaradaRSDFieldClass + 
										     ",." + this.zaradaEURFieldClass + 
										     ",." + this.zaradaKMFieldClass + 
										     ",." + this.amountFieldClass ).each(function(index, element) {
	  
		if( $( element ).attr( "class" ) == s.zaradaRSDFieldClass )
		{
			zur +=  parseFloat(  $( element ).val() || 0 );
		}
		else if( $( element ).attr( "class" ) == s.zaradaEURFieldClass )
		{
			zue +=  parseFloat(  $( element ).val() || 0 );
		}
		else if( $( element ).attr( "class" ) == s.zaradaKMFieldClass )
		{
			zuk +=  parseFloat(  $( element ).val() || 0 );
		}
		else if( $( element ).attr( "class" ) == s.amountFieldClass )
		{
			sauk += parseFloat(  $( element ).val() || 0 );

			if( $( element ).val().length > 0 )
				s.calcluateAll( element );
		}


	});
	
	$( el ).parent().parent().parent().find( 'input[name="' + szurName + '"]' ).val( zur.toFixed( 4 ) );
	$( el ).parent().parent().parent().find( 'input[name="' + szueName + '"]' ).val( zue.toFixed( 5 ) );
	$( el ).parent().parent().parent().find( 'input[name="' + szukName + '"]' ).val( zuk.toFixed( 6 ) );
	$( el ).parent().parent().parent().find( 'input[name="' + saukName + '"]' ).val( sauk.toFixed( 4 ) );
}