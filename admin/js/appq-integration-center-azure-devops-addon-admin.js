(function($) {
	'use strict';

	$(document).ready(function() {
		$('#azure_devops_settings .field_mapping .remove').click(function(){
			$(this).parent().remove()
		})
		$('#azure_devops_settings .add_field_mapping').click(function(){
			var button = $(this)
			button.attr('disabled','disabled')
			var proto = $('<div><input type="text" placeholder="key" name="key"><input type="text" placeholder="value" name="value"><button class="btn btn-primary">OK</button></div>')
			proto.find('button').click(function(e){
				e.preventDefault()
				var key = $(this).parent().find('[name="key"]').val()
				var value = $(this).parent().find('[name="value"]').val()
				
				var new_input = $(`
				<div class="form-group row">
					<label class="col-sm-2"></label>
					<textarea class="col-sm-9 form-control" placeholder="Title: {Bug.title}"></textarea>
					<button class="col-sm-1 remove btn btn-danger">-</button>
				</div>`)
				new_input.find('label').attr('for','field_mapping['+key+']').text(key)
				new_input.find('textarea').attr('name','field_mapping['+key+']').val(value)
				new_input.find('.remove').click(function(){
					$(this).parent().remove()
				})
				
				new_input.insertBefore(button)
				$(this).parent().remove()
				button.removeAttr('disabled')
			})
			proto.insertBefore($(this))
		})
		$('#azure_devops_settings .save').click(function(){
			var srcParams = new URLSearchParams(window.location.search)
			var cp_id = srcParams.has('id') ? srcParams.get('id') : -1
			var button = $(this)
			var text = button.text()
			var data = $('#azure_devops_settings').serializeArray()
			data.push({
				'name' : 'action',
				'value': 'appq_azure_devops_edit_settings'
			})
			data.push({
				'name' : 'cp_id',
				'value': cp_id
			})
			button.text("")
			button.append('<span class="fa fa-spinner fa-spin"></span>')
			jQuery.ajax({
				type: "post",
				dataType: "json",
				url: custom_object.ajax_url,
				data: data,
				success: function(msg) {
			 		button.text(text)
				}
			});
		})
	})
})(jQuery);