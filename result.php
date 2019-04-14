<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
   /*
   * Collect all Details from Angular HTTP Request.
   */
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    @$searchterm = $request->searchterm;
    /*
    @$personal_lib= $request->personal_lib;
    @$personal_shelf= $request->personal_shelf;
    $personal_lib = "";
    $personal_shelf = "";
    $query =  "SELECT knt,title,bookid,author,googleid  FROM wp_bookshelf  order by knt desc, title limit 50";
    if ( $searchterm == "" && $personal_lib == "" { 
        $query =  "SELECT knt,title,bookid,author,googleid  FROM wp_bookshelf  order by knt desc, title limit 50";
    }

    if ( $searchterm != "" && $personal_lib == "" { 
    	$query =  "SELECT knt,title,bookid,author,googleid  FROM wp_bookshelf  where (title like '%$searchterm%' or author like '%$searchterm%' or bookid = '$searchterm')  order by knt desc, title limit 50 ";
    }

    if (  $personal_lib != "" { 
	$query = "select a.title,a.author,a.bookid,b.personal_lib_id,b.shelf_name,a.knt,a.googleid from wp_bookshelf a  inner join  wp_bookshelf_personal b on a.bookid=b.bookid where b.personal_lib_id = '$personal_lib' order by  title";
    }
     */
//    echo $email; //this will go back under "data" of angular call.
    /*
     * You can use $email and $pass for further work. Such as Database calls.
    */    
    	$query =  "SELECT knt,title,bookid,author,googleid  FROM wp_bookshelf  where (title like '%$searchterm%' or author like '%$searchterm%' or bookid = '$searchterm')  order by knt desc, title limit 50 ";
$host     = "localhost";
$user     = "herb";
$password = "redwine";
$database = "bookshelf";
$wpdb = new mysqli($host, $user, $password, $database);
if ($wpdb->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        
}
$rows = array();
$result = $wpdb->query($query);

foreach ($result as $r ) {
    $rows[]=$r;
}

//header ("Content-type:application/json")
echo json_encode($rows);
$wpdb->close();
?>
