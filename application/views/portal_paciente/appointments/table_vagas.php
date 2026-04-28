<?php
if (is_array($vagas)) {
    //print_r($vagas); exit;
    ?>
    <div class="row">


        <?php
        $i = 0;
        foreach ($vagas as $vaga) {

            //print_r($vaga);
            //exit;
            ?>
            <div class="d-flex mt-2 col-md-4 col-sm-12 ">

                <div class="">
                    <div class="numbers">
                        <h6 class="mb-1 text-dark text-sm">Dr(a) <?php echo $vaga->MEDICO; ?></h6>
                        <span class="text-sm">Data mais Próxima: <?php echo $vaga->DATA_PROXIMA; ?></span><br>
                        <span class="text-sm">Endereço: <?php echo $vaga->ENDERECO;?></span>
                    </div>
                </div>
                <a href="#" onclick="openrow('<?php echo $i; ?>', '<?php echo $vaga->CD_AGENDA; ?>')" style="margin-right: 0px;">
                    <div class="icon icon-shape bg-gradient-success icon-md text-center border-radius-md shadow-none">
                        <i class="material-icons text-white opacity-10" aria-hidden="true">event_available</i>
                    </div>
                </a>
            </div>
            <div class="" style="display: none;" id="det<?php echo $i; ?>">

                
            </div>
            <?php
            $i++;
        }
        ?>


    </div>
    <script>

        function openrow(i, cod_agenda) {

            if (document.getElementById('det' + i).style.display == 'none') {
                if (cod_agenda != "") {
                    $.ajax({
                        method: "POST",
                        url: "<?php echo base_url('portal/Appointments/get_vagas'); ?>",
                        data: {
                            cod_agenda: cod_agenda,
                            "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
                        },
                        success: function (data) {
                            document.getElementById('det' + i).style.display = '';
                            $('#see_det' + i).html('Fechar');
                            $('#det' + i).html(data);
                        }
                    });
                } else {
                    alert('INVALIDO!');
                }
            } else {
                document.getElementById('det' + i).style.display = 'none';
                $('#see_det' + i).html('Vagas');
                $('#det' + i).html('');
            }




        }



    </script>
<?php } else { ?>
    <script src="<?php echo base_url(); ?>assets/portal/js/plugins/sweetalert.min.js"></script>
    <script>


        material.showSwal('info-message', 'Sem Vagas!', 'Esta especialidade não tem vagas disponíveis. Por favor, selecione outra especialidade.');



    </script>
<?php } ?>