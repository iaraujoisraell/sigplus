<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(__DIR__ . '/App_pdf.php');

class Rel_frequencia_pdf extends App_pdf
{
    protected $payment;
    protected $frequencia;

    public function __construct($frequencia, $tag = '')
    {
        $GLOBALS['payment_pdf'] = $frequencia;

        parent::__construct();
        
        if (!class_exists('Invoices_model', false)) {
            $this->ci->load->model('invoices_model');
        }
        
        if (!class_exists('Relatorio_frequencia_model', false)) {
            $this->ci->load->model('Relatorio_frequencia_model');
        }
        
        $this->escala = $frequencia['escala'];
        $this->horarios = $frequencia['horarios'];
        $this -> setores = $frequencia['setores'];
        $this->tag     = $tag;

        $this->load_language($this->payment->invoice_data->clientid);
        $this->SetTitle('Relatório de frequências'  . $this->payment->paymentid);
    }

    public function prepare()
    {
        $amountDue = ($this->payment->invoice_data->status != Invoices_model::STATUS_PAID && $this->payment->invoice_data->status != Invoices_model::STATUS_CANCELLED ? true : false);
       
        $this->set_view_vars([
            'frequencia'   => $this->escala,
            'horarios'   => $this->horarios,
            'setores' => $this-> setores,
           
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
        $actualPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/frequencia_pdf.php';

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }

        return $actualPath;
    }
}

