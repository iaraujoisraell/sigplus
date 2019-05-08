<?php
        // Usuário do banco oracle.
        $oracle_usuario = "mto"; 
        //Senha do usuário no banco de dados.
        $oracle_senha = "migracao3"; 
        
        $oracle_bd = "(DESCRIPTION =
                    (ADDRESS = (PROTOCOL = TCP)(HOST = 40.40.0.56)(PORT = 1521))
                     (CONNECT_DATA =(SERVER = DEDICATED)
                                    (SERVICE_NAME = LOCAL)
                     )
                  )"; 
        /*
        $oracle_bd = "(DESCRIPTION=
                  (ADDRESS_LIST=
                    (ADDRESS=(PROTOCOL=TCP) 
                      (HOST=srv-oda1-scan)(PORT=1521)
                    )
                  )
                  (CONNECT_DATA=(SERVICE_NAME=tasy_prd_prim_db))
             )"; 
         * 
         */

        // Aqui, nós validamos se a conexão foi feita com sucesso ou não.
        if ($ora_conexao = OCILogon($oracle_usuario,$oracle_senha,$oracle_bd) )   
          echo ""; 
        else														   
          echo "Erro na conexão com o Oracle.";	
        ?>