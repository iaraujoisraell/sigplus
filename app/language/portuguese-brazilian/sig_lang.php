<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Module: General Language File for common lang keys
 * Language: Brazilian Portuguese Language File
 * 
 * Last edited:
 * 10 de Maio de 2016
 *
 * Package:
 * Stock Manage Advance v3.0.2.8
 *
 * Translated by:
 * Robson Gonçalves (POP Computadores) robson@popcomputadores.com.br
 *
 * License:
 * GPL v3 or above
 *
 * You can translate this file to your language. 
 * For instruction on new language setup, please visit the documentations. 
 * You also can share your language files by emailing to saleem@tecdiary.com 
 * Thank you 
 */


/* --------------------- CUSTOM FIELDS ------------------------ */
/*
* Below are custome field labels
* Please only change the part after = and make sure you change the the words in between ""; 
* $lang['bcf1'] 						= "Biller Custom Field 1";
* Don't change this						= "You can change this part";
* For support email contact@tecdiary.com Thank you!
*/

$lang['bcf1'] 							= "Vendedor - Campo personalizado 1";
$lang['bcf2'] 							= "Vendedor - Campo personalizado 2";
$lang['bcf3'] 							= "Vendedor - Campo personalizado 3";
$lang['bcf4'] 							= "Vendedor - Campo personalizado 4";
$lang['bcf5'] 							= "Vendedor - Campo personalizado 5";
$lang['bcf6'] 							= "Vendedor - Campo personalizado 6";
$lang['pcf1'] 							= "Cor";
$lang['pcf2'] 							= "PIS";
$lang['pcf3'] 							= "NCM";
$lang['pcf4'] 							= "CFOP";
$lang['pcf5'] 							= "ICMS";
$lang['pcf6'] 							= "CONFINS";
$lang['ccf1'] 							= "Inscr. Estadual / R.G.";
$lang['ccf2'] 							= "Numero do Endereço";
$lang['ccf3'] 							= "Complemento";
$lang['ccf4'] 							= "Tipo de Pessoa (J ou F)";
$lang['ccf5'] 							= "Bairro";
$lang['ccf6'] 							= "Como chegou à nossa Empresa?";
$lang['scf1'] 							= "Inscr. Estadual / R.G.";
$lang['scf2'] 							= "Ramo / Atividade";
$lang['scf3'] 							= "Método de Pagamento";
$lang['scf4'] 							= "Website";
$lang['scf5'] 							= "Whatsapp";
$lang['scf6'] 							= "Observação";


/* ----------------- DATATABLES LANGUAGE ---------------------- */
/*
* Below are datatables language entries
* Please only change the part after = and make sure you change the the words in between ""; 
* 'sEmptyTable'                     => "No data available in table",
* Don't change this                 => "You can change this part but not the word between and ending with _ like _START_;
* For support email contact@tecdiary.com Thank you!
*/

$lang['datatables_lang']        = array(
    'sEmptyTable'                   => "Não há dados disponíveis na tabela",
    'sInfo'                         => "Exibindo _START_ para _END_ de _TOTAL_ entradas",
    'sInfoEmpty'                    => "Não há nada para exibir",
    'sInfoFiltered'                 => "(filtrado de um total de _MAX_ entradas)",
    'sInfoPostFix'                  => "",
    'sInfoThousands'                => ",",
    'sLengthMenu'                   => "Exibindo _MENU_ ",
    'sLoadingRecords'               => "Carregando...",
    'sProcessing'                   => "Processando...",
    'sSearch'                       => "Pesquisar",
    'sZeroRecords'                  => "Não há registros correspondentes encontrados",
    'oAria'                                     => array(
      'sSortAscending'                => ": ativar para classificar a coluna crescente",
      'sSortDescending'               => ": ativar para classificar a coluna decrescente"
      ),
    'oPaginate'                                 => array(
      'sFirst'                        => "<< Primeira",
      'sLast'                         => "Última >>",
      'sNext'                         => "Próxima >",
      'sPrevious'                     => "< Anterior",
      )
    );

/* ----------------- Select2 LANGUAGE ---------------------- */
/*
* Below are select2 lib language entries
* Please only change the part after = and make sure you change the the words in between "";
* 's2_errorLoading'                 => "The results could not be loaded",
* Don't change this                 => "You can change this part but not the word between {} like {t};
* For support email support@tecdiary.com Thank you!
*/

$lang['select2_lang']               = array(
    'formatMatches_s'               => "Um resultado está disponível, pressione enter para selecioná-lo.",
    'formatMatches_p'               => "resultados estão disponíveis, use cima e para baixo teclas de seta para navegar.",
    'formatNoMatches'               => "Não há registros encontrados",
    'formatInputTooShort'           => "Por favor digite {n} ou mais caracteres",
    'formatInputTooLong_s'          => "Por favor exclua {n} caracter",
    'formatInputTooLong_p'          => "Por favor exclua {n} caracteres",
    'formatSelectionTooBig_s'       => "Você pode selecionar somente {n} ítem",
    'formatSelectionTooBig_p'       => "Você pode selecionar somente {n} ítens",
    'formatLoadMore'                => "Carregando mais resultados...",
    'formatAjaxError'               => "Uma requisição de Ajax falhou",
    'formatSearching'               => "Buscando..."
    );

/*  --------------------- DADOS DA EMPRESA ---------------------- */

$lang['razao_social'] 						= "Nome";
$lang['tipo']    						= "Tipo";
$lang['cnpj']    						= "CNPJ";
$lang['cpf']    						= "CPF";    
$lang['ie']                                                     = "Inscrição Estadual";
$lang['im']                                                     = "Inscricao Municipal";

$lang['endereco'] 						= "Endereço";
$lang['numero'] 						= "Numero";
$lang['bairro'] 						= "Bairro";
$lang['complemento'] 						= "Complemento";
$lang['cidade'] 						= "Cidade";
$lang['uf']                                                     = "UF";
$lang['cep']                                                    = "CEP";

$lang['telefone'] 						= "Telefone";
$lang['celular'] 						= "Celular";
$lang['skype']                                                  = "Skype";
$lang['email'] 						        = "Email do Responsável";
$lang['diaVencimento'] 						= "Dia para Faturamento";
$lang['valorMensalidade'] 					= "Valor da Mensalidade";
$lang['status'] 						= "Status";

$lang['dataCadastro'] 						= "Data do Cadastro";
$lang['diaVencimento'] 						= "Dia do Vencimento da Mensalidade";
$lang['status'] 						= "Status";
$lang['valorMensalidade'] 						= "Valor da Mensalidade";

$lang['data_inicio'] 						= "Data do Cadastro";

$lang['ambiente'] 						= "Ambiente da Nfe";
$lang['numeroNfe']                                              = "Número Atual da Nfe";

$lang['opcoesContato']                                          = "Opções de Contato";
$lang['opcoesNfe']                                              = "Opções da NFe";
$lang['infoEmpresa']                                            = "Informações";

/* ----------------- SMA GENERAL LANGUAGE KEYS -------------------- */

$lang['home'] 								= "Início";
$lang['dashboard'] 							= "Painel de Controle";
$lang['username'] 							= "Nome de Usuário";
$lang['password'] 							= "Senha";
$lang['first_name'] 						= "Nome";
$lang['last_name'] 							= "Sobrenome";
$lang['confirm_password'] 					= "Confirmar senha";
$lang['email'] 								= "E-mail";
$lang['phone'] 								= "Telefone";
$lang['company'] 							= "Empresa";
$lang['product_code'] 						= "Código do Produto";
$lang['product_name'] 						= "Nome do Produto";
$lang['cname'] 								= "Nome do Cliente";
$lang['barcode_symbology'] 					= "Simbologia do Cód. de Barras";
$lang['product_unit'] 						= "Unid. do Produto";
$lang['product_price'] 						= "Preço";
$lang['contact_person'] 					= "Contato";
$lang['email_address'] 						= "E-mail";
$lang['address'] 							= "Endereço";
$lang['city'] 								= "Cidade";
$lang['monday'] 							= "Segunda";
$lang['tuesday'] 							= "Terça";
$lang['wednesday'] 							= "Quarta";
$lang['thursday'] 							= "Quinta";
$lang['friday'] 							= "Sexta";
$lang['saturday'] 							= "Sábado";
$lang['sunday'] 							= "Domingo";
$lang['mon'] 								= "Seg";
$lang['tue'] 								= "Ter";
$lang['wed'] 								= "Qua";
$lang['thu'] 								= "Qui";
$lang['fri'] 								= "Sex";
$lang['sat'] 								= "Sáb";
$lang['sun'] 								= "Dom";
$lang['mo'] 								= "Se";
$lang['tu'] 								= "Te";
$lang['we'] 								= "Qu";
$lang['th'] 								= "Qu";
$lang['fr'] 								= "Se";
$lang['sa'] 								= "Sá";
$lang['su'] 								= "Do";
$lang['january']   							= "Janeiro";
$lang['february']   						= "Fevereiro";
$lang['march']     							= "Março";
$lang['april']      						= "Abril";
$lang['may']      							= "Maio";
$lang['june']       						= "Junho";
$lang['july']       						= "Julho";
$lang['august']     						= "Agosto";
$lang['september']  						= "Setembro";
$lang['october']    						= "Outubro";
$lang['november']   						= "Novembro";
$lang['december']   						= "Dezembro"; 
$lang['jan']   			 					= "Jan";
$lang['feb']   								= "Fev";
$lang['mar']     						 	= "Mar";
$lang['apr']      							= "Abr";
$lang['may']      							= "Mai";
$lang['jun']       							= "Jun";
$lang['jul']       							= "Jul";
$lang['aug']     							= "Ago";
$lang['sep']  								= "Set";
$lang['oct']    							= "Out";
$lang['nov']   								= "Nov";
$lang['dec']   								= "Dez";
$lang['today'] 								= "Hoje";
$lang['welcome'] 							= "Bem-vindo";
$lang['profile'] 							= "Perfil";
$lang['change_password'] 					= "Mudar Senha";
$lang['logout'] 							= "Sair";
$lang['notifications'] 						= "Notificações";
$lang['calendar'] 							= "Calendário";
$lang['messages'] 							= "Mensagens";
$lang['styles'] 							= "Estilos";
$lang['language'] 							= "Idioma";
$lang['alerts'] 							= "Alertas";
$lang['list_products'] 						= "Lista Produtos";
$lang['add_product'] 						= "Adicionar Produto";
$lang['print_barcodes'] 					= "Imprimir cód. de barras";
$lang['print_labels'] 						= "Etiquetas de Impressão";
$lang['import_products'] 					= "Importar Produtos";
$lang['update_price'] 						= "Atualizar Preço";
$lang['damage_products'] 					= "Alerta de Produto";

$lang['dados_empresa'] 						= "Conta da Empresa";
$lang['cadastro'] 						= "Dados da empresa";
$lang['mensalidades'] 						= "Mensalidades";
$lang['logo_empresa'] 						= "Logo da Empresa";
$lang['cadastroEmpresa'] 						= "Dados da Empresa";
$lang['contatos'] 						= "Contatos";
$lang['nfe']                                                    = "NFe";
$lang['atualizar_cadastro'] 						= "Atualizar Cadastro da Empresa";
/*
 * Financeiro
 */
$lang['financeiro'] 						= "Financeiro";
$lang['despesas'] 						= "Contas a Pagar";
$lang['receitas'] 						= "Contas a Receber";

$lang['despesas2'] 						= "Despesas";
$lang['receitas2'] 						= "Receitas";

$lang['add_despesas'] 						= "Adicionar Conta a Pagar";
$lang['edd_despesas'] 						= "Editar Conta a Pagar";
$lang['add_receita'] 						= "Adicionar Receita";
$lang['edd_receitas'] 						= "Editar Conta a Receber";

$lang['add_payment'] 						= "Adicionar Pagamento";

$lang['transactions']                                           = "Transações Financeiras";
$lang['expenses']                                               = "Despesas";
$lang['all_expenses']                                           = "Todos as Despesas";

$lang['income']                                                 = "Receitas";
$lang['all_income']                                             = "Todos as Receitas";

$lang['value'] 							= "Valor";
$lang['value_pago'] 						= "Valor Pago";
$lang['parcela'] 						= "Parcela(s)";


$lang['add_receita'] 						= "Adicionar Receita";

//Fim financeiro


/**************************************FISCAL************************************/
$lang['fiscal'] 						= "Fiscal";
$lang['nfes']    						= "Notas Fiscas";
$lang['all_nfe'] 						= "Todas as NFe";

$lang['num_nfe'] 						= "Número da NFe";
$lang['all_nfe'] 						= "Todas as NFe";

$lang['chave']                                                  = "Chave da NFe";
$lang['num_venda']                                              = "Num. Venda";
$lang['total_nota']                                             = "Valor da Nota";
$lang['referencia']                                             = "Referencia";
$lang['nfe']                                                    = "NFe";

$lang['cancelar_nfe']                                           = "Cancelamento da NFe";
$lang['carta_correcao']                                         = "Carta de Correção da NFe";
$lang['numero_inutilizacao']                                    = "Inutilização de numeração de Nfe";

$lang['motivo']                                                 = "Informe o Motivo do Cancelamento";
/********************************************************************************/



$lang['sales'] 								= "Vendas";
$lang['list_sales'] 						= "Lista de vendas";
$lang['add_sale'] 							= "Adicionar Venda";
$lang['deliveries'] 						= "Entregas";
$lang['gift_cards'] 						= "VALE-COMPRA";
$lang['quotes'] 							= "Cotações";
$lang['list_quotes'] 						= "Lista de Cotações";
$lang['add_quote'] 							= "Adicionar Cotação";
$lang['purchases'] 							= "Compras";
$lang['list_purchases'] 					= "Lista de Compras";
$lang['add_purchase'] 						= "Adicionar Compra";
$lang['add_purchase_by_csv'] 				= "Adic. Compra por CSV";
$lang['transfers'] 							= "Transferências";
$lang['register'] 							= "Cadastros";


$lang['list_transfers'] 					= "Lista de Transferências";
$lang['add_transfer'] 						= "Adic. Transferência";
$lang['add_transfer_by_csv'] 				= "Adic. Transf. por CSV";
$lang['people'] 							= "Pessoas";
$lang['list_users'] 						= "Lista de Usuários";
$lang['new_user'] 							= "Adicionar Usuário";
$lang['list_billers'] 						= "Lista de Vendedores";
$lang['add_biller'] 						= "Adicionar Vendedor";
$lang['list_customers'] 					= "Lista de Clientes";
$lang['add_customer'] 						= "Adicionar Cliente";
$lang['list_suppliers'] 					= "Lista de Fornecedores";
$lang['add_supplier'] 						= "Adicionar Fornecedor";
$lang['settings'] 							= "Configurações";
$lang['system_settings'] 					= "Config. do Sistema";
$lang['change_logo'] 						= "Mudar Logo";
$lang['currencies'] 						= "Moedas";
$lang['attributes'] 						= "Variantes do Produto";
$lang['customer_groups'] 					= "Grupos de clientes";
$lang['categories'] 						= "Categorias";
$lang['subcategories'] 						= "Sub-Categorias";
$lang['tax_rates'] 							= "Taxas Fiscais";
$lang['warehouses'] 						= "Depósitos";

$lang['filtro'] 						= "Filtro";
$lang['all_months'] 						= "Todos os Meses";
$lang['warehouses'] 						= "Depósitos";

$lang['Estock'] 						= "Estoque";
$lang['email_templates'] 					= "Modelos de e-mail";
$lang['group_permissions'] 					= "Permissões de grupo";
$lang['backup_database'] 					= "Fazer Backup do Banco de Dados";
$lang['reports'] 							= "Relatórios";
$lang['overview_chart'] 					= "Ver Gráfico";
$lang['warehouse_stock'] 					= "Gráf. do Estoque do Dep.";
$lang['product_quantity_alerts'] 			= "Alerta de Estoque";
$lang['product_expiry_alerts'] 				= "Alerta de Validade";
$lang['products_report'] 					= "Relatório de Produtos";
$lang['daily_sales'] 						= "Vendas Diárias";
$lang['monthly_sales'] 						= "Vendas Mensais";
$lang['sales_report'] 						= "Relatório de Vendas";
$lang['payments_report'] 					= "Relatórios de Pagtos.";
$lang['profit_and_loss'] 					= "Lucros e/ou perda";
$lang['purchases_report'] 					= "Relatório de Compras";
$lang['customers_report'] 					= "Relatório de Clientes";
$lang['suppliers_report'] 					= "Relatório de Fornecedores";
$lang['staff_report'] 						= "Relatório da Administração";
$lang['your_ip'] 							= "Seu IP";
$lang['last_login_at'] 						= "Último acesso em";
$lang['notification_post_at'] 				= "Notificação publicada em";
$lang['quick_links'] 						= "Links Rápidos";
$lang['date'] 								= "Data";
$lang['reference_no'] 						= "Nº de Ref.";
$lang['products'] 							= "Produtos";
$lang['customers'] 							= "Clientes";
$lang['suppliers'] 							= "Fornecedores";
$lang['users'] 								= "Usuários";
$lang['latest_five'] 						= "Últimos 5";
$lang['total'] 								= "Total";
$lang['payment_status'] 					= "Situação do Pagamento";
$lang['paid'] 								= "Pago";
$lang['customer'] 							= "Cliente";
$lang['status'] 							= "Status";
$lang['amount'] 							= "Valor Pago";
$lang['amount2'] 							= "Valor a Pagar";
$lang['supplier'] 							= "Fornecedor";
$lang['from'] 								= "De";
$lang['to'] 								= "Para";
$lang['name'] 								= "Nome";
$lang['create_user'] 						= "Adicionar Usuário";
$lang['gender'] 							= "Gênero";
$lang['biller'] 							= "Vendedor";
$lang['select'] 							= "Selecionar";
$lang['warehouse'] 							= "Depósito";
$lang['active'] 							= "Ativo";
$lang['inactive'] 							= "Inativo";
$lang['all'] 								= "Todos";
$lang['list_results'] 						= "Por favor, use a tabela abaixo para navegar ou filtrar os resultados. Você pode baixar a tabela como excel e pdf .";
$lang['actions'] 							= "Ações";
$lang['pos'] 								= "PDV";
$lang['access_denied'] 						= "Acesso negado! Você não tem direito de acessar a página solicitada. Por favor, contate o administrador. ";
$lang['add'] 								= "Adicionar";
$lang['edit'] 								= "Editar";
$lang['delete'] 							= "Excluir";
$lang['view'] 								= "Ver";
$lang['update'] 							= "Atualizar";
$lang['save'] 								= "Salvar";
$lang['login'] 								= "Entrar";
$lang['submit'] 							= "Enviar";
$lang['symbnumber'] 						= "Nº";
$lang['no'] 								= "Não";
$lang['yes'] 								= "Sim";
$lang['disable'] 							= "Desativar";
$lang['enable'] 							= "Ativar";
$lang['enter_info'] 						= "Por favor, preencha as informações abaixo. Os títulos de campos marcados com * são obrigatórios.";
$lang['update_info'] 						= "Por favor, atualize as informações abaixo. Os títulos de campos marcados com * são obrigatórios.";
$lang['no_suggestions'] 					= "Não foi possível obter dados para sugestões, por favor verifique a sua entrada";
$lang['i_m_sure'] 							= 'Sim, Eu tenho certeza';
$lang['r_u_sure'] 							= '<b> Você tem certeza?</b>';
$lang['export_to_excel'] 					= "Exportar para um arquivo em Excel";
$lang['export_to_pdf'] 						= "Exportar para um arquivo em PDF";
$lang['image'] 								= "Imagem";
$lang['sale'] 								= "Venda";
$lang['quote'] 								= "Cotação";
$lang['purchase'] 							= "Compra";
$lang['transfer'] 							= "Transferência";
$lang['payment'] 							= "Pagamento";
$lang['payments'] 							= "Pagamentos";
$lang['orders'] 							= "Pedidos";
$lang['pdf'] 								= "PDF";
$lang['vat_no'] 							= "CNPJ / CPF";
$lang['country'] 							= "País";
$lang['add_user'] 							= "Adicionar usuário";
$lang['type'] 								= "Digite";
$lang['person'] 							= "Pessoa";
$lang['state'] 								= "Estado";
$lang['postal_code'] 						= "CEP";
$lang['id'] 								= "ID";
$lang['close'] 								= "Fechar";
$lang['male'] 								= "Masculino";
$lang['female'] 							= "Feminino";
$lang['notify_user'] 						= "Informe o usuário";
$lang['notify_user_by_email'] 				= "Informe o usuário por e-mail";
$lang['billers'] 							= "Vendedores";
$lang['all_warehouses'] 					= "Todos os Depósitos";
$lang['category'] 							= "Categoria";
$lang['product_cost'] 						= "Custo";
$lang['quantity'] 							= "Quantidade";
$lang['loading_data_from_server'] 			= "Carregando Dados do Servidor";
$lang['excel'] 								= "Excel";
$lang['print'] 								= "Imprimir";
$lang['ajax_error'] 						= "Ocorreu erro no Ajax, por favor, tente novamente.";
$lang['product_tax'] 						= "Impostos sobre Produto";
$lang['order_tax'] 							= "Impostos sobre o Pedido";
$lang['upload_file'] 						= "Enviar Arquivo";
$lang['download_sample_file'] 				= "Baixar Arquivo de Exemplo";
$lang['csv1'] 								= "A primeira linha no arquivo CSV baixado deve permanecer como está. Por favor, não alterar a ordem das colunas. ";
$lang['csv2'] 								= "A ordem da coluna correta é";
$lang['csv3'] 								= "&amp; você deve seguir isto. Se você estiver usando qualquer outra língua, em seguida, Inglês, por favor, verifique se o arquivo CSV é UTF-8 codificado e não salva com marca de ordem de byte (BOM) ";
$lang['import'] 							= "Importar";
$lang['note'] 								= "Nota";
$lang['grand_total'] 						= "Soma Total";
$lang['download_pdf'] 						= "Baixar como PDF";
$lang['no_zero_required'] 					= "O campo %s é necessária";
$lang['no_product_found'] 					= "Nenhum produto encontrado";
$lang['pending'] 							= "Pendente";
$lang['sent'] 								= "Enviado";
$lang['completed'] 							= "Concluído";
$lang['shipping'] 							= "Transporte";
$lang['add_product_to_order']				= "Por favor, adicione a lista de produtos ao Pedido";
$lang['order_items'] 						= "Itens do Pedido";
$lang['net_unit_cost'] 						= "Custo Unitário Líq.";
$lang['net_unit_price'] 					= "Preço Unitário Liq.";
$lang['expiry_date'] 						= "Data de Vencimento";
$lang['subtotal'] 							= "Sub-Total";
$lang['reset'] 								= "Limpar";
$lang['items'] 								= "Itens";
$lang['au_pr_name_tip'] 					= "Por favor, comece a digitar o código/nome para receber sugestões ou apenas scaneie um Cód. de Barra ";
$lang['no_match_found'] 					= "Nenhum resultado correspondente encontrado, por favor, tente novamente";
$lang['csv_file'] 							= "Arquivo CSV";
$lang['document'] 							= "Anexar Documentos";
$lang['product'] 							= "Produto";
$lang['user'] 								= "Usuário";
$lang['created_by'] 						= "Criado por";
$lang['loading_data'] 						= "Carregando dados da tabela no servidor";
$lang['tel'] 								= "Tel";
$lang['ref'] 								= "Ref.";
$lang['description'] 						= "Descrição";
$lang['code'] 								= "Cód.";
$lang['tax']								= "Imposto";
$lang['unit_price'] 						= "Preço Unitário";
$lang['discount'] 							= "Desconto";
$lang['order_discount'] 					= "Desconto do Pedido";
$lang['total_amount'] 						= "Valor Total";
$lang['download_excel'] 					= "Baixar como Excel";
$lang['subject'] 							= "Assunto";
$lang['cc'] 								= "CC";
$lang['bcc'] 								= "BCC";
$lang['message'] 							= "Mensagem";
$lang['show_bcc'] 							= "Mostra/Oculta BCC";
$lang['price'] 								= "Preço";
$lang['add_product_manually'] 				= "Adicionar Produto Manualmente";
$lang['currency'] 							= "Moeda";
$lang['product_discount'] 					= "Desconto no Produto";
$lang['email_sent'] 						= "E-mail enviado com sucesso!";
$lang['add_event'] 							= "Adicionar Evento";
$lang['add_modify_event'] 					= "Adicionar/Modificar o Evento";
$lang['adding'] 							= "Adicionar...";
$lang['delete'] 							= "Excluir";
$lang['deleting'] 							= "Excluindo...";
$lang['calendar_line'] 						= "Por favor, clique na data para adicionar/modificar o evento.";
$lang['discount_label'] 					= "Desconto (5/5%)";
$lang['product_expiry'] 					= "product_expiry";
$lang['unit'] 								= "Unid.";
$lang['cost'] 								= "Custo";
$lang['tax_method'] 						= "Método Fiscal";
$lang['inclusive'] 							= "Incluir";
$lang['exclusive'] 							= "Excluir";
$lang['expiry'] 							= "Validade";
$lang['customer_group'] 					= "Grupo de Clientes";
$lang['account'] 					= "Contas";
$lang['is_required'] 						= "é obrigatório";
$lang['form_action'] 						= "Formulário de Ação";
$lang['return_sales'] 						= "Estorno de Vendas";
$lang['list_return_sales'] 					= "Lista de Devoluções";
$lang['no_data_available'] 					= "Não há dados disponíveis";
$lang['disabled_in_demo']					= "Lamentamos, mas este recurso está desabilitado na versão demonstração.";
$lang['payment_reference_no']				= "Nº da Ref. de Pagamento";
$lang['gift_card_no']                       = "Nº do Vale-Compra";
$lang['paying_by']                          = "Pago por";
$lang['cash']                               = "Dinheiro";
$lang['gift_card']                          = "Vale-Compra";
$lang['CC']                                 = "Credit Card";
$lang['cheque']                             = "Cheque";
$lang['cc_no']                              = "Nº do Cartão de Crédito";
$lang['cc_holder']                          = "Nome do Titular";
$lang['card_type']                          = "Tipo do Cartão";
$lang['Visa']                               = "Visa";
$lang['MasterCard']                         = "MasterCard";
$lang['Amex']                               = "Amex";
$lang['Discover']                           = "Discover";
$lang['month']                              = "Mês";
$lang['year']                               = "Ano";
$lang['cvv2']                               = "CVV2";
$lang['cheque_no']                          = "Nº do Cheque";
$lang['Visa']                              	= "Visa";
$lang['MasterCard']                        	= "MasterCard";
$lang['Amex']                              	= "Amex";
$lang['Discover']                          	= "Discover";
$lang['send_email']							= "Enviar E-mail";
$lang['order_by']							= "Ordenado por";
$lang['updated_by']							= "Atualizado por";
$lang['update_at']							= "Atualizado em";
$lang['error_404']							= "404 - Página não encontrada ";
$lang['default_customer_group']				= "Grupo de Clientes Padrão";
$lang['pos_settings']						= "Config. do PDV";
$lang['pos_sales']							= "Vendas no PDV";
$lang['seller']                             = "Vendedor";
$lang['ip:']								= "IP:";
$lang['sp_tax']                             = "Impostos sobre Vendas";
$lang['pp_tax']                             = "Impostos sobre Compras";
$lang['overview_chart_heading']             = "Visão Geral em Gráficos do Estoque, incluindo as vendas mensais com imposto sobre os produtos e imposto de Pedidos(colunas), Compras(linha) e o valor atual da ação de custo e preço (pizza). Você pode salvar o gráfico como jpg, png e pdf.";
$lang['stock_value']                        = "Valor do Estoque";
$lang['stock_value_by_price']               = "Valor do Estoque pela Venda";
$lang['stock_value_by_cost']                = "Valor do Estoque pelo Custo";
$lang['sold']                               = "Vendido";
$lang['purchased']                          = "Comprado";
$lang['chart_lable_toggle']                 = "É possível alterar gráfico clicando na legenda do gráfico. Clique em qualquer legenda acima para mostrar / ocultá-lo no gráfico.";
$lang['register_report'] 					= "Relatório do Caixa";
$lang['sEmptyTable']                        = "Não há dados disponíveis na tabela";
$lang['upcoming_events']                    = "Próximos Eventos";
$lang['clear_ls']                           = "Limpar todos os dados gravados localmente";
$lang['clear']                              = "Limpar";
$lang['edit_order_discount']                = "Editar Desconto do Pedido";
$lang['product_variant']                    = "Variação do Produto";
$lang['product_variants']                   = "Variações do Produto";
$lang['prduct_not_found']                   = "Produto não encontrado";
$lang['list_open_registers']                = "Listar Caixas Abertos";
$lang['delivery']                           = "Entrega";
$lang['serial_no']                          = "Nº Serial";
$lang['logo']                               = "Logo";
$lang['attachment']                         = "Anexo";
$lang['noattachment']                         = "Conta Sem Anexo";
$lang['balance']                            = "Média";
$lang['nothing_found']                      = "Nenhum resultado foi encontrado";
$lang['db_restored']                        = "O Banco de Dados foi Restaurado com Sucesso.";
$lang['backups']                            = "Backups";
$lang['best_seller']                        = "Mais Vendidos";
$lang['chart']                              = "Gráfico";
$lang['received']                           = "Recebido";
$lang['returned']                           = "Reembolsado";
$lang['award_points']                       = 'Pontos de Volume';
$lang['expenses']                           = "Despesas";
$lang['add_expense']                        = "Adicionar Despesa";
$lang['other']                              = "Outro";
$lang['none']                               = "nenhum";
$lang['calculator']                         = "Calculadora";
$lang['updates']                            = "Atualizações";
$lang['update_available']                   = "Nova atualização disponível, por favor atualize agora.";
$lang['please_select_customer_warehouse']   = "Por favor selecione cliente/depósito";
$lang['variants']                           = "Variações";
$lang['add_sale_by_csv']                    = "Adicionar Vendas por CSV";
$lang['categories_report']                  = "Relatório das Categorias";
$lang['adjust_quantity']                    = "Ajustar Qtd.";
$lang['quantity_adjustments']               = "Ajustes de Quantidades";
$lang['partial']                            = "Parcial";
$lang['unexpected_value']                   = "Valor inesperado fornecido!";
$lang['select_above']                       = "Por favor, selecione acima primeiro";
$lang['no_user_selected']                   = "Nenhum usuário selecionado, por favor selecione pelo menos um usuário";
$lang['sale_details']                       = "Detalhes da Venda";
$lang['due'] 								= "À Pagar";
$lang['ordered'] 							= "Pedido";
$lang['profit'] 						    = "Lucro";
$lang['unit_and_net_tip'] 			        = "Calculado com unidade (com impostos) e líquido (sem impostos) ex.<strong>unidade (líquido)</strong> para todas as vendas";
$lang['expiry_alerts'] 				        = "Alerta de Vencimento";
$lang['quantity_alerts'] 				    = "Alerta de Estoque";
$lang['products_sale']                      = "Lucro do Produto";
$lang['products_cost']                      = "Custo do Produto";
$lang['day_profit']                         = "Ganhos e/ou Percas do Dia";
$lang['get_day_profit']                     = "Você pode clicar na data para obter o relatório de ganhos e/ou perdas do dia.";
$lang['combine_to_pdf']                     = "Exportar em pdf";
$lang['print_barcode_label']                = "Imprimir Código/Etiquetas";
$lang['list_gift_cards']                    = "Cupons de Desconto";
$lang['today_profit']                       = "Lucro do Dia";
$lang['adjustments']                        = "Ajustes";
$lang['download_xls']                       = "Baixar em XLS";
$lang['browse']                             = "Buscar ...";
$lang['transferring']                       = "Transferindo";
$lang['supplier_part_no']                   = "Código do Fornecedor";
$lang['deposit']                            = "Depósito";
$lang['ppp']                                = "Paypal Pro";
$lang['stripe']                             = "Stripe";
$lang['amount_greater_than_deposit']        = "Valor é maior do que o depósito do cliente, por favor tente novamente com valor inferior ao depósito do cliente.";
$lang['stamp_sign']                         = "Carimbo &amp; Assinatura";
$lang['product_option']                     = "Variação do Produto";
$lang['Cheque']                             = "Cheque";
$lang['sale_reference']                     = "Ref. da Venda";
$lang['surcharges']                         = "Preços Excessivos";
$lang['please_wait']                        = "Por favor Aguarde...";
$lang['list_expenses']                      = "Listar Despesas";
$lang['deposit']                            = "Depósito";
$lang['deposit_amount']                     = "Valor do Depósito";
$lang['return_purchases']                   = "Compras Devolvidas";
$lang['list_return_purchases']              = "Devoluções de Compras";
$lang['expense_categories']                 = "Categorias de Despesas";
$lang['finance_categories']                 = "Categorias Financeiras";
$lang['authorize']                          = "Authorize.net";
$lang['expenses_report']            		= "Relatório de Despesas";
$lang['income_categories']                 = "Categorias de Receitas";
$lang['edit_event']                         = "Editar Evento";
$lang['title']                              = "Título";
$lang['event_error']                        = "Título & Início são obrigatórios";
$lang['start']                              = "Inicio";
$lang['end']                                = "Término";
$lang['event_added']                        = "Evento Adicionado com Sucesso";
$lang['event_updated']                      = "Evento Atualizado com Sucesso";
$lang['event_deleted']                      = "Evento Excluído com Sucesso";
$lang['event_color']                        = "Cor do Evento";
$lang['toggle_alignment']                   = "Mudar Alinhamento";
$lang['images_location_tip']                = "As imagens devem ser enviadas na pasta <strong>uploads</strong>.";
$lang['this_sale']                          = "Esta Venda";
$lang['return_ref']                         = "Ref. de Devolução";
$lang['return_total']                       = "Total de Devolução";
$lang['daily_purchases']                    = "Compras Diárias";
$lang['monthly_purchases']                  = "Compras Mensais";
$lang['please_select_these_before_adding_product'] = "Por favor, selecione estes antes de adicionar qualquer produto";

$lang['produt_details']                       = "Detalhes do Produto";

