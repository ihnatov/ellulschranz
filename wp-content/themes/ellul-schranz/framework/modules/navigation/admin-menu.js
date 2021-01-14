(function($){
	$(function(){
		var	timeout, regex = /%1\$u/g;
		$(document).on('click','.menu-item-icon-wrap',function(){
			var	is = !! $('#'+this.id+'-packages').text();
			if( ! is ){
				var icons = new String( EdnsMenuItem.markup );
				$('#'+this.id+'-packages').html( icons.replace( regex, $(this).data('id') ) );
			}
			$('#'+this.id+'-packages,#'+this.id+'-search').toggle();
		}).on('keydown','.menu-icon-search',function(e){
			var key = e.keyCode ? e.keyCode : e.which;
			if( key == 13 ){
				e.preventDefault();
			}
		}).on('keyup','.menu-icon-search',function(e){
			var key  = e.keyCode ? e.keyCode : e.which,
				$id  = $( '#' + $(this).attr('data-id') );
			clearTimeout( timeout );
			if( key == 27 ){
				this.value = '';
				$id.find('label').show();
			} else {
				var	self = this;
				if( this.value.trim() ){
					timeout = setTimeout(function(){
						$id.find('label').hide();
						var $search = $id.find('label [class*="'+self.value+'"]');
						if( $search.length ){
							$search.parent().show();
						}
					},300);
				} else {
					$id.find('label').show();
				}
			}
		}).on('click','.menu-item-icon-choose label',function(){
			var id = $(this).attr('data-id');
			$('#'+id+'-packages,#'+id+'-search').hide();
			$('#'+id).html( $(this).find('i')[0].outerHTML );
		});
	});
})(jQuery);
