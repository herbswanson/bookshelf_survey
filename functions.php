<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
 
    $parent_style = 'parent-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
 
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
function the_category_unlinked($separator = ' ') {
    $categories = (array) get_the_category();
    
    $thelist = '';
    foreach($categories as $category) {    // concate
        $thelist .= $separator . $category->category_nicename;
    }
    
    echo $thelist;
}
function echo_log( $what )
{
    echo '<pre>'.print_r( $what, true ).'</pre>';
}
function get_apikey()
{
	$apikey = file_get_contents(ABSPATH . "wp-content/themes/yaaburnee-themes-child/apikey.txt");
	return($apikey);
}

function get_bookinfo ($btitle)
{
    require_once 'vendor/autoload.php';
    $key = trim(get_apikey());
    $client = new Google_Client();
    $client->setApplicationName("Client_Library_Examples");
    $client->setDeveloperKey($key);
    $service = new Google_Service_Books($client);
    $optParams = array('projection' => 'full', 'langRestrict' => 'en');
    $results = $service->volumes->listVolumes($btitle, $optParams);
    
    $books=[];
    $knt = 0;
    foreach ($results as $item) {
	$knt += 1;
        $googleID = $item['id'];
    	$title = $item['volumeInfo']['title'];
    	$subtitle = $item['volumeInfo']['subtitle'];
    	$authors = $item['volumeInfo']['authors'][0];
    	$description = $item['volumeInfo']['description'];
        $volimg = $item['volumeInfo']['imageLinks']['thumbnail'];
	$volid = "No ISBN/Identifier";
	if (isset($item['volumeInfo']['industryIdentifiers'][0]->{'type'})) {
  		$type =  $item['volumeInfo']['industryIdentifiers'][0]->{'type'};
  		$vid = $item['volumeInfo']['industryIdentifiers'][0]->{'identifier'};
		$volid = $type.">  ".$vid;
	}
        $book_item=array($title,$subtitle,$authors,$description,$volid,$googleID,$volimg);
	$books[]=$book_item;
	if ($knt == 5) { break;}
	}
    return $books;
}
function bookdb_update($book_id,$nick,$shelf)
{
    global $wpdb;
    $url= 'https://www.googleapis.com/books/v1/volumes/' . $book_id;
    $json= file_get_contents($url);
    $bookinfo = json_decode($json);

    $table_name = $wpdb->prefix.'bookshelf';
    $googleid=$book_id;
    $title = ($bookinfo->volumeInfo)->title;
    $subtitle = (isset($bookinfo->volumeInfo->subtitle) ? $bookinfo->volumeInfo->subtitle   : '' );
    $author= (isset(($bookinfo->volumeInfo)->authors[0]) ? $bookinfo->volumeInfo->authors[0]   : '' );
    $review = (isset(($bookinfo->volumeInfo)->description) ? $bookinfo->volumeInfo->description   : '' );
    //print_r($title . '|' . $subtitle . '|' . $author . '|' . $review);
    $industry_key = $bookinfo->volumeInfo->industryIdentifiers;
    if (count($industry_key) == 0) {
        return 0;
    }
    $id = trim($industry_key[0]->identifier);
    $id_type = trim($industry_key[0]->type);
    $rtn = $wpdb->query($wpdb->prepare(
	    "
		insert into $table_name
		(bookid,id_type,knt,title,subtitle,author,review,googleid)
		values (%s,%s,%d,%s,%s,%s,%s,%s) 
		on duplicate key update knt = knt + 1
	     "
	       ,
	     $id,$id_type,1,$title,$subtitle,$author,$review,$googleid)) ;
    print_r('bookshelf_rtn:'.$rtn);
    if ($rtn === false) { return $rtn; } // sql update failed
    if ($nick == '' || $nick == null) { return $rtn ; }
    $nick = trim($nick);
    $shelf = trim($shelf);
    $table_name = $wpdb->prefix.'bookshelf_personal';
    $rtn = $wpdb->query($wpdb->prepare(
	    "
		insert ignore into $table_name
		(nick_id,bookid,shelf_name)
		values (%s,%s,%s)
	    "
	     ,
		     $nick,$id,$shelf));
    print_r('sql:'.$rtn);
    return $rtn; 
}
function book_action($postarray)
{
	$init_page = 0;
	$awaiting_input = 1;
	$google_search = 2;
	$db_update = 3;
	$db_update_google_search = 4;
	$list_nick_books = 5;
	$list_books = 6;
	$continue_search = 7;
        $delete_books = 8;
        $delete_books_now = 9;
        $help_page = 10;
        $back_to_view_lib = 11;

	
	if ($postarray['bookshelf_survey'] == '2')
        {
		return $list_books;
	}
	if ($postarray['bookshelf_survey'] == '3')
	{
		return $delete_books;
        }
	if ($postarray['bookshelf_survey'] == '4')
	{
		return $help_page;
        }
	if ($postarray['bookshelf_survey'] == '5')
	{
		return $back_to_view_lib;
	//	return $google_search;
        }
	if ($postarray['bookshelf_survey'] == '6')
	{
		return $db_update;
        }
	if ($postarray['books_to_delete'] != '' && $postarray['nick'] != '') {
		return $delete_books_now;
	}
        /******
	if ($postarray['t1'] != '' && $postarray['bookselected'] != '6')
	{
		if ($postarray['t1'] == $_SESSION['t1']) {
			return $db_update;
		} else {
			return $db_update_google_search;
		}
	}
	if ($postarray['t1'] == '' && $postarray['bookselected'] != '6')
	{
		return $db_update;

	}
        *******/
	if ($postarray['t1'] != '' && $postarray['bookselected'] == '6')
	{
		if ($postarray['t1'] == $_SESSION['t1']) {
			if ($postarray['nick'] != '') {
				return $continue_search;
			} else {
				return $awaiting_input;
			}
		} else {
			return $google_search;
		}
	}
	if ($postarray['t1'] == '' && $postarray['bookselected'] == '6')
	{
		return $awaiting_input;
	}
	if ($postarray['t1'] == '' && $postarray['bookselected'] == '6' && $postarray['listnick'] == '1')
	{
		return $list_nick_books;
	}

	return $init_page;

}
function build_booklists($t1,$nick,$shelf,$dbooks) {
global $wpdb;
if ($t1 == '' && $nick == null )
{
	$q = 1;
	$query =  "SELECT knt,title,bookid,author,googleid  FROM wp_bookshelf  order by knt desc, title limit 50";
}
elseif ( $t1 != null)
{
	$q = 1;
	$query =  "SELECT knt,title,bookid,author,googleid  FROM wp_bookshelf  where (title like '%$t1%' or author like '%$t1%' or bookid = '$t1')  order by knt desc, title limit 50 ";
}
elseif ( $nick != null && $shelf == null)
{
	$q = 2;
	$query = "select a.title,a.author,a.bookid,b.nick_id,b.shelf_name,a.knt,a.googleid from wp_bookshelf a  inner join  wp_bookshelf_personal b on a.bookid=b.bookid where b.nick_id = '$nick' order by  title";
} 
else
{
	$q = 3;
	$query = "select a.title,a.author,a.bookid,b.nick_id,b.shelf_name, a.knt,a.googleid  from wp_bookshelf a  inner join  wp_bookshelf_personal b on a.bookid=b.bookid where b.nick_id = '$nick' and b.shelf_name = '$shelf' order by  shelf_name,title";
}
$result = $wpdb->get_results( $query);
echo "<p hidden id='sqlquery_num'>$q</p>";

echo "<table id='table_of_books' border='2px' style='width:100%;'>";

var_dump($q);
switch ($q)
{
case 1:
	echo '  <tr>';
	echo '    <th>Count</th>';
	echo '    <th>Title</th>';
	echo '    <th>Author</th>';
	echo '    <th>ISBN</th>';
	echo '    <th>GoogleID</th>';
        if ($dbooks) {
            echo '    <th>Delete</th>';
        }
	echo "</tr>";
	$row_nm = 0;
        $k = 1;
	foreach ($result as $row ) {
                $row_nm =  "rlib". $k;
                        
                if (!$dbooks){
		        echo "<tr onclick='rowSelection(this,$dbooks)' class='row_class'>" ;
                } else {
		        echo "<tr onclick='rowSelection(this,$dbooks)' class='row_class_delete'>" ;
                }
			echo "<td>" . $row->knt . "</td>";
			echo "<td> " . $row->title . "</td>";
			echo "<td>" . $row->author . "</td>";
			echo "<td>" . $row->bookid . "</td>";
			echo "<td class='hideANDseek'>" . $row->googleid . "</td>";

                        if ($dbooks){
                        echo "<td><input id='$row_nm' type='checkbox' name='$row_nm' value='$k'  class='chk'></td>"; 
                        }
		echo "</tr>";
                $k += 1; 
            }
	break;
case 2:
	echo '  <tr>';
	echo '    <th>Title</th>';
	echo '    <th>Author</th>';
	echo '    <th>ISBN</th>';
	echo '    <th>Library</th>';
	echo '    <th>Shelf</th>';
	echo '    <th>GoogleID</th>';
	echo '    <th>Count</th>';
	echo '  </tr>';
	$row_nm = 0;
        $k = 1;
	foreach ($result as $row ) {
                $row_nm =  "rlib". $k;
                if (!$dbooks){
		        echo "<tr onclick='rowSelection(this,$dbooks)' class='row_class'>" ;
                } else {
		        echo "<tr onclick='rowSelection(this,$dbooks)' class='row_class_delete'>" ;
                }
			echo "<td>" . $row->title . "</td>";
			echo "<td>" . $row->author . "</td>";
			echo "<td>" . $row->bookid . "</td>";
			echo "<td>" . $row->nick_id . "</td>";
			echo "<td>" . $row->shelf_name . "</td>";
			echo "<td class='hideANDseek'>" . $row->googleid . "</td>";
			echo "<td>" . $row->knt . "</td>";
                        if ($dbooks){
                        echo "<td><input id='$row_nm' type='checkbox' name='$row_nm' value='$k'  class='chk'></td>"; 
                        }
		echo "</tr>";
                $k += 1; 
	}
	break;
case 3:
	echo '  <tr>';
	echo '    <th>Title</th>';
	echo '    <th>Author</th>';
	echo '    <th>ISBN</th>';
	echo '    <th>Nickname</th>';
	echo '    <th>Shelf</th>';
	echo '    <th>GoogleID</th>';
	echo '    <th>Count</th>';
	echo '  </tr>';
	$row_nm = 0;
        $k = 1;
	foreach ($result as $row ) {
                $row_nm =  "rlib". $k;
                if (!$dbooks){
		        echo "<tr onclick='rowSelection(this,$dbooks)' class='row_class'>" ;
                } else {
		        echo "<tr onclick='rowSelection(this,$dbooks)' class='row_class_delete'>" ;
                }
			echo "<td>" . $row->title . "</td>";
			echo "<td>" . $row->author . "</td>";
			echo "<td>" . $row->bookid . "</td>";
			echo "<td>" . $row->nick_id . "</td>";
			echo "<td>" . $row->shelf_name . "</td>";
			echo "<td class='hideANDseek'>" . $row->googleid . "</td>";
			echo "<td>" . $row->knt . "</td>";
                        if ($dbooks){
                        echo "<td><input id='$row_nm' type='checkbox' name='$row_nm' value='$k'  class='chk'></td>"; 
                        }
		echo "</tr>";
                $k += 1; 
	}
}
echo '</table>';
}
function bookdb_delete($books_tobe_deleted,$nick)
{
global $wpdb;
foreach($books_tobe_deleted as $book_key) {
        $q1 = "delete from wp_bookshelf_personal where bookid = '$book_key'";
        $q2 = "update wp_bookshelf set knt = knt -1 where bookid = '$book_key' and knt > 0";
        $result = $wpdb->get_results( $q1);
        $result = $wpdb->get_results( $q2);
	}
}
function bookdb_info($key ) {
        global $wpdb;
        $q = "select * from wp_bookshelf where bookid = '$key' ";
        $result = $wpdb->get_results( $q);
        return $result;
}
function clear_session($postarray,$sessionarray){
        $postarray = [];  
        $sessionarray = [];
        return;
}
