<?php
include ("../../../inc/includes.php");

Session::checkLoginUser();
Html::header_nocache();

header('Content-Type: application/json');

$id = $_GET['id'] ?? 0;
$contact = new Contact();

if ($contact->getFromDB($id)) {
   echo json_encode([
      'id' => $contact->fields['id'],
      'name' => $contact->fields['name'],
      'phone' => $contact->fields['phone'] ?? '',
      'email' => $contact->fields['email'] ?? ''
   ]);
} else {
   http_response_code(404);
   echo json_encode(['error' => 'Contato não encontrado']);
}