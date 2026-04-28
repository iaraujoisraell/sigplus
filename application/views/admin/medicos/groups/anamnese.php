<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="customer-profile-group-heading"><?php echo _l('medico_add_edit_profile').' '._l('medico'); ?></h4>
<div class="row">
    <?php 
    $anamnese_id = $anamnese->id;
    $queixas = $anamnese->queixas_hda;
    $antecedentes = $anamnese->antecedentes;
    $uso_medicacoes = $anamnese->uso_medicacoes;
    $alergias = $anamnese->alergias;
    $habitos_sociais = $anamnese->habitos_sociais;
    $exames_fisicos = $anamnese->exames_fisicos;
    $diagnostico = $anamnese->diagnostico;
    $conduta = $anamnese->conduta;
    $informacoes_adicionais = $anamnese->informacoes_adicionais;
    $refracao= $anamnese->refracao;
    $biomicroscopia = $anamnese->biomicroscopia;
    $fundoscopia = $anamnese->fundoscopia;
    ?>
   <?php echo form_open(admin_url('misc/add_anamnese_medico/'.$anamnese_id),array('id'=>'medico_form')); ?>
    <input type="hidden" value="<?php echo $medico->medicoid ?>" name="medico_id">
   <div class="additional"></div>
   <div class="col-md-12">   
      <div class="tab-content mtop15">      
        <div class="row">
           <div class="col-md-6">
                <?php // queixas_hda ?>
                <div class="form-group">
                    <label for="<?php echo 'queixas_hda'; ?>" class="control-label clearfix">
                        <?php echo($tooltip != '' ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l($tooltip, '', false) . '"></i> ': '') . _l('queixas_hda', '', false); ?>
                    </label>
                    
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="queixas_hda" name="queixas_hda" value="1" <?php if ($queixas == 1 ) {?> checked="true" <?php } ?> > 
                        <label for="queixas_hda">
                            <?php echo _l('settings_yes'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="queixas_hda" name="queixas_hda" value="0" <?php if ($queixas == 0 ) {?> checked="true" <?php } ?> >
                        <label for="queixas_hda">
                            <?php echo _l('settings_no'); ?>
                        </label>
                    </div>
                </div>
               
                <?php // antecedentes ?>
                <div class="form-group">
                    <label for="<?php echo 'antecedentes'; ?>" class="control-label clearfix">
                        <?php echo($tooltip != '' ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l($tooltip, '', false) . '"></i> ': '') . _l('antecedentes', '', false); ?>
                    </label>
                    
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="antecedentes" name="antecedentes" value="1" <?php if ($antecedentes == 1 ) {?> checked="true" <?php } ?>>
                        <label for="antecedentes">
                            <?php echo _l('settings_yes'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="antecedentes" name="antecedentes" value="0" <?php if ($antecedentes == 0 ) {?> checked="true" <?php } ?>>
                        <label for="antecedentes">
                            <?php echo _l('settings_no'); ?>
                        </label>
                    </div>
                </div>
               
               <?php // Uso de Medicações ?>
                <div class="form-group">
                    <label for="<?php echo 'uso_medicacoes'; ?>" class="control-label clearfix">
                        <?php echo($tooltip != '' ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l($tooltip, '', false) . '"></i> ': '') . _l('uso_medicacoes', '', false); ?>
                    </label>
                    
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="uso_medicacoes" name="uso_medicacoes" value="1" <?php if ($uso_medicacoes == 1 ) {?> checked="true" <?php } ?> >
                        <label for="uso_medicacoes">
                            <?php echo _l('settings_yes'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="uso_medicacoes" name="uso_medicacoes" value="0" <?php if ($uso_medicacoes == 0 ) {?> checked="true" <?php } ?> >
                        <label for="uso_medicacoes">
                            <?php echo _l('settings_no'); ?>
                        </label>
                    </div>
                </div>
               
               <?php // alergias ?>
                <div class="form-group">
                    <label for="<?php echo 'alergias'; ?>" class="control-label clearfix">
                        <?php echo($tooltip != '' ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l($tooltip, '', false) . '"></i> ': '') . _l('alergias', '', false); ?>
                    </label>
                    
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="alergias" name="alergias" value="1" <?php if ($alergias == 1 ) {?> checked="true" <?php } ?> >
                        <label for="alergias">
                            <?php echo _l('settings_yes'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="alergias" name="alergias" value="0" <?php if ($alergias == 0 ) {?> checked="true" <?php } ?> >
                        <label for="alergias">
                            <?php echo _l('settings_no'); ?>
                        </label>
                    </div>
                </div>
               
                <?php // habitos_sociais ?>
                <div class="form-group">
                    <label for="<?php echo 'habitos_sociais'; ?>" class="control-label clearfix">
                        <?php echo($tooltip != '' ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l($tooltip, '', false) . '"></i> ': '') . _l('habitos_sociais', '', false); ?>
                    </label>
                    
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="habitos_sociais" name="habitos_sociais" value="1" <?php if ($habitos_sociais == 1 ) {?> checked="true" <?php } ?> >
                        <label for="habitos_sociais">
                            <?php echo _l('settings_yes'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="habitos_sociais" name="habitos_sociais" value="0" <?php if ($habitos_sociais == 0 ) {?> checked="true" <?php } ?> >
                        <label for="habitos_sociais">
                            <?php echo _l('settings_no'); ?>
                        </label>
                    </div>
                </div>
               
               <?php // exames_fisicos ?>
                <div class="form-group">
                    <label for="<?php echo 'exames_fisicos'; ?>" class="control-label clearfix">
                        <?php echo($tooltip != '' ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l($tooltip, '', false) . '"></i> ': '') . _l('exames_fisicos', '', false); ?>
                    </label>
                    
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="exames_fisicos" name="exames_fisicos" value="1" <?php if ($exames_fisicos == 1 ) {?> checked="true" <?php } ?> >
                        <label for="exames_fisicos">
                            <?php echo _l('settings_yes'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                            <input type="radio" id="exames_fisicos" name="exames_fisicos" value="0" <?php if ($exames_fisicos == 0 ) {?> checked="true" <?php } ?> >
                            <label for="exames_fisicos">
                                <?php echo _l('settings_no'); ?>
                            </label>
                    </div>
                </div>
               
                <?php // diagnostico ?>
                <div class="form-group">
                    <label for="<?php echo 'diagnostico'; ?>" class="control-label clearfix">
                        <?php echo($tooltip != '' ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l($tooltip, '', false) . '"></i> ': '') . _l('diagnostico', '', false); ?>
                    </label>
                    
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="diagnostico" name="diagnostico" value="1" <?php if ($diagnostico == 1 ) {?> checked="true" <?php } ?> >
                        <label for="diagnostico">
                            <?php echo _l('settings_yes'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                            <input type="radio" id="diagnostico" name="diagnostico" value="0" <?php if ($diagnostico == 0 ) {?> checked="true" <?php } ?> >
                            <label for="diagnostico">
                                <?php echo _l('settings_no'); ?>
                            </label>
                    </div>
                </div>
               
                <?php // conduta ?>
                <div class="form-group">
                    <label for="<?php echo 'conduta'; ?>" class="control-label clearfix">
                        <?php echo($tooltip != '' ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l($tooltip, '', false) . '"></i> ': '') . _l('conduta', '', false); ?>
                    </label>
                    
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="conduta" name="conduta" value="1" <?php if ($conduta == 1 ) {?> checked="true" <?php } ?> >
                        <label for="conduta">
                            <?php echo _l('settings_yes'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="conduta" name="conduta" value="0" <?php if ($conduta == 0 ) {?> checked="true" <?php } ?> >
                        <label for="conduta">
                            <?php echo _l('settings_no'); ?>
                        </label>
                    </div>
                </div>
               
                <?php // informacoes_adicionais ?>
                <div class="form-group">
                    <label for="<?php echo 'uso_medicacoes'; ?>" class="control-label clearfix">
                        <?php echo($tooltip != '' ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l($tooltip, '', false) . '"></i> ': '') . _l('informacoes_adicionais', '', false); ?>
                    </label>
                    
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="informacoes_adicionais" name="informacoes_adicionais" value="1" <?php if ($informacoes_adicionais == 1 ) {?> checked="true" <?php } ?> >
                        <label for="informacoes_adicionais">
                            <?php echo _l('settings_yes'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                            <input type="radio" id="informacoes_adicionais" name="informacoes_adicionais" value="0" <?php if ($informacoes_adicionais == 0 ) {?> checked="true" <?php } ?> >
                            <label for="informacoes_adicionais">
                                <?php echo _l('settings_no'); ?>
                            </label>
                    </div>
                </div>
                
                  <?php // refracao ?>
                <div class="form-group">
                    <label for="<?php echo 'refracao'; ?>" class="control-label clearfix">
                        <?php echo($tooltip != '' ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l($tooltip, '', false) . '"></i> ': '') . _l('refracao', '', false); ?>
                    </label>
                    
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="refracao" name="refracao" value="1" <?php if ($refracao == 1 ) {?> checked="true" <?php } ?> >
                        <label for="refracao">
                            <?php echo _l('settings_yes'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="refracao" name="refracao" value="0" <?php if ($refracao == 0 ) {?> checked="true" <?php } ?> >
                        <label for="refracao">
                            <?php echo _l('settings_no'); ?>
                        </label>
                    </div>
                </div>
                
                <?php // biomicroscopia ?>
                <div class="form-group">
                    <label for="<?php echo 'biomicroscopia'; ?>" class="control-label clearfix">
                        <?php echo($tooltip != '' ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l($tooltip, '', false) . '"></i> ': '') . _l('Biomicroscopia', '', false); ?>
                    </label>
                    
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="biomicroscopia" name="biomicroscopia" value="1" <?php if ($biomicroscopia == 1 ) {?> checked="true" <?php } ?> >
                        <label for="biomicroscopia">
                            <?php echo _l('settings_yes'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                            <input type="radio" id="biomicroscopia" name="biomicroscopia" value="0" <?php if ($biomicroscopia == 0 ) {?> checked="true" <?php } ?> >
                            <label for="biomicroscopia">
                                <?php echo _l('settings_no'); ?>
                            </label>
                    </div>
                </div>
                
                <?php // fundoscopia ?>
                <div class="form-group">
                    <label for="<?php echo 'fundoscopia'; ?>" class="control-label clearfix">
                        <?php echo($tooltip != '' ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-title="' . _l($tooltip, '', false) . '"></i> ': '') . _l('fundoscopia', '', false); ?>
                    </label>
                    
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="fundoscopia" name="fundoscopia" value="1" <?php if ($fundoscopia == 1 ) {?> checked="true" <?php } ?> >
                        <label for="fundoscopia">
                            <?php echo _l('settings_yes'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="fundoscopia" name="fundoscopia" value="0" <?php if ($fundoscopia == 0 ) {?> checked="true" <?php } ?> >
                        <label for="fundoscopia">
                            <?php echo _l('settings_no'); ?>
                        </label>
                    </div>
                </div> 
                 
                 
                  
               </div>
            
        </div>
          <div class="pull-right mtop15">
                <button type="submit" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" data-form="#record_payment_form" class="btn btn-success"><?php echo _l('submit'); ?></button>
            </div>
      </div>
   </div>
   <?php echo form_close(); ?>
</div>


