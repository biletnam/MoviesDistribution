// JavaScript Document

var Settings = function( app )
{
	var s = this;
	this.base = new ModuleBase( this );
	this.base.setApp( app );
	this.korisniciGrid = null;
	this.korisniciGridSelectedRow;
	
	this.updateUserPasswordDialog = null;
	
	this.sacuvajMaticnuFirmu = function(){
		
		$.ajax({
				type: 'post',
 				url: s.base.config.baseUri + "settings/sacuvajMaticnuFirmu",
				data: $( this ).serialize(),
				success: function(data)
				{	
					if( data == 0 )
					{
						alert( s.base.app.config.lang.dataSaved );
					}
					else
					{
						alert( s.base.app.config.lang.dogodila_se_greska );
					}
				}
			});
			
		return false;	
	}
	
	this.sacuvajMaticnuFirmucg = function(){
		
		$.ajax({
				type: 'post',
 				url: s.base.config.baseUri + "settings/sacuvajMaticnuFirmucg",
				data: $( this ).serialize(),
				success: function(data)
				{	
					if( data == 0 )
					{
						alert( s.base.app.config.lang.dataSaved );
					}
					else
					{
						alert( s.base.app.config.lang.dogodila_se_greska );
					}
				}
			});
			
		return false;	
	},

	this.sacuvajPodesavanja = function() {

		$.ajax({
			type: 'post',
			url: s.base.config.baseUri + "settings/sacuvajPodesavanja",
			data: $( this ).serialize(),
			success: function( data ) {
				if( data == 0 ) {
					alert( s.base.app.config.lang.dataSaved );
				}
				else
				{
					alert( s.base.app.config.lang.dogodila_se_greska );
				}
			}
		});

		return false;
	}
	
	
	this.updateUserPassword = function()
	{
		var ss = $( "#stara-sifra-input" ).val();
		var ns = $( "#nova-sifra-input" ).val();
		var ps = $( "#ponovi-sifra-input" ).val();
		
		var pd = { stara_sifra:ss, nova_sifra:ns, ponovi_sifru:ps };
		

		var rd = s.korisniciGrid.getRowData( s.korisniciGridSelectedRow );
			pd.user_id = rd[ s.base.app.config.SCPN + "user_id"];
								 		 
		if( ss.length > 1 && ns.length > 1 && ns == ps )
		{
			$.ajax({
				type: 'post',
 				url: s.base.config.baseUri + "users/updateUserPassword",
				data: pd,
				success: function(data)
				{	
					if( data == 0 )
					{
						alert( "Успешно сте променили шифру корисника." );
					}
					else if( data == s.base.config.errorCodes.userNotFound )
					{
						alert( "Стара шифра није добра!" );
					}
					else
					{
						alert( s.base.app.config.lang.dogodila_se_greska );
					}
					
				}
			});
		}
		else
		{
			alert( "Форма није правилно попуњена!" );
		}
			
		return false;
	};
	
}

Settings.prototype.init = function()
{
	var s = this;
	
	$.ajax({
 		type: 'get',
 		url: this.base.config.baseUri + "settings/",
 		success: function(data)
 		{
			s.base.mainView = data;
			s.base.app.moduleReadyHandler( s );
			
			s.korisniciGrid = $("#korisnici-grid").jqGrid({
						width:1024,
						height:570,
						url: s.base.config.baseUri + "users/read/",
						cellEdit:true,
						datatype: 'xml',
						cellsubmit:'remote',
						cellurl: s.base.config.baseUri + 'users/updateUser/',
						mtype: 'POST',
						prmNames: s.base.app.config.paramNames,
						xmlReader: { 
							  repeatitems:false,
							  id:'user_id'
						},
						colModel :[ 
						  {
							    label: s.base.app.config.lang.sifra,
								name: s.base.app.config.SCPN + 'user_id', 
								index: 'user_id', 
								xmlmap:'user_id',
								width:55
						  }, 
						  {
							    label: s.base.app.config.lang.ime,
								name: s.base.app.config.SCPN + 'ime_korisnika', 
								index: 'ime_korisnika', 
								xmlmap:'ime_korisnika',
								editable:true,
								width:90, 
								editable:true
						  }, 
						  {
							  label: s.base.app.config.lang.korisnicko_ime,
							  name: s.base.app.config.SCPN + 'username', 
							  index:'username',
							  xmlmap:'username', 
							  editable:true,
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.sifra,
							  name: s.base.app.config.SCPN + 'sifra_korisnika', 
							  index:'sifra_korisnika',
							  editable:true,
							  width:80,
							  align:'right',
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: s.base.app.config.lang.sifra,
							  name: s.base.app.config.SCPN + 'sifra_korisnika_repeat', 
							  index:'sifra_korisnika_repeat',
							  editable:true,
							  width:80,
							  align:'right',
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: s.base.app.config.lang.email,
							  name: s.base.app.config.SCPN + 'email', 
							  index:'email',
							  xmlmap:'email', 
							  width:80,
							  editable:true
						  },
						  {
							  label: s.base.app.config.lang.tip,
							  name: s.base.app.config.SCPN + 'type', 
							  index:'type',
							  xmlmap:'type', 
							  width:80,
							  editable:true,
							  edittype:"select",
							  formatter:"select" ,
							  editoptions:{value:{ 1: s.base.app.config.lang.administrator, 2: s.base.app.config.lang.bioskop } },
							  editrules:{ edithidden:true } 
						  }
						],
						// end of col model
						
						onCellSelect:function( rowid, iCol, cellcontent, e ){
							
							if( rowid != s.korisniciGridSelectedRow )
							{
								s.korisniciGridSelectedRow = rowid;
							}
						},
							
						afterSubmitCell:function( response, rowid, cellname, value, iRow, iCol ){
														
								return s.checkResponseMessage( response.responseText );	
						},
						
						loadComplete:function(){
							
							s.korisniciGridSelectedRow = null; 
							
						},
						
						emptyrecords: s.base.app.config.lang.nema_podataka,
						sortname: 'type',
						sortorder: 'asc',
						viewrecords: true,
						gridview: true,
						caption: s.base.app.config.lang.korisnici
						
					  }); 
					  // END OF FILMOVI GRID
				
				$( "#update-user-password-form" ).submit( s.updateUserPassword );
					  
				s.updateUserPasswordDialog = $( "#update-password-dialog-cnt" ).dialog({
							title: s.base.app.config.lang.promeni_sifru_korisnika,
							autoOpen: false,
							height: 250,
							width: 450,
							modal: true,
							resizable:false							
				});
				
				
				$("#novi-korisnik-btn" ).click(function( e ) {
					
						s.korisniciGrid.editGridRow( "new", {
										addCaption: s.base.app.config.lang.novi_korisnik,
										bSubmit: s.base.app.config.lang.sacuvaj,
										bCancel: s.base.app.config.lang.otkazi,
										bClose: s.base.app.config.lang.zatvori,
										bYes : s.base.app.config.lang.da,
										bNo : s.base.app.config.lang.ne,
										bExit : s.base.app.config.lang.zatvori,
										width:450,
										url: s.base.config.baseUri + "users/create/",
										
										beforeSubmit : function ( postdata, formid ){
											
											var sifra = postdata[ s.base.app.config.SCPN + 'sifra_korisnika' ];
											
											if(  sifra.length > 0 && 
												 sifra ==  postdata[ s.base.app.config.SCPN + 'sifra_korisnika_repeat' ] 
											  )
											{
												return [true, ""];
											}
											else
											{
												return [ false, s.base.app.config.lang.sifre_se_ne_poklapaju ];
											}
											
											
										},
										
										afterSubmit : function (response, postdata, formid){
										
											return s.checkResponseMessage( response.responseText );
										}
								} );
	
				});  
				
				
				$("#promeni-sifru-korisnika-btn" ).click(function( e ) {
					
					if( s.korisniciGridSelectedRow )
					{
						s.updateUserPasswordDialog.dialog( "open" );
					}
					else
					{
						alert( s.base.app.config.lang.odaberite_korisnika );
					}
					
	
				});  
				
			
			$( "#obrisi-korisnika-btn" ).click( function(){
				
				if( ! s.korisniciGridSelectedRow )
				{
					alert( s.base.app.config.lang.odaberite_korisnika );
					return;
				}
				
				if( confirm( s.base.app.config.lang.obrisi_korisnika_upozorenje ) )
				{
					var rd = s.korisniciGrid.getRowData( s.korisniciGridSelectedRow );
												 
					$.ajax({
						type: 'post',
						url: s.base.config.baseUri + "users/deleteUser",
						data: { user_id:rd[ s.base.app.config.SCPN + "user_id"] },
						success: function(data)
						{	
							if( data == 0 )
							{
								alert( s.base.app.config.lang.korisnik_obrisan );
								s.korisniciGrid.trigger( "reloadGrid" );
							}
							else
							{
								alert( s.base.app.config.lang.dogodila_se_greska );
							}
						}
					});
					
				}
				
			});	
				
			$( "#opcije-podesavanja-cnt" ).accordion({autoHeight:true, width:400});
					  
			$( "#settings-save-form" ).submit( s.sacuvajMaticnuFirmu )
			
			$( "#generalna-podesavanja-form" ).submit( s.sacuvajPodesavanja )
			
			
			$( "#settings-save-formcg" ).submit( s.sacuvajMaticnuFirmucg )
			
 		}
	});
}


Settings.prototype.checkResponseMessage = function( response )
{
	if( response == 0 )
	{
		return [ true, "" ];
	}
	else if( response ==  this.base.config.errorCodes.alreadyExists )
	{
		return [ false, s.base.app.config.lang.korisnik_postoji_username ];
	}
	else if( this.base.config.errorCodes.invalidInput )
	{
		return [ false, s.base.app.config.lang.forma_nije_validna ];
	}
	else
	{
		return [ false, s.base.app.config.lang.dogodila_se_greska ];
	}
}
