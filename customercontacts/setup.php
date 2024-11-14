<?php
define('CUSTOMERCONTACTS_VERSION', '1.0.0');

function plugin_init_customercontacts() {
   global $PLUGIN_HOOKS;
   $PLUGIN_HOOKS['csrf_compliant']['customercontacts'] = true;
   Plugin::registerClass('PluginCustomercontactsContact', [
      'addtabon' => ['Ticket']
   ]);
}

function plugin_version_customercontacts() {
   return [
      'name'           => 'Contatos de Clientes',
      'version'        => CUSTOMERCONTACTS_VERSION,
      'author'         => 'Adriano Marinho',
      'license'        => 'GLPv3+',
      'homepage'       => 'https://github.com/malakaygames',
      'requirements'   => [
         'glpi' => [
            'min' => '9.5',
            'max' => '10.1.99',
         ]
      ]
   ];
}

function plugin_customercontacts_check_prerequisites() {
   return true;
}

function plugin_customercontacts_check_config() {
   return true;
}