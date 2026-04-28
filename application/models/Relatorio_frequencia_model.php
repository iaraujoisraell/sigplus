<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
@autor: Larissa
@data: 07/07/2022
@desc: model que retorna a query com informações
relevantes do relatório de escala
*/
class Relatorio_frequencia_model extends CI_Model{
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Larissa Oliveira
     * 29/07/2022
     * Descrição: retorna os setores pertencentes a cada unidade dependendo da competencia
     */
     public function get_setores_unidade($competencia_id = '', $unidade_id = '', $setor_id='')
    {
         
       $empresa_id = $this->session->userdata('empresa_id');


       $sql = " SELECT uh.fantasia, sm.nome, uh.id, ps.competencia_id, ps.setor_id, hp.hora_inicio, hp.hora_fim FROM tblsetores_medicos sm
                inner join tblunidades_hospitalares uh on uh.id = sm.unidade_id
                inner join tblcompetencias_plantoes_setores ps on ps.setor_id = sm.id
                inner join tblunidades_hospitalar_configuracao c on c.id = ps.config_id
                inner join tblhorario_plantao hp on hp.id = c.horario_id
                where sm.deleted = 0 and sm.empresa_id = $empresa_id and sm.unidade_id = $unidade_id and ps.competencia_id= $competencia_id 
               and ps.setor_id = $setor_id
               group by ps.setor_id";

             //echo $sql; exit;

            $result = $this->db->query($sql)->result_array();
           // print_r($result); 
       return $result;
       
    }
    
    /*
     * Larissa Oliveira
     * 29/07/2022
     * Descrição: retorna os horários de cada unidade dependendo da competencia
     */
    public function get_horarios($competencia_id = '',$unidade_id = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
       
        $sql = "SELECT ps.unidade_id, ps.setor_id, s.nome, m.nome_reduzido as medico, hp.id, hp.hora_inicio, hp.hora_fim, e.dia_semana, e.start, e.end FROM tblcompetencias_plantoes_setores ps
                inner join tblunidades_hospitalar_configuracao c on c.id = ps.config_id
                inner join tblescala_fixa ef on ef.config_id = c.id
                inner join tblhorario_plantao hp on hp.id = c.horario_id
                inner join tblevents e on e.config_id = ps.config_id
                left join tblmedicos m on m.medicoid = e.medico_escalado_id
                inner join tblsetores_medicos s on s.id = ps.setor_id
                where ps.deleted = 0 and ps.empresa_id = $empresa_id and ps.unidade_id = $unidade_id and e.competencia_id = $competencia_id 
               group by hp.hora_inicio, hp.hora_fim";
        //echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
        //print_r($result); exit;
        return $result;
    }
    
     /*
     * Larissa Oliveira
     * 29/07/2022
     * Descrição: retorna os nomes dos médicos para determinada unidade, horario e setor, dependendo da competencia
     */
    public function get_medicos($competencia_id = '', $setor_id = '', $start = '',$hora_fim = '' )
    {
        $empresa_id = $this->session->userdata('empresa_id');
       
        $sql = "SELECT ps.unidade_id, ps.setor_id, s.nome as nome_setor, m.nome_reduzido as medico, m.nome_profissional, m.codigo_registro as CRM, hp.id, hp.hora_inicio, hp.hora_fim, e.dia_semana, e.start, e.end

                FROM tblcompetencias_plantoes_setores ps

                inner join tblunidades_hospitalar_configuracao c on c.id = ps.config_id

                inner join tblescala_fixa ef on ef.config_id = c.id inner join tblhorario_plantao hp on hp.id = c.horario_id

                inner join tblevents e on e.config_id = ps.config_id

                left join tblmedicos m on m.medicoid = e.medico_escalado_id

                inner join tblsetores_medicos s on s.id = ps.setor_id where e.deleted = 0

                and ps.empresa_id = $empresa_id and ps.setor_id = $setor_id and e.start = '$start'
                    
                and hp.hora_fim = '$hora_fim' and e.competencia_id = $competencia_id group by e.medico_escalado_id
            ";
        //echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
        //print_r($result); exit;
        return $result;
    } 
    
    /*
     * Larissa Oliveira
     * 29/07/2022
     * Descrição: as funções a seguir possuem a mesma lógica das anteriores mas são específicas para a UBS Danilo Correa
     */
     public function get_setores_DaniloCorrea($competencia_id = '', $unidade_id = '', $setor_id='')
    {
         
       $empresa_id = $this->session->userdata('empresa_id');


       $sql = " SELECT uh.fantasia, sm.nome, uh.id, ps.competencia_id, ps.setor_id, m.medicoid FROM
                tblsetores_medicos sm
                inner join tblunidades_hospitalares uh on uh.id = sm.unidade_id
                inner join tblcompetencias_plantoes_setores ps on ps.setor_id = sm.id
                inner join tblunidades_hospitalar_configuracao c on c.id = ps.config_id
                inner join tblhorario_plantao hp on hp.id = c.horario_id
                inner join tblevents e on e.config_id = ps.config_id
                inner join tblmedicos m on m.medicoid = e.medico_escalado_id
                where e.deleted = 0 and sm.empresa_id = $empresa_id and sm.unidade_id = $unidade_id and ps.competencia_id= $competencia_id 
                and ps.setor_id = $setor_id group by m.nome_reduzido";

             //echo $sql; exit;

            $result = $this->db->query($sql)->result_array();
            //print_r($result); exit;
       return $result;
       
    }
   
     public function get_medicos_DanielCorrea($competencia_id = '', $setor_id = '', $medico_id = '' )
    {
        $empresa_id = $this->session->userdata('empresa_id');
       
        $sql = "SELECT ps.unidade_id, ps.setor_id, s.nome, m.medicoid, m.nome_reduzido as medico, hp.id, hp.hora_inicio, hp.hora_fim, e.dia_semana, e.start, e.end
                FROM tblcompetencias_plantoes_setores ps
                inner join tblunidades_hospitalar_configuracao c on c.id = ps.config_id
                inner join tblescala_fixa ef on ef.config_id = c.id
                inner join tblhorario_plantao hp on hp.id = c.horario_id
                inner join tblevents e on e.config_id = ps.config_id
                inner join tblmedicos m on m.medicoid = e.medico_escalado_id
                inner join tblsetores_medicos s on s.id = ps.setor_id
                where ps.deleted = 0 and ps.empresa_id = $empresa_id and ps.setor_id = $setor_id and m.medicoid = $medico_id
                and e.competencia_id = $competencia_id group by e.start
            ";
       //echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
        //print_r($result); exit;
        return $result;
    }
    
   
}
