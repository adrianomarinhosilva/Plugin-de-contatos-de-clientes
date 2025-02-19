Plugin CustomerContacts para GLPI
Visão Geral
O plugin "CustomerContacts" é uma extensão para o sistema GLPI que adiciona uma funcionalidade de gerenciamento e pesquisa de contatos de clientes, especialmente integrada ao contexto de tickets.
Detalhes Técnicos
1. Configurações Básicas (composer.json e setup.php)

Versão: 1.0.0
Autor: Adriano Marinho
Licença: GPL-3.0-or-later
Compatibilidade GLPI: Versões 9.5 a 10.1.99
Requisito PHP: Versão 7.2 ou superior

2. Funcionalidades Principais
2.1 Classe de Contatos (contact.class.php)

Adiciona uma nova aba "Contatos do Cliente" nos tickets
Permite busca e exibição de contatos vinculados a uma entidade específica

2.2 Recuperação de Contatos (getcontacts.php)

Script AJAX para buscar contatos
Funcionalidades:

Filtro por entidade
Busca por nome, telefone ou email
Exibição dinâmica dos resultados



2.3 Interface de Busca

Campo de pesquisa em tempo real
Lista de contatos rolável
Destaque dos resultados de busca

Componentes Técnicos Detalhados
Classe Principal: PluginCustomercontactsContact
Script de Recuperação de Contatos (getcontacts.php)
JavaScript para Interatividade (customercontacts.js)
CSS para Estilização (customercontacts.css)
Recursos Principais

Busca dinâmica de contatos
Filtro por entidade
Interface responsiva
Integração direta com tickets do GLPI

Melhorias Potenciais

Adicionar opção de exportação de contatos
Implementar cache de resultados
Criar filtros mais avançados
Adicionar paginação para grandes conjuntos de contatos

Considerações de Segurança

Verificação de login antes do acesso
Filtro por entidade para isolamento de dados
Uso de htmlspecialchars() para prevenir XSS

O plugin "CustomerContacts" oferece uma solução simples e eficiente para gerenciar e pesquisar contatos de clientes dentro do ambiente do GLPI, facilitando o acesso rápido a informações importantes durante o atendimento de tickets.
