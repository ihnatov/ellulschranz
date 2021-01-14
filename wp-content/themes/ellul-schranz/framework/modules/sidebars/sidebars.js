(function($){
	$(function(){
		var	working = false;
		$('#ellul_schranz-add-new-sidebar').on('click',function(event){
			event.preventDefault();
			if( working ) return;
			var name = prompt( ellul_schranz_sidebars.l10n.give_me_name );
			name = name && name.trim ? name.trim() : name;
			if( name ){
				working = true;
				$('#ajax-loader').show();
				var data = {
					action: 'ellul_schranz-save-sidebar',
					nonce: ellul_schranz_sidebars.wp.nonce,
					name: name
				};
				$.post(ellul_schranz_sidebars.wp.ajax,data,function(d){
					if( ! d.success ){
						alert( d.message );
					} else {
						$('#ellul_schranz-sidebar-wrap').append( d.markup );
						if( $('#ellul_schranz-sidebar-wrap tr').length > 1 ){
							$('#ellul_schranz-no-sidebars').hide();
						}
					}
					$('#ajax-loader').hide();
					working = false;
				},'json');
			}
		});
		$('#ellul_schranz-sidebar-wrap').on('click','.ellul_schranz-sidebar-remove',function(event){
			event.preventDefault();
			if( working ) return;
			var $self = $(this), id = $self.attr('data-id');
			if( id && confirm( ellul_schranz_sidebars.l10n.confirm_delete ) ){
				$self.hide();
				$self.next().show();
				working = true;
				var data = {
					action: 'ellul_schranz-delete-sidebar',
					nonce: ellul_schranz_sidebars.wp.nonce,
					id: id
				};
				$.post(ellul_schranz_sidebars.wp.ajax,data,function(d){
					if( ! d.success ){
						alert( d.message );
						$self.show();
						$self.next().hide();
					} else {
						$self.closest('tr').remove();
						if( $('#ellul_schranz-sidebar-wrap tr').length <= 1 ){
							$('#ellul_schranz-no-sidebars').show();
						}
					}
					working = false;
				},'json');
			}
		});
	});
})(jQuery);
