


    <div class="row" style="padding-left: 3px; padding-right: 3px;">
        <div class="col-md-12 col-sm-12 col-lg-6">
            <div class="card card-calendar">
                <div class="card-body p-3">
                    <div class="calendar" data-bs-toggle="calendar" id="calendar"></div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-6 text-center bg-gray-100 border-radius-lg border-radius-sm border-radius-md" id="vaga_det" style="  margin: auto;">
             <p style="">Selecione uma vaga</p>
        </div>  
    </div>  

<script src="<?php echo base_url(); ?>assets/portal/js/plugins/fullcalendar.min.js"></script>


<script>
    // Inicialize o calendário com as configurações atualizadas
    var calendar = new FullCalendar.Calendar(document.getElementById("calendar"), {
        // Set the height of the calendar content to auto
        contentHeight: 'auto',

        // Set the initial view to "dayGridMonth" (month view)
        initialView: "dayGridMonth",

        // Customize the header toolbar
        headerToolbar: {
            start: 'title', // Show the calendar title on the left side
            center: '', // Leave the center part empty
            end: 'prev,next' // Show buttons for today, previous, and next
        },

        // Allow selecting dates in the calendar
        selectable: true,

        // Allow editing events
        editable: true,

        // Set the initial date of the calendar to December 1, 2020
        initialDate: '<?php echo date('Y-m-d', strtotime(str_replace('/', '-', $dias[0]->DT_AGENDA))); ?>',

        // Define events to be displayed on the calendar
        events: [
<?php foreach ($dias as $d) { ?>
                {
                    title: '<?php echo $d->QTDE; ?> vagas', // Event title]
                    start: '<?php echo date('Y-m-d', strtotime(str_replace('/', '-', $d->DT_AGENDA))); ?>', // Event start date
                    end: '<?php echo date('Y-m-d', strtotime(str_replace('/', '-', $d->DT_AGENDA))); ?>', // Event end date
                    groupId: '<?php echo $d->DT_AGENDA; ?>', // dia
                    className: 'bg-gradient-success  my-event' // CSS class to apply to the event element
                },
<?php } ?>
        ],

        // Customize the different views of the calendar
        views: {
            month: {
                titleFormat: {
                    month: "long", // Display the full month name
                    year: "numeric", // Display the year
                }
            },
            agendaWeek: {
                titleFormat: {
                    month: "long",
                    year: "numeric",
                    day: "numeric",
                }
            },
            agendaDay: {
                titleFormat: {
                    month: "short", // Display the abbreviated month name
                    year: "numeric",
                    day: "numeric",
                }
            }
        },

        // Set the locale to Portuguese (Brazil)
        locale: 'pt-br',

        // Event click handler
        eventClick: function (info) {

           
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('portal/Appointments/get_vaga_det'); ?>",
                data: {
                    cod_agenda: '<?php echo $cod_agenda; ?>',
                    date: info.event.groupId,
                    "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
                },
                success: function (data) {
              
                    $('#vaga_det').html(data);
                }
            });
            // Add the code you want to execute when an event is clicked
        }
    });

// Render the calendar
    calendar.render();




</script>

<script>


</script>
