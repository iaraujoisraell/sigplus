<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . "/third_party/MPDF/mpdf.php";

class Pdf extends mPDF
{
    public function __construct()
    {
        parent::__construct();
    }
}
