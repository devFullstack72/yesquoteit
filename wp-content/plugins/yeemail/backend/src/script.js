(function($) {
    "use strict";
    $( document ).ready( function () {
        var yeemail_builder_main = {
            json_to_builder: function(){
                var data_json = $(".yeemail_data_email").val();
                var datas = {};
                var html="";
                if( data_json =="" || typeof data_json === "undefined"){
                  return;
                }
                datas = JSON.parse(data_json);
                $(".builder__list").html("");
                $(".yeemail-builder-main").css(datas["container"]);
                for (let index_row in datas['rows'] ) {
                    var row_style = datas['rows'][index_row].style;
                    var row_columns = datas['rows'][index_row].columns;
                    var row_type = datas['rows'][index_row].type;
                    var row_attr = datas['rows'][index_row].attr;
                    var row_condition = datas['rows'][index_row].condition;
                    var row = $('<div class="builder-row-container builder__item"></div>');
                    var inner_row = $('<div data-type="'+row_type+'" class="builder-row-container-row builder-row-container-'+row_type+'"></div>');
                    inner_row.css(row_style);
                    inner_row.attr(row_attr);
                    inner_row.attr("data-condition",row_condition);
                    inner_row.appendTo(row);
                    var i = 0;
                    for (let index_column in row_columns ) {
                      i++;
                      switch(row_type) {
                          case "row3":
                              if( i == 1){
                                var column = $('<div class="builder-row bd-row-2 builder-row-empty"></div>');  
                              }else{
                                var column = $('<div class="builder-row builder-row-empty"></div>');
                              }
                          break;
                          case "row4":
                              if( i != 1){
                                var column = $('<div class="builder-row bd-row-2 builder-row-empty"></div>');  
                              }else{
                                var column = $('<div class="builder-row builder-row-empty"></div>');
                              }
                              break;
                          default:
                              var column = $('<div class="builder-row builder-row-empty"></div>');
                        }
                      var elements = row_columns[index_column].elements;
                      for (let index_element in elements ) { 
                          column.removeClass('builder-row-empty');
                          var element_type = elements[index_element].type;
                          var element=$.yeemail_load_type(element_type,elements[index_element]);
                          element.appendTo(column);
                      } 
                      column.appendTo(row.find(".builder-row-container-row"));
                  }
                   //row.find(".builder-row").yeemail_element_droppable();
                  row.find(".builder-row").yeemail_element_sortable();             
                  row.appendTo(".builder__list--js");                
                }
            },
            builder_to_json: function(){ 
                var datas = {}; 
                var container = $(".yeemail-builder-main");
                datas['container'] = {
                    'background-color': $.email_builder_cover_color($(".yeemail-builder-main").css("background-color")),
                    'padding-top': $(".yeemail-builder-main").css("padding-top"),
                    'padding-bottom': $(".yeemail-builder-main").css("padding-bottom"),
                    'padding-left': $(".yeemail-builder-main").css("padding-left"),
                    'padding-right': $(".yeemail-builder-main").css("padding-right"),
                    'background-image': $(".yeemail-builder-main").css("background-image"),
                    "background-position": $(".yeemail-builder-main").css("background-position"),
                    "background-repeat": $(".yeemail-builder-main").css("background-repeat"),
                    "background-size": $(".yeemail-builder-main").css("background-size"),
                };
                container.css(datas["container"]);
                datas["rows"] = {};
                $(".builder-row-container-row").each(function(index,row){
                    var type = $(row).data("type");
                    var style_row = {};
                    var list_css = yeemail_builder["block"][type]["editor"]["container"]["style"];
                    $.each( list_css, function( key, value ) {
                        var css = $(row).css(value);
                        if( value.indexOf("color") >= 0 ){
                          style_row[value] = $.email_builder_cover_color(css);    
                        }else{
                          style_row[value] = css; 
                        }
                    });
                    var attr_row = {};
                    attr_row["background_full"] = $(row).attr("background_full");
                    attr_row["responsive"] = $(row).attr("responsive");
                    if( attr_row["background_full"] !="not" ) {
                      attr_row["background_full"] = "ok";
                    }
                    if( attr_row["responsive"] !="not" ) {
                      attr_row["responsive"] = "ok";
                    }
                    var condition = $(row).attr("data-condition");
                    if( condition === undefined){
                        condition = ""; 
                    }
                    datas["rows"][index] = {style:style_row,
                                            attr: attr_row,
                                            type:   type,
                                            columns: {},
                                            condition: condition
                                          };
                    $(row).find(".builder-row").each(function(index1,row1){ 
                        datas["rows"][index]["columns"][index1]={
                            elements: {}
                        };
                        $(row1).find(".builder-elements-content").each(function(index2,row2){
                          var type = $(row2).data("type");
                          var element = $(row2).yeemail_save_type();
                          datas["rows"][index]["columns"][index1]["elements"][index2]= element;
                        })
                    })                       
                })
                return JSON.stringify(datas);
            },
            json_to_email: function(){
                var data_json = $(".yeemail_data_email").val();
                var id = $("#post_ID").val();
                var datas = {};
                if( data_json ==""){
                  return;
                }
                datas = JSON.parse(data_json);
                var container =$('<div data-yeemail-id="'+id+'" class="wap" width="100%" style="margin: 0 auto;padding-top:100px;padding-bottom:100px;"><div class="container" style="margin: 0 auto;"class="email-container" ></div></div>');
                container.css(datas["container"]);
                for (let index_row in datas['rows'] ) {
                    var row_style = datas['rows'][index_row].style;
                    var row_attr = datas['rows'][index_row].attr;
                    var row_columns = datas['rows'][index_row].columns;
                    var row_type = datas['rows'][index_row].type;
                    var row_container = $('<div class="container-row" style="width:100%"></div>');
                    for (const [key, value] of Object.entries(row_attr)) {
                      switch(key) { 
                          case "background_full":
                              if( value !== "not"){
                                row_container.css("background-color",row_style["background-color"]);
                              }
                            break;
                            case "responsive":
                              if( value !== "not"){
                                row_container.addClass("yeemail-responsive");
                              }
                            break;
                          default:
                            row_container.attr(key,value);
                            break;
                      }
                    }
                    var row =$('<table class="row" width="'+yeemail_script.email_width+'px" class="row" align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;"><tr></tr></table');
                    row = row.css(row_style);
                    var i = 0;
                    for (let index_column in row_columns ) {
                        i++;
                        var col_width = "100%";
                        switch(row_type) {
                          case "row2":
                            col_width = "50%";
                            break;
                          case "row3":
                              if( i == 1){
                                col_width = "65%";  
                              }else{
                                col_width = "35%";
                              } 
                          break;
                          case "row4":
                              if( i == 1){
                                col_width = "35%";  
                              }else{
                                col_width = "65%";
                              } 
                              break;
                          case "row5":
                              col_width = "33.33%;";
                              break;
                          case "row6":
                              col_width = "25%;";
                              break;
                          default:
                              col_width = "100%;";
                        }
                        var column = $('<td class="col" width="'+col_width+'"></td>');
                        var elements = row_columns[index_column].elements;
                        var col_container = $("<div class='col-container'></div>");
                        for (const [key, value] of Object.entries(elements)) {
                            var element_type = value.type;
                            var element = $.yeemail_load_type(element_type,value);
                            element.find(".builder-elements-content .text-content").remove();
                            element.appendTo(col_container);
                          }
                        col_container.appendTo(column);
                        column.appendTo(row.find("tr"));
                    } 
                    row.appendTo(row_container);
                    row_container.appendTo(container.find(".container"));                             
                }
                return container[0].outerHTML;
            },
            json_to_email_head: function(){
              return ''
            }
        }
        yeemail_builder_main.json_to_builder();
        $( ".builder-row-container-row" ).each(function( index ) {
          if( $(this).attr("background_full") != "not" ){
            $(this).closest(".builder-row-container").css("background-color",$(this).css("background-color"));
          }
        });
        $('body').on("click",".builder-row-container",function(e){
            e.preventDefault();
            $(this).find(".builder-row-container-row").click();
         })
        $('body').on("click",".yeemail-builder-save",function(e){
             e.preventDefault();
             var email_json = yeemail_builder_main.builder_to_json();
             $(".yeemail_data_email").val(email_json);
            $("#publish").click();
        })
        $('body').on("click",".yeemail-builder-testting-send",function(e){
            e.preventDefault();
            var email = $("#yeemail-builder-testting").val();
            var id = $("#post_ID").val();
            if( email =="" || id == ""){
              alert("Enter Email or Save Post");
            }else{
                $(this).html("Sending...");
                var button = $(this);
                var data = {
                    'action': 'yeemail_builder_send_email_testing',
                    'id': id,
                    'nonce': yeemail_script.nonce,
                    'email' : email
                  };
                jQuery.post(ajaxurl, data, function(response) {
                  alert(response);
                  button.html("Send Email");
                });
            }
         })
        $('body').on("click",".momongaDelete",function(e){
             e.preventDefault();
             e.stopPropagation();
             $(".builder__editor .builder__editor--item").addClass('hidden');
             if(  $(this).closest(".builder-elements").length < 1 ){
                $(this).closest(".builder-row-container").remove();
             }else{
                $(this).closest('.builder-elements').remove();
             }
        })
        $('body').on("click",".momongaDuplicate",function(e){
             e.preventDefault();
             e.stopPropagation();
             if(  $(this).closest(".builder-elements").length > 0 ){
                var main_item = $(this).closest('.builder-elements');
                var newItem = main_item.clone(true);
                newItem.find(".builder__toolbar").remove();
                newItem.find(".builder-elements-content").removeClass("yeemail_builder_focus");
                main_item.after(newItem);
             }else{
                var main_item = $(this).closest('.builder-row-container');
                var newItem = main_item.clone(true);
                newItem.find(".builder__toolbar").remove();
                newItem.removeClass('yeemail_builder_show').find(".builder-row-container-row").removeClass("yeemail_builder_focus");
                newItem.find(".builder-elements-content").removeClass("yeemail_builder_focus");
                main_item.after(newItem);
             }   
        })
        $("body").on('mouseenter', '.builder-elements', function() {
            if( $(this).closest(".yeemail_builder_show").length < 1  ){
               $(this).closest('.builder-row-container').addClass('yeemail_builder_hover');
                $(this).addClass('yeemail_builder_hover');     
            }else{
                $(this).addClass('yeemail_builder_hover');  
            }
        });
        $("body").on('mouseleave', '.builder-elements', function() {
            $(this).closest('.builder-row-container').removeClass('yeemail_builder_hover');
            $(this).removeClass('yeemail_builder_hover');
        });
        $("body").on('mouseenter', '.builder-row-container-row', function() {
            if( $(this).closest(".yeemail_builder_show").length < 1  ){
               $(this).closest('.builder-row-container').addClass('yeemail_builder_hover');
            }
        });
        $("body").on('mouseleave', '.builder-row-container-row', function() {
            $(this).closest('.builder-row-container').removeClass('yeemail_builder_hover');
        });
        $('body').on("click",".builder__tab a",function(e){
             e.preventDefault();
             $(".builder__tab a").removeClass("active");
             $(this).addClass("active");
             var tab = $(this).attr('id');
             $('.tab__content').hide();
             $(tab).show();
        })
        $('body').on("click",".yeemail-builder-goback",function(e){
            e.preventDefault();
            $(".builder__tab li").first().find("a").click();
      })
        $('body').on('click', '.yeemail-builder-import', function(e){
            e.preventDefault();
                var button = $(this),
                    custom_uploader = wp.media({
                title: 'Import template',
                library : {
                    type : [ 'json',"text"]
                },
                button: {
                    text: 'Import template' // button label text
                },
                multiple: false // for multiple image selection set to true
            }).on('select', function() { // it also has "open" and "close" events 
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $.getJSON(attachment.url, function(data){
                    $(".yeemail_data_email").val(data);
                    $(".builder__list").html("");
                    yeemail_builder_main.json_to_builder();
                    $( ".builder-row-container-row" ).each(function( index ) {
                      if( $(this).attr("background_full") != "not" ){
                        $(this).closest(".builder-row-container").css("background-color",$(this).css("background-color"));
                      }
                    });
                }).fail(function(){
                  alert("Error");
                });
            })
            .open();
        });
        $("body").on("click",".yeemail-builder-export",function(){
            $("<a />", {
                "download": "yeemail_template.json",
                "href" : "data:text/plain;charset=utf-8," + encodeURIComponent(JSON.stringify($(".yeemail_data_email").val()))
              }).appendTo("body")
              .click(function() {
                 $(this).remove()
              })[0].click();
        })
        $("body").on("click",".yeemail-builder-choose-template",function(e){
            e.preventDefault();
            $( "#yeemail-builder-templates" ).dialog({
              modal: true,
              width: 900,
              title: "Templates",
              buttons: {
                Close: function() {
                  $( this ).dialog( "close" );
                }
              }
            });
            return false;
        })
        $("body").on("click",".yeemail-builder-choose-shortcodes",function(e){
          e.preventDefault();
          $( "#yeemail-builder-shortcodes-templates" ).dialog({
            modal: true,
            width: 800,
            title: "All Shortcodes",
            buttons: {
              Close: function() {
                $( this ).dialog( "close" );
              }
            }
          });
          return false;
        })
        $("body").on("click",".yeemail-builder-choose-test-email",function(e){
          e.preventDefault();
          $( "#yeemail-builder-templates-test-email" ).dialog({
            modal: true,
            width: 500,
            title: "Test Email",
            buttons: {
              Close: function() {
                $( this ).dialog( "close" );
              }
            }
          });
          return false;
        })
        $("body").on("click",".yeemail-builder-actions-import",function(e){
            e.preventDefault();
            var attachment = $(this).closest(".grid-item").data("file");
                $.getJSON(attachment, function(data){
                    $(".yeemail_data_email").val(data);
                    $(".builder__list").html("");
                    yeemail_builder_main.json_to_builder();
                    $( ".builder-row-container-row" ).each(function( index ) {
                      if( $(this).attr("background_full") != "not" ){
                        $(this).closest(".builder-row-container").css("background-color",$(this).css("background-color"));
                      }
                    });
                    $( "#yeemail-builder-templates" ).dialog( "close" );
                }).fail(function(){
                  alert("Error");
                });
        })
        $('body').on("click",".yeemail-builder-choose-blank",function(e){ 
          e.preventDefault();
          if (confirm("Changes you made will be lost.") == true) {
              $.getJSON(yeemail_script.yeemail_url_plugin + "backend/demo/yeemail_template0.json", function(data){
                  $(".yeemail_data_email").val(data);
                  $(".builder__list").html("");
                  yeemail_builder_main.json_to_builder();
                  $( ".builder-row-container-row" ).each(function( index ) {
                    if( $(this).attr("background_full") != "not" ){
                      $(this).closest(".builder-row-container").css("background-color",$(this).css("background-color"));
                    }
                  });
              }).fail(function(){
                alert("Error");
              });
          }
      })
      $('body').on("click",".yeemail-builder-choose-reset",function(e){ 
          e.preventDefault();
          if (confirm("All changes you made won't be saved.") == true) {
            var id = $("#post_ID").val();
            var data = {
                'action': 'yeemail_builder_reset_template',
                'id': id,
                'nonce': yeemail_script.nonce,
              };
            jQuery.post(ajaxurl, data, function(response) {
              window.location.reload(true);
            });
          }
      })
      $("body").on("click",".yeemail-builder-expand",function(e){
          e.preventDefault();
          var type = $(this).data("type");
          if( type == "left"){
              $(".builder__widget").effect( "size", {
                                  to: { width: 900, }
                                }, 500 );
              $(this).closest('div').find(".yeemail-builder-shrink").removeClass("hidden");
              $(this).addClass('hidden');
          }else{
              $("#poststuff #post-body.columns-2").css("margin-right","300px");
              $(".yeemail-builder-slide").removeClass('hidden');
              $(".yeemail_builder-expand-right").addClass('hidden');
          }
      })
      $("body").on("click",".yeemail-builder-shrink",function(e){
          e.preventDefault();
          var type = $(this).data("type");
          if( type == "left"){
              $(".builder__widget").effect( "size", {
                                  to: { width: 420, }
                                }, 500 );
              $(this).closest('div').find(".yeemail-builder-expand").removeClass("hidden");
              $(this).addClass('hidden');
          }else{
              $("#poststuff #post-body.columns-2").css("margin-right","0");
              $(".yeemail-builder-slide").addClass('hidden');
              $(".yeemail_builder-expand-right").removeClass('hidden');
          }
      })       
    })
})(jQuery);