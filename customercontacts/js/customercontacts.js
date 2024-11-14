// js/customercontacts.js
$(document).ready(function() {
    function loadTicketContacts() {
        const entityId = $('#dropdown_entities_id').val();
        $.ajax({
            url: CFG_GLPI.root_doc + '/plugins/customercontacts/ajax/getcontacts.php',
            data: { entity_id: entityId },
            success: function(data) {
                $('#contacts-container').html(data);
            }
        });
    }

    $('#dropdown_entities_id').on('change', loadTicketContacts);
    loadTicketContacts();
});