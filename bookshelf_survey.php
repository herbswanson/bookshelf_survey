<?php /* Template Name: BookShelf_Survey */ ?>
<!DOCTYPE html> <head>
<title> Saker BookShelf</title>
<meta  content="HTML,CSS,XML,JavaScript" charset="utf-8">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic">
<link rel='stylesheet' href='https://material.angularjs.org/1.1.4/docs.css'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'>
<!--
<link rel='stylesheet' href='new_bookshelf_survey.css'>
-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"> </script>
<script>
$(document).ready(function() {



    /*****************************************
    $('#help_button').click(function(){
        $('#help_page').toggle();
    });
    $("#view_library").click(function(event) {
               $.post( 
                  "result.php",
                  { name: "Zara" },
                  function(data) {
                     jobj = JSON.parse(data);
                     $('#stage').html(data);
                  }
               );

       
    });
    ****************************************/
});
  // all custom jQuery will go here
</script>
<style>
table, th , td {
  border: 1px solid grey;
  border-collapse: collapse;
  padding: 5px;

}

table tr:nth-child(odd) {
  background-color: #f1f1f1;

}

table tr:nth-child(even) {
  background-color: #ffffff;

}
.container {
  display: grid;
  grid-gap: 10px;
  padding: 0px;
  width:1100px;
}

.container > div {
  text-align: center;
  padding: 2px 0;
  font-size: 30px;
}
.row.grid-divider [class*='col-']:not(:last-child):after {
  background: #000000;
  width: 5px;
  content: "";
  display:block;
  position: absolute;
  top:0;
  bottom: 0;
  right: 0;
  min-height: 70px;
}
.btn-primary:hover, .btn-primary:focus, .btn-primary:active,
.btn-primary.active, .open.dropdown-toggle.btn-primary {
        color: black;
        background-color:gold ;
        border-color: #285e8e; /*set the color you want here*/
                            
}

.bordered {
	border-width:10px 10px 10px 10px;
	border-color: white;
}
#bird {
  background: #7989dd;
  color: #fff;
}
#book-heading {
    font-weight:900;
    color:blue;
}
.display_text {
    text-align:left;
}
.hidden {
    { display: none }
}
.row_m_top{
    margin-top:15px;
}
.row_class:hover {
    background-color: darksalmon;
 }

</style>

</head>

<body ng-app="bookApp" ng-controller="book_Ctrl">

<div class="container">
<div class="row">
    <div class="col-md-4">
        <div class="well" id="bird">
            <h3>Saker BookShelf</h3>
            <img src="http://vsaker.net/wp-content/uploads/2019/02/saker_body-300x200.jpg" alt="" width="280" height="200" class="alignnone size-medium wp-image-200">
            <p> We are asking our readers to submit the titles and authors of their favorite books. These will be reviewed and selected books will be included in the BookShelf.
	</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div>
                <h2 id='book-heading'>Favorites</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4"></div class="col-md-4">
     <div class="col-md-8">
        <div class="row">
            <div>
                    <form id="theBookForm"   name="theBookForm" action="" method="post" >     
		    <div>
                    <label style="font-size:30px; font-weight:800; " for="inputlg">Enter title, author, isbn number</label>
                    <input value='' id='searchterm' ng-model="searchTerm"  ng-model-options="{ debounce: 800 }" ng-enter="searchBook()" class="form-control input-lg" placeholder="Enter book title" autofocus />
<!--
                    <input value='' id='searchterm' ng-model="searchTerm"  ng-model-options="{ debounce: 800 }"  class="form-control input-lg" placeholder="Enter book title" autofocus />
-->
	            <input type="hidden" name="bookshelf_survey" value="1">
	            <input type="hidden" name="bookselected" value="6">
	            <input type="hidden" name="books_to_delete" value="">
	            <input type="hidden" name="google_book_id" value="" ng-model="google_book_id">
	            <input type="hidden" name="google_book_title" value="" ng-model="google_book_title">
                    </form>
	    </div>	
