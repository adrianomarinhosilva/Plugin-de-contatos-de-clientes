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
      echo "<div class='contact-card'>";
      echo "<div class='contact-info'>";
      echo "<strong>Nome:</strong> <span>" . htmlspecialchars($data['name']) . "</span>";
      
      // Formata telefone como link
      if (!empty($data['phone'])) {
         $phone = preg_replace("/[^0-9]/", "", $data['phone']); // Remove não-números
         echo "<strong>Telefone:</strong> <span><a href='tel:" . $phone . "'>" . htmlspecialchars($data['phone']) . "</a></span>";
      } else {
         echo "<strong>Telefone:</strong> <span>-</span>";
      }
      
      // Formata email como link
      if (!empty($data['email'])) {
         echo "<strong>Email:</strong> <span><a href='mailto:" . htmlspecialchars($data['email']) . "'>" . htmlspecialchars($data['email']) . "</a></span>";
      } else {
         echo "<strong>Email:</strong> <span>-</span>";
      }
      
      echo "</div>";
      echo "<div class='contact-actions'>";
      echo "<button class='btn btn-sm btn-primary' onclick='editContact({$data['id']})'><i class='fas fa-edit'></i></button>";
      echo "<button class='btn btn-sm btn-danger' onclick='deleteContact({$data['id']})'><i class='fas fa-trash'></i></button>";
      echo "</div>";
      echo "</div>";
   }
}