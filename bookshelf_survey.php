<!DOCTYPE html> <head>
<title> Saker BookShelf</title>
<meta  content="HTML,CSS,XML,JavaScript" charset="utf-8">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic">
<link rel='stylesheet' href='https://material.angularjs.org/1.1.4/docs.css'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'>
<link rel='stylesheet' href='new_bookshelf_survey.css'>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"> </script>
<script>
$(document).ready(function() {



    $('#help_button').click(function(){
        $('#help_page').toggle();
    });
    /*****************************************
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
    <div class="col-md-8">
        <div class="row">
            <div>
                <h2 id='book-heading'>Favorite Books</h2>
            </div>
        </div>
    </div>
     <div class="col-md-8">
        <div class="row">
            <div>
                    <form id="theBookForm"   name="theBookForm" action="" method="post" >     
		    <div>
                    <label style="font-size:30px; font-weight:800; " for="inputlg">Enter title, author, isbn number</label>
                    <input value='' id='searchterm' ng-model="searchTerm"  ng-model-options="{ debounce: 800 }" ng-keydown ="event.keyCode === 13 && searchBook()" class="form-control input-lg" placeholder="Enter book title" autofocus />
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
		        <button  type="button" class="btn btn-primary btn-lg btn-block" id="save_button">Save Book</button>
                    </div>
		    <div class="col-md-3 bordered ">
		        <button  ng-click='viewLibrary();' type="button" class="btn btn-primary btn-lg btn-block" id="view_library">View Library</button> 
                    </div>
		    <div class="col-md-3 bordered border-right-0">
		        <button  type="button" class="btn btn-primary btn-lg btn-block" id="delete_button">Delete Book</button>
                    </div>
 
            </div>
  
           
            <div class="row row_m_top">
		    <div class="col-md-3 bordered ">
		        <button  type="button" class="btn btn-primary btn-lg btn-block" id="help_button">Help</button>
                    </div>
		    <div class="col-md-3 bordered ">
		        <button ng-click="reloadPage();"  type="button" class="btn btn-primary btn-lg btn-block" id="clear_button">Reset</button>
                    </div>
                    <div class="col-md-6">
			   <label style="font-size:16px; font-weight:800; " for="inputlg">My Library (Name)</label>
                    </div>
            </div>
            <div class="row row_m_top">
		    <div class="col-md-3 bordered ">
                    </div>
		    <div class="col-md-3 bordered ">
                    </div>
                    <div class="col-md-6">
                           <input value='' ng-model="personal_lib" class="forms-control input-lg" id="nick_in" type="text" placeholder="Optional not required ..." name="nick">
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
                           <input value='' ng-model="personal_shelf" class="forms-control input-lg" id="shelf_in" type="text" placeholder="Optional not required ..." name="bs_personal">
                    </div>
            </div>

            </div> <!-- buttons plus mylib -->
            </div> <!-- 'form' division -->
        
            </div> <!-- buttons plus mylib -->
            </div> <!-- 'form' division -->
        </div> <!-- division containing forms and buttons -->
     </div> <!-- 8 column row 'forms & buttons & library' -->
  </div> <!-- four column row 'bird' plus the 8 column buttons forms & mylib -->
     <div id = "stage" style = "background-color:cc0;">
         STAGE
      </div>

<p> hello world {{searchTerm}}</p>

<?php
    readfile("./bookshelf_help.html");
    readfile("./bookshelf_book.html");
?>
<div>
</div>

  <table border="1">
    <tr>
        <th ng-repeat="column in cols">{{column}}</th>
    </tr>
    <tr ng-repeat="row in rows">
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
<script src='https://cdn.gitcdn.link/cdn/angular/bower-material/v1.1.4/angular-material.js'></script>
<script>

         /*****************  app creation **************************/
  var app = angular.module('bookApp', ['ngRoute', 'ngMaterial', 'ngMessages', 'material.svgAssetsCache']);
    
 /*
This directive allows us to pass a function in on an enter key to do what we want.
https://eric.sau.pe/angularjs-detect-enter-key-ngenter/
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
 */
/***********************************************************************************/
 

  app.controller('book_Ctrl', function($scope,$route,$http,$document) {

      console.log('entering in app.controller val scope:'+$scope);
      $scope.google_book_title = '';
      $scope.searchTerm = "";
      fetch(0);
      $scope.index = 0;
      $scope.tables = [];

      $scope.viewLibrary = function(){
            if (typeof $scope.searchTerm == 'undefined') {$scope.searchTerm = '';}
            if (typeof $scope.personal_lib == 'undefined') {$scope.personal_lib = '';}
            if (typeof $scope.personal_shelf == 'undefined') {$scope.personal_shelf = '';}
            $scope.showSearch = false;
            $scope.showSearch = function(){
                return false;
            }
            var request = $http({
		    method: "post",
		    url: "/result.php",
                    data: {
                            searchterm: $scope.searchTerm,
                            personal_lib: $scope.personal_lib,
                                /*
                            pass: $scope.personal_shelf
                                 */
		    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                });

                
		request.success(function (result) {
                $scope.rows = result;
                $scope.cols = Object.keys($scope.rows[0]);
                });
        }

      $scope.reloadPage = function(){
          console.log('here within reloadpage');
          window.location.reload();
      }

    $scope.enterPressed = function () {
	console.log("enter has been pressed");
        fetch();
        $scope.showSearch = function(){
            return true;
        }
    }

    $scope.searchBook = function() {
        console.log('got click from search'+$scope.searchTerm);
        $scope.google_book_title = $scope.searchTerm;
       fetch(0);
       $scope.showSearch = function(){
            return true;
        }
}

    function fetch(getType) {
        if (typeof getType === "undefined") {var getType = 0;}
        if (getType == 1) {
            $http.get("https://www.googleapis.com/books/v1/volumes/"+ $scope.google_book_id).then(function(res2) {
                $scope.google_book_id  = res2.data.id;
                $scope.bookInfo = res2.data.volumeInfo;
                $scope.saleInfo = res2.data.saleInfo;
        //        $scope.searchTerm = res2.data.volumeInfo.title;
                $scope.google_book_title = $scope.bookInfo.title;
            });
            $http.get("https://www.googleapis.com/books/v1/volumes?q=" + $scope.google_book_title).then(function(res) {
                 $scope.relatedBooks = res.data.items;
           
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
         //        $scope.searchTerm = res.data.items[0].volumeInfo.title;
                 $scope.google_book_title = $scope.bookInfo.title;
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
})
</script>
</body>
</html>