<div>
           
        <div>
            <div class="row row_m_top">
                    <div class="col-md-3 bordered ">
		        <button  ng-click="searchBook()" type="button" class="btn btn-primary btn-lg btn-block" id="search_button">Search for Book</button>
                    </div>
		    <div class="col-md-3 bordered ">
		        <button  ng-click='saveBook()' type="button" class="btn btn-primary btn-lg btn-block" id="save_button">Save Book</button>
                    </div>
		    <div class="col-md-3 bordered ">
		        <button  ng-click='viewLibrary();' type="button" class="btn btn-primary btn-lg btn-block" id="view_library">Search Library</button> 
                    </div>
		    <div class="col-md-3 bordered border-right-0">
		        <button  ng-click="deleteBook()" type="button" class="btn btn-primary btn-lg btn-block" id="delete_button" ng-disabled="isDisabled">Delete Book</button> 
                    </div>
 
            </div>
  
           
            <div class="row row_m_top">
		    <div class="col-md-3 bordered ">
		        <button ng-click="helpPage()" type="button" class="btn btn-primary btn-lg btn-block" id="help_button">Help</button>
                    </div>
		    <div class="col-md-3 bordered ">
		        <button ng-click="reloadPage();"  type="button" class="btn btn-primary btn-lg btn-block" id="clear_button">Reset</button>
                    </div>
		    <div class="col-md-3 bordered ">
                            <a href="/export_bookshelf.ods" download>
                                <button type="button" class="btn btn-primary btn-lg btn-block">Download</button>
                            </a>
<!--		        <button  ng-click=downloadSpreadSheet() type="button" class="btn btn-primary btn-lg btn-block" id="download_button">Download Lib</button> -->
                    </div>
		    <div class="col-md-3 bordered ">
                    </div>
            </div>
            <div class="row row_m_top">
		    <div class="col-md-6 bordered ">
                    </div>
		    <div class="col-md-6 bordered ">
			   <label style="font-size:16px; font-weight:800; " for="inputlg">My Library (Name)</label>
                    </div>
            </div>
            <div class="row row_m_top">
		    <div class="col-md-3 bordered ">
                    </div>
		    <div class="col-md-3 bordered ">
                    </div>
                    <div class="col-md-6">
<!--
                        <form name="personalLib">
                           <input value='' ng-model="personal_lib" class=" input-lg"  ng-pattern="/^[a-zA-Z_-.\s]*$/" id="nick_in" type="text" placeholder="Optional not required ..." name="personal_lib">
                            <div ng-messages="personalLib.personal_lib.$error">
                                <div ng-message="pattern">Invalid characters.
                            </div>
                        </div> 
                        </form>
-->
				<form name="personalLibForm">
				  <input ng-model="personal_lib" name="personal_lib" ng-pattern="/^[\w\-\_\s\.]+$/" class="input-lg"  placeholder="Optional not required ..." value='' />
				  <div ng-messages="personalLibForm.personal_lib.$error">
				      <div style="color:blue" ng-message="pattern">Invalid characters.</div>
				  </div>
				</form>
				
                    </div>
            </div>
            <div class="row ">
		    <div class="col-md-3 bordered ">
                    </div>
		    <div class="col-md-3 bordered ">
                    </div>
                    <div class="col-md-6">
			   <label style="font-size:16px; font-weight:800; " for="inputlg">BookShelf</label>
                    </div>
            </div>
            <div class="row row_m_top">
		    <div class="col-md-3 bordered ">
                    </div>
		    <div class="col-md-3 bordered ">
                    </div>
                    <div class="col-md-6">
				<form name="personalShelfForm">
				  <input ng-model="personal_shelf" name="personal_shelf" ng-pattern="/^[a-zA-Z_-]*$/" class="input-lg"  placeholder="Optional not required ..." value='' />
				  <div ng-messages="personalShelfForm.personal_shelf.$error">
				      <div style="color:blue" ng-message="pattern">Invalid characters.</div>
				  </div>
				</form>
	
