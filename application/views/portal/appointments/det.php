
            <p style="mt">Confirmar Agendamento</p>
         
            
            <hr class="horizontal bg-gray-100 my-4">
          
                <p class="pst-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Especiapdade:</strong> &nbsp; <?php echo $det['DS_ESPECIALIDADE'];?></p>
                <p class="pst-group-item border-0 ps-0 text-sm"><strong class="text-dark">Médico:</strong> &nbsp; <?php echo $det['MEDICO'];?></p>
                <p class="pst-group-item border-0 ps-0 text-sm"><strong class="text-dark">Endereço:</strong> &nbsp; <?php echo $det['ENDERECO'];?></p>
                <p class="pst-group-item border-0 ps-0 text-sm"><strong class="text-dark">Tipo:</strong> &nbsp; <?php echo $det['IE_TIPO_ATENDIMENTO'];?></p>
                <p class="pst-group-item border-0 ps-0 text-sm"><strong class="text-dark">Data:</strong> &nbsp; <?php echo $date;?></p>
                <p class="pst-group-item border-0 ps-0 text-sm"><strong class="text-dark">Horário:</strong> &nbsp; <?php echo $horario;?></p>
                <p class="pst-group-item border-0 ps-0 pb-0">
                    <!--<strong class="text-dark text-sm">Social:</strong> &nbsp;
                    <a class="btn btn-facebook btn-simple mb-0 ps-1 pe-2 py-0" href="javascript:;">
                        <i class="fab fa-facebook fa-lg"></i>
                    </a>
                    <a class="btn btn-twitter btn-simple mb-0 ps-1 pe-2 py-0" href="javascript:;">
                        <i class="fab fa-twitter fa-lg"></i>
                    </a>
                    <a class="btn btn-instagram btn-simple mb-0 ps-1 pe-2 py-0" href="javascript:;">
                        <i class="fab fa-instagram fa-lg"></i>
                    </a>-->
                    <a class="btn btn-sm btn-success mb-0 mx-auto" href="<?php echo base_url('portal/appointments/schedule/');?><?php echo $det['CD_AGENDA'];?>?date=<?php echo $date;?>&especialidade=<?php echo $det['CD_ESPECIALIDADE'];?>" >Confirmar Agendamento</a>
                </p>
           
           


            <!--<ul class="pst-unstyled mx-auto">
                <p class="d-flex">
                    <p class="mb-0">Industry:</p>
                    <span class="badge badge-secondary ms-auto">Marketing Team</span>
                </p>
                <p>
                    <hr class="horizontal dark">
                </p>
                <p class="d-flex">
                    <p class="mb-0">Rating:</p>
                    <div class="rating ms-auto">
                        <i class="material-icons text-lg">grade</i>
                        <i class="material-icons text-lg">grade</i>
                        <i class="material-icons text-lg">grade</i>
                        <i class="material-icons text-lg">grade</i>
                        <i class="material-icons text-lg">star_outpne</i>
                    </div>
                </p>
                <p>
                    <hr class="horizontal dark">
                </p>
                <p class="d-flex">
                    <p class="mb-0">Members:</p>
                    <div class="avatar-group ms-auto">
                        <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Alexa Tompson">
                            <img alt="Image placeholder" src="../../assets/img/team-1.jpg">
                        </a>
                        <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Romina Hadid">
                            <img alt="Image placeholder" src="../../assets/img/team-2.jpg">
                        </a>
                        <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Alexander Smith">
                            <img alt="Image placeholder" src="../../assets/img/team-3.jpg">
                        </a>
                        <a href="javascript:;" class="avatar avatar-lg avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Martin Doe">
                            <img alt="Image placeholder" src="../../assets/img/team-4.jpg">
                        </a>
                    </div>
                </p>
            </ul>-->
