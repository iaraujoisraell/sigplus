<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Whatsapp extends App_Controller
{
    
     public function __construct()
    {
        parent::__construct();
        
    }
 
    public function index(){
     echo 'Acesso Negado'; exit;
      
    }


    public function busca_atendimentos_recepcao()
    {
        
        $data_hoje = date('Y-m-d');
       
        // LISTA TODOS OS MÉDICOS QUE TEM AGENDA NO DIA
        $this->load->model('mensagens_model');
        $atendimentos = $this->mensagens_model->get_atendimentos_recepcao_agenda_dia();
       // print_r($atendimentos); exit;
        foreach ($atendimentos as $dados){
            $id = $dados['id'];
            $hash = $dados['hash'];
            $raw_phone = $dados['phone'];
            $phone = preg_replace('/[^0-9]/', '', $raw_phone);
          
            // Verifica se está vazio
            if (empty($hash)) {
                // Gera um novo hash único
                $novo_hash = md5(uniqid($id, true));

                // Atualiza no banco
                $this->db->where('id', $id);
                $this->db->update('tblappointly_appointments', ['hash' => $novo_hash]);

                // Atualiza a variável local também se quiser usar depois
                $hash = $novo_hash;
            }     
       

        $msg = "Olá! Tudo bem?
Queremos saber como foi sua experiência com o atendimento da nossa recepção.
É uma pergunta rápida e sua resposta vai nos ajudar a melhorar ainda mais!
Agadecemos pela sua participação! 💙
*Tenha um excelente dia 😃☀️*";
        // $msg = 'teste de novo \n aqui';
        //echo $msg; exit;
       // $phone = "5592991553632";
        
        $enviado = $this->enviarBotaoWhatsApp($phone, $msg, $hash);
       
            if (!empty($enviado['messageId']) || !empty($enviado['id'])) {

                $this->db->where('id', $id);
               $this->db->update('tblappointly_appointments', ['avaliacao_enviada' => 1]);

                // Salva o log
                $this->db->insert('tblwhatsapp_log', [
                    'userid' => $id,
                    'phone' => $phone,
                    'message' => $msg,
                    'zaap_id' => $enviado['zaapId'] ?? null,
                    'zaap_msg_id' => $enviado['messageId'] ?? null,
                    'status' => ($enviado['id'] ?? '') === 'Message sent successfully' ? 'sucesso' : 'falha'
                ]);
                
                
               
            } else {
                log_message('error', "Falha no envio para {$phone} - ID: {$id}");
            }
           
        //echo 'aqui'; exit;
        }// fim for
        //$this->enviarMensagemWhatsApp('92991553632', 'executou rotina');
        //echo 'ENVIOU TODOS';
        //exit;

    }

    
    public function enviarMensagemWhatsApp($numero , $mensagem)
    {
            $url = 'https://api.z-api.io/instances/3E3D7A0FDD33807118574AF116590B15/token/70471779940B658581F68798/send-text';
            $data = [
                'phone' => "$numero",
                'message' => "$mensagem"
            ];
            $headers = [
                'Content-Type: application/json',
                'Client-Token: Fa895471a019a4f3aa34f892a79461f32S' // ✅ esse é o novo header necessário
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $resposta = curl_exec($ch);
            curl_close($ch);
            return json_decode($resposta, true);
    }


    public function enviarLinkWhatsApp($numero , $mensagem, $hash)
    {
        $url = 'https://api.z-api.io/instances/3E3D7A0FDD33807118574AF116590B15/token/70471779940B658581F68798/send-link';

        $data = [
            'phone' => "$numero",
            'message' => "$mensagem",
            'image' => "https://app.domvscare.com.br/logo_vision.png",
            'linkUrl' => "https://app.domvscare.com.br/avaliacao/vision?hash=$hash",
            'title' => "Vision Clínica - Sua opinião é importante! ",
            'linkDescription' => "Nos ajude a melhorar nos avaliando."
        ];

        $headers = [
            'Content-Type: application/json',
            'Client-Token: Fa895471a019a4f3aa34f892a79461f32S'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $resposta = curl_exec($ch);
        curl_close($ch);

        return json_decode($resposta, true);
    }


    public function enviarBotaoWhatsApp($numero , $mensagem, $hash)
    {
        $url = 'https://api.z-api.io/instances/3E3D7A0FDD33807118574AF116590B15/token/70471779940B658581F68798/send-button-actions';
       
        $data = [
            'phone' => "$numero",
            'message' => "$mensagem",
            'title' => 'Vision Clínica de Olhos',
            'footer' => 'Agradecemos por seu tempo',
            'buttonActions' => [
                [
                    'id' => '1',
                    'type' => 'URL',
                    'url' => "https://app.domvscare.com.br/avaliacao/vision?hash=$hash",
                    'label' => 'Avaliar Atendimento'
                ],
                [
                    'id' => '2',
                    'type' => 'URL',
                    'url' => 'https://api.whatsapp.com/send/?phone=559236593131', // substitua pelo telefone real da clínica
                    'label' => 'Falar com Atendimento'
                ]
            ]
        ];

        $headers = [
            'Content-Type: application/json',
            'Client-Token: Fa895471a019a4f3aa34f892a79461f32S'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $resposta = curl_exec($ch);
        curl_close($ch);
       
        return json_decode($resposta, true);
    }



    public function enviarBotaoWhatsApp2()
    {
        $msg = "Olá! Tudo bem?
Queremos saber como foi sua experiência com o atendimento da nossa recepção.
É uma pergunta rápida e sua resposta vai nos ajudar a melhorar ainda mais!
Agadecemos pela sua participação! 💙
*Tenha um excelente dia 😃☀️*

Clique no link para responder";
        $url = 'https://api.z-api.io/instances/3E3D7A0FDD33807118574AF116590B15/token/70471779940B658581F68798/send-link';

        $data = [
            'phone' => '5592991553632',
            'message' => $msg,

            'title' => 'Vision Clínica de Olhos - Sua opinião é muito importante!',
            'linkUrl' => 'https://app.domvscare.com.br/avaliacao/vision?hash=21c4a82a994c35ef2c2dec860180cfbe'
          
        ];

        $headers = [
            'Content-Type: application/json',
            'Client-Token: Fa895471a019a4f3aa34f892a79461f32S'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $resposta = curl_exec($ch);
        curl_close($ch);
        
        print_r($resposta); exit;
    }



}
