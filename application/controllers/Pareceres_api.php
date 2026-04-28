<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pareceres_api extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pareceres_model');
    }

    /**
     * Ponto de entrada: importa o array retornado pela API e salva.
     * Você pode chamar via CLI/CRON ou rota web protegida.
     */
    public function importar()
    {
        // (1) Obter dados da API
        // Se já tiver o array em mão, substitua $apiData por ele.
        $apiData = $this->mock_api_data(); // <- troque por seu array original
        //print_r($apiData); exit;
        // --- Exemplo cURL (se precisar) ---
        /*
        $url = 'https://sua.api/pontos/pareceres';
        $token = 'SEU_TOKEN';
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer '.$token, 'Accept: application/json'],
            CURLOPT_TIMEOUT => 30,
        ]);
        $resp = curl_exec($ch);
        if ($resp === false) {
            show_error('Erro ao consumir API: '.curl_error($ch), 500);
        }
        curl_close($ch);
        $apiData = json_decode($resp, true);
        if (!is_array($apiData)) {
            show_error('Resposta inválida da API', 500);
        }
        */
       
        // (2) Tenant opcional (Perfex multiempresa)
        $tenant_id = $this->session->userdata('empresa_id') ?? null;

        $importados = 0;
        foreach ($apiData as $row) {
            $parecer = $this->map_parecer($row);
            
            $parecer_id = $this->Pareceres_model->upsert_parecer($parecer, $tenant_id);
            $destinatarios = $this->parse_destinatarios($row['DESTINATARIOS'] ?? '');
            
            $this->Pareceres_model->replace_destinatarios($parecer_id, $destinatarios);
            $importados++;
        }

        echo "Importação concluída. Registros processados: {$importados}";
    }

    /* ===========================
       MAP & HELPERS
       =========================== */

    private function map_parecer(array $r): array
    {
        return [
            'nr_parecer'             => $this->to_int($r['NR_PARECER'] ?? null),
            'nr_atendimento'         => $this->to_int($r['NR_ATENDIMENTO'] ?? null),
            'dt_entrada'             => $this->date_br_to_sql($r['DT_ENTRADA'] ?? null),
            'dt_alta'                => $this->date_br_to_sql($r['DT_ALTA'] ?? null),
            'nome_pac_iniciais'      => $this->clean($r['NOME_PAC_INICIAIS'] ?? null),
            'nm_paciente_abrev'      => $this->clean($r['NM_PACIENTE_ABREV'] ?? null),
            'idade'                  => $this->to_int($r['IDADE'] ?? null),
            'localizacao_leito'      => $this->clean($r['LOCALIZACAO_LEITO'] ?? null),
            'dt_pedido'              => $this->date_br_to_sql($r['DT_PEDIDO'] ?? null),
            'dia_pedido'             => $this->clean($r['DIA_PEDIDO'] ?? null),
            'dt_resposta'            => $this->date_br_to_sql($r['DT_RESPOSTA'] ?? null),
            'status_parecer'         => $this->clean($r['STATUS_PARECER'] ?? null),
            'nm_medico_solicit'      => $this->clean($r['NM_MEDICO_SOLICIT'] ?? null),
            'nm_medico_parecer'      => $this->clean($r['NM_MEDICO_PARECER'] ?? null),
            'tipo_de_parecer'        => $this->clean($r['TIPO_DE_PARECER'] ?? null),
            'ds_especialidade_origem'=> $this->clean($r['DS_ESPECIALIDADE_ORIGEM'] ?? null),
            'ds_especialidade_dest'  => $this->clean($r['DS_ESPECIALIDADE_DEST'] ?? null),
            'tempo_horas'            => $this->decimal_br($r['TEMPO_HORAS'] ?? null),
            'qt_pedido'              => $this->to_int($r['QT_PEDIDO'] ?? null),
            'destinatarios_raw'      => $r['DESTINATARIOS'] ?? null,
        ];
    }

    /**
     * Quebra o campo DESTINATARIOS em linhas: nome, email, telefone, ordem
     * Exemplo de pedaço:
     * "Ricardo Rumaldo...,Email: ,Tel: 991392473 ; NIVALDO...,Email: ,Tel: ; ..."
     */
    private function parse_destinatarios(?string $raw): array
    {
        $rows = [];
        if (!$raw) return $rows;

        // Split por ';' (cada destinatário)
        $parts = preg_split('/\s*;\s*/', trim($raw), -1, PREG_SPLIT_NO_EMPTY);
        $ordem = 1;
        foreach ($parts as $p) {
            // Normaliza espaços
            $p = trim(preg_replace('/\s+/', ' ', $p));

            // Padrões: Nome,Email: algo,Tel: algo
            // Email pode vir vazio, telefone pode vir vazio
            $nome = $p;
            $email = null;
            $tel = null;

            // Extrai Email:
            if (preg_match('/Email:\s*([^,;]+)/i', $p, $m)) {
                $email = trim($m[1]);
                if ($email === '') $email = null;
            }

            // Extrai Tel:
            if (preg_match('/Tel:\s*([^,;]+)/i', $p, $m2)) {
                $tel = trim($m2[1]);
                if ($tel === '') $tel = null;
                // limpa tel deixando só dígitos (+ opcional)
                if ($tel) {
                    $tel = preg_replace('/[^\d+]/', '', $tel);
                }
            }

            // Remove os sufixos " ,Email: ...,Tel: ..." do nome
            $nome = preg_replace('/,?\s*Email:.*$/i', '', $nome); // corta a partir de "Email:"
            $nome = preg_replace('/,?\s*Tel:.*$/i', '', $nome);   // se vier Tel antes
            $nome = trim($nome, " ,");

            $rows[] = [
                'ordem'    => $ordem++,
                'nome'     => $nome ?: null,
                'email'    => $email,
                'telefone' => $tel,
            ];
        }
        return $rows;
    }

    /* ====== Utils ====== */

    private function clean($v)
    {
        if ($v === null) return null;
        $v = trim($v);
        return ($v === '') ? null : $v;
    }

    private function to_int($v)
    {
        if ($v === null || $v === '') return null;
        return (int) preg_replace('/\D+/', '', (string)$v);
    }

    /**
     * Converte "dd/mm/aa" ou "dd/mm/aaaa" para "YYYY-mm-dd".
     * Aceita vazio -> null
     */
    private function date_br_to_sql($d)
    {
        $d = $this->clean($d);
        if (!$d) return null;

        // dd/mm/aa ou dd/mm/aaaa
        if (preg_match('#^(\d{2})/(\d{2})/(\d{2,4})$#', $d, $m)) {
            $dd = (int)$m[1]; $mm = (int)$m[2]; $yy = $m[3];
            if (strlen($yy) === 2) {
                // regra simples: 00-69 => 2000-2069; 70-99 => 1970-1999 (ajuste conforme sua realidade)
                $yy = (int)$yy;
                $yy += ($yy <= 69) ? 2000 : 1900;
            } else {
                $yy = (int)$yy;
            }
            if (checkdate($mm, $dd, $yy)) {
                return sprintf('%04d-%02d-%02d', $yy, $mm, $dd);
            }
        }
        return null; // formato inválido
    }

    /**
     * Converte decimal BR ("2,7602777") para float/decimal (2.7602777)
     */
    private function decimal_br($v)
    {
        $v = $this->clean($v);
        if ($v === null) return null;
        // troca vírgula por ponto e remove espaços
        $v = str_replace(['.', ' '], ['', ''], $v); // remove separador de milhar
        $v = str_replace(',', '.', $v);
        return is_numeric($v) ? (float)$v : null;
    }

    /**
     * Somente para testes locais – usa seu exemplo
     */
    private function mock_api_data(): array
    {
        return [
            [
                'NR_PARECER' => '33633',
                'NR_ATENDIMENTO' => '3908742',
                'DT_ENTRADA' => '19/08/25',
                'DT_ALTA' => '',
                'NOME_PAC_INICIAIS' => 'R A G N M',
                'NM_PACIENTE_ABREV' => 'Rn A G N M',
                'IDADE' => '0',
                'LOCALIZACAO_LEITO' => 'UTI - Neonatal - UN: Leito NEO - 2',
                'DT_PEDIDO' => '20/08/25',
                'DIA_PEDIDO' => '20-Qua',
                'DT_RESPOSTA' => '',
                'STATUS_PARECER' => 'Pendente',
                'NM_MEDICO_SOLICIT' => 'Sonia Maria Rego de Almeida',
                'NM_MEDICO_PARECER' => '',
                'TIPO_DE_PARECER' => 'Parecer',
                'DS_ESPECIALIDADE_ORIGEM' => 'PEDIATRIA',
                'DS_ESPECIALIDADE_DEST' => 'NEUROCIRURGIA',
                'TEMPO_HORAS' => '4,05777777777777777777777777777777777778',
                'QT_PEDIDO' => '1',
                'DESTINATARIOS' => 'Torben Cavalcante Bezerra,Email: ,Tel: 981649375;Wander da Silva Ferreira,Email: ,Tel: ;Rochester o. Jezini,Email: ,Tel: ;Murilo dos Santos Silva,Email: ,Tel: ;',
            ],
            [
                'NR_PARECER' => '33624',
                'NR_ATENDIMENTO' => '3909016',
                'DT_ENTRADA' => '20/08/25',
                'DT_ALTA' => '',
                'NOME_PAC_INICIAIS' => 'M F S F',
                'NM_PACIENTE_ABREV' => 'Maria F S F',
                'IDADE' => '64',
                'LOCALIZACAO_LEITO' => 'Pronto Atendimento Adulto - UN: Leito Sala',
                'DT_PEDIDO' => '20/08/25',
                'DIA_PEDIDO' => '20-Qua',
                'DT_RESPOSTA' => '',
                'STATUS_PARECER' => 'Pendente',
                'NM_MEDICO_SOLICIT' => 'Angeli Alexandra Caro Contreras',
                'NM_MEDICO_PARECER' => '',
                'TIPO_DE_PARECER' => 'Parecer',
                'DS_ESPECIALIDADE_ORIGEM' => 'CLINICA MEDICA',
                'DS_ESPECIALIDADE_DEST' => 'ORTOPEDIA E TRAUMATOLOGIA',
                'TEMPO_HORAS' => '2,76027777777777777777777777777777777778',
                'QT_PEDIDO' => '1',
                'DESTINATARIOS' => 'Ricardo Rumaldo Chiroque Inga,Email: ,Tel: 991392473 ;NIVALDO AMARAL DE SOUZA,Email: ,Tel: ;RAFAEL SABOGAL,Email: ,Tel: ;GIUSEPPE LEMOS PERTOTI,Email: ,Tel: ;CORACY GONÇALVES BRASIL NETO,Email: ,Tel: ;MARLON CARNEIRO,Email: ,Tel: ;ADELINO JEAN RAMOS,Email: ,Tel: ;IRAIR DOS REIS PINTO,Email: ,Tel: ;ANA CRISTINA,Email: ,Tel: ;Chang Chia Po,Email: ,Tel: 92981378932;Paula Hevelyne Pinto dos Santos,Email: ,Tel: 981750115;Luiz Fernando Tupinamba da Silva,Email: ,Tel: 995283000;Flavio de Figueiredo Bezerra,Email: ,Tel: ;Armando Dantas Araújo,Email: ,Tel: 991612200;Julian S. Moura,Email: ,Tel: ;Almir Ribeiro De Carvalho Junior,Email: almir.ribeirojr@gmail.com,Tel: 984155873;Marlon Ferreira Carneiro,Email: drmarlonfc@yahoo.com.br,Tel: 988135609;Vivaldo Jose Diniz Mangueira,Email: ,Tel: ;Marcelo de Brito Monteiro,Email: ssylvia963@gmail.com,Tel: 981474444;Franklin Nascimento De Andrade,Email: sfachimello@bol.com.br,Tel: 991529770;',
            ],
        ];
    }

    public function cron_disparar_do_dia()
    {
        $hoje = '2025-08-20'; // date('Y-m-d');
        $tenant_id =  null;

        $pendentes = $this->Pareceres_model->get_pendentes_do_dia($hoje, $tenant_id, 300);
        
        $sum = ['ok'=>0,'fail'=>0,'skip'=>0];
        foreach ($pendentes as $row) {
            $fone = $this->normalize_phone($row['telefone']);
            if (!$fone) {
                $this->Pareceres_model->set_envio_result($row['destinatario_id'], 'skipped', json_encode(['reason'=>'no-phone']));
                $sum['skip']++; continue;
            }

            // token único do destinatário
            $token = $this->Pareceres_model->ensure_accept_token($row['destinatario_id']);

             // 1) botão interativo
            $okBtn = $this->enviarBotaoParecerWhatsApp($fone, $row, $token);

            // 2) detalhes na sequência (no próprio WhatsApp)
            $detalhes = "*Detalhes do Parecer*\n".$this->msg_detalhes($row);
            $okTxt   = $this->enviarMensagemWhatsApp($fone, $detalhes);

            $is_ok = $this->is_send_ok($okBtn) && $this->is_send_ok($okTxt);
            $this->Pareceres_model->set_envio_result(
                $row['destinatario_id'],
                $is_ok ? 'sent' : 'failed',
                json_encode(['btn'=>$okBtn,'txt'=>$okTxt]),
                $fone
            );
        }

        echo "Disparo (botões) concluído. Enviados={$sum['ok']}, Falhas={$sum['fail']}, Sem telefone={$sum['skip']}";
    }

    private function enviarBotaoParecerWhatsApp(string $fone55, array $r, string $token)
    {
        $clinica = $this->get_clinic_title();
        $socialPhone = $this->get_social_phone();

        $nome_med = $r['destinatario_nome'] ?: 'Médico(a)';
        $esp_dest = $r['ds_especialidade_dest'] ?: 'ESPECIALIDADE';
        $mensagem = "Prezado(a) Dr(a). {$nome_med}\n"
                . "Solicitação de *parecer* para *{$esp_dest}*.\n"
                . "Clique em *SIM* para aceitar ou fale com o Serviço Social.";

        // AGORA vai direto para o aceitar:
        $urlAceitar = site_url('pareceres_api/aceitar'
                    .'?pid='.(int)$r['parecer_id']
                    .'&did='.(int)$r['destinatario_id']
                    .'&t='.urlencode($token));

        $textoSS = "Olá, Serviço Social. Tenho interesse em responder o Parecer #".$r['nr_parecer'].".";
        $urlSocial = 'https://wa.me/'.$socialPhone.'?text='.rawurlencode($textoSS);

        return $this->enviarBotaoWhatsApp(
            $fone55,
            $mensagem,
            $clinica,
            ltrim($socialPhone, '+'),
            'pid='.(int)$r['parecer_id'].'&did='.(int)$r['destinatario_id'].'&t='.urlencode($token),
            $urlAceitar,     // <- botão SIM agora chama aceitar()
            $urlSocial
        );
    }


    public function confirmar()
    {
        $parecer_id = (int)$this->input->get('pid');
        $dest_id    = (int)$this->input->get('did');
        $token      = (string)$this->input->get('t');
        
        if (!$parecer_id || !$dest_id || !$token) {
            return $this->render_msg_html('Dados inválidos. Tente novamente ou contate o Serviço Social.');
        }

        $dest = $this->Pareceres_model->get_destinatario($dest_id);
        if (!$dest || $dest['parecer_id'] != $parecer_id) {
            return $this->render_msg_html('Destinatário não encontrado para este parecer.');
        }
        if (empty($dest['accept_token']) || !hash_equals($dest['accept_token'], $token)) {
            return $this->render_msg_html('Link inválido ou expirado.');
        }

        $p = $this->Pareceres_model->get_parecer($parecer_id);
        if (!$p) return $this->render_msg_html('Parecer não encontrado.');

        if (!empty($p['accepted_at'])) {
            $msg = "Este parecer já foi aceito por {$p['accepted_by_nome']} em "
                . date('d/m/Y H:i', strtotime($p['accepted_at'])).". Obrigado!";
            return $this->render_msg_html($msg);
        }

        // Monta os detalhes
        $detalhes = $this->msg_detalhes([
            'nr_parecer'            => $p['nr_parecer'],
            'nr_atendimento'        => $p['nr_atendimento'],
            'nm_paciente_abrev'     => $p['nm_paciente_abrev'],
            'idade'                 => $p['idade'],
            'localizacao_leito'     => $p['localizacao_leito'],
            'dt_pedido'             => $p['dt_pedido'],
            'ds_especialidade_origem'=> $p['ds_especialidade_origem'],
        ]);

        $confirmUrl = site_url('pareceres_api/aceitar?pid='.$parecer_id.'&did='.$dest_id.'&t='.urlencode($token));

        $html = "<h2>Confirmação de Aceite do Parecer</h2>"
            . "<p>Antes de aceitar, revise as informações abaixo:</p>"
            . "<pre style='white-space:pre-wrap;border:1px solid #ddd;padding:12px;border-radius:8px;background:#fafafa'>"
            . htmlspecialchars($detalhes, ENT_QUOTES, 'UTF-8')
            . "</pre>"
            . "<p><a href='{$confirmUrl}' style='display:inline-block;padding:12px 16px;background:#0a7a0a;color:#fff;text-decoration:none;border-radius:8px;font-weight:bold'>"
            . "ACEITAR DEFINITIVAMENTE</a></p>"
            . "<p style='margin-top:8px;color:#555'>Se você clicou em SIM por engano, apenas feche esta página.</p>";

        return $this->render_msg_html($html, true);
    }

    public function aceitar()
    {
        $parecer_id = (int)$this->input->get('pid');
        $dest_id    = (int)$this->input->get('did');
        $token      = (string)$this->input->get('t');
      
        if (!$parecer_id || !$dest_id || !$token) {
            return $this->render_msg_html('Dados inválidos. Tente novamente ou contate o Serviço Social.');
        }

        $dest = $this->Pareceres_model->get_destinatario($dest_id);
        if (!$dest || $dest['parecer_id'] != $parecer_id) {
            return $this->render_msg_html('Destinatário não encontrado.');
        }
        if (empty($dest['accept_token']) || !hash_equals($dest['accept_token'], $token)) {
            return $this->render_msg_html('Link inválido ou expirado.');
        }

        $p = $this->Pareceres_model->get_parecer($parecer_id);
        if (!$p) return $this->render_msg_html('Parecer não encontrado.');

        /*if (!empty($p['accepted_at'])) {
            $msg = "Este parecer já foi aceito por {$p['accepted_by_nome']} em "
                . date('d/m/Y H:i', strtotime($p['accepted_at'])).". Obrigado!";
            return $this->render_msg_html($msg);
        } */
       
        // Marca aceite
        $ip = $this->input->ip_address();
        $ua = $this->input->user_agent();
        $this->Pareceres_model->marcar_aceite($parecer_id, $dest_id, $dest['nome'], $ip, $ua);
        
        // Notifica os demais
        $outros = $this->Pareceres_model->get_outros_destinatarios($parecer_id, $dest_id);
        foreach ($outros as $o) {
            $f = $this->normalize_phone($o['telefone'] ?? '');
            if (!$f) continue;
            $texto = "Informamos que o Parecer #{$p['nr_parecer']} foi aceito por Dr(a). {$dest['nome']} em ".date('d/m/Y H:i').". Obrigado!";
            $this->enviarMensagemWhatsApp($f, $texto);
        }
       
        // Notifica Serviço Social
        $wa_social = $this->get_social_phone();
        if ($wa_social) {
            $texto_social = "Parecer #{$p['nr_parecer']} ACEITO por Dr(a). {$dest['nome']} em ".date('d/m/Y H:i').". Atendimento #{$p['nr_atendimento']}.";
            $this->enviarMensagemWhatsApp($wa_social, $texto_social);
            $this->db->where('id',$parecer_id)->update('tblpareceres', ['accepted_notified_at' => date('Y-m-d H:i:s')]);
        }

        // Agradece ao médico no WhatsApp
        
        $foneAceitou = $this->normalize_phone($dest['telefone'] ?? '');
        if ($foneAceitou) {
            $detalhesOk = "*Confirmação do Aceite*\n"
                        . "Você aceitou o Parecer #{$p['nr_parecer']} em ".date('d/m/Y H:i').".\n\n"
                        . $this->msg_detalhes([
                            'nr_parecer'             => $p['nr_parecer'],
                            'nr_atendimento'         => $p['nr_atendimento'],
                            'nm_paciente_abrev'      => $p['nm_paciente_abrev'],
                            'idade'                  => $p['idade'],
                            'localizacao_leito'      => $p['localizacao_leito'],
                            'dt_pedido'              => $p['dt_pedido'],
                            'ds_especialidade_origem'=> $p['ds_especialidade_origem'],
                        ]);
            $this->enviarMensagemWhatsApp($foneAceitou, $detalhesOk);
        }

        return $this->render_msg_html("Obrigado, Dr(a). {$dest['nome']}! Seu aceite do Parecer #{$p['nr_parecer']} foi registrado com sucesso.");
    }



    /**
     * Envia mensagem com botões (Z-API: send-button-actions)
     * Ajustei a assinatura para também aceitar as URLs já montadas
     */
    public function enviarBotaoWhatsApp($telefone, $mensagem, $clinica, $contato_empresa, $hashQuery, $urlConfirmar, $urlSocial)
    {
        $url = 'https://api.z-api.io/instances/3E4F999648BA80884D9E5E8072250E75/token/B1E0F5FE99B1A66DA6F934F0/send-button-actions';

        $phoneSan = preg_replace('/\D+/', '', $telefone);
        if (strpos($phoneSan, '55') !== 0) $phoneSan = '55'.$phoneSan;
        $phoneSan = '5592984068481';
        //$phoneSan = '559291553632';
        $data = [
            'phone'   => $phoneSan,
            'message' => $mensagem,
            'title'   => $clinica ?: 'HOSPITAL',
            'footer'  => 'Agradecemos por seu tempo',
            'buttonActions' => [
                [
                    'id'    => 'aceitar',
                    'type'  => 'URL',
                    'url'   => $urlConfirmar, // página de CONFIRMAÇÃO
                    'label' => 'SIM – Prosseguir'
                ],
                [
                    'id'    => 'social',
                    'type'  => 'URL',
                    'url'   => $urlSocial,
                    'label' => 'Serviço Social'
                ],
                // (Opcional) Terceiro botão: “Ver detalhes”
                // [
                //     'id'    => 'detalhes',
                //     'type'  => 'URL',
                //     'url'   => site_url('pareceres_api/detalhes?'.$hashQuery),
                //     'label' => 'Ver detalhes'
                // ],
            ],
        ];

        $headers = [
            'Content-Type: application/json',
            'Client-Token: F9b6d1dfa91b24d469ef64612d85e27b2S'
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_HTTPHEADER     => $headers
        ]);
        $resposta = curl_exec($ch);
        curl_close($ch);
        print_r($resposta); exit;
        return json_decode($resposta, true);
    }

    private function get_clinic_title()
    {
        return 'HOSPITAL UNIMED'; // ou get_option('companyname')
    }




    /* =========================
    Helpers de mensagem/envio
    ========================= */

    private function msg_cabecalho($nome_medico, $especialidade_dest, $sim_url, $wa_social_link)
    {
        $nome_medico = $nome_medico ?: 'Médico(a)';
        $esp = $especialidade_dest ?: 'ESPECIALIDADE';

        // Observação: WhatsApp aceita links clicáveis em texto.
        return "HOSPITAL UNIMED\n\n"
            . "Prezado(a) Doutor(a) {$nome_medico}\n\n"
            . "Informamos que foi inserida no sistema uma solicitação de parecer para a especialidade *{$esp}*.\n\n"
            . "Se tiver interesse e disponibilidade em responder, clique em *SIM*:\n{$sim_url}\n\n"
            . "Dúvidas? Fale com o *Serviço Social*:\n{$wa_social_link}";
    }

    private function msg_detalhes($r)
    {
        // datas em dd/mm/aaaa
        $dt_ped = $this->fmt_date_br($r['dt_pedido'] ?? null);

        $linhas = [];
        $linhas[] = "Nro Parecer: {$r['nr_parecer']}";
        $linhas[] = "Nro Atendimento: {$r['nr_atendimento']}";
        $linhas[] = "";
        $pac = trim(($r['nm_paciente_abrev'] ?? '')) ?: 'N/I';
        $idade = isset($r['idade']) && $r['idade'] !== '' ? $r['idade']." anos" : 'N/I';
        $linhas[] = "Paciente: {$pac}, {$idade}";
        $setor = $r['localizacao_leito'] ?: 'N/I';
        $linhas[] = "Setor: {$setor}";
        $linhas[] = "";
        // itálico no WhatsApp: underscore _texto_
        $linhas[] = "Data Solicitação: _{$dt_ped}_";
        $esp_org = $r['ds_especialidade_origem'] ?: 'N/I';
        $linhas[] = "Especialidade solicitante: {$esp_org}";

        return implode("\n", $linhas);
    }

    private function fmt_date_br($sql_date)
    {
        if (!$sql_date) return 'N/I';
        $t = strtotime($sql_date);
        return $t ? date('d/m/Y', $t) : 'N/I';
    }

    private function normalize_phone($raw)
    {
        if (!$raw) return null;
        $d = preg_replace('/\D+/', '', $raw);

        // Já com DDI?
        if (strpos($d, '55') === 0 && strlen($d) >= 12 && strlen($d) <= 13) {
            return $d;
        }

        // Se vier 10/11 dígitos (DDD + número), prefixa Brasil
        if ((strlen($d) === 10) || (strlen($d) === 11)) {
            return '55'.$d;
        }

        // Qualquer outro caso: não envia
        return null;
    }

    private function is_send_ok($apiResp)
    {
        // Ajuste conforme retorno real da Z-API.
        // Ex.: sucesso => {"messageId": "...", "status": "sent"} ou similar.
        return is_array($apiResp) && !isset($apiResp['error']);
    }

    /**
     * Link pré-preenchido para falar com o Serviço Social no WhatsApp.
     * Ajuste o número abaixo (DDI+DDD+número).
     */
    private function servico_social_wa_link($row)
    {
        $fone = $this->get_social_phone();
        if (!$fone) return 'https://wa.me/'; // fallback

        $texto = "Olá, Serviço Social. Tenho interesse em responder o Parecer #{$row['nr_parecer']}.";
        return 'https://wa.me/'.$fone.'?text='.rawurlencode($texto);
    }

    /** Configure aqui o número do Serviço Social (DDI+DDD+numero), ex: 5592987654321 */
    private function get_social_phone()
    {
        // Ideal: recuperar de options/config da empresa.
        // return get_option('social_whatsapp_number'); // se existir
        return '5592991507535'; // <-- AJUSTE AQUI
    }

    /* ==========
    Envio Z-API
    ========== */

    public function enviarMensagemWhatsApp($numero , $mensagem)
    {
        
        $numero = '559284068481';
        //$numero = '559291553632';
       
        $url = 'https://api.z-api.io/instances/3E4F999648BA80884D9E5E8072250E75/token/B1E0F5FE99B1A66DA6F934F0/send-text';
        $data = [
            'phone' => "$numero",
            'message' => "$mensagem"
        ];
        $headers = [
            'Content-Type: application/json',
            'Client-Token: F9b6d1dfa91b24d469ef64612d85e27b2S'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $resposta = curl_exec($ch);
        
        curl_close($ch);
        return json_decode($resposta, true);
    }

    /* =====
    View
    ===== */
    private function render_msg_html($texto)
    {
        echo "<!doctype html><html><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'>";
        echo "<title>Parecer</title>";
        echo "<style>body{font-family:Arial, sans-serif;padding:20px;color:#111} .ok{padding:12px;border:1px solid #0a0;border-radius:8px;background:#f6fff6}</style>";
        echo "</head><body><div class='ok'>".htmlspecialchars($texto, ENT_QUOTES, 'UTF-8')."</div></body></html>";
    }

}
