<?php
    function find_wordpress_base_path() {
        $dir = dirname(__FILE__);
        do {
           if( file_exists($dir."/wp-config.php") ) {
                return $dir;
            }
        } while( $dir = realpath("$dir/..") );
        return null;
    }

    define( 'BASE_PATH', find_wordpress_base_path()."/" );
    define('WP_USE_THEMES', false);
    global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header, $wpdb;
    require(BASE_PATH . 'wp-load.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
   /*
   * Collect all Details from Angular HTTP Request.
   */
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $response = [];
    @$transactionId = $request->transactionId;
    @$personal_lib= $request->personal_lib;
    @$personal_shelf= $request->personal_shelf;

    $query = '';
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
		                          from wp_bookshelf a  inner join  wp_bookshelf_personal b on a.bookid=b.book_id
		                          where b.nick_id = '$personal_lib' order by  title";
                                break;
	                    }

		            $query =  "SELECT knt,title,bookid,author,googleid 
		                FROM wp_bookshelf  order by knt desc, title limit 50";
                    }

		    $rows = array();
		    $result = $wpdb->get_results($query);

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
                $id= $request->id;
                $title= $bookInfo->title;
                $subtitle= $bookInfo->subtitle;
                $author=   $bookInfo->authors[0];
                $publisher= $bookInfo->publisher;
                $ind_id=  $bookInfo->industryIdentifiers[0]->identifier;
                $ind_type=   $bookInfo->industryIdentifiers[0]->type;
                $category=  $bookInfo->categories[0];
                $description=$bookInfo->description;
 		$response['insert_book'] = $wpdb->query($wpdb->prepare(                
            	"                                           
	                insert into `wp_bookshelf`                                                           
		    (
	            googleid,
	            title,
	            subtitle,
	            author,
	            publisher,
	            bookid,
	            ind_type,
	            category,
	            review,
                    knt
		    )
	                values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%d)                                   
	                on duplicate key update knt = knt + 1
             "                                                                                                                                                                                                                                                                                                                                            
               ,                                                                                                                                                                                                                                                                                                                                          
                  $id,$title,$subtitle,$author,$publisher,$ind_id,$ind_type,$category,$description,1)) ; 

                    if ($personal_lib == '') {
                        break;
                    }
		    $response['insert_shelf'] = $wpdb->query($wpdb->prepare(
            	    "
                    insert ignore into wp_bookshelf_personal 
                (nick_id,book_id,shelf_name)
                values (%s,%s,%s)
            "
             ,
                     $personal_lib,$ind_id,$personal_shelf));
                    $response['at-end']	= "got to save book block";
                    break;
                    
            default:
                    $response['error'] = "invalid transactionId:$transactionId";

            };
	    echo json_encode($response);
?>

