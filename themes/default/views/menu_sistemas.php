<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<!DOCTYPE html>
<html lang="en">
<?php  $this->load->view($this->theme . 'menu_head'); ?>
 
<body>

    <div id="wrapper">

         <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("auth/troca_perfil_dashboard/".$projeto_selecionado, $attrib);
                ?>
        <?php
                    $usuario = $this->session->userdata('user_id');
                    $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                    
                    ?>
                <!-- begin TOP NAVIGATION  style="background-color: seagreen; position: relative; width: 100%; height: 50px;"-->
                <nav style="background-color: <?php echo $projetos->botao; ?>;" class="navbar-top"  role="navigation">

                    <div class="navbar-header">
                           
                                        <a  href="<?= site_url('Login_Projetos/menu_sistemas'); ?>" >
                                            <img width="170px" height="50px" src="<?= base_url() ?>assets/uploads/logos/LogoUnimed1.png " >
                                        </a>  
                     
                    <?php
                    $usuario = $this->session->userdata('user_id');
                    $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                    $perfil_atual = $projetos->group_id;
                    $perfis_user = $this->site->getUserGroupAtual($perfil_atual);

                   $perfis_user = $this->site->getPerfilusuarioByID($usuario);
                   $qtde_perfis_user = 0;
                       foreach ($perfis_user as $item) {
                           $qtde_perfis_user++;
                       }
                    ?>
                        
                        
                    </div>
                    
                    
                     
                    
                    
                    <div class="nav-top">
                        <ul class="nav navbar-right">
                            
                            <li>
                                <img style="width: 50px; height: 50px; margin-top: -5px;" alt="" src="<?= $this->session->userdata('avatar') ? $assets . '../../../assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>" class="mini_avatar img-rounded">
                            </li>
                          <li style="text-decoration: none"  >
                                    <span style="text-decoration: none" >
                                        <p style="font-size: 14px; color: #ffffff;">Olá <?= $this->session->userdata('username'); ?>, Bem Vindo </p>
                                     </li>
                           
                       
                       </li>
                        
                            <li class="dropdown">
                                <a class="red dropdown-toggle" href="<?= site_url('Auth/logout'); ?>">
                                    <i class="fa fa-sign-out"></i> 
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
                
                
                <?php echo form_close(); ?>

       
        <!-- end SIDE NAVIGATION -->

        <!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">

                <div class="col-lg-12">
                <center>
                    <div >
                        <div style="margin-top: 15%;" class="portlet-body">
                             <?php
                             $sistemas = $this->site->getAllSistemasByID($usuario);
                             foreach ($sistemas as $sistema) {                                         
                            ?>
                            
                           
                                <div class="col-lg-3 col-sm-6">
                                  <div class="tile <?php echo $sistema->cor; ?> dash-demo-tile">
                                    <h3> <?php echo $sistema->descricao; ?></h3>
                                    
                                    <div style="margin-top: 20%;" class="col-md-12">
                                        <div class="fprom-group"><a  class="btn btn-large btn-default"  href="<?= site_url("$sistema->link"); ?>"><?= lang('ACESSAR') ?></a></div>
                                    </div>

                                   </div>
                                    
                                </div>
                            
                            

                            <?php     
                             }
                            ?>

                        </div>
                    </div>
                </center>
                </div>

            </div>
           

        </div>
        <!-- /#page-wrapper -->
        <!-- end MAIN PAGE CONTENT -->

    </div>
    <!-- /#wrapper -->

    <!-- GLOBAL SCRIPTS -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/jquery.popupoverlay.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/defaults.js"></script>
    <!-- Logout Notification Box -->
   
    <!-- /#logout -->
    <!-- Logout Notification jQuery -->
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/logout.js"></script>
    <!-- HISRC Retina Images -->
    <script src="<?= $assets ?>dashboard/js/plugins/hisrc/hisrc.js"></script>

    <!-- PAGE LEVEL PLUGIN SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/plugins/ladda/spin.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/ladda/ladda.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap-multiselect/bootstrap-multiselect.js"></script>

    <!-- THEME SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/flex.js"></script>
    <script src="<?= $assets ?>dashboard/js/demo/buttons-demo.js"></script>
    
    
    
    
        <script src="<?= $assets ?>dashboard/js/plugins/fullcalendar/fullcalendar.min.js"></script>
        
        
        <script>
    //Calendar Demo
