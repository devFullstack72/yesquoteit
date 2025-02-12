(function($) {
    "use strict";
    $( document ).ready( function () {
        $.fn.yeemail_row_droppable = function () { 
            $(this).draggable({
              helper: function () {
                    var type = $(this).data("type");
                    var html = $.yeemail_load_type(type);
                    if(type == "row1"||type == "row2"||type == "row3" ||type == "row4" ||type == "row5" ||type == "row6" ||type == "row7" ||type == "row8" ||type == "row9" ||type == "row10" ){
                        html.find(".builder-row").addClass("builder-row-empty");
                    }
                    html.find(".builder-row").yeemail_element_sortable();
                    return html.removeAttr('style').css({width: 'auto',height: 'auto'});
                },
              start: function (e, ui) {
                  ui.helper.addClass('yeemailemail-temp');
              },
              stop: function (e, ui) {
                 ui.helper.removeClass('yeemailemail-temp');
              },
              cursorAt: {left: 40, top: 15},
              connectToSortable: ".builder__list",
              revert : 0,
            });
        }
        $.fn.yeemail_row_sortable = function () {
            $(this).sortable({
              revert: "invalid",
              connectWith: '.builder-row-tool',  
              placeholder: 'builder-row-insert',
              start: function (ev, ui) {
                  ui.helper.addClass('wpbuider-email-dragging');
              },
              stop: function (ev, ui) {  
                  ui.item.removeClass('wpbuider-email-dragging');
              },
              handle: ".momongaDragHandle",
              revert : 0,
            });
        }
        $.fn.yeemail_element_droppable = function () { 
            $(this).draggable({
              helper: function () {
                    var type = $(this).data("type");
                    $( this ).removeClass('builder-row-empty');
                    var html = $.yeemail_load_type(type);
                    return html.removeAttr('style').css({width: 'auto',height: 'auto'});
                },
              cursor: "move",
              cursorAt: {left: 40, top: 15},
              start: function (e, ui) {
                  ui.helper.addClass('yeemailemail-temp');
              },
              stop: function (e, ui) {
                 ui.helper.removeClass('yeemailemail-temp');
              },
              connectToSortable: ".builder-row",
              revert : 0,
            });
        }
        $.fn.yeemail_element_sortable = function () { 
            $(this).sortable({
              connectWith: '.builder-row',
              revert: "invalid",
              placeholder: 'builder-row-insert',
              column: '',
              tolerance: "pointer",
              handle: ".momongaDragHandle",
              revert : 0,
              start: function (ev, ui) {
                    ui.helper.addClass('wpbuider-email-dragging');
                    this.column = ui.helper.closest('.builder-row');
                },
              stop: function (ev, ui) { 
                ui.item.removeClass('wpbuider-email-dragging');
                if (ui.item.closest(".builder-row").find('.builder-elements').length) {
                    ui.item.closest(".builder-row").removeClass('builder-row-empty');
                }
                if (!(this.column.find('.builder-elements').length)) {
                    this.column.addClass('builder-row-empty');
                }
              },
            });
        }
        if(!yeemail_script.disable_builder){
            $( ".builder-row-tool li" ).yeemail_row_droppable();
            $( ".builder__list--js" ).yeemail_row_sortable();
            $( ".momongaPresets li>div" ).yeemail_element_droppable();
            $( ".builder-row" ).yeemail_element_sortable();
            $( ".builder-row-templates li" ).yeemail_row_droppable();
        }
        
    })
})(jQuery);