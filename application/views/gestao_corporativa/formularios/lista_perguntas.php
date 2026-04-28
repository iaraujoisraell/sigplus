
<?php if (count($perguntas) > 0) { ?>
<div class="card">
       
      <div class="card-header">
        <h3 class="card-title">
          <i class="ion ion-clipboard mr-1"></i>
          Perguntas
        </h3>

            <div class="card-tools">
          
        </div>
      </div>

      <!-- /.card-header -->
      <div class="card-body">
        <ul class="todo-list" data-widget="todo-list">
            <?php foreach ($perguntas as $pergunta){  ?>
            <div class="card">
                    <div class="card-header">
                        <span class="handle">
                                <i class="fas fa-ellipsis-v"></i>
                                <i class="fas fa-ellipsis-v"></i>
                            </span>
                        
                        <h3 class="card-title">
                            
                                    <input type="hidden" name="form_id<?php echo $pergunta['id']; ?>" id="form_id<?php echo $pergunta['id']; ?>" value="<?php echo $form['id']; ?>">
                                    <input name="title<?php echo $pergunta['id']; ?>" id="title<?php echo $pergunta['id']; ?>" style="width: 500px;" placeholder="pergunta" onkeyup="atualizar_resposta(<?php echo $pergunta['id']; ?>);" onkeydown="atualizar_resposta(<?php echo $pergunta['id']; ?>)" onkeypress="atualizar_resposta(<?php echo $pergunta['id']; ?>)"  type="text"  value="<?php echo $pergunta['title']; ?>" class="form-control">
                                
                        </h3>
                        
                        <div class="card-tools">
                            <div class="select-placeholder form-group">
                                <select name="type<?php echo $pergunta['id']; ?>" id="type<?php echo $pergunta['id']; ?>" class="selectpicker form-control"  onchange="Mudarestado(this.value, <?php echo $pergunta['id']; ?>)" data-width="100%"  data-hide-disabled="true">
                                <option value="text" <?php if($pergunta['tipo'] == 'text'){echo 'selected';} ?>>Resposta Curta</option>
                                <option value="textarea" <?php if($pergunta['tipo'] == 'textarea'){echo 'selected';} ?>>Parágrafo</option>
                                
                                <option value="multiselect" <?php if($pergunta['tipo'] == 'multiselect'){echo 'selected';} ?>> Multipla Escolha</option>
                                <option value="caixa_selecao" <?php if($pergunta['tipo'] == 'caixa_selecao'){echo 'selected';} ?>>Caixa de Seleção</option>
                                <option value="select" <?php if($pergunta['tipo'] == 'select'){echo 'selected';} ?>>Lista suspensa</option>
                                
                                <option value="date" <?php if($pergunta['tipo'] == 'date'){echo 'selected'; ?>  <?php } ?>>Data </option>
                                <option value="number" <?php if($pergunta['tipo'] == 'number'){echo 'selected';} ?>>Número</option>
                                <option value="datetime" <?php if($pergunta['tipo'] == 'datetime'){echo 'selected';} ?>>Horário</option>
                                <option value="colorpicker" <?php if($pergunta['tipo'] == 'colorpicker'){echo 'selected';} ?>>Cores</option>
                                
                                
                                
                                
                                
                               <!-- <option value="link" <?php if(isset($custom_field) && $custom_field->type == 'link'){echo 'selected';} ?><?php if(isset($custom_field) && $custom_field->fieldto == 'items'){echo 'disabled';} ?>>Hyperlink</option> -->
                            </select>
                           </div>
                        </div>
                    </div> 
                
                    <div class="card-body">
                        <li>
                            <div id="resposta_curta<?php echo $pergunta['id']; ?>" >
                                <label >Resposta Curta <br> ____________________________</label>
                            </div>
                            
                            
                            <label id="textarea<?php echo $pergunta['id']; ?>" style="display: none" >Parágrafo  <br>
                                ____________________________ <br>
                                ____________________________ <br>
                                ____________________________
                            
                            </label>
                            
                             <label id="multiselect<?php echo $pergunta['id']; ?>" style="display: none" >
                                 
                                <div id="lista_multiplaescolha<?php echo $pergunta['id']; ?>">
                                    
                                </div>
                            </label> 
                            
                            <label id="caixa_selecao<?php echo $pergunta['id']; ?>" style="display: none" >
                                 
                                <div id="lista_caixaselecao<?php echo $pergunta['id']; ?>">
                                    
                                </div>
                            </label>
                            
                            <label id="select<?php echo $pergunta['id']; ?>" style="display: none" >
                                 
                                <div id="lista_select<?php echo $pergunta['id']; ?>">
                                    
                                </div>
                            </label>
                            
                            <label id="date<?php echo $pergunta['id']; ?>" style="display: none" >Data  <br>
                                __/__/____ 
                            </label>
                            
                            <label id="number<?php echo $pergunta['id']; ?>" style="display: none" >Número  <br>
                                123456
                            </label>
                            
                            <label id="datetime<?php echo $pergunta['id']; ?>" style="display: none" >Horário  <br>
                                hh:mm
                            </label> 
                            
                            <label id="colorpicker<?php echo $pergunta['id']; ?>" style="display: none" >Cores  <br>
                                <i class="btn btn-primary"></i><i class="btn btn-warning"></i><i class="btn btn-danger"></i><i class="btn btn-success"></i>
                            </label>
                            
                        </li>
                    </div>

                    <div class="card-footer clearfix">
                        <a class="float-right btn btn-danger" title="Deletar pergunta" onclick="apagar_perguntas(<?php echo $pergunta['id']; ?>)" ><i class="fa fa-trash"></i> </a>
                    </div>
                </div>
             
            <script> 
                 $(function () {
                    var select = document.getElementById("type<?php echo $pergunta['id']; ?>");
                    var opcaoTexto = select.options[select.selectedIndex].text;
                    var opcaoValor = select.options[select.selectedIndex].value;
                      
                    Mudarestado(opcaoValor, <?php echo $pergunta['id']; ?>);
                   
                   });
            </script>
            <?php } ?>


        </ul>
      </div>
     
      <!-- /.card-body 
      <div class="card-footer clearfix">
        <a class="float-right btn btn-primary" onclick="add_perguntas()" ><i class="fa fa-plus"></i> NOVA PERGUNTA </a>
      </div>-->
    </div>
  
<?php } ?>

<script src="<?php echo base_url(); ?>assets/lte/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?php echo base_url(); ?>assets/lte/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lte/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url(); ?>assets/lte/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?php echo base_url(); ?>assets/lte/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lte/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo base_url(); ?>assets/lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url(); ?>assets/lte/dist/js/pages/dashboard.js"></script>


<script type="text/javascript">
 
 /************************** MULTIPLA ESCOLHA ********************************/
 
   function lista_multipla_escolha(perg_id) {
       
         $.ajax({
             type: "POST",
             url: "<?php echo base_url('gestao_corporativa/Formularios/lista_multipla_escolha'); ?>",
             data: {
                 perg_id: perg_id
             },
             success: function (data) {
                 $('#lista_multiplaescolha'+perg_id).html(data);
             }
         });
    }
   
    function add_item_multipla_escolha(perg_id) {
        var item_name = document.getElementById("item_name_"+perg_id);
        var item_name_Valor = item_name.value;
        
        $.ajax({
             type: "POST",
             url: "<?php echo base_url('gestao_corporativa/Formularios/add_item_multipla_escolha'); ?>",
             data: {
                 perg_id: perg_id, 
                 item_name: item_name_Valor
             },
             success: function (data) {
                 lista_multipla_escolha(perg_id);
             }
         });
         
         
         document.getElementById("item_name_"+perg_id).value = '';
        // lista_multipla_escolha(perg_id);
        
    }
    
    function delete_item_multipla_escolha(item_id, perg_id) {
    $.ajax({
             type: "POST",
             url: "<?php echo base_url('gestao_corporativa/Formularios/deletar_item_multipla_escolha'); ?>",
             data: {
                 item_id: item_id
             },
             success: function (data) {
                 lista_multipla_escolha(perg_id);
             }
         });
         
         //lista_multipla_escolha(perg_id);
    }
    
     /****************** CAIXA DE SELECAO**************************************/
     
     function lista_caixa_selecao(perg_id) {
        
         $.ajax({
             type: "POST",
             url: "<?php echo base_url('gestao_corporativa/Formularios/lista_caixa_selecao'); ?>",
             data: {
                 perg_id: perg_id
             },
             success: function (data) {
                 $('#lista_caixaselecao'+perg_id).html(data);
             }
         });
    }
    
    function add_item_caixa_selecao(perg_id) {
        var item_name = document.getElementById("item_selecao_"+perg_id);
        var item_name_Valor = item_name.value;
        
        $.ajax({
             type: "POST",
             url: "<?php echo base_url('gestao_corporativa/Formularios/add_item_multipla_escolha'); ?>",
             data: {
                 perg_id: perg_id, 
                 item_name: item_name_Valor
             },
             success: function (data) {
                 lista_caixa_selecao(perg_id);
             }
         });
         
         
         document.getElementById("item_name_"+perg_id).value = '';
        // lista_caixa_selecao(perg_id);
        
    }
    
    function delete_item_caixa_selecao(item_id, perg_id) {
    $.ajax({
             type: "POST",
             url: "<?php echo base_url('gestao_corporativa/Formularios/deletar_item_multipla_escolha'); ?>",
             data: {
                 item_id: item_id
             },
             success: function (data) {
                 lista_caixa_selecao(perg_id);
             }
         });
         
         //lista_caixa_selecao(perg_id);
    }
    
    /****************** LISTA SUSPENSA**************************************/

    function lista_select(perg_id) {
        
         $.ajax({
             type: "POST",
             url: "<?php echo base_url('gestao_corporativa/Formularios/lista_select'); ?>",
             data: {
                 perg_id: perg_id
             },
             success: function (data) {
                 $('#lista_select'+perg_id).html(data);
             }
         });
    }
    
    function add_item_select(perg_id) {
        var item_name = document.getElementById("item_select_"+perg_id);
        var item_name_Valor = item_name.value;
        
        $.ajax({
             type: "POST",
             url: "<?php echo base_url('gestao_corporativa/Formularios/add_item_multipla_escolha'); ?>",
             data: {
                 perg_id: perg_id, 
                 item_name: item_name_Valor
             },
             success: function (data) {
                 lista_select(perg_id);
             }
         });
         
         
         document.getElementById("item_name_"+perg_id).value = '';
         //lista_select(perg_id);
        
    }
    
    function delete_item_select(item_id, perg_id) {
    $.ajax({
             type: "POST",
             url: "<?php echo base_url('gestao_corporativa/Formularios/deletar_item_multipla_escolha'); ?>",
             data: {
                 item_id: item_id
             },
             success: function (data) {
                 lista_select(perg_id);
             }
         });
         
        // lista_select(perg_id);
    }


    /**************************************************************************/
    function Mudarestado(el, pergunta_id) {
        
        if(el == 'text'){
            Mudarestado_resposta('resposta_curta'+pergunta_id);
            document.getElementById('textarea'+pergunta_id).style.display = 'none';
            document.getElementById('date'+pergunta_id).style.display = 'none';
            document.getElementById('number'+pergunta_id).style.display = 'none';
            document.getElementById('datetime'+pergunta_id).style.display = 'none';
            document.getElementById('colorpicker'+pergunta_id).style.display = 'none';
            document.getElementById('multiselect'+pergunta_id).style.display = 'none';
            document.getElementById('caixa_selecao'+pergunta_id).style.display = 'none';
            document.getElementById('select'+pergunta_id).style.display = 'none';
            
        }else if(el == 'textarea'){
             Mudarestado_resposta('textarea'+pergunta_id);
             document.getElementById('resposta_curta'+pergunta_id).style.display = 'none';
             document.getElementById('date'+pergunta_id).style.display = 'none';
             document.getElementById('number'+pergunta_id).style.display = 'none';
             document.getElementById('datetime'+pergunta_id).style.display = 'none';
            document.getElementById('colorpicker'+pergunta_id).style.display = 'none';
            document.getElementById('multiselect'+pergunta_id).style.display = 'none';
            document.getElementById('caixa_selecao'+pergunta_id).style.display = 'none';
            document.getElementById('select'+pergunta_id).style.display = 'none';
             
        }else if(el == 'date'){
            
             Mudarestado_resposta('date'+pergunta_id);
             document.getElementById('resposta_curta'+pergunta_id).style.display = 'none';
             document.getElementById('textarea'+pergunta_id).style.display = 'none';
             document.getElementById('number'+pergunta_id).style.display = 'none';
             document.getElementById('datetime'+pergunta_id).style.display = 'none';
            document.getElementById('colorpicker'+pergunta_id).style.display = 'none';
            document.getElementById('multiselect'+pergunta_id).style.display = 'none';
            document.getElementById('caixa_selecao'+pergunta_id).style.display = 'none';
            document.getElementById('select'+pergunta_id).style.display = 'none';
             
        }else if(el == 'number'){
             Mudarestado_resposta('number'+pergunta_id);
             document.getElementById('resposta_curta'+pergunta_id).style.display = 'none';
             document.getElementById('textarea'+pergunta_id).style.display = 'none';
             document.getElementById('date'+pergunta_id).style.display = 'none';
             document.getElementById('datetime'+pergunta_id).style.display = 'none';
            document.getElementById('colorpicker'+pergunta_id).style.display = 'none';
            document.getElementById('multiselect'+pergunta_id).style.display = 'none';
            document.getElementById('caixa_selecao'+pergunta_id).style.display = 'none';
            document.getElementById('select'+pergunta_id).style.display = 'none';
             
        }else if(el == 'datetime'){
             Mudarestado_resposta('datetime'+pergunta_id);
             document.getElementById('resposta_curta'+pergunta_id).style.display = 'none';
             document.getElementById('textarea'+pergunta_id).style.display = 'none';
             document.getElementById('date'+pergunta_id).style.display = 'none';
             document.getElementById('number'+pergunta_id).style.display = 'none';
            document.getElementById('colorpicker'+pergunta_id).style.display = 'none';
            document.getElementById('multiselect'+pergunta_id).style.display = 'none';
            document.getElementById('caixa_selecao'+pergunta_id).style.display = 'none';
            document.getElementById('select'+pergunta_id).style.display = 'none';
             
        }else if(el == 'colorpicker'){
             Mudarestado_resposta('colorpicker'+pergunta_id);
             document.getElementById('resposta_curta'+pergunta_id).style.display = 'none';
             document.getElementById('textarea'+pergunta_id).style.display = 'none';
             document.getElementById('date'+pergunta_id).style.display = 'none';
             document.getElementById('datetime'+pergunta_id).style.display = 'none';
            document.getElementById('number'+pergunta_id).style.display = 'none';
            document.getElementById('multiselect'+pergunta_id).style.display = 'none';
            document.getElementById('caixa_selecao'+pergunta_id).style.display = 'none';
            document.getElementById('select'+pergunta_id).style.display = 'none';
            
        }else if(el == 'multiselect'){
             
             Mudarestado_resposta('multiselect'+pergunta_id);
            
             document.getElementById('resposta_curta'+pergunta_id).style.display = 'none';
             document.getElementById('textarea'+pergunta_id).style.display = 'none';
             document.getElementById('date'+pergunta_id).style.display = 'none';
             document.getElementById('datetime'+pergunta_id).style.display = 'none';
            document.getElementById('number'+pergunta_id).style.display = 'none';
            document.getElementById('colorpicker'+pergunta_id).style.display = 'none';
             
            document.getElementById('caixa_selecao'+pergunta_id).style.display = 'none';
            
            document.getElementById('select'+pergunta_id).style.display = 'none';
            
            lista_multipla_escolha(pergunta_id);
            
        }else if(el == 'caixa_selecao'){
             Mudarestado_resposta('caixa_selecao'+pergunta_id);
             document.getElementById('resposta_curta'+pergunta_id).style.display = 'none';
             document.getElementById('textarea'+pergunta_id).style.display = 'none';
             document.getElementById('date'+pergunta_id).style.display = 'none';
             document.getElementById('datetime'+pergunta_id).style.display = 'none';
            document.getElementById('number'+pergunta_id).style.display = 'none';
            document.getElementById('colorpicker'+pergunta_id).style.display = 'none';
            document.getElementById('multiselect'+pergunta_id).style.display = 'none';
            document.getElementById('select'+pergunta_id).style.display = 'none';
            lista_caixa_selecao(pergunta_id);
            
        }else if(el == 'select'){
             Mudarestado_resposta('select'+pergunta_id);
             document.getElementById('resposta_curta'+pergunta_id).style.display = 'none';
             document.getElementById('textarea'+pergunta_id).style.display = 'none';
             document.getElementById('date'+pergunta_id).style.display = 'none';
             document.getElementById('datetime'+pergunta_id).style.display = 'none';
            document.getElementById('number'+pergunta_id).style.display = 'none';
            document.getElementById('colorpicker'+pergunta_id).style.display = 'none';
            document.getElementById('multiselect'+pergunta_id).style.display = 'none';
            document.getElementById('caixa_selecao'+pergunta_id).style.display = 'none';
            
            lista_select(pergunta_id);
            
        }
        
        
        atualizar_resposta(pergunta_id);
        
        lista_multipla_escolha(pergunta_id);
        lista_caixa_selecao(pergunta_id);
        lista_select(pergunta_id);
    }
 
    function Mudarestado_resposta(el) {
      
        var display = document.getElementById(el).style.display;
        if(display == "none"){
            document.getElementById(el).style.display = 'block';
        }
           // document.getElementById(el).style.display = 'none';
    }
    
    
  function atualizar_resposta(pergunta_id) {
     
        var pergunta_id = pergunta_id;
        var title = document.getElementById("title"+pergunta_id);
        var title_Valor = title.value;
      
      
        var tipo = document.getElementById("type"+pergunta_id);
        var tipoValor = tipo.options[tipo.selectedIndex].value;
        
      $.ajax({
             type: "POST",
             url: "<?php echo base_url('gestao_corporativa/Formularios/atualiza_perguntas'); ?>",
             data: {
                 form_id: pergunta_id,
                 title: title_Valor,
                 tipo: tipoValor
             },
             success: function (data) {
                 $('#retorno_atualiza').html(data);
             }
         });
  }
    


</script>