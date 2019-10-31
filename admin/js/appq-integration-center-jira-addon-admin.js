(function($) {
	'use strict';

	$(document).ready(function() {
		$('#jira_settings .field_mapping .remove').click(function(){
			$(this).parent().remove()
		})
		$('#jira_settings .add_field_mapping').click(function(){
			var button = $(this)
			button.attr('disabled','disabled')
			var proto = $('<div><input type="text" placeholder="key" name="key"><input type="text" placeholder="value" name="value"><button class="btn btn-primary">OK</button></div>')
			proto.find('button').click(function(e){
				e.preventDefault()
				var key = $(this).parent().find('[name="key"]').val()
				var value = $(this).parent().find('[name="value"]').val()
				
				var new_input = $(`
				<div class="form-group row">
					<label for="key" class="col-sm-2 align-self-center text-center"></label>
					<textarea name="key" class="col-sm-7 form-control" placeholder="Title: {Bug.title}"></textarea>
					<div class="custom-control custom-checkbox col-sm-1">
					  <input name="sanitize" class="custom-control-input" type="checkbox" >
					  <label class="custom-control-label" for="sanitize">
						Sanitize?
					  </label>
					</div>
					<div class="custom-control custom-checkbox col-sm-1">
					  <input name="is_json" class="custom-control-input" type="checkbox" >
					  <label class="custom-control-label" for="is_json">
						JSON?
					  </label>
					</div>
					<button class="col-sm-1 remove btn btn-danger">-</button>
				</div>`)
				new_input.find('label[for=key]').attr('for','field_mapping['+key+']').text(key)
				new_input.find('textarea[name=key]').attr('name','field_mapping['+key+'][value]').val(value)
				new_input.find('.form-check-input[name=sanitize]').attr('name','field_mapping['+key+'][sanitize]')
				new_input.find('label[for=sanitize]').attr('for','field_mapping['+key+'][sanitize]')
				new_input.find('.form-check-input[name=is_json]').attr('name','field_mapping['+key+'][is_json]')
				new_input.find('label[for=is_json]').attr('for','field_mapping['+key+'][is_json]')
				new_input.find('.custom-checkbox').click(function(){
					var input = $(this).find("input")
		        	input.prop("checked", !input.prop("checked"));
				})
				new_input.find('.remove').click(function(){
					$(this).parent().remove()
				})
				
				new_input.insertBefore(button)
				$(this).parent().remove()
				button.removeAttr('disabled')
			})
			proto.insertBefore($(this))
		})
		$('#jira_settings .save').click(function(){
			var srcParams = new URLSearchParams(window.location.search)
			var cp_id = srcParams.has('id') ? srcParams.get('id') : -1
			var button = $(this)
			var text = button.text()
			var data = $('#jira_settings').serializeArray()
			data.push({
				'name' : 'action',
				'value': 'appq_jira_edit_settings'
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
		
		$('.custom-checkbox').click(function(){
			var input = $(this).find("input")
        	input.prop("checked", !input.prop("checked"));
		})
	})
})(jQuery);