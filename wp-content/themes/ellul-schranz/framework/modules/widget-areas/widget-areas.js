(function($){
	var draggableOptions = {
		grid: [ 22, 30 ],
		snap: '.area-columns',
		snapTolerance: 4,
		axis: 'x',
		cursor: 'move',
		containment: 'parent',
		drag: function( evt, ui ){
			if(
				ui.position.left <= 0 ||
				( ui.position.left + 4 ) >= ui.helper.parent().width()
			){
				evt.preventDefault();
			}
			var	prev = ui.helper.prev();
			if(
				prev.length > 0 &&
				evt.offsetX < ( prev.position().left + 22 ) &&
				evt.offsetX > prev.position().left
			){
				evt.preventDefault();
			}
			var	next = ui.helper.next();
			if(
				next.length > 0 &&
				( next.position().left - evt.offsetX ) < 22
			){
				evt.preventDefault();
			}
		},
		stop: function(evt,ui){
			var	parent = ui.helper.parent(),
				prev = 0, current = 0, all = 0,
				cols   = [];
			parent.find('.widget-column').each(function(){
				current = ( $(this).position().left / 22 );
				cols.push( current - prev );
				prev = current;
			});
			cols.push( 12 - prev );
			parent.next().val( cols.join(',') );
		}
	},
	tpl = _.template( document.getElementById('area-template').innerHTML ),
	timeout = {
		on_off: null,
		save: null,
	}, row_count = 1;
	$('.area-placeholder').each(function(){
		var idx = $(this).attr('data-index');
		$(this)
			.sortable({
				connectWith: '.area-placeholder[data-index="'+idx+'"]',
				placeholder: 'ui-state-highlight',
				opacity: .5,
				items: 'li.widget-block',
				handle: '.area-move'
			})
			.droppable({
				accept: '.widget-block',
				drop: function( evt, ui ){},
				over: function( evt, ui ){},
				out: function( evt, ui ){}
			});
	});
	$('.widget-column').draggable( draggableOptions );
	$(document)
		// edit single area
		.on('click','.edit-single-area',function(e){
			$(this).toggleClass('dashicons-arrow-down dashicons-arrow-up').parent().toggleClass('open');
		})
		// change columns size
		.on('change','.area-columns-size',function(){
			var	totalCols = parseInt( this.value ),
				col = Math.floor( 12 / totalCols ),
				html = '', cols = [], remains = 12,
				$container = $(this).next().find('.area-columns');
			$(this).next().find('.widget-column').draggable('destroy').remove();
			for( i = 1; i < totalCols; i++ ){
				var left = 22 * i * col;
				html += '<div class="widget-column" style="left:'+left+'px"></div>';
				cols.push(col);
				remains -= col;
			}
			cols.push(remains);
			$container.next().val(cols.join(','));
			if( totalCols > 1 ){
				$(html)
					.draggable( draggableOptions )
					.appendTo( $container );
			}
		})
		// remove area
		.on('click','.area-remove',function(e){
			e.preventDefault();
			$(this).closest('li.widget-block').remove();
		})
		// add area
		.on('click','.area-add',function(e){
			e.preventDefault();
			var	data = $.extend( {}, ellul_schranz_tpl );
			data.section   = $(this).attr('data-index');
			data.row_title = data.row_title + ' ' + row_count;
			row_count++;
			$(this).prev().append( tpl( {
				data: data
			} ) );
		});
	// toggle edit area
	$('.edit-area').on('click',function(e){
		e.preventDefault();
		$(this).find('i').toggleClass('dashicons-arrow-down dashicons-arrow-up');
		$(this).closest('tr').find('.widget-areas').toggle();
	});
	// toggle on off
	$('.on-off-btn').on('click',function(e){
		e.preventDefault();
		var $self = $(this), $img = $self.find('img');
		$self.next().show();
		if( parseInt( this.value ) ){
			this.value = 0;
			$img.prop('src',ellul_schranz_tpl.wp.img_src+'on.png');
			$self.closest('tr').find('.widget-areas').show();
			$self.closest('tr').find('.edit-area i').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');
		} else {
			this.value = 1;
			$img.prop('src',ellul_schranz_tpl.wp.img_src+'off.png');
			$self.closest('tr').find('.widget-areas').hide();
			$self.closest('tr').find('.edit-area i').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');
		}
		clearTimeout( timeout.on_off );
		timeout.on_off = setTimeout(function(){
			var data = {
				action: 'update_status',
				nonce: ellul_schranz_tpl.wp.nonce,
				ajax: 1
			};
			data[ $self.attr('name') ] = parseInt( $self.val() ) ? 0 : 1;
			$.post(ellul_schranz_tpl.wp.ajax,data,function(d){
				$self.next().hide();
			});
		},300);
	});
	// reset
	$('#ellul_schranz-restore').on('click',function(e){
		if( ! confirm( ellul_schranz_tpl.confirm ) ){
			e.preventDefault();
		}
	});
	// update areas
	$('#update-areas').on('click',function(e){
		e.preventDefault();
		var $self = $(this);
		$self.next().show();
		clearTimeout( timeout.save );
		timeout.save = setTimeout(function(){
			var data = $('#widget-area-form').serialize();
			data += '&'+$('#update-areas').attr('name')+'='+$('#update-areas').val();
			data += '&action=update_widget_area';
			data += '&ajax=1';
			$.post(ellul_schranz_tpl.wp.ajax,data,function(d){
				$self.next().hide();
			});
		},300);
	});
})(jQuery);
