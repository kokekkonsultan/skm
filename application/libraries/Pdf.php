<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf extends TCPDF {

	public function __construct()
	{
		parent::__construct();
		

	}

}

class ScanBarcodePdfLight extends TCPDF {

	public function __construct()
	{
		parent::__construct();
		

	}

	public function Header()
	{
		// get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = base_url().'assets/img/bg-scan/background-light.jpg';
        $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
	}

	public function Footer()
	{
		
	}
}

class ScanBarcodePdfDark extends TCPDF {

	public function __construct()
	{
		parent::__construct();
		

	}

	public function Header()
	{
		// get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = base_url().'assets/img/bg-scan/background-dark.jpg';
        $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
	}

	public function Footer()
	{
		
	}
}

/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */