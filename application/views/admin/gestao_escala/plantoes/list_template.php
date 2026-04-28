<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12">
    <section class="content-header">
      <h1>
        Escala - Unidades Hospitalares
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-dashboard"></i> Gestão de Plantão </a></li>
        <li class="active"><a href="<?php echo admin_url('financeiro'); ?>">Add Escala </a></li>
      </ol>
    </section>
   <div class="panel_s mbot10">
      <div class="panel-body _buttons">
          
         <?php $this->load->view('admin/financeiro/conta_pagar/invoices_top_stats'); ?>
         <?php if(has_permission('invoices','','create')){ ?>
            <a href="#" class="btn btn-info pull-left new new-invoice-list mright5"  data-toggle="modal" data-target="#add_escala_modal" data-id=""><?php echo 'Add Escala'; ?></a>
         <?php } ?>
            <br>
            
            <div class="row">
                <br><Br>
            </div>    
         <div class="row">        
              <div class="col-md-3">
                  <div class="form-group">
                      <label>Competência</label>
                      <select name="competencia_id" id="competencia_id"  required="true" class="form-control" onchange="procedimentos_table_reload();">

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
   <div class="row">
       
      <div class="col-md-12" id="small-table">
         <div class="panel_s">
                
            <div class="panel-body">
               <!-- if invoiceid found in url -->
               <?php echo form_hidden('invoiceid',$invoiceid); ?>
               <?php $this->load->view('admin/gestao_escala/plantoes/table_html'); ?>
            </div>
         </div>
      </div>
      <div class="col-md-7 small-table-right-col">
         <div id="invoice" class="hide">
         </div>
      </div>
   </div>
</div>
