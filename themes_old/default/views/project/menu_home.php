


<link href="<?= $assets ?>theme2/css/style.css" rel="stylesheet">
<div class="row">
<!-- /navbar -->

<!-- /subnavbar -->
      <div class="col-md-12">
       <div class="col-md-6">
          <div class="widget widget-nopad">
            <div class="widget-header">
                <i class="icon-list-alt"></i>
              <h3>Ações</h3>
              
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <div class="widget big-stats-container">
                <div class="widget-content">
                  <div id="big_stats" class="cf">
                    <div class="stat"> <i title="Total de Ações do Projeto" class="icon-flag"></i> <span class="value">600</span> </div>
                    <!-- .stat -->
                    
                    <div class="stat"> <i title="Ações Concluídas" class="icon-thumbs-up-alt"></i> <span class="value">300</span> </div>
                    <!-- .stat -->
                    
                    <div class="stat"> <i title="Ações Atrasadas" class="icon-thumbs-down-alt"></i> <span class="value">100</span> </div>
                    <!-- .stat -->
                    
                    <div class="stat"> <i title="Aguardando Validação" class="icon-asterisk"></i> <span class="value">200</span> </div>
                    <!-- .stat --> 
                  </div>
                </div>
                <!-- /widget-content --> 
                
              </div>
            </div>
            
            <div class="widget">
            <div class="widget-header"> <i class="icon-signal"></i>
              <h3> Area Chart Example</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <canvas id="area-chart" class="chart-holder" height="250" width="538"> </canvas>
              <!-- /area-chart --> 
            </div>
            <!-- /widget-content --> 
          </div>
            
          </div>
          <!-- /widget -->
          
          <!-- /widget -->
          
          <!-- /widget --> 
        </div>
          
          
          <div class="col-md-6">
          <div class="widget">
            <div class="widget-header"> <i class="icon-bookmark"></i>
              <h3>Important Shortcuts</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <div class="shortcuts"> 
                  <a href="<?= site_url('project/dashboard'); ?>" class="shortcut"><i class="shortcut-icon icon-list-ol"></i><span class="shortcut-label">Dashboard</span> </a>
                  <a href="javascript:;" class="shortcut"><i class="shortcut-icon icon-align-left"></i><span class="shortcut-label">Gantt</span> </a>
                  <a href="javascript:;" class="shortcut"><i class="shortcut-icon icon-signal"></i> <span class="shortcut-label">Kamban</span> </a>
                  <a href="javascript:;" class="shortcut"> <i class="shortcut-icon icon-sitemap"></i><span class="shortcut-label">EAP</span> </a>
                  <a href="javascript:;" class="shortcut"><i class="shortcut-icon icon-user"></i><span class="shortcut-label">Equipes</span> </a>
                  <a href="javascript:;" class="shortcut"><i class="shortcut-icon icon-paper-clip"></i><span class="shortcut-label">Documentos</span> </a>
                  <a href="javascript:;" class="shortcut"><i class="shortcut-icon icon-file"></i><span class="shortcut-label">Documentos</span> </a>
                  <a href="javascript:;" class="shortcut"><i class="shortcut-icon icon-calendar"></i> <span class="shortcut-label">Calendário</span> </a>
                   
               </div>
              <!-- /shortcuts --> 
            </div>
            <!-- /widget-content --> 
          </div>
          <!-- /widget -->
          
          <!-- /widget -->
          
          <!-- /widget --> 
          
          <!-- /widget -->
        </div>
      </div>    
        <!-- /span6 -->
        
        <!-- /span6 --> 
      
      <!-- /row --> 
    <!-- /container --> 

<!-- /main -->
</div>
<!-- /footer --> 
<!-- Le javascript
================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="<?= $assets ?>theme2/js/excanvas.min.js"></script> 
<script src="<?= $assets ?>theme2/js/chart.min.js" type="text/javascript"></script> 
 <script>     

        var lineChartData = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [
				{
				    fillColor: "rgba(220,220,220,0.5)",
				    strokeColor: "rgba(220,220,220,1)",
				    pointColor: "rgba(220,220,220,1)",
				    pointStrokeColor: "#fff",
				    data: [65, 59, 90, 81, 56, 55, 40]
				},
				{
				    fillColor: "rgba(151,187,205,0.5)",
				    strokeColor: "rgba(151,187,205,1)",
				    pointColor: "rgba(151,187,205,1)",
				    pointStrokeColor: "#fff",
				    data: [28, 48, 40, 19, 96, 27, 100]
				}
			]

        }

        var myLine = new Chart(document.getElementById("area-chart").getContext("2d")).Line(lineChartData);


        var barChartData = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [
				{
				    fillColor: "rgba(220,220,220,0.5)",
				    strokeColor: "rgba(220,220,220,1)",
				    data: [65, 59, 90, 81, 56, 55, 40]
				},
				{
				    fillColor: "rgba(151,187,205,0.5)",
				    strokeColor: "rgba(151,187,205,1)",
				    data: [28, 48, 40, 19, 96, 27, 100]
				}
			]

        }    

        $(document).ready(function() {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var calendar = $('#calendar').fullCalendar({
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
          },
          selectable: true,
          selectHelper: true,
          select: function(start, end, allDay) {
            var title = prompt('Event Title:');
            if (title) {
              calendar.fullCalendar('renderEvent',
                {
                  title: title,
                  start: start,
                  end: end,
                  allDay: allDay
                },
                true // make the event "stick"
              );
            }
            calendar.fullCalendar('unselect');
          },
          editable: true,
          events: [
            {
              title: 'All Day Event',
              start: new Date(y, m, 1)
            },
            {
              title: 'Long Event',
              start: new Date(y, m, d+5),
              end: new Date(y, m, d+7)
            },
            {
              id: 999,
              title: 'Repeating Event',
              start: new Date(y, m, d-3, 16, 0),
              allDay: false
            },
            {
              id: 999,
              title: 'Repeating Event',
              start: new Date(y, m, d+4, 16, 0),
              allDay: false
            },
            {
              title: 'Meeting',
              start: new Date(y, m, d, 10, 30),
              allDay: false
            },
            {
              title: 'Lunch',
              start: new Date(y, m, d, 12, 0),
              end: new Date(y, m, d, 14, 0),
              allDay: false
            },
            {
              title: 'Birthday Party',
              start: new Date(y, m, d+1, 19, 0),
              end: new Date(y, m, d+1, 22, 30),
              allDay: false
            },
            {
              title: 'EGrappler.com',
              start: new Date(y, m, 28),
              end: new Date(y, m, 29),
              url: 'http://EGrappler.com/'
            }
          ]
        });
      });
    </script><!-- /Calendar -->