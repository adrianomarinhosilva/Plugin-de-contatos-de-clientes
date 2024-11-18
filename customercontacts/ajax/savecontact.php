<?php
include ("../../../inc/includes.php");

Session::checkLoginUser();
Html::header_nocache();

$input = [
   'name' => $_POST['name'] ?? '',
   'phone' => $_POST['phone'] ?? '',
   'email' => $_POST['email'] ?? '',
   'entities_id' => $_POST['entity_id'] ?? 0
];

$contact = new Contact();

if (!empty($_POST['id'])) {
   $input['id'] = $_POST['id'];
   $success = $contact->update($input);
} else {
   $success = $contact->add($input);
}

echo json_encode(['success' => $success]);