<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*

@autor: Larissa

@data: 07/07/2022

@desc: model que retorna a query com informaÃ§Ãµes

relevantes do relatÃ³rio de escala

*/

class Relatorio_escala_model extends CI_Model{

    

    public function __construct()

    {

        parent::__construct();

    }


    /*

     * REtorna todos os setores e a qtde de horas prevista para uma unidade e para uma competencia

     * 


    */

     public function get_todas_unidades($competencia_id = '')

    {

         

       $empresa_id = $this->session->userdata('empresa_id');





       $sql = " SELECT uh.fantasia, sm.nome, sm.unidade_id, ps.competencia_id, ps.setor_id, hp.hora_inicio, hp.hora_fim

                FROM tblsetores_medicos sm

                inner join tblunidades_hospitalares uh on uh.id = sm.unidade_id

                inner join tblcompetencias_plantoes_setores ps on ps.setor_id = sm.id

                inner join tblunidades_hospitalar_configuracao c on c.id = ps.config_id

                inner join tblhorario_plantao hp on hp.id = c.horario_id

                where sm.deleted = 0 and sm.empresa_id = $empresa_id and ps.competencia_id= $competencia_id

                group by ps.setor_id order by  sm.unidade_id";



           //echo $sql; exit;



            $result = $this->db->query($sql)->result_array();

            //print_r($result); 

       return $result;

       

    }

    

         public function get_medicos_geral($competencia_id = '', $setor_id='')

    {

         

       $empresa_id = $this->session->userdata('empresa_id');





       $sql = " SELECT  tblevents.dia as dia, tblevents.dia_semana as dia_semana, hp.hora_inicio, hp.hora_fim,

                tit.nome_reduzido as titular, esc.nome_reduzido as escalado, subs.nome_reduzido as substituto, plant.nome_reduzido as plantonista,

                cred.nome_reduzido as creditado, tblevents.status as status, tblevents.status_faturamento as faturamento,tblevents.quantidade as quantidade,

                tblevents.unidade_id ,tblevents.setor_id

                FROM tblevents

                INNER JOIN tblmedicos tit on tit.medicoid = tblevents.titular_id

                LEFT JOIN tblmedicos esc on esc.medicoid = tblevents.medico_escalado_id

                LEFT JOIN tblmedicos subs on subs.medicoid = tblevents.substituto

                LEFT JOIN tblmedicos plant on plant.medicoid =tblevents.medico_plantonista_id

                LEFT JOIN tblmedicos cred on cred.medicoid = tblevents.medico_creditado

                INNER JOIN tblunidades_hospitalares ON tblunidades_hospitalares.id = tblevents.unidade_id

                INNER JOIN tblsetores_medicos ON tblsetores_medicos.id = tblevents.setor_id

                INNER JOIN tblcompetencias_plantoes_setores ps ON ps.config_id = tblevents.config_id

                INNER JOIN tblunidades_hospitalar_configuracao c on c.id = ps.config_id

                INNER JOIN tblhorario_plantao hp on hp.id = c.horario_id

                WHERE tblevents.competencia_id = $competencia_id AND tblevents.empresa_id = $empresa_id AND tblevents.setor_id = $setor_id

                order by tblevents.dia asc, tblevents.titular_id";



           //echo $sql; exit;



            $result = $this->db->query($sql)->result_array();

            //print_r($result); 

       return $result;

       

    }

    /*

     * Larissa Oliveira

     * 29/07/2022

     * DescriÃ§Ã£o: retorna os setores pertencentes a cada unidade dependendo da competencia



     */

     public function get_setores_unidade($competencia_id = '', $unidade_id = '')

    {

         

       $empresa_id = $this->session->userdata('empresa_id');



       $sql = " SELECT uh.fantasia, sm.nome, sm.unidade_id, ps.competencia_id, ps.setor_id, hp.hora_inicio, hp.hora_fim FROM tblsetores_medicos sm


                inner join tblunidades_hospitalares uh on uh.id = sm.unidade_id

                inner join tblcompetencias_plantoes_setores ps on ps.setor_id = sm.id

                inner join tblunidades_hospitalar_configuracao c on c.id = ps.config_id

                inner join tblhorario_plantao hp on hp.id = c.horario_id

                where sm.deleted = 0 and sm.empresa_id = $empresa_id and sm.unidade_id = $unidade_id and ps.competencia_id= $competencia_id 




               group by ps.setor_id";



             //echo $sql; exit;



            $result = $this->db->query($sql)->result_array();

           // print_r($result); 

       return $result;

       

    }

    

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


               group by hp.hora_inicio, hp.hora_fim

               group by ps.setor_id";



           //echo $sql; exit;



            $result = $this->db->query($sql)->result_array();

            //print_r($result); 

       return $result;

       

    }

    /*

     * Larissa Oliveira

     * 29/07/2022

     * DescriÃ§Ã£o: retorna os horÃ¡rios de cada unidade dependendo da competencia

     */


    public function get_horarios_escala($setor_id = '')


    {

        $empresa_id = $this->session->userdata('empresa_id');

       

        $sql = "SELECT tblevents.unidade_id , tblevents.setor_id, tblsetores_medicos.nome as nome, tblevents.horario_id, hp.hora_inicio, hp.hora_fim
                FROM tblevents
                INNER JOIN tblmedicos tit on tit.medicoid = tblevents.titular_id
                LEFT JOIN tblmedicos esc on esc.medicoid = tblevents.medico_escalado_id LEFT JOIN tblmedicos subs on subs.medicoid = tblevents.substituto
                LEFT JOIN tblmedicos plant on plant.medicoid =tblevents.medico_plantonista_id LEFT JOIN tblmedicos cred on cred.medicoid =tblevents.medico_creditado
                INNER JOIN tblunidades_hospitalares ON tblunidades_hospitalares.id = tblevents.unidade_id
                INNER JOIN tblsetores_medicos ON tblsetores_medicos.id = tblevents.setor_id
                INNER JOIN tblhorario_plantao hp on hp.id = tblevents.horario_id
                WHERE tblevents.setor_id  = $setor_id AND tblevents.deleted = 0
                AND tblevents.empresa_id = $empresa_id group by tblevents.horario_id";




        //echo $sql; exit;

        $result = $this->db->query($sql)->result_array();

        //print_r($result); exit;

        return $result;

    }






     /*

     * Larissa Oliveira

     * 18/08//2022

     * DescriÃ§Ã£o: retorna os nomes dos mÃ©dicos para determinada unidade, horario e setor, dependendo da competencia

     */



    public function get_medicos($competencia_id = '', $setor_id = '', $start = '',$end = '', $dia_semana = '' )

    {

        $empresa_id = $this->session->userdata('empresa_id');

       

        $sql = "
                SELECT tblevents.unidade_id , tblevents.setor_id, tblsetores_medicos.nome as nome_setor,esc.nome_reduzido as medico,
                esc.nome_profissional,  m.codigo_registro as CRM, hp.id, hp.hora_inicio, hp.hora_fim, tblevents.dia_semana,
                tblevents.start, tblevents.end
                FROM tblevents
                INNER JOIN tblmedicos tit on tit.medicoid          = tblevents.titular_id
                LEFT JOIN tblmedicos esc on esc.medicoid          = tblevents.medico_escalado_id
                LEFT JOIN  tblmedicos m on m.medicoid = tblevents.medico_escalado_id
                INNER JOIN tblunidades_hospitalares ON tblunidades_hospitalares.id             = tblevents.unidade_id
                INNER JOIN tblsetores_medicos ON tblsetores_medicos.id                   = tblevents.setor_id
                INNER JOIN tblhorario_plantao hp on hp.id = tblevents.horario_id

                WHERE  tblevents.competencia_id  = $competencia_id AND tblevents.setor_id  = $setor_id
                AND tblevents.start = '$start' AND tblevents.dia_semana = '$dia_semana' AND tblevents.end = '$end'
                AND tblevents.deleted = 0  AND tblevents.empresa_id = $empresa_id
                group by tblevents.medico_escalado_id

            ";

      // echo $sql; exit;

        $result = $this->db->query($sql)->result_array();

        //print_r($result); exit;

        return $result;


    }   

 

     /*

     * Larissa Oliveira

     * 29/07/2022

     * DescriÃ§Ã£o: retorna a quantidade de plantÃµes de determinada unidade e setor

     */

        public function get_quantidade($competencia_id = '', $setor_id = '')

    {

        $empresa_id = $this->session->userdata('empresa_id');

       

        $sql = "SELECT ps.unidade_id, ps.setor_id, s.nome, m.nome_reduzido as medico, hp.id, hp.hora_inicio, hp.hora_fim, e.dia_semana, e.start, e.end, e.quantidade

                FROM tblcompetencias_plantoes_setores ps

                inner join tblunidades_hospitalar_configuracao c on c.id = ps.config_id

                inner join tblescala_fixa ef on ef.config_id = c.id inner join tblhorario_plantao hp on hp.id = c.horario_id

                inner join tblevents e on e.config_id = ps.config_id

                left join tblmedicos m on m.medicoid = e.medico_escalado_id

                inner join tblsetores_medicos s on s.id = ps.setor_id

                where ps.deleted = 0  and ps.empresa_id = $empresa_id and ps.setor_id = $setor_id and e.competencia_id= $competencia_id 

                group by e.start, e.end

            ";

        //echo $sql; exit;

        $result = $this->db->query($sql)->result_array();

        //print_r($result); exit;

        return $result;

    }            
   
    
    
    public function get_medicos_hpsZonaOeste($competencia_id = '', $setor_id = '', $start = '', $end ='')

    {

        $empresa_id = $this->session->userdata('empresa_id');

       

        $sql = "        SELECT tblevents.start as start, tblevents.end as end,
                        esc.nome_profissional, esc.codigo_registro as CRM, tblsetores_medicos.nome as nome_setor
                        FROM tblevents
                        LEFT JOIN tblmedicos esc on esc.medicoid          = tblevents.medico_escalado_id
                        INNER JOIN tblunidades_hospitalares ON tblunidades_hospitalares.id             = tblevents.unidade_id
                        INNER JOIN tblsetores_medicos ON tblsetores_medicos.id                   = tblevents.setor_id
                        WHERE  tblevents.competencia_id  = $competencia_id AND tblevents.setor_id  = $setor_id 
                        AND tblevents.start = '$start'
                        AND tblevents.end = '$end' AND  tblevents.empresa_id = $empresa_id AND tblevents.deleted=0
                        order by tblevents.dia asc, esc.nome_profissional asc
              
            ";

        //echo $sql; exit;

        $result = $this->db->query($sql)->result_array();

        //print_r($result); exit;

        return $result;


    }   

 
}
