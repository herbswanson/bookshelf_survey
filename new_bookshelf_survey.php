<?php /* Template Name: BookShelf_Survey */ ?>
<?php
session_start();
$bookinfo = [];
$booktitles = array('','','','','');
$book_array = [];
$a_array = array( 'init_page',
'awaiting_input',
'google_search',
'db_update',
'db_update_google_search',
'list_nick',
'list_books',
'continue_search');
?>

<?php
	if (!isset($_SESSION['screen'])) {
		$_SESSION['screen'] = 0;
	}
	$tmp = $_SESSION['screen'];
	$log_msg = date("Y-m-d H:i:s");
	$log_msg .= trim(print_r($_POST,true));
	$log_msg = $log_msg . "screen_in:" . $a_array[$tmp]; 
//	print_r($_POST);
	if ((count($_POST)) == 0)
	{
		$action = 0;
		$t1 = '';
		$init_page = true;
		$_SESSION['nick'] = '';
		$_SESSION['shelf']= '';
		$_SESSION['t1']= '';
		$_SESSION['screen'] = '0';
		$awaiting_input = false;
		$google_search = false;
		$db_update = false;
		$db_update_google_search = false;
		$radio_buttons = false;
		$ckbox_list_nick = false;
		$list_nick = false;
		$list_books = false;
		$continue_search = false;
		goto very_first;
	}

	$init_page = false;
	$awaiting_input = false;
	$google_search = false;
	$db_update = false;
	$db_update_google_search = false;
	$radio_buttons = false;
	$ckbox_list_nick = false;
	$list_nick = false;
	$list_books = false;
	$continue_search = false;
	$action = book_action($_POST);
	//$t1 = $_SESSION['t1'];
	if ($action == 0) {$init_page = true;}
	if ($action == 1) {$awaiting_input = true;}
	if ($action == 2) {$google_search = true;}
	if ($action == 3) {$db_update = true;}
	if ($action == 4) {$db_update_google_search = true;}
	if ($action == 5) {$list_nick = true;}
	if ($action == 6) {$list_books = true;}
	if ($action == 7) {$continue_search = true;}
	//print_r('action:'.$action);

	if ($list_books) {
		$_SESSION['screen'] = '6';
		$_SESSION['nick'] = $_POST['nick'];
		$_SESSION['shelf'] = $_POST['bs_personal'];
		$_SESSION['t1'] = $_POST['t1'];
		$t1 = $_SESSION['t1'];
	}

	if ($db_update_google_search)
	{
		if (isset($_SESSION['thebooks'])) {
				$old_bookinfo = $_SESSION['thebooks'];  // save off prior books 
		}
		$_SESSION['nick'] = $_POST['nick'];
		$_SESSION['shelf'] = $_POST['bs_personal'];
		$_SESSION['t1'] = $_POST['t1'];
		$_SESSION['screen'] = '4';
		$nick = $_SESSION['nick'];
		$shelf = $_SESSION['shelf'];
		$t1 = $_SESSION['t1'];
		$bookinfo = get_bookinfo($t1);
		$_SESSION['thebooks'] = $bookinfo;
		$k = 0;
		foreach ($bookinfo as $book) {
			$booktitles[$k] = $book[0];
			$k += 1;
		}
		$nick = $_POST['nick'];
		$shelf = $_POST['bs_personal'];
		$b_idx = (int)$_POST['bookselected']; 
		$b_idx -= 1;
		$the_selected_book = $old_bookinfo[$b_idx];
		$rtn = bookdb_update($the_selected_book,$nick,$shelf);
		$radio_buttons = true;
	}
	if ($google_search)
	{
		//print_r($_POST);
		$_SESSION['screen'] = '2';
		$_SESSION['nick'] = $_POST['nick'];
		$_SESSION['shelf'] = $_POST['bs_personal'];
		$_SESSION['t1'] = $_POST['t1'];
		$nick = $_SESSION['nick'];
		$shelf = $_SESSION['shelf'];
		$t1 = $_SESSION['t1'];
		$radio_buttons = true;
		$param_val = $_POST['t1'];
		$bookinfo = get_bookinfo($param_val);
		$_SESSION['thebooks'] = $bookinfo;
		$k = 0;
		foreach ($bookinfo as $book) {
			$booktitles[$k] = $book[0];
			$k += 1;
		}
	}
	if ($db_update)
	{
		$b_idx = (int)$_POST['bookselected']; 
		$b_idx -= 1;
		$_SESSION['screen'] = '3';
		$nick = $_SESSION['nick'];
		$shelf = $_SESSION['shelf'] ;
		$t1= $_SESSION['t1'] ;
		$len = count($_SESSION['thebooks']);
		if (($b_idx+1) <= $len)
		{  
			$the_selected_book = ($_SESSION['thebooks'][$b_idx]);
			$rtn = bookdb_update($the_selected_book,$nick,$shelf);
		}
		$_SESSION['t1'] = '';
		$t1 = '';
	}

	if ($list_nick) {
		$_SESSION['screen'] = '5';
		$ckbox_list_nick = true;	
	}

	if ($awaiting_input)
	{
		$_SESSION['nick'] = $_POST['nick'];
		$_SESSION['shelf'] = $_POST['bs_personal'];
		$_SESSION['t1'] = '';
		$_SESSION['screen'] = '1';
		$t1 = $_SESSION['t1'];
		$nick  = $_POST['nick'];
		$shelf = $_POST['bs_personal'];
	}
	if ($continue_search)
	{
		$_SESSION['screen'] = '7';
		$_SESSION['nick'] = $_POST['nick'];
		$_SESSION['shelf'] = $_POST['bs_personal'];
		$_SESSION['t1'] = $_POST['t1'];
		$t1 = $_SESSION['t1'];
		$nick  = $_POST['nick'];
		$shelf = $_POST['bs_personal'];
	}

very_first:
$tmp = $_SESSION['screen'];
$log_msg = $log_msg . '  screen:'. $a_array[$tmp] . ' action: ' . $a_array[$action]; 
file_put_contents('/var/www/html/wp-content/themes/yaaburnee-themes-child/new_bookshelf_survey.log',$log_msg . "\n",FILE_APPEND);
?>
<?php //  get_header(); ?>
<!DOCTYPE html>

<head>
<h1 class="header">Saker BookShelf</h1>
<style>
<?php include 'new_bookshelf_survey.css'; ?> 
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"> </script>
<script>
$(document).ready(function() {
  // all custom jQuery will go here
    $( "#view_button" ).click(function() {
  	document.theBookForm.bookshelf_survey.value = '2';
        $( "#theBookForm" ).submit();
    });
    $("#nick_in").on("input", function() {
  	document.theBookForm.nick.value = this.value;
    });
    $("#shelf_in").on("input", function() {
  	document.theBookForm.bs_personal.value = this.value;
    });
		
});
function submitClick()
{
	/***
	document.getElementById('formSubmit').style.visibility='hidden';
	document.getElementById('formSubmit').disabled=true;
  	document.forms["theBookForm"].submit();
	document.getElementById('selBtn').style.visibility='visible';
	document.getElementById('selBtn').disabled=false;
	document.getElementById('formSubmit').setAttribute('style','visibility:hidden');
	document.getElementById('formSubmit').disabled=true;
  	document.forms["theBookForm"].submit();
	document.getElementById('selBtn').setAttribute('style','visibility:visible');
	document.getElementById('selBtn').disabled=false;
	****/
}
function nickfill()
{
	alert('hello world from nickfill');
}
function whichBook()
{
  	book_sel = document.querySelector('input[name="chooseone"]:checked').value;
  	document.theBookForm.bookselected.value = book_sel;
	/**********
	if (book_sel == 6 ) {
		document.theBookForm.bookselected.value = '6';
  		document.theBookForm.nick.value = '';
  		document.theBookForm.bs_personal.value = '';
  		document.theBookForm.t1.value = '';
  	}
**********/
  	document.forms["theBookForm"].submit();
}
function clear_form()
{
  	document.theBookForm.bookselected.value = '6';
  	document.theBookForm.nick.value = '';
  	document.theBookForm.bs_personal.value = '';
  	document.theBookForm.t1.value = '';
  	document.forms["theBookForm"].submit();
}
function help_instructions() {
	alert("help button pressed");
}
function delete_book()
{
	alert("Delete Button pressed");
}
function view_lib()
{
    /* future code goes here */
}
</script>
</head>

<body>
<div>
  <div class="wrapper">
    <div class="contacts">
      <h3>Saker BookShelf</h3>
      	<img src="http://vsaker.net/wp-content/uploads/2019/02/saker_body-300x200.jpg" alt="" width="300" height="200" class="alignnone size-medium wp-image-200">
	<p> We are asking our readers to submit the titles and authors of their favorite books. These will be reviewed and selected books will be included in the BookShelf.
	</p>
    </div><!-- contact -->  
 <div class="form">
      <h3>Favorite Books</h3>
      <form id="theBookForm" name="theBookForm" action="" method="post" >     
<!-- Enter title, author, isbn -->
		<div class="searchterm">
          <label style="font-size:20px; font-weight:800; " for="">Enter title, author, isbn number</label>
	  <input style="width:100%;" type="text" name="t1" value="<?php echo $t1; ?>">
	  <input type="hidden" name="bookshelf_survey" value="1">
	  <input type="hidden" name="bookselected" value="6">
	</div>	
<!-- /title, author, isbn -->
<!--** Left column = Search/Select, Clear, Help -->
	<div id="leftcol">
		  <div id="select_id">
			<?php
			if ($google_search || $continue_search || $db_update_google_search)
			{
			echo '<button  onclick="whichBook()" id="selBtn" >Press here to Select Book</button>';
			}
			else
			{
			echo '<button  onclick="submitClick()" id="formSubmit">Search for Book</button>';
			}
			?>
		  </div>
<!-- View Library --> 
	<div id="view_id">
		<button  onclick="view_lib()" id="view_button">View Library</button>
		</div>
<!-- /view --> 
<!-- Delete button--> 
	<div id="delete_id">
		<button  onclick="delete_book()" id="delete_button">Delete Book</button>
	</div>
<!-- /delete --> 

<!-- clear  --> 
        <div id="clear_id">
			<button  onclick="clear_form()" id="clear_button">Clear</button>
        </div>
<!-- /clear  --> 

	</div> <!-- left column ends  -->
<!-- /left column -->

<!--** Right column = My Library Name , Bookshelf name-->
	<div id="rightcol">
		<h3>Add book to Favorites</h3>
		  <?php if ($action <= '1' || $action == 6)
		  {
			echo '<div>';
				echo '<label style="font-size:16px; font-weight:800; " for="">My Library (Name)</label>';
				echo '<input id="nick_in" style="width:100%; line-height:1em;font-size:15px;"   type="text" placeholder="Optional not required ..." name="nick">';
			echo '</div>';
			echo '<div>';
				echo '<label style="font-size:16px; font-weight:800; " for="">My Bookshelf</label>';
				echo '<input id="shelf_in" style="width:100%; line-height:1em;font-size:15px;"   type="text" placeholder="Optional not required ..." name="bs_personal">';
			echo '</div>';
		  }	  
		  else
		  {
			echo '<div>';
				echo '<label style="font-size:20px; font-weight:800; " for="">My Library</label>';
			echo '<input id="nick_in" style="width:100%; line-height:1em;font-size:15px;" value="';
			echo  $nick;
			echo '" type="text" readonly name="nick">';
			echo '</div>';
			echo '<div>';
				echo '<label style="font-size:20px; font-weight:800; " for="">My BookShelf Name</label>';
			echo '<input id="shelf_in" style="width:100%; line-height:1em;font-size:15px;" value="';
				echo $shelf; 
			echo '" type="text" readonly name="bs_personal">';
			echo '</div>';		 
		  }	
		 ?>

		  <div id="help_id">
			<button  onclick="help_instructions()" id="help_button">Help</button>
		  </div>
	</div>
<!-- /right column --> 
</form>
<?php
if ($radio_buttons) {
echo "<div class='radios'  >";
echo  "<input type='radio'   name='chooseone' value='1' id='r1'><label class='radiosel' for='r1'>  $booktitles[0]</label><br>";
echo  "<input type='radio'   name='chooseone' value='2' id='r2'><label class='radiosel' for='r2'>  $booktitles[1]</label><br>";
echo  "<input type='radio'   name='chooseone' value='3' id='r3'><label class='radiosel' for='r3'>  $booktitles[2]</label><br>";
echo  "<input type='radio'   name='chooseone' value='4' id='r4'><label class='radiosel' for='r4'>  $booktitles[3]</label><br>";
echo  "<input type='radio'   name='chooseone' value='5' id='r5'><label class='radiosel' for='r5'>  $booktitles[4]</label><br>";
echo  "<input type='radio'   name='chooseone' value='6' id='r6' checked='checked' ><label class='radiosel' for='r6'> none of the above </label><br>";
}
echo "</div>";
?>
<?php
if ($db_update or $db_update_google_search) 
{
echo "<div>";
if (isset($the_selected_book[0]))
{	
	echo "<p class='bookaccepted'> $the_selected_book[0] ... has been entered</p>";
}
echo "</div>";
}
?>
     </div>
     </div>  <!-- end of wrapper -->
     <div class='wrapper-2'>
<?php
if ($radio_buttons) {
		echo "<div class='google_icon'>";
			echo  "<img style='display:block; margin-left:100px;' src='/wp-content/uploads/2019/03/google_search1-e1552834186185.png'>";
		echo "</div>";
		$k = 0;
		$a =[];
		echo "<div class='google_results' >";
		foreach ($bookinfo as $book) {
			$title = $book[0];
			$subtitle = $book[1];
			$author = $book[2];
			$review = $book[3];
			$volid  = $book[4];
			echo "<div class='full_bookinfo'>";
			echo "<div class='bookinfo'>";
				echo "<p>";
				echo "$title <br>";
				if ($subtitle != "") {
				echo "$subtitle <br>";
				}
				echo "$author <br>";
				echo "$volid <br>";
				echo "</p>";
			echo "</div>";
			echo "<div>";
				echo "<div class='bookreview'>";
				echo "$review <br>";
				echo "</div>";
			echo "</div>";
			echo "<br>";
			$k += 1;
			echo "</div>";  // full_bookinfo end
		}
		echo "</div>";  // google_results end
	}
	
	if ($list_books) {
		$nick = trim($_SESSION['nick']);
		$shelf = trim($_SESSION['shelf']);
		build_booklists($t1,$nick,$shelf);
		$_SESSION['nick'] = '';
		$_SESSION['shelf'] = '';
		$_SESSION['t1'] = '';
	}
?>
     </div>
  </div>
</div>
</body>
</html>
