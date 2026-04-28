<div class="table-responsive">
    <table class="table table-bordered table-hover expenses-report" id="expenses-report-table">
        <thead>

            <tr> 

                <th class="bold" colspan="4">Documentos Vinculados </th>

            </tr>
            <tr> 

                <th class="bold">CODIGO</th>
                <th class="bold">TITULO</th>
                <th class="bold">IMPACTO</th>
                <th class="bold">EXCLUIR</th>


            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($links as $link) {
                //print_r($link);
                ?>
                <tr class="">
                    <td class=" bg-odd"> <span class="bold"><?php echo $link['codigo']; ?></span><br> Versão: <?php echo $link['numero_versao']; ?></td>
                    <td class="bold"> <?php echo $link['titulo']; ?></td>

                    <td style="text-align: center;"> 
                        <div class="checkbox checkbox-warning">
                            <input type="checkbox" name="required_old" id="required_old" value="1" <?php
                                   if ($link['has']) {
                                       echo 'disabled checked';
                                   }
                                   ?> onchange="toggleButtonVisibility(this, 'btn_add_request_<?php echo $link['link_id']; ?>')">
                            <label for="required"></label>
                        </div>
                        <?php if (!$link['has']) { ?>
                            <button type="button" id="btn_add_request_<?php echo $link['link_id']; ?>" onclick="Add_version('<?php echo $link['link_id']; ?>', '<?php echo $link['id']; ?>', '<?php echo $link['linker']; ?>');" class="btn btn-xs btn-success hide"  ><i class="fa fa-plus-square-o"></i> Solicitar Versão</button>
                        <?php
                        } else {
                            $this->db->where('id', $link['has']);
                            $request = $this->db->get('tbl_intranet_request')->row();
                            $can = false;
                            if ($request->status == 1) {
                                $class = 'success';
                                $title = 'Solicitação RESPONDIDA';
                                $can = true;
                            } elseif ($request->created) {
                                $class = 'info';
                                $title = 'Solicitação EM ANDAMENTO';
                            } else {
                                $class = 'warning';
                                $title = 'Solicitação ABERTA';
                            }
                            ?>
                            <button type="button" onclick="View_request('<?php echo $link['has']; ?>');" class="btn btn-xs btn-<?php echo $class; ?> "  ><i class="fa fa-eye"></i> <?php echo $title; ?></button>
                        <?php } ?>
                    </td>
                    <td> 
                        
                        <button type="button" onclick="Delete_link('<?php echo $link['link_id']; ?>', '<?php echo $link['linker']; ?>');" class="btn btn-xs btn-danger" <?php
                            if ($link['has'] and $can == false) {
                                echo 'disabled';
                            }
                            ?> ><i class="fa fa-trash"></i></button>
                    </td>


                </tr>
<?php } ?>
        </tbody>
    </table>
    <script>
        function toggleButtonVisibility(checkbox, btn_add_request) {
            var button = document.getElementById(btn_add_request);

            if (checkbox.checked) {
                button.classList.remove('hide');
            } else {
                button.classList.add('hide');
            }
        }
    </script>
    <script>
        function Add_version(el, id, cdc) {
            $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Cdc/modal", {
                slug: 'add_version',
                el: el,
                id: id,
                cdc: cdc,
                type: 'cdc'
            }, function () {
                if ($('.modal-backdrop.fade').hasClass('in')) {
                    $('.modal-backdrop.fade').remove();
                }
                if ($('#add_request_version').is(':hidden')) {
                    $('#add_request_version').modal({
                        show: true
                    });
                }
            });
        }

        function View_request(id) {
            $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Cdc/modal", {
                slug: 'view_request',
                id: id
            }, function () {
                if ($('.modal-backdrop.fade').hasClass('in')) {
                    $('.modal-backdrop.fade').remove();
                }
                if ($('#view_request').is(':hidden')) {
                    $('#view_request').modal({
                        show: true
                    });
                }
            });
        }
        function add_request_cdc(linker, linked, departmentid, cdc) {
            var description = document.getElementById("description_version").value;
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('gestao_corporativa/Cdc/add_request'); ?>",
                data: {
                    description: description,
                    linker: linker,
                    linked: linked,
                    departmentid: departmentid,
                    cdc: cdc
                },
                success: function (response) {

                    document.getElementById("table_search").innerHTML = response;
                     $('#add_request_version').modal('hide');
                     if ($('.modal-backdrop.fade').hasClass('in')) {
                    $('.modal-backdrop.fade').remove();
                }
                    
                    
                }
            });

        }

        function Delete_link(link, cdc) {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('gestao_corporativa/Cdc/delete_link'); ?>",
                data: {
                    link: link,
                    cdc: cdc
                },
                success: function (response) {
                    document.getElementById("table_search").innerHTML = response;

                }
            });

        }
    </script>
</div>
<div id="modal_wrapper"></div>