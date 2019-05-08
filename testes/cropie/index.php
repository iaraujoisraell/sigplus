<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Crop and upload</title>

  
  <style>#page { background: #FFF; padding: 20px; margin: 20px; } #demo-basic { width: 200px; height: 300px; } </style>
  <script>$(function() { var basic = $('#demo-basic').croppie({ viewport: { width: 150, height: 200 } }); basic.croppie('bind', { url: 'logo_aplicativo_2.png', points: [77, 469, 280, 739] }); });</script>
</head>
<body>
    

<form id="form1" runat="server">
  <input type='file' id="imgInp" />
  <img id="my-image" src="#" />
</form>
<button id="use">Upload</button>
<img id="result" src="">
</body>