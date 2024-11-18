<?php
include ("../../../inc/includes.php");

Session::checkLoginUser();
Html::header_nocache();

header('Content-Type: application/json');

if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID não fornecido']);
    return;
}

$contact = new Contact();
$id = intval($_POST['id']);

// Verifica permissões
if (!$contact->canDelete()) {
    echo json_encode(['success' => false, 'message' => 'Sem permissão para deletar']);
    return;
}

// Tenta deletar o contato
if ($contact->delete(['id' => $id], true)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao deletar contato']);
}