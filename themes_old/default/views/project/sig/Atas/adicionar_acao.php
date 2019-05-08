<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script>
       $(document).ready(function() {
    $('.btn-theme').click(function(){
        $('#aguarde, #blanket').css('display','block');
    });
});
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('Nova Ação'); ?></h2> 
    </div>
       <?php $date_cadastro = date('Y-m-d H:i:s');               
        $data_entrega = $this->sma->hrld($date_cadastro); ?>

 
<style>
    #blanket,#aguarde {
    position: fixed;
    display: none;
}

#blanket {
    left: 0;
    top: 0;
    background-color: #f0f0f0;
    filter: alpha(opacity =         65);
    height: 100%;
    width: 100%;
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=65)";
    opacity: 0.65;
    z-index: 9998;
}

#aguarde {
    width: auto;
    height: 30px;
    top: 40%;
    left: 45%;
    background: url('http://i.imgur.com/SpJvla7.gif') no-repeat 0 50%; 
    line-height: 30px;
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    z-index: 9999;
    padding-left: 27px;
}
</style> 

        <?php
        $acao = $this->atas_model->getPlanoByID($idplano);
        $usuario = $this->session->userdata('user_id');
//$users = $this->site->geUserByID($acao->responsavel);                              
        ?>    

<?php
                        function resume( $var, $limite ){	// Se o texto for maior que o limite, ele corta o texto e adiciona 3 pontinhos.	
                                  if (strlen($var) > $limite)	{
                        		$var = substr($var, 0, $limite);		
                                        $var = trim($var) . "...";	
                                        
                                  }return $var;
                                  
                                  }
                              ?>
        <div class="box-content">
            <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <center>
                                        <h2> NOVA AÇÃO DO PLANO DE AÇÃO DA ATA <?php echo $ata; ?>  </h2>
                                    </center>
                                </div>
                            </div>
                              <div id="blanket"></div>
                                <div id="aguarde">Aguarde...Enviando Email</div>
                            <div class="clearfix"></div>
                            <?php
                            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                            echo form_open_multipart("Atas/plano_acao/".$ata, $attrib);
                            echo form_hidden('id', $ata);
                            echo form_hidden('avulsa', $avulsa);
                            ?>
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Descrição", "sldescricao"); ?>
                                        <?php echo form_input('descricao', (isset($_POST['descricao']) ? $_POST['descricao'] : $descricao), 'maxlength="200" class="form-control input-tip" required="required" id="sldescricao"'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Item do Evento", "slEvento"); ?>
                                        <?php
                                        $wue[''] = '';
                                      foreach ($eventos as $evento) {
                                            $wue[$evento->id_item] = $evento->tipo.'/'.$evento->evento.' - '.resume($evento->descricao, 100).' ( de: '.$this->sma->hrld($evento->inicio) .' até : '.$this->sma->hrld($evento->fim) .' )';
                                           
                                        }
                                        echo form_dropdown('evento', $wue, (isset($_POST['evento']) ? $_POST['evento'] : ""), 'id="slEvento"  class="form-control  select" data-placeholder="' . lang("Selecione o Evento da Ação") . ' "  style="width:100%;" required="true"  required="required"');
                                        ?>
                                    </div>
                                </div>
                                </div>
                           
                            <div class="col-md-12">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <?= lang("Prazo da Entrega", "sldate"); ?>
                                             <?php echo form_input('dateEntrega', (isset($_POST['dateEntrega']) ? $_POST['dateEntrega'] : $data_entrega ), 'class="form-control input-tip datetime" id="sldate" required="required"'); ?>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <?= lang("Data Término da demanda", "sldateEntregaDemanda"); ?>
                                        <?php $date_cadastro = date('Y-m-d H:i:s');               
                                              $data_entrega_demanda = $this->sma->hrld($date_cadastro); ?>
                                                   <?php echo form_input('dateEntregaDemanda', (isset($_POST['dateEntregaDemanda']) ? $_POST['dateEntregaDemanda'] : $data_entrega_demanda), 'class="form-control input-tip datetime" id="sldateEntregaDemanda" required="required"'); ?>
                                        </div>
                                </div>
                                

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?= lang("Responsável", "slResponsavel"); ?>
                                        <a style="margin-left:10px;" data-toggle="modal" data-target="#myModal" href="<?= site_url('atas/lista_participante_usuario/'.$ata.'/'.$avulsa) ?>"> 
                                            <i class="fa fa-users"></i>   <?= lang('Usuários/Participantes') ?>
                                        </a>
                                        <?php
                                        //$wu4[''] = '';
                                        foreach ($users as $user) {
                                            $wu4[$user->id] = $user->nome.' '.$user->last.' - '.$user->setor;
                                        }
                                      //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                        echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : $participantes_usuarios), 'id="slResponsavel" required="required"  class="form-control  select" data-placeholder="' . lang("Selecione o(s) Responsavel(eis)") . ' "   style="width:100%;"  multiple ');
                              
                                        ?>
                                    </div>
                                </div>
                            </div>    
                            <div class="col-md-12">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <?= lang("Custo ", "custo"); ?>
                                        <?php echo form_input('custo', (isset($_POST['custo']) ? $_POST['custo'] : "N/A"), 'maxlength="200" class="form-control input-tip" required="required"  id="custo"'); ?>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <?= lang("Consultoria ", "consultoria"); ?>

                                        <?php
                                        $pst12[''] = '';
                                        $pst12['SIM'] = lang('SIM');
                                        $pst12['NÃO'] = lang('NÃO');

                                        echo form_dropdown('consultoria', $pst12, (isset($_POST['consultoria']) ? $_POST['consultoria'] : "NÃO"), 'id="consultoria" class="form-control " data-placeholder="' . lang("Precisará") . ' ' . lang("de Consultoria?") . '" required="required"   style="width:100%;" ');
                                        ?>

                                    </div>
                                </div>
                                <div class="col-md-4">
                           <div class="form-group">
                                <?= lang("Anexar Documento", "document") ?> 
                                    <?php if($ata->anexo){ ?>
                                <div class="btn-group">
                            <a href="<?= site_url('assets/uploads/atas/' . $ata->anexo_ata) ?>" class="tip btn btn-file" title="<?= lang('Arquivo em Anexo') ?>">
                                <i class="fa fa-chain"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('Ver Anexo') ?></span>
                            </a>
                                    <?php /* <input type="checkbox"><button type="button" class="btn btn-danger" id="reset"><?= lang('REMOVER') ?> */ ?>
                        </div>
                               
                                <?php } ?>
                               <?php if($statusAta != 1){ ?>
                               <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" value="<?php echo $projeto->anexo; ?>" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                                <?php } ?>  
                            </div>
                            
                        </div>

                               
                            </div>
                            <div class="col-md-12">
                                 <div class="col-sm-8">
                                    <div class="form-group">
                                        <?= lang("Ação da Consultoria ", "acaoconsultoria"); ?>

                                        <?php echo form_input('acaoconsultoria', (isset($_POST['acaoconsultoria']) ? $_POST['acaoconsultoria'] : ""), 'maxlength="200" class="form-control input-tip"  id="acaoconsultoria"'); ?>

                                    </div>
                                </div>
                                
                                 </div>
                            <div class="col-md-12">
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Vincular Ações", "slVinculoAcao"); ?>
                                        <?php
                                       
                                       // $wu_acao[''] = '';
                                        foreach ($acoes as $acao) {
                                            $wu_acao[$acao->idplanos] = $acao->idplanos .' - '. substr($acao->descricao, 0, 100);
                                        }
                                        echo form_dropdown('acoes_vinculo[]', $wu_acao, (isset($_POST['acoes_vinculo']) ? $_POST['acoes_vinculo'] : ""), 'id="slVinculoAcao"  class="form-control  select" data-placeholder="' . lang("Selecione a(s) Ações(es)") . ' "   style="width:100%;"  multiple ');
                                        ?>
                                    </div>
                                </div>
                           </div>
                            <div class="col-md-12">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Observação", "slobservacao"); ?>

                                        <?php echo form_textarea('observacao', (isset($_POST['observacao']) ? $_POST['observacao'] : ""), 'class="form-control" id="slobservacao" equired="required"  style="margin-top: 10px; height: 150px;"'); ?>

                                    </div>
                                </div>
                            </div>

                            <center>

                                <div class="col-md-12">
                                      <?php echo form_submit('add_item', lang("Salvar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>
                                        <a  class="btn btn-danger" class="close" data-dismiss="modal"  href="<?= site_url('Atas/plano_acao/'.$ata); ?>"><?= lang('Sair') ?></a>
                             
                                </div>
                                 </center>
                             <?php echo form_close(); ?>
                       
       
                <br><br><br>
                </div>
            </div>
     

</div>
    <script>
     function alertas(){
         
    
    document.getElementById('blanket').style.display = 'block';
    document.getElementById('aguarde').style.display = 'block';
   
    
    }
    </script>