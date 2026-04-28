

<section class="col-lg-12 "> 
<div class="card">
       
      <div class="card-header">
        <h3 class="card-title">
          <i class="ion ion-clipboard mr-1"></i>
          Perguntas
        </h3>

        <div class="card-tools">
          <ul class="pagination pagination-sm">
            <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
            <li class="page-item"><a href="#" class="page-link">1</a></li>
            <li class="page-item"><a href="#" class="page-link">2</a></li>
            <li class="page-item"><a href="#" class="page-link">3</a></li>
            <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
          </ul>
        </div>
      </div>

      <!-- /.card-header -->
      <div class="card-body">
        <ul class="todo-list" data-widget="todo-list">
            <?php foreach ($perguntas as $pergunta){ ?>
            <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                           
                            <div class="col-md-12">
                                    <input type="hidden" name="form_id" id="form_id" value="<?php echo $form->id; ?>">
                                    <input style="width: 500px;" placeholder="pergunta" name="titulo" id="titulo" type="text"  value="<?php echo $pergunta->title; ?>" class="form-control">
                                </div>
                        </h3>

                        <div class="card-tools">
                            <div class="select-placeholder form-group">
                            <select name="type" id="type" class="selectpicker form-control"  data-width="100%"  data-hide-disabled="true">
                                <option value="text" <?php if(isset($custom_field) && $custom_field->type == 'input'){echo 'selected';} ?>>Resposta Curta</option>
                                <option value="textarea" <?php if(isset($custom_field) && $custom_field->type == 'textarea'){echo 'selected';} ?>>Parágrafo</option>
                                <option value="select" <?php if(isset($custom_field) && $custom_field->type == 'select'){echo 'selected';} ?>>Lista suspensa</option>
                                <option value="number" <?php if(isset($custom_field) && $custom_field->type == 'number'){echo 'selected';} ?>>Número</option>
                                <option value="multiselect" <?php if(isset($custom_field) && $custom_field->type == 'multiselect'){echo 'selected';} ?>> Multipla Escolha</option>
                                <option value="checkbox" <?php if(isset($custom_field) && $custom_field->type == 'checkbox'){echo 'selected';} ?>>Caixa de Seleção</option>
                                <option value="date_picker" <?php if(isset($custom_field) && $custom_field->type == 'date_picker'){echo 'selected';} ?>>Date </option>
                                <option value="date_picker_time" <?php if(isset($custom_field) && $custom_field->type == 'date_picker_time'){echo 'selected';} ?>>Horário</option>
                                <option value="colorpicker" <?php if(isset($custom_field) && $custom_field->type == 'colorpicker'){echo 'selected';} ?>>Cores</option>
                                <option value="link" <?php if(isset($custom_field) && $custom_field->type == 'link'){echo 'selected';} ?><?php if(isset($custom_field) && $custom_field->fieldto == 'items'){echo 'disabled';} ?>>Hyperlink</option>
                            </select>
                           </div>
                        </div>
                    </div> 
                    <div class="card-body">
                        <li>
                            <!-- drag handle -->
                            <span class="handle">
                                <i class="fas fa-ellipsis-v"></i>
                                <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <!-- checkbox -->
                            <div  class="icheck-primary d-inline ml-2">
                                <input type="checkbox" value="" name="todo1" id="todoCheck1">
                                <label for="todoCheck1"></label>
                            </div>
                            <!-- todo text -->
                            <span class="text">Design a nice theme</span>
                            <!-- Emphasis label -->
                            <small class="badge badge-danger"><i class="far fa-clock"></i> 2 mins</small>
                            <!-- General tools such as edit or delete-->
                            <div class="tools">
                                <i class="fas fa-edit"></i>
                                <i class="fas fa-trash-o"></i>
                            </div>
                        </li>
                    </div>

                    <div class="card-footer clearfix">
                        <button type="button" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Add item</button>
                    </div>
                </div>
            <?php } ?>


        </ul>
      </div>
      <!-- /.card-body -->
      <div class="card-footer clearfix">
        <button type="button" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Add item</button>
      </div>
    </div>
   </section>   




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