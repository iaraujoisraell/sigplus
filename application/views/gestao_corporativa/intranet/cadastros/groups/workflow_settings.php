
<h4 class="customer-profile-group-heading"><?php echo 'Workflow - Configurações'; ?></h4>
<?php 
            $data['rel_type'] = 'workflow';
            $this->load->view('gestao_corporativa/categorias_campos/admin_categoria_tab', $data);?>
<div id="modal_wrapper"></div>


<?php init_tail(); ?>
<script>
    function mudar(el) {
        var display = document.getElementById(el).style.display;
        if (display == "none")
            document.getElementById(el).style.display = 'block';
        else
            document.getElementById(el).style.display = 'none';
    }
    function change(value) {
        if (value === 'select' || value === 'multiselect') {
            document.getElementById('listadeopcoes').style.display = 'block';
        } else {
            document.getElementById('listadeopcoes').style.display = 'none';
        }
    }
    function change_separador(value) {
        if (value === 'separador') {
            document.getElementById('tam_ocultar').style.display = 'none';
            document.getElementById('check_ocultar').style.display = 'none';
            document.getElementById('obrigatorio').removeAttribute("checked");
        } else {
            document.getElementById('tam_ocultar').style.display = 'block';
            document.getElementById('check_ocultar').style.display = 'block';

        }
    }
    function delete_categoria(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Workflow/delete_tipo'); ?>",
            data: {
                id: id
            },
            success: function (data) {
                reload_categoria();
            }
        });
    }

    


    function Update_categoria(el) {
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Workflow/modal", {
            slug: 'update_categoria',
            id: el
        }, function () {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#edit_categoria').is(':hidden')) {
                $('#edit_categoria').modal({
                    show: true
                });
            }
        });
    }

    function Campos(el) {
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Workflow/modal", {
            slug: 'campos_categoria',
            id: el
        }, function () {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#Campos').is(':hidden')) {
                $('#Campos').modal({
                    show: true
                });
            }
        });
    }

    function add_categoria() {
        var titulo = document.querySelector("#titulo_nova_categoria");
        var titulo = titulo.value;
        var prazo = document.querySelector("#prazo_nova_categoria");
        var prazo = prazo.value;
        var select = document.getElementById('responsavel_nova_categoria');
        var responsavel = select.options[select.selectedIndex].value;
        
        let checkbox = document.getElementById('aprovacao');
        if (checkbox.checked) {
            var aprovacao_gestor = 1;
        } else {
            var aprovacao_gestor = 0;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Workflow/add_tipo'); ?>",
            data: {
                titulo: titulo, prazo: prazo, responsavel: responsavel, aprovacao: aprovacao_gestor
            },
            success: function (data) {
                $('#add_tipo').modal('hide');
                reload_categoria();
            }
        });
    }
    function add_opcoes() {
        var div = document.getElementById('duplicar'),
                clone = div.cloneNode(true); // true means clone all childNodes and all event handlers
        $('.caixadefilhos').append(clone);
        //deletclass('duplicar' + id);

    }

    $(function () {
        var CustomersServerParams = {};
        var tAPI = initDataTable('.table-table', '<?php echo base_url(); ?>' + 'gestao_corporativa/Workflow/table_categorias', [0], [0], CustomersServerParams, [1, 'desc']);
    });
    
    function reload_categoria() {
        var CustomersServerParams = {};
        if ($.fn.DataTable.isDataTable('.table-table')) {
            $('.table-table').DataTable().destroy();
        }
        var tAPI = initDataTable('.table-table', '<?php echo base_url(); ?>' + 'gestao_corporativa/Workflow/table_categorias', [0], [0], CustomersServerParams, [1, 'desc']);
    }


</script>