$(function() {


    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    /* initialize the calendar
        -----------------------------------------------------------------*/

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        editable: true,
        droppable: true, // this allows things to be dropped onto the calendar !!!
        drop: function(date, allDay) { // this function is called when something is dropped

            // retrieve the dropped element's stored Event Object
            var originalEventObject = $(this).data('eventObject');

            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);

            // assign it the date that was reported
            copiedEventObject.start = date;
            copiedEventObject.allDay = allDay;

            // render the event on the calendar
            // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

            // is the "remove after drop" checkbox checked?
            if ($('#drop-remove').is(':checked')) {
                // if so, remove the element from the "Draggable Events" list
                $(this).remove();
            }
        },

        

        events: [
            <?php
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $eventos = $this->projetos_model->getAllMarcosProjetoByProjeto($projetos_usuario->projeto_atual,'start', 'asc');
            foreach ($eventos as $evento) {
       
            $data_ini = substr("$evento->start", 0, 10);
            $data_ini_n = str_replace("-", ",", $data_ini);

            $partes_ini_n = explode("-", $data_ini);
            $dia_ini_n = $partes_ini_n[2];
            $mes_ini_n = $partes_ini_n[1]-1;
            $ano_ini_n = $partes_ini_n[0];
            $nova_data_ini = $ano_ini_n . ',' . $mes_ini_n . ',' . $dia_ini_n;

            $data_fim = substr("$evento->end", 0, 10);
            $data_fim_n = str_replace("-", ",", $data_fim);

            $partes_fim_n = explode("-", $data_fim);
            $dia_fim_n = $partes_fim_n[2];
            $mes_fim_n = $partes_fim_n[1] - 1;
            $ano_fim_n = $partes_fim_n[0];
            $nova_data_fim = $ano_fim_n . ',' . $mes_fim_n . ',' . $dia_fim_n;   
           ?>
                           
        {
            title: '<?php echo $evento->title; ?>',
            start: new Date(<?php echo $ano_ini_n; ?>, <?php echo $mes_ini_n; ?>, <?php echo $dia_ini_n; ?> ),
            end: new Date(<?php echo $ano_fim_n; ?>, <?php echo $mes_fim_n; ?>, <?php echo $dia_fim_n; ?>),
            className: 'fc-<?php echo $evento->color; ?>'
        }, 
        
        <?php
         }
        ?>
    ]
    });

    /* initialize the external events
     * {
            title: 'Long Event',
            start: new Date(y, m, d - 5),
            end: new Date(y, m, d - 2),
            className: 'fc-orange'
        }, {
            id: 999,
            title: 'Repeating Event',
            start: new Date(y, m, d - 3, 16, 0),
            allDay: false,
            className: 'fc-blue'
        }, {
            id: 999,
            title: 'Repeating Event',
            start: new Date(y, m, d + 4, 16, 0),
            allDay: false,
            className: 'fc-red'
        }, {
            title: 'Meeting',
            start: new Date(y, m, d, 10, 30),
            allDay: false,
            className: 'fc-purple'
        }, {
            title: 'Lunch',
            start: new Date(y, m, d, 12, 0),
            end: new Date(y, m, d, 14, 0),
            allDay: false,
            className: 'fc-default'
        }, {
            title: 'Birthday Party',
            start: new Date(y, m, d + 1, 19, 0),
            end: new Date(y, m, d + 1, 22, 30),
            allDay: false,
            className: 'fc-white'
        }
     * 
        -----------------------------------------------------------------*/

    $('#external-events div.external-event').each(function() {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
            title: $.trim($(this).text()) // use the element's text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true, // will cause the event to go back to its
            revertDuration: 0 //  original position after the drag
        });

    });


});

    </script>
</body>

</html>
