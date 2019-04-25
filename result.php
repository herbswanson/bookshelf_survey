<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
   /*
   * Collect all Details from Angular HTTP Request.
   */
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $response = [];
    $host     = "localhost";
    $user     = "herb";
    $password = "redwine";
    @$transactionId = $request->transactionId;
    @$personal_lib= $request->personal_lib;
    @$personal_shelf= $request->personal_shelf;

    $database = "bookshelf";
    switch ($transactionId) {

	    case "viewLib":

	            @$searchterm = $request->searchterm;
	            
                    while ($query == '') {


		            if ( strlen($searchterm) == 1) { 
		                $query =  "SELECT knt,title,bookid,author,googleid 
		                           FROM wp_bookshelf where title like '$searchterm%' order by  title ";
                                break;
			    }


			    if ( $searchterm == "" && $personal_lib == "") { 
		                $query =  "SELECT knt,title,bookid,author,googleid 
		                           FROM wp_bookshelf  order by knt desc, title limit 50";
                                break;
			    }
			
				    if ( $searchterm != "" && $personal_lib == "") { 
		                $query =  "SELECT knt,title,bookid,author,googleid 
		                           FROM wp_bookshelf   
		                           where (title like '%$searchterm%' or author like '%$searchterm%' or bookid = '$searchterm') 
		                           order by knt desc, title limit 50 ";
                                break;
			    }
			
			    if (  $personal_lib != "") { 
		                $query = "select a.title,a.author,a.bookid,b.nick_id,b.shelf_name,a.knt,a.googleid
		                          from wp_bookshelf a  inner join  wp_bookshelf_personal b on a.bookid=b.bookid
		                          where b.nick_id = '$personal_lib' order by  title";
                                break;
	                    }

		            $query =  "SELECT knt,title,bookid,author,googleid 
		                FROM wp_bookshelf  order by knt desc, title limit 50";
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
                @$googleid = $request->googleid;
                $url = "https://www.googleapis.com/books/v1/volumes/" . $googleid;
                $results = file_get_contents($url);
                $request = json_decode($results);
                $bookInfo = $request->volumeInfo;

		    $wpdb = new mysqli($host, $user, $password, $database);
		    if ($wpdb->connect_errno) {
	               $response['sqlerror'] = "$wpdb->connect_errno";
	               break;
		    }
                     $id= mysqli_real_escape_string($wpdb ,$request->id);
                    $title= mysqli_real_escape_string($wpdb , $bookInfo->title);
                    $subtitle= mysqli_real_escape_string($wpdb ,  $bookInfo->subtitle);
                    $author= mysqli_real_escape_string($wpdb ,  $bookInfo->authors[0]);
                    $publisher= mysqli_real_escape_string($wpdb ,  $bookInfo->publisher);
                    $ind_id= mysqli_real_escape_string($wpdb , $bookInfo->industryIdentifiers[0]->identifier);
                    $ind_type=  mysqli_real_escape_string($wpdb , $bookInfo->industryIdentifiers[0]->type);
                    $category=mysqli_real_escape_string($wpdb ,  $bookInfo->categories[0]);
                    $description= mysqli_real_escape_string($wpdb ,  $bookInfo->description);
                    $sql = "
                        insert into wp_bookshelf
                     (googleid,
	            title,
	            subtitle,
	            author,
	            publisher,
	            bookid,
	            ind_type,
	            category,
	            review
                    ) VALUES (
                    '$id',
                    '$title',
                    '$subtitle',
                    '$author',
                    '$publisher',
                    '$ind_id',
                    '$ind_type',
                    '$category',
                    '$description')
                    on duplicate key update knt = knt + 1"; 
                    
		    $response['query'] = $wpdb->query($sql);

                    if ($personal_lib == '') {
                        break;
                    }
                    $sql = "insert ignore into wp_bookshelf_personal
                        (nick_id,
                        bookid,
                        shelf_name
                        ) values (
                        '$personal_lib',
                        '$ind_id',
                        '$personal_shelf')";

		    $response['query'] = $wpdb->query($sql);

                    $response['at-end']	= "got to save book block";
                    break;
                    
            default:
                    $response['error'] = "invalid transactionId:$transactionId";

            };
	    echo json_encode($response);
?>

