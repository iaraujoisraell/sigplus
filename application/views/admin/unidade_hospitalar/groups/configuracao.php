<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h4 class="customer-profile-group-heading"><?php echo 'Configuração de Escalas'; ?></h4>
<div class="col-md-12">

 <a href="#" class="btn btn-info pull-left new new-invoice-list mright5"  data-toggle="modal" data-target="#add_registro_modal" data-id=""><?php echo 'Add Registro'; ?></a>
 <div class="clearfix"></div>
<div class="row">
     <hr class="hr-panel-heading" />
</div>
 <div class="clearfix"></div>

<div class="clearfix"></div>
<div class="mtop15">
    <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
        <thead>
            <tr>
                <th>
                    <?php echo _l( '#'); ?>
                </th>
                <th>
                    Setor
                </th>
                <th>
                    Horário
                </th>
                 <th>
                    <?php echo 'Segunda'; ?>
                </th>
                <th>
                    <?php echo 'Terça'; ?>
                </th>
                <th>
                    <?php echo 'Quarta'; ?>
                </th>
                <th>
                    <?php echo 'Quinta'; ?>
                </th>
                <th>
                    <?php echo 'Sexta'; ?>
                </th>
                <th>
                    <?php echo 'Sábado'; ?>
                </th>
                <th>
                    <?php echo 'Domingo'; ?>
                </th>
                <th>
                    <?php echo 'Escala Fixa'; ?>
                </th>
                <th>
                    <?php echo _l( 'options'); ?>
                </th>
                
               
            </tr>
        </thead>
        <tbody>
            <?php
            $configuracaoes = $this->Unidades_hospitalares_model->get_configuracao_horario($unidade->id);
            foreach($configuracaoes as $config){ ?>  
            <tr>
                     
                <td>
                    <?php echo $config['id']; ?>
                </td>
                <td>
                    <?php echo $config['setor']; ?>
                </td>
                <td>
                    <?php echo $config['hora_inicio'].' - '.$config['hora_fim']; ?>
                </td>
                <td>
                    <?php echo $config['segunda']; ?>
                </td>
                <td>
                    <?php echo $config['terca']; ?>
                </td>
                <td>
                    <?php echo $config['quarta']; ?>
                </td>
                <td>
                    <?php echo $config['quinta']; ?>
                </td>
                <td>
                    <?php echo $config['sexta']; ?>
                </td>
                <td>
                    <?php echo $config['sabado']; ?>
                </td>
                <td>
                    <?php echo $config['domingo']; ?>
                </td>
                <td>
                    <?php
                    $consulta_escala_fixa = $this->Unidades_hospitalares_model->get_escala_fixa_by_config_setor_id($config['id']);
                    if($consulta_escala_fixa){
                     ?>
                    <a class="btn btn-success" href="<?php echo admin_url('Unidades_hospitalares/add_escala_fixa/'.$config['id'].'/'.$unidade->id); ?>"> Consultar </a>
                    <?php   
                    }else{
                     ?>
                    <a class="btn btn-primary" href="<?php echo admin_url('Unidades_hospitalares/add_escala_fixa/'.$config['id'].'/'.$unidade->id); ?>"> Gerar </a>
                    <?php
                    }
                    ?>
                </td>
          
                <td>
                    <?php if(!$consulta_escala_fixa){ ?>


                    <a href="<?php echo admin_url('Unidades_hospitalares/delete_configuracao/'. $config['id'].'/'.$unidade->id); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                    <?php }  ?>
                </td>
        
        
    </tr>
    <?php } ?>
</tbody>
</table>
</div>

<?php $this->load->view('admin/unidade_hospitalar/groups/novo_configuracao'); ?>


