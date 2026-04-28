<?php
/*
@autor: Larissa
@data: 07/07/2022
@desc: controller que chama as models Relatorio_escala_model e Unidades_hospitalares_model;
carregamento da view de relatorios de escala
*/

defined('BASEPATH') or exit('No direct script access allowed');

class Lista_frequencia extends AdminController
{

   private $not_importable_fields = ['id'];
  
    public function __construct()
    {
        parent::__construct();
         $this->load->model('Unidades_hospitalares_model');  
          $this->load->model('Relatorio_frequencia_model');
    }
    
    public function retorno_setores_gestao_escala(){
       $unidade_id = $this->input->post("unidade_id");
       $data['setores'] = $this->Unidades_hospitalares_model->get_setores_escala($unidade_id);
       $data['unidade_id'] = $unidade_id;
       $this->load->view('admin/gestao_escala/relatorios/relatorio_frequencia/retorno_setores', $data);
    }
    
    public function index()
    {
       
        if (!has_permission('cadastro_menu', '', 'cadastro_unidades')) {
          access_denied('Unidades Hospitalares');
        }
        
        //$data['categorias_financeira'] = $this->Financeiro_model->get_categorias(1);
        
        
        $this->load->model("Unidades_hospitalares_model");
        $data['unidades_hospitalares'] = $this->Unidades_hospitalares_model->get();
        $data['competencias'] = $this->Unidades_hospitalares_model->get_competencia_escala();        
           
        $this->load->view('admin/gestao_escala/relatorios/relatorio_frequencia/manage', $data);

        //$dados = array("escalas" => $lista );
       
      
    }
    
    public function pdf()
    {

//        echo '<script>';
//        echo 'console.log('. json_encode( $this->input->post("competencia_id") ) .');';
//        echo 'console.log('. json_encode( $this->input->post("unidade_id") ) .');';
//        echo 'console.log('. json_encode( $this->input->post("setor_id") ) .');';
////      echo 'console.log('. json_encode( $this->input->post("horarios_id") ) .');';
////      echo 'console.log('. json_encode( $this->input->post("dias_semana") ) .');';
//        echo '</script>';
        
//        exit;
        $competencia_id = $this->input->post("competencia_id");
        $unidade_id = $this->input->post("unidade_id");
        $setor_id = $this->input->post("setor_id");
        
        $frequencia['escala']= $this->Relatorio_frequencia_model->get_setores_unidade($competencia_id, $unidade_id, $setor_id );
        $frequencia['horarios']= $this->Relatorio_frequencia_model->get_horarios($competencia_id, $unidade_id); 
        $frequencia['setores']= $this->Relatorio_frequencia_model->get_setores_DaniloCorrea($competencia_id, $unidade_id, $setor_id ); 

        try {
            $frequenciapdf = frequencias_pdf($frequencia);
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

        $frequenciapdf->Output(mb_strtoupper(slug_it(_l('frequencia') . '-' . $frequencia->escalaid)) . '.pdf', $type);
    }
    
    
  
    
}
 