<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Indicadores_model extends App_Model
{
    
    /*****************************************************************
     **************** INDICADORES - AGENDAMENTOS ***********************
     ********************************************************************/
    
    /*
     * RETORNA O TOTAL DE AGENDADOS
     */
    public function get_total_agendado($data_de = '', $data_ate = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT count(*) as total FROM tblappointly_appointments
                where contact_id > 0 
                and date between '$data_de' and '$data_ate' and empresa_id = $empresa_id";
       // echo $sql; exit;
       
       
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    
     /*
     * RETORNA O TOTAL DE AGENDADOS
     */
    public function get_total_confirmado($data_de = '', $data_ate = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT count(*) as total FROM tblappointly_appointments
                where contact_id > 0 and approved = 1 and cancelled = 0 
                and date between '$data_de' and '$data_ate' and empresa_id = $empresa_id";
       // echo $sql; exit;
       
       
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
     /*
     * RETORNA O TOTAL DE CANCELADO
     */
    public function get_total_cancelado($data_de = '', $data_ate = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT count(*) as total FROM tblappointly_appointments
                where contact_id > 0 and cancelled = 1 and finished = 0 
                and date between '$data_de' and '$data_ate' and empresa_id = $empresa_id";
       // echo $sql; exit;
       
       
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    /*
     * RETORNA O TOTAL DE ATENDIDOS NO PERÍODO
     */
    public function get_total_atendidos($data_de = '', $data_ate = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
       $sql = "SELECT count(*) as total FROM tblappointly_appointments
                where finished = 1 
                and cancelled = 0
                and date between '$data_de' and '$data_ate' and empresa_id = $empresa_id";
       // echo $sql; exit;
       
       
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    
     public function get_total_atendidos_medico($where_medico, $data_de = '', $data_ate = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
       $sql = "SELECT count(*) as total FROM tblappointly_appointments
                where finished = 1 
                and cancelled = 0
                and medico_id = $where_medico
                and date between '$data_de' and '$data_ate' and empresa_id = $empresa_id";
       // echo $sql; exit;
       
       
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    /*
     * RETORNA O TOTAL DE AGENDAMENTO NO PERÍODO
     */
    public function get_total_em_atendimentos($data_de = '', $data_ate = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT count(*) as total FROM tblappointly_appointments
                where approved = 1 and inicio_atendimento = 1 and finished = 0 and cancelled = 0
                and date between '$data_de' and '$data_ate' and empresa_id = $empresa_id";
       // echo $sql; exit;
       
       
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    public function get_total_em_atendimentos_medico($where_medico, $data_de = '', $data_ate = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT count(*) as total FROM tblappointly_appointments
                where approved = 1 and inicio_atendimento = 1 and finished = 0 and cancelled = 0 and medico_id = $where_medico
                and date between '$data_de' and '$data_ate' and empresa_id = $empresa_id";
       // echo $sql; exit;
       
       
        $result = $this->db->query($sql)->row();
        return $result;
    }
   

    /*
     * RETORNA O TOTAL DE FALTA
     */
    public function get_total_falta($data_de = '', $data_ate = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT count(*) as total FROM tblappointly_appointments
                where contact_id > 0 and finished = 0 and cancelled = 0 and inicio_atendimento = 0 
                and date between '$data_de' and '$data_ate' and empresa_id = $empresa_id";
       // echo $sql; exit;
       
       
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    
    /*
     * RETORNA O TOTAL DE ATENDIMENTO POR TIPO DE ATENDIMENTO
     */
    public function get_atendimento_tipo_atendimento($data_de = '', $data_ate = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT t.type, count(*) as total FROM tblappointly_appointments
                inner join tblappointly_appointment_types t on t.id = tblappointly_appointments.type_id
                where finished = 1 
                and cancelled = 0
                and date between '$data_de' and '$data_ate'
                and empresa_id = $empresa_id
                GROUP BY type_id";
       // echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    /*
     * RETORNA O TOTAL DE ATENDIMENTO POR TIPO DE ATENDIMENTO
     */
    public function get_atendimento_tipo_sexo($data_de = '', $data_ate = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT c.sexo, count(*) as total FROM tblappointly_appointments
                inner join tblclients c on c.userid = tblappointly_appointments.contact_id
                where finished = 1 
                and cancelled = 0
                and date between '$data_de' and '$data_ate'
                and tblappointly_appointments.empresa_id = $empresa_id
                GROUP BY c.sexo";
        
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    // GRÁFICO BARRA
    
    /*
     * RETORNA O TOTAL DE ATENDIMENTO POR DIA (DATA)
     */
    public function get_atendimento_por_dia($data_de = '', $data_ate = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT date, count(*) as total FROM tblappointly_appointments
                where finished = 1 and cancelled = 0
                and date between '$data_de' and '$data_ate'
                and tblappointly_appointments.empresa_id = $empresa_id
                GROUP BY date";
        
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    
    /*
     * RETORNA O TOTAL DE ATENDIMENTO POR CONVÊNIO (DATA)
     */
    public function get_atendimento_por_convenio($data_de = '', $data_ate = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT c.name as convenio, count(*) as total FROM tblappointly_appointments
                inner join tblconvenio c on c.id = tblappointly_appointments.convenio
                where finished = 1 and cancelled = 0
                and date between '$data_de' and '$data_ate'
                and tblappointly_appointments.empresa_id = $empresa_id
                GROUP BY c.id";
        
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    
    /*
     * RETORNA O TOTAL DE ATENDIMENTO POR HORÁRIO 
     */
    public function get_atendimento_por_horario($data_de = '', $data_ate = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT start_hour, count(*) as total FROM tblappointly_appointments
        where finished = 1 
        and cancelled = 0
        and date between '$data_de' and '$data_ate'
        and tblappointly_appointments.empresa_id = $empresa_id
        GROUP BY start_hour";
        
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    /*
     * RETORNA O TOTAL DE ATENDIMENTO POR MÉDICO 
     */
    public function get_atendimento_por_medico($data_de = '', $data_ate = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT m.nome_profissional as nome_profissional, count(*) as total FROM tblappointly_appointments
        inner join tblmedicos m on m.medicoid = tblappointly_appointments.medico_id
        where finished = 1 and cancelled = 0
        and date between '$data_de' and '$data_ate'
        and tblappointly_appointments.empresa_id = $empresa_id
        GROUP BY medico_id order by total desc";
       // echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    
    /*
     * RETORNA O TOTAL DE AGENDAMENTO POR ATENDENTE
     */
    public function get_agendamento_atendente($data_de = '', $data_ate = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT s.firstname, count(*) as total FROM tblappointly_appointments
        inner join tblstaff s on s.staffid = tblappointly_appointments.created_by
        where finished = 1 and cancelled = 0
        and tblappointly_appointments.date between '$data_de' and '$data_ate'
        and tblappointly_appointments.empresa_id = $empresa_id
        GROUP BY tblappointly_appointments.created_by order by total desc";
        
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    
    
    
    /*****************************************************************
     ****************************  EMPRÉSTIMOS
     ***********************************/
    
    /*
     * RETORNA O TOTAL DE EMPRESTIMOS ABERTOS
     */
    public function get_total_emprestimos()
    {
        $empresa_id = $this->session->userdata('empresa_id');
       $sql = "select count(*) as total FROM tblemprestimos where status = 1 and empresa_id = $empresa_id";
       // echo $sql; exit;
       
       
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    public function get_total_emprestimos_pagos()
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "select count(*) as qtde_parcelas, sum(prestacao) as valor_pago FROM tblemprestimo_parcelas where quitado = 1  and empresa_id = $empresa_id";
       // echo $sql; exit;
       
       
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    public function get_total_emprestimos_abertos()
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "select count(*) as qtde_parcelas, sum(prestacao) as valor_aberto FROM tblemprestimo_parcelas where quitado = 0  and empresa_id = $empresa_id";
       // echo $sql; exit;
       
       
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    /*
     * RETORNA O TOTAL DE ATENDIMENTO POR MÉDICO 
     */
    public function get_emprestimo_por_valor($data_de = '', $data_ate = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "select * FROM tblemprestimos where status = 1 and empresa_id = $empresa_id";
       // echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    
    /*
     * RETORNA O TOTAL DE PARCELAS
     */
    public function get_total_parcela_by_emprestimo($id = null)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "select count(*) as qtde_parcelas_total, sum(prestacao) as valor_devido FROM tblemprestimo_parcelas where emprestimo_id = $id  and empresa_id = $empresa_id";
       // echo $sql; exit;
       
       
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    
    /*
     * RETORNA O TOTAL DE PARCELAS PAGAS
     */
    public function get_total_parcela_pagas_by_emprestimo($id = null)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "select count(*) as qtde_parcelas, sum(prestacao) as valor_pago FROM tblemprestimo_parcelas where emprestimo_id = $id and quitado = 1  and empresa_id = $empresa_id";
       // echo $sql; exit;
       
       
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    
    /*
     * RETORNA O TOTAL DE PARCELAS PAGAS
     */
    public function get_total_parcela_abertas_by_emprestimo($id = null)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "select count(*) as qtde_parcelas, sum(prestacao) as valor_pago FROM tblemprestimo_parcelas where emprestimo_id = $id and quitado = 0  and empresa_id = $empresa_id";
       // echo $sql; exit;
       
       
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    
    
    /*
     * RETORNA O TOTAL DE VENCIMENTO DO EMPRESTIMO POR DIA (DATA)
     */
    public function get_vencimento_emprestimo_por_dia()
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "select data_vcto,  sum(prestacao) as vl_total
                FROM tblemprestimo_parcelas
                where month(data_vcto) = month(now()) and year(data_vcto) = year(now()) and empresa_id = $empresa_id
                 group by data_vcto
                order by data_vcto asc";
        
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    
    /*
     * RETORNA O VALOR TOTAL DO MÊS DA PARCELA DO EMPRESTIMO POR MES )
     */
    public function get_total_emprestimo_por_mes()
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "select   sum(prestacao) as vl_total, month(data_vcto) as mes, year(data_vcto) as ano
        FROM tblemprestimo_parcelas

        where year(data_vcto) = year(now()) and empresa_id = $empresa_id
        group by month(data_vcto), year(data_vcto)
        order by data_vcto asc";
        
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    
    
    /*
     * produção médica - lista
     */
    
     /*
     * RETORNA O TOTAL DE ATENDIMENTO POR TIPO DE ATENDIMENTO
     */
    public function get_todos_atendimento_producao($medico_id = "",  $data_de = "", $data_ate = "") //$convenios = "", $procedimentos = "",
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT a.medico_id as medico_id,  m.nome_profissional, a.data_atendimento as data_atendimento, p.start_hour as start_hour, t.convenio_id as convenio_id, v.name as convenio, a.item_id as item_id, 
                    t.description as procedimento, t.group_id as group_id, g.name as categoria, c.company as paciente,  a.valor_medico
                FROM tblappointly_producao_medica a
                left join tblinvoices               i on i.atendimento_id = a.agenda_id
                inner join tblmedicos                m on m.medicoid = a.medico_id
                inner join tblitems                  t on t.id = a.item_id
                inner join tblitems_groups           g on g.id = t.group_id
                inner join tblconvenio               v on v.id = t.convenio_id
                inner join tblappointly_appointments p on p.id = a.agenda_id
                inner join tblclients                c on c.userid = p.contact_id
                where a.data_atendimento between '$data_de'  and '$data_ate'
                and a.medico_id IN ($medico_id)
                
               

                order by data_atendimento, start_hour asc";
        echo $sql; exit;
        /*
         *    AND t.convenio_id IN ('.$convenios.')
                AND t.item_id IN ('.$procedimentos.')      
         */
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    
    /*
     * RETORNA O AGENDAMENTO MÉDICO
     */
    public function get_todos_agendamento_medico($medico_id = "",  $data_de = "", $data_ate = "") //$convenios = "", $procedimentos = "",
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT a.medico_id as medico_id ,  m.nome_profissional, c.phonenumber, c.phonenumber2, a.date as data_atendimento, a.start_hour as start_hour, a.convenio as convenio_id, t.type, t.color, v.name as convenio , c.company as paciente, a.finished
                FROM tblappointly_appointments a
                inner join tblappointly_appointment_types t on t.id = a.type_id 
                inner join tblmedicos                   m on m.medicoid = a.medico_id
                inner join tblconvenio                  v on v.id = a.convenio
                inner join tblclients                c on c.userid = a.contact_id
                where a.date between '$data_de'  and '$data_ate'
                and a.medico_id IN ($medico_id)
                
               

                order by a.date, start_hour asc";
        //echo $sql; exit;
        /*
         *    AND t.convenio_id IN ('.$convenios.')
                AND t.item_id IN ('.$procedimentos.')      
         */
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
}
