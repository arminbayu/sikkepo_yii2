$(function() {
	$('#mapButton').click(function() {
		$('#mapModal').modal('show')
			.find('#modalMap')
			.load($(this).attr('value'));
	});

	$('#modalButton').click(function() {
		$('#modal').modal('show')
			.find('#modalContent')
			.load($(this).attr('value'));
	});
});