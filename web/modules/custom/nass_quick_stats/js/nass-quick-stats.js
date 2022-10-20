(function ($, Drupal, drupalSettings) {
  //console.log(drupalSettings);
  Drupal.behaviors.quickStats = {
    attach: function (context, settings) {
      
      $(document).ready(function () {
        init();
        console.log(document);
      });

        // qslite.js
        // ... last version 67
        var ERR_ = 1;
        var tableName = "";
        var MENUS_ = new Array("SECTOR_DESC","GROUP_DESC","COMMODITY_DESC","REPORT_NAME","YEAR","AGG_LEVEL_DESC","STATE_NAME","LOCATION_DESC");
        // init
        // ...called onLoad
        function init() {
            var hash=parent.location.hash;
            var params="";
            if(hash) {
              var uuid=hash.replace('#','');
              jQuery.ajax( {
                  type: "POST",
                  url: "/qs/uuid/decode/"+uuid,
                  data: '', 
                  dataType: "json",
                  //cache: false,
                  async: false,
                  success: function(data,stat,xhr) {
                      params=data;
                  },
                  error: function(){
                      alert("init ::async error");
                  }
              } );
              // Debug ....
              /*for(var i =0; i<MENUS_.length -1;i++) {*/
              /*for (var key in params) {*/
              /*if( key == MENUS_[i]) {*/
              /*//debug(key +"="+ params[key]);*/
              /*/*//*tmp += key +" in ('" + params[key] +"') and ";*/
              /*}}}*/
              var where='and ';
              //var where=' ';
        //alert(where);
              for(var i =0; i<MENUS_.length -0;i++) { //out of bound     
                  debug("menu : "+i+" = " + MENUS_[i]);
        debug("MENUS_["+i+"]:"+MENUS_[i]);
                  jQuery.each(params,function(key,val) {
        debug("key:"+key+" val:"+val);
                    if( key == MENUS_[i] ){// && key != "REPORT_NAME") {
                      if (key != "REPORT_NAME"){
                          if(key != "YEAR") {
                              value = params[key].toString().replace(/( \, )/g,"','");
                              where += key +" in ('" + value +"') and ";
                          } else {
                              value = params[key];//.toString().replace(/\,/,"','");
                              where += key +" in (" + value + ") and ";
                          }
                      }
                      //restoreMenus(key,val,MENUS_[i+1],where);
                      var curMenu=key;
                      var curVal=val;
                      var nexMenu='';
                      if(i<MENUS_.length) {
                          nexMenu=MENUS_[i+1];
                      } else {
                          nexMenu=MENUS_[i];
                      }
                      debug("current menu: "+curMenu + "    current selected: " + curVal);
                      restoreMenus(curMenu,curVal,nexMenu,where);
                    }
                  } );
              }
            }
        }

        // restoreMenus
        //...
        function restoreMenus(cName,cValue,nName,where){
            var iregexp = new RegExp("_\\w+");
            var menuDiv = cName.toLowerCase().replace(iregexp,"_div");
            if(menuDiv=="year") {
                menuDiv="year_div";
                toggleDiv("yearloc")
                selDiv="YEAR";    
            } else if (menuDiv =='ag_div'){
                nName="AGG_LEVEL_DESC"
            }
        debug("menuDiv::" + menuDiv);
            toggleDiv(menuDiv);
            //ajax ...
            where=where.replace(/and.$/,"");
            var tabbleName="";
            if(cName == "SECTOR_DESC" || cName == "GROUP_DESC") {
                data_ = "current="+escape(cName)+"&next="+escape(nName)+"&where="+escape(where);
            }
            else if( cName == "COMMODITY_DESC" ) {
                reportName = where;//currentSelected;
                data_ = "current="+escape(cName)+"&next="+escape(nName)+"&reportname="+escape(reportName);
            }
            else {
              if(cName=="REPORT_NAME") { 
                  tableName='getme table_name from table_params where table_param_key='+cValue;
              }
              data_ = "current="+escape(cName)+"&next="+escape(nName)+"&tablename="+escape(tableName)+"&currentselect="+escape(where);
            }
            //
            var desDiv='';
            if(nName == "YEAR") { 
                desDiv="year_";
            } else {
                if(nName != null){
                    desDiv = nName.toLowerCase().replace("_","");
                }
            }
        //alert(where);
            restoreCallBack(desDiv,data_,cName,cValue);
        }
        // setSelected ... 
        function setSelected(id, value){
          $.trim(id);
          $.trim(value);
          var menu_obj = document.getElementById(id);
          var text='';
          //debug("set selected... text: " + id + "... value: " + value + " length: " + menu_obj.options.length);
          if(menu_obj){
              for(i=0; i<menu_obj.options.length; i++) {
                  text = menu_obj.options[i].text;
                  if(text==value) { 
                      menu_obj.options[i].selected = true; 
                      return 0;
                  } else {
                      //menu_obj.options[i].selected = true;
                      ;
                  }
              }
          }
        }
        // restoreSelected ... 
        function restoreSetSelected(id, value){
          $.trim(id);
          $.trim(value);
          var menu_obj = document.getElementById(id);
          var text='';
          var values = new Array();
          var tmp = value.toString();
          //tmp=tmp.split(" , ").join(',');
          //values = tmp.split(',');
          var iregexp = new RegExp(" , ");
          values = tmp.split(iregexp);
          debug("===> values: " + values + "....values[1]" + values[1]);
          if(menu_obj){
              for(var j=0;j<values.length;j++) { 
                for(var i=0; i<menu_obj.options.length; i++) {
                  //text = menu_obj.options[i].text;
                  text = menu_obj.options[i].value;
                  if(text==values[j]) { 
                      debug(text+" <==TEXT ... VALUE==> "+values[j]);
                      menu_obj.options[i].selected = true; 
                      break; 
                  } else {
                      //menu_obj.options[i].selected = true;         
                  }
                }
              }
          }
          else {
              debug(" object is  not exist");
          }
        }
        // ...
        // updateMenu1 ...
        function updateMenu1(current) {
              var c_index = $.inArray(current,MENUS_); 
              var next = MENUS_[c_index+1];
              var iregexp = new RegExp("_\\w+");
              //var nextDiv = next.toLowerCase().replace("_","div");
              var nextDiv = next.toLowerCase().replace(iregexp,"_div");
              if(nextDiv=="year") {
                  nextDiv="year_div";
                  toggleDiv("yearloc")
              }
              //toggleDiv(nextDiv);
              var nextDiv_ = next.split("_")[0].toLowerCase()+"_div";
              showDiv(nextDiv_);
              var desDiv = next.toLowerCase().replace("_","");          
              if(next == "YEAR") { desDiv="year_"}
              var currentSelected="";
              var data_="";
              if((current =="SECTOR_DESC" || current =="GROUP_DESC" || current=="COMMODITY_DESC")) {
                  if(document.getElementById("yearloc").style.display==''){
                      document.getElementById("yearloc").style.display="none";
                  }
              }
              //current select ... 
              current = $.trim(current);
              var current_menu = document.getElementById(current);
              if(current_menu) {
                  if(current_menu.selectedIndex >= 0 ) {       
                      current_menu_text=current_menu.options[current_menu.selectedIndex].text;
                      if(current == "REPORT_NAME") {
                            tableName = current_menu_text;              
                            currentSelected = "";
                        }
              //alert(current)
                        for(var i=0; i<=c_index;i++) {
                          if( MENUS_[i] != "REPORT_NAME") {       
                              select_val = getSelectVal(MENUS_[i]);
                              if(current == "SECTOR_DESC") {
                                  currentSelected += "  " + MENUS_[i] + " in (";                       
                              } else {
                                  currentSelected += " and " + MENUS_[i] + " in (";                       
                              }
                              for(var ii=0 ; ii<select_val.length; ii++) {
                                  if(ii==select_val.length-1) {       
                                      if(MENUS_[i] == 'YEAR') {             
                                          currentSelected += select_val[ii];    
                                      } else {
                                          currentSelected += "'"+select_val[ii]+"'";
                                      }
                                  } else {
                                      if(MENUS_[i] == 'YEAR') {
                                          currentSelected += select_val[ii]+",";    
                                      } else {
                                          currentSelected += "'"+select_val[ii]+"',";
                                      }                            
                                  }
                              }
                              currentSelected += ")";
                          }  
                        }
                        if(current == "SECTOR_DESC" || current == "GROUP_DESC") {
                            tableName=""; 
                            where = currentSelected;
                            data_ = "current="+escape(current)+"&next="+escape(next)+"&where="+escape(where);
                        } else if( current == "COMMODITY_DESC" ) {
                            reportName = currentSelected;
                            data_ = "current="+escape(current)+"&next="+escape(next)+"&reportname="+escape(reportName);
                        } else {
                            data_ = "current="+escape(current)+"&next="+escape(next)+"&tablename="+escape(tableName)+"&currentselect="+escape(currentSelected);
                        }
                  }
              } 
              else {
                  //debug(" Obj does not exist ::" + current+" ****" );
              }
              for(var ii=c_index; ii<MENUS_.length-2 ; ii++) {
                  //debug("....." + MENUS_[ii+1].split("_")[0] + " ii= "+ ii);
                  hideMenu(MENUS_[ii+2]); //ii+2;
              }
        //alert(where);
              updateMenu1CallBack1(desDiv,data_);
        }
        // ...
        function updateMenu1CallBack_(desDiv, data) {
            var idiv=document.getElementById(desDiv);
            if(idiv != null){
                idiv.innerHTML=data;
            }
        }
        // ... 
        function updateMenu1CallBack1(desDiv, data_) {
            jQuery.ajax( {
                  //type: "GET",
                  type: "POST",
                  url: "qslite.php",
                  data: data_,
                  dataType: "html",
                  //cache: false,
                  //async: false,
                  beforeSend: fadein(desDiv),
                  success: function(data,stat,xhr) {
                      //debug(" status:::"+stat);
                      updateMenu1CallBack_(desDiv,data); 
                      //debug("xhr::" + xhr);
                  },
                  error: function(){
                      alert("updateMenuCallback1 ::async error");
                  }
              }
            );
        }
        // ... 
        function restoreCallBack(desDiv, data_,name,value) {
            jQuery.ajax( {
                  //type: "GET",
                  type: "POST",
                  url: "qslite.php",
                  data: data_,
                  dataType: "html",
                  //cache: false,
                  //async: false,
                  //processData: false,
                  beforeSend: fadein(desDiv),
                  success: function(data,stat,xhr) {
                      //debug(" status:::"+stat);
                      if (stat=="success"){
                        updateMenu1CallBack_(desDiv,data);
                        var idiv=document.getElementById(desDiv);
                        if(idiv != null){
                            idiv.style.border="0px #ccc solid";
                            idiv.style.display='none';
                        }
                      } 
                  },
                  error: function(){
                      alert("updateMenuCallback1 ::async error");
                  },
                  complete:function(rt,st){  
                      if(st=="success"){
                        debug("call set selected: "+name +"...."+value);  
                        //$.delay(3000,restoreSetSelected(name,value));
                        window.setTimeout(function() {  
                          restoreSetSelected(name,value);
                          var idiv=document.getElementById(desDiv);
                          if(idiv!=null){idiv.style.display='';}
                          enableGetdata();
                        }, 4000);
                      }
                  }
              } );
        }
        // fade in ...
        function fadein(id){
          //document.getElementById(id).innerHTML="<img src=./images/indicator.gif>";
          var idiv=document.getElementById(id);
          if (idiv != null) {idiv.innerHTML="<img src=./images/loading.gif>";}
        }
        // hideMenu_old ...
        function hideMenu_old(menu) {    
          //debug("menu:::"+menu.split("_")[0].toLowerCase()+"_div"); 
          menu=menu.split("_")[0].toLowerCase()+"_div"; 
          var menu_ob = document.getElementById(menu);
          if(menu_ob) {
              if(menu_ob.style.visibility=='') {
                  menu_ob.style.visibility="hidden";
              } else {
                  menu_ob.style.visibility='';
              }
              if(menu_ob.tagName !="DIV"){
                  div = menu_ob.parentNode; 
                  //debug(" a div : " + div.id);
                  div.innerHTML=menu.toLowerCase().replace("_"," ")+" here ...";
              }
          }
          document.getElementById("getdata").disabled="true";
        }
        // hideMenu ...
        function hideMenu(menu) {   
          //debug("menu:::"+menu.split("_")[0].toLowerCase()+"_div"); 
          menu=menu.split("_")[0].toLowerCase()+"_div"; 
          var menu_ob = document.getElementById(menu);
          if(menu_ob) {
              if(menu_ob.style.display=='') {
                  menu_ob.style.display="none";
              } else {
                  //menu_ob.style.display='';
              }
              if(menu_ob.tagName !="DIV"){
                  div = menu_ob.parentNode; 
                  //debug(" a div : " + div.id);
                  div.innerHTML=menu.toLowerCase().replace("_"," ")+" here ...";
              }
          }
          document.getElementById("getdata").disabled="true";
        }
        // getSelectVal ...
        // Get one or more selected value of <select><option>
        function getSelectVal(nodeName) {
          var nodeOb = document.getElementById(nodeName);
          var nodeVal = new Array();
          var ii=0;
          for(var i = 0; i < nodeOb.options.length; i++) {
              if(nodeOb.options[i].selected) {
                  //nodeVal[ii++] = nodeOb.options[i].value + " ";
                  nodeVal[ii++] = nodeOb.options[i].value;
              }
          }
          return nodeVal;
        }
        // getAllSelects ...
        // check the curent page select menu all selected 
        // then return all the selected values on current page
        function getAllSelects() {
            var result='';
            for(var i =0 ; i<MENUS_.length ; i++) {
              var selectMe = getSelectVal(MENUS[i]);
              if(selectMe) {
                  //console.debug("select: "+MENUS[i] + " = " + selectMe );
                  result += MENUS_[i] + " = " + selectMe +"<br>";
              } else {
                  alert ("Error! "+MENUS_[i]+" must be selected");
                  //console.debug(" Oop!!  must select " + MENUS[i]);
                  return ERR_;
              }
            }
            return result;
        }
        // set select all
        function selectAll(id) {
          /*$(#id).attr('selected',true);*/
          var options=document.getElementById(id).options;
          var lg =options.length;
          for(i=0;i<lg;i++){
              $('#id')[options[i].value].attr('selected','selected');
          }
          debug('select all::'+id+":::::"+lg);
        }
        function selectedIt(id){
        // change the menus order
        //...
            var selects=getSelectVal(id);
            var lg=selects.length;
            var pat=/REGION|INTERNATIONAL/gi;
            for(i=0; i<lg;i++){
                if(selects[i].match(pat)) {// == "REGION : MULTI-STATE"){
                    if(document.getElementById("STATE_NAME")) {
                        var node=document.getElementById("state_div");
                        node.style.display="none";
                    }
                    MENUS_=["SECTOR_DESC","GROUP_DESC","COMMODITY_DESC","REPORT_NAME","YEAR","AGG_LEVEL_DESC","LOCATION_DESC"];
                    enableGetdata();
                }
                else if (lg >=2) {
                    var node=document.getElementById("state_div");
                    if(node != null){node.style.display='';}
                    for(i=0;i<lg;i++){
                        if(selects[i].match(pat)){
                            alert("Unfortunately you can not combine Region or Internatinal with other Geographic Levels");
                        } else {
                            MENUS_ = ["SECTOR_DESC","GROUP_DESC","COMMODITY_DESC","REPORT_NAME","YEAR","AGG_LEVEL_DESC","STATE_NAME","LOCATION_DESC"];
                        }
                    } 
                }
                else {
                    var node=document.getElementById("state_div");
                    if(node !=null){node.style.display='';}
                    //&& selects[i] == "REGION : MULTI-STATE") {
                    //alert("Can not combine region with other");
                    MENUS_ = ["SECTOR_DESC","GROUP_DESC","COMMODITY_DESC","REPORT_NAME","YEAR","AGG_LEVEL_DESC","STATE_NAME","LOCATION_DESC"];           
                }
            }
        }
        function onSubmit(){
        // set url hash
        // go to result page
          var queryStr='';
          for(var i = 0 ; i<MENUS_.length ; i++) {
              var optObj = document.getElementById(MENUS_[i]);
              var optVal = getSelectVal(MENUS_[i]);
              queryStr += MENUS_[i]+'=';
              if(optVal.length > 1 ) {
                  for(var j=0 ; j < optVal.length; j++) {
                    if(j == optVal.length - 1) {
                        queryStr += encodeURIComponent(optVal[j])+'&';
                    } else {
                        //queryStr += encodeURIComponent(optVal[j])+","; //||
                        queryStr += encodeURIComponent(optVal[j])+" , "; //||
                        //queryStr += encodeURIComponent(optVal[j])+"||"; //||
                    }
                  }
              } else {
                    queryStr += encodeURIComponent(optVal[0])+'&';
              }
          } 
          queryStr = queryStr.substring(0,queryStr.length-1);
          debug("query string: "+queryStr);
          var uuid ="";
          jQuery.ajax( {
              type: "POST",
              url: "/qs/uuid/encode/",
              data: queryStr,
              dataType: "html",
              //dataType: "json",
              //cache: false,
              async: false,
              success: function(data,stat,xhr) {
                  uuid=data;
                  uuid=uuid.replace(/"/g,'');
              },
              error: function(){
                  alert("onSubmit ::async error");
              }
          });
          window.location.hash=uuid;
          window.location="./result.php?"+uuid;
          //window.location.hash=queryStr;
          //window.location="./result.php?"+queryStr;
        }
        function enableGetdata() {
            document.getElementById('getdata').disabled=false;
        }
        function Hash(){
        // http://www.mojavelinux.com/articles/javascript_hashes.html   
        // js hash:
        // - remove, get, add    
            /*Hash constructor*/
            this.length = 0;
            this.items = new Array();
            for (var i = 0; i < arguments.length; i += 2) {
                if (typeof(arguments[i + 1]) != 'undefined') {
                        this.items[arguments[i]] = arguments[i + 1];
                        this.length++;
                }
            }
            this.removeItem = function(in_key)
            {
                var tmp_previous;
                if (typeof(this.items[in_key]) != 'undefined') {
                        this.length--;
                        var tmp_previous = this.items[in_key];
                        delete this.items[in_key];
                }
                return tmp_previous;
            }
            this.getItem = function(in_key) {
                return this.items[in_key];
            }
            this.addItem = function(in_key, in_value) {
                var tmp_previous;
                if (typeof(in_value) != 'undefined') {
                    if (typeof(this.items[in_key]) == 'undefined') {
                        this.length++;
                    }
                    else {
                        tmp_previous = this.items[in_key];
                    }
                    this.items[in_key] = in_value;
                } 
                return tmp_previous;
            }
            this.hasItem = function(in_key) {
                return typeof(this.items[in_key]) != 'undefined';
            }
            this.clear = function() {
                for (var i in this.items) {
                        delete this.items[i];
                }
                this.length = 0;
            }
        }
        function toggleDiv(id){
            debug("toggle div::"+id);
            var div=document.getElementById(id);
            if(div.style.display=='') {
                div.style.display='';
            } else if (div.style.display=='none') {
                div.style.display='';
            }
        }
        function showDiv(id) {
            //debug("toggle div::"+id);
            var div=document.getElementById(id);
            if(div.style.display==''){
                div.style.display='';
            } else if (div.style.display=='none') {
                div.style.display='';
            }
        }
        function toggleHelp(helpDiv) {
            var div=document.getElementById(helpDiv);
            if (div.style.display=='block') {
                div.style.display='none';
            } else {
                div.style.display='block';
            }
        }
        function debug(msg) {
          //console.debug("::"+msg);
        }
        //get DOM
        function getDom(id) {
          var aNode = document.getElementById(id);
          debug(" a node: " + aNode);
        }
      
      // var data;
      // var baseurl = window.location.origin;
      // var paramsURL = baseurl + drupalSettings.quickStats.params;
      // var groupsURL = baseurl + drupalSettings.quickStats.groups;
      // var commodityURL = baseurl + drupalSettings.quickStats.commodities;      
      // var sectorName;
      // var groupName;
      // var commodityName;

      // function toTitleCase(str) {
      //   return str.replace(
      //     /\w\S*/g,
      //     function(txt) {
      //       return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
      //     }
      //   );
      // }

      // $.ajax({
      //   cache: false,
      //   method: 'GET',
      //   url: paramsURL,
      //   // contentType: 'application/json',
      //   data: data,
      //   // dataType: 'json',
      //   error: function (e, textStatus, errorThrown) {
      //     console.log('No response');
      //     console.log(textStatus,errorThrown);
      //   },
      //   success: function (data) {
      //     var sect;
      //     sect = data.sector_desc;
      //     //console.log(data.sector_desc);
      //     for (var i = 0; i < sect.length; i++) {
      //       var sectors = sect[i];
      //       $('#sector').append('<option value="' + sectors + '">' + toTitleCase(sectors) + '</option>');
      //     }
      //   }
      // }); 

      // $('#sector').on('change', function(e) {
      //   sectorName = $(this).val();
      //   // sectorName = sectorName.replace(/\W+/g, '-');
      //   // sectorName = sectorName.replace(/\s+/g, '-').toLowerCase();
      //   window.history.replaceState(null, null, '?sector=' + encodeURIComponent(sectorName));

      //   $.ajax({
      //     cache: false,
      //     method: 'GET',
      //     url: groupsURL + '?sector=' + encodeURIComponent(sectorName),
      //     data: data,
      //     error: function (e, textStatus, errorThrown) {
      //       console.log('No response');
      //       console.log(textStatus,errorThrown);
      //     },
      //     beforeSend: function() {
      //       $('#group').empty();
      //       $('#group').append('<option>Select a group</option>');
      //     },
      //     success: function (data) {
      //       var group;
      //       group = data.group_desc;
      //       //console.log(data.group_desc);
      //       for (var i = 0; i < group.length; i++) {
      //         var groups = group[i];
      //         $('#group').append('<option value="' + groups + '">' + toTitleCase(groups) + '</option>');
      //       }
      //     }
      //   }); 

      // });

      // $('#group').on('change', function (e) {
      //   groupName = $(this).val();

      //   const url = new URL(window.location.href);
      //   url.searchParams.set('sector', sectorName);
      //   url.searchParams.set('group', groupName);
      //   window.history.replaceState(null, null, url);
      //   console.log(commodityURL);

      //   $.ajax({
      //     cache: false,
      //     method: 'GET',
      //     url: commodityURL + '?sector=' + encodeURIComponent(sectorName) + '&group=' + encodeURIComponent(groupName),
      //     data: data,
      //     error: function (e, textStatus, errorThrown) {
      //       console.log('No response');
      //       console.log(textStatus,errorThrown);
      //     },
      //     beforeSend: function() {
      //       $('#commodity').empty();
      //       $('#commodity').append('<option>Select a commodity</option>');
      //     },
      //     success: function (data) {
      //       var commodity;
      //       commodity = data.commodity_desc;
      //       console.log(data);
      //       console.log(commodity);
      //       //console.log(sectorName);
      //       //console.log(groupName);            
      //       for (var i = 0; i < commodity.length; i++) {
      //         var commodities = commodity[i];
      //         $('#commodity').append('<option value="' + commodities + '">' + toTitleCase(commodities) + '</option>');
      //       }
          
      //     }
      //   }); 

      // });


    }
  }
}(jQuery, Drupal, drupalSettings));