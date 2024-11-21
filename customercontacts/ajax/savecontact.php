<?php
include ("../../../inc/includes.php");

Session::checkLoginUser();
Html::header_nocache();

// Captura os dados do formulário
$input = [
   'name' => $_POST['name'] ?? '',
   'phone' => $_POST['phone'] ?? '',
   'email' => $_POST['email'] ?? '',
   'entities_id' => $_POST['entity_id'] ?? 0,
   'usertitles_id' => intval($_POST['usertitles_id']) // ID do título selecionado
];

$contact = new Contact();

$success = false;
if (!empty($_POST['id'])) {
   $input['id'] = $_POST['id'];
   
   // Primeiro atualiza o contato existente
   if ($contact->getFromDB($input['id'])) {
      // Atualiza todos os campos incluindo o título
      $success = $contact->update([
         'id' => $input['id'],
         'name' => $input['name'],
         'phone' => $input['phone'],
         'email' => $input['email'],
         'entities_id' => $input['entities_id'],
         'usertitles_id' => $input['usertitles_id'] // Garante que o título é atualizado
      ]);
   }
} else {
   // Cria um novo contato com todos os campos
   $success = $contact->add([
      'name' => $input['name'],
      'phone' => $input['phone'],
      'email' => $input['email'],
      'entities_id' => $input['entities_id'],
      'usertitles_id' => $input['usertitles_id'] // Inclui o título no novo contato
   ]);
}

// Debug para verificar os dados
error_log('Contact save operation - Input: ' . print_r($input, true));
error_log('Contact save operation - Success: ' . ($success ? 'true' : 'false'));

echo json_encode([
   'success' => $success,
   'message' => $success ? 'Contato salvo com sucesso' : 'Erro ao salvar contato',
   'debug' => [
      'input' => $input,
      'result' => $success
   ]
]);