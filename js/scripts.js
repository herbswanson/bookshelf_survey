$(document).ready(function() {
    // all custom jQuery will go here
});
function whichBook()
{
  	book_sel = document.querySelector('input[name="chooseone"]:checked').value;
  	document.theBookForm.bookselected.value = book_sel;
  	document.forms["theBookForm"].submit();
}
function nickfill()
{
	alert('hello world from nickfill');
	var rtn_val = document.getElementById('nick_ckbox').checked;
//  	document.nick_book_shelf.shelf_display.value = rtn_val;
//	document.forms["nick_book_shelf"].submit();
	
//  $("#form").submit( function(eventObj) {
 //     $('<input />').attr('type', 'hidden')
  //        .attr('name', "shelf_display")
   //       .attr('value', "rtn_val")
    //      .appendTo('#form');
    //  return true;
//  });

	alert('aqt the end') ;
});
