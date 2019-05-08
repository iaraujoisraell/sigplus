<?php defined('BASEPATH') OR exit('No direct script access allowed');

class EnvioEmails extends MY_Controller
{

        function __construct()
    {
        parent::__construct();

       // $this->load->controllers('Reports');
    }

    /*
     ***********************************ENVIO DE EMAILS AUTOMÁTICO*****************************************
     */
    
    
    
    /*
     *  GERAR STATUS REPORT
     * TODA QUARTA FEIRA 07:00
     */
    
    public function geraStatusReport()
    {
      
        
       //redirect("Reports/add_status_report");
         $this->geraRegistroLinhaTempo();

    }
    
  
    /*
     *  GERAR REGISTRO - GRÁFICO DASHBOARD AÇÕES NO TEMPO DO PROJETO
     * TODA SEXTA FEIRA 18:00
     */
    
    public function geraRegistroLinhaTempo()
    {
        echo 'aqui'; exit;
       //redirect("Reports/status_report");

    }
   

}
