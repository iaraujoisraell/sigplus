<?php
class Verification extends Service { private $config; public function onRegister() { parent::onRegister(); $this->config = $this->get('config'); } 

public function verifyInstallation() 
{ 
    $sp2a5233 = $this->config->data['services']['verify']; 
    $sp1cf959 = $sp2a5233['code'];
    $spa89a73 = $sp2a5233['token']; 
    return true;
} 
     
     public function verifyCodeToken($sp1cf959, $spa89a73) { if (is_array($sp1cf959)) { return !empty($sp1cf959[0]) && !empty($sp1cf959[1]) && !empty($spa89a73) && sha1(md5($sp1cf959[0] . $sp1cf959[1])) == $spa89a73; } return !empty($sp1cf959) && !empty($spa89a73) && sha1(md5($sp1cf959)) == $spa89a73; } public function updateInstallation($sp1cf959, $spa89a73) { $this->config->updateParameters(array('services' => array('verify' => array('code' => $sp1cf959, 'token' => $spa89a73)))); } }