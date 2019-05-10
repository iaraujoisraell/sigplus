<script type="text/javascript">
    var count = 1, an = 1, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0, allow_discount = <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? 1 : 0; ?>,
        tax_rates = <?php echo json_encode($tax_rates); ?>;
    //var audio_success = new Audio('<?= $assets ?>sounds/sound2.mp3');
    //var audio_error = new Audio('<?= $assets ?>sounds/sound3.mp3');
    $(document).ready(function () {
     
      if (!localStorage.getItem('dateInicial')) {
            $("#dateInicial").datetimepicker({
                format: site.dateFormats.js_ldate,
                fontAwesome: true,
                language: 'sma',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0
            }).datetimepicker('update', <?php echo $inv->date ?>);
        }
        
        
    

        
</script>
  <script type="text/javascript">
/* Máscaras ER */
function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}
function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function mcep(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que não é dígito
    v=v.replace(/^(\d{5})(\d)/,"$1-$2")         //Esse é tão fácil que não merece explicações
    return v
}
function mdata(v){
    v=v.replace(/\D/g,"");                    //Remove tudo o que não é dígito
    v=v.replace(/(\d{2})(\d)/,"$1/$2");       
    v=v.replace(/(\d{2})(\d)/,"$1/$2");       
                                             
    v=v.replace(/(\d{2})(\d{2})$/,"$1$2");
    return v;
}
function mrg(v){
	v=v.replace(/\D/g,'');
	v=v.replace(/^(\d{2})(\d)/g,"$1.$2");
	v=v.replace(/(\d{3})(\d)/g,"$1.$2");
	v=v.replace(/(\d{3})(\d)/g,"$1-$2");
	return v;
}
function mvalor(v){
    v=v.replace(/\D/g,"");//Remove tudo o que não é dígito
    v=v.replace(/(\d)(\d{8})$/,"$1.$2");//coloca o ponto dos milhões
    v=v.replace(/(\d)(\d{5})$/,"$1.$2");//coloca o ponto dos milhares
        
    v=v.replace(/(\d)(\d{2})$/,"$1,$2");//coloca a virgula antes dos 2 últimos dígitos
    return v;
}
function id( el ){
	return document.getElementById( el );
}
function next( el, next )
{
	if( el.value.length >= el.maxLength ) 
		id( next ).focus(); 
}
</script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.maskMoney.js"></script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-edit"></i><?= lang('Editar Evento'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                 <div class="row">
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("projetos/edit_evento", $attrib);
                echo form_hidden('data_inicio_projeto', $projetos->dt_inicio);
                echo form_hidden('data_fim_projeto', $projetos->dt_final);
                echo form_hidden('id', $evento->id);
                ?>
               
                    <div class="col-lg-12">
                        <div class="col-md-8">
                            <div class="form-group">
                                <?= lang("Projeto", "slProjeto"); ?>
                                    <?php
                                    $wu3[''] = '';
                                    echo form_dropdown('projeto_nome', $projetos->projeto.' - De :'.$this->sma->hrld($projetos->dt_inicio).'  Até :'.$this->sma->hrld($projetos->dt_final), (isset($_POST['projeto']) ? $_POST['projeto'] : $projetos->projeto .' - Dt Início :'.$projetos->dt_inicio), 'id="slProjeto" required="required" class="form-control  select" data-placeholder="' . lang("Selecione") . ' "  style="width:100%;" ');
                                    echo form_hidden('projeto', $projetos->id);
                                   // echo $projetos->projeto;
                                    ?>
                            </div>
                        </div>                        
                    </div>
                    <div class="col-lg-12">
                    <div class="col-md-8">
                            <div class="form-group">
                                <?= lang("Nome do Evento", "slprojeto"); ?>
                                <?php echo form_input('nome_evento', (isset($_POST['nome_evento']) ? $_POST['nome_evento'] : $evento->nome_evento), 'maxlength="200" class="form-control input-tip" required="required" id="slprojeto"'); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-12">
                        <div class="col-sm-4">
                                <div class="form-group">
                                 <?= lang("Data Início", "start_date"); ?>
                                 <?php echo form_input('dateInicial', (isset($_POST['dateInicial']) ? $_POST['dateInicial'] : $this->sma->hrld($evento->data_inicio)), 'class="form-control datetime" id="start_date"'); ?>
                                 </div>
                                   
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                 <?= lang("Data Término", "dateEntregaDemanda"); ?>
                                 <?php echo form_input('dateFim', (isset($_POST['dateFim']) ? $_POST['dateFim'] : $this->sma->hrld($evento->data_fim)), 'class="form-control datetime" id="dateEntregaDemanda" '); ?>
                                 </div>
                                    
                                </div>
                        
                        </div>
                    
                    <div class="col-lg-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("Responsável Técnico", "slGerenteArea"); ?>
                                

                                    <?php
                                    $wu1[''] = '';
                                    foreach ($users as $user1) {
                                        $wu1[$user1->id] = $user1->first_name . ' ' . $user1->last_name;
                                    }
                                    echo form_dropdown('responsavel_tecnico', $wu1, (isset($_POST['responsavel_tecnico']) ? $_POST['responsavel_tecnico'] : $evento->responsavel), 'id="slGerenteArea" class="form-control  select" data-placeholder="' . lang("Selecione") . ' " required="required" style="width:100%;" ');
                                    ?>


                                   
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("Responsável EDP", "slEDP"); ?>
                               

                                    <?php
                                    $wu2[''] = '';
                                    foreach ($users as $user2) {
                                        $wu2[$user2->id] = $user2->first_name . ' ' . $user2->last_name;
                                    }
                                    echo form_dropdown('responsavel_edp', $wu2, (isset($_POST['responsavel_edp']) ? $_POST['responsavel_edp'] : $evento->responsavel_edp), 'id="slEDP" class="form-control  select" data-placeholder="' . lang("Selecione") . ' "  style="width:100%;" ');
                                    ?>
                                    
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        
                        <div class="clearfix"></div>
                        
                        <div class="col-md-8">
                            <div class="form-group">
                                <?= lang("Responsável da Área", "slRespArea"); ?>
                                <?php echo form_input('responsavel_area', (isset($_POST['responsavel_area']) ? $_POST['responsavel_area'] : $evento->responsavel_area), 'maxlength="200" class="form-control input-tip" required="required" id="slRespArea"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">  
                     <div class="col-lg-8">
                    
                        <div class="form-group">
                                <?= lang("Setor(es) do Evento", "group"); ?>
                           
                                <?php
                                foreach ($setores as $setor) {
                                    $gp[$setor->id_setor] = $setor->setor .' - '. $setor->superintendencia;
                                }
                                
                                 foreach ($setores_eventos as $setor_evento) {
                                  $wua[$setor_evento->setor] = $setor_evento->setor;
                                } 
                                
                                echo form_dropdown('setores[]', $gp, (isset($_POST['setores']) ? $_POST['setores'] : $wua), 'id="group"  multiple class="form-control select" style="width:100%;" data-placeholder="' . lang("Selecione o(s) Setor(es)") . ' " ');
                                ?>
                                
                               
                               
                            </div>
                        
                        
                         </div>
                        <div class="col-lg-8">
                    
                        <div class="form-group">
                                <?= lang("Módulo(s)", "slModulos"); ?>
                            
                                <?php
                                foreach ($modulos as $modulo) {
                                    $gp2[$modulo->id] = $modulo->descricao;
                                }
                                 foreach ($modulos_eventos as $modulo_evento) {
                                  $wue[$modulo_evento->modulo] = $modulo_evento->modulo;
                                } 
                                echo form_dropdown('modulos[]', $gp2, (isset($_POST['modulos']) ? $_POST['modulos'] : $wue), 'id="slModulos" multiple class="form-control select" style="width:100%;" data-placeholder="' . lang("Selecione o(s) Módulo(s)") . ' " ');
                                ?>
                                
                               
                               
                            </div>
                        
                        
                         </div>
            
                    </div>
                     <div class="col-md-12">    
                     <div class="col-md-8">
                            <div class="form-group">
                                <?= lang("Tipo (Agrupa os eventos)", "sltipo"); ?>
                                <?php echo form_input('tipo', (isset($_POST['tipo']) ? $_POST['tipo'] : $evento->tipo), 'maxlength="100" class="form-control input-tip" required="required" id="sltipo"'); ?>
                            </div>
                        </div>
                    </div>
                     
                    <div class="col-md-12">    
                    

                        <div class="row" id="bt">
                            <div class="col-md-12">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <?= lang("Observação", "slnote"); ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : $evento->observacoes), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>

                                    </div>
                                </div>
                       


                            </div>

                        </div>
                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php echo form_submit('add_projeto', lang("submit"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                  <a  class="btn btn-danger"   href="<?= site_url('Projetos/Eventos_index'); ?>"> <div ><?= lang('Sair ') ?></div>  </a>  
                             </div>
                        </div>
                    </div>
                
                

                <?php echo form_close(); ?>

            </div>
    </div>
</div>


