<?php
//echo "aqui";exit;
$response = [];
$response_ = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fieldToTargetMap = json_decode($_POST['fieldToTargetMap'], true);
   // print_r($fieldToTargetMap);
    $fieldValueMap = '';
    if ($_POST['fieldValueMap'] != '') {

        $fieldValueMap = json_decode($_POST['fieldValueMap'], true);

    }

    foreach ($_FILES as $fieldName => $fileData) {

        if (is_array($fileData['name'])) {

            $fileCount = count($fileData['name']);
            $more = [];
            for ($i = 0; $i < $fileCount; $i++) {
                //print_r($fileData); exit;
                $target = $fieldName . "[$i]";
                $targetDirectory = '../' . $fieldToTargetMap[$target]; //echo $targetDirectory; exit;
                $_targetDirectory = $fieldToTargetMap[$target];
                $_value = '';
                if (is_array($fieldValueMap)) {
                    $_value = $fieldValueMap[$target];
                }

                if (!file_exists($targetDirectory)) {
                    mkdir($targetDirectory, 0777, true);
                }


                $originalFileName = basename($fileData['name'][$i]);
                $fileNameWithoutExtension = pathinfo($originalFileName, PATHINFO_FILENAME);
                $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

                // Gerar um nome de arquivo único
                if (!$_value) {
                    $newFileName = date('d_m_Y_H_i_s_') . uniqid() . '.' . $fileExtension;
                } else {
                    $newFileName = $_value . '-' . uniqid() . '.' . $fileExtension;
                }
                $targetFile = $targetDirectory . $newFileName;
                //echo $targetFile;
                $_targetFile = $_targetDirectory . $newFileName;

                if (move_uploaded_file($fileData['tmp_name'][$i], $targetFile)) {
                    if ($_POST['target'] == 1) {
                        $attachment = array("file" => $newFileName, "target_map" => $fieldToTargetMap[$target] . $newFileName);
                    } else {
                        $attachment = $newFileName;
                    }
                    $response[] = $fieldName . "[$i]";
                    $response_[] = $attachment;
                }
            }
        } else {
            $targetDirectory = '../' . $fieldToTargetMap[$fieldName]; //echo $targetDirectory; exit;
            $_targetDirectory = $fieldToTargetMap[$fieldName];
            $_value = '';
            if (is_array($fieldValueMap)) {
                $_value = $fieldValueMap[$fieldName];
            }


            // Verifique se o diretório de destino existe; se não existir, crie-o recursivamente
            if (!file_exists($targetDirectory)) {
                mkdir($targetDirectory, 0777, true);
            }

            // Processa campos com um único arquivo
            $originalFileName = basename($fileData['name']);
            $fileNameWithoutExtension = pathinfo($originalFileName, PATHINFO_FILENAME);
            $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

            // Gerar um nome de arquivo único
            if (!$_value) {
                $newFileName = date('d_m_Y_H_i_s_') . uniqid() . '.' . $fileExtension;
            } else {
                $newFileName = $_value . '-' . uniqid() . '.' . $fileExtension;
            }
            $targetFile = $targetDirectory . $newFileName; // echo $targetFile; exit;
            $_targetFile = $_targetDirectory . $newFileName;

          //  print_r($fileData['tmp_name']); exit;

            if (move_uploaded_file($fileData['tmp_name'], $targetFile)) {

                if ($_POST['target'] == 1) {
                    $attachment = array("file" => $newFileName, "target_map" => $fieldToTargetMap[$fieldName] . $newFileName);
                } else {
                    $attachment = $newFileName;
                }
                $response[] = $fieldName;
                $response_[] = $attachment;


            }
               /* echo "Moveu arquivo !"; exit;
            }else{
                echo "Não Moveu"; exit;
            }*/
        }
    }

    $result = array_combine($response, $response_);
    //echo(json_encode($result, true)); exit;
    echo json_encode($result, true);
    //echo 'djjd';
}
?>
