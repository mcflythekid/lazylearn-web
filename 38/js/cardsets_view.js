

function delete_confirm(){
	$( "#dialog-confirm" ).dialog({
		resizable: false,
		height:140,
		modal: true,
		buttons: {
		"Delete": function() {
			document.delete.submit();
			$(this).dialog("close");
		},
		Cancel: function() {
			$(this).dialog("close");
		}
		}
	});
}

$('body').delegate('button[id^="edit_submit-"]', "click", function( event ) {
	$("#edit_error-" + id).html("");
	var id = this.id.substring(12);
	var front = $("textarea#edit_front-" + id).val();
	var back = $("textarea#edit_back-" + id).val();
	front = $.trim(front);
	back = $.trim(back);
	if (!front || !back || !/^(.{1,1024})$/.test(front) || !/^(.{1,1024})$/.test(back)){
		$("#edit_error-" + id).html("Card size can't be blank or greater than 1024.");
		return;
	}
	$("p#card-front-" + id).text(front);
	$("p#card-back-" + id).text(back);
	$("table#card_display-" + id).css("display", "table");
	$("table#card_edit-" + id).css("display", "none");
	$.post("/jax/update_card.php", {id : id, front : front, back : back},  function(result){
	});
}); 
$('body').delegate('button#new_submit', "click", function( event ) {
	$("#new_error").html("");
	var id = this.id.substring(12);
	var front = $("textarea#new_front").val();
	var back = $("textarea#new_back").val();
	front = $.trim(front);
	back = $.trim(back);
	if (!front || !back || !/^(.{1,1024})$/.test(front) || !/^(.{1,1024})$/.test(back)){
		$("#new_error").html("Card size can't be blank or greater than 1024.");
		return;
	}
	$("div#cardcnt").text($("div#cardcnt").text() - 1 + 2);
	$.post("/jax/new_card.php", {set_id : set_id, front : front, back : back},  function(result){
		if (result){
			$('div#card-new').before(sample.replace(/xxx/g, result));
			$("p#card-front-" + result).text(front);
			$("p#card-back-" + result).text(back);
			$("textarea#new_front").val("");
			$("textarea#new_back").val("");
			$("textarea#new_front").focus();
			$(window).scrollTop($(document).height());
		}
	});
}); 
$('body').delegate('a[id^="edit_cancel-"]', "click", function( event ) {
	var id = this.id.substring(12);
	$("table#card_display-" + id).css("display", "table");
	$("table#card_edit-" + id).css("display", "none");
}); 
$('body').delegate('img[id^="cardlist_card_data_edit-"]', "click", function( event ) {
	var id = this.id.substring(24);
	$("table#card_display-" + id).css("display", "none");
	$("textarea#edit_front-" + id).val($("p#card-front-" + id).text());
	$("textarea#edit_back-" + id).val($("p#card-back-" + id).text());
	$("table#card_edit-" + id).css("display", "table");
}); 
$('body').delegate('img[id^="cardlist_card_data_delete-"]', "click", function( event ) {
	var id = this.id.substring(26);
	$("div#card-" + id).remove();
	$("div#cardcnt").text($("div#cardcnt").text() - 1);
	$.post("/jax/delete_card.php", {id : id},  function(result){
	});
});

for (i = 0; i < theCardset.length; i++) { 
	$('a#port').before(sample.replace(/xxx/g, theCardset[i].card_id));
	$("p#card-front-" + theCardset[i].card_id).text(theCardset[i].card_front);
	$("p#card-back-" + theCardset[i].card_id).text(theCardset[i].card_back);
}
$("div#loading").remove();
