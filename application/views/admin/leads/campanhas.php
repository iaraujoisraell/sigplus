<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<script>
    function Mudarestado(el) {
        var display = document.getElementById(el).style.display;
        if (display == "none")
            document.getElementById(el).style.display = 'block';
        else
            document.getElementById(el).style.display = 'none';
    }</script>


<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="#" data-toggle="modal" data-target="#addcampanha" class="btn mright5 btn-info pull-left display-block">
                                Nova Campanha
                            </a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <?php if (count($campaigns) > 0) { ?>
                                <table class="table dt-table scroll-responsive" data-order-col="1" data-order-type="asc">
                                <thead>
                                <th><?php echo _l('id'); ?></th>
                                <th>Nome da Campanha</th>
                                <th>Tags</th>
                                <th>Status</th>
                                <th>Fontes</th>
                                <th><?php echo _l('options'); ?></th>
                                </thead>
                                <tbody>
                                    <?php foreach ($campaigns as $c): ?>

                                        <?php if ($c->isdefault == 0): ?>
                                            <tr>
                                                <td>
                                                    <?php echo $c->id; ?>
                                                </td>
                                                <td><a href="<?php echo admin_url('leads/switch_kanban_campanha/' . $c->id); ?>" ><?php echo $c->titulo; ?></a><br />
                                                    <!--<span class="text-muted">
                                                    <?php // echo _l('leads_table_total', total_rows(db_prefix() . 'leads', array('status' => $status['id']))); ?></span>-->
                                                </td>
                                                <td>
                                                    <?php
                                                    $tags = explode(",", $c->tags);
                                                    $contador = 0;
                                                    foreach ($tags as $tag):
                                                        ?>
                                                        <span class="badge badge-info text-muted items-center">
                                                            <p><?php echo $tag; ?></p>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $i = 0;
                                                    for ($i = 0; $i < count($c->status); $i++) {
                                                        ?>
                                                        <span class="badge badge-info text-muted items-center">
                                                            <p><?php echo $c->status[$i]['name']; ?></p>
                                                        </span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $i = 0;
                                                    for ($i = 0; $i < count($c->fontes); $i++) {
                                                        ?>
                                                        <span class="badge badge-info text-muted items-center">
                                                            <p><?php echo $c->fontes[$i]['name']; ?></p>
                                                        </span>
                                                    <?php } ?>

                                                </td>


                                                <?php ?>
                                                <td>
                                                    <a  href="#" data-toggle="modal" data-target="#status<?php echo $c->id ?>" data-color="<?php echo $status['color']; ?>" data-name="<?php echo $status['name']; ?>" data-order="<?php echo $status['statusorder']; ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a>
                                                    <a href="<?php echo admin_url('leads/delete_camp/' . $c->id); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>


                                                </td>
                                            </tr>
                                            <div class="modal fade" id="status<?php echo $c->id ?>" tabindex="-1" role="dialog">
                                                <div class="modal-dialog">
                                                    <?php echo form_open('admin/leads/add_campanha', array('id' => 'form')); ?>
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">
                                                                <span class="edit-title">Editar Campanha</span>
                                                            </h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <input type="hidden" name="id" id="id" required="true"  class="form-control" value="<?php echo $c->id; ?>">
                                                                    <label>Título</label>
                                                                    <input type="text" name="titulo" id="titulo" required="true"  class="form-control" value="<?php echo $c->titulo; ?>">
                                                                    <br>
                                                                    <div class="row col-md-12">
                                                                        <div class="row col-md-6">
                                                                            <?php
                                                                                for ($i = 0; $i < count($statuses); $i++) {
                                                                                    if (in_array($statuses[$i], $c->status)) {
                                                                                        ?>

                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input " type="checkbox" value="<?php echo $statuses[$i]['id']; ?>" id="status" name="status[]" checked>
                                                                                            <label class="custom-control-label" ><?php echo $statuses[$i]['name']; ?></label>
                                                                                        </div>
                                                                                    <?php } else { ?>

                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input" type="checkbox" value="<?php echo $statuses[$i]['id']; ?>" id="status" name="status[]">
                                                                                            <label class="custom-control-label"><?php echo $statuses[$i]['name']; ?></label>
                                                                                        </div>
                                                                                    <?php }
                                                                                }
                                                                                ?>
                                                                        </div>
                                                                        <div class="row col-md-6">

                                                                            <?php
                                                                                for ($i = 0; $i < count($sources); $i++) {
                                                                                    if (in_array($sources[$i], $c->fontes)) {
                                                                                        ?>

                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input " type="checkbox" value="<?php echo $sources[$i]['id']; ?>" id="status" name="fontes[]" checked>
                                                                                            <label class="custom-control-label" ><?php echo $sources[$i]['name']; ?></label>
                                                                                        </div>
                                                                                    <?php } else { ?>

                                                                                        <div class="form-check">
                                                                                            <input class="form-check-input" type="checkbox" value="<?php echo $sources[$i]['id']; ?>" id="status" name="fontes[]">
                                                                                            <label class="custom-control-label"><?php echo $sources[$i]['name']; ?></label>
                                                                                        </div>
                                                                                    <?php }
                                                                                }
                                                                                ?>

                                                                            </div>
                                                                    </div>
                                                                    <div class="row col-md-12">
                                                                        <div class="row col-md-12">
                                                                            <?php 
                                                                                /*
                                                                                $equipe = explode(",",$c->equipe);
                                                                                $selected = array();
                                                                                foreach($staff as $member){
                                                                                    if (in_array($member['staffid'], $equipe)) {
                                                                                       array_push($selected,$member['staffid']);
                                                                                    }
                                                                               }
                                                                                array_push($selected,get_staff_user_id());
                                                                                
                                                                                echo render_select('equipe[]',$staff,array('staffid',array('firstname','lastname')),'Equipe',$selected, array('multiple'=>true));
                                                                                 * 
                                                                                 */
                                                                            ?>
                                                                        <div>
                                                                    <div>
                                                                     <div>
                                                                    
                                                                    <br><label>Tags</label>
                                                                    <?php 
                                                                    
                                                                    ?>
                                                                    <input type="text" class="tagsinput" id="tags" name="tags" value="<?php echo $c->tags; ?>" data-role="tagsinput">



                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                                                            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                    <?php echo form_close(); ?>
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                            </div>    
                                    <?php endif;
                                endforeach;
                                ?>
                                </tbody>
                            </table>
                        <?php } else { ?>
                            <p class="no-margin"><?php echo 'Sem campanha(s) Cadastradas'; ?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addcampanha" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <?php echo form_open('admin/leads/add_campanha', array('id' => 'parcela_form')); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <span class="edit-title">Salvar Campanha</span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Título</label>
                            <input type="text"  name="titulo" id="titulo_c" required="true"  class="form-control">
                            <br>
                            <?php
                            $selected = '';
                            if (isset($lead)) {
                                $selected = $lead->status;
                            } else if (isset($status_id)) {
                                $selected = $status_id;
                            }
                            echo render_leads_status_select($statuses, $selected, 'lead_add_edit_status', 'status[]', array('multiple' => true));
                            ?>
                            <?php
                            $selected = (isset($lead) ? $lead->source : get_option('leads_default_source'));
                            echo render_leads_source_select($sources, $selected, 'lead_add_edit_source', 'fontes[]', array('multiple' => true));
                            
                            
                            $selected = '';
                            //if($invoice->sale_agent == null){
                               $selected = get_staff_user_id();
                            //}
                            echo render_select('equipe[]',$staff,array('staffid',array('firstname','lastname')),'Equipe',$selected, array('multiple'=>true,'data-actions-box'=>true));
                            ?>
                            <label>Tags</label>
                            <input type="text" class="tagsinput" id="tags" name="tags" value="<?php echo (isset($lead) ? prep_tags_input(get_tags_in($lead->id, 'lead')) : ''); ?>" data-role="tagsinput">


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                    <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                </div>
            </div>
            <!-- /.modal-content -->
            <?php echo form_close(); ?>
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>
<?php include_once(APPPATH . 'views/admin/leads/status.php'); ?>
<?php init_tail(); ?>
</body>
</html>
