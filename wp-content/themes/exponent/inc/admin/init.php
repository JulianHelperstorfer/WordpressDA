<?php
//include customizer options
if( is_customize_preview() && current_user_can('manage_options') && class_exists( 'Kirki' ) ) {
    require_once trailingslashit( get_template_directory() ) . 'inc/classes/class-be-options.php'; 
}

if( is_admin() ) {
    //include metas
    require trailingslashit( get_template_directory() ) . 'inc/admin/metas/init.php';

    //update checker
    require_once( get_template_directory().'/inc/classes/theme-update-checker.php' );
    if( class_exists( 'ThemeUpdateChecker' ) ) {
        $exponent_update_checker = new ThemeUpdateChecker(
            'exponent',
            'https://brandexponents.com/wp-json/beepapi/v1/purchase-verifier'
        );
    }
    if( !function_exists( 'exponent_autoupdate_verify' ) ) {
        function exponent_autoupdate_verify( $query_args ) {
            $exponent_purchase_data = get_option('exponent_purchase_data', '' );
            if(is_array($exponent_purchase_data) && array_key_exists('theme_purchase_code', $exponent_purchase_data)){
                $query_args['purchase_key'] = $exponent_purchase_data['theme_purchase_code'];
            }else{
                $query_args['purchase_key'] = '';
            }
            $vars = exponent_pk_verify_vars();
		    $query_args = array_merge($query_args,$vars);
            return $query_args;
        }
        add_filter ('tuc_request_update_query_args-exponent','exponent_autoupdate_verify');
    }
}