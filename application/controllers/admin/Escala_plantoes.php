<?php
/*
@autor: Larissa
@data: 07/07/2022
@desc: controller que chama as models Relatorio_escala_model e Unidades_hospitalares_model;
carregamento da view de relatorios de escala
*/

defined('BASEPATH') or exit('No direct script access allowed');

class Escala_plantoes extends AdminController
{

  // private $not_importable_fields = ['id'];
  
    public function __construct()
    {
        parent::__construct();
          $this->load->model('Unidades_hospitalares_model');  
          $this->load->model('Relatorio_escala_model');
    }
    
    public function index()
    {
     //  echo 'aqui'; exit;
        if (!has_permission('cadastro_menu', '', 'cadastro_unidades')) {
          access_denied('Unidades Hospitalares');
        }
        
        //$data['categorias_financeira'] = $this->Financeiro_model->get_categorias(1);
        
        
        $this->load->model("Unidades_hospitalares_model");
        $data['unidades_hospitalares'] = $this->Unidades_hospitalares_model->get();
        $data['competencias'] = $this->Unidades_hospitalares_model->get_competencia_escala();        
           
        $this->load->view('admin/gestao_escala/relatorios/relatorio_escala/manage', $data);

    }
    
    public function pdf()
    {
        $competencia_id = $this->input->post("competencia_id");
        $unidade_id = $this->input->post("unidade_id");
        
        $escala['escala']= $this->Relatorio_escala_model->get_setores_unidade($competencia_id, $unidade_id);
    
        try {
            
            $escalapdf = escalas_pdf($escala);
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        $type = 'I';

        if ($this->input->get('output_type')) {
            $type = $this->input->get('output_type');
        }

        if ($this->input->get('print')) {
            $type = 'I';
        }

        $escalapdf->Output(mb_strtoupper(slug_it('escala' . '-' . $escala->escalaid)) . '.pdf', $type);
    }
    
    
  
    
}
