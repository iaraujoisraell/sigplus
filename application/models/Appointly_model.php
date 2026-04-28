<?php defined('BASEPATH') or exit('No direct script access allowed');

class Appointly_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('appointly/appointly_attendees_model', 'atm');
    }


    /**
     * Insert new appointment
     *
     * @param [array] $data
     * @return boolean
     */
    public function insert_appointment($data)
    {
      
     
        $attendees = [];
        $relation = $data['rel_type'];
        $external_cid = null;
        
        $procedimentos = $data['procedimentos'];
        
        

        unset($data['rel_type']);

        if ($relation == 'lead_related') {
            $this->load->model('leads_model');

            $lead = $this->leads_model->get($data['rel_id']);

            $data['contact_id'] = $data['rel_id'];

            $data['name'] = $lead->name;

            if ($lead->phonenumber != '') {
                $data['phone'] = $lead->phonenumber;
            }

            if ($lead->address != '') {
                $data['address'] = $lead->address;
            }

            if ($lead->email != '') {
                $data['email'] = $lead->email;
            }

            $attendees = $data['attendees'];
            $data['source'] = 'lead_related';
            $data['created_by'] = get_staff_user_id();

            unset($data['rel_lead_type'], $data['rel_id'], $data['attendees']);
        } else {
            unset($data['rel_lead_type']);
        }

        if ($relation == 'internal') {
            $data['created_by'] = get_staff_user_id();
            $data['source'] = 'internal';
            $attendees = $data['attendees'];
            unset($data['attendees']);
        } else {
            if (isset($data['attendees']) && $relation != 'lead_related') {
                /**
                 * Means it is comming from inside crm form as External not internal (Contact)
                 */
                $data['created_by'] = get_staff_user_id();
                $external_cid = $data['contact_id'];
                $data['contact_id'] = NULL;
                /**
                 * We are setting source to external because it is relation is marked as an External Contact
                 */
                $data['source'] = 'external';
                $attendees = $data['attendees'];
                unset($data['attendees']);

                if (is_admin() || (staff_can('view_own', 'appointments') || staff_can('view', 'appointments'))) {
                    $data['approved'] = 1;
                }
            }
        }

        if (
            is_admin() && $relation == 'internal'
            || is_admin() && $relation == 'lead_related'
            || (staff_can('view_own', 'appointments')
                || staff_can('view', 'appointments')) && $relation == 'internal'
        ) {
            $data['approved'] = 1;
        }

        // Remove white spaces from phone number
        // In case is sent from external form as internal client when logged in 
        if (!empty($data['phone'])) {
            $data['phone'] = preg_replace('/\s+/', '', $data['phone']);
        }

        if ($data['source'] == 'internal' && empty($data['email'])) {
            $contact_data = get_appointment_contact_details($data['contact_id']);
            $data['email'] = $contact_data['email'];
            $data['name'] = $contact_data['full_name'];
            $data['phone'] = $contact_data['phone'];
        }
       
        if (appointlyGoogleAuth()) {
            if (isset($data['google'])) {
                $data['external_contact_id'] = $external_cid;

                $googleEvent = insertAppointmentToGoogleCalendar($data, $attendees);
                $data['google_event_id'] = $googleEvent['google_event_id'];
                $data['google_calendar_link'] = $googleEvent['htmlLink'];
                $data['google_added_by_id'] = get_staff_user_id();

                unset($data['google'], $data['external_contact_id']);
            }
        }
        
        $data = array_merge($data, $this->convertDateForDatabase($data['date']));

        $data = $this->handleDataReminderFields($data);

        $data['hash'] = app_generate_hash();

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }
        
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa_id'] = $empresa_id;
        
        
        
        $this->db->insert(db_prefix() . 'appointly_appointments', $data);
        $appointment_id = $this->db->insert_id();
        
           // salva os procedimentos na tabela nova
        
            $data_item['item_id'] = $procedimentos;
            $data_item['agenda_id'] = $appointment_id;
            $data_item['empresa_id'] = $empresa_id;
            $this->db->insert(db_prefix() . 'appointly_appointments_items', $data_item);
      

        if (isset($custom_fields)) {
            handle_custom_fields_post($appointment_id, $custom_fields);
        }

        $this->atm->create($appointment_id, $attendees);

        $this->appointment_approve_notification_and_sms_triggers($appointment_id);

        $responsiblePerson = get_option('appointly_responsible_person');

        if (!empty($responsiblePerson)) {
            add_notification([
                'description'     => 'appointment_new_appointment_submitted',
                'touserid'        => $responsiblePerson,
                'fromcompany'     => true,
                'link'            => 'appointly/appointments/view?appointment_id=' . $appointment_id,
            ]);
        }

        return $appointment_id;
    }
    
    public function insert_agenda($data)
    {
        $this->db->insert(db_prefix() . 'appointly_appointments', $data);
        $appointment_id = $this->db->insert_id();
      //  echo $appointment_id; exit;
        return true;
    }

    public function add_event_to_google_calendar($data)
    {
        $result = [
            'result' => 'error',
            'message' => _l(
                'Oops, something went wrong, please try again...'
            )
        ];


        if (appointlyGoogleAuth()) {

            if (isset($data['google_added_by_id']) && $data['google_added_by_id'] == null) {
                unset($data['rel_type']);

                $googleEvent = insertAppointmentToGoogleCalendar($data, isset($data['attendees']) ? $data['attendees'] : []);

                // It means that meeting is internal an created from CRM inside
                $data['google_event_id'] = $googleEvent['google_event_id'];
                $data['google_calendar_link'] = $googleEvent['htmlLink'];
                $data['google_added_by_id'] = get_staff_user_id();

                $data['id'] = $data['appointment_id'];

                $data = array_merge($data, $this->convertDateForDatabase($data['date']));

                if ($googleEvent) {
                    unset($data['selected_contact']);
                    unset($data['appointment_id']);
                    unset($data['attendees']);
                    $this->db->where('id', $data['id']);
                    $this->db->update(db_prefix() . 'appointly_appointments', $data);
                    if ($this->db->affected_rows() !== 0) {
                        return [
                            'result' => 'success',
                            'message' => _l(
                                'appointments_added_to_google_calendar'
                            )
                        ];
                    }
                }
                return $result;
            }
        }
        return $result;
    }

    
    public function get_agenda_medico_disponivel_fatura($medico_id)
    {
        $hoje = date('Y-m-d');
       
       $sql = "SELECT id, contact_id,  date, start_hour, finished, cancelled, approved, medico_id   FROM tblappointly_appointments
        where medico_id = $medico_id and date >= '$hoje' and contact_id is null order by date, start_hour";
      //echo $sql; exit;
       $result = $this->db->query($sql)->result_array();
       return $result;
      
    }
    /** 
     * Inserts appointment submitted from external clients form
     * @param array $data
     * @return boolean
     */
    public function insert_external_appointment($data)
    {
        $data['hash'] = app_generate_hash();

        if ($data['phone']) {
            $data['phone'] = preg_replace('/\s+/', '', $data['phone']);
        }


        $data = array_merge($data, $this->convertDateForDatabase($data['date']));

        $responsiblePerson = get_option('appointly_responsible_person');

        if (get_option('appointly_client_meeting_approved_default')) {
            $data['approved'] = 1;
        }

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        $this->db->insert(db_prefix() . 'appointly_appointments', $data);
        $appointment_id = $this->db->insert_id();

        if (isset($custom_fields)) {
            handle_custom_fields_post($appointment_id, $custom_fields);
        }

        if (get_option('appointly_client_meeting_approved_default')) {
            // If responsible person is set add as main attendee else first admin craeted with id 1
            $this->atm->create($appointment_id, [($responsiblePerson) ? $responsiblePerson : '1']);
        }


        if ($responsiblePerson !== '') {
            $notified_users = [];
            $notified_users[] = $responsiblePerson;

            $appointment = $this->apm->get_appointment_data($appointment_id);

            $staff = appointly_get_staff($responsiblePerson);

            send_mail_template('appointly_appointment_new_appointment_submitted', 'appointly', array_to_object($staff), array_to_object($appointment));

            add_notification([
                'description'     => 'appointment_new_appointment_submitted',
                'touserid'        => $responsiblePerson,
                'fromcompany'     => true,
                'link'            => 'appointly/appointments/view?appointment_id=' . $appointment_id,
            ]);

            pusher_trigger_notification($notified_users);
        }

        hooks()->do_action('send_sms_after_external_appointment_submitted');

        return true;
    }

    /**
     * Update existing appointment
     *
     * @param [array] $data
     * @return boolean
     */
    public function update_appointment_fatura($data)
    {
        
        $medicoid = $data['medicoid'];
        unset($data['medicoid']);
        $convenio = $data['convenio'];
        $centrocustoid = $data['centrocustoid'];
        unset($data['centrocustoid']);
        $agenda_id = $data['data_hora']; 
         unset($data['data_hora']);
         
        $data['consultorio_id'] = $centrocustoid;
        $data['medico_id'] = $medicoid;
        
        
        $fatura_id = $data['merge_current_invoice'];
        unset($data['merge_current_invoice']);  
        
        $adminnote = $data['adminnote'];
        unset($data['adminnote']);  
        
        
        unset($data['number']);  
        unset($data['project_id']);
        unset($data['color']);
        unset($data['total']);
        unset($data['discount_total']);
        unset($data['subtotal']);
        
        $contact_id = $data['clientid'];
        unset($data['clientid']); 
        
        $data['contact_id'] = $contact_id;
        
        $procedimentos = $data['items'];
        unset($data['items']);
        
       
        
        $proc_individual = "";
        $cont_proc = 1;
      
        foreach ($procedimentos as $proc) {
            $item_id = $proc['item_id'];
            if(count($procedimentos) == $cont_proc){
                $proc_individual .= $item_id;
            }else{
                $proc_individual .= $item_id.',';
            }
            
            $cont_proc++;
        }
      
        $data['notes'] = $adminnote;
        $data['procedimentos'] = $proc_individual;
        $data['approved'] = 1;
        $data['deleted'] = 0;
        $data['created_by'] = get_staff_user_id();
        $data['fatura_id'] = $fatura_id;
        
         
          
        if(!$data['hash']){
        $data['hash'] = app_generate_hash();
        }else{
        unset($data['hash']);    
        }
     
       
        // se for paciente novo, cria um novo usuário
       
        
       
        $this->db->where('id', $agenda_id);
        $this->db->update(db_prefix() . 'appointly_appointments', $data);
        
       
        
        /*
         * salva os procedimentos na tabela nova
         */
        $this->db->where('agenda_id', $agenda_id);
        $this->db->delete(db_prefix() . 'appointly_appointments_items');
        $empresa_id = $this->session->userdata('empresa_id');
        
        $items_array = explode(',', $proc_individual);
            foreach ($procedimentos as $proc) {
                $item_id = $proc['item_id'];
                $qty = $proc['qty'];
                
                $data_item['item_id'] = $item_id;
                $data_item['quantidade'] = $qty;
                $data_item['agenda_id'] = $agenda_id;
                $data_item['empresa_id'] = $empresa_id;
                $this->db->insert(db_prefix() . 'appointly_appointments_items', $data_item);
            
        }
        
        
        

        return true;
    }

    /**
     * Delete appointment
     *
     * @param [string] $appointment_id
     * @return boolean
     */
    public function delete_appointment($appointment_id)
    {

        $_appointment = $this->get_appointment_data($appointment_id);

        if ($_appointment['created_by'] !== get_staff_user_id() && !is_admin() && !is_staff_appointments_responsible()) {
            set_alert('danger', _l('appointments_no_delete_permissions'));

            if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect(admin_url('access_denied'));
            }
        }

        if (get_option('appointly_also_delete_in_google_calendar') == 1) {
            if ($_appointment['google_event_id'] && $_appointment['google_added_by_id'] == get_staff_user_id()) {
                $this->load->model('googlecalendar');
                if (appointlyGoogleAuth()) {
                    $this->googlecalendar->deleteEvent($_appointment['google_event_id']);
                }
            }
        }

        $this->atm->deleteAll($appointment_id);

        $this->db->where('id', $appointment_id);

        if (!is_admin() && !is_staff_appointments_responsible()) {
            $this->db->where('created_by', get_staff_user_id());
        }

        $this->db->delete(db_prefix() . 'appointly_appointments');

        if ($this->db->affected_rows() !== 0) {
            return true;
        }
        return false;
    }


    /**
     * Get todays appoinemtns
     *
     * @return array
     */
    public function fetch_todays_appointments()
    {
        $date = new DateTime();
        $today = $date->format('Y-m-d');

        $staff_has_permissions = !staff_can('view', 'appointments') || !staff_can('view_own', 'appointments');

        if ($staff_has_permissions) {
            $this->db->where('id IN (SELECT appointment_id FROM ' . db_prefix() . 'appointly_attendees WHERE staff_id=' . get_staff_user_id() . ')');
        }

        $this->db->where('date', $today);
        $this->db->where('approved', 1);

        return $this->db->get(db_prefix() . 'appointly_appointments')->result_array();
    }


    /**
     * Get all busy appointment dates
     *
     * @return json
     */
    public function getBusyTimes()
    {
        $time_format = get_option('time_format');
        $format = '';
        $time = '24';

        if ($time_format === '24') {
            $format = '"%H:%i"';
        } else {
            $time = '12';
            $format = '"%h:%i %p"';
        }

        $this->db->select('TIME_FORMAT(start_hour, ' . $format . ') as start_hour, date, source, created_by', FALSE);
        $this->db->from(db_prefix() . 'appointly_appointments');
        $this->db->where('approved', 1);

        $dates = $this->db->get()->result_array();

        if ($format == '"%h:%i %p"') {
            foreach ($dates as &$date) {
                $date['start_hour'] = substr($date['start_hour'], 1);
            }
        }


        if (appointlyGoogleAuth()) {

            $this->load->model('googlecalendar');

            $google_calendar_dates = $this->googlecalendar->getEvents();

            $convertedDates = [];

            if (count($google_calendar_dates) > 0) {
                foreach ($google_calendar_dates as &$gcdate) {

                    $gcdate['start'] = _dt($gcdate['start']);
                    $gcdate['start'] = explode(" ", $gcdate['start']);

                    if (!empty($gcdate['start'][0])) {
                        if (!in_array($gcdate['start'], $convertedDates)) {

                            if ($time_format == '24') {

                                array_push(
                                    $convertedDates,
                                    $this->convertDateForValidation($gcdate['start'][0] . ' ' . $gcdate['start'][1], $time)
                                );
                            } else {
                                array_push(
                                    $convertedDates,
                                    $this->convertDateForValidation($gcdate['start'][0] . ' ' . $gcdate['start'][1] . ' ' . $gcdate['start'][2], $time)
                                );
                            }
                        }
                    }
                }
                $dates = array_merge($dates, $convertedDates);
            }
        }

        echo json_encode($dates);
    }


    /**
     * Get all appointment data for calendar event
     *
     * @param [string] $start
     * @param [string] $end
     * @param [array] $data
     * @return array
     */
    public function getCalendarData($start, $end, $data)
    {
        $this->db->select('subject as title, date, hash, start_hour, id, type_id');
        $this->db->from('appointly_appointments');
        $this->db->where('finished = 0 AND cancelled = 0');


        if (!is_client_logged_in()) {
           $this->db->where('id IN (SELECT appointment_id FROM ' . db_prefix() . 'appointly_attendees WHERE staff_id=' . get_staff_user_id() . ')');
            
        } else {
            $this->db->where('id IN (SELECT appointment_id FROM ' . db_prefix() . 'appointly_attendees WHERE contact_id=' . get_contact_user_id() . ')');
        }

        $this->db->where('(CONCAT(date, " ", start_hour) BETWEEN "' . $start . '" AND "' . $end . '")');

        $appointments = $this->db->get()->result_array();

        foreach ($appointments as $key => $appointment) {

            $appointment['url'] = admin_url('appointly/appointments/view?appointment_id=' . $appointment['id']);

            if (is_client_logged_in()) {
                $appointment['url'] = admin_url('appointly/appointments_public/client_hash?hash=' . $appointment['hash']);
                $appointment['_tooltip'] = $appointment['title'];
            } else {
                $appointment['_tooltip'] = (get_appointment_type($appointment['type_id']))
                    ? _l('appointments_type_heading') . ": " . get_appointment_type($appointment['type_id'])
                    : $appointment['title'];
            }

            $appointment['date'] = $appointment['date'] . ' ' . $appointment['start_hour'] . ':00';
            $appointment['color'] = get_appointment_color_type($appointment['type_id']);
            $data[] = $appointment;
        }

        return $data;
    }

    /**
     * Fetch contact data and apply to fields in modal
     *
     * @param [string] $contact_id
     * @return function
     */
    function apply_contact_data($contact_id, $is_lead)
    {

        if ($is_lead == 'false') {
            return $this->clients_model->get_contact($contact_id);
        } else {
            $this->load->model('leads_model');
            return $this->leads_model->get($contact_id);
        }
    }

    /**
     * Get single appointment data
     *
     * @param [string] $appointment_id
     * @return array
     */
    function get_appointment_data($appointment_id)
    {
        $this->db->where('id', $appointment_id);
        $appointment = $this->db->get(db_prefix() . 'appointly_appointments')->row_array();

        if ($this->db->affected_rows() > 0) {
            $appointment['attendees'] = $this->atm->get($appointment_id);
            return $appointment;
        }
        return false;
    }
    
    /*
     * VERIFICA SE TEM HORÁRIO REGISTRADO PARA UM DETERMINADO MÉDICO, DATA E HORA
     */
    public function getQuantidadeAgendaMedico_data_hora($hora, $medico_id, $data_agenda, $consultorio_id)
    {
      
         $sql = "SELECT count(*) as quantidade
                FROM tblappointly_appointments
                where date = '$data_agenda' AND start_hour = '$hora' AND medico_id = '$medico_id' AND consultorio_id = $consultorio_id";
         
       $result = $this->db->query($sql)->row();
       $quantidade = $result->quantidade;
        
       return $quantidade;
        
    }
    
    
    


    /**
     * Cancel appointment
     *
     * @param [string] $appointment_id
     * @return boolean
     */
    function cancel_appointment($appointment_id)
    {
        $appointment = $this->get_appointment_data($appointment_id);

        $notified_users = [];

        $attendees = $appointment['attendees'];

        foreach ($attendees as $staff) {

            if ($staff['staffid'] === get_staff_user_id()) {
                continue;
            }

            add_notification([
                'description'     => 'appointment_is_cancelled',
                'touserid'        => $staff['staffid'],
                'fromcompany'     => true,
                'link'            => 'appointly/appointments/view?appointment_id=' . $appointment['id'],
            ]);

            $notified_users[] = $staff['staffid'];
            send_mail_template('appointly_appointment_notification_cancelled_to_staff', 'appointly', array_to_object($appointment), array_to_object($staff));
        }

        pusher_trigger_notification(array_unique($notified_users));

        $template = mail_template('appointly_appointment_notification_cancelled_to_contact', 'appointly', array_to_object($appointment));

        if (!empty($appointment['phone'])) {
            $merge_fields =  $template->get_merge_fields();
            $this->app_sms->trigger(APPOINTLY_SMS_APPOINTMENT_CANCELLED_TO_CLIENT, $appointment['phone'], $merge_fields);
        }

        $template->send();

        $this->db->where('id', $appointment_id);
        $this->db->update(db_prefix() . 'appointly_appointments', ['cancelled' => 1]);

        header('Content-Type: application/json');
        if ($this->db->affected_rows() !== 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => true]);
        }
    }

    /**
     * Approve appointment
     *
     * @param [string] $appointment_id
     * @return boolean
     */
    function approve_appointment($appointment_id)
    {
        $this->appointment_approve_notification_and_sms_triggers($appointment_id);

        $this->db->where('id', $appointment_id);
        $this->db->update(db_prefix() . 'appointly_appointments', ['finished' => 0, 'approved' => 1, 'external_notification_date' => date('Y-m-d')]);

        return true;
    }


    /**
     * Check for external client hash token
     *
     * @param [string] $hash
     * @return void
     */
    function getByHash($hash)
    {
        $this->db->where('hash', $hash);
        $appointment = $this->db->get('appointly_appointments')->row_array();
        if ($appointment) {
            $appointment['feedbacks'] = json_decode(get_option('appointly_default_feedbacks'));

            $appointment['selected_contact'] = $appointment['contact_id'];

            if (!empty($appointment['selected_contact'])) {
                $appointment['details'] = get_appointment_contact_details($appointment['selected_contact']);
            }
            $appointment['attendees'] = $this->atm->get($appointment['id']);
            return $appointment;
        }
        return false;
    }


    /** 
     * Marks appointment as finished
     * @param [string] $appointment_id
     * @return json
     */
    function mark_as_finished($id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'appointly_appointments', ['finished' => 1]);

        header('Content-Type: application/json');
        if ($this->db->affected_rows() !== 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }


    /** 
     * Marks appointment as ongoing
     * @param [string] $appointment_id
     * @return json
     */
    function mark_as_ongoing($appointment)
    {
        $this->appointment_approve_notification_and_sms_triggers($appointment['id']);

        $this->db->where('id', $appointment['id']);
        $this->db->update(db_prefix() . 'appointly_appointments', ['cancelled' => 0, 'finished' => 0, 'cancel_notes' => null]);

        header('Content-Type: application/json');
        if ($this->db->affected_rows() !== 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }


    /**
     * Send email and SMS notifications
     *
     * @param [string] $appointment_id
     * @return void
     */
    private function appointment_approve_notification_and_sms_triggers($appointment_id)
    {
        $appointment = $this->get_appointment_data($appointment_id);

        $notified_users = [];

        $attendees = $appointment['attendees'];

        if (count($attendees) == 0) {
            $this->atm->create($appointment_id, [get_staff_user_id()]);
            $attendees = $this->atm->get($appointment_id);
        }

        foreach ($attendees as $staff) {
            if ($staff['staffid'] === get_staff_user_id()) {
                continue;
            }

            add_notification([
                'description'     => 'appointment_is_approved',
                'touserid'        => $staff['staffid'],
                'fromcompany'     => true,
                'link'            => 'appointly/appointments/view?appointment_id=' . $appointment['id'],
            ]);


            $notified_users[] = $staff['staffid'];
            send_mail_template('appointly_appointment_approved_to_staff_attendees', 'appointly', array_to_object($appointment), array_to_object($staff));
        }

        pusher_trigger_notification(array_unique($notified_users));

        $template = mail_template('appointly_appointment_approved_to_contact', 'appointly', array_to_object($appointment));
        $merge_fields =  $template->get_merge_fields();

        if (!empty($appointment['phone'])) {
            $merge_fields =  $template->get_merge_fields();
            $this->app_sms->trigger(APPOINTLY_SMS_APPOINTMENT_APPROVED_TO_CLIENT, $appointment['phone'], $merge_fields);
        }

        $template->send();
    }

    /**
     * External appointment cancellation handler
     *
     * @param [string] $hash
     * @param [string] $notes
     * @return array
     */
    public function applyForAppointmentCancellation($hash, $notes)
    {
        $this->db->where('hash', $hash);
        $this->db->update(db_prefix() . 'appointly_appointments', ['cancel_notes' => $notes]);

        if ($this->db->affected_rows() !== 0) {
            return ['response' => [
                'message' => _l('appointments_thank_you_cancel_request'),
                'success' => true
            ]];
        }
    }


    /**
     * Check if cancellation is in progress already
     *
     * @param [appointment hash] $hash
     * @return array
     */
    public function checkIfCancellationIsInProgress($hash)
    {
        $this->db->select('cancel_notes');
        $this->db->where('hash', $hash);
        return $this->db->get(db_prefix() . 'appointly_appointments')->row_array();
    }


    /**
     * Convert dates for database insertion
     *
     * @param [string] $date
     * @return array
     */
    protected function convertDateForDatabase($date)
    {
        $date = to_sql_date($date, true);

        $toTime = strtotime($date);
        return [
            'date' => date('Y-m-d', $toTime),
            'start_hour' => date('H:i', $toTime),
        ];
    }

    /**
     * Convert dates for database insertion
     *
     * @param [string] $date
     * @return array
     */
    protected function convertDateForValidation($date, $time)
    {
        $date = to_sql_date($date, true);
        $convertor = 'H:i';

        if ($time == '12') {
            $convertor = 'g:i A';
        }

        $toTime = strtotime($date);
        return [
            'date' => date('Y-m-d', $toTime),
            'start_hour' => date($convertor, $toTime),
        ];
    }


    /**
     * Send appointment early reminders
     *
     * @param [string] $appointment_id
     * @return boolean
     */
    public function send_appointment_early_reminders($appointment_id)
    {
        $appointment = $this->get_appointment_data($appointment_id);

        foreach ($appointment['attendees'] as $staff) {
            add_notification([
                'description'       => 'appointment_you_have_new_appointment',
                'touserid'          => $staff['staffid'],
                'fromcompany'     => true,
                'link'               => 'appointly/appointments/view?appointment_id=' . $appointment_id,
            ]);

            $notified_users[] = $staff['staffid'];

            send_mail_template('appointly_appointment_cron_reminder_to_staff', 'appointly', array_to_object($appointment), array_to_object($staff));
        }

        $template = mail_template('appointly_appointment_cron_reminder_to_contact', 'appointly', array_to_object($appointment));

        $merge_fields =  $template->get_merge_fields();

        $template->send();

        if ($appointment['by_sms']) {
            $this->app_sms->trigger(APPOINTLY_SMS_APPOINTMENT_APPOINTMENT_REMINDER_TO_CLIENT, $appointment['phone'], $merge_fields);
        }

        return true;
    }


    /** 
     * Add new appointment type
     * @param [string] $type
     * @param [string] $color
     * @return boolean
     */
    public function new_appointment_type($type, $color)
    {
        return $this->db->insert(
            db_prefix() . 'appointly_appointment_types',
            [
                'type' => $type,
                'color' => $color
            ]
        );
    }


    /** 
     * Delete appointment type
     * @param [string] $id
     * @return json
     */
    public function delete_appointment_type($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'appointly_appointment_types');

        header('Content-Type: application/json');
        if ($this->db->affected_rows() !== 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }


    /** 
     * Update appointment types
     * @param [array] $data
     * @return void
     */
    public function update_appointment_types($data, $meta)
    {
        $data_color_types = [];

        foreach ($data as $key => $color) {
            if (strpos($key, 'type_id') === 0) {
                $key = substr($key, 8, 9);
                $data_color_types[$key]['id'] = $key;
                $data_color_types[$key]['color'] = $color;
            }
        }

        foreach ($data_color_types as $new_types) {
            $this->db->where('id', $new_types['id']);
            $this->db->update(db_prefix() . 'appointly_appointment_types', ['color' => $new_types['color']]);
        }
        hanleAppointlyUserMeta($meta);
    }

    /** 
     * Helper function to handle reminder fields
     * @param array $data
     * @return array
     */
    private function handleDataReminderFields($data)
    {

        (isset($data['by_email']) && $data['by_email'] == 'on')
            ? $data['by_email'] = '1'
            : $data['by_email'] = NULL;

        (isset($data['by_sms']) && $data['by_sms'] == 'on')
            ? $data['by_sms'] = '1'
            : $data['by_sms'] = NULL;

        if ($data['by_email'] === null && $data['by_sms'] === null) {
            $data['reminder_before'] = null;
            $data['reminder_before_type'] = null;
        }

        if (isset($data['by_email']) || isset($data['by_sms'])) {
            if ($data['reminder_before'] == '') {
                $data['reminder_before'] = '30';
            }
        }
        return $data;
    }

    public function request_appointment_feedback($appointment_id)
    {
        $appointment = $this->get_appointment_data($appointment_id);

        if (is_array($appointment) && !empty($appointment)) {
            send_mail_template('appointly_appointment_request_feedback', 'appointly',  array_to_object($appointment));
            echo json_encode(['success' => true]);
            return;
        } else {
            echo json_encode(['success' => false]);
        }
    }

    function handle_feedback_post($id, $feedback, $comment = null)
    {

        $data = ['feedback' => $feedback];

        $responsiblePerson = get_option('appointly_responsible_person');

        $notified_users = [];

        ($responsiblePerson !== '') ? $notified_users[] = $responsiblePerson : $notified_users[] = '1';

        ($responsiblePerson !== '') ? $staff = appointly_get_staff($responsiblePerson) : $staff = appointly_get_staff('1');

        $appointment = $this->apm->get_appointment_data($id);

        $tmp_name = 'appointly_appointment_feedback_received';
        $tmp_lang = 'appointment_new_feedback_added';

        if ($appointment['feedback'] !== NULL) {
            $tmp_name = 'appointly_appointment_feedback_updated';
            $tmp_lang = 'appointly_feedback_updated';
        }

        // send_mail_template($tmp_name, 'appointly', array_to_object($staff), array_to_object($appointment));

        add_notification([
            'description'     => $tmp_lang,
            'touserid'        => ($responsiblePerson) ? $responsiblePerson : 1,
            'fromcompany'     => true,
            'link'            => 'appointly/appointments/view?appointment_id=' . $id,
        ]);

        pusher_trigger_notification($notified_users);

        if ($comment !== null) {
            $data['feedback_comment'] = $comment;
        }

        $this->db->where('id', $id);

        $this->db->update(db_prefix() . 'appointly_appointments', $data);

        if ($this->db->affected_rows() !== 0) {
            return true;
        }
        return false;
    }
    
    
    
    
    // atualização de tabelas
    public function get_todos_agendamentos()
    {
         $sql = "SELECT * FROM tblappointly_appointments where contact_id > 0 "; // 
       //echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    
     public function get_verifica_procedimento($id = '')
    {
         $sql = "SELECT * FROM tblappointly_appointments_items where agenda_id = $id";
       //echo $sql; exit;
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    
    
    public function new_procedimento_atendimento($data_item)
    {
        $this->db->insert(db_prefix() . 'appointly_appointments_items', $data_item);
    }
    
}
