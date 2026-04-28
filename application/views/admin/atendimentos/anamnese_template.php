<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12 no-padding animated fadeIn">
    <div class="panel_s">
        <?php echo form_open(admin_url('invoices/record_anamnese'),array('id'=>'record_payment_form')); ?>
        <?php echo form_hidden('atendimento_id',$atendimento['id']); ?>
        <?php $qtde = count($this->invoices_model->get_historico_cliente_atendimento($atendimento['id'])); ?>
        <?php echo form_hidden('quantidade',$qtde); ?>
        <?php echo form_hidden('rel_id',$atendimento['contact_id']); ?>
        <div class="panel-body">
            <h4 class="no-margin"><?php  echo 'ANAMNESE DO ATENDIMENTO'; ?> </h4>
           <hr class="hr-panel-heading" />
           <?php 
           $medico_id = $atendimento['medico_id'];
           $anamnese = $this->invoices_model->get_anamnese_medico($medico_id);
           
           $queixas_hda = $anamnese->queixas_hda;
           $antecedentes = $anamnese->antecedentes;
           $refracao = $anamnese->refracao;
           $biomicroscopia = $anamnese->biomicroscopia;
           $fundoscopia = $anamnese->fundoscopia;
           $diagnostico = $anamnese->diagnostico;
           $conduta = $anamnese->conduta;
           $informacoes_adicionais = $anamnese->informacoes_adicionais;
           $uso_medicacoes = $anamnese->uso_medicacoes;
           $alergias = $anamnese->alergias;
           $habitos_sociais = $anamnese->habitos_sociais;
           $exames_fisicos = $anamnese->exames_fisicos;
           
           
           // Histórico
           $historico = $this->invoices_model->get_historico_cliente_atendimento($atendimento['id']);
           $historico_queixas_hda = $historico->queixas_hda;
           $historico_antecedentes = $historico->antecedentes;
           $historico_refracao = $historico->refracao;
           $historico_biomicroscopia = $historico->biomicroscopia;
           $historico_fundoscopia = $historico->fundoscopia;
           $historico_diagnostico = $historico->diagnostico;
           $historico_conduta = $historico->conduta;
           $historico_informacoes_adicionais = $historico->informacoes_adicionais;
           $historico_uso_medicacoes = $historico->uso_medicacoes;
           $historico_alergias = $historico->alergias;
           $historico_habitos_sociais = $historico->habitos_sociais;
           $historico_exames_fisicos = $historico->exames_fisicos;
           ?>
            <?php if($queixas_hda){ echo render_textarea( 'queixas_hda', 'queixas_hda', $historico_queixas_hda,array( 'rows'=>5)); } ?>
            <?php if($antecedentes){ echo render_textarea( 'antecedentes', 'antecedentes', $historico_antecedentes,array( 'rows'=>5)); } ?>
            <?php if($habitos_sociais){ echo render_textarea( 'habitos_sociais', 'habitos_sociais', $historico_habitos_sociais,array( 'rows'=>5)); } ?>
            <?php if($alergias){ echo render_textarea( 'alergias', 'alergias', $historico_alergias,array( 'rows'=>5)); } ?>
            <?php if($uso_medicacoes){ echo render_textarea( 'uso_medicacoes', 'uso_medicacoes', $historico_uso_medicacoes,array( 'rows'=>5)); } ?>
            <?php if($informacoes_adicionais){ echo render_textarea( 'informacoes_adicionais', 'informacoes_adicionais', $historico_informacoes_adicionais,array( 'rows'=>5)); } ?>
           
            <?php if($refracao){ echo render_textarea( 'refracao', 'refracao', $historico_refracao,array( 'rows'=>5)); } ?>
            <?php if($biomicroscopia){ echo render_textarea( 'biomicroscopia', 'Biomicroscopia', $historico_biomicroscopia,array( 'rows'=>5)); } ?> 
            <?php if($fundoscopia){ echo render_textarea( 'fundoscopia', 'fundoscopia', $historico_fundoscopia,array( 'rows'=>5)); } ?>
            <?php if($exames_fisicos){ echo render_textarea( 'exames_fisicos', 'exames_fisicos', $historico_exames_fisicos,array( 'rows'=>5)); } ?>
            <?php if($diagnostico){ echo render_textarea( 'diagnostico', 'diagnostico', $historico_diagnostico,array( 'rows'=>5)); } ?>
            <?php if($conduta){ echo render_textarea( 'conduta', 'conduta', $historico_conduta,array( 'rows'=>5)); } ?>
            
            
            
           
            
            <div class="pull-right mtop15">
                <a href="#" class="btn btn-danger" onclick="init_atendimento(<?php echo $atendimento['id']; ?>); return false;"><?php echo _l('cancel'); ?></a>
                <button type="submit" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" data-form="#record_payment_form" class="btn btn-success"><?php echo _l('submit'); ?></button>
            </div>
            
          
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script>
   $(function(){
     init_selectpicker();
     init_datepicker();
     appValidateForm($('#record_payment_form'),{amount:'required',date:'required',paymentmode:'required'});
     var $sMode = $('select[name="paymentmode"]');
     var total_available_payment_modes = $sMode.find('option').length - 1;
     if(total_available_payment_modes == 1) {
        $sMode.selectpicker('val',$sMode.find('option').eq(1).attr('value'));
        $sMode.trigger('change');
     }
 });
</script>
