<?php
function get_the_user_ip() {
if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
//check ip from share internet
$ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
//to check ip is pass from proxy
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
$ip = $_SERVER['REMOTE_ADDR'];
}
return apply_filters( 'wpb_get_ip', $ip );
}
add_action('bookshelf_spreadsheet','create_bookshelf_spreadsheet');
function create_bookshelf_spreadsheet() {
     $wrkfile = ABSPATH . 'wp-content/themes/yaaburnee-themes-child/export_bookshelf.csv';
     $output = exec("rm $wrkfile");
     global $wpdb;
     $q2 = "select 'title','subtitle','author','publisher','bookid','id type','category','review','googleid' union all select title,subtitle,author,publisher,bookid,ind_type,category,review,googleid into outfile '$wrkfile' character set utf8 FIELDS TERMINATED BY '|' OPTIONALLY ENCLOSED BY '~' LINES TERMINATED BY '\n' FROM wp_bookshelf";
     $result = $wpdb->query($q2);
     exec("unoconv -f ods -i FilterOptions='124,126,,1' $wrkfile");
}
?>
