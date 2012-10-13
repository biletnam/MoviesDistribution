
var ZvanicnaGledanost = function( app )
{
	var s = this;
	
	this.base = new ModuleBase( this );
	this.base.setApp( app );
	this.zvanicnaGledanostGrid = null;
	this.zvanicnaGledanostSelectedRowId;
	this.faktureDialog = null;
	this.naprednaPretragaDialog = null;
	
	this.advancedSearch = false;
	
	this.sacuvajFakturu = function(){
		
		var valuta = $( "input[name='" + s.base.app.config.ICP + "valuta_fakture']:checked" ).val();
		var rok_placanja = $( "#rok-placanja-fakture-input" ).val();
		var dpo = $( "#datum-prometa-fakture-input" ).val();
		var raspodela_naocare = $( "#raspodela_naocare-fakture-input" ).val() || 0;
		
		
		if( valuta && rok_placanja && dpo )
		{
			var rd = s.zvanicnaGledanostGrid.getRowData( s.zvanicnaGledanostSelectedRowId );
				rd[ s.base.app.config.ICP + 'valuta_fakture' ] = valuta;
				rd[ s.base.app.config.ICP + 'rok_placanja_fakture' ] = rok_placanja;
				rd[ s.base.app.config.ICP + 'datum_prometa_fakture' ] = $( $( "#datum-prometa-fakture-input" ).datepicker( "option", "altField" ) ).val();
				rd[ s.base.app.config.ICP + 'raspodela_naocare' ] = raspodela_naocare;
				rd[ 'sa_porezom_naocare' ] = ( $( "#sa-porezom-naocare-fakture-input:checked" ).length === 1 ) ?  true : false ;
				
				
			$.ajax({
				type: 'post',
				url: s.base.config.baseUri + "fakture/create/",
				data:rd,
				success: function(data)
				{
					if( data == 0 )
					{
						$( "#save-faktura-btn" ).attr( "disabled", true );
						s.base.app.setInfoText( $( "#nova-faktura-info-text" ), s.base.app.config.lang.sacuvana_faktura );
					}
					else
					{
						s.base.app.setInfoText( $( "#nova-faktura-info-text" ), s.base.app.config.lang.dogodila_se_greska, true );
					}
				}
			});		
		}
		else
		{
			alert( s.base.app.config.lang.forma_nije_validna );
		}
	}
	
	this.prikaziFakturu = function(){
		
		var fids = 	s.zvanicnaGledanostGrid.jqGrid( "getGridParam", "selarrrow" );
		var fsids = "";
		
		$( fids ).each(function(index, element){
            fsids += fids[index] + ",";
        });
		
		$.ajax({
				type: 'post',
				url: s.base.config.baseUri + "fakture/encodeIds/",
				data:{ faktureIds: fsids.substring( -1, fsids.length - 1 ) },
				success: function(data)
				{
					window.open( s.base.config.baseUri + "fakture/prikaziFakturu/?fakture=" + data, "_blank" )
				}
			});
	}
	
	this.prikaziZvanicnuGledanost = function(){
		
		var fids = 	s.zvanicnaGledanostGrid.jqGrid( "getGridParam", "selarrrow" );
		var fsids = "";
		
		$( fids ).each(function(index, element){
            fsids += fids[index] + ",";
        });
		
		$.ajax({
				type: 'post',
				url: s.base.config.baseUri + "zvanicnaGledanost/encodeIds/",
				data:{ zGledanostIds: fsids.substring( -1, fsids.length - 1 ) },
				success: function(data)
				{
					window.open( s.base.config.baseUri + "zvanicnaGledanost/prikaziGledanost/?gledanost=" + data, "_blank" );
				}
			});
	}
	
	this.obrisiZvanicnuGledanost = function()
	{
		if( s.zvanicnaGledanostSelectedRowId )
		{
			if( confirm( s.base.app.config.lang.obrisi_zg_upozorenje ) )
			{
				$.ajax({
					type: 'post',
					url: s.base.config.baseUri + "zvanicnaGledanost/obrisiZvanicnuGledanost/" + s.zvanicnaGledanostSelectedRowId,
					success: function(data)
					{
						if( data != 0 )
						{
							alert( s.base.app.config.lang.dogodila_se_greska );
						}
						else
						{
							s.zvanicnaGledanostGrid.trigger( "reloadGrid" );
						}
						
					}
				});
			}			
		}
		else
		{
			alert( s.base.app.config.lang.odaberi_zg );
		}
	}
	
	this.stornirajZGledanost = function()
	{
		if( s.zvanicnaGledanostSelectedRowId )
		{
			if( confirm( s.base.app.config.lang.storniraj_zg_upozorenje ) )
			{								
				$.ajax({
					type: 'post',
					url: s.base.config.baseUri + "zvanicnaGledanost/stornirajZvanicnuGledanost/" + s.zvanicnaGledanostSelectedRowId,
					success: function( data )
					{
						if( data == 0 )
						{
							s.zvanicnaGledanostGrid.trigger( "reloadGrid" );
						}
						else
						{
							alert( s.base.app.config.lang.dogodila_se_greska );
						}
					}
				});
			}
		}
		else
		{
			alert( s.base.app.config.lang.odaberi_zg );
		}
	}
	
	this.povratiZGledanost = function()
	{
		if( s.zvanicnaGledanostSelectedRowId )
		{
			if( confirm( s.base.app.config.lang.povrati_zg_upozorenje ) )
			{								
				$.ajax({
					type: 'post',
					url: s.base.config.baseUri + "zvanicnaGledanost/povratiZvanicnuGledanost/" + s.zvanicnaGledanostSelectedRowId,
					success: function( data )
					{
						if( data == 0 )
						{
							s.zvanicnaGledanostGrid.trigger( "reloadGrid" );
						}
						else
						{
							alert( s.base.app.config.lang.dogodila_se_greska );
						}
					}
				});
			}
		}
		else
		{
			alert( s.base.app.config.lang.odaberi_zg );
		}
	};
	
}

ZvanicnaGledanost.prototype.init = function()
{
	var s = this;
	
	$.ajax({
 		type: 'get',
 		url: this.base.config.baseUri + "zvanicnaGledanost/",
 		success: function(data)
 		{
			s.base.mainView = data;
			s.base.app.moduleReadyHandler( s );
			
			$( "#preview-zvanicne-gledanosti-btn" ).click( s.prikaziZvanicnuGledanost );
			$( "#delete-zvanicna-gledanost-btn" ).click( s.obrisiZvanicnuGledanost );
			$( "#storniraj-zvanicnu-gledanost-btn" ).click( s.stornirajZGledanost );
			$( "#povrati-zvanicnu-gledanost-btn" ).click( s.povratiZGledanost );
			
			$( "#datum_od_z_gledanosti_pretraga_input" ).datepicker( { dateFormat:"dd/mm/yy", altFormat: "yy-mm-dd", monthNames:s.base.config.monthNames } );
			$( "#datum_do_z_gledanosti_pretraga_input" ).datepicker( { dateFormat:"dd/mm/yy", altFormat: "yy-mm-dd", monthNames:s.base.config.monthNames } );
			
			s.faktureDialog = $( "#nova-faktura-dialog" ).dialog({
								title: s.base.app.config.lang.nova_faktura,
								autoOpen: false,
								height: 350,
								width: 710,
								modal: true,
								resizable:false,
								draggable:true,
								open: function(event, ui) {
									
									$( "#sa-porezom-naocare-fakture-input" ).attr( "checked", "checked" );
									
									var rd = s.zvanicnaGledanostGrid.getRowData( s.zvanicnaGledanostSelectedRowId );
									
									$( '#rok-placanja-fakture-input' ).val( "2" );
									$( "#datum-prometa-fakture-input" ).datepicker( );
									
									var datum = new Date();
										datum.setTime( Date.parse( rd[ s.base.app.config.SCPN + 'zadnji_dan_gledanosti' ] ) );
									
										
									$( "#datum-prometa-fakture-input" ).datepicker( "setDate", datum );
									
									if( rd[ s.base.app.config.SCPN + 'tip_raspodele' ] == 3 )
									{
										$( "#raspodela_naocare-fakture-input" ).val( rd[ s.base.app.config.SCPN + 'raspodela_iznos' ] ).removeAttr( "disabled" );
									}
									else
									{
										$( "#raspodela_naocare-fakture-input" ).val( "n/a" ).attr( "disabled", true );
									}
									
									$( "#save-faktura-btn" ).removeAttr( "disabled" );
						
									
								},
								create:function(){
									
									$( "#preview-faktura-btn" ).click( s.prikaziFakturu );
									$( "#save-faktura-btn" ).click( s.sacuvajFakturu );
									
									$( "#datum-prometa-fakture-input" ).attr( "autocomplete", "off" ).datepicker( { altField: '#datum-prometa-fakture-input-insert-format', 
																													 dateFormat: "dd/mm/yy", 
																													 altFormat: "yy-mm-dd", 
																													 monthNames:s.base.config.monthNames } );
							}
			});
				
			$("#create-faktura-btn" ).click(function( e ) {
			
				if( s.zvanicnaGledanostSelectedRowId )
				{
					s.base.app.setInfoText( $( "#nova-faktura-info-text" ), "" );
					
					var rd = s.zvanicnaGledanostGrid.getRowData( s.zvanicnaGledanostSelectedRowId );
				
					if( rd[ s.base.app.config.SCPN + 'stornirana' ] == 0 )
					{
						
						s.faktureDialog.dialog( "open" );
					}
					else
					{
						alert( s.base.app.config.lang.storno_zg_faktura );
					}
				}
				else
				{
					alert( s.base.app.config.lang.odaberi_zg );
				}
			});
			
			
			var buttons = {};
				buttons[ s.base.app.config.lang.trazi ] = function() {
										s.advancedSearch = true;
										s.zvanicnaGledanostGrid.trigger( "reloadGrid" );	
									};
									
				buttons[ s.base.app.config.lang.resetuj ] = function() {
										s.advancedSearch = false;
										$('#zvanicna-gledanost-pretraga-form').each (function(){
											  this.reset();
										});
										
										setTimeout(function(){
											s.zvanicnaGledanostGrid.trigger( "reloadGrid" );
										}, 100 );
									};
									
									
			
			s.naprednaPretragaDialog = $( "#zvanicna-gledanost-napredna-pretraga-dialog" ).dialog({
							title: s.base.app.config.lang.napredna_pretraga,
							autoOpen: false,
							height: 480,
							width: 550,
							modal: false,
							buttons: buttons
		});


		$( "#pretraga-zvanicne-gledanosti-btn" ).click(function() {
				s.naprednaPretragaDialog.dialog( "open" );
			});
			
			
			
		
			s.zvanicnaGledanostGrid = $("#zvanicnaGledanost-grid").jqGrid({
						width:1900,
						height:670,
						url: s.base.config.baseUri + "zvanicnaGledanost/read/",
						multiselect:true,
						cellEdit:true,
						datatype: 'xml',
						mtype: 'POST',
						prmNames: s.base.app.config.paramNames,
						xmlReader: { 
							  repeatitems:false,
							  id: 'z_gledanost_id'
						},
						colModel :[ 
						  {
							    label: s.base.app.config.lang.sifra,
								name: s.base.app.config.SCPN + 'z_gledanost_id', 
								index: 'z_gledanost_id', 
								xmlmap:'z_gledanost_id',
								width:55
						  }, 
						  {
							  	label: s.base.app.config.lang.sifra_rokovnika,
								name: s.base.app.config.SCPN + 'rokovnik_id', 
								index: 'rokovnik_id', 
								xmlmap:'rokovnik_id',
								width:55
						  }, 
						  {
							  label: s.base.app.config.lang.broj_dokumenta,
							  name: s.base.app.config.SCPN + 'broj_dokumenta_z_gledanosti', 
							  index:'broj_dokumenta_z_gledanosti',
							  xmlmap:'broj_dokumenta_z_gledanosti',
							  width:100
						  },
						  {
							  label: s.base.app.config.lang.datum_od,
							  name: s.base.app.config.SCPN + 'datum_z_gledanost_od', 
							  index:'datum_z_gledanost_od',
							  xmlmap:'datum_z_gledanost_od',
							  formatter:'date',
							  formatoptions:{ srcformat:'Y-m-d', newformat:'d/m/Y' }, 
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.datum_do,
							  name: s.base.app.config.SCPN + 'datum_z_gledanost_do', 
							  index:'datum_z_gledanost_do',
							  xmlmap:'datum_z_gledanost_do', 
							  formatter:'date',
							  formatoptions:{ srcformat:'Y-m-d', newformat:'d/m/Y' },
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.ukupno_gledalaca,
							  name: s.base.app.config.SCPN + 'ukupno_gledalaca', 
							  index:'ukupno_gledalaca',
							  xmlmap:'ukupno_gledalaca', 
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.ukupan_prihod_karte,
							  name: s.base.app.config.SCPN + 'ukupan_prihod_karte_stampa', 
							  index:'ukupan_prihod_karte_stampa',
							  xmlmap:'ukupan_prihod_karte_stampa', 
							  hidden:true,
							  editrules:{ edithidden:true },
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.ukupan_prihod_karte_eur,
							  name: s.base.app.config.SCPN + 'ukupan_prihod_karte_eur_stampa', 
							  index:'ukupan_prihod_karte_eur_stampa',
							  xmlmap:'ukupan_prihod_karte_eur_stampa', 
							  hidden:true,
							  editrules:{ edithidden:true },
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.ukupan_prihod,
							  name: s.base.app.config.SCPN + 'ukupan_prihod_stampa', 
							  index:'ukupan_prihod_stampa',
							  xmlmap:'ukupan_prihod_stampa', 
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.ukupan_prihod_eur,
							  name: s.base.app.config.SCPN + 'ukupan_prihod_eur_stampa', 
							  index:'ukupan_prihod_eur_stampa',
							  xmlmap:'ukupan_prihod_eur_stampa', 
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.prodato_naocara,
							  name: s.base.app.config.SCPN + 'ukupno_prodato_naocara', 
							  index:'ukupno_prodato_naocara',
							  xmlmap:'ukupno_prodato_naocara', 
							  width:80,
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: s.base.app.config.lang.ukupan_prihod_naocare,
							  name: s.base.app.config.SCPN + 'ukupan_prihod_naocare_stampa', 
							  index:'ukupan_prihod_naocare_stampa',
							  xmlmap:'ukupan_prihod_naocare_stampa', 
							  width:80,
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: s.base.app.config.lang.ukupan_prihod_naocare_eur,
							  name: s.base.app.config.SCPN + 'ukupan_prihod_naocare_eur_stampa', 
							  index:'ukupan_prihod_naocare_eur_stampa',
							  xmlmap:'ukupan_prihod_naocare_eur_stampa', 
							  width:80
						  },
						  {
							  label:s.base.app.config.lang.ukupan_prihod_karte,
							  name: s.base.app.config.SCPN + 'ukupan_prihod_karte', 
							  index:'ukupan_prihod_karte',
							  xmlmap:'ukupan_prihod_karte', 
							  hidden:true,
							  editrules:{ edithidden:true },
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.ukupan_prihod_karte_eur,
							  name: s.base.app.config.SCPN + 'ukupan_prihod_karte_eur', 
							  index:'ukupan_prihod_karte_eur',
							  xmlmap:'ukupan_prihod_karte_eur', 
							  hidden:true,
							  editrules:{ edithidden:true },
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.ukupan_prihod,
							  name: s.base.app.config.SCPN + 'ukupan_prihod', 
							  index:'ukupan_prihod',
							  xmlmap:'ukupan_prihod', 
							  hidden:true,
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.ukupan_prihod_eur,
							  name: s.base.app.config.SCPN + 'ukupan_prihod_eur', 
							  index:'ukupan_prihod_eur',
							  xmlmap:'ukupan_prihod_eur', 
							  hidden:true,
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.prodato_naocara,
							  name: s.base.app.config.SCPN + 'ukupno_prodato_naocara', 
							  index:'ukupno_prodato_naocara',
							  xmlmap:'ukupno_prodato_naocara', 
							  width:80,
							  hidden:true
						  },
						  {
							  label: s.base.app.config.lang.ukupan_prihod_naocare,
							  name: s.base.app.config.SCPN + 'ukupan_prihod_naocare', 
							  index:'ukupan_prihod_naocare',
							  xmlmap:'ukupan_prihod_naocare', 
							  width:80,
							  hidden:true
						  },
						  {
							  label: s.base.app.config.lang.ukupan_prihod_naocare_eur,
							  name: s.base.app.config.SCPN + 'ukupan_prihod_naocare_eur', 
							  index:'ukupan_prihod_naocare_eur',
							  xmlmap:'ukupan_prihod_naocare_eur', 
							  hidden:true,
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.crveni_krst,
							  name: s.base.app.config.SCPN + 'crveni_krst', 
							  index:'crveni_krst',
							  xmlmap:'crveni_krst', 
							  width:80,
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: s.base.app.config.lang.ostalo,
							  name: s.base.app.config.SCPN + 'ostalo', 
							  index:'ostalo',
							  xmlmap:'ostalo', 
							  width:80,
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: s.base.app.config.lang.tip_raspodele,
							  name: s.base.app.config.SCPN + 'tip_raspodele', 
							  index:'tip_raspodele',
							  xmlmap:'tip_raspodele',
							  hidden:true, 
							  width:80,
							  formatter:"select",
							  editrules:{ edithidden:true },
							  editoptions:{ value:{ 1: s.base.app.config.lang.minimalna_garancija, 2: s.base.app.config.lang.ugovoren_iznos, 3: s.base.app.config.lang.raspodela } }
						  },
						  {
							  label: s.base.app.config.lang.raspodela_iznos,
							  name: s.base.app.config.SCPN + 'raspodela_iznos', 
							  index:'raspodela_iznos',
							  xmlmap:'raspodela_iznos',
							  hidden:true, 
							  width:80,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: s.base.app.config.lang.za_raspodelu,
							  name: s.base.app.config.SCPN + 'za_raspodelu_rsd_stampa', 
							  index:'za_raspodelu_rsd_stampa',
							  xmlmap:'za_raspodelu_rsd_stampa', 
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.za_distributera,
							  name: s.base.app.config.SCPN + 'za_distributera_rsd_stampa', 
							  index:'za_distributera_rsd_stampa',
							  xmlmap:'za_distributera_rsd_stampa', 
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.vrednost_poreza,
							  name: s.base.app.config.SCPN + 'iznos_pdv_rsd_stampa', 
							  index:'iznos_pdv_rsd_stampa',
							  xmlmap:'iznos_pdv_rsd_stampa', 
							  width:80
						  },
						   {
							  label: s.base.app.config.lang.raspodela_iznos,
							  name: s.base.app.config.SCPN + 'raspodela_iznos', 
							  index:'raspodela_iznos',
							  xmlmap:'raspodela_iznos',
							  hidden:true
						  },
						  {
							  label: s.base.app.config.lang.za_raspodelu,
							  name: s.base.app.config.SCPN + 'za_raspodelu_rsd', 
							  index:'za_raspodelu_rsd',
							  xmlmap:'za_raspodelu_rsd', 
							  width:80,
							  hidden:true
						  },
						  {
							  label: s.base.app.config.lang.za_distributera,
							  name: s.base.app.config.SCPN + 'za_distributera_rsd', 
							  index:'za_distributera_rsd',
							  xmlmap:'za_distributera_rsd', 
							  width:80,
							  hidden:true
						  },
						  {
							  label: s.base.app.config.lang.vrednost_poreza,
							  name: s.base.app.config.SCPN + 'iznos_pdv_rsd', 
							  index:'iznos_pdv_rsd',
							  xmlmap:'iznos_pdv_rsd', 
							  width:80,
							  hidden:true
						  },
						  {
							  label: s.base.app.config.lang.pdv,
							  name: s.base.app.config.SCPN + 'pdv_procenat_rsd', 
							  index:'pdv_procenat_rsd',
							  xmlmap:'pdv_procenat_rsd', 
							  width:40
						  },
						  {
							  label: s.base.app.config.lang.datum_kopije_od,
							  name: s.base.app.config.SCPN + 'datum_kopije_od', 
							  index:'datum_kopije_od',
							  xmlmap:'datum_kopije_od', 
							  formatter:'date',
							  formatoptions:{ srcformat:'Y-m-d', newformat:'d/m/Y' },
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.datum_kopije_do,
							  name: s.base.app.config.SCPN + 'datum_kopije_do', 
							  index:'datum_kopije_do',
							  xmlmap:'datum_kopije_do',
							  formatter:'date',
							  formatoptions:{ srcformat:'Y-m-d', newformat:'d/m/Y' },
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.tehnika,
							  name: s.base.app.config.SCPN + 'tehnika_kopije_filma', 
							  index:'tehnika_kopije_filma',
							  xmlmap:'tehnika_kopije_filma', 
							  width:80,
							  formatter:"select", 
							  editoptions:{ value:{1:'35mm',2:'3D',3:'2D' }  },
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: s.base.app.config.lang.zadnji_dan_gledanosti,
							  xmlmap:'zadnji_dan_gledanosti', 
							  formatter:'date',
							  formatoptions:{ srcformat:'Y-m-d', newformat:'d/m/Y' },
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.zadnji_dan_gledanosti,
							  name: s.base.app.config.SCPN + 'zadnji_dan_gledanosti', 
							  index:'zadnji_dan_gledanosti',
							  xmlmap:'zadnji_dan_gledanosti', 
							  hidden:true,
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.naziv_filma,
							  name: s.base.app.config.SCPN + 'naziv_filma', 
							  index:'naziv_filma',
							  xmlmap:'naziv_filma', 
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.naziv_komitenta, 
							  name: s.base.app.config.SCPN + 'naziv_komitenta', 
							  index:'naziv_komitenta',
							  xmlmap:'naziv_komitenta', 
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.naziv_bioskopa, 
							  name: s.base.app.config.SCPN + 'naziv_bioskopa', 
							  index:'naziv_bioskopa',
							  xmlmap:'naziv_bioskopa', 
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.stornirana,
							  name: s.base.app.config.SCPN + 'stornirana', 
							  index:'stornirana',
							  xmlmap:'stornirana', 
							  width:20,
							  cellattr:function( rowId, val, rawObject, cm, rdata )
							  {
								 if( val == 1 )
								 {
									return "style='background-color:#da0000;color:#da0000'";
								 }
								 else
								 {
									 return "style='background-color:#FFFFFF;color:#FFFFFF'";
								 }
							  }
						  },
						],
						// end of col model
						
						onCellSelect:function( rowid, iCol, cellcontent, e ){
							
							if( rowid != s.zvanicnaGledanostSelectedRowId )
							{
								s.zvanicnaGledanostSelectedRowId = rowid;
							}
						},
						
						
						serializeGridData:function( p ){
							
							if( s.base )
							{	
								p[ 'advanced_search' ] = true;
								$( "#zvanicna-gledanost-pretraga-form .pretraga_input" ).each(function(index, element) {
									p[ element.name ] = element.value;
								});
										
								var stornirana = $( "#zvanicna-gledanost-pretraga-form" ).find( "input[name='" + s.base.app.config.ICP + "stornirana_z_gledanost_pretraga']:checked" ).val() || "";
									
								p[ s.base.app.config.SCPN  + 'stornirana' ] = stornirana;
							}
							else
							{
								p[ 'advanced_search' ] = false;
								$( "#zvanicna-gledanost-pretraga-form .pretraga_input" ).each(function(index, element) {
									p[ element.name ] = '';
								});
									
								p[ s.base.app.config.SCPN  + 'stornirana' ] = '';
							}
							
							return p;
						},

						loadComplete:function(){
							
							s.zvanicnaGledanostSelectedRowId = null; 
							
						},
						
						
						
						pager: '#zvanicnaGledanost-grid-pager',
						emptyrecords: s.base.app.config.lang.nema_podataka,
						rowNum:30,
						rowList:[10,20,30,100],
						sortname: 'z_gledanost_id',
						sortorder: 'desc',
						viewrecords: true,
						gridview: true,
						caption: s.base.app.config.lang.zvanicna_gledanost
						
					  }).navGrid('#zvanicnaGledanost-grid-pager',{view:true, search: false, edit:false, del:false, refresh:true, add:false} ); 
					  // END OF FILMOVI GRID
				
				
 		}
	});
}


