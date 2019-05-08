<?php
$participantes_lista = $_POST["nome"]; 




//$email = $_POST["email"];  
// imprime na tela em formato json 
//echo json_encode( array( "participantes" => $participantes) );  
?>

   <?php
                                           /*
                                            * // print_r($participantes_lista);exit;
                                            foreach ($participantes_lista as $participante) {
                                                
                                                $cadastro_usuario =  $this->site->getUser($participante);
                                                
                                                if($participante){
                                                
                                                    
                                                 $lista_check_participantes = "<input name='participantes[]' type='checkbox' checked value='$cadastro_usuario->id'> $cadastro_usuario->first_name.' '.$cadastro_usuario->last_name "; 
                                                 //
                                                echo $lista_check_participantes; 
                                                }
                                            }
                                            */
                                            
                                            
                                            
                                            echo json_encode( array(  "nome" => $participantes_lista ) );  
                                            ?>