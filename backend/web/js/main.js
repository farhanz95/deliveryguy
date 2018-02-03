$(function(){

	$('body').on('click','.fc-day',function(){
		var date = $(this).attr('data-date');

		$.get('/deliveryguy/backend/web/event/create',{'date':date}, function(data){

		$('#modal').modal('show')
			.find('#modalContent')
			.html(data);

		});
	});

	// $("table").find("td.fc-day").hover(function()	{

 //      $(this).next().css("border-style", "solid");
 //      $(this).next().css("border-color", "#000");  

 //  	});


	// get the click of the create button 
	$('#modalButton').click(function() {
		$('#modal').modal('show')
			.find('#modalContent')
			.load($(this).attr('value'));
	});
});