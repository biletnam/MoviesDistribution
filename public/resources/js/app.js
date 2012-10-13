// JavaScript Document

var MoviesApp = function( config )
{
	this.config = config;
	this.selectedMenuItem = null;
	this.selectedModuleId = "";
	this.loadedModules = [];
	this.modules = {};
	
	this.alertDialog = null;
};

MoviesApp.prototype.init = function()
{
	var s = this;
	
	$( ".main-menu-item" ).click( function( e ) { 
	
		$(this).addClass( "selected" );
		
		
		if( s.selectedMenuItem )
		{
			$( s.selectedMenuItem ).removeClass( "selected" );
		}
		
		s.selectedMenuItem = this;
		
		var smid = s.selectedMenuItem.id.split( "-" )[0];
		
		if( smid == "logout" ) 
		{
			s.logOut(); 
			return;
		}
		
		if( s.selectedModuleId &&  smid != s.selectedModuleId )
		{	
			$( "#" + s.selectedModuleId + "-cnt" ).removeClass( "active-module" );
			$( "#" + s.selectedModuleId + "-cnt" ).addClass( "inactive-module" );
		}
	
		s.selectedModuleId = smid;
		s.loadSelectedModule();
		
	});
};

MoviesApp.prototype.loadSelectedModule = function()
{
	this.loadModule( this.selectedModuleId );
};

MoviesApp.prototype.loadModule = function( id )
{
	if( this.loadedModules.indexOf( id ) == -1 )
	{
		this.loadedModules.push( id );
		
		var class_name = id.charAt(0).toUpperCase() + id.slice(1);
		
		var m = new window[ class_name ]( this );
		
		this.modules[ id ] = m;
		
		m.base.moduleReady = this.moduleReadyHandler;
		m.base.config = this.config;
		
		m.init();
	}
	else
	{		
		$( "#" + id + "-cnt" ).removeClass( "inactive-module" );
		$( "#" + id + "-cnt" ).addClass( "active-module" );
	}
};

MoviesApp.prototype.moduleReadyHandler = function( module )
{
	$( "#module-cnt" ).append( module.base.mainView );
	$( "#" + module.base.moduleId + "-cnt" ).addClass( "active-module" );	
};


MoviesApp.prototype.setInfoText = function( el, msg, err )
{
	$( el ).html( msg );
	
	if( msg.length > 0 )
	{	
		err == true ? $( el ).css( "color", "#FF0000" ) : $( el ).css( "color", "#00c46a" );	
		$( el ).css( "display", "block" );
	}
	else
	{
		$( el ).css( "display", "none" );
	}		
};

MoviesApp.prototype.logOut = function()
{
	window.location = this.config.baseUri + "users/logout/";
};

MoviesApp.prototype.validateResponseMessage = function( reponse ){
	
};

MoviesApp.prototype.openAlert = function( options )
{
	if( ! this.alertDialog )
	{
		
	}
};

MoviesApp.prototype.closeAlert = function()
{
	
};

MoviesApp.prototype.getCssColor = function( number )
{
	var cs = parseInt( number, 10 ).toString( 16 );
												 
	var lz =  6  - cs.length;
	
	for( var i = 0; i < lz; i ++ )
	{
		cs = "0" + cs;
	}
	
	return "#" + cs;
};

