<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(__DIR__ . '/App_pdf.php');

class Rel_escala_pdf extends App_pdf
{
    protected $payment;
    protected $escala;

    public function __construct($escala, $tag = '')
    {
        $GLOBALS['payment_pdf'] = $escala;

        parent::__construct();

        if (!class_exists('Invoices_model', false)) {
            $this->ci->load->model('invoices_model');
        }
        
        if (!class_exists('Relatorio_escala_model', false)) {
            $this->ci->load->model('Relatorio_escala_model');
        }
        
        $this->escala = $escala['escala'];
       // $this->todas = $escala['todas'];
        $this->tag     = $tag;
       
        $this->load_language($this->payment->invoice_data->clientid);
        $this->SetTitle('Relatório de escalas'  . $this->escala->id);
    }

    public function prepare()
    {
        $amountDue = ($this->payment->invoice_data->status != Invoices_model::STATUS_PAID && $this->payment->invoice_data->status != Invoices_model::STATUS_CANCELLED ? true : false);

        $this->set_view_vars([
            'escala'   => $this->escala,
            //'todas'    => $this->todas,
            
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
        $actualPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/escala_pdf.php';

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }

        return $actualPath;
    }
}

