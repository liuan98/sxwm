(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-db1acf78"],{"0ccb":function(t,e,i){var s=i("50c4"),n=i("1148"),a=i("1d80"),o=Math.ceil,r=function(t){return function(e,i,r){var l,c,u=String(a(e)),d=u.length,h=void 0===r?" ":String(r),m=s(i);return m<=d||""==h?u:(l=m-d,c=n.call(h,o(l/h.length)),c.length>l&&(c=c.slice(0,l)),t?u+c:c+u)}};t.exports={start:r(!1),end:r(!0)}},1276:function(t,e,i){"use strict";var s=i("d784"),n=i("44e7"),a=i("825a"),o=i("1d80"),r=i("4840"),l=i("8aa5"),c=i("50c4"),u=i("14c3"),d=i("9263"),h=i("d039"),m=[].push,f=Math.min,g=4294967295,p=!h((function(){return!RegExp(g,"y")}));s("split",2,(function(t,e,i){var s;return s="c"=="abbc".split(/(b)*/)[1]||4!="test".split(/(?:)/,-1).length||2!="ab".split(/(?:ab)*/).length||4!=".".split(/(.?)(.?)/).length||".".split(/()()/).length>1||"".split(/.?/).length?function(t,i){var s=String(o(this)),a=void 0===i?g:i>>>0;if(0===a)return[];if(void 0===t)return[s];if(!n(t))return e.call(s,t,a);var r,l,c,u=[],h=(t.ignoreCase?"i":"")+(t.multiline?"m":"")+(t.unicode?"u":"")+(t.sticky?"y":""),f=0,p=new RegExp(t.source,h+"g");while(r=d.call(p,s)){if(l=p.lastIndex,l>f&&(u.push(s.slice(f,r.index)),r.length>1&&r.index<s.length&&m.apply(u,r.slice(1)),c=r[0].length,f=l,u.length>=a))break;p.lastIndex===r.index&&p.lastIndex++}return f===s.length?!c&&p.test("")||u.push(""):u.push(s.slice(f)),u.length>a?u.slice(0,a):u}:"0".split(void 0,0).length?function(t,i){return void 0===t&&0===i?[]:e.call(this,t,i)}:e,[function(e,i){var n=o(this),a=void 0==e?void 0:e[t];return void 0!==a?a.call(e,n,i):s.call(String(n),e,i)},function(t,n){var o=i(s,t,this,n,s!==e);if(o.done)return o.value;var d=a(t),h=String(this),m=r(d,RegExp),b=d.unicode,v=(d.ignoreCase?"i":"")+(d.multiline?"m":"")+(d.unicode?"u":"")+(p?"y":"g"),w=new m(p?d:"^(?:"+d.source+")",v),y=void 0===n?g:n>>>0;if(0===y)return[];if(0===h.length)return null===u(w,h)?[h]:[];var k=0,x=0,$=[];while(x<h.length){w.lastIndex=p?x:0;var _,O=u(w,p?h:h.slice(x));if(null===O||(_=f(c(w.lastIndex+(p?0:x)),h.length))===k)x=l(h,x,b);else{if($.push(h.slice(k,x)),$.length===y)return $;for(var j=1;j<=O.length-1;j++)if($.push(O[j]),$.length===y)return $;x=k=_}}return $.push(h.slice(k)),$}]}),!p)},1887:function(t,e,i){},4082:function(t,e,i){"use strict";var s=i("dea6"),n=i.n(s);n.a},"40b1":function(t,e,i){"use strict";i.r(e);var s=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("div",{directives:[{name:"show",rawName:"v-show",value:!t.addshow,expression:"!addshow"}],staticClass:"content"},[i("div",[i("span",[t._v(t._s(t.$t("documentation.Tradename")))]),i("el-input",{attrs:{placeholder:t.$t("documentation.Entername")},model:{value:t.goodsname,callback:function(e){t.goodsname=e},expression:"goodsname"}}),i("span",[t._v(t._s(t.$t("documentation.Addtime")))]),i("el-date-picker",{attrs:{type:"date",placeholder:t.$t("documentation.Selectdate")},model:{value:t.datevalue,callback:function(e){t.datevalue=e},expression:"datevalue"}}),i("el-button",{attrs:{type:"primary",icon:"el-icon-search"},on:{click:t.searchdata}},[t._v(" "+t._s(t.$t("documentation.search"))+" ")])],1),i("div",{staticClass:"divaddbtn"},[i("el-button",{attrs:{type:"warning"},on:{click:function(e){return t.addgoodsbtn("form")}}},[t._v(" +"+t._s(t.$t("documentation.Additem"))+" ")]),i("el-button",{attrs:{type:"danger",icon:"el-icon-delete"},on:{click:t.batchdel}},[t._v(" "+t._s(t.$t("documentation.Batchdeletion"))+" ")])],1),i("div",{staticClass:"goodswrap"},[i("div",{staticClass:"goodsleft"},[i("div",{staticClass:"goodscalss"},[t._v(" "+t._s(t.$t("documentation.commoditytypes"))+" ")]),i("el-tree",{attrs:{props:t.props,load:t.loadNode,lazy:""},on:{"node-click":t.clickNode}})],1),i("el-table",{ref:"multipleTable",staticStyle:{width:"100%"},attrs:{data:t.tableData,border:"","tooltip-effect":"dark","default-sort":{prop:"add_time",order:"descending"}},on:{"selection-change":t.handleSelectionChange}},[i("el-table-column",{attrs:{type:"selection",width:"55"}}),i("el-table-column",{attrs:{label:t.$t("documentation.Commodity"),prop:"number"}}),i("el-table-column",{attrs:{prop:"name",label:t.$t("documentation.Tradename")}}),i("el-table-column",{attrs:{prop:"price",label:t.$t("documentation.Price"),"show-overflow-tooltip":""}}),i("el-table-column",{attrs:{prop:"big_id",label:t.$t("documentation.classify")}}),t._l(t.categorylist,(function(t,e){return[i("el-table-column",{key:e,attrs:{prop:"biaoti"+e,label:t.name}})]})),i("el-table-column",{attrs:{prop:"add_time",label:t.$t("documentation.Addtime"),width:"150"}}),i("el-table-column",{attrs:{prop:"attribute",label:t.$t("documentation.attribute"),width:"100",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("span",[i("el-button",{class:2==e.row.attribute?"":"gray",attrs:{type:"text"},on:{click:function(i){return t.changeStatus(2,e.row)}}},[t._v(t._s(t.$t("documentation.recommend")))]),i("el-button",{class:1==e.row.attribute?"":"gray",attrs:{type:"text"},on:{click:function(i){return t.changeStatus(1,e.row)}}},[t._v(t._s(t.$t("documentation.CostEffective")))])],1)]}}])}),i("el-table-column",{attrs:{prop:"status",label:t.$t("documentation.state")}}),i("el-table-column",{attrs:{width:"250px",label:t.$t("documentation.operation")},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-button",{directives:[{name:"show",rawName:"v-show",value:e.row.status==t.$t("tips.sale"),expression:"scope.row.status==$t('tips.sale')?true:false"}],attrs:{size:"mini",type:"warning"},nativeOn:{click:function(i){return i.stopPropagation(),t.changeS(e.row)}}},[t._v(" "+t._s(t.$t("documentation.Offshelf"))+" ")]),i("el-button",{directives:[{name:"show",rawName:"v-show",value:e.row.status==t.$t("tips.notAvailable"),expression:"scope.row.status==$t('tips.notAvailable')?true:false"}],attrs:{size:"mini",type:"success"},nativeOn:{click:function(i){return t.changeS2(e.row)}}},[t._v(" "+t._s(t.$t("documentation.Ontheshelf"))+" ")]),i("el-button",{staticStyle:{margin:"0 5px"},attrs:{size:"mini",type:"primary",icon:"el-icon-edit"},on:{click:function(i){return t.handleEdit(e.$index,e.row)}}}),i("el-popover",{attrs:{placement:"top",width:"160"},model:{value:t.boollist[e.$index],callback:function(i){t.$set(t.boollist,e.$index,i)},expression:"boollist[scope.$index]"}},[i("p",{attrs:{icon:"el-icon-warning"}},[t._v(" "+t._s(t.$t("documentation.isdelete"))+" ")]),i("div",{staticStyle:{"text-align":"right",margin:"0"}},[i("el-button",{attrs:{size:"mini",type:"text"},on:{click:function(i){return i.stopPropagation(),t.offdel(e.$index)}}},[t._v(" "+t._s(t.$t("documentation.cancel"))+" ")]),i("el-button",{attrs:{type:"primary",size:"mini"},on:{click:function(i){return i.stopPropagation(),t.submitdel(e.$index,e.row.id)}}},[t._v(" "+t._s(t.$t("documentation.determine"))+" ")])],1),i("el-button",{attrs:{slot:"reference",size:"mini",type:"danger",icon:"el-icon-delete"},on:{click:function(i){return t.handleDelete(e.$index,e.row.id)}},slot:"reference"})],1)]}}])})],2)],1),i("div",{directives:[{name:"show",rawName:"v-show",value:t.morewrap,expression:"morewrap"}],staticClass:"morediv"},[i("div",{directives:[{name:"show",rawName:"v-show",value:t.moreshow,expression:"moreshow"}],on:{click:t.morebtn}},[t._v(" "+t._s(t.$t("documentation.moredata"))+" ")]),i("div",{directives:[{name:"show",rawName:"v-show",value:!t.moreshow,expression:"!moreshow"}]},[t._v(" "+t._s(t.$t("documentation.moredatanone"))+" ")])])]),i("div",{directives:[{name:"show",rawName:"v-show",value:t.addshow,expression:"addshow"}],staticClass:"content2"},[i("div",{staticClass:"addtitle"},[t._v(" "+t._s(t.$t("documentation.Additem"))+" ")]),i("el-form",{ref:"form",attrs:{"label-position":t.labelPosition,"label-width":"100px",model:t.form}},[i("el-form-item",{attrs:{label:t.$t("documentation.Tradename")}},[i("el-input",{model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}}),i("el-button",{directives:[{name:"show",rawName:"v-show",value:!1,expression:"false"}],on:{click:function(e){return t.clear("form")}}})],1),i("el-form-item",{attrs:{label:t.$t("documentation.Subtitle")}},[i("el-input",{model:{value:t.form.title,callback:function(e){t.$set(t.form,"title",e)},expression:"form.title"}})],1),i("el-form-item",{attrs:{label:t.$t("documentation.Selcategory")}},[i("el-select",{on:{change:t.selectOne},model:{value:t.form.region,callback:function(e){t.$set(t.form,"region",e)},expression:"form.region"}},t._l(t.bigclassarr,(function(t,e){return i("el-option",{key:e,attrs:{label:t.name,value:t.id}})})),1)],1),i("el-form-item",{attrs:{label:t.$t("documentation.Selsmallcat")}},[i("el-select",{model:{value:t.form.region2,callback:function(e){t.$set(t.form,"region2",e)},expression:"form.region2"}},t._l(t.smallclassarr2,(function(t,e){return i("el-option",{key:e,attrs:{label:t.name_little,value:t.id}})})),1)],1),i("div",{staticClass:"divflex"},t._l(t.storehouse,(function(e,s){return i("el-form-item",{key:s,attrs:{label:e.name}},[i("el-input",{attrs:{type:"number"},on:{change:t.inputone},model:{value:t.storevue[s],callback:function(e){t.$set(t.storevue,s,e)},expression:"storevue[storeindex]"}})],1)})),1),i("div",{staticClass:"divflex"},[i("el-form-item",{attrs:{label:t.$t("documentation.Commodity")}},[i("el-input",{model:{value:t.form.number,callback:function(e){t.$set(t.form,"number",e)},expression:"form.number"}})],1),i("el-form-item",{attrs:{label:t.$t("documentation.Disprice")}},[i("el-input",{model:{value:t.form.showprice,callback:function(e){t.$set(t.form,"showprice",e)},expression:"form.showprice"}})],1),i("el-form-item",{attrs:{label:t.$t("documentation.marketprice")}},[i("el-input",{model:{value:t.form.markprice,callback:function(e){t.$set(t.form,"markprice",e)},expression:"form.markprice"}})],1),i("el-form-item",{attrs:{label:t.$t("documentation.Specifications")}},[i("el-input",{attrs:{placeholder:t.$t("documentation.Pleasinput")},model:{value:t.form.standard,callback:function(e){t.$set(t.form,"standard",e)},expression:"form.standard"}})],1)],1),i("el-form-item",{attrs:{label:t.$t("documentation.limitBuy")}},[i("el-input",{staticStyle:{width:"200px"},attrs:{type:"number",placeholder:t.$t("documentation.limitBuy")},model:{value:t.form.restrict,callback:function(e){t.$set(t.form,"restrict",t._n(e))},expression:"form.restrict"}})],1),i("el-form-item",{attrs:{label:t.$t("documentation.rightlabel")}},[t._l(t.form.rightlabel,(function(e){return i("el-tag",{key:e,attrs:{closable:"","disable-transitions":!1},on:{close:function(i){return t.handleClose(e)}}},[t._v(" "+t._s(e)+" ")])})),t.inputVisible?i("el-input",{ref:"saveTagInput",staticClass:"input-new-tag",attrs:{size:"small"},on:{blur:t.handleInputConfirm},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.handleInputConfirm(e)}},model:{value:t.inputValue,callback:function(e){t.inputValue=e},expression:"inputValue"}}):i("el-button",{staticClass:"button-new-tag",attrs:{size:"small"},on:{click:t.showInput}},[t._v("+ New Tag")])],2),i("el-form-item",{attrs:{label:t.$t("documentation.Uploadpictures")}},[i("el-upload",{attrs:{action:"/upload","list-type":"picture-card","on-preview":t.handlePictureCardPreview,"on-remove":t.handleRemove,"on-success":t.picsuccess,"file-list":t.imgList}},[i("i",{staticClass:"el-icon-plus"})]),i("el-dialog",{attrs:{visible:t.dialogVisible},on:{"update:visible":function(e){t.dialogVisible=e}}},[i("img",{attrs:{width:"100%",src:t.dialogImageUrl,alt:""}})])],1),i("el-form-item",{attrs:{label:t.$t("documentation.Details")}},[i("Tinymce",{model:{value:t.tinytext,callback:function(e){t.tinytext=e},expression:"tinytext"}})],1),i("el-form-item",{attrs:{label:t.$t("documentation.onshelf")}},[i("el-switch",{attrs:{"active-color":"#13ce66","inactive-color":"#ff4949"},model:{value:t.issale,callback:function(e){t.issale=e},expression:"issale"}})],1),i("el-form-item",[i("el-button",{attrs:{type:"primary"},on:{click:t.onSubmit}},[t._v(" "+t._s(t.$t("documentation.preservation"))+" ")]),i("el-button",{on:{click:t.closeadd}},[t._v(" "+t._s(t.$t("documentation.cancel"))+" ")])],1)],1)],1)])},n=[],a=(i("99af"),i("4160"),i("c975"),i("a15b"),i("d81d"),i("fb6a"),i("a434"),i("accc"),i("0d03"),i("b0c0"),i("d3b7"),i("ac1f"),i("25f0"),i("6062"),i("3ca3"),i("1276"),i("159b"),i("ddb0"),i("2b3d"),i("284c")),o=i("bf2d"),r=(i("96cf"),i("89ba")),l=i("8256"),c=i("b8f0"),u={name:"Productinfor",components:{Tinymce:l["b"]},data:function(){return{props:{label:"name",children:"zones",isLeaf:"leaf"},categorylist:[],categorylisttwo:{},goodsname:"",tinytext:"",storevue:[],addshow:!1,datevalue:"",issale:!0,dialogImageUrl:"",dialogVisible:!1,labelPosition:"right",form:{name:"",region:"",region2:"",type:"",title:"",rightlabel:[],restrict:""},delvisible:!1,tableData:[],multipleSelection:[],defaultProps:{children:"children",label:"label"},dynamicTags:["标签一","标签二","标签三"],inputVisible:!1,inputValue:"",issearch:"2",morewrap:!1,moreshow:!0,standard:"",number:"",showprice:"",markprice:"",getdelid:"",boollist:[],getidarr:[],storehouse:[],smallclassarr:[],smallclassarr2:[],bigclassarr:[],storevuearr:[],bigclassid:"",titleid:"",addclassid:"",addsmclassid:"",getimg:[],editbigid:"",editid:"",addedit:"",edittitleid:"",imgList:[]}},created:function(){this.goodsList(),this.warehouseList()},methods:{changeStatus:function(t,e){var i=this;Object(c["F"])({id:e.id,attribute:t}).then((function(t){i.$message.success(i.$t("tips.success")),i.goodsList()}))},handleClose:function(t){this.form.rightlabel.splice(this.form.rightlabel.indexOf(t),1)},picsuccess:function(t,e,i){console.log(t),console.log(this.getimg),this.dialogImageUrl=URL.createObjectURL(e.raw),this.getimg.push(t.data.path),console.log(e,"file"),console.log(t,"response")},inputone:function(t){console.log(t,"val"),console.log(this.storevue,"storevue")},warehouseList:function(){var t=Object(r["a"])(regeneratorRuntime.mark((function t(){var e=this;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,Object(c["Cb"])({}).then((function(t){console.log(t,"res"),e.storehouse=t.data})).catch((function(t){}));case 2:case"end":return t.stop()}}),t)})));function e(){return t.apply(this,arguments)}return e}(),selectOne:function(t){console.log(t,"val"),this.addclassid=t,this.getsubcla()},getsubcla:function(){var t=this,e=this.addclassid;Object(c["rb"])({id:e}).then((function(e){t.smallclassarr2=e.data,console.log(t.smallclassarr2,"smallclassarr2")})).catch((function(t){}))},batchdel:function(){this.goodsDelete()},clickNode:function(t){console.log(t),t.data&&(this.bigclassid=t.id),void 0==t.name_little?this.titleid="":(this.titleid=t.id,this.issearch="1",this.goodsList()),console.log(this.titleid,"this.titleid"),console.log(t,"nodeclick")},loadNode:function(){var t=Object(r["a"])(regeneratorRuntime.mark((function t(e,i){var s,n=this;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return e.data&&(this.bigclassid=e.data.id),t.next=3,this.getlargeList();case 3:if(s=t.sent,console.log(s,"classbig"),0!==e.level){t.next=7;break}return t.abrupt("return",i(s));case 7:if(!(e.level>1)){t.next=9;break}return t.abrupt("return",i([]));case 9:setTimeout((function(){var t=n.subclassList();t.then((function(t){console.log(t,"result"),i(t)}))}),500);case 10:case"end":return t.stop()}}),t,this)})));function e(e,i){return t.apply(this,arguments)}return e}(),getlargeList:function(){var t=Object(r["a"])(regeneratorRuntime.mark((function t(){var e=this;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,Object(c["V"])({}).then((function(t){e.bigclassarr=t.data,e.bigclassarr.map((function(t,e){return t.isshow=!1,t})),console.log(t,"res"),console.log(e.bigclassarr," this.bigclassarr大分类")})).catch((function(t){}));case 2:return t.abrupt("return",this.bigclassarr);case 3:case"end":return t.stop()}}),t,this)})));function e(){return t.apply(this,arguments)}return e}(),subclassList:function(){var t=Object(r["a"])(regeneratorRuntime.mark((function t(){var e,i=this;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return e=this.bigclassid,t.next=3,Object(c["rb"])({id:e}).then((function(t){for(var e in i.smallclassarr=t.data,i.smallclassarr)i.smallclassarr[e].name=i.smallclassarr[e].name_little;i.smallclassarr.map((function(t,e){return t.isshow=!1,t})),console.log(i.smallclassarr,"smallclassarr")})).catch((function(t){}));case 3:return t.abrupt("return",this.smallclassarr);case 4:case"end":return t.stop()}}),t,this)})));function e(){return t.apply(this,arguments)}return e}(),goodsDelete:function(t){var e=this;console.log(this.getidarr);try{for(var i in this.getidarr=[],console.log(this.multipleSelection),this.multipleSelection)this.getidarr.push(this.multipleSelection[i].id);0!=this.getidarr.length&&(this.getidarr=this.getidarr.join(","));var s=this.getidarr;t&&(s=t+""),console.log(this.getidarr),Object(c["J"])({id:s}).then((function(t){e.goodsList(),e.$message.success(e.$t("tips.success"))})).catch((function(t){e.$message.error(e.$t("tips.fail"))}))}catch(n){console.log(n,"error")}},goodsAdd:function(){var t=this;for(var e in this.storevuearr=[],this.storehouse){var i={};i.warehouse_id=this.storehouse[e].id+"",i.num=this.storevue[e],void 0==this.storevue[e]&&(i.num=0),this.storevuearr.push(i)}console.log(this.storevuearr,"this.storevuearr");var s=this.form.region,n=this.form.region2,a=this.form.name,r=this.form.title,l=this.form.number,u=this.form.showprice,d=this.form.markprice,h=this.form.standard,m="";try{m=this.form.rightlabel.join(",")}catch(w){m=""}var f="",g=this.form.restrict;console.log(Object(o["a"])(this.getimg)),f="string"==typeof this.getimg?this.getimg:this.getimg.join(",");var p="",b=this.tinytext,v=JSON.stringify(this.storevuearr);p=1==this.issale?1:2,Object(c["I"])({big_id:s,little_id:n,name:a,title:r,number:l,money:u,price:d,standard:h,label:m,img:f,status:p,text:b,repertory:v,restrict:g}).then((function(e){t.goodsList(),t.$message.success(t.$t("tips.success")),t.form={},t.imgList=[],t.getimg=[]})).catch((function(e){t.$message.error(t.$t("tips.fail")),t.form={},t.imgList=[],t.getimg=[]}))},goodsUpdate:function(){var t=this,e=this.editid;for(var i in this.storevuearr=[],this.storehouse){var s={};s.warehouse_id=this.storehouse[i].warehouse_id+"",s.id=this.storehouse[i].id.toString(),s.num=this.storevue[i],void 0==this.storevue[i]&&(s.num=0),this.storevuearr.push(s)}console.log(this.storevuearr,"this.storevuearr");var n=this.editbigid,a=this.edittitleid,o=this.form.name,r=this.form.title,l=this.form.number,u=this.form.showprice,d=this.form.markprice,h=this.form.standard;f="string"==typeof this.getimg?this.getimg:this.getimg.join(",");var m="";try{m=this.form.rightlabel.join(",")}catch(w){m=""}var f="",g=this.form.restrict;f="string"==typeof this.getimg?this.getimg:this.getimg.join(",");var p="",b=this.tinytext,v=JSON.stringify(this.storevuearr);p=1==this.issale?1:2,Object(c["L"])({id:e,big_id:n,little_id:a,name:o,title:r,number:l,money:u,price:d,standard:h,label:m,img:f,status:p,text:b,repertory:v,restrict:g}).then((function(e){t.goodsList(),t.$message.success(t.$t("tips.success")),t.form={},t.imgList=[],t.getimg=[]})).catch((function(e){t.$message.error(t.$t("tips.fail"))}))},changeS:function(t){console.log(t),this.statusid=t.id,this.statusnum=2,this.memberStatus(t.id,2)},changeS2:function(t){console.log(t),this.statusid=t.id,this.statusnum=1,this.memberStatus(t.id,1)},memberStatus:function(t,e){var i=this;console.log(t),Object(c["H"])({id:t,status:e}).then((function(t){i.goodsList()}))},morebtn:function(){this.issearch="2",this.goodsList("more")},searchdata:function(){this.issearch="1",this.goodsList()},formatTime:function(t){var e=new Date(1e3*t);return new Date(Date.UTC(e.getFullYear(),e.getMonth(),e.getDate())).toISOString().slice(0,10)},goodsList:function(t){var e=this,i="",s=this.goodsname,n="",o=this.titleid;if(0!=this.tableData.length&&t){var r=this.tableData[this.tableData.length-1];n=r.id}"1"==this.issearch&&(this.tableData=[],n=""),i=this.datevalue?this.datevalue.getTime()/1e3:"",console.log(this.datevalue),Object(c["K"])({time:i,id:n,name:s,little_id:o}).then((function(i){console.log(i,"res"),i.data||(e.tableData=[]),0!=i.data.length&&0!=e.tableData.length||(e.morewrap=!1),0==i.data.length&&(e.moreshow=!1);var s=i.data;if(0!=s.length){var n=[];s.forEach((function(t,e){for(var i=0;i<t.warehouse.length;i++)n.push(t.warehouse[i].name)}));var o=[];n=Object(a["a"])(new Set(n)),n=n.map((function(t,e){var i={};return i.name=t,i.id="biaoti"+e,o[t]=i,i})),e.categorylist=n,e.categorylisttwo=o,s=s.map((function(t,e){for(var i=0;i<t.warehouse.length;i++){var s=o[t.warehouse[i].name].id;console.info("indexsss",s),t[s]=t.warehouse[i].num}return t}))}try{for(var r in e.tableData=t?e.tableData.concat(i.data):i.data,e.tableData)1==e.tableData[r].status&&(e.tableData[r].status=e.$t("tips.sale")),2==e.tableData[r].status&&(e.tableData[r].status=e.$t("tips.notAvailable")),e.tableData[r].add_time=e.formatTime(e.tableData[r].add_time),e.boollist[r]=!1}catch(l){console.log(l)}0!=e.tableData.length&&(e.morewrap=!0,0!=i.data.length&&(e.moreshow=!0))})).catch((function(t){}))},closeadd:function(){this.addshow=!1},clear:function(t){console.log(t,"formName")},addgoodsbtn:function(){var t=Object(r["a"])(regeneratorRuntime.mark((function t(e){return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return this.$refs[e].resetFields(),this.addedit="添加商品",this.tinytext="",this.storevue={},t.next=6,this.warehouseList();case 6:this.addshow=!0;case 7:case"end":return t.stop()}}),t,this)})));function e(e){return t.apply(this,arguments)}return e}(),onSubmit:function(){"添加商品"==this.addedit?this.goodsAdd():this.goodsUpdate(),this.addshow=!1,console.log("submit!")},handleRemove:function(t,e){for(var i in console.log(t,e),this.getimg)t.url?t.url==this.getimg[i]&&this.getimg.splice(i,1):t.response.data.path==this.getimg[i]&&this.getimg.splice(i,1)},handlePictureCardPreview:function(t){this.dialogImageUrl=t.url,this.dialogVisible=!0},showInput:function(){var t=this;console.log(this.form.rightlabel.length),5!=this.form.rightlabel.length?(this.inputVisible=!0,this.$nextTick((function(e){t.$refs.saveTagInput.$refs.input.focus()}))):this.$message.error(this.$t("documentation.maxLength"))},handleInputConfirm:function(){var t=this.inputValue;t&&this.form.rightlabel.push(t),this.inputVisible=!1,this.inputValue=""},handleEdit:function(t,e){var i=this;console.log(e),this.addedit="编辑商品",this.editid=e.id,this.edittitleid=e.little_id,this.editbigid=e.big_id,this.addshow=!0,this.$set(this.form,"name",e.name),this.$set(this.form,"title",e.title),this.$set(this.form,"number",e.number),this.$set(this.form,"showprice",e.money),this.$set(this.form,"markprice",e.price),this.$set(this.form,"standard",e.standard),this.$set(this.form,"rightlabel",e.label.split(",")),this.$set(this,"tinytext",e.text),this.$set(this.form,"region",e.big_id),this.$set(this.form,"region2",e.id),this.dialogImageUrl=e.img;try{this.getimg=e.img.split(",")}catch(l){}this.$set(this,"dialogImageUrl",e.img);try{this.getimg=e.img.split(",")}catch(l){}this.$set(this,"storehouse",e.warehouse);try{var s=e.img.split(",");this.imgList=[],s.forEach((function(t){i.imgList.push({url:t})}))}catch(c){}console.log(this.storevue,"this.storevue编辑"),console.log(e.status);try{for(var n in this.storevuearr=[],this.storehouse){var a={};a.warehouse_id=this.storehouse[n].id,a.name=this.storehouse[n].name,a.num=this.storevue[n],void 0==this.storevue[n]&&(a.num=0),this.storevuearr.push(a)}for(var o in console.log(this.storevuearr,"this.storevuearr"),this.storevuearr)for(var r in e.warehouse)this.storevuearr[o].name==e.warehouse[r].name&&(this.storevue[o]=e.warehouse[o].num);console.log(this.storevue,"this.storevue编辑"),console.log(e.status),1==e.status||"上架"==e.status?(this.$set(this,"issale",!0),console.log(this.issale)):this.$set(this,"issale",!1)}catch(u){console.log(u,"error")}},offdel:function(t){console.log(this.boollist),this.$set(this.boollist,t,!1)},submitdel:function(t,e){this.goodsDelete(e),this.boollist[t]=!1},handleDelete:function(t,e){this.getidarr=e,this.boollist[t]=!0,console.log(this.boollist[t],"bool")},handleSelectionChange:function(t){this.multipleSelection=t},handleNodeClick:function(t){console.log(t)}}},d=u,h=(i("9efd"),i("2877")),m=Object(h["a"])(d,s,n,!1,null,"b07a96fc",null);e["default"]=m.exports},6062:function(t,e,i){"use strict";var s=i("6d61"),n=i("6566");t.exports=s("Set",(function(t){return function(){return t(this,arguments.length?arguments[0]:void 0)}}),n)},"64e5":function(t,e,i){"use strict";var s=i("d039"),n=i("0ccb").start,a=Math.abs,o=Date.prototype,r=o.getTime,l=o.toISOString;t.exports=s((function(){return"0385-07-25T07:06:39.999Z"!=l.call(new Date(-5e13-1))}))||!s((function(){l.call(new Date(NaN))}))?function(){if(!isFinite(r.call(this)))throw RangeError("Invalid time value");var t=this,e=t.getUTCFullYear(),i=t.getUTCMilliseconds(),s=e<0?"-":e>9999?"+":"";return s+n(a(e),s?6:4,0)+"-"+n(t.getUTCMonth()+1,2,0)+"-"+n(t.getUTCDate(),2,0)+"T"+n(t.getUTCHours(),2,0)+":"+n(t.getUTCMinutes(),2,0)+":"+n(t.getUTCSeconds(),2,0)+"."+n(i,3,0)+"Z"}:l},6566:function(t,e,i){"use strict";var s=i("9bf2").f,n=i("7c73"),a=i("e2cc"),o=i("f8c2"),r=i("19aa"),l=i("2266"),c=i("7dd0"),u=i("2626"),d=i("83ab"),h=i("f183").fastKey,m=i("69f3"),f=m.set,g=m.getterFor;t.exports={getConstructor:function(t,e,i,c){var u=t((function(t,s){r(t,u,e),f(t,{type:e,index:n(null),first:void 0,last:void 0,size:0}),d||(t.size=0),void 0!=s&&l(s,t[c],t,i)})),m=g(e),p=function(t,e,i){var s,n,a=m(t),o=b(t,e);return o?o.value=i:(a.last=o={index:n=h(e,!0),key:e,value:i,previous:s=a.last,next:void 0,removed:!1},a.first||(a.first=o),s&&(s.next=o),d?a.size++:t.size++,"F"!==n&&(a.index[n]=o)),t},b=function(t,e){var i,s=m(t),n=h(e);if("F"!==n)return s.index[n];for(i=s.first;i;i=i.next)if(i.key==e)return i};return a(u.prototype,{clear:function(){var t=this,e=m(t),i=e.index,s=e.first;while(s)s.removed=!0,s.previous&&(s.previous=s.previous.next=void 0),delete i[s.index],s=s.next;e.first=e.last=void 0,d?e.size=0:t.size=0},delete:function(t){var e=this,i=m(e),s=b(e,t);if(s){var n=s.next,a=s.previous;delete i.index[s.index],s.removed=!0,a&&(a.next=n),n&&(n.previous=a),i.first==s&&(i.first=n),i.last==s&&(i.last=a),d?i.size--:e.size--}return!!s},forEach:function(t){var e,i=m(this),s=o(t,arguments.length>1?arguments[1]:void 0,3);while(e=e?e.next:i.first){s(e.value,e.key,this);while(e&&e.removed)e=e.previous}},has:function(t){return!!b(this,t)}}),a(u.prototype,i?{get:function(t){var e=b(this,t);return e&&e.value},set:function(t,e){return p(this,0===t?0:t,e)}}:{add:function(t){return p(this,t=0===t?0:t,t)}}),d&&s(u.prototype,"size",{get:function(){return m(this).size}}),u},setStrong:function(t,e,i){var s=e+" Iterator",n=g(e),a=g(s);c(t,e,(function(t,e){f(this,{type:s,target:t,state:n(t),kind:e,last:void 0})}),(function(){var t=a(this),e=t.kind,i=t.last;while(i&&i.removed)i=i.previous;return t.target&&(t.last=i=i?i.next:t.state.first)?"keys"==e?{value:i.key,done:!1}:"values"==e?{value:i.value,done:!1}:{value:[i.key,i.value],done:!1}:(t.target=void 0,{value:void 0,done:!0})}),i?"entries":"values",!i,!0),u(e)}}},"6d61":function(t,e,i){"use strict";var s=i("23e7"),n=i("da84"),a=i("94ca"),o=i("6eeb"),r=i("f183"),l=i("2266"),c=i("19aa"),u=i("861d"),d=i("d039"),h=i("1c7e"),m=i("d44e"),f=i("7156");t.exports=function(t,e,i,g,p){var b=n[t],v=b&&b.prototype,w=b,y=g?"set":"add",k={},x=function(t){var e=v[t];o(v,t,"add"==t?function(t){return e.call(this,0===t?0:t),this}:"delete"==t?function(t){return!(p&&!u(t))&&e.call(this,0===t?0:t)}:"get"==t?function(t){return p&&!u(t)?void 0:e.call(this,0===t?0:t)}:"has"==t?function(t){return!(p&&!u(t))&&e.call(this,0===t?0:t)}:function(t,i){return e.call(this,0===t?0:t,i),this})};if(a(t,"function"!=typeof b||!(p||v.forEach&&!d((function(){(new b).entries().next()})))))w=i.getConstructor(e,t,g,y),r.REQUIRED=!0;else if(a(t,!0)){var $=new w,_=$[y](p?{}:-0,1)!=$,O=d((function(){$.has(1)})),j=h((function(t){new b(t)})),C=!p&&d((function(){var t=new b,e=5;while(e--)t[y](e,e);return!t.has(-0)}));j||(w=e((function(e,i){c(e,w,t);var s=f(new b,e,w);return void 0!=i&&l(i,s[y],s,g),s})),w.prototype=v,v.constructor=w),(O||C)&&(x("delete"),x("has"),g&&x("get")),(C||_)&&x(y),p&&v.clear&&delete v.clear}return k[t]=w,s({global:!0,forced:w!=b},k),m(w,t),p||i.setStrong(w,t,g),w}},8256:function(t,e,i){"use strict";var s=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"tinymce-container",class:{fullscreen:t.fullscreen},style:{width:t.containerWidth}},[i("tinymce-editor",{attrs:{id:t.id,init:t.initOptions},model:{value:t.tinymceContent,callback:function(e){t.tinymceContent=e},expression:"tinymceContent"}}),i("div",{staticClass:"editor-custom-btn-container"},[i("editor-image-upload",{staticClass:"editor-upload-btn",attrs:{color:t.uploadButtonColor},on:{successCBK:t.imageSuccessCBK}})],1)],1)},n=[],a=(i("99af"),i("4160"),i("0d03"),i("b680"),i("d3b7"),i("25f0"),i("159b"),i("9f12")),o=i("53fe"),r=i("8b83"),l=i("c65a"),c=i("c03e"),u=i("9ab4"),d=(i("e562"),i("0d68"),i("ecb9"),i("0902"),i("d2dc"),i("2fec"),i("ffbe"),i("64d8"),i("07d7f"),i("855b"),i("69e4"),i("3154"),i("2b07"),i("4ea8"),i("8863"),i("4bd0"),i("4237"),i("84ec8"),i("3aea"),i("eda9"),i("cfb0"),i("ebac"),i("bc54"),i("0a9d"),i("840a"),i("6957"),i("62e5"),i("dcb7"),i("55a0"),i("07d1"),i("0335"),i("78e4"),i("0efa"),i("365e"),i("9434"),i("ca72")),h=i("60a3"),m=i("ac1a"),f=i("7383"),g=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"upload-container"},[i("el-button",{style:{background:t.color,borderColor:t.color},attrs:{icon:"el-icon-upload",size:"mini",type:"primary"},on:{click:function(e){t.dialogVisible=!0}}},[t._v(" upload ")]),i("el-dialog",{attrs:{visible:t.dialogVisible},on:{"update:visible":function(e){t.dialogVisible=e}}},[i("el-upload",{staticClass:"editor-slide-upload",attrs:{multiple:!0,"file-list":t.defaultFileList,"show-file-list":!0,"on-remove":t.handleRemove,"on-success":t.handleSuccess,"before-upload":t.beforeUpload,action:"/upload","list-type":"picture-card"}},[i("el-button",{attrs:{size:"small",type:"primary"}},[t._v(" Click upload ")])],1),i("el-button",{on:{click:function(e){t.dialogVisible=!1}}},[t._v(" Cancel ")]),i("el-button",{attrs:{type:"primary"},on:{click:t.handleSubmit}},[t._v(" Confirm ")])],1)],1)},p=[],b=(i("a623"),i("d81d"),i("b64b"),i("3ca3"),i("ddb0"),i("2b3d"),function(t){function e(){var t;return Object(a["a"])(this,e),t=Object(r["a"])(this,Object(l["a"])(e).apply(this,arguments)),t.dialogVisible=!1,t.listObj={},t.defaultFileList=[],t}return Object(c["a"])(e,t),Object(o["a"])(e,[{key:"checkAllSuccess",value:function(){var t=this;return Object.keys(this.listObj).every((function(e){return t.listObj[e].hasSuccess}))}},{key:"handleSubmit",value:function(){var t=this,e=Object.keys(this.listObj).map((function(e){return t.listObj[e]}));this.checkAllSuccess()?(this.$emit("successCBK",e),this.listObj={},this.defaultFileList=[],this.dialogVisible=!1):this.$message("Please wait for all images to be uploaded successfully. If there is a network problem, please refresh the page and upload again!")}},{key:"handleSuccess",value:function(t,e){var i=e.uid;console.log(t);for(var s=Object.keys(this.listObj),n=0,a=s.length;n<a;n++)if(this.listObj[s[n]].uid===i)return this.listObj[s[n]].url=t.data.path,this.listObj[s[n]].hasSuccess=!0,void console.log(this.listObj)}},{key:"handleRemove",value:function(t){for(var e=t.uid,i=Object.keys(this.listObj),s=0,n=i.length;s<n;s++)if(this.listObj[i[s]].uid===e)return void delete this.listObj[i[s]]}},{key:"beforeUpload",value:function(t){var e=this,i=t.uid,s=new Image;s.src=window.URL.createObjectURL(t),s.onload=function(){e.listObj[i]={hasSuccess:!1,uid:t.uid,url:"",width:s.width,height:s.height}}}}]),e}(h["c"]));Object(u["a"])([Object(h["b"])({required:!0})],b.prototype,"color",void 0),b=Object(u["a"])([Object(h["a"])({name:"EditorImageUpload"})],b);var v=b,w=v,y=(i("8502"),i("4082"),i("2877")),k=Object(y["a"])(w,g,p,!1,null,"7ac3d61e",null),x=k.exports,$=["advlist anchor autolink autosave code codesample directionality emoticons fullscreen hr image imagetools insertdatetime link lists media nonbreaking noneditable pagebreak paste preview print save searchreplace spellchecker tabfocus table template textpattern visualblocks visualchars wordcount"],_=["searchreplace bold italic underline strikethrough alignleft aligncenter alignright outdent indent blockquote undo redo removeformat subscript superscript code codesample","hr bullist numlist link image charmap preview anchor pagebreak insertdatetime media table emoticons forecolor backcolor fullscreen"],O=function(){return"vue-tinymce-"+ +new Date+(1e3*Math.random()).toFixed(0)},j=function(t){function e(){var t;return Object(a["a"])(this,e),t=Object(r["a"])(this,Object(l["a"])(e).apply(this,arguments)),t.hasChange=!1,t.hasInit=!1,t.fullscreen=!1,t.languageTypeList={en:"en",zh:"zh_CN",es:"es",ja:"ja",fr:"fr"},t}return Object(c["a"])(e,t),Object(o["a"])(e,[{key:"onLanguageChange",value:function(){var t=this,e=window.tinymce,i=e.get(this.id);this.fullscreen&&i.execCommand("mceFullScreen"),i&&i.destroy(),this.$nextTick((function(){return e.init(t.initOptions)}))}},{key:"imageSuccessCBK",value:function(t){var e=window.tinymce.get(this.id);t.forEach((function(t){e.insertContent('<img class="wscnph" src="'.concat(t.url,'" >'))}))}},{key:"language",get:function(){return this.languageTypeList[m["a"].language]}},{key:"uploadButtonColor",get:function(){return f["a"].theme}},{key:"tinymceContent",get:function(){return this.value},set:function(t){this.$emit("input",t)}},{key:"containerWidth",get:function(){var t=this.width;return/^[\d]+(\.[\d]+)?$/.test(t.toString())?"".concat(t,"px"):t}},{key:"initOptions",get:function(){var t=this;return{selector:"#".concat(this.id),height:this.height,body_class:"panel-body ",object_resizing:!1,toolbar:this.toolbar.length>0?this.toolbar:_,menubar:this.menubar,plugins:$,language:this.language,language_url:"en"===this.language?"":"".concat("/back/","tinymce/langs/").concat(this.language,".js"),skin_url:"".concat("/back/","tinymce/skins"),emoticons_database_url:"".concat("/back/","tinymce/emojis.min.js"),end_container_on_empty_block:!0,powerpaste_word_import:"clean",code_dialog_height:450,code_dialog_width:1e3,advlist_bullet_styles:"square",advlist_number_styles:"default",imagetools_cors_hosts:["www.tinymce.com","codepen.io"],default_link_target:"_blank",link_title:!1,nonbreaking_force_tab:!0,init_instance_callback:function(e){t.value&&e.setContent(t.value),t.hasInit=!0,e.on("NodeChange Change KeyUp SetContent",(function(){t.hasChange=!0,t.$emit("input",e.getContent())}))},setup:function(e){e.on("FullscreenStateChanged",(function(e){t.fullscreen=e.state}))}}}}]),e}(h["c"]);Object(u["a"])([Object(h["b"])({required:!0})],j.prototype,"value",void 0),Object(u["a"])([Object(h["b"])({default:O})],j.prototype,"id",void 0),Object(u["a"])([Object(h["b"])({default:function(){return[]}})],j.prototype,"toolbar",void 0),Object(u["a"])([Object(h["b"])({default:"file edit insert view format table"})],j.prototype,"menubar",void 0),Object(u["a"])([Object(h["b"])({default:"360px"})],j.prototype,"height",void 0),Object(u["a"])([Object(h["b"])({default:"auto"})],j.prototype,"width",void 0),Object(u["a"])([Object(h["d"])("language")],j.prototype,"onLanguageChange",null),j=Object(u["a"])([Object(h["a"])({name:"Tinymce",components:{EditorImageUpload:x,TinymceEditor:d["a"]}})],j);var C=j,S=C,D=(i("f220"),Object(y["a"])(S,s,n,!1,null,"d7c7b56c",null));e["b"]=D.exports},8502:function(t,e,i){"use strict";var s=i("99d7"),n=i.n(s);n.a},"99d7":function(t,e,i){t.exports={menuBg:"#304156",menuText:"#bfcbd9",menuActiveText:"#409eff"}},"9efd":function(t,e,i){"use strict";var s=i("1887"),n=i.n(s);n.a},a15b:function(t,e,i){"use strict";var s=i("23e7"),n=i("44ad"),a=i("fc6a"),o=i("b301"),r=[].join,l=n!=Object,c=o("join",",");s({target:"Array",proto:!0,forced:l||c},{join:function(t){return r.call(a(this),void 0===t?",":t)}})},accc:function(t,e,i){var s=i("23e7"),n=i("64e5");s({target:"Date",proto:!0,forced:Date.prototype.toISOString!==n},{toISOString:n})},bb2f:function(t,e,i){var s=i("d039");t.exports=!s((function(){return Object.isExtensible(Object.preventExtensions({}))}))},d6f8:function(t,e,i){},dea6:function(t,e,i){},f183:function(t,e,i){var s=i("d012"),n=i("861d"),a=i("5135"),o=i("9bf2").f,r=i("90e3"),l=i("bb2f"),c=r("meta"),u=0,d=Object.isExtensible||function(){return!0},h=function(t){o(t,c,{value:{objectID:"O"+ ++u,weakData:{}}})},m=function(t,e){if(!n(t))return"symbol"==typeof t?t:("string"==typeof t?"S":"P")+t;if(!a(t,c)){if(!d(t))return"F";if(!e)return"E";h(t)}return t[c].objectID},f=function(t,e){if(!a(t,c)){if(!d(t))return!0;if(!e)return!1;h(t)}return t[c].weakData},g=function(t){return l&&p.REQUIRED&&d(t)&&!a(t,c)&&h(t),t},p=t.exports={REQUIRED:!1,fastKey:m,getWeakData:f,onFreeze:g};s[c]=!0},f220:function(t,e,i){"use strict";var s=i("d6f8"),n=i.n(s);n.a}}]);