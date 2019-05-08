<?php
        // Usuário do banco oracle.
        $oracle_usuario = "consulta"; 
        //Senha do usuário no banco de dados.
        $oracle_senha = "consulta2009"; 


        $oracle_bd = "(DESCRIPTION=
                  (ADDRESS_LIST=
                    (ADDRESS=(PROTOCOL=TCP) 
                      (HOST=srv-oda1-scan)(PORT=1521)
                    )
                  )
                  (CONNECT_DATA=(SERVICE_NAME=tasy_prd_prim_db))
             )"; 

        // Aqui, nós validamos se a conexão foi feita com sucesso ou não.
        if ($ora_conexao = OCILogon($oracle_usuario,$oracle_senha,$oracle_bd) )   
          echo ""; 
        else														   
          echo "Erro na conexão com o Oracle.";	
        ?>