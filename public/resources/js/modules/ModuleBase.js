// JavaScript Document

var ModuleBase = function( inst )
{
	this.app = null
	this.module = inst;
	this.config = {};
	this.mainView = "";
	this.moduleReady = null;
	this.moduleId = "";
	this.moduleSelected = null;

}

ModuleBase.prototype.setApp = function( app )
{
	this.app = app;
}