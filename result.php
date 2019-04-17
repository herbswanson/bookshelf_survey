<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
   /*
   * Collect all Details from Angular HTTP Request.
   */
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $response = [];
    @$transactionId = $request->transactionId;
    switch ($transactionId) {

	    case "viewLib":

	            @$searchterm = $request->searchterm;
	            @$personal_lib= $request->personal_lib;
	            @$personal_shelf= $request->personal_shelf;
	
	            $query =  "SELECT knt,title,bookid,author,googleid 
	                FROM wp_bookshelf  order by knt desc, title limit 50";
		    if ( $searchterm == "" && $personal_lib == "") { 
	                $query =  "SELECT knt,title,bookid,author,googleid 
	                           FROM wp_bookshelf  order by knt desc, title limit 50";
		    }
		
		    if ( $searchterm != "" && $personal_lib == "") { 
	                $query =  "SELECT knt,title,bookid,author,googleid 
	                           FROM wp_bookshelf   
	                           where (title like '%$searchterm%' or author like '%$searchterm%' or bookid = '$searchterm') 
	                           order by knt desc, title limit 50 ";
		    }
		
		    if (  $personal_lib != "") { 
	                $query = "select a.title,a.author,a.bookid,b.nick_id,b.shelf_name,a.knt,a.googleid
	                          from wp_bookshelf a  inner join  wp_bookshelf_personal b on a.bookid=b.bookid
	                          where b.nick_id = '$personal_lib' order by  title";
		    }
		
		    $host     = "localhost";
		    $user     = "herb";
		    $password = "redwine";
		    $database = "bookshelf";
		    $wpdb = new mysqli($host, $user, $password, $database);
		    if ($wpdb->connect_errno) {
	               $response['sqlerror'] = "$wpdb->connect_errno";
	               break;
		    }
		    $rows = array();
		    $result = $wpdb->query($query);
		
		    foreach ($result as $r ) {
	               $rows[]=$r;
		    }
	            $response = $rows;
		    $wpdb->close();
	            break;
            case "saveBook":

                    $response['error']	= "got to save book block";
                    break;
                    
            default:

            $response['error'] = "invalid transactionId:$transactionId";
}
	    echo json_encode($response);
?>
