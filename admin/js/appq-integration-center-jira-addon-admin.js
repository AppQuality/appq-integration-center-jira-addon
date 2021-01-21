(function($) {
	'use strict';

	$(document).ready(function() {
		$('#issue_id').on('keypress', function (e) {
			  if(e.which === 13){
				  e.preventDefault()
				  $('#retrieve_mappings').click()
			  }
		});
		$('#retrieve_mappings').click(function(){
			var srcParams = new URLSearchParams(window.location.search)
			var cp_id = srcParams.has('id') ? srcParams.get('id') : -1
			var issue_id = $('#issue_id').val()
			var button = $(this)
			var text = button.text()
			button.text("")
			button.append('<span class="fa fa-spinner fa-spin"></span>')
			$.ajax({
				type: "post",
				dataType: "json",
				url: custom_object.ajax_url,
				data: {
					action: 'appq_get_issue_from_bugtracker',
					issue_id: issue_id,
					cp_id: cp_id
				},
				success: function(res) {
			 		button.text(text)
					if (res.success) {
						var fields = res.data.fields
						$('#retrieved_mappings').find('li').remove()
						$('#getBugModal').find('pre').remove()
						if (!fields) {
							toastr.error('Invalid bug. No fields to retrieve')
						} else {
							var mappings = {}
							if (fields.reporter && fields.reporter.accountId) {
								mappings.reporter = {data: { id: fields.reporter.accountId }}
							}
							if (fields.assignee && fields.assignee.accountId) {
								mappings.assignee = {data: { id: fields.assignee.accountId }}
							}
							if (fields.issuetype && fields.issuetype.name) {
								mappings.issuetype = {data: { name: fields.issuetype.name }}
							}
							if (fields.labels) {
								mappings.labels = {data: fields.labels }
							}
							Object.keys(mappings).forEach(function(name){
								var li = $(`
									<li class="row">
									<span style="align-self: center;" class="col-3 name font-weight-bold">`+ name +`</span>
									<span style="align-self: center;" class="col-6 data">`+ JSON.stringify(mappings[name].data) +`</span>
									<span class="col-3 d-flex"><button style="align-self: center;" class="ml-auto btn btn-success import-mapping">Import</button></span>
									</li>`)
									li.find('.import-mapping').click(function(e){
										e.preventDefault()
										var name = $(this).closest('.row').find('.name').text()
										var data = $(this).closest('.row').find('.data').text()
										$('#jira .add_field_mapping').click()
										$('#jira input[name="key"]').val(name)
										$('#jira textarea[name="value"]').val(data)
										$('#jira .confirm-add-mapping').click()
										$('#jira label[for="field_mapping['+name+'][is_json]"]').click()
										$('#jira input[name="field_mapping['+name+'][is_json]"]').prop('checked', true);
									})
									$('#retrieved_mappings').append(li)
								})
								$('<pre style="max-height: 400px; overflow: scroll; background: #000; color: #fff; padding: 15px;">'+JSON.stringify(fields,undefined,2)+'</pre>').insertAfter('#retrieved_mappings')
						}
					}
					else {
						toastr.error(res.data)
					}
				}
			});
		})
		
		$('#jira_settings .field_mapping .remove').click(function(){
			$(this).parent().remove()
		})
		$('#jira_settings .add_field_mapping').click(function(){
			var button = $(this)
			button.attr('disabled','disabled')
			var proto = $('<div style="display:flex"><input type="text" placeholder="key" name="key"><textarea style="flex-grow:1" placeholder="value" name="value"></textarea><button class="btn btn-primary confirm-add-mapping">OK</button></div>')
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
				new_input.find('input[name=is_json]').attr('name','field_mapping['+key+'][is_json]')
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
		$('#jira_settings').submit(function(e){
			e.preventDefault();
			var srcParams = new URLSearchParams(window.location.search)
			var cp_id = srcParams.has('id') ? srcParams.get('id') : -1
			var data = $('#jira_settings').serializeArray()
			data.push({
				'name' : 'action',
				'value': 'appq_jira_edit_settings'
			});
			data.push({
				'name' : 'cp_id',
				'value': cp_id
			});
			data.push({
			  name: "nonce",
			  value: appq_ajax.nonce,
			});
			jQuery.ajax({
				type: "post",
				dataType: "json",
				url: appq_ajax.url,
				data: data,
				success: function(msg) {
					location.reload();
				}
			});
		})
		
		$('.custom-checkbox').click(function(){
			var input = $(this).find("input")
        	input.prop("checked", !input.prop("checked"));
		})
	})
})(jQuery);
