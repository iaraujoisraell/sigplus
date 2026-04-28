<?php

$response = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fieldToTargetMap = json_decode($_POST['fieldToTargetMap'], true);

    // $targetDirectory = "./assets/intranet/arquivos/categorias_arquivos/";

    

    foreach ($_FILES as $fieldName => $fileData) {

// Verifica se o diretório de destino existe; se não existir, cria-o recursivamente
        $targetDirectory =  '../'.$fieldToTargetMap[$fieldName];
        $_targetDirectory = $fieldToTargetMap[$fieldName];

        // Verifique se o diretório de destino existe; se não existir, crie-o recursivamente
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0777, true);
        }
        if (is_array($fileData['name'])) {
            // Processa campos com múltiplos arquivos
            $fileCount = count($fileData['name']);
            $more = [];
            for ($i = 0; $i < $fileCount; $i++) {
                $originalFileName = basename($fileData['name'][$i]);
                $fileNameWithoutExtension = pathinfo($originalFileName, PATHINFO_FILENAME);
                $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

                // Gerar um nome de arquivo único
                $newFileName = 'teste_' . uniqid() . '.' . $fileExtension;
                $targetFile = $targetDirectory . $newFileName;
                $_targetFile = $_targetDirectory . $newFileName;

                if (move_uploaded_file($fileData['tmp_name'][$i], $targetFile)) {
                    $more[] = $newFileName;
                }
            }
            $one = ['name' => $fileData['name'], 'files' => $more];
        } else {

            // Processa campos com um único arquivo
            $originalFileName = basename($fileData['name']);
            $fileNameWithoutExtension = pathinfo($originalFileName, PATHINFO_FILENAME);
            $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

            // Gerar um nome de arquivo único
            $newFileName = date('d_m_Y_H_i_s_') . uniqid() . '.' . $fileExtension;
            $targetFile = $targetDirectory . $newFileName;
            $_targetFile = $_targetDirectory . $newFileName;

            if (move_uploaded_file($fileData['tmp_name'], $targetFile)) {
                $one = ['name' => $fileData['name'], 'files' => $newFileName, 'target_map' => $fieldToTargetMap[$fieldName].$newFileName];
            }
        }
        array_push($response, $one);
    }

    echo json_encode($response);
}
?>
