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
						$('#get_from_bug').find('pre').remove()
						$('#get_from_bug').find('h5').remove()
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
							$('<h5 class="mt-3" >Identified fields</h5>').insertBefore('#retrieved_mappings')
							Object.keys(mappings).forEach(function(name){
									var li = $(`
										<li class="row mt-3">
										<div class="col-9">
										<small class="name">`+ name +`</small>
										<span class="data">`+ JSON.stringify(mappings[name].data) +`</span>
										</div>
										<div class="col-3 text-right"><button class="btn btn-secondary import-mapping">Import</button></div>
										</li>`)
									li.find('.import-mapping').click(function(e){
										e.preventDefault();
										var submit_btn_content = $(this).html();
										$(this).html('<i class="fa fa-spinner fa-spin"></i>');
										var name = $(this).closest('.row').find('.name').text();
										var data = $(this).closest('.row').find('.data').text();
										var form = $('#jira_mapping_field');
										var input_name = form.find('input[name="name"]');
										var input_content = form.find('textarea[name="value"]');
										var input_sanitize = form.find('input[name="sanitize"]');
										var input_json = form.find('input[name="is_json"]');

										input_name.val(name);
										input_content.val(data);
										input_sanitize.prop('checked', true);
										input_json.prop('checked', true);
										form.trigger('submit');
										$(this).html(submit_btn_content);
									})
									$('#retrieved_mappings').append(li)
								})
								$('<h5 class="mt-3" >Raw code</h5><pre class="raw-content">'+JSON.stringify(fields,undefined,2)+'</pre>').insertAfter('#retrieved_mappings')
						}
					}
					else {
						toastr.error(res.data)
					}
				}
			});
		})
		
		$('#jira_fields_settings .field_mapping .remove').click(function(){
			$(this).parent().remove()
		})
		$('#jira_fields_settings .add_field_mapping').click(function(){
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
		$('#jira_tracker_settings').submit(function(e){
			e.preventDefault();
			var srcParams = new URLSearchParams(window.location.search)
			var cp_id = srcParams.has('id') ? srcParams.get('id') : -1
			var data = $('#jira_tracker_settings').serializeArray();
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
					toastr.success('Tracker settings updated!');
					location.reload();
				}
			});
		})
		$('#jira_mapping_field').submit(function(e){
			e.preventDefault();
			var field_list_wrap = $('.fields-list');
			var srcParams = new URLSearchParams(window.location.search)
			var cp_id = srcParams.has('id') ? srcParams.get('id') : -1
			var data = $('#jira_mapping_field').serializeArray();
			
			var submit_btn = $(this).find('[type="submit"]');
			var submit_btn_html = submit_btn.html();
			submit_btn.html('<i class="fa fa-spinner fa-spin"></i>');
			data.push({
				'name' : 'action',
				'value': 'appq_jira_edit_mapping_fields'
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
					toastr.success('Field added!');
					submit_btn.html(submit_btn_html);
					field_list_wrap.find(`[data-row="${msg.data.key}"]`).remove();
					field_list_wrap.prepend(`<div class="row mb-2" data-row="${msg.data.key}">
					<div class="col-2">${msg.data.key}</div>
					<div class="col-4">${msg.data.content}</div>
					<div class="col-2 text-center"><input type="checkbox" style="cursor: unset;" disabled="" ${'on' == msg.data.sanitize ? 'checked' : ''}></div>
					<div class="col-2 text-center"><input type="checkbox" style="cursor: unset;" disabled="" ${'on' == msg.data.json ? 'checked' : ''}></div>
					<div class="col-2 text-right actions">
					<button data-toggle="modal" data-target="#add_mapping_field_modal" type="button" class="btn btn-secondary mr-1 edit-mapping-field" data-key="${msg.data.key}" data-content="${msg.data.content}" data-sanitize="${msg.data.sanitize}" data-json="${msg.data.json}"><i class="fa fa-pencil"></i></button>
					<button data-toggle="modal" data-target="#delete_mapping_field_modal" type="button" class="btn btn-secondary delete-mapping-field" data-key="${msg.data.key}"><i class="fa fa-trash"></i></button>
					</div>    
					</div>`);
					$('#add_mapping_field_modal').modal('hide');
				}
			});
		})
		$(document).on('click', '[data-target="#add_mapping_field_modal"]', function(){
			var modal_id = $(this).attr('data-target');
			var input_name = $(modal_id).find('input[name="name"]');
			var input_value = $(modal_id).find('textarea[name="value"]');
			var input_sanitize = $(modal_id).find('input[name="sanitize"]');
			var input_json = $(modal_id).find('input[name="is_json"]');

			input_name.val('');
			input_value.val('');
			input_sanitize.prop('checked', false);
			input_json.prop('checked', false);

			var key = $(this).attr('data-key');
			if(!key) return;
			var content = $(this).attr('data-content');
			var sanitize = $(this).attr('data-sanitize');
			var json = $(this).attr('data-json');

			input_name.val(key);
			input_value.val(content);
			if('on' == sanitize) input_sanitize.prop('checked', true);
			if('on' == json) input_json.prop('checked', true);
		})
		$(document).on('click', '#jira_fields_settings .delete-mapping-field', function(){
			var key = $(this).attr('data-key');
			var modal_id = $(this).attr('data-target');
			$(modal_id).find('input[name="field_key"]').val(key);
		})
		$('#jira_delete_field').submit(function(e){
			e.preventDefault();
			var field_list_wrap = $('.fields-list');
			var srcParams = new URLSearchParams(window.location.search)
			var cp_id = srcParams.has('id') ? srcParams.get('id') : -1
			var data = $('#jira_delete_field').serializeArray();
			var submit_btn = $(this).find('[type="submit"]');
			var submit_btn_html = submit_btn.html();
			submit_btn.html('<i class="fa fa-spinner fa-spin"></i>');
			data.push({
				'name' : 'action',
				'value': 'appq_jira_delete_mapping_fields'
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
					toastr.success('Field deleted!');
					submit_btn.html(submit_btn_html);
					field_list_wrap.find(`[data-row="${msg.data}"]`).remove();
					$('#delete_mapping_field_modal').modal('toggle');
				}
			});
		})
		$('.custom-checkbox').click(function(){
			var input = $(this).find("input")
        	input.prop("checked", !input.prop("checked"));
		})
	})
})(jQuery);
