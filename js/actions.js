$(function(){

	// Add ajax actions to file buttons
	// Add delete action
	var ACTIONS_URL = "/windex/functions/file_actions.php";

	$(".action.delete").on("click", function(event){
		var $target = $(event.currentTarget);
		$.ajax({
			"url" : ACTIONS_URL,
			"type" : "DELETE",
			"data" : {
				"uri": $target.attr("data-uri")
			},
			"success" : function() {
				$target.parent().remove();
				// Ping Msg
    		ping('File deleted');
    		
				// Log
				var project_data = get_project_data();
  			add_log(project_data, 'delete');

  			
			}, 
			"error" : function() {
				console.log("File " + $target.attr("data-uri") + " couldn't be deleted.");
			}
		});
	});
});