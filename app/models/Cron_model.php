<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function run_cron()
    {
        $m = '';
        if ($this->resetOrderRef()) {
            $m .= '<p>Reference number has been reset.</p>';
        }
        if ($pendingInvoices = $this->getAllPendingInvoices()) {
            $p = 0;
            foreach ($pendingInvoices as $invoice) {
                $this->updateInvoiceStatus($invoice->id);
                $p++;
            }
            $m .= '<p>' . $p . ' pending invoices status has been changed to due.</p>';
        }
        if ($partialInvoices = $this->getAllPPInvoices()) {
            $pp = 0;
            foreach ($partialInvoices as $invoice) {
                $this->updateInvoiceStatus($invoice->id);
                $pp++;
            }
            $m .= '<p>' . $pp . ' partially paid invoices status has been changed to due.</p>';
        }
        if ($unpaidpurchases = $this->getUnpaidPuchases()) {
            $up = 0;
            foreach ($unpaidpurchases as $purchase) {
                $this->db->update('purchases', array('payment_status' => 'due'), array('id' => $purchase->id));
                $up++;
            }
            $m .= '<p>' . $up . ' pending/partially paid purchases has been changed to due.</p>';
        }
        if ($pis = $this->get_expired_products()) {
            $e = 0; $ep = 0;
            foreach($pis as $pi) {
                $this->db->update('purchase_items', array('quantity_balance' => 0), array('id' => $pi->id));
                $e++;
                $ep += $pi->quantity_balance;
            }
            $this->site->syncQuantity(NULL, NULL, $pis);
            $m .= '<p>'.$e.' products with total quantity of '.$ep.' are expired.</p>';
        }
        if ($promos = $this->getPromoProducts()) {
            $pro = 0;
            foreach($promos as $pr) {
                $this->db->update('products', array('promotion' => 0), array('id' => $pr->id));
                $pro++;
            }
            $m .= '<p>'.$pro.' product promotions are expired.</p>';
        }
        $date = date('Y-m-d H:i:s', strtotime('-1 month'));
        if ($this->deleteUserLgoins($date)) {
            $m .= '<p>User login records previous to ' . $date . ' had been deleted.</p>';
        }
        if ($this->db_backup()) {
            $m .= '<p>Database backup successful and backups older then 30 days are deleted.</p>';
        }
        if ($this->checkUpdate()) {
            $m .= '<p>New update(s) available, please visit the updates menu under settings.</p>';
        }
        $r = $m != '' ? $m : false;
        return $r;
    }

    public function getAllPendingInvoices()
    {
        $today = date('Y-m-d');
        $paid = $this->lang->line('paid');
        $canceled = $this->lang->line('cancelled');
        $q = $this->db->get_where('sales', array('due_date <=' => $today, 'due_date !=' => '1970-01-01', 'due_date !=' => NULL, 'payment_status' => 'pending'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllPPInvoices()
    {
        $today = date('Y-m-d');
        $paid = $this->lang->line('paid');
        $canceled = $this->lang->line('cancelled');
        $q = $this->db->get_where('sales', array('due_date <=' => $today, 'due_date !=' => '1970-01-01', 'due_date !=' => NULL, 'payment_status' => 'partial'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function updateInvoiceStatus($id)
    {
        if ($this->db->update('sales', array('payment_status' => 'due'), array('id' => $id))) {
            return TRUE;
        }
        return FALSE;
    }

    public function resetOrderRef()
    {
        $settings = $this->getSettings();
        if ($settings->reference_format == 1 || $settings->reference_format == 2) {
            $month = date('Y-m') . '-01';
            $year = date('Y') . '-01-01';
            if ($ref = $this->getOrderRef()) {
                $reset_ref = array('date' => $month, 'so' => 1, 'qu' => 1, 'po' => 1, 'to' => 1, 'pos' => 1, 'do' => 1, 'pay' => 1, 're' => 1, 'rep' => 1, 'ex' => 1);
                if ($settings->reference_format == 1) {
                    if (strtotime($ref->date) < strtotime($year)) {
                        $this->db->update('order_ref', $reset_ref, array('ref_id' => 1));
                        return TRUE;
                    }
                } elseif ($settings->reference_format == 2) {
                    if (strtotime($ref->date) < strtotime($month)) {
                        $this->db->update('order_ref', $reset_ref, array('ref_id' => 1));
                        return TRUE;
                    }
                }
            }
        }
        return FALSE;
    }

    public function getOrderRef()
    {
        $q = $this->db->get_where('order_ref', array('ref_id' => 1), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSettings()
    {
        $q = $this->db->get_where('settings', array('setting_id' => 1), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function deleteUserLgoins($date)
    {
        $this->db->where('time <', $date);
        if ($this->db->delete('user_logins')) {
            return true;
        }
        return FALSE;
    }

    public function checkUpdate()
    {
        $settings = $this->getSettings();
        $fields = array('version' => $settings->version, 'code' => $settings->purchase_code, 'username' => $settings->envato_username, 'site' => base_url());
        $this->load->helper('update');
        $protocol = is_https() ? 'https://' : 'http://';
        $updates = get_remote_contents($protocol.'tecdiary.com/api/v1/update/', $fields);
        $response = json_decode($updates);
        if (!empty($response->data->updates)) {
            $this->db->update('settings', array('update' => 1), array('setting_id' => 1));
            return TRUE;
        }
        return FALSE;
    }

    public function get_expired_products() {
        $settings = $this->getSettings();
        if ($settings->remove_expired) {
            $date = date('Y-m-d');
            $this->db->where('expiry <=', $date)->where('expiry !=', NULL)->where('expiry !=', '0000-00-00')->where('quantity_balance >', 0);
            $q = $this->db->get('purchase_items');
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        return FALSE;
    }

    public function getUnpaidPuchases()
    {
        $today = date('Y-m-d');
        $q = $this->db->get_where('purchases', array('payment_status !=' => 'paid', 'payment_status !=' => 'due', 'payment_term >' => 0, 'due_date !=' => NULL, 'due_date <=' => $today));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getPromoProducts()
    {
        $today = date('Y-m-d');
        $q = $this->db->get_where('products', array('promotion' => 1, 'end_date !=' => NULL, 'end_date <=' => $today));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    private function db_backup() {
        $this->load->dbutil();
        $prefs = array(
            'format' => 'txt',
            'filename' => 'sma_db_backup.sql'
        );
        $back = $this->dbutil->backup($prefs);
        $backup =& $back;
        $db_name = 'db-backup-on-' . date("Y-m-d-H-i-s") . '.txt';
        $save = './files/backups/' . $db_name;
        $this->load->helper('file');
        write_file($save, $backup);

        $files = glob('./files/backups/*.txt', GLOB_BRACE);
        $now   = time();
        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= 60 * 60 * 24 * 30) {
                    unlink($file);
                }
            }
        }

        return TRUE;
    }

}
