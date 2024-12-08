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
         echo "<div class='d-flex justify-content-between align-items-center mb-3'>";
         echo "<div class='search-box flex-grow-1 me-3'>";
         echo "<input type='text' id='contact-search' class='form-control' placeholder='Pesquisar por nome, email ou telefone...'>";
         echo "</div>";
         echo "<button class='btn btn-primary' onclick='openNewContactModal()'><i class='fas fa-plus'></i> Novo Contato</button>";
         echo "</div>";
         echo "<div id='contact-list' class='contact-grid'></div>";
         echo "</div>";

         // Modal para novo/editar contato
         echo "<div class='modal fade' id='contactModal' tabindex='-1'>";
         echo "<div class='modal-dialog'>";
         echo "<div class='modal-content'>";
         echo "<div class='modal-header'>";
         echo "<h5 class='modal-title' id='modalTitle'>Novo Contato</h5>";
         echo "<button type='button' class='btn-close' data-bs-dismiss='modal'></button>";
         echo "</div>";
         echo "<div class='modal-body'>";
         echo "<form id='contactForm'>";
         echo "<input type='hidden' id='contact_id'>";
         echo "<div class='mb-3'>";
         echo "<label class='form-label'>Nome</label>";
         echo "<input type='text' class='form-control' id='contact_name' required>";
         echo "</div>";
         echo "<div class='mb-3'>";
         echo "<label class='form-label'>Telefone</label>";
         echo "<input type='text' class='form-control' id='contact_phone'>";
         echo "</div>";
         echo "<div class='mb-3'>";
         echo "<label class='form-label'>Email</label>";
         echo "<input type='email' class='form-control' id='contact_email'>";
         echo "</div>";
         echo "</form>";
         echo "</div>";
         echo "<div class='modal-footer'>";
         echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>";
         echo "<button type='button' class='btn btn-primary' onclick='saveContact()'>Salvar</button>";
         echo "</div>";
         echo "</div>";
         echo "</div>";
         echo "</div>";

         $baseUrl = $CFG_GLPI["root_doc"];
         
         echo Html::scriptBlock("
            function openNewContactModal() {
               $('#modalTitle').text('Novo Contato');
               $('#contactForm')[0].reset();
               $('#contact_id').val('');
               $('#contactModal').modal('show');
            }

            function editContact(id) {
               $.ajax({
                  url: '$baseUrl/plugins/customercontacts/ajax/getcontact.php',
                  type: 'GET',
                  data: { id: id },
                  dataType: 'json',
                  success: function(data) {
                     $('#modalTitle').text('Editar Contato');
                     $('#contact_id').val(data.id);
                     $('#contact_name').val(data.name);
                     $('#contact_phone').val(data.phone);
                     $('#contact_email').val(data.email);
                     $('#contactModal').modal('show');
                  },
                  error: function() {
                     alert('Erro ao carregar dados do contato');
                  }
               });
            }

            function deleteContact(id) {
               if (confirm('Tem certeza que deseja excluir este contato?')) {
                  $.ajax({
                     url: '$baseUrl/plugins/customercontacts/ajax/deletecontact.php',
                     type: 'POST',
                     data: { id: id },
                     dataType: 'json',
                     success: function(response) {
                        if (response.success) {
                           loadContacts();
                        } else {
                           alert(response.message || 'Erro ao excluir contato');
                        }
                     },
                     error: function(xhr, status, error) {
                        alert('Erro ao excluir contato: ' + error);
                     }
                  });
               }
            }

            function saveContact() {
               let formData = {
                  id: $('#contact_id').val(),
                  name: $('#contact_name').val(),
                  phone: $('#contact_phone').val(),
                  email: $('#contact_email').val(),
                  entity_id: $entity_id
               };

               $.ajax({
                  url: '$baseUrl/plugins/customercontacts/ajax/savecontact.php',
                  type: 'POST',
                  data: formData,
                  dataType: 'json',
                  success: function(response) {
                     if (response.success) {
                        $('#contactModal').modal('hide');
                        loadContacts();
                     } else {
                        alert('Erro ao salvar contato');
                     }
                  },
                  error: function() {
                     alert('Erro ao salvar contato');
                  }
               });
            }

            $(document).ready(function() {
               loadContacts();
               $('#contact-search').on('keyup', function() {
                  loadContacts($(this).val());
               });
            });

            function loadContacts(search = '') {
               $.get('$baseUrl/plugins/customercontacts/ajax/getcontacts.php', 
                  { 
                     entity_id: $entity_id,
                     search: search 
                  }, 
                  function(data) {
                     $('#contact-list').html(data);
                  }
               );
            }
         ");

         echo "<style>
            .contacts-wrapper {
               padding: 15px;
               background: #fff;
               border-radius: 4px;
               box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
            .contact-grid {
               display: grid;
               grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
               gap: 15px;
               margin-top: 15px;
            }
            .contact-card {
               border: 1px solid #ddd;
               border-radius: 4px;
               padding: 15px;
               background: #fff;
               position: relative;
               overflow: hidden;
            }
            .contact-card:hover {
               box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
            .contact-info {
               display: grid;
               grid-template-columns: auto 1fr;
               gap: 10px;
               align-items: center;
               word-break: break-word;
               padding-right: 60px;
            }
            .contact-info span {
               overflow-wrap: break-word;
               word-wrap: break-word;
               hyphens: auto;
               max-width: 100%;
            }
            .contact-info a {
               word-break: break-all;
               color: #0056b3;
               text-decoration: none;
            }
            .contact-info a:hover {
               text-decoration: underline;
            }
            .contact-actions {
               position: absolute;
               top: 10px;
               right: 10px;
               background: #fff;
               padding: 5px;
            }
            .contact-actions button {
               padding: 4px 8px;
               margin-left: 5px;
            }
            .contact-info [href^='tel:'] {
               color: #28a745;
            }
            .contact-info [href^='mailto:'] {
               color: #007bff;
            }
         </style>";
      }
      return true;
   }
}