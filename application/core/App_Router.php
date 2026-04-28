<?php

defined('BASEPATH') or exit('No direct script access allowed');

/* load the MX_Router class */
require APPPATH . 'third_party/MX/Router.php';

class App_Router extends MX_Router
{
}
//saas:start:init.txt
//dont remove/change above line

require_once(FCPATH.'modules/saas/core/SaasInit.php');

//dont remove/change below line
//saas:end:init.txt