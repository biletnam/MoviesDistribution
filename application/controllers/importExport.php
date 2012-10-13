<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/hooks/PreController.php';

class ImportExport extends PreController 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function index()
	{
		$this->load->view( "import_export" );
	}
	
}

/* End of file importExport.php */
/* Location: ./application/controllers/importExport.php */