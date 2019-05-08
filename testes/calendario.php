<link rel="stylesheet" href="/Web2Cal/css/Web2Cal.css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.js"></script>
<script src="/Web2Cal/Web2Cal-3.0.min.js" type="text/javascript"></script>
<script src="/Web2Cal/Web2Cal.support.js" type="text/javascript"></script>
<script src="/Web2Cal/Web2Cal.templates.js" type="text/javascript"></script>

<div id="calendarContainer"></div>

<script> 
jQuery(document).ready(function(){ 
iCal = new Web2Cal( "calendarContainer",
       { 
            loadEvents: function(startDate, endDate, viewName){ 
                            /* Get events from any source. This can be a PHP/Java/.NET/Facebook or any other source. Once you have the data, invoke ical.render(data).*/
                           ical.render( eventSource.getEvents() ); 
                        } 
           ,onNewEvent: function(event, groups, allDay){ } 
           ,onUpdateEvent: function(event){ }
       });
       iCal.build(); 
}); 
</script> 