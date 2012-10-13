var ImportExport = function( app )
{
	this.base = new ModuleBase( this );
	this.base.setApp( app );
}

ImportExport.prototype.init = function()
{
	var s = this;
	
	$.ajax({
 		type: 'get',
 		url: this.base.config.baseUri + "importExport/",
 		success: function(data)
 		{
			s.base.mainView = data;
			s.base.app.moduleReadyHandler( s );
			
			
				
 		}
	});
}


