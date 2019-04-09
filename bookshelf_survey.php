<!DOCTYPE html>
<head>
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
})
  // all custom jQuery will go here
});
</script>
<style>
.container {
  display: grid;
  grid-gap: 10px;
  padding: 0px;
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

</style>

</head>

<body ng-app="bookApp" ng-controller="book_Ctrl">
<div class="container">
<div class="row">
    <div class="col-md-4">
        <div class="well" id="bird">
            <h3>Saker BookShelf</h3>
            <img src="http://vsaker.net/wp-content/uploads/2019/02/saker_body-300x200.jpg" alt="" width="300" height="200" class="alignnone size-medium wp-image-200">
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
                    <form id="theBookForm" name="theBookForm" action="" method="post" >     
		    <div class="searchterm">
                    <label style="font-size:30px; font-weight:800; " for="inputlg">Enter title, author, isbn number</label>
                    <input ng-model="search" ng-model-options="{ debounce: 800 }" onclick="select()" class="form-control input-lg" placeholder="Enter book title" autofocus />
	            <input type="hidden" name="bookshelf_survey" value="1">
	            <input type="hidden" name="bookselected" value="6">
	            <input type="hidden" name="books_to_delete" value="">
	            <input type="hidden" name="google_book_id" value="">
                    </form>
	    </div>	
            <div class="row"> <div class="col-md-3 bordered ">
		        <button  type="button" class="btn btn-primary btn-lg" id="search_button">Search for Book</button>
                    </div>
		    <div class="col-md-3 bordered ">
		        <button  type="button" class="btn btn-primary btn-lg" id="view_button">View Library</button>
                    </div>
		    <div class="col-md-3 bordered ">
		        <button  type="button" class="btn btn-primary btn-lg" id="delete_button">Delete Book</button>
                    </div>
		    <div class="col-md-3 bordered ">
		        <button  type="button" class="btn btn-primary btn-lg" id="clear_button">Clear</button>
            </div>
            <div class="row">
                    <div class="col-md-6">
			   <label style="font-size:16px; font-weight:800; " for="inputlg">My Library (Name)</label>
                           <input class="forms-control input-lg" id="nick_in" type="text" placeholder="Optional not required ..." name="nick">
                    </div>
                    <div class="col-md-6">
                            <label style="font-size:16px; font-weight:800; " for="inputlg">My Bookshelf</label>
                            <input class="forms-control input-lg" id="shelf_in" type="text" placeholder="Optional not required ..." name="bs_personal">
                    </div>
            </div>
            <div class="row">
                    <div class="col-md-6">
                        <button  type="button" class="btn btn-primary btn-lg " id="help_button">Help</button>
                    </div>
                    <div class="col-md-6"></div>
            </div>
            

            </div> <!-- buttons plus mylib -->
            </div> <!-- 'form' division -->
        </div> <!-- division containing forms and buttons -->
     </div> <!-- 8 column row 'forms & buttons & library' -->
  </div> <!-- four column row 'bird' plus the 8 column buttons forms & mylib -->
</div> <!-- container -->

<?php
    readfile("./bookshelf_help.html");
    readfile("./bookshelf_book.html");
?>



   
<script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.js'></script>
<script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-animate.min.js'></script>
<script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-route.min.js'></script>
<script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-aria.min.js'></script>
<script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-messages.min.js'></script>
<script src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/t-114/svg-assets-cache.js'></script>
<script src='https://cdn.gitcdn.link/cdn/angular/bower-material/v1.1.4/angular-material.js'></script>
<script>
  var app = angular.module('bookApp', ['ngMaterial', 'ngMessages', 'material.svgAssetsCache']);
  app.controller('book_Ctrl', function($scope,$http) {
   
    $scope.$watch('search', function() {
    fetch();
    });
    $scope.search = "The Essential Saker";

    function fetch() {
    $http.get("https://www.googleapis.com/books/v1/volumes?q=" + $scope.search).then(function(res) {
  		console.log(res.data);

      document.theBookForm.google_book_id.value = res.data.items[0]['id'];
      //          alert(document.theBookForm.bookselected.value);
      $scope.relatedBooks = res.data.items;
      $scope.bookInfo = res.data.items[0].volumeInfo;
      $scope.saleInfo = res.data.items[0].saleInfo;
      $scope.related = res.data;
    });
       
      
  }
    $scope.update = function(book) {
      $scope.search = book.volumeInfo.title;
//      document.theBookForm.t1.value = $scope.search;
    };
})
</script>
</body>
</html>
