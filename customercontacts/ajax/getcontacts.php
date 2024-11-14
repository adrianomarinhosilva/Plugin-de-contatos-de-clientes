<?php
include ("../../../inc/includes.php");

Session::checkLoginUser();
Html::header_nocache();

$entity_id = $_GET['entity_id'] ?? 0;
$search = $_GET['search'] ?? '';

$contact = new Contact();
$where = ['entities_id' => $entity_id];

if (!empty($search)) {
   $where['OR'] = [
      'name'  => ['LIKE', "%$search%"],
      'phone' => ['LIKE', "%$search%"],
      'email' => ['LIKE', "%$search%"]
   ];
}

$contacts = $contact->find($where);

if (empty($contacts)) {
   echo "<div class='alert alert-info'>Nenhum contato encontrado.</div>";
} else {
   foreach ($contacts as $data) {
      echo "<div class='contact-item'>";
      echo "<div><strong>Nome:</strong> " . htmlspecialchars($data['name']) . "</div>";
      if (!empty($data['phone'])) {
         echo "<div class='mt-2'><strong>Telefone:</strong> " . htmlspecialchars($data['phone']) . "</div>";
      }
      if (!empty($data['email'])) {
         echo "<div class='mt-2'><strong>Email:</strong> " . htmlspecialchars($data['email']) . "</div>";
      }
      echo "</div>";
   }
}