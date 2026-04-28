<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if(isset($client)){ ?>
<h4 class="customer-profile-group-heading"><?php echo 'ATENDIMENTOS DO PACIENTE'; ?></h4>
<div class="col-md-12">

 <div class="clearfix"></div>

<div class="mtop15">
    <table class="table  scroll-responsive" >
        <thead>
            <tr>
                <th>
                    <?php echo 'Data'; ?>
                </th>
                <th >
                    <?php echo 'Tipo'; ?>
                </th>
                <th >
                    <?php echo 'Convênio'; ?>
                </th>
                <th >
                    <?php echo 'Médico(a)'; ?>
                </th>
                <th >
                    <?php echo 'Fatura'; ?>
                </th>
                <th >
                    <?php echo 'Status'; ?>
                </th>
                
                
                
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($user_visitas as $note){ ?>
            <tr>
            <td >
             <?php  if(!empty($note['date'])){ ?>
               <span data-toggle="tooltip" data-title="Data da consulta">
                  <i class="fa fa-calendar text-success font-medium valign" aria-hidden="true"></i>
              </span>
              <?php } ?>
              <?php echo date("d/m/Y", strtotime($note[ 'date'])).' '.$note[ 'start_hour'];   ?>
            </td>
            
            <td>
                <?php echo $note['type']; ?>
            </td>
            
            <td>
                <?php echo $note['convenio']; ?>
            </td>
            
            <td>
                <?php echo $note['nome_profissional']; ?>
            </td>
            <td>
                <?php
                $invoice_id = $note['fatura'];
                $status = format_invoice_status($invoice_status);
                $numberOutput = '<a href="' . admin_url('invoices/list_invoices/' . $invoice_id) . '" target="_blank">' . format_invoice_number($invoice_id) . '</a>';
                echo $numberOutput; ?>
            </td>
            <td>
                <?php
                $status = $note['finished'];
                
                if($status == 1){
                    $label_status = "<label class='label label-success'>ATENDIDO</label>";
                }else{
                    $label_status = "<label class='label label-warning'>AGENDADO</label>";
                }
                
                echo $label_status; ?>
            </td>
        
    </tr>
    <?php } ?>
</tbody>
</table>
</div>

</div>
<?php } ?>
