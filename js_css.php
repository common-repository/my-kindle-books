<?php

function my_kindle_books_styles_and_scripts() {

    wp_register_style(
        'jquery-ui-css',
        plugins_url( 'css/jquery-ui.css',__FILE__ )
    );
    wp_register_style(
        'my-kindle-books-css',
        plugins_url( 'css/my-kindle-books.css',__FILE__ )
    );
}

add_action( 'admin_init', 'my_kindle_books_styles_and_scripts' );

function my_kindle_books_admin_scripts() {

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-ui-sortable' );
}

function my_kindle_books_admin_styles(){

    wp_enqueue_style( 'jquery-ui-css' );
    wp_enqueue_style( 'my-kindle-books-css' );
}