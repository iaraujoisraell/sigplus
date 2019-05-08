<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Samples_controller extends CI_Controller {

   /* -----------------------------------------------------
    * MAIL PLUGIN SAMPLES
    * -----------------------------------------------------*/
  public function index(){
  $this->load->library('email');
  $this->email->from('iaraujo.israel@gmail.com', 'Israel Araujo');
  $this->email->to('israel.araujo@unimedmanaus.coop.br');
  $this->email->subject('This is my subject');
  $this->email->message('This is my message');
  $this->email->send();
  echo 'aqui;';
}

   /* -----------------------------------------------------
    * Message without HTML template as inline HTML format
    * -----------------------------------------------------*/
    

}

/* End of file Samples_controller.php */
/* Location: ./application/controllers/Samples_controller.php */
