<?php
include ("../../../inc/includes.php");

Session::checkLoginUser();
Html::header_nocache();

$entity_id = $_GET['entity_id'] ?? 0;
$search = $_GET['search'] ?? '';

$contact = new Contact();
$where = ['entities_id' => $entity_id];

if (!empty($search)) {
   // Join com a tabela de títulos
   $table_contact = Contact::getTable();
   $table_usertitles = UserTitle::getTable();
   
   $contacts = $DB->request([
      'SELECT' => ["$table_contact.*", "$table_usertitles.name as title_name"],
      'FROM' => $table_contact,
      'LEFT JOIN' => [
         $table_usertitles => [
            'ON' => [
               $table_contact => 'usertitles_id',
               $table_usertitles => 'id'
            ]
         ]
      ],
      'WHERE' => [
         "$table_contact.entities_id" => $entity_id,
         'OR' => [
            "$table_contact.name" => ['LIKE', "%$search%"],
            "$table_contact.phone" => ['LIKE', "%$search%"],
            "$table_contact.email" => ['LIKE', "%$search%"],
            "$table_usertitles.name" => ['LIKE', "%$search%"] // Pesquisa por título
         ]
      ]
   ]);
} else {
   $contacts = $contact->find($where);
}

if (empty($contacts)) {
   echo "<div class='alert alert-info'>Nenhum contato encontrado.</div>";
} else {
   foreach ($contacts as $data) {
      echo "<div class='contact-card'>";
      echo "<div class='contact-info'>";
      echo "<strong>Nome:</strong> <span>" . htmlspecialchars($data['name']) . "</span>";
      
      if (!empty($data['phone'])) {
         $phone = preg_replace("/[^0-9]/", "", $data['phone']);
         echo "<strong>Telefone:</strong> <span><a href='tel:" . $phone . "'>" . htmlspecialchars($data['phone']) . "</a></span>";
      } else {
         echo "<strong>Telefone:</strong> <span>-</span>";
      }
      
      if (!empty($data['email'])) {
         echo "<strong>Email:</strong> <span><a href='mailto:" . htmlspecialchars($data['email']) . "'>" . htmlspecialchars($data['email']) . "</a></span>";
      } else {
         echo "<strong>Email:</strong> <span>-</span>";
      }

      // Exibição do título
      echo "<strong>Título:</strong> <span>";
      if (!empty($data['usertitles_id'])) {
         $userTitle = new UserTitle();
         if ($userTitle->getFromDB($data['usertitles_id'])) {
            echo htmlspecialchars($userTitle->fields['name']);
         } else {
            echo "-";
         }
      } else {
         echo "-";
      }
      echo "</span>";
      
      echo "</div>";
      echo "<div class='contact-actions'>";
      echo "<button class='btn btn-sm btn-primary' onclick='editContact({$data['id']})'><i class='fas fa-edit'></i></button>";
      echo "<button class='btn btn-sm btn-danger' onclick='deleteContact({$data['id']})'><i class='fas fa-trash'></i></button>";
      echo "</div>";
      echo "</div>";
   }
}