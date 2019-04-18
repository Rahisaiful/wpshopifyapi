<?php
/**
* @package 
* @version 1.0
*
*/


// Api config
function wsa_api_config( $targetEndpoint = 'products' ) {

    $adminApi = 'c13fa616a1d1e69835dd52f6ddd171e6';
    $pass     = '06d048c6f66999f6214101aa5ea55e13';
    $storeUrl  = 'teststor26.myshopify.com';
    $apiVersion =  '2019-04';
    $targetEndpoint = $targetEndpoint;

    $apiUrl = 'https://'.$adminApi . ':' .$pass . '@' . $storeUrl . '/admin/api/' . $apiVersion .'/'. $targetEndpoint .'.json';


    return esc_url_raw(  $apiUrl );
    //return $apiUrl;

}



// Get total product
function wsa_total_products() {

    $apiUrl =  wsa_api_config( 'products' );

    $jsonData = wp_remote_get( $apiUrl );


    $data = json_decode( $jsonData['body'], true );

    return $data['products'];

}


// Get Total Customar 
function wsa_total_customars() {

    $apiUrl =  wsa_api_config( 'customers' );
    $jsonData = wp_remote_get( $apiUrl );

    $data = json_decode( $jsonData['body'], true );

    return $data['customers'];
}

// Get checkouts count
function wsa_checkouts_count() {

    $apiUrl =  wsa_api_config( 'checkouts/count' );

    $jsonData = wp_remote_get( $apiUrl );

    $data = json_decode( $jsonData['body'], true );

    if( !empty( $data['count'] ) ) {
        echo $data['count'];
    }
}



// Statistics Widget
add_action( 'wp_dashboard_setup', 'add_statistics_admin_widget' );
function add_statistics_admin_widget(){

    wp_add_dashboard_widget(
            'wsa-statistics',     // Widget slug.
            'Shopify Statistics',    // Title.
           'wsa_statistics_callback' // Display function.
    );  


}
// 
function wsa_statistics_callback() {

    // products
    $products = wsa_total_products();

    // customars
    $customars = wsa_total_customars();
    ?>
    <div class="single-widget" style="background: #eee; margin-bottom: 20px;padding:8px;">
        <h3>Total Product: <?php echo esc_html( count( $products ) ); ?></h3>
        <ul>
            <?php 
            foreach( $products as $data ) {

                if( ! empty( $data['title'] ) ) {
                    echo '<li>Product Name: '.esc_html( $data['title'] ).'</li>';
                }
            }
            ?>

        </ul> 
    </div>
    <div class="single-widget" style="background: #eee; margin-bottom: 20px;padding:8px;">
        <h3>Total Customar: <?php echo esc_html( count( $customars ) ); ?><h3>
        <ul>
            <strong>Customar Email:</strong>
            <?php 
            foreach( $customars as $data ) {
                if( ! empty( $data['email'] ) ) {
                    echo '<li>'.esc_html( $data['email'] ).'</li>';
                }
            }
            ?>
        </ul> 
    </div>
    <div class="single-widget" style="background: #eee; margin-bottom: 20px;padding:8px;">
        <h3>Total Abandoned Checkouts: <?php  wsa_checkouts_count() ?><h3>
    </div>

    <?php
}


