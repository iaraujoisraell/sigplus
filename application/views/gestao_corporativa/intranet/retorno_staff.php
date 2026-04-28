<?php
if ($staffs) {
    foreach ($staffs as $staff):
        ?>
        <div class=" col-md-2 d-flex align-items-stretch flex-column">
            <div class="card bg-light d-flex flex-fill">
                <!--<div class="card-header text-muted border-bottom-0">
                <?php //echo $staff['name']; ?>
                </div>-->
                <div class="card-body pt-10">
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
                                <?php //print_r($staff);?>
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
        <?php
    endforeach;
} else {
    ?>
    <div class=" col-md-12 d-flex align-items-stretch flex-column">
        <div class="card bg-light d-flex flex-fill">
            <div class="card-header text-muted border-bottom-0">
                Nada Encontrado!
            </div>
        </div>
    </div>
    <?php
}?>