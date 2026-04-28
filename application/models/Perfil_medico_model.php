<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*

@autor: Larissa

@data: 07/07/2022

@desc: model que retorna a query com informações

relevantes do relatório de escala

*/

class Perfil_medico_model extends CI_Model{

    

    public function __construct()

    {

        parent::__construct();

    }

    

         public function get_tabela($medicoid = '')

    {

         

       $empresa_id = $this->session->userdata('empresa_id');





       $sql = " SELECT tit.nome_reduzido, tblevents.dia as dia, tblevents.mes, tblevents.dia_semana as dia_semana, tblunidades_hospitalares.fantasia, 

                tblsetores_medicos.nome as setor, hp.hora_inicio, hp.hora_fim, tblevents.quantidade as quantidade

                FROM tblevents

                INNER JOIN tblmedicos tit on tit.medicoid = tblevents.titular_id

                INNER JOIN tblunidades_hospitalares ON tblunidades_hospitalares.id = tblevents.unidade_id

                INNER JOIN tblsetores_medicos ON tblsetores_medicos.id = tblevents.setor_id

                INNER JOIN tblcompetencias_plantoes_setores ps ON ps.config_id = tblevents.config_id

                INNER JOIN tblunidades_hospitalar_configuracao c on c.id = ps.config_id

                INNER JOIN tblhorario_plantao hp on hp.id = c.horario_id

                WHERE tblevents.empresa_id = $empresa_id and tit.medicoid = $medicoid

                order by tblevents.mes desc, tblevents.dia ";



           //echo $sql; exit;



            $result = $this->db->query($sql)->result_array();

            //print_r($result); 

       return $result;

       

    }

         public function get_info_pessoais($medicoid){

          

       $empresa_id = $this->session->userdata('empresa_id');





       $sql = " select medicoid, nome_reduzido, nome_profissional, cns, codigo_registro as CRM, celular, cpf, email,

                cep, endereco, numero, bairro, complemento, cidade, uf, especialidade

                from tblmedicos

                where empresa_id = $empresa_id and deleted = 0 and medicoid = $medicoid group by medicoid

                ";



         // echo $sql; exit;



            $result = $this->db->query($sql)->row();

            //print_r($result); 

            return $result;

             

             

         }

}

