<?php defined('BASEPATH') or exit('No direct script access allowed');

/*
 *  ==============================================================================
 *  Author    : Mian Saleem
 *  Email    : saleem@tecdiary.com
 *  For        : Stock Manager Advance
 *  Web        : http://tecdiary.com
 *  ==============================================================================
 */

class Sma
{

    public function __construct()
    {

    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    private function _rglobRead($source, &$array = array())
    {
        if (!$source || trim($source) == "") {
            $source = ".";
        }
        foreach ((array) glob($source . "/*/") as $key => $value) {
            $this->_rglobRead(str_replace("//", "/", $value), $array);
        }
        $hidden_files = glob($source . ".*") and $htaccess = preg_grep('/\.htaccess$/', $hidden_files);
        $files = array_merge(glob($source . "*.*"), $htaccess);
        foreach ($files as $key => $value) {
            $array[] = str_replace("//", "/", $value);
        }
    }

    private function _zip($array, $part, $destination, $output_name = 'sma')
    {
        $zip = new ZipArchive;
        @mkdir($destination, 0777, true);

        if ($zip->open(str_replace("//", "/", "{$destination}/{$output_name}" . ($part ? '_p' . $part : '') . ".zip"), ZipArchive::CREATE)) {
            foreach ((array) $array as $key => $value) {
                $zip->addFile($value, str_replace(array("../", "./"), null, $value));
            }
            $zip->close();
        }
    }

    public function formatMoney($number)
    {
        if ($this->Settings->sac) {
            return ($this->Settings->display_symbol == 1 ? $this->Settings->symbol : '') .
            $this->formatSAC($this->formatDecimal($number)) .
            ($this->Settings->display_symbol == 2 ? $this->Settings->symbol : '');
        }
        $decimals = $this->Settings->decimals;
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return ($this->Settings->display_symbol == 1 ? $this->Settings->symbol : '') .
        number_format($number, $decimals, $ds, $ts) .
        ($this->Settings->display_symbol == 2 ? $this->Settings->symbol : '');
    }

    public function formatQuantity($number, $decimals = null)
    {
        if (!$decimals) {
            $decimals = $this->Settings->qty_decimals;
        }
        if ($this->Settings->sac) {
            return $this->formatSAC($this->formatDecimal($number, $decimals));
        }
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return number_format($number, $decimals, $ds, $ts);
    }

    public function formatNumber($number, $decimals = null)
    {
        if (!$decimals) {
            $decimals = $this->Settings->decimals;
        }
        if ($this->Settings->sac) {
            return $this->formatSAC($this->formatDecimal($number, $decimals));
        }
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return number_format($number, $decimals, $ds, $ts);
    }

    public function formatDecimal($number, $decimals = null)
    {
        if (!is_numeric($number)) {
            return null;
        }
        if (!$decimals) {
            $decimals = $this->Settings->decimals;
        }
        return number_format($number, $decimals, '.', '');
    }

    public function clear_tags($str)
    {
        return htmlentities(
            strip_tags($str,
                '<span><div><a><br><p><b><i><u><img><blockquote><small><ul><ol><li><hr><big><pre><code><strong><em><table><tr><td><th><tbody><thead><tfoot><h3><h4><h5><h6>'
            ),
            ENT_QUOTES | ENT_XHTML | ENT_HTML5,
            'UTF-8'
        );
    }

    public function decode_html($str)
    {
        return html_entity_decode($str, ENT_QUOTES | ENT_XHTML | ENT_HTML5, 'UTF-8');
    }

    public function roundMoney($num, $nearest = 0.05)
    {
        return round($num * (1 / $nearest)) * $nearest;
    }

    public function roundNumber($number, $toref = null)
    {
        switch ($toref) {
            case 1:
                $rn = round($number * 20) / 20;
                break;
            case 2:
                $rn = round($number * 2) / 2;
                break;
            case 3:
                $rn = round($number);
                break;
            case 4:
                $rn = ceil($number);
                break;
            default:
                $rn = $number;
        }
        return $rn;
    }

    public function unset_data($ud)
    {
        if ($this->session->userdata($ud)) {
            $this->session->unset_userdata($ud);
            return true;
        }
        return false;
    }

    public function hrsd($sdate)
    {
        if ($sdate) {
            return date($this->dateFormats['php_sdate'], strtotime($sdate));
        } else {
            return '0000-00-00';
        }
    }

    public function hrld($ldate)
    {
        if ($ldate) {
            return date($this->dateFormats['php_ldate'], strtotime($ldate));
        } else {
            return '0000-00-00 00:00:00';
        }
    }

    public function fsd($inv_date)
    {
        if ($inv_date) {
            $jsd = $this->dateFormats['js_sdate'];
            if ($jsd == 'dd-mm-yyyy' || $jsd == 'dd/mm/yyyy' || $jsd == 'dd.mm.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 3, 2) . "-" . substr($inv_date, 0, 2);
            } elseif ($jsd == 'mm-dd-yyyy' || $jsd == 'mm/dd/yyyy' || $jsd == 'mm.dd.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 0, 2) . "-" . substr($inv_date, 3, 2);
            } else {
                $date = $inv_date;
            }
            return $date;
        } else {
            return '0000-00-00';
        }
    }

    public function fld($ldate)
    {
        if ($ldate) {
            $date = explode(' ', $ldate);
            $jsd = $this->dateFormats['js_sdate'];
            $inv_date = $date[0];
            $time = $date[1];
            if ($jsd == 'dd-mm-yyyy' || $jsd == 'dd/mm/yyyy' || $jsd == 'dd.mm.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 3, 2) . "-" . substr($inv_date, 0, 2) . " " . $time;
            } elseif ($jsd == 'mm-dd-yyyy' || $jsd == 'mm/dd/yyyy' || $jsd == 'mm.dd.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 0, 2) . "-" . substr($inv_date, 3, 2) . " " . $time;
            } else {
                $date = $inv_date;
            }
            return $date;
        } else {
            return '0000-00-00 00:00:00';
        }
    }

    public function send_email($to, $subject, $message, $from , $from_name = null, $attachment = null, $cc = null, $bcc = null, $headers= null)
    {
        
        $this->email->from("$from", "$from_name");
        $this->email->subject("$subject");
        //$this->email->reply_to("email_de_resposta@dominio.com");
        $this->email->to("$to"); 
        $this->email->cc("$cc");
        $this->email->bcc("$bcc");
        $this->email->message("$message");
        $enviado = $this->email->send(); 
        


   
        // Exibe uma mensagem de resultado
        if ($enviado) {
          echo "E-mail enviado com sucesso!";
          echo "<script>alert('E-mail enviado com sucesso.')</script>";
          echo "<script>history.go(-1)</script>"; exit;
          return true;
        } else {
          echo "<script>alert('Não foi possível enviar o e-mail. $mail->ErrorInfo;')</script>";
          echo "<script>history.go(-1)</script>"; exit;
          echo "Não foi possível enviar o e-mail.";
          echo "<b>Informações do erro:</b> " . $mail->ErrorInfo;
          return false;
        }

    }
    
    public function send_email_credencial($to, $subject, $message, $from , $from_name = null, $attachment = null, $cc = null, $bcc = null, $headers= null)
    {
        
        $this->email->from("$from", "$from_name");
        $this->email->subject("$subject");
        //$this->email->reply_to("email_de_resposta@dominio.com");
        $this->email->to("$to"); 
        $this->email->cc("$cc");
        $this->email->bcc("$bcc");
        $this->email->message("$message");
        $enviado = $this->email->send(); 
        


   
        // Exibe uma mensagem de resultado
        /*
        if ($enviado) {
          echo "E-mail enviado com sucesso!";
          echo "<script>alert('E-mail enviado com sucesso.'); window.close();</script>";
           echo "<script>window.close();</script>";
          echo "<script>history.go(-1)</script>"; 
          return true;
        } else {
          echo "<script>alert('Não foi possível enviar o e-mail. $mail->ErrorInfo;')</script>";
          echo "<script>history.go(-1)</script>"; 
          echo "Não foi possível enviar o e-mail.";
          echo "<b>Informações do erro:</b> " . $mail->ErrorInfo;
          return false;
        }
         * 
         */

    }

    public function checkPermissions($action = null, $js = null, $module = null)
    {
        if (!$this->actionPermissions($action, $module)) {
            $this->session->set_flashdata('error', lang("access_denied"));
            if ($js) {
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
            } else {
                redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
            }
        }
    }

    public function actionPermissions($action = null, $module = null)
    {
       
         $usuario = $this->session->userdata('user_id');
         $empresa = $this->session->userdata('empresa');
         
         $dados = $this->site->getUser($usuario);
         $id_usuario = $dados->id;
         $projeto_atual = $dados->projeto_atual;
         $empresa_usuario = $dados->empresa_id;
         $modulo_atual = $dados->modulo_atual;
         $permissao_project = $dados->project;
         
        
         $cont_log = 0;
            
        if($usuario != $id_usuario){
             return false;
             //bloqueia usuário
             // gerar 
            }else       
            if($empresa != $empresa_usuario){
                 return false;
                 // bloquear usuário
                 // gerar alerta
            }
         
        // PROJECT
        if($modulo_atual == 4){
       //  echo $permissao_project;exit;
          if($permissao_project == 1){
           // validações do módulo project
              
              //1 - verifica se o projeto que esta acessando é da empresa dele.
              $projeto_cadastro = $this->projetos_model->getProjetoByID($projeto_atual);
              $empresa_cadastro_projeto = $projeto_cadastro->empresa_id;
             
              if($empresa_cadastro_projeto == $empresa){
                  return true;
              }else{
                  // email de alerta
                  return true;
              }
              
              return true;
              
          }else{
              return false;
          }
            
        }
         
         
        if($cont_log == 0){
           return true;
            //$gp = $this->site->checkPermissions();
           
        } 
         
         
       
        
    }

    public function save_barcode($text = null, $bcs = 'code128', $height = 56, $stext = 1, $sq = null)
    {
        $file_name = 'assets/uploads/barcode' . $this->session->userdata('user_id') . ($sq ? $sq : '') . '.png';
        $drawText = ($stext != 1) ? false : true;
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcodeOptions = array('text' => $text, 'barHeight' => $height, 'drawText' => $drawText, 'factor' => 1);
        $rendererOptions = array('imageType' => 'png', 'horizontalPosition' => 'center', 'verticalPosition' => 'middle');
        $image = Zend_Barcode::draw($bcs, 'image', $barcodeOptions, $rendererOptions);
        if (imagepng($image, $file_name)) {
            imagedestroy($image);
            $bc = file_get_contents($file_name);
            $bcimage = base64_encode($bc);
            return $bcimage;
        }
        return false;
    }

    public function qrcode($type = 'text', $text = 'PHP QR Code', $size = 2, $level = 'H', $sq = null)
    {
        $file_name = 'assets/uploads/qrcode' . $this->session->userdata('user_id') . ($sq ? $sq : '') . '.png';
        if ($type == 'link') {
            $text = urldecode($text);
        }
        $this->load->library('phpqrcode');
        $config = array('data' => $text, 'size' => $size, 'level' => $level, 'savename' => $file_name);
        $this->phpqrcode->generate($config);
        $qr = file_get_contents($file_name);
        $qrimage = base64_encode($qr);
        return $qrimage;
    }

    public function generate_pdf_ata($content, $name , $output_type = null, $footer = null, $margin_bottom = null, $header = null, $margin_top = null, $orientation = 'P', $logo_top = null, $logo_bottom = null, $usuario_emitiu, $documentacao)
    {
        if (!$output_type) {
            $output_type = 'D';
        }
        if (!$margin_bottom) {
            $margin_bottom = 20;
        }
        if (!$margin_top) {
            $margin_top = 30;
        }
        
        $this->load->library('pdf');
        $pdf = new mPDF('utf-8', 'A4-' . $orientation, '13', '', 10, 10, $margin_top, $margin_bottom, 4, 9);
        $pdf->debug = false;
        $pdf->autoScriptToLang = true;
        $pdf->autoLangToFont = true;
        $pdf->SetProtection(array('print')); // You pass 2nd arg for user password (open) and 3rd for owner password (edit)
        //$pdf->SetProtection(array('print', 'copy')); // Comment above line and uncomment this to allow copying of content
        $pdf->SetTitle($this->Settings->site_name);
        $pdf->SetAuthor($this->Settings->site_name);
        $pdf->SetCreator($this->Settings->site_name);
        $pdf->SetDisplayMode('fullpage');
        $stylesheet = file_get_contents('assets/bs/bootstrap.min.css');
        $pdf->WriteHTML($stylesheet, 1);
        $date_cadastro = date('Y-m-d');
        //date_default_timezone_set('America/Manaus');
       
          date_default_timezone_set('America/Manaus');
          // CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
            //$dataLocal = date('d/m/Y H:i:s', time());
        
        $hotas = date('H:i:s', time());
        
        $pdf->SetFooter($this->Settings->site_name.'<br> Emitido por: '.$usuario_emitiu.' <br> Em : '. date("d/m/Y", strtotime($date_cadastro)).' '.$hotas. '||{PAGENO}/{nbpg}', '', TRUE); // For simple text footer
       
    
        
        if($logo_top){
             $imagem_header = '<img  width="100%" height="70px; " src="'. base_url() . 'assets/uploads/logos/'.$logo_top.'">';  
        }else{
             $imagem_header = '<img  width="100%" height="70px; " src="'. base_url() . 'assets/uploads/logos/cabecalho_ata_sig.png">';  
        }
         
         
              
             
         $conteudo_header = '<table style = "width : 100%;" >'
                          . '<tr>'
                                . '<td style = "width : 100%; ">'.$imagem_header. '</td>'
                            . '</tr>'
                          . '</table>';
        
         
         $header = $conteudo_header;
        
        
        
         if($logo_bottom){
         $footer = ' <img width="80%" height="38px;" src="'. base_url() . 'assets/uploads/logos/'.$logo_bottom.'" >';
         }
         
        if (is_array($content)) {
            $pdf->SetHeader('Relatório'.'||{PAGENO}/{nbpg}', '', TRUE); // For simple text header
            $as = sizeof($content);
            $r = 1;
            foreach ($content as $page) {
                
                $pdf->WriteHTML($page['content']);
                if (!empty($page['footer'])) {
                    $pdf->SetHTMLFooter('<p class="text-center">' . $page['footer'] . '</p>', '', true);
                }
                if ($as != $r) {
                    $pdf->AddPage();
                }
                $r++;
            }

        } else {
            $pdf->SetHTMLHeader('<p class="text-center">' . $header . '</p>  ', '', true);
           
            $pdf->WriteHTML($content);
          //  $pdf->SetHTMLFooter('<p class="text-center">' . $page['footer'] . '</p>', '', true);
            
            if ($header != '') {
                $pdf->SetHTMLHeader('<p class="text-center">' . $header . '</p>', '', true);
            }
            
            if ($logo_bottom) {
                $pdf->SetHTMLFooter('<p class="text-center">' . $footer . '</p>', '', true);
            }

        }

        if ($output_type == 'S') {
            $file_content = $pdf->Output('', 'S');
            write_file('assets/uploads/' . $name, $file_content);
            return 'assets/uploads/' . $name;
        } else {
            $pdf->Output($name, $output_type);
        }
    }
    
    public function generate_pdf_plano_acao($content, $name , $output_type = null, $footer = null, $margin_bottom = null, $header = null, $margin_top = null, $orientation = 'P', $logo_top = null, $logo_bottom = null, $usuario_emitiu, $documentacao)
    {
        if (!$output_type) {
            $output_type = 'D';
        }
        if (!$margin_bottom) {
            $margin_bottom = 20;
        }
        if (!$margin_top) {
            $margin_top = 30;
        }
        
        $this->load->library('pdf');
        $pdf = new mPDF('utf-8', 'A4-' . $orientation, '13', '', 10, 10, $margin_top, $margin_bottom, 4, 9);
        $pdf->debug = false;
        $pdf->autoScriptToLang = true;
        $pdf->autoLangToFont = true;
        $pdf->SetProtection(array('print')); // You pass 2nd arg for user password (open) and 3rd for owner password (edit)
        //$pdf->SetProtection(array('print', 'copy')); // Comment above line and uncomment this to allow copying of content
        $pdf->SetTitle($this->Settings->site_name);
        $pdf->SetAuthor($this->Settings->site_name);
        $pdf->SetCreator($this->Settings->site_name);
        $pdf->SetDisplayMode('fullpage');
        $stylesheet = file_get_contents('assets/bs/bootstrap.min.css');
        $pdf->WriteHTML($stylesheet, 1);
        $date_cadastro = date('Y-m-d');
        //date_default_timezone_set('America/Manaus');
       
          date_default_timezone_set('America/Manaus');
          // CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
            //$dataLocal = date('d/m/Y H:i:s', time());
        
        $hotas = date('H:i:s', time());
        
        $pdf->SetFooter($this->Settings->site_name.'<br> Emitido por: '.$usuario_emitiu.' <br> Em : '. date("d/m/Y", strtotime($date_cadastro)).' '.$hotas. '||{PAGENO}/{nbpg}', '', TRUE); // For simple text footer
       
    
        
        if($logo_top){
             $imagem_header = '<img  width="100%" height="70px; " src="'. base_url() . 'assets/uploads/logos/'.$logo_top.'">';  
        }else{
             $imagem_header = '<img  width="70px" height="70px;" src="'. base_url() . 'assets/uploads/logos/logo_sig.jpeg">';  
        }
         
         
              
             
         $conteudo_header = '<table style = "width : 100%;" >'
                              . '<tr>'
                                    . '<td style = "width : 80%;  "><img  width="100%" height="70px; " src="'. base_url() . 'assets/uploads/logos/cabecalho_plano_acao_sig.png"></td>'
                                    . '<td style = "width : 20%;  position: relative; float: right; ">'.$imagem_header. '</td>'
                                . '</tr>'
                              . '</table>';
        
         
         $header = $conteudo_header;
        
        
        
         if($logo_bottom){
         $footer = ' <img width="80%" height="38px;" src="'. base_url() . 'assets/uploads/logos/'.$logo_bottom.'" >';
         }
         
        if (is_array($content)) {
            $pdf->SetHeader('Relatório'.'||{PAGENO}/{nbpg}', '', TRUE); // For simple text header
            $as = sizeof($content);
            $r = 1;
            foreach ($content as $page) {
                
                $pdf->WriteHTML($page['content']);
                if (!empty($page['footer'])) {
                    $pdf->SetHTMLFooter('<p class="text-center">' . $page['footer'] . '</p>', '', true);
                }
                if ($as != $r) {
                    $pdf->AddPage();
                }
                $r++;
            }

        } else {
            $pdf->SetHTMLHeader('<p class="text-center">' . $header . '</p>  ', '', true);
           
            $pdf->WriteHTML($content);
          //  $pdf->SetHTMLFooter('<p class="text-center">' . $page['footer'] . '</p>', '', true);
            
            if ($header != '') {
                $pdf->SetHTMLHeader('<p class="text-center">' . $header . '</p>', '', true);
            }
            
            if ($logo_bottom) {
                $pdf->SetHTMLFooter('<p class="text-center">' . $footer . '</p>', '', true);
            }

        }

        if ($output_type == 'S') {
            $file_content = $pdf->Output('', 'S');
            write_file('assets/uploads/' . $name, $file_content);
            return 'assets/uploads/' . $name;
        } else {
            $pdf->Output($name, $output_type);
        }
    }
    
    public function generate_pdf($content, $name , $output_type = null, $footer = null, $margin_bottom = null, $header = null, $margin_top = null, $orientation = 'P', $logo_top = null, $logo_bottom = null, $usuario_emitiu, $documentacao)
    {
        if (!$output_type) {
            $output_type = 'D';
        }
        if (!$margin_bottom) {
            $margin_bottom = 20;
        }
        if (!$margin_top) {
            $margin_top = 30;
        }
        
        $this->load->library('pdf');
        $pdf = new mPDF('utf-8', 'A4-' . $orientation, '13', '', 10, 10, $margin_top, $margin_bottom, 4, 9);
        $pdf->debug = false;
        $pdf->autoScriptToLang = true;
        $pdf->autoLangToFont = true;
        $pdf->SetProtection(array('print')); // You pass 2nd arg for user password (open) and 3rd for owner password (edit)
        //$pdf->SetProtection(array('print', 'copy')); // Comment above line and uncomment this to allow copying of content
        $pdf->SetTitle($this->Settings->site_name);
        $pdf->SetAuthor($this->Settings->site_name);
        $pdf->SetCreator($this->Settings->site_name);
        $pdf->SetDisplayMode('fullpage');
        $stylesheet = file_get_contents('assets/bs/bootstrap.min.css');
        $pdf->WriteHTML($stylesheet, 1);
        $date_cadastro = date('Y-m-d');
        //date_default_timezone_set('America/Manaus');
       
          date_default_timezone_set('America/Manaus');
          // CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
            //$dataLocal = date('d/m/Y H:i:s', time());
        
        $hotas = date('H:i:s', time());
        
        $pdf->SetFooter($this->Settings->site_name.'<br> Emitido por: '.$usuario_emitiu.' <br> Em : '. date("d/m/Y", strtotime($date_cadastro)).' '.$hotas. '||{PAGENO}/{nbpg}', '', TRUE); // For simple text footer
       
    
        
        if($logo_top){
         
         
         if(($documentacao->status == "FINALIZADO")||$documentacao->status == "EM APROVAÇÃO"){
             $imagem_header = '<img  width="550px" height="70px; " src="'. base_url() . 'assets/uploads/logos/'.$logo_top.'">';   
             
         $conteudo_header = '<table style = "width : 100%;" class="table table-striped table-bordered table-hover table-green" >'
                          . '<tr>'
                                . '<td style = "width : 80%; ">'.$imagem_header. '</td>'
                                . '<td style = "width : 20%;  "> '
                                    . '<table  style = "width : 100%; " id="sample_table"  class="table table-striped table-bordered table-hover table-green">'
                                        . '<tr><td style = "width : 30%;"> <font style = "font-size: 10px; text-align: right;"> Versão  </font></td>        <td style = "width : 70%;"><font style = "font-size: 10px; "> '.$documentacao->versao.'</font></td></tr>'
                                        . '<tr><td style = "width : 30%;"> <font style = "font-size: 10px; text-align: right;"> Elaborado por  </font></td> <td style = "width : 70%;"><font style = "font-size: 10px; "> '.$documentacao->quem_elaborou.'</font></td></tr>'
                                        . '<tr><td style = "width : 30%;"> <font style = "font-size: 10px; text-align: right;">Verificação  por  </font></td>  <td style = "width : 70%;"><font style = "font-size: 10px; "> '.$documentacao->revisado_por.'</font></td></tr>'
                                        . '<tr><td style = "width : 30%;"> <font style = "font-size: 10px; text-align: right;">Aprovado  por   </font></td> <td style = "width : 70%;"><font style = "font-size: 10px; "> '.$documentacao->quem_assinou.'</font></td></tr>'    
                                        . '<tr><td style = "width : 30%;"> <font style = "font-size: 10px; text-align: right;">Concluído em   </font></td>  <td style = "width : 70%;"><font style = "font-size: 10px; "> '.date("d/m/Y", strtotime($documentacao->data_finalizacao)).'</font></td></tr>'
                                         . '<tr><td style = "width : 30%;"> <font style = "font-size: 10px; text-align: right;">Registro :  </font></td>  <td style = "width : 70%;"> <font style = "font-size: 10px; ">  EDP - 0'.$documentacao->id.' </font> </td></tr>'    
                                  
                 .'</table></td>'
                            . '</tr>'
                          . '</table>';
         }else if($documentacao->status == "RASCUNHO"){
             $imagem_header = '<img  width="400px" height="70px; " src="'. base_url() . 'assets/uploads/logos/'.$logo_top.'">';   
             $conteudo_header = '<table style = "width : 100%;" id="sample_table"  class="table table-striped table-bordered table-hover table-green">'
                          . '<tr>'
                                . '<td style = "width : 75%;">'.$imagem_header. '</td>'
                                . '<td style = "width : 25%;"> <font style = "font-size: 18px; text-align: right;"> RASCUNHO </FONT> </td>'
                            . '</tr>'
                          . '</table>';
         }else{
             $imagem_header = '<img  width="100%" height="70px; " src="'. base_url() . 'assets/uploads/logos/'.$logo_top.'">';   
              $conteudo_header = '<table style = "width : 100%;">'
                          . '<tr>'
                                . '<td style = "width : 100%;">'.$imagem_header. '</td>'
                               
                            . '</tr>'
                          . '</table>';
         }
         $header = $conteudo_header;
        }
        
        
         if($logo_bottom){
         $footer = ' <img width="80%" height="38px;" src="'. base_url() . 'assets/uploads/logos/'.$logo_bottom.'" >';
         }
         
        if (is_array($content)) {
            $pdf->SetHeader('Relatório'.'||{PAGENO}/{nbpg}', '', TRUE); // For simple text header
            $as = sizeof($content);
            $r = 1;
            foreach ($content as $page) {
                
                $pdf->WriteHTML($page['content']);
                if (!empty($page['footer'])) {
                    $pdf->SetHTMLFooter('<p class="text-center">' . $page['footer'] . '</p>', '', true);
                }
                if ($as != $r) {
                    $pdf->AddPage();
                }
                $r++;
            }

        } else {
            $pdf->SetHTMLHeader('<p class="text-center">' . $header . '</p>  ', '', true);
           
            $pdf->WriteHTML($content);
          //  $pdf->SetHTMLFooter('<p class="text-center">' . $page['footer'] . '</p>', '', true);
            
            if ($header != '') {
                $pdf->SetHTMLHeader('<p class="text-center">' . $header . '</p>', '', true);
            }
            
            if ($logo_bottom) {
                $pdf->SetHTMLFooter('<p class="text-center">' . $footer . '</p>', '', true);
            }

        }

        if ($output_type == 'S') {
            $file_content = $pdf->Output('', 'S');
            write_file('assets/uploads/' . $name, $file_content);
            return 'assets/uploads/' . $name;
        } else {
            $pdf->Output($name, $output_type);
        }
    }

    public function print_arrays()
    {
        $args = func_get_args();
        echo "<pre>";
        foreach ($args as $arg) {
            print_r($arg);
        }
        echo "</pre>";
        die();
    }

    public function logged_in()
    {
        return (bool) $this->session->userdata('identity');
    }

    public function in_group($check_group, $id = false)
    {
        if ( ! $this->logged_in()) {
            return false;
        }
        $id || $id = $this->session->userdata('user_id');
        $group = $this->site->getUserGroup($id);
        if ($group->name === $check_group) {
            return true;
        }
        return false;
    }

    public function log_payment($msg, $val = null)
    {
        $this->load->library('logs');
        return (bool) $this->logs->write('payments', $msg, $val);
    }

    public function update_award_points($total, $customer, $user, $scope = null)
    {
        if (!empty($this->Settings->each_spent) && $total >= $this->Settings->each_spent) {
            $company = $this->site->getCompanyByID($customer);
            $points = floor(($total / $this->Settings->each_spent) * $this->Settings->ca_point);
            $total_points = $scope ? $company->award_points - $points : $company->award_points + $points;
            $this->db->update('companies', array('award_points' => $total_points), array('id' => $customer));
        }
        if (!empty($this->Settings->each_sale) && !$this->Customer && $total >= $this->Settings->each_sale) {
            $staff = $this->site->getUser($user);
            $points = floor(($total / $this->Settings->each_sale) * $this->Settings->sa_point);
            $total_points = $scope ? $staff->award_points - $points : $staff->award_points + $points;
            $this->db->update('users', array('award_points' => $total_points), array('id' => $user));
        }
        return true;
    }

    public function zip($source = null, $destination = "./", $output_name = 'sma', $limit = 5000)
    {
        if (!$destination || trim($destination) == "") {
            $destination = "./";
        }

        $this->_rglobRead($source, $input);
        $maxinput = count($input);
        $splitinto = (($maxinput / $limit) > round($maxinput / $limit, 0)) ? round($maxinput / $limit, 0) + 1 : round($maxinput / $limit, 0);

        for ($i = 0; $i < $splitinto; $i++) {
            $this->_zip(array_slice($input, ($i * $limit), $limit, true), $i, $destination, $output_name);
        }

        unset($input);
        return;
    }

    public function unzip($source, $destination = './')
    {

        // @chmod($destination, 0777);
        $zip = new ZipArchive;
        if ($zip->open(str_replace("//", "/", $source)) === true) {
            $zip->extractTo($destination);
            $zip->close();
        }
        // @chmod($destination,0755);

        return true;
    }

    public function view_rights($check_id, $js = null)
    {
        if (!$this->Owner && !$this->Admin) {
            if ($check_id != $this->session->userdata('user_id')) {
                $this->session->set_flashdata('warning', $this->data['access_denied']);
                if ($js) {
                    die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome') . "'; }, 10);</script>");
                } else {
                    redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
                }
            }
        }
        return true;
    }


    
    public function makecomma($input)
    {
        if (strlen($input) <= 2) {return $input;}
        $length = substr($input, 0, strlen($input) - 2);
        $formatted_input = $this->makecomma($length) . "," . substr($input, -2);
        return $formatted_input;
    }

    public function formatSAC($num)
    {
        $pos = strpos((string) $num, ".");
        if ($pos === false) {$decimalpart = "00";} else {
            $decimalpart = substr($num, $pos + 1, 2);
            $num = substr($num, 0, $pos);}

        if (strlen($num) > 3 & strlen($num) <= 12) {
            $last3digits = substr($num, -3);
            $numexceptlastdigits = substr($num, 0, -3);
            $formatted = $this->makecomma($numexceptlastdigits);
            $stringtoreturn = $formatted . "," . $last3digits . "." . $decimalpart;
        } elseif (strlen($num) <= 3) {
            $stringtoreturn = $num . "." . $decimalpart;
        } elseif (strlen($num) > 12) {
            $stringtoreturn = number_format($num, 2);
        }

        if (substr($stringtoreturn, 0, 2) == "-,") {$stringtoreturn = "-" . substr($stringtoreturn, 2);}

        return $stringtoreturn;
    }

    public function md()
    {
        die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome') . "'; }, 10);</script>");
    }

    public function analyze_term($term)
    {
        $spos = strpos($term, $this->Settings->barcode_separator);
        if ($spos !== false) {
            $st = explode($this->Settings->barcode_separator, $term);
            $sr = trim($st[0]);
            $option_id = trim($st[1]);
        } else {
            $sr = $term;
            $option_id = false;
        }
        return array('term' => $sr, 'option_id' => $option_id);
    }

    public function paid_opts($paid_by = null, $purchase = false)
    {
        $opts = '
        <option value="Dinheiro"'.($paid_by && $paid_by == 'cash' ? ' selected="selected"' : '').'>'.lang("cash").'</option>
        <option value="transferencia"'.($paid_by && $paid_by == 'transferencia' ? ' selected="selected"' : '').'>'.lang("transferencia").'</option>    
        <option value="boleto"'.($paid_by && $paid_by == 'boleto' ? ' selected="selected"' : '').'>'.lang("boleto").'</option>    
        <option value="credito"'.($paid_by && $paid_by == 'credito' ? ' selected="selected"' : '').'>'.lang("credito").'</option>
        <option value="debito"'.($paid_by && $paid_by == 'debito' ? ' selected="selected"' : '').'>'.lang("debito").'</option>
        <option value="Cheque"'.($paid_by && $paid_by == 'Cheque' ? ' selected="selected"' : '').'>'.lang("cheque").'</option>
        <option value="vale_alimentacao"'.($paid_by && $paid_by == 'gift_card' ? ' selected="selected"' : '').'>'.lang("gift_card").'</option>
        
        <option value="outro"'.($paid_by && $paid_by == 'other' ? ' selected="selected"' : '').'>'.lang("other").'</option>';
        if (!$purchase) {
            $opts .= '<option value="deposito"'.($paid_by && $paid_by == 'deposit' ? ' selected="selected"' : '').'>'.lang("deposit").'</option>';
        }
        return $opts;
    }

    public function send_json($data)
    {
        header('Content-Type: application/json');
        die(json_encode($data));
        exit;
    }

}
