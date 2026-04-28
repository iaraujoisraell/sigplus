


<?php //print_r($appointment);?>

<p class="text-sm font-weight-normal"> Prezado(a) <u><?php echo $appointment['NM_PACIENTE']; ?></u> segue as informações do agendamento abaixo:</p>
<ul class="list-unstyled mx-auto">
    <li class="d-flex mt-0 mb-0 mt-0 mb-0">
        <p class="mt-0 mb-0">Especialidade:</p>
        <span class="badge badge-secondary ms-auto"><?php echo $appointment['DS_ESPECIALIDADE']; ?></span>
    </li>
    <li class="mt-0 mb-0">
        <hr class="horizontal dark">
    </li>
    <li class="d-flex mt-0 mb-0">
        <p class="mt-0 mb-0">Status do Agendamento:</p>
        <span class="badge badge-warning   ms-auto"><?php echo $appointment['DS_STATUS_AGENDA']; ?></span>
    </li>
    <li class="mt-0 mb-0">
        <hr class="horizontal dark">
    </li>
    <li class="d-flex mt-0 mb-0">
        <p class="mt-0 mb-0">Medico:</p>
        <p class="  ms-auto"><?php echo $appointment['NM_AGENDA']; ?></p>
    </li>
    <li class="mt-0 mb-0">
        <hr class="horizontal dark">
    </li>
    <li class="d-flex mt-0 mb-0">
        <p class="mt-0 mb-0">Data da Consulta:</p>
        <p class="text-sm font-weight-normal  ms-auto"><?php echo $appointment['DT_AGENDA']; ?> <?php echo $appointment['HR_INICIO']; ?></p>
    </li>
    <li class="mt-0 mb-0">
        <hr class="horizontal dark">
    </li>
    <li class="d-flex mt-0 mb-0">
        <p class="mt-0 mb-0">Tipo de Atendimento:</p>
        <p class="  ms-auto"><?php echo $appointment['IE_TIPO_ATENDIMENTO']; ?></p>
    </li>
    <li class="mt-0 mb-0">
        <hr class="horizontal dark">
    </li>
    <li class="d-flex mt-0 mb-0">
        <p class="mt-0 mb-0">Local:</p>
        <p class="  ms-auto"><?php echo $appointment['ENDERECO']; ?></p>
    </li><!-- comment -->
    <li class="mt-0 mb-0">
        <hr class="horizontal dark">
    </li>

    <li class="d-flex mt-0 mb-0">
        <p class="mt-0 mb-0">Data de Cadastro:</p>
        <p class=" text-sm font-weight-normal ms-auto"><?php echo $appointment['DT_AGENDAMENTO']; ?></p>
    </li>
    <li class="mt-0 mb-0">
        <hr class="horizontal dark">
    </li>

</ul>
<?php if ($appointment['DS_OBSERVACAO']) { ?>
    <p class="text-sm font-weight-normal">Observação: <?php echo $appointment['DS_OBSERVACAO']; ?></p>
<?php } ?>
<?php if ($appointment['INSTRUCOES']) { ?>
    <p class="text-sm font-weight-normal">AVISOS: <?php echo $appointment['INSTRUCOES']; ?></p>
<?php } ?>
<?php if ($appointment['DT_CANCELAMENTO']) { ?>
    <div class="alert alert-danger alert-dismissible text-white fade show" role="alert">
        <span class="alert-icon align-middle">
            <span class="material-icons text-md">
                thumb_down_off_alt
            </span>
        </span>
        <span class="alert-text"><strong>Cancelado!</strong> Em <?php echo $appointment['DT_CANCELAMENTO'];?></span>
       
    </div>
<?php } else { ?>
    <?php if ($appointment['DT_CONFIRMACAO']) { ?>
        <div class="alert alert-success alert-dismissible text-white fade show" role="alert">
            <span class="alert-icon align-middle">
                <span class="material-icons text-md">
                    thumb_up_off_alt
                </span>
            </span>
            <span class="alert-text"><strong>Confirmado!</strong> Em <?php echo $appointment['DT_CONFIRMACAO'];?></span>
            
        </div>
    <?php } ?>
<?php } ?>
<?php if ($appointment['DT_ATENDIDO']) { ?>
    <div class="alert alert-secondary alert-dismissible text-white fade show" role="alert">
        <span class="alert-icon align-middle">
          <span class="material-icons text-md">
          thumb_up_off_alt
          </span>
        </span>
        <span class="alert-text"><strong>Atendido!</strong> Em <?php echo $appointment['DT_ATENDIDO'];?></span>
        
    </div>

 <?php } ?>