<!--
                           <input value='' ng-model="personal_shelf" class="forms-control input-lg" id="shelf_in" type="text" placeholder="Optional not required ..." name="bs_personal">
-->
                    </div>
            </div>

            </div> <!-- buttons plus mylib -->
            </div> <!-- 'form' division -->
        
            </div> <!-- buttons plus mylib -->
            </div> <!-- 'form' division -->
        </div> <!-- division containing forms and buttons -->
     </div> <!-- 8 column row 'forms & buttons & library' -->
  </div> <!-- four column row 'bird' plus the 8 column buttons forms & mylib -->
     <div class="col-md-6" id = "stage" style = "background-color:cc0;">
        <p style="font-size:16px; font-weight:800; color:blue; ">  {{message}}</p>
      </div>

     <div class="col-md-6">
     </div>

<?php
    readfile("/var/www/wordpress/wp-content/themes/yaaburnee-themes-child/bookshelf_help.html");
    readfile("/var/www/wordpress/wp-content/themes/yaaburnee-themes-child/bookshelf_book.html");
?>
<div>
</div>

  <table border="1" ng-show="showLibrary">
    <tr>
        <th ng-repeat="column in cols">{{column}}</th>
    </tr>
    <tr ng-repeat="row in rows" class="row_class" ng-click="rowSelected(this)">
      <td ng-repeat="column in cols">{{row[column]}}</td>
    </tr>
  </table>
</div> <!-- container -->


   
<script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.js'></script>
<script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-animate.min.js'></script>
<script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-route.min.js'></script>
<script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-aria.min.js'></script>
<script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-messages.min.js'></script>
<script src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/t-114/svg-assets-cache.js'></script>
<!--<script src='https://cdn.gitcdn.link/cdn/angular/bower-material/v1.1.4/angular-material.js'></script> -->
<script src='https://ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min.js'></script>
<script>

         /*****************  app creation **************************/
  var app = angular.module('bookApp', ['ngRoute', 'ngMaterial', 'ngMessages', 'material.svgAssetsCache']);
    
app.directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.ngEnter);
                });

                event.preventDefault();
            }
        });
    };
});
 

  app.controller('book_Ctrl', function($scope,$route,$http,$document) {

      console.log('entering in app.controller val scope:'+$scope);
      $scope.google_book_title = '';
      $scope.personal_lib = "";
      $scope.searchTerm = "";
      purchaseSite_p1 = "https://www.bookfinder.com/search/?lang=en&currency=USD&ac=qr&isbn=";
      purchaseSite_p2 = "&mode=basic&destination=us&new_used=*&st=sr";
      fetch(0);
      $scope.index = 0;
      $scope.tables = [];
      $scope.showSearch = false;
      $scope.showLibrary = false;
      $scope.showHelp = false;
      $scope.isDisabled = true;

      
      showPage= function(s) {

          $scope.showSearch = false;
          $scope.showLibrary = false;
          $scope.showHelp = false;
          
          switch(s) {
              
          case 'helpSection':

              $scope.showHelp = true;
              break;
          

	  case 'librarySection': 
              $scope.showLibrary = true;
              break;
		
	  
	  case 'searchSection': 
              $scope.showSearch = true;
              break;
          
	  case 'deleteSection': 
              break;

	  case 'reloadSection': 
            
                $scope.showSearch = false;
                $scope.showLibrary = false;
                $scope.showHelp = false;
                break;

       	  case 'allOff': 
            
                $scope.showSearch = false;
                $scope.showLibrary = false;
                $scope.showHelp = false;
                break;

          default:

                $scope.showSearch = false;
                $scope.showLibrary = false;
                $scope.showHelp = false;
          }
      }

      $scope.helpPage = function() {
        showPage('helpSection');
      }

      $scope.deleteBook = function () {
          console.log('here within deleteBook');
          console.log('personal_lib:'+$scope.personal_lib);
          if ($scope.google_book_id == '') {return;}
          var request = $http({
		    method: "post",
		    url: "/result.php",
                    data: {
                            transactionId: 'deleteBook',
                            googleid: $scope.google_book_id,
                            personal_lib: $scope.personal_lib,
                            personal_shelf: $scope.personal_shelf
		    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                });
                
		request.success(function (result) {
                 $scope.message = result;
          console.log('result'+{result});
                 if (typeof result.bookDelete_nonadmin_personal !== 'undefined' && result.bookDelete_nonadmin_personal == 512) {
                        $scope.message = 'Book is in use in Private Libraries';
                 }
                else if(typeof result.bookDelete_nonadmin_personal !== 'undefined' && result.bookDelete_nonadmin_personal == 513) {
                        $scope.message = 'Another reader has added this book .. (cannot remove)';
                 }
                 else if(typeof result.bookDelete_nonadmin_personal !== 'undefined' && result.bookDelete_nonadmin_personal >= 1) {
                        $scope.message = 'Book Deleted from your Library';
                 }
                 else if(typeof result.bookDelete_nonadmin_bookshelf !== 'undefined' && result.bookDelete_nonadmin_bookshelf >= 1 )
                 {
                        $scope.message = 'Book Deleted from general Library';
                 }
                 else if(typeof result.bookDelete_admin_lib !== 'undefined' && result.bookDelete_admin_lib >= 1 )
                 {
                        $scope.message = 'Admin Deleted Book from general Library';
                 }
                 else {$scope.message = 'Book cannot be Deleted from General Library';}
                $scope.isDisabled = true;
                $scope.viewLibrary();
                });
         
      }

      $scope.rowSelected = function(x) {
          $scope.google_book_id = x.row.googleid;
          console.log('here within rowSelected');
          fetch(1);
          $scope.isDisabled = false;
          showPage('searchSection');
      }
 
     
      $scope.viewLibrary = function(){
            if (typeof $scope.searchTerm == 'undefined') {$scope.searchTerm = '';}
            if (typeof $scope.personal_lib == 'undefined') {$scope.personal_lib = '';}
            if (typeof $scope.personal_shelf == 'undefined') {$scope.personal_shelf = '';}
            $scope.showSearch = false;
            showPage('librarySection');
            var request = $http({
		    method: "post",
		    url: "/result.php",
                    data: {
                            transactionId: 'viewLib',
                            searchterm: $scope.searchTerm,
                            personal_lib: $scope.personal_lib,
                            personal_shelf: $scope.personal_shelf
		    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                });
                    
		request.success(function (result) {
                $scope.rows = result;
                if(typeof $scope.rows[0] == 'undefined'){
                    $scope.message = 'Not found in Library';
                } else {
                   $scope.cols = Object.keys($scope.rows[0]);
                }
            });
        };
      $scope.saveBook = function(){
          console.log('here within saveBook'+$scope.google_book_id);
          if ($scope.google_book_id == '' || typeof  $scope.google_book_id === 'undefined') {return;}
          var request = $http({
		    method: "post",
		    url: "/result.php",
                    data: {
                            transactionId: 'saveBook',
                            googleid: $scope.google_book_id,
                            personal_lib: $scope.personal_lib,
                            personal_shelf: $scope.personal_shelf
		    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                });
                
		request.success(function (result) {
                    console.log(result);
                    console.log(Object.keys(result));
                    console.log(result.insert_lib);
                    console.log(result["insert_lib"]);
                $scope.message = result;
                    if (typeof result.insert_lib !== 'undefined' && result.insert_lib >= 1) {
                        $scope.message = 'Book Added to your Library';
                    }
                    else if(typeof result.insert_book !== 'undefined' && result.insert_book >= 1 )
                    {
                        console.log('insert_book:'+result.insert_book);
                        $scope.message = 'Book Added to general Library';
                    }
                    else {$scope.message = 'Book already in Library';}
/*
                    if (result.insert_book == 1 or result.insert_lib == 1) {
                        console.log("got hit");
                    }
                    else {
                        console.log("not hit");
                    }
                     */
                });
      }

      $scope.reloadPage = function(){
          console.log('here within reloadpage');
          showPage('reloadSection');
          window.location.reload();
      }

      /***********
    $scope.enterPressed = function () {
	console.log("enter has been pressed");
        fetch(0);
        $scope.showSearch = function(){
            return true;
        }
    }
    ***********/

    $scope.searchBook = function(){
        console.log('got click from search'+$scope.searchTerm);
        $scope.google_book_title = $scope.searchTerm;
        console.log('hexdump of searchterm:'+$scope.searchTerm.toString(16));
       fetch(0);
       showPage('searchSection');
}

    function fetch(getType) {
        $scope.message = '';
        if (typeof getType === "undefined") {var getType = 0;}
        if (getType == 1) {
            $http.get("https://www.googleapis.com/books/v1/volumes/"+ $scope.google_book_id).then(function(res2) {
                $scope.google_book_id  = res2.data.id;
                $scope.bookInfo = res2.data.volumeInfo;
                $scope.bookInfo.description = strip_html_tags(res2.data.volumeInfo.description);
                $scope.saleInfo = res2.data.saleInfo;
        //        $scope.searchTerm = res2.data.volumeInfo.title;

                console.log('within fetch type 1');
                $scope.google_book_title = $scope.bookInfo.title;
                if (typeof $scope.bookInfo.industryIdentifiers != 'undefined') {
                        isbnRe = /ISBN/;
	                if (isbnRe.test($scope.bookInfo.industryIdentifiers[0]['type'])) {
	                    $scope.purchaseSite = purchaseSite_p1+$scope.bookInfo.industryIdentifiers[0]['identifier']+purchaseSite_p2;
                        } else {
                            $scope.message ='No ISBN number for book';
                        }     
                } else {
                            $scope.message = 'No Industry Identifier eg ISBN for book';
                            console.log('undefined industryIdentifiers');
                }     
                
                $http.get("https://www.googleapis.com/books/v1/volumes?q=" + $scope.google_book_title).then(function(res) {
                     $scope.relatedBooks = res.data.items;
                });
            });
        } else {

            $http.get("https://www.googleapis.com/books/v1/volumes?q=" + $scope.google_book_title).then(function(res) {
  		console.log(res.data);

              document.theBookForm.google_book_id.value = res.data.items[0]['id'];
      //          alert(document.theBookForm.bookselected.value);
                 $scope.google_book_id  = res.data.items[0].id;
                 $scope.relatedBooks = res.data.items;
                 $scope.bookInfo = res.data.items[0].volumeInfo;
                 $scope.saleInfo = res.data.items[0].saleInfo;
                 $scope.related = res.data;
                 $scope.searchTerm = res.data.items[0].volumeInfo.title;
                 $scope.google_book_title = $scope.bookInfo.title;

                console.log('within fetch type 2');
                if ($scope.bookInfo.industryIdentifiers[0]['type'] != null) {
                    isbnRe = /ISBN/;
                    if (isbnRe.test($scope.bookInfo.industryIdentifiers[0]['type'])) {
                        $scope.purchaseSite = purchaseSite_p1+$scope.bookInfo.industryIdentifiers[0]['identifier']+purchaseSite_p2;
                    }
                }
              });
        }
                $scope.searchTerm = ''; 
    }
      
    $scope.update = function(book) {
        $scope.google_book_title = book.volumeInfo.title;
        $scope.searchTerm = ''; 
        if ($scope.google_book_id != book.id) {
            $scope.google_book_id= book.id;
            fetch(1);
        } else {
            $scope.google_book_title = book.volumeInfo.title;
            fetch(0);
        }
      console.log("now within update:"+book.volumeInfo.title)
    };

    function bufferToHex(buffer) {
            var s = '', h = '0123456789ABCDEF';
                (new Uint8Array(buffer)).forEach((v) => { s += h[v >> 4] + h[v & 15];  });
                return s;
                
    }
    function strip_html_tags(str)
    {
           if ((str==null) || (str=='' ))
                      return ' ';
             else
                    str = str.toString();
             return str.replace(/<[^>]*>/g, '');
           
    }

})
</script>
</body>
</html>
