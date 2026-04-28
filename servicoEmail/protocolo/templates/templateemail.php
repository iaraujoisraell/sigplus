
<?php 

function templateEmail($conteudo){
	
$conteudo = nl2br($conteudo); 

$html = '
	<div width="100%" style="margin:0;padding:0!important" bgcolor="#E5E5E5">
	   <center style="width:100%;background-color:#e5e5e5">
		  <div style="max-width:600px;margin:0 auto" class="m_2893092752679934296email-container">
			 <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:auto" bgcolor="#fff">
				<tbody>
				   <tr>
					  <td height="30"></td>
				   </tr>
				   <tr>
					  <td align="center" style="font-family:Calibri,Arial,sans-serif;font-size:11px;color:#6a6e81;text-align:center">
						 <img src="http://unimedweb.unimedmanaus.com.br/templates/logo.png" width="140" alt="" class="CToWUd">
					  </td>
				   </tr>
				   <tr>
					  <td height="45"></td>
				   </tr>
				   <tr>
					  <td>
						 <table style="margin: 0 auto !important;" role="presentation" cellspacing="0" cellpadding="0" border="0" width="90%">
							<tbody>
							   <tr>
								  <td style="font-family:sans-serif;font-size:14px;line-height:20px;color:#6a6e81"></td>
							   </tr>
				
							   <tr>
								  <td style="padding:10px 0px;text-align:left;width:479px">
									 <p style="font-family:Calibri,Arial,sans-serif;font-size:14px;color:#58595b;text-align:left">'.$conteudo.'</p>                                 
								  </td>
							   </tr>
							</tbody>
						 </table>
					  </td>
				   </tr>
				   <tr>
					  <td width="510" height="30"></td>
				   </tr>
				   <tr>
					  <td style="background-color:#ffffff">
						 <table style="margin: 0 auto !important;" role="presentation" cellspacing="0" cellpadding="0" border="0" width="90%">
							<tbody>
							   <tr>
								  <td style="font-family:sans-serif;font-size:14px;line-height:20px;color:#6a6e81">
									 <p style="color:#00995D">
										<strong>Abraços da equipe Unimed Manaus.</strong>
									 </p>
								  </td>
							   </tr>
							</tbody>
						 </table>
					  </td>
				   </tr>
				</tbody>
			 </table>
			 <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#fff">
				<tbody>
				   <tr>
					  <td height="20"></td>
				   </tr>
				   <tr>
					  <td>
						 <table width="196" cellspacing="0" cellpadding="0" border="0" align="center">
							<tbody>
							   <tr>
								  <td style="font-family:Calibri,Arial,sans-serif;font-size:12px;color:#ccc;text-align:center" width="25">
									 <a href="https://www.facebook.com/unimedmanausoficial" title="Facebook" style="font-family:Calibri,Arial,sans-serif;font-size:12px;color:#ccc;text-align:center;text-decoration:none" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://www.facebook.com/bancointer&amp;source=gmail&amp;ust=1601040105717000&amp;usg=AFQjCNGIdLuykhHUn9OLGTlJ0zPxTuWlww"> 
									 <img src="https://ci5.googleusercontent.com/proxy/VxV8YmlTvBnfcLsD1f7Ab8it8JXd0jcq-rAcLe6WcN5sKIl-b0qRiiyGYiFHyl6wH_yIdaY6wfl4BjZCXsGa1q-Nt_6OJnNN7lw_MnVacHAX59oz5kla7EQ0ygFqZkIDEcC2_9uOUqb53f9KPmJhI13-kd1PIzjDJA=s0-d-e1-ft#http://marketing.bancointer.com.br/comunicacao/e-mail-mkt-institucional/imagens/icon-facebook-gray.png" alt="Facebook" width="25" height="25" border="0" class="CToWUd">
									 </a>
								  </td>
								  <td width="8"></td>
								  <td style="font-family:Calibri,Arial,sans-serif;font-size:12px;color:#ccc;text-align:center" width="25">
									 <a href="https://www.instagram.com/unimedmanaus_" title="Instagram" style="font-family:Calibri,Arial,sans-serif;font-size:12px;color:#ccc;text-align:center;text-decoration:none" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://www.instagram.com/bancointer&amp;source=gmail&amp;ust=1601040105717000&amp;usg=AFQjCNHzh6Tld5m2qpUhs4mozr-8-7Q-mA">
									 <img src="https://ci5.googleusercontent.com/proxy/Rb7EQqIT-Ab13kCYHca49JOs710Bkk7Ma0QKyKjCzpRtGvI-2n1mPbEHjrvVK3E3Sk0RM0PQxbaaDY7aCat79mVFUCwxYnB333_pKvgew0REfICoINAib9LsgweimyI90GEI2C5p3gHkmipsfi0As5CxC44ORWQfIUY=s0-d-e1-ft#http://marketing.bancointer.com.br/comunicacao/e-mail-mkt-institucional/imagens/icon-instagram-gray.png" alt="Instagram" width="25" height="25" border="0" class="CToWUd">
									 </a>
								  </td>
								  <td width="8"></td>
								  <td style="font-family:Calibri,Arial,sans-serif;font-size:12px;color:#ccc;text-align:center" width="25">
									 <a href="https://www.linkedin.com/company/unimed-manaus" title="Siga-nos no Linkedin" style="font-family:Calibri,Arial,sans-serif;font-size:12px;color:#ccc;text-align:center;text-decoration:none" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://www.linkedin.com/company/banco-inter/&amp;source=gmail&amp;ust=1601040105717000&amp;usg=AFQjCNHk2i_9pXuQ6E8zeePnWco2v6OWqA">
									 <img src="https://ci3.googleusercontent.com/proxy/sAIn3PEtfGzbqaEut5mk9Ra5FExc6_O8uajOSnJZho78V_-y0Cr19-i0j0x5KzJ1971iqu965XypKA3Voc0CQypl5urUzIEINsAP_yJP2cB5rNjKVywqaKTkqhBa2blMN7KyqSOJcqQukg0X2-GAxBqnzaLRFICfsQ=s0-d-e1-ft#http://marketing.bancointer.com.br/comunicacao/e-mail-mkt-institucional/imagens/icon-linkedin-gray.png" alt="Linkedin" width="25" height="25" border="0" class="CToWUd">
									 </a>
								  </td>
								  <td width="8"></td>
								  <td style="font-family:Calibri,Arial,sans-serif;font-size:12px;color:#ccc;text-align:center" width="25">
									 <a href="https://www.youtube.com/c/UnimedManausAM/featured" title="Youtube" style="font-family:Calibri,Arial,sans-serif;font-size:12px;color:#ccc;text-align:center;text-decoration:none" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://www.youtube.com/c/BancoInter&amp;source=gmail&amp;ust=1601040105717000&amp;usg=AFQjCNG1V93m3-Kvq9q8SL7cinkBWEuSGA">
									 <img src="https://ci5.googleusercontent.com/proxy/kLV52Af1_HihJMgKHv2qQltIwmrqpdR64Xsyeuy4jh2Nig-HXC_gikfTaOFiuSF5aGQs2BU4W447nlg-GBl7WIaxKFiX17R9vNqmgI1cP3b77RBmhLnkwz7FEK9QuzC9NNiBdg-weuRCcY5BdXmkKivaHR7S0dR-=s0-d-e1-ft#http://marketing.bancointer.com.br/comunicacao/e-mail-mkt-institucional/imagens/icon-youtube-gray.png" alt="Youtube" width="25" height="25" border="0" class="CToWUd">
									 </a>
								  </td>
								  <td width="8"></td>
								  <td style="font-family:Calibri,Arial,sans-serif;font-size:12px;color:#ccc;text-align:center" width="25">
									 <a href="https://twitter.com/Unimedam" title="Twitter" style="font-family:Calibri,Arial,sans-serif;font-size:12px;color:#ccc;text-align:center;text-decoration:none" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://twitter.com/Bancointer&amp;source=gmail&amp;ust=1601040105717000&amp;usg=AFQjCNGx_23TRzqBjV9t2uHRAiYeMFRslw">
									 <img src="https://ci6.googleusercontent.com/proxy/LVBP3KC-D9fCGhGXQ2jKOFz7XsW1HqputcWhILWF5GiwkezfZurSC8J6yDHp6OsTxJU6IIZl0fw5U_8isut_rrbEBHGBC-IVI20GlQZIYcPojdxBpXLmu2UwE3Fr17c7W1DcUHHXiXfT-yxeLE9Cyyz03P1iJCix=s0-d-e1-ft#http://marketing.bancointer.com.br/comunicacao/e-mail-mkt-institucional/imagens/icon-twitter-gray.png" alt="Twitter" width="25" height="25" border="0" class="CToWUd">
									 </a>
								  </td>
								  <td width="8"></td>
							   </tr>
							</tbody>
						 </table>
					  </td>
				   </tr>
				   <tr>
					  <td height="8" align="center"></td>
				   </tr>
				   <tr>
					  <td style="padding:5px" align="center">
						 <a href="https://www.unimedmanaus.com.br/" title="" style="font-family:Calibri,Arial,sans-serif;font-size:18px;color:#00995D;text-align:center;text-decoration:none" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://www.unimedmanaus.com.br/&amp;source=gmail&amp;ust=1601040105718000&amp;usg=AFQjCNGC6J7By3mz9pbCYRWuE0cv7IDejQ">
						 unimedmanaus.com.br
						 </a>
					  </td>
				   </tr>
				   <tr>
					  <td align="center">
						 <p style="line-height:1.5;font-family:Calibri,Arial,sans-serif;font-size:12px;color:#58595b;text-align:center;text-decoration:none"> 
							Central de Atendimento: 
							<a href="tel:+08007029088" style="font-family:Calibri,Arial,sans-serif;font-size:12px;color:#00995D;text-align:center;text-decoration:none" target="_blank">
							0800 702 9088
							</a>
							
							<br> 
							
							Whatsapp: 
							<a href="https://api.whatsapp.com/send?phone=559232122001&text=Quero%20entrar%20em%20contato%20com%20a%20Unimed%20Manaus" style="font-family:Calibri,Arial,sans-serif;font-size:12px;color:#00995D;text-align:center;text-decoration:none" target="_blank">
							(92) 3212-2001
							</a>
							
							<br> 
							
							Av. Constantino Nery, 1678 - São Geraldo, Manaus - AM, 69050-000
						 </p>
					  </td>
				   </tr>
				</tbody>
			 </table>
		  </div>
	   </center>
	   <div class="yj6qo"></div>
	   <div class="adL"></div>
	</div>';
	
	return $html;
	
}

?>
