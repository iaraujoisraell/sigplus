<?php //print_r($dados); exit;
?>
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/bower_components/Ionicons/css/ionicons.min.css">

<?php $this->load->view('gestao_corporativa/css_background'); ?>
<input type="hidden" value="<?php echo get_staff_user_id(); ?>" name="staffid">
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <h5 style="font-weight: bold;">INTRANET - GERADOR DE ARQUIVOS - CONTROLE DE TABELAS</h5>
            <!-- <div>
                <ol class="breadcrumb" style="background-color: white;">
                     <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li>
                    <li><a href="<?= base_url('gestao_corporativa/Registro_ocorrencia'); ?>"><i class="fa fa-backward"></i> Histórico de Arquivos </a></li>
                </ol>
            </div>-->
        </div>

    </div>
    <div class="panel_s project-top-panel">
        <div class="panel-body _buttons">

            <div class="col-md-2">
                <button type="button" class="btn btn-success" title="Cadastrar Tabela" onclick="add_tabela()">ADD TABELA <i class="fa fa-plus"></i></button>
            </div>


        </div>
    </div>
    <br>


    <div class="row">
        <div class="col-md-12" id="small-table">
            <div class="panel_s">
                <div class="">

                </div>
                <div class="panel-body">

                    <table class="table dt-table table-bordered scroll-responsive" data-order-col="" data-order-type="">
                        <thead>
                            <tr>
                                <th>
                                    #ID
                                </th>
                                <th>
                                    DESCRIÇÃO
                                </th>
                                <th>
                                    CONTA RAZÃO
                                </th>
                                <th>
                                    NUMERO ORDEM
                                </th>
                                <th>
                                    CLASSE
                                </th>
                                <th>
                                    OPÇÕES
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lista_tabelas as $tabela) {
                                # code...
                            ?>
                                <tr>
                                    <td>
                                        <?php echo $tabela['id'] ?>
                                    </td>
                                    <td>
                                        <?php echo $tabela['descricao'] ?>
                                    </td>
                                    <td>
                                        <?php echo $tabela['conta_razao'] ?>
                                    </td>
                                    <td>
                                        <?php echo $tabela['numero_ordem'] ?>
                                    </td>
                                    <td>
                                        <?php echo $tabela['classe'] ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info" title="Editar Tabela" onclick="edit_tabela( <?php echo $tabela['id'] ?>)"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-danger" title="Excluir Tabela" onclick="delete_tabela( <?php echo $tabela['id'] ?>)"><i class="fa fa-trash"></i></button>

                                    </td>
                                </tr>

                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php // 
        ?>
    </div>


</div>
</div>

<?php init_tail(); ?>



<div id="modal_wrapper"></div>



<script>
    function add_tabela() {

        // alert(id);
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/gerador_arquivos/modal", {
            slug: 'add_tabela',

        }, function() {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#add_tabela').is(':hidden')) {
                $('#add_tabela').modal({
                    show: true,
                    backdrop: 'static'
                });
            }
        });


    }

    function edit_tabela(id_tabela) {

        // alert(id);
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/gerador_arquivos/modal", {
            slug: 'edit_tabela',
            id_tabela: id_tabela

        }, function() {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#edit_tabela').is(':hidden')) {
                $('#edit_tabela').modal({
                    show: true,
                    backdrop: 'static'
                });
            }
        });


    }

    function delete_tabela(id_tabela) {
        if (confirm("Tem certeza que deseja deletar esse registro ?") == true) {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('gestao_corporativa/Gerador_arquivos/delete_tabela'); ?>",
                data: {
                    id_tabela: id_tabela

                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    alert_float(obj.alert, obj.message);
                    window.location.reload();
                }
            });
        }
    }
</script>


</body>

</html>