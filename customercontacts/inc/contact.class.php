<?php
if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

class PluginCustomercontactsContact extends CommonGLPI {
   static $rightname = "ticket";

   static function getTypeName($nb = 0) {
      return 'Contatos do Cliente';
   }

   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {
      if ($item->getType() == 'Ticket') {
         return self::getTypeName();
      }
      return '';
   }

   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
      if ($item->getType() == 'Ticket') {
         $ticket = new Ticket();
         $ticket->getFromDB($item->getID());
         $entity_id = $ticket->fields['entities_id'];

         echo "<div class='contacts-wrapper'>";
         echo "<div class='search-box'>";
         echo "<input type='text' id='contact-search' class='form-control' placeholder='Pesquisar por nome, email ou telefone...'>";
         echo "</div>";
         echo "<div id='contact-list' class='contact-list'></div>";
         echo "</div>";

         $baseUrl = $CFG_GLPI["root_doc"];
         
         echo Html::scriptBlock("
            $(document).ready(function() {
               $.get('$baseUrl/plugins/customercontacts/ajax/getcontacts.php', 
                  { entity_id: $entity_id }, 
                  function(data) {
                     $('#contact-list').html(data);
                  }
               );

               $('#contact-search').on('keyup', function() {
                  $.get('$baseUrl/plugins/customercontacts/ajax/getcontacts.php', 
                     { 
                        entity_id: $entity_id,
                        search: $(this).val()
                     }, 
                     function(data) {
                        $('#contact-list').html(data);
                     }
                  );
               });
            });
         ");

         echo "<style>
            .contacts-wrapper {
               padding: 15px;
               background: #fff;
               border-radius: 4px;
               box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
            .search-box {
               margin-bottom: 15px;
            }
            .contact-list {
               max-height: 500px;
               overflow-y: auto;
            }
            .contact-item {
               padding: 10px;
               margin-bottom: 8px;
               border: 1px solid #ddd;
               border-radius: 4px;
               background: #fff;
            }
            .contact-item:hover {
               background: #f8f9fa;
            }
         </style>";
      }
      return true;
   }
}