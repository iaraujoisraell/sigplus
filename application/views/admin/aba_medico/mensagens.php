<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/aba_medico/head');?> 
<head>
    <br>
     <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <section class="content-header">
                        <h1>
                           Caixa de mensagens
                        </h1>
                        <ol class="breadcrumb">
                          <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-dashboard"></i> Agenda de Médicos</a></li>
                           <li class="active">Caixa de mensagens</li>
                        </ol>
                    </section>
                
                </div>
            </div>
     </div>   
</head> 
<body>

 
    <div class="content" id="trocar">

               
                <div class="row">
                    <div class="col-lg-12">

                        <div class="portlet portlet-default">
                            <div class="portlet-body">
                                      <nav class="navbar mailbox-topnav" role="navigation">
                                    <!-- Brand and toggle get grouped for better mobile display -->
                                    <div class="navbar-header">
                                        <a class="navbar-brand" href="mailbox.html"><i class="fa fa-inbox"></i> Inbox</a>
                                    </div>

                                    <!-- Collect the nav links, forms, and other content for toggling -->
                                    <div class="mailbox-nav">
                                        <ul class="nav navbar-nav button-tooltips">
                                            <li class="checkall">
                                                <input type="checkbox" id="selectall" data-toggle="tooltip" data-placement="bottom" title="Select All">
                                            </li>
                                            <li class="message-actions">
                                                <div class="btn-group navbar-btn">
                                                    <button type="button" class="btn btn-white" data-toggle="tooltip" data-placement="bottom" title="Archive"><i class="fa fa-archive"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-white" data-toggle="tooltip" data-placement="bottom" title="Mark as Important"><i class="fa fa-exclamation-circle"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-white" data-toggle="tooltip" data-placement="bottom" title="Trash"><i class="fa fa-trash-o"></i>
                                                    </button>
                                                </div>
                                            </li>
                                            <li class="dropdown message-label">
                                                <button type="button" class="btn btn-white navbar-btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-tag"></i>  <i class="fa fa-caret-down text-muted"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#"><i class="fa fa-square text-green"></i> Purchase Orders</a>
                                                    </li>
                                                    <li><a href="#"><i class="fa fa-square text-orange"></i> Current Projects</a>
                                                    </li>
                                                    <li><a href="#"><i class="fa fa-square text-purple"></i> Work Groups</a>
                                                    </li>
                                                    <li><a href="#"><i class="fa fa-square text-blue"></i> Personal</a>
                                                    </li>
                                                    <li><a href="#"><i class="fa fa-square-o"></i> None</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                        <form class="navbar-form navbar-right visible-lg" role="search">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Search Mail...">
                                            </div>
                                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i>
                                            </button>
                                        </form>
                                    </div>
                                </nav>

                                <div id="mailbox">

                                    <ul class="nav nav-pills nav-stacked mailbox-sidenav">
                                        <li><a class="btn btn-white" href="compose-message.html"><i class="fa fa-edit"></i> Compose Message</a>
                                        </li>
                                        <li class="nav-divider"></li>
                                        <li class="mailbox-menu-title text-muted">Folder</li>
                                        <li class="active"><a href="#">Inbox (15)</a>
                                        </li>
                                        <li><a href="#">Sent</a>
                                        </li>
                                        <li><a href="#">Drafts</a>
                                        </li>
                                        <li><a href="#">Spam</a>
                                        </li>
                                        <li><a href="#">Trash</a>
                                        </li>
                                        <li class="nav-divider"></li>
                                        <li class="mailbox-menu-title text-muted">Labels</li>
                                        <li><a href="#"><i class="fa fa-square text-green"></i> Purchase Orders</a>
                                        </li>
                                        <li><a href="#"><i class="fa fa-square text-orange"></i> Current Projects</a>
                                        </li>
                                        <li><a href="#"><i class="fa fa-square text-purple"></i> Work Groups</a>
                                        </li>
                                        <li><a href="#"><i class="fa fa-square text-blue"></i> Personal</a>
                                        </li>
                                        <li><a href="#"><i class="fa fa-plus"></i> Create New Label</a>
                                        </li>
                                    </ul>

                                    <div id="mailbox-wrapper">

                                        <div class="table-responsive mailbox-messages">
                                            <table class="table table-bordered table-striped table-hover">
                                                <tbody>
                                                    <tr class="unread-message clickableRow">
                                                        <td class="checkbox-col">
                                                            <input type="checkbox" class="selectedId" name="selectedId">
                                                        </td>
                                                        <td class="from-col">Jane Smith</td>
                                                        <td class="msg-col">
                                                            <span class="label green">Orders</span> Order Status Update: Order #231
                                                            <span class="text-muted">- Hi again! I wanted to let you know that the order...</span>
                                                        </td>
                                                        <td class="date-col"><i class="fa fa-paperclip"></i> 1/1/14</td>
                                                    </tr>
                                                    <tr class="unread-message clickableRow">
                                                        <td class="checkbox-col">
                                                            <input type="checkbox" class="selectedId" name="selectedId">
                                                        </td>
                                                        <td class="from-col">Roddy Auston</td>
                                                        <td>
                                                            <span class="label purple">Work</span> Thanks for the information!
                                                            <span class="text-muted">- Thanks again for the info! If you need anything from...</span>
                                                        </td>
                                                        <td class="date-col">1/1/14</td>
                                                    </tr>
                                                    <tr class="unread-message clickableRow">
                                                        <td class="checkbox-col">
                                                            <input type="checkbox" class="selectedId" name="selectedId">
                                                        </td>
                                                        <td class="from-col"><i class="fa fa-exclamation-circle text-orange"></i> Stacy Gibson</td>
                                                        <td>
                                                            <span class="label orange">Projects</span> Order number for new client
                                                            <span class="text-muted">- Hey, what was the purchase order number for the...</span>
                                                        </td>
                                                        <td class="date-col">1/1/14</td>
                                                    </tr>
                                                    <tr class="unread-message clickableRow">
                                                        <td class="checkbox-col">
                                                            <input type="checkbox" class="selectedId" name="selectedId">
                                                        </td>
                                                        <td class="from-col">Jeffery Cortez</td>
                                                        <td>
                                                            <span class="label blue">Personal</span> Check out this video.
                                                            <span class="text-muted">- Check out this video I found the other day, it's...</span>
                                                        </td>
                                                        <td class="date-col"><i class="fa fa-paperclip"></i> 1/1/14</td>
                                                    </tr>
                                                    <tr class="clickableRow">
                                                        <td class="checkbox-col">
                                                            <input type="checkbox" class="selectedId" name="selectedId">
                                                        </td>
                                                        <td class="from-col">Jane Smith</td>
                                                        <td>
                                                            <span class="label green">Orders</span> Order Status Update: Order #219
                                                            <span class="text-muted">- This order has been filled and is ready to ship...</span>
                                                        </td>
                                                        <td class="date-col"><i class="fa fa-paperclip"></i> 1/1/14</td>
                                                    </tr>
                                                    <tr class="clickableRow">
                                                        <td class="checkbox-col">
                                                            <input type="checkbox" class="selectedId" name="selectedId">
                                                        </td>
                                                        <td class="from-col">FlexCorp Marketing</td>
                                                        <td>Monthly Newsletter from FlexCorp - This Month's Trends
                                                            <span class="text-muted">- FlexCo has some great updates for you in this...</span>
                                                        </td>
                                                        <td class="date-col">1/1/14</td>
                                                    </tr>
                                                    <tr class="clickableRow">
                                                        <td class="checkbox-col">
                                                            <input type="checkbox" class="selectedId" name="selectedId">
                                                        </td>
                                                        <td class="from-col">Jeffery Cortez</td>
                                                        <td>
                                                            <span class="label blue">Personal</span> FWD: Best Cat Videos of 2013
                                                            <span class="text-muted">- These are some of the best cat videos I have ever...</span>
                                                        </td>
                                                        <td class="date-col">1/1/14</td>
                                                    </tr>
                                                    <tr class="clickableRow">
                                                        <td class="checkbox-col">
                                                            <input type="checkbox" class="selectedId" name="selectedId">
                                                        </td>
                                                        <td class="from-col">Mom</td>
                                                        <td>
                                                            <span class="label blue">Personal</span> Is your phone on?
                                                            <span class="text-muted">- I tried to call you this morning and your phone wasn't on. I know...</span>
                                                        </td>
                                                        <td class="date-col">1/1/14</td>
                                                    </tr>
                                                    <tr class="clickableRow">
                                                        <td class="checkbox-col">
                                                            <input type="checkbox" class="selectedId" name="selectedId">
                                                        </td>
                                                        <td class="from-col"><i class="fa fa-warning text-red"></i> System Warning</td>
                                                        <td>Server #4 Crashed
                                                            <span class="text-muted">- This is an automated message notifying you that there is a problem with...</span>
                                                        </td>
                                                        <td class="date-col">1/1/14</td>
                                                    </tr>
                                                    <tr class="clickableRow">
                                                        <td class="checkbox-col">
                                                            <input type="checkbox" class="selectedId" name="selectedId">
                                                        </td>
                                                        <td class="from-col">System Report</td>
                                                        <td>Daily Report for 12/31/13
                                                            <span class="text-muted">- Daily traffic and user breakdown for 12/31/13. To view the report, open...</span>
                                                        </td>
                                                        <td class="date-col"><i class="fa fa-paperclip"></i> 12/31/13</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <ul class="list-inline pull-right">
                                            <li><strong>1-10 of 1,392</strong>
                                            </li>
                                            <li>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-white"><i class="fa fa-chevron-left"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-white"><i class="fa fa-chevron-right"></i>
                                                    </button>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- /.portlet-body -->
                        </div>
                        <!-- /.portlet -->


                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->

</div>
    <!-- GLOBAL SCRIPTS -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/js/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/s/plugins/popupoverlay/jquery.popupoverlay.js"></script>
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/js/plugins/popupoverlay/defaults.js"></script>
    <!-- Logout Notification Box -->
    <div id="logout">
     
    </div>
    <!-- /#logout -->
    <!-- Logout Notification jQuery -->
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/popupoverlay/logout.js"></script>
    <!-- HISRC Retina Images -->
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/hisrc/hisrc.js"></script>

    <!-- PAGE LEVEL PLUGIN SCRIPTS -->
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/demo/mailbox-demo.js"></script>

    <!-- THEME SCRIPTS -->
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/flex.js"></script>

</body>

</html>
