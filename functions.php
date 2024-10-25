<?php
/**
 * astra-escuta Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package astra-escuta
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_ESCUTA_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'astra-escuta-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_ESCUTA_VERSION, 'all' );

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

/*
 *	
 *	
 *	
 *	
 *	
 *	
 *	
 *	
 *	
 *	    <<<<<<<<<<<<< START >>>>>>>>>>>
 *		ADDED BY Setor de Suporte e T.I. 
*/


require_once 'shortcodes.php';


function add_header_custom_scripts(){
	?>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<?php
}

add_action( 'wp_head', 'add_header_custom_scripts' );

function add_footer_custom_scripts(){
	?>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
	<?php
}

add_action( 'wp_footer', 'add_footer_custom_scripts' );

/** 
  * Carregando conteúdo extra com base na página
**/
function add_footer_custom_scripts_by_page(){
	
	if( is_page( "perfil" ) ){
		echo '<script src="' . get_stylesheet_directory_uri() . '/assets/js/perfil.js"></script>';
	}
}

add_action( 'wp_footer', 'add_footer_custom_scripts_by_page' );


/**
 * ADDING SHORTCODES
 * START
**/
add_shortcode( 'lista_acolhidos', 'gerar_lista_acolhidos' );


add_shortcode( 'vizualizar_perfil_acolhido', 'gerar_perfil_acolhido' );


add_shortcode( 'calendario_escutas', 'gerar_calendario_escutas' );

