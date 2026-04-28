<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(__DIR__ . '/App_pdf.php');

class Rel_geral_pdf extends App_pdf
{
    protected $payment;
    protected $relatorio_geral;

    public function __construct($relatorio_geral, $tag = '')
    {
        $GLOBALS['payment_pdf'] = $relatorio_geral;

        parent::__construct();
        
        if (!class_exists('Invoices_model', false)) {
            $this->ci->load->model('invoices_model');
        }
        
        if (!class_exists('Relatorio_escala_model', false)) {
            $this->ci->load->model('Relatorio_escala_model');
        }
        
        $this->escala = $relatorio_geral['escala'];
        
        $this->tag     = $tag;

        $this->load_language($this->payment->invoice_data->clientid);
        $this->SetTitle('ITOAM'  . $this->payment->paymentid);
    }

    public function prepare()
    {
        $amountDue = ($this->payment->invoice_data->status != Invoices_model::STATUS_PAID && $this->payment->invoice_data->status != Invoices_model::STATUS_CANCELLED ? true : false);
       
        $this->set_view_vars([
            'relatorio_geral'   => $this->escala,
                      
            'amountDue' => $amountDue
        ]);

        return $this->build();
    }

    protected function type()
    {
        return 'payment';
    }

    protected function file_path()
    {
        $actualPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/geral_pdf.php';

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }

        return $actualPath;
    }
}

