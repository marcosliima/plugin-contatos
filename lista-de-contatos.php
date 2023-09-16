<?php
/*
Plugin Name: Lista de Contatos
Description: Um plugin para gerenciar contatos.
Version: 1.0
Author: Marcos Lima 
*/

// Função para criar tabelas ao ativar o plugin
function criar_tabelas_contatos() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $tabela_contato_pessoas = $wpdb->prefix . 'contato_pessoas';
    $tabela_telefone_pessoas = $wpdb->prefix . 'telefone_pessoas';

    $sql_contato_pessoas = "CREATE TABLE $tabela_contato_pessoas (
        id INT NOT NULL AUTO_INCREMENT,
        nome VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    $sql_telefone_pessoas = "CREATE TABLE $tabela_telefone_pessoas (
        id INT NOT NULL AUTO_INCREMENT,
        contato_id INT NOT NULL,
        telefone VARCHAR(20) NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (contato_id) REFERENCES $tabela_contato_pessoas(id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_contato_pessoas);
    dbDelta($sql_telefone_pessoas);
}

register_activation_hook(__FILE__, 'criar_tabelas_contatos');

// Adicione um menu administrativo no WordPress
function adicionar_menu_administrativo() {
    add_menu_page(
        'Lista de Contatos',
        'Lista de Contatos',
        'manage_options',
        'lista-contatos',
        'renderizar_pagina_lista_contatos'
    );

    add_submenu_page(
        'lista-contatos',
        'Adicionar Contato',
        'Adicionar Contato',
        'manage_options',
        'add-contatos',
        'renderizar_pagina_add_contatos'
    );
}

function renderizar_pagina_lista_contatos() {
    include('lista-contatos.php');
}

function renderizar_pagina_add_contatos() {
    include('add-contatos.php');
}

add_action('admin_menu', 'adicionar_menu_administrativo');
?>
