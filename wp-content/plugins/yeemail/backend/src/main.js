(function($) {
    "use strict";
    $( document ).ready( function () {
        // Save bulder to josn
        $.fn.yeemail_save_type = function (type) { 
            var html_emlement = $(this);
            var type = $(this).data("type");
            var container_style = {};
            var container_attr = {};
            var inner_style = {};
            var inner_attr = {};
            var style_container_element = yeemail_builder["block"][type]["editor"]["container"]["style"];
            var style_inner_element = yeemail_builder["block"][type]["editor"]["inner"]["style"];
            var attr_inner_element = yeemail_builder["block"][type]["editor"]["inner"]["attr"];
            //Save style container
            $.each( style_container_element, function( key, value ) {
                var data = html_emlement.css(value);
                container_style[value] = data;
            });
            //Save style inner
            $.each( style_inner_element, function( key, value ) {
                var style_content= {};
                $.each( value , function( index, style ) {
                  var data = html_emlement.find(key).css(style);
                  style_content[style] = data
                })
                inner_style[key] = style_content;
            });
            //Save attr
            $.each( attr_inner_element, function( key, value ) {
                var attr_content= {};
                $.each( value , function( index, style ) { 
                    switch(style) {
                      case "text":
                        var data = html_emlement.find(key).html();
                        break;
                      case "menu":
                        var menu = {};
                        var i =0;
                        html_emlement.find( ".yeemail-menu td" ).each(function() {
                            var text_menu = $(this).find("a").html();
                            var href = $(this).find("a").attr("href");
                            var background = $(this).css("background-color");
                            var color = $(this).find("a").css("color");
                            color = $.email_builder_cover_color(color);
                            background = $.email_builder_cover_color(background);
                            menu[i] = {"text":text_menu,"background":background,"color":color,"href":href} ;
                            i++;
                        });
                        var data = menu;
                        break;
                      case "html":
                      case "html_hide":
                      case "html1":
                      case "html_hide1":
                        var data = html_emlement.find(key).html(); 
                        break; 
                      default:
                        var data = html_emlement.find(key).attr(style);
                    }
                    attr_content[style] = data
                })
                inner_attr[key] = attr_content;
            });
            var condition = "";
            condition = html_emlement.attr("data-condition");
            if( condition === undefined || condition == "" ){
                condition = ""; 
            }
            return {"type":type,"container_style":container_style,"inner_style":inner_style,"inner_attr":inner_attr,"condition":condition };
        }
         //Click editor -> set element
        $.fn.yeemail_set_type_editor = function ( row ) {
                var builder = $(this);
                var type = $(this).data("type");
                if (typeof type === "undefined") {
                    return
                }
                var style_container_element = yeemail_builder["block"][type]["editor"]["container"]["style"];
                var attr_container_element = yeemail_builder["block"][type]["editor"]["container"]["attr"];
                //set editor in container element style
                $.each( style_container_element, function( key, value ) {
                    $.yeemail_set_css_element(key,value,builder);
                });
                $.each( attr_container_element, function( key, value ) {
                    var data = $(key).val();
                    if( $(key).attr("type") == "checkbox" ){
                        if(  $(key).is(':checked') ){
                          data = "ok";
                        }else{
                          data = "not";
                        }
                    }
                    builder.attr(value,data);
                });
                var condition = $(".builder__editor--item-conditional_logic .builder__editor--condition").val();
                builder.attr("data-condition",condition);
                //Element
                if( builder.closest(".builder-elements").length > 0 ) {
                    var attr_inner_element = yeemail_builder["block"][type]["editor"]["inner"]["attr"];
                    var style_inner_element = yeemail_builder["block"][type]["editor"]["inner"]["style"];
                    //set editor in element style
                    $.each( style_inner_element, function( key, value ) {
                      $.each( value , function( index, style ) {
                          $.yeemail_set_css_element(index,style,builder,key);
                      })
                    });
                    //set editor in  element attr
                    $.each( attr_inner_element, function( key, value ) {
                        $.each( value , function( index, attr ) {
                            var data = $(index).val();
                            var data_value = $(index).data("after_value");
                            if (data_value !== undefined) {
                                data +=data_value;
                            }
                            switch(attr) {
                              case "html":
                                //change shortcode
                                if(data == ""){
                                  data = tinyMCE.get('.builder__editor--js').getContent();
                                }
                                builder.find(key).html($.yeemail_replace_shorcode(data));
                                break;
                              case "html_hide":
                                  builder.find(key).html(data);
                                  break;
                              case "text":
                                builder.find(key).html(data);
                                break;
                              case "data-showimg": 
                              case "data-sku":
                              case "data-totals": 
                              case "data-showdes": 
                                  var showimg = $(".builder__editor--item-detail-template .detail-img");
                                  if( showimg.is(":checked")){
                                    showimg ="yes";
                                  }else{
                                    showimg ="hidden";
                                  }
                                  var totals = $(".builder__editor--item-detail-template .detail-totals");
                                  if( totals.is(":checked")){
                                    totals ="yes";
                                  }else{
                                    totals ="hidden";
                                  }
                                  var sku = $(".builder__editor--item-detail-template .detail-sku");
                                  if( sku.is(":checked")){
                                    sku ="yes";
                                  }else{
                                    sku ="hidden";
                                  }
                                  var des = $(".builder__editor--item-detail-template .detail-des");
                                  if( des.is(":checked")){
                                    des ="yes";
                                  }else{
                                    des ="hidden";
                                  }
                                  var value = "";
                                  value = $(index).val();
                                  if($(index).attr("type") == "checkbox"){
                                    if( $(index).is(":checked")){
                                      value = "yes";
                                    }else{
                                      value = "hidden";
                                    }
                                  }else{
                                    value = data;
                                  }
                                  var shortcode ="[yeemail_woo_order_detail show_img='"+showimg+"' item_totals='"+totals+"' item_sku='"+sku+"' show_des='"+des+"']";
                                  let count_hidden = $('th[data-sku="yes"],th[data-showimg="yes"],th[data-showdes="yes"]',builder).length;
                                  builder.find(".woo_totals th").attr("colspan",2 + parseInt(count_hidden));
                                  $(".builder-elements-content.yeemail_builder_focus .text-content-data").html(shortcode);
                                  builder.find(key).attr(attr,value);
                                  break;
                              case "menu":
                                  var tr = $('<tr class="links"></tr');
                                  var i_menu = 0;
                                  $( ".builder__editor--item-menu .menu-content-tool li.data" ).each(function() {
                                      var text_menu = $(this).find(".text").val();
                                      var href = $(this).find(".text_url").val();
                                      var background = $(this).find(".text_background").val();
                                      var color = $(this).find(".text_color").val();
                                      var td = $('<td align="center" valign="top"><a target="_blank" href=""></a></td>');
                                      td.css("background-color",background);
                                      td.find("a").css("color",color);
                                      td.find("a").attr("href",href);
                                      td.find("a").html(text_menu);
                                      td.appendTo(tr);
                                      i_menu++;
                                  });
                                  builder.find("tr").remove();
                                  tr.appendTo(builder.find("table"));
                                  var menu_width = 100 / i_menu;
                                  builder.find("td").css("width",menu_width);
                                  $.each( style_inner_element, function( key, value ) {
                                      $.each( value , function( index, style ) {
                                          $.yeemail_set_css_element(index,style,builder,key);
                                      })
                                  });
                                break;
                            default:
                                builder.find(key).attr(attr,data);  
                            }
                        })
                    });
                }
          }
         //Click emlement -> get and show editor
        $.fn.yeemail_load_type_editor = function (row ) {
            var type = $(this).data("type");
            var builder = $(this);
            var type_text = type;
            if( yeemail_builder["block"][type]["type_text"] !== undefined){
              type_text = yeemail_builder["block"][type]["type_text"];
            }
            $(".yeemail-builder-goback .yeemail-builder-goback_block").html(type_text);
            var show_class_container = yeemail_builder["block"][type]["editor"]["container"]["show"];
            var style_container_element = yeemail_builder["block"][type]["editor"]["container"]["style"];
            var attr_container_element = yeemail_builder["block"][type]["editor"]["container"]["attr"];
            //Show eidtor
            $.each( show_class_container, function( key, value ) {
              $(".builder__editor--item-"+value).removeClass("hidden");
            });
            //set editor in container element style
            $.each( style_container_element, function( key, value ) {
                var data = builder.css(value);
                $.yeemail_set_css_editor(value,key,data);
            });
            $.each( attr_container_element, function( key, value ) {
                var data = builder.attr(value);
                var type = $(key).attr("type");
                if( type == "checkbox"){
                  if( data != "ok"){
                        $(key).prop('checked', false);;
                    }else{
                        $(key).prop('checked', true);
                    }
                }
                $(key).val(data);
            });
            var condition = builder.attr("data-condition");
            if( condition === undefined || condition == "" ){
                condition = ""; 
            }
            $(".builder__editor--item-conditional_logic .builder__editor--condition").val(condition);
            if( !row ){
                var attr_inner_element = yeemail_builder["block"][type]["editor"]["inner"]["attr"];
                var style_inner_element = yeemail_builder["block"][type]["editor"]["inner"]["style"];
                //set editor in element style
                $.each( style_inner_element, function( key, value ) {
                  $.each( value , function( index, style ) {
                      var data = builder.find(key).css(style); 
                      $.yeemail_set_css_editor(style,index,data);
                  })
                });
                //set editor in  element attr
                $.each( attr_inner_element, function( key, value ) {
                    $.each( value , function( index, attr ) {
                        switch(attr) {
                              case "html_hide":
                              case "data-src":      
                                break;
                              case "text":
                                var data = builder.find(key).html();
                                $(index).val(data);
                                break;
                              case "html":
                                if( builder.find(key+"-data").length > 0 ){
                                    var data = builder.find(key +"-data").html(); 
                                }else{
                                    var data = builder.find(key).html(); 
                                }
                                tinyMCE.activeEditor.setContent(data);
                                $(index).val(data);
                                break;
                              case "html_ajax":
                                var data = builder.find(key+"-data").html();
                                tinyMCE.activeEditor.setContent(data);
                                break;
                              case "menu":
                                var html_menu=$("<ul></ul>");
                                $( builder.find(".yeemail-menu td") ).each(function() {
                                    var text_menu = $(this).find("a").html();
                                    var href = $(this).find("a").attr("href");
                                    var background = $(this).find("a").css("background-color");
                                    var color = $(this).find("a").css("color");
                                    color = $.email_builder_cover_color(color);
                                    background = $.email_builder_cover_color(background);
                                    var menu = $(".builder__editor--item-menu-hidden").clone().removeClass('hidden builder__editor--item-menu-hidden');
                                    menu.find(".text").val(text_menu);
                                    menu.find(".text_url").val(href);
                                    menu.find(".text_background").val(background);
                                    menu.find(".text_color").val(color);
                                    var container_li = $("<li class='data'></li>");
                                    menu.appendTo(container_li);
                                    container_li.appendTo(html_menu);
                                });
                                html_menu.removeClass('hidden');
                                $(".builder__editor--item-menu .menu-content-tool ul").remove();
                                html_menu.appendTo($(".menu-content-tool"));
                                $('.menu-content-tool .text_background,.menu-content-tool .text_color').wpColorPicker({
                                    change: function(event, ui){
                                        $(".yeemail_builder_focus").yeemail_set_type_editor();   
                                    }
                                });
                                break;  
                              default:
                                var data = builder.find(key).attr(attr); 
                                var type = $(index).attr("type");
                                if(type == "checkbox"){
                                  if( data == "ok" || data == "yes"){
                                      $(index).prop('checked', true);
                                  }else{
                                    $(index).prop('checked', false);
                                  }
                                }else{
                                  $(index).val(data);
                                }
                        }
                    })
                });
            }
        } 
        $.yeemail_replace_shorcode = function (str) { 
          if( str === undefined){
            return str;
          }
            let re = new RegExp(yeemail_script.builder_shorcode_re, "gi");
            str = str.replaceAll(re, function (matched) {
                matched = matched.replace(/\[|\]/gi, "");
                if(  yeemail_script.builder_shorcode[matched] === undefined ){
                  return "["+matched+"]";
                }else{
                  return yeemail_script.builder_shorcode[matched];
                }
            })
            return str;
        }
        // Drop emlement and load builder -> show data builder  
    	  $.yeemail_load_type = function (type,elements,email) { 
          if (typeof yeemail_builder["block"][type] === undefined){
            return "Type undefined";
          }else{
            var html = $(yeemail_builder["block"][type]["builder"]);
          }   
          if( elements ){
                var container_style = elements.container_style;
                var inner_style = elements.inner_style;
                var inner_attr = elements.inner_attr;
                html.find(".builder-elements-content").css(container_style);
                $.each( inner_style, function( key, value ) {
                  if( value != "") {
                    html.find(key).css(value); 
                  }
                });
                if( elements.condition != ""){
                    html.find(".builder-elements-content").attr("data-condition",elements.condition);
                }
                $.each( inner_attr, function( key, value ) {
                  $.each( value, function( k, v ) { 
                        switch(k) {
                            case "html":
                              var v_hide = inner_attr[".text-content-data"]["html_hide"];
                              if(v_hide != ""){
                                if(v_hide.search("=")>0){
                                  v_hide = v;
                                }
                              }
                              v_hide = $.yeemail_replace_shorcode(v_hide);
                              html.find(key).html(v_hide);
                              break;
                            case "text":
                            case "html_hide":
                              html.find(key).html(v);
                              break;
                            case "data-src":
                              html.find(key).attr("src",v);
                              html.find(key).attr(k,v);
                              break;
                            case "menu":
                              var menu_main ="";
                              $.each( v, function(menu_key, menu ) {
                                  menu_main += '<td style="background-color:'+menu.background+';padding:10px 0;" align="center" valign="top"><a style="color:'+menu.color+';" target="_blank" href="'+menu.href+'">'+menu.text+'</a></td>';
                              })
                              html.find("tr").html(menu_main);
                              $.each( inner_style, function( key, value ) {
                                  if( value != "") {
                                      html.find(key).css(value); 
                                  }  
                              });
                              break;
                            default:
                              html.find(key).attr(k,v);
                              break;
                          }
                    })
                });
            }
	          return html;
          }
          //Cover grb to hex
          $.email_builder_cover_color = function (rgb) {
              if( rgb === undefined){
                return "";
              }
              if( rgb == "" ){
                  return "transparent";
              } 
              if( "rgba(0,0,0,0)" == rgb.replace(/\s/g, '') ){
                  return "transparent";
              }
              rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
              return (rgb && rgb.length === 4) ? "#" +
              		  ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
              		  ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
              		  ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
          }
        //set css edit
        $.youtube_parser =function( url ) {
            var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
            var match = url.match(regExp);
            return (match&&match[7].length==11)? match[7] : false;
        }
        $.yeemail_background_img =function( data ) {
          if( data === undefined){
            return "";
          }
          var img = data.replace('url(','').replace(')','').replace(/\"/gi, "");
          return img;
        }
        $.yeemail_set_css_editor =function( style, index,data) {
            switch(style) {
                case "border-color":
                case "background-color":
                case "color":
                    data = $.email_builder_cover_color(data);
                    $(index).val(data);
                    $(index).closest(".wp-picker-container").find(".button").css("background-color",data);
                  break;
                case "background-image":
                    if( data == "none"){
                      data = "";
                    }else{
                      data = $.yeemail_background_img(data);
                    }
                    $(index).val(data); 
                    break;
                case "border-style":
                    $(index).val(data); 
                  break;
                case "text-align":
                    $(index).val(data); 
                    $(index).closest(".builder__editor--item").find(".button__align").removeClass("active");
                    $(index).closest(".builder__editor--item").find(".builder__editor--align-"+data).addClass("active");
                    if( data == "stat")
                  break;
                default:
                  data = data.replace("px",""); 
                  if(!isNaN(data)){
                    data = Math.ceil(data);
                  }
                  var type_text = $(index).attr("type");
                  if( type_text == "checkbox"){
                      if( data== "none"){
                        $(index).prop('checked', false);;
                      }else{
                        $(index).prop('checked', true);
                      }
                  } 
                  $(index).val(data); 
              }
        }
      $.yeemail_set_css_element =function( selector, style,builder,key) {
        var data = $(selector).val();
        var data_value = $(selector).data("after_value");
        if (data_value !== undefined) {
            data +=data_value;
        }
        if(key){
          builder = builder.find(key);
        }
        switch(style) {
          case "display":
            if( $(selector).is(':checked') ) { 
              builder.css(style,"inline-block");  
            }else{
                builder.css(style,"none"); 
            }
            break;
          case "text":
            builder.html(data);
            break;
          case "border-style":
            builder.css(style,data);
            break;
          case "background-image":
            if( data == ""){
                builder.css("background-image","");
            }else{
                builder.css({"background-image":'url("' + data + '")',
                            "background-position": "center",
                            "background-repeat": "no-repeat",
                            "background-size": "cover"
                          });
            }
            break;
          default:
            builder.css(style,data);
            break;
        }   
      }  
    })
})(jQuery);