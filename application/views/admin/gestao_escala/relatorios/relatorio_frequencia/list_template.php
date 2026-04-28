<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12">
    <section class="content-header">
      <h1>
        Relatório de Escalas
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-dashboard"></i> Gestão de Plantão </a></li>
      </ol>
    </section>
   <div class="panel_s mbot10">
      <div class="panel-body _buttons">
                     
         <div class="row">        
              <div class="col-md-3">
                  <div class="form-group">
                      <label>Competência</label>
                      <select name="competencia_id" id="competencia_id"  required="true" class="form-control" onchange="procedimentos_table_reload();">
                            <option value="">Selecione uma competência</option>
                            <?php foreach ($competencias as $comp){ ?>
                             <option value="<?php echo $comp['id']; ?>"><?php echo $comp['mes'].' / '.$comp['ano']; ?></option>   
                            <?php } ?>
                        </select>
                  </div>
                </div>
                <!-- CONVENIO -->
              <div class="col-md-3">
                  <div class="form-group">
                       <label>Unidades Hospitalares</label>
                        <select name="unidade_filtro_id" id="unidade_filtro_id" onchange="setores(this.value); procedimentos_table_reload();" required="true" class="form-control">
                            <option value="">Selecione uma unidade</option>
                            <?php foreach ($unidades_hospitalares as $unidades){ ?>
                             <option value="<?php echo $unidades['id']; ?>"><?php echo $unidades['razao_social']; ?></option>   
                            <?php } ?>
                        </select>
                    </div>
              </div>
              <!-- CATEGORIA -->
        </div>
         
      </div>
 
   </div>
   
</div>
