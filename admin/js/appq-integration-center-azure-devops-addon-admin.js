(function($) {
	'use strict';

	$(document).ready(function() {
		$('#bugs-tabs-content .fa.fa-upload').not(".text-secondary").click(function() {
			var cp_id = $('#cp_id').val()
			var bug_id = $(this).data('bug-id')
			jQuery.ajax({
				type: "post",
				dataType: "json",
				url: custom_object.ajax_url,
				data: {
					'action': 'azure_devops_upload_bugs',
					'cp_id': cp_id,
					'bug_id': bug_id
				},
				success: function(msg) {
					console.log(msg);
				}
			});
		})
	})
})(jQuery);