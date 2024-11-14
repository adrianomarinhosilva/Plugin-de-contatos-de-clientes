<?php
include ('../../../inc/includes.php');

$ticket = new Ticket();
Html::header(__('Ticket'), $_SERVER['PHP_SELF'], "helpdesk", "ticket");
$ticket->display($_GET);
Html::footer();