<style>
    ul.breadcrumb {
        padding: 10px 16px;
        list-style: none;
        background-color: white;
    }
    ul.breadcrumb li {
        display: inline;
        font-size: 18px;
    }
    ul.breadcrumb li+li:before {
        padding: 8px;
        color: black;
        content: "/\00a0";
    }
    ul.breadcrumb li a {
        color: #0275d8;
        text-decoration: none;
    }
    ul.breadcrumb li a:hover {
        color: #01447e;
        text-decoration: underline;
    }
</style>
<div class="" style="padding: 10px;">
    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                <li><a href="<?= base_url('gestao_corporativa/intranet/contatos'); ?>"><i class="fa fa-users"></i> Contatos </a></li> 
            </ul>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group" >
                <input type="text" class="form-control" placeholder="Pesquisar" name="pesquisa" id="pesquisa" onkeyup="refresh_table();">
                <div class="input-group-append">
                    <div class="btn btn-primary" onclick="refresh_table();">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>
        </div> 
        <div class="col-md-6">
            <div class="input-group input-group-sm" >
                <?php
                $this->load->model('Departments_model');
                $setores = $this->Departments_model->get();
                ?>
                <select class="form-control select2bs4" style="width: 100%;" onchange="table_departamentos(this.value);">
                    <option selected="selected" disabled>Todos os Setores</option>
                    <?php foreach ($setores as $setor) { ?>
                        <option value="<?php echo $setor['departmentid']; ?>"><?php echo $setor['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div> 
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-solid" style="margin-top: 15px;">
                <div class="card-body pb-0">
                    <div class="row" id="trocar">
                        <?php foreach ($staffs as $staff): ?>
                            <div class=" col-md-2 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                    <!--<div class="card-header text-muted border-bottom-0">
                                    <?php //echo $staff['name']; ?>
                                    </div>-->
                                    <div class="card-body pt-10" style="align-items: center;">
                                        <div class="row">
                                            <div class="col-7">
                                                <h5 class="lead"><b><?php echo ($staff['firstname'] . ' ' . $staff['lastname']); ?></b></h5>

                                            </div>
                                            <div class="col-5 text-center" style="width: 70px; height: 70px; position: relative; background-repeat: no-repeat;">
                                                <?php if ($staff['profile_image']): ?>
                                                    <img class="img-circle img-fluid" style=" width: 100%; height: 100%;" src="<?php echo base_url(); ?>uploads/staff_profile_images/<?php echo $staff['staffid']; ?>/<?php echo 'small_' . $staff['profile_image']; ?>" alt="User Image" >
                                                <?php else: ?>
                                                    <img class="img-circle img-fluid" style=" width: 100%; height: 100%;" src="<?php echo base_url(); ?>assets/images/user-placeholder.jpg" alt="User Image">
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-12">
                                                <ul class="ml-4 mb-0 fa-ul text-muted">
                                                    <li class="small"><span class="fa-li"><i class="fas fa-building"></i></span> Cargo: <?php echo $staff['cargo']; ?></li>
                                                    <li class="small"><span class="fa-li"><i class="fas fa-phone"></i></span> Telefone: <?php echo $staff['phonenumber']; ?></li>
                                                    <li class="small"><span class="fa-li"><i class="fas fa-hashtag"></i></span> Ramal: <?php echo $staff['num_ramal']; ?></li>
                                                    <li class="small"><span class="fa-li"><i class="fa fa-at"></i></span> Email: <?php echo $staff['email']; ?></li>
                                                    <li class="small"><span class="fa-li"><i class="fa fa-briefcase"></i></span> Departamento: <ul class="ml-2 mb-0 fa-ul text-muted"><?php
                                                            $this->load->model('Departments_model');
                                                            $deps = $this->Departments_model->get_staff_departments($staff['staffid']);
                                                            foreach ($deps as $dep) {
                                                                ?> <li>  <?php echo $dep['name']; ?></li> <?php } ?>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="card-footer">
                                        <div class="text-right">
                                            <a href="#" class="btn btn-sm bg-teal">
                                                <i class="fas fa-comments"></i>
                                            </a>
                                            <a href="<?php echo base_url('gestao_corporativa/intranet/perfil?id=' . $staff['staffid']); ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-user"></i> Ver Perfil
                                            </a>
                                        </div>
                                    </div>
                                    -->
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>
        <!-- FOOTER -->


        <!-- Control Sidebar -->
        <?php $this->load->view('gestao_corporativa/intranet/control_sidebar.php'); ?>
        <!-- /.control-sidebar -->
    </div>




</div>



<script>
    function refresh_table() {
        $('#trocar').html('<div id="carregando"><div class="c-loader"></div></div>');
        var input = document.querySelector("#pesquisa");
        var texto = input.value;

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Intranet/retorno_staffs'); ?>",
            data: {
                texto: texto
            },
            success: function (data) {
                $('#trocar').html(data);
            }
        });
    }


</script>
<style>

    #carregando {
        align-items: center;
        display: flex;
        justify-content: center;
        min-height: 100vh;
        transform: scale(5);
        width: 100%;
    }
    .c-loader {
        animation: is-rotating 1s infinite;
        border: 6px solid #e5e5e5;
        border-radius: 50%;
        border-top-color: #51d4db;
        height: 50px;
        width: 50px;
    }

    @keyframes is-rotating {
        to {
            transform: rotate(1turn);
        }
    }
</style>
<script>
    function table_departamentos(id) {
        $('#trocar').html('<div id="carregando"><div class="c-loader"></div></div>');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Intranet/retorno_departamentos'); ?>",
            data: {
                id: id
            },
            success: function (data) {
                $('#trocar').html(data);
            }
        });
    }
</script>