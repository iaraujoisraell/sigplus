<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>  
<title>Chat - Customer Module</title>  
<link type="text/css" rel="stylesheet" href="style.css" />  
</head>  

<?php  
    session_start();  

    function loginForm(){  
        echo' 
        <div id="loginform"> 
            <form action="index.php" method="post">  
                <label for="nome">Name:</label> 
                <input type="text" name="nome" id="nome" /> 
                <input type="submit" name="entrar" id="entrar" value="Entrar" /> 
            </form> 
        </div> 
        ';  
    }

    if(isset($_POST['entrar'])){  
        if($_POST['nome'] != ""){  
            $_SESSION['nome'] = stripslashes(htmlspecialchars($_POST['nome']));
            
            $fp = fopen("log.html", 'a');  // "a" abrir para ler e escrever
            fwrite($fp, "<div class='msgln'><i>". $_SESSION['nome'] ." entrou do chat.</i><br></div>");  
            fclose($fp);
        }  
        else{  
            echo '<span class="error">Escolha um nome, antes de entrar!</span>';  
        }  
    }  
    
    if(isset($_GET['sair'])){ 
        $fp = fopen("log.html", 'a');  // "a" abrir para ler e escrever
        fwrite($fp, "<div class='msgln'><i>". $_SESSION['nome'] ." saiu do chat.</i><br></div>");  
        fclose($fp);  

        session_destroy();  
        header("Location: index.php");
    }
    
    if(!isset($_SESSION['nome'])){  
        loginForm();  
    }else{  
?>  
    <div id="wrapper">  
        <div id="menu">  
            <p class="welcome">Bem-vindo <b><?php echo $_SESSION['nome']; ?></b> | <a class="logout" id="sair" href="#">Sair</a></p>  
            <div style="clear:both"></div>  
        </div>      
        <div id="chatbox">
            <?php  
                if(file_exists("log.html") && filesize("log.html") > 0){  
                    $handle = fopen("log.html", "r");  
                    $contents = fread($handle, filesize("log.html"));  
                    fclose($handle);  

                    echo $contents;  
                }  
            ?>
        </div>  

        <form name="message" action="">  
            <input name="usermsg" type="text" id="usermsg" size="63" />  
            <input name="submitmsg" type="submit"  id="submitmsg" value="Enviar!" />  
        </form>  
    </div>  
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>  
    <script type="text/javascript">  
        // jQuery Document  
        $(document).ready(function(){
            $("#sair").click(function(){  
                var sair = confirm("Tem a certeza que quer sair?");  
                if(sair==true){window.location = 'index.php?sair=true';}        
            });
            
            $("#submitmsg").click(function(){     
                var clientmsg = $("#usermsg").val();  
                $.post("post.php", {text: clientmsg});                
                $("#usermsg").attr("value", "");  
                return false;  
            });
            
            function loadLog(){
                var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height before the request
                $.ajax({  
                    url: "log.html",  
                    cache: false,  
                    success: function(html){          
                        $("#chatbox").html(html); //Insert chat log into the #chatbox div
                        
                        //Auto-scroll             
                        var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height after the request  
                        if(newscrollHeight > oldscrollHeight){  
                            $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div  
                        }
                    }  
                });  
            }
            setInterval (loadLog, 2500);    //Reload file every 2500 ms or x ms if you wish to change the second parameter
        });  
    </script>  
<?php  
    }  
?>  

</body>  
</html>  