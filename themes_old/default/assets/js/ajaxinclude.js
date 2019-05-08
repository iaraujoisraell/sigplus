//Cria√ß√£o do objeto XML HTTP necess√°rio para uso do ajax
var http = false;

if(navigator.appName == "Microsoft Internet Explorer")
{
   http = new ActiveXObject("Microsoft.XMLHTTP");
}
else
{
   http = new XMLHttpRequest();
}

function fn_CarregarPagina(strURL,objContainer,bl_ExibirMensagem)
{
  
         
   document.getElementById(objContainer).innerHTML ='';
   if((strURL=='')||(typeof(strURL)=='undefined')||(strURL=='undefined'))
      {
         document.getElementById(objContainer).innerHTML = 'P&aacute;gina n&atilde;o encontrada.';
      }
   else if (bl_ExibirMensagem)
     {
        fn_PainelMensagem("<div id='processando' class='procbox'>\n\
                               <center>\n\
                                    <table align='center'>\n\
                                       <tr>\n\
                                           <td align='center'>\n\
                                               <img src='imagens/carregando.gif' border='0' width='35' height='35' >\n\
                                           </td>\n\
                                           <td style='font-size: 15px; color: #4169E1' align='center'>\n\
                                               <strong>\n\
                                                   &ensp;&ensp;Processando...\n\
                                               </strong>\n\
                                           </td>\n\
                                       </tr>\n\
                                    </table>\n\
                                </center>\n\
                           </div>",objContainer);
     }

   http.open("GET", strURL, true);

   http.onreadystatechange=function()
   {
      if(http.readyState == 4)
      {
         var tx_Retorno=http.responseText;
         if(bl_ExibirMensagem)
         {
            fn_PainelMensagemFechar()
            document.getElementById(objContainer).innerHTML = tx_Retorno;
         }
         else
         {
            document.getElementById(objContainer).innerHTML = tx_Retorno;
         }
         fn_PegarScripts(tx_Retorno);
      }
   }
   http.send(null);
}

function insertAfter(parent, node, referenceNode) {
  parent.insertBefore(node, referenceNode.nextSibling);
}

function fn_RetornarPagina(strURL,strContainer,strMatriz)
{
   if((strURL=='')||(typeof(strURL)=='undefined')||(strURL=='undefined'))
      {
         return 'P&aacute;gina n&atilde;o encontrada.';
      }

   http.open("GET", strURL, true);

   http.onreadystatechange=function()
   {
      if(http.readyState == 4)
      {
         var tx_Retorno=http.responseText;
         fn_PegarScripts(tx_Retorno);
         strModelo=tx_Retorno;

         //*********************************************************************************************
         //FunÁ„o adicionar campos
         var strMarcador=/icontador/g;

         strModelo=strModelo.replace(strMarcador, intIndice);
   //      alert(document.getElementById(strModeloCampos));
         var obj_Container=document.getElementById(strContainer);
         var objFragmento=document.createDocumentFragment();
         var obj_dvDinamico=document.createElement('div');
         obj_dvDinamico.setAttribute('id',strContainer+intIndice);
         obj_dvDinamico.innerHTML=strModelo;
         objFragmento.appendChild(obj_dvDinamico);
         obj_Container.insertBefore(objFragmento,obj_Container.nextChild);

         intCampos++;
         //**************************************************************************************
         return;
      }
   }
   http.send(null);
}