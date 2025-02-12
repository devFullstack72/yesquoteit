(function($) {
    "use strict";
    $( document ).ready( function () { 
    	var ajax_change_editor = null;
        function formatState_data (state) {
            if (!state.id) {
              return state.text;
            }
            var baseUrl = "/user/pages/images/flags";
            var data_class = $(state.element).data("class");
              var $state = $(
                '<span class="slect-main-rs"><span class="'+data_class+'"></span> ' + state.text + '</span>'
            );
            return $state;
          };
        $('.yeemail_choose_template_status').select2({
            templateResult: formatState_data,
            minimumResultsForSearch: -1,
            width: '100%',
            containerCssClass: "yeemail_select_width" 
          });
        $('.builder__editor1 select').select2({
            minimumResultsForSearch: -1
          });
        //upload IMG
        $('body').on('click', '.upload-editor--image', function(e){
            e.preventDefault();
            var input = $(this).closest(".builder__editor--button-url").find(".image_url");
                var button = $(this),
                    custom_uploader = wp.media({
                title: 'Insert image',
                library : {
                    type : 'image'
                },
                button: {
                    text: 'Use this image' // button label text
                },
                multiple: false // for multiple image selection set to true
            }).on('select', function() { // it also has "open" and "close" events 
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                input.val(attachment.url).change();
                var img = new Image();
                img.src = attachment.url;
                img.onload = function() {
                  var pr_width = $(".yeemail_builder_focus").width();
                  pr_width = Math.round( pr_width); 
                  var  width = Math.round( this.width); 
                  var  height = Math.round( this.height); 
                  if( width >  pr_width ){ 
                        var pe = Math.round(width / pr_width);
                        $(".builder__editor--item-width .text_width").val(pr_width);
                        $(".builder__editor--item-height .text_height").val(Math.round(height/pe)).change();
                  }else{
                        $(".builder__editor--item-width .text_width").val(width);
                        $(".builder__editor--item-height .text_height").val(height).change();
                  }
                } 
            })
            .open();
        });
        //Menu
        $('body').on("click",".yeemail_builder_add_menu",function(e){
             e.preventDefault();
             var data =$(".builder__editor--item-menu-hidden").html();
             $(".menu-content-tool>ul").append("<li class='data'>"+data+"</li>"); 
             $('.menu-content-tool .text_background,.menu-content-tool .text_color').wpColorPicker({
                change: function(event, ui){
                    $(".yeemail_builder_focus").yeemail_set_type_editor();
                    if( $(".yeemail_builder_focus").attr("background_full") == "ok" ){
                        $(".yeemail_builder_focus").closest(".builder-row-container").css("background-color",$(".yeemail_builder_focus").css("background-color"));
                    }else{
                        $(".yeemail_builder_focus").closest(".builder-row-container").css("background-color","transparent");
                    }     
                }
            });  
        })
        //Editor Change
        $('body').on("change keyup",".builder__editor input, .builder__editor--item input, .builder__editor--item select, .builder__editor--item textarea",function(e){
             $(".yeemail_builder_focus").yeemail_set_type_editor();
            if( $(".yeemail_builder_focus").attr("background_full") == "ok" ){
                $(".yeemail_builder_focus").closest(".builder-row-container").css("background-color",$(".yeemail_builder_focus").css("background-color"));
            }else{
                $(".yeemail_builder_focus").closest(".builder-row-container").css("background-color","transparent");
            }    
        })
        $('body').on("change",".yeemail_settings_update",function(e){
            e.preventDefault();
            var id = $(this).val();
            var status = "disable";
            if($(this).is(':checked')){
                status = "enable";
            }
            var data = {
                'action': 'yeemail_update_settings_template',
                'id': id,
                'status': status,
                'nonce': yeemail_script.nonce
            };
            $.post(ajaxurl, data, function(response) {  
                if(status == "enable"){
                    $(".yeemail_settings_update-"+id).prop('checked', true);
                }else{
                    $(".yeemail_settings_update-"+id).prop('checked', false);
                }
            });
            //sync 2 checkbox
       })
        //set width container
        $('body').on("change keyup",".builder__editor--item-setting_width .text_width",function(e){
            e.preventDefault();
            var width = $(this).val();
            if(width < 480){
                width = 480; 
            }
            if(width > 900){
                width = 900; 
            }
            $(".builder-row-container-row").css("width",width);
       })
        //align
         $('body').on("click",".builder__editor--align a",function(e){
             e.preventDefault();
             $(".builder__editor--align a").removeClass("active");
             $(this).addClass("active");
             var vl = $(this).data("value");
             $(this).closest(".builder__editor--align").find(".text_align").val(vl).change();
        })
        $("body").on("click",".builder__editor--js",function(e){
            tinymce.execCommand('mceToggleEditor', false, 'content');
        })
         //text
         tinymce.init({
            selector: '.builder__editor--js',
            mode: 'exact',
            content_style: "body { font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;background:#eeeeee;} h1,h2,h3,h4,h5,h6 { font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; }",
            font_formats: yeemail_script.google_font_font_formats,
            height: 'auto',
            skin: "lightgray",
			theme: "modern",
            menubar: false,
            statusbar: false,
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            forced_root_block : false,
            plugins: ["link textcolor colorpicker image code_toggle"],
            toolbar:
                [
                    'bold italic underline | fontselect styleselect',
                    'fontsizeselect | forecolor | backcolor | link image',
                    'yeemail_shortcode | code_toggle'
                ],   
            fontsize_formats: '10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 22px 24px 26px 28px 30px 35px 40px 50px 60px',  
            setup:function(ed) {
                var full_shortcodes = [];
                $.each(yeemail_script.shortcodes, function( index_1, values_1 ) {
                    var menu1 = [];
                    $.each(values_1, function( index_2, value_2 ) {
                        if (typeof value_2 === 'object' && value_2 !== null){
                            var menu2 = [];
                            $.each(value_2, function( index_3, value_3 ) {
                                menu2.push({"text":value_3,onclick: function() {
                                    if( index_3.search("{") <0) {
                                        ed.insertContent("["+index_3+"]");
                                    }else{
                                        ed.insertContent(index_3);
                                    }
                                }});
                            });
                            menu1.push({"text":index_2,"menu":menu2});
                        }else{
                            menu1.push({"text":value_2,onclick: function() {
                                if( index_2.search("{") <0) {
                                    ed.insertContent("["+index_2+"]");
                                }else{
                                    ed.insertContent(index_2);
                                }
                            }});
                        }
                    });
                    full_shortcodes.push({"text": index_1, "menu":menu1 });
                });  
                 ed.addButton('yeemail_shortcode', {
                    text: 'Shortcodes',
                    type: "menubutton",
                    menu: full_shortcodes,      
                });
                ed.addButton('icons', {
                        onclick: function() {
                            ed.windowManager.open( {
                                title: 'Insert icon',
                                body: [{
                                    type: 'textbox',
                                    name: 'icon',
                                    label: 'Icon'
                                },
                                {
                                    type: 'textbox',
                                    name: 'size',
                                    label: 'Size'
                                },
                                {
                                    type: 'textbox',
                                    name: 'color',
                                    label: 'Color'
                                },
                                ],
                                onsubmit: function( e ) {
                                    ed.insertContent( '&lt;h3&gt;' + e.data.title + '&lt;/h3&gt;');
                                }
                            });
                        }
                    });
                ed.on('keyup paste change', function(e) {
                    $(".builder-elements-content.yeemail_builder_focus .text-content-data").html(ed.getContent()); 
                    $(".builder-elements-content.yeemail_builder_focus .text-content").html($.yeemail_replace_shorcode(ed.getContent())); 
                });
            }
        });
         //color
          $('.builder__editor_color').wpColorPicker({
            change: function(event, ui){     
                $(".yeemail_builder_focus").yeemail_set_type_editor();
                if( $(".yeemail_builder_focus").attr("background_full") == "ok" ){
                    $(".yeemail_builder_focus").closest(".builder-row-container").css("background-color",$(".yeemail_builder_focus").css("background-color"));
                }else{
                    $(".yeemail_builder_focus").closest(".builder-row-container").css("background-color","transparent");
                }    
            }
        });
        //color link
        $('.builder__editor_color_link').wpColorPicker({
            change: function(event, ui){
                $("#poststuff .yeemail-builder-main a").css("color",ui.color.toString());
            }
        });
        $.selector_element = function(){
            var button_tab = $('.builder__tab li a');
            button_tab.each(function () {
                var button = $(this);
                if(button.attr('id') == '#tab__editor' ) {
                    $('.builder__tab li a').removeClass('active');
                    button.addClass('active');
                    var tab = $(button.attr('id'));
                    $('.tab__content').hide();
                    tab.show();
                }
            });
            $('.builder__toolbar').remove();
            $(".builder__editor--item").addClass("hidden");
            $("div").removeClass("yeemail_builder_focus").removeClass("yeemail_builder_show");
        }
        ///Get ----------------------------------------
        $('body').on("click",".yeemail-builder-main-change_backgroud",function(e){ 
            e.preventDefault();
            e.stopPropagation();
            if(!yeemail_script.disable_builder){
                $.selector_element();
                $(".yeemail-builder-main").addClass("yeemail_builder_focus yeemail_builder_show");
                $(".yeemail-builder-main").yeemail_load_type_editor(true);
            }
        })
        //settings
        $('body').on("click",".yeemail_button_settings",function(e){ 
            e.preventDefault();
            e.stopPropagation();
            if(!yeemail_script.disable_builder){
                $.selector_element();
                $(".yeemail-builder-main").addClass("yeemail_builder_focus yeemail_builder_show");
                $(".yeemail-builder-main").yeemail_load_type_editor(true); 
            }
        })
        $('body').on("change",".yeemail_choose_template_status",function(e){ 
            e.preventDefault();
            if (confirm("You need to save data before switching to another template!") == true) {
                var url = $(this).find(':selected').data('link');
                window.location = url;
            }
        })
        $('body').on("click",".builder__widget_tab_title",function(e){ 
            e.preventDefault();
            $(this).toggleClass("yeemail_tab_hide");
            $(this).closest(".builder__widget_tab").find("ul").slideToggle();
        })
        //click out 
        $('body').on("click",".builder__tab,.yeemail-builder-slide,.builder-actions,#builder-header,#titlediv",function(e){
            $("div").removeClass('yeemail_builder_show yeemail_builder_focus');
            $("div").remove(".builder__toolbar");
            $(".builder__editor--item").addClass('hidden');
        })
        $('body').on("click",".builder-elements-content",function(e){
            e.preventDefault();
            e.stopPropagation();
            if(!yeemail_script.disable_builder){
                $.selector_element();
                var toolbar= $('<div class="builder__toolbar">' +
                '<div class="momongaDragHandle"><i class="yeemail_builder-icon icon-menu-1"></i></div>' +
                '<div class="momongaEdit"><i class="yeemail_builder-icon icon-pencil"></i></div>' +
                '<div class="momongaDuplicate"><i class="yeemail_builder-icon icon-docs"></i></div>' +
                '<div class="momongaDelete"><i class="yeemail_builder-icon icon-trash"></i></div>' +
                '</div>');
                $(this).addClass("yeemail_builder_focus");
                $(this).closest(".builder-row-container").addClass("yeemail_builder_show");
                $(this).append(toolbar.clone());
                $(this).closest(".builder-row-container").append(toolbar);
                $(this).yeemail_load_type_editor();
            }  
        })
        $("body").on("click",".momongaEdit",function(e){
            e.preventDefault();
            $(this).closest('.builder__toolbar').parent( ".builder-row-container").find(".builder-row-container-row").click();
        })
        $('body').on("click",".builder-row-container-row",function(e){
            e.preventDefault();
            e.stopPropagation();
            if(!yeemail_script.disable_builder){
                $.selector_element();
                var toolbar= $('<div class="builder__toolbar">' +
                '<div class="momongaDragHandle"><i class="yeemail_builder-icon icon-menu-1"></i></div>' +
                '<div class="momongaEdit"><i class="yeemail_builder-icon icon-pencil"></i></div>' +
                '<div class="momongaDuplicate"><i class="yeemail_builder-icon icon-docs"></i></div>' +
                '<div class="momongaDelete"><i class="yeemail_builder-icon icon-trash"></i></div>' +
                '</div>');
                $(this).addClass("yeemail_builder_focus");
                $(this).closest(".builder-row-container").addClass('yeemail_builder_show');
                $(this).closest(".builder-row-container").removeClass('.builder-row-empty');
                $.check_row_empty();
                $(this).closest(".builder-row-container").append(toolbar);
                $(this).yeemail_load_type_editor(true);
            }
        })
        $.check_row_empty = function() {
            $( ".builder-row-container" ).each(function( index ) {
                    $(this ).find(".builder-row").each(function( index ) { 
                        var check = $(this).find('.builder-elements');
                        if( check.length > 0 ){
                            $(this).removeClass('builder-row-empty');
                            $(this).closest('.builder-row-container').removeClass('builder-row-empty');
                        }
                    })
            });
        }
})
})(jQuery);