(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-7621e33c"],{"0ccb":function(t,e,i){var n=i("50c4"),a=i("1148"),o=i("1d80"),s=Math.ceil,c=function(t){return function(e,i,c){var l,r,u=String(o(e)),d=u.length,b=void 0===c?" ":String(c),h=n(i);return h<=d||""==b?u:(l=h-d,r=a.call(b,s(l/b.length)),r.length>l&&(r=r.slice(0,l)),t?u+r:r+u)}};t.exports={start:c(!1),end:c(!0)}},"0dea":function(t,e,i){"use strict";i.r(e);var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("el-tabs",{on:{"tab-click":t.handleClick},model:{value:t.activeName,callback:function(e){t.activeName=e},expression:"activeName"}},[i("el-tab-pane",{attrs:{label:t.$t("documentation.Aboutus"),name:"first"}},[i("div",{directives:[{name:"show",rawName:"v-show",value:!t.isshow,expression:"!isshow"}],staticClass:"content"},[i("el-row",[i("el-col",[i("span",[t._v(t._s(t.$t("documentation.nameart"))+":")]),i("el-input",{model:{value:t.textname,callback:function(e){t.textname=e},expression:"textname"}})],1),i("el-col",[i("span",[t._v(t._s(t.$t("documentation.title"))+":")]),i("el-input",{model:{value:t.texttitle,callback:function(e){t.texttitle=e},expression:"texttitle"}})],1)],1),i("p",[i("span",[t._v(t._s(t.$t("documentation.content"))+":")]),i("tinymce",{staticStyle:{width:"100%"},model:{value:t.tinytext,callback:function(e){t.tinytext=e},expression:"tinytext"}})],1),i("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[i("el-button",{on:{click:t.dialogcancel}},[t._v(t._s(t.$t("documentation.cancel")))]),i("el-button",{attrs:{type:"primary"},on:{click:t.dialogsumit}},[t._v(t._s(t.$t("documentation.Modifynow")))])],1)],1),i("div",{directives:[{name:"show",rawName:"v-show",value:t.isshow,expression:"isshow"}],staticClass:"content content4"},[i("el-table",{staticStyle:{width:"100%"},attrs:{data:t.articledata}},[i("el-table-column",{attrs:{label:t.$t("documentation.nameart"),prop:"name"}}),i("el-table-column",{attrs:{label:t.$t("documentation.title"),prop:"title"}}),i("el-table-column",{attrs:{label:t.$t("documentation.briefint"),prop:"text"}}),i("el-table-column",{attrs:{label:t.$t("documentation.Addtime"),prop:"time"}}),i("el-table-column",{attrs:{label:t.$t("documentation.operation")},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-button",{attrs:{size:"mini",type:"primary",icon:"el-icon-edit"},on:{click:function(i){return t.handleEdit(e.row)}}})]}}])})],1)],1)]),i("el-tab-pane",{attrs:{label:t.$t("documentation.contactus"),name:"second"}},[i("div",{directives:[{name:"show",rawName:"v-show",value:!t.isshow2,expression:"!isshow2"}],staticClass:"content"},[i("p",[i("span",[t._v(t._s(t.$t("documentation.nameart"))+":")]),i("el-input",{model:{value:t.textname2,callback:function(e){t.textname2=e},expression:"textname2"}})],1),i("p",[i("span",[t._v(t._s(t.$t("documentation.title"))+":")]),i("el-input",{model:{value:t.texttitle2,callback:function(e){t.texttitle2=e},expression:"texttitle2"}})],1),i("p",[i("span",[t._v(t._s(t.$t("documentation.content"))+":")]),i("tinymce",{staticStyle:{width:"60%"},model:{value:t.tinytext2,callback:function(e){t.tinytext2=e},expression:"tinytext2"}})],1),i("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[i("el-button",{on:{click:t.dialogcancel2}},[t._v(t._s(t.$t("documentation.cancel")))]),i("el-button",{attrs:{type:"primary"},on:{click:t.dialogsumit2}},[t._v(t._s(t.$t("documentation.Modifynow")))])],1)]),i("div",{directives:[{name:"show",rawName:"v-show",value:t.isshow2,expression:"isshow2"}],staticClass:"content content4"},[i("p",[i("el-button",{attrs:{type:"primary"},on:{click:t.addtextbtn}},[t._v(t._s(t.$t("documentation.addto")))])],1),i("el-table",{staticStyle:{width:"60%"},attrs:{data:t.articledata2}},[i("el-table-column",{attrs:{label:t.$t("documentation.nameart"),prop:"name"}}),i("el-table-column",{attrs:{label:t.$t("documentation.briefint"),prop:"text"}}),i("el-table-column",{attrs:{label:t.$t("documentation.Addtime"),prop:"time"}}),i("el-table-column",{attrs:{label:t.$t("documentation.operation")},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-button",{staticStyle:{"margin-right":"5px"},attrs:{size:"mini",type:"primary",icon:"el-icon-edit"},on:{click:function(i){return t.handleEdit2(e.row)}}}),i("el-popover",{attrs:{width:"160"},model:{value:t.boollist[e.$index],callback:function(i){t.$set(t.boollist,e.$index,i)},expression:"boollist[scope.$index]"}},[i("p",[t._v(t._s(t.$t("documentation.wantdelete")))]),i("div",{staticStyle:{"text-align":"right, margin: 0"}},[i("el-button",{attrs:{size:"mini",type:"text"},on:{click:function(i){return i.stopPropagation(),t.closepop(e.$index)}}},[t._v(t._s(t.$t("documentation.cancel")))]),i("el-button",{attrs:{type:"primary",size:"mini"},on:{click:function(i){return i.stopPropagation(),t.delorder(e.$index)}}},[t._v(t._s(t.$t("documentation.determine")))])],1),i("el-button",{staticStyle:{"margin-left":"10px"},attrs:{slot:"reference",size:"mini",type:"danger",icon:"el-icon-delete"},on:{click:function(i){return t.handleDelete(e.row.id,e.$index)}},slot:"reference"})],1)]}}])})],1)],1)])],1)],1)},a=[],o=(i("fb6a"),i("accc"),i("0d03"),i("b0c0"),i("bf2d")),s=i("8256"),c=i("b8f0"),l={name:"articletext",components:{Tinymce:s["b"]},data:function(){return{isshow:!0,isshow2:!0,tinytext:"",texttitle:"",texttitle2:"",textname:"",textname2:"",tinytext2:"",dialogtext:!0,dialogtext2:!1,activeName:"first",articledata:[],articledata2:[],boollist:[],editid:"",editid2:"",isaddtext:"",deleid:""}},created:function(){this.aboutList(),this.contactList()},methods:{contactDelete:function(){var t=this,e=this.deleid;Object(c["p"])({id:e}).then((function(e){console.log(e,"res"),t.$message.success(t.$t("tips.success")),t.contactList()})).catch((function(e){t.$message.error(t.$t("tips.fail"))}))},formatTime:function(t){var e=new Date(1e3*t);return new Date(Date.UTC(e.getFullYear(),e.getMonth(),e.getDate())).toISOString().slice(0,10)},dialogcancel:function(){this.isshow=!0},dialogcancel2:function(){this.isshow2=!0},dialogsumit:function(){this.aboutList()},dialogsumit2:function(){this.contactAdd()},addtextbtn:function(){this.isaddtext="添加",this.isshow2=!1,this.textname2="",this.tinytext2=""},contactAdd:function(){var t=this,e=this.editid2;"添加"==this.isaddtext&&(e="");var i=this.textname2,n=this.texttitle2;console.log(n);var a=this.tinytext2;Object(c["o"])({id:e,name:i,title:n,text:a}).then((function(e){console.log(e,"res"),t.$message.success(t.$t("tips.success")),t.contactList()})).catch((function(e){t.$message.error(t.$t("tips.fail"))}))},contactList:function(){var t=this;Object(c["q"])({}).then((function(e){for(var i in console.log(e,"res"),t.articledata2=[],e.data)t.articledata2.push(e.data[i]),t.articledata2[i].time=t.formatTime(t.articledata2[i].time),t.boollist[i]=!1})).catch((function(t){}))},aboutList:function(){var t=this,e=this.editid,i=this.textname,n=this.texttitle,a=this.tinytext,s=this;Object(c["e"])({id:e,name:i,title:n,text:a}).then((function(e){if(console.log(e,"res"),console.log(Object(o["a"])(e.data)),"string"==typeof e.data)return s.$message.success(s.$t("tips.success")),s.isshow=!0,t.editid="",t.textname="",t.texttitle="",t.tinytext="",void t.aboutList();for(var i in t.articledata=e.data,console.log(t.articledata),e.data)t.articledata[i].time=t.formatTime(t.articledata[i].time)})).catch((function(t){}))},handleClick:function(t,e){console.log(t,e)},textClose:function(){},textClose2:function(){},handleDelete:function(t,e){this.deleid=t,this.boollist[e]=!0},closepop:function(t){this.boollist[t]=!1},delorder:function(t){this.contactDelete(),this.boollist[t]=!1},chancheck:function(){},handleEdit:function(t){this.isshow=!1,this.editid=t.id,this.textname=t.name,this.texttitle=t.title,this.tinytext=t.text},handleEdit2:function(t){this.isaddtext="编辑",this.isshow2=!1,this.editid2=t.id,this.textname2=t.name,this.tinytext2=t.text}}},r=l,u=(i("e373"),i("2877")),d=Object(u["a"])(r,n,a,!1,null,"1a7f271b",null);e["default"]=d.exports},4082:function(t,e,i){"use strict";var n=i("dea6"),a=i.n(n);a.a},"64e5":function(t,e,i){"use strict";var n=i("d039"),a=i("0ccb").start,o=Math.abs,s=Date.prototype,c=s.getTime,l=s.toISOString;t.exports=n((function(){return"0385-07-25T07:06:39.999Z"!=l.call(new Date(-5e13-1))}))||!n((function(){l.call(new Date(NaN))}))?function(){if(!isFinite(c.call(this)))throw RangeError("Invalid time value");var t=this,e=t.getUTCFullYear(),i=t.getUTCMilliseconds(),n=e<0?"-":e>9999?"+":"";return n+a(o(e),n?6:4,0)+"-"+a(t.getUTCMonth()+1,2,0)+"-"+a(t.getUTCDate(),2,0)+"T"+a(t.getUTCHours(),2,0)+":"+a(t.getUTCMinutes(),2,0)+":"+a(t.getUTCSeconds(),2,0)+"."+a(i,3,0)+"Z"}:l},8256:function(t,e,i){"use strict";var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"tinymce-container",class:{fullscreen:t.fullscreen},style:{width:t.containerWidth}},[i("tinymce-editor",{attrs:{id:t.id,init:t.initOptions},model:{value:t.tinymceContent,callback:function(e){t.tinymceContent=e},expression:"tinymceContent"}}),i("div",{staticClass:"editor-custom-btn-container"},[i("editor-image-upload",{staticClass:"editor-upload-btn",attrs:{color:t.uploadButtonColor},on:{successCBK:t.imageSuccessCBK}})],1)],1)},a=[],o=(i("99af"),i("4160"),i("0d03"),i("b680"),i("d3b7"),i("25f0"),i("159b"),i("9f12")),s=i("53fe"),c=i("8b83"),l=i("c65a"),r=i("c03e"),u=i("9ab4"),d=(i("e562"),i("0d68"),i("ecb9"),i("0902"),i("d2dc"),i("2fec"),i("ffbe"),i("64d8"),i("07d7f"),i("855b"),i("69e4"),i("3154"),i("2b07"),i("4ea8"),i("8863"),i("4bd0"),i("4237"),i("84ec8"),i("3aea"),i("eda9"),i("cfb0"),i("ebac"),i("bc54"),i("0a9d"),i("840a"),i("6957"),i("62e5"),i("dcb7"),i("55a0"),i("07d1"),i("0335"),i("78e4"),i("0efa"),i("365e"),i("9434"),i("ca72")),b=i("60a3"),h=i("ac1a"),m=i("7383"),f=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"upload-container"},[i("el-button",{style:{background:t.color,borderColor:t.color},attrs:{icon:"el-icon-upload",size:"mini",type:"primary"},on:{click:function(e){t.dialogVisible=!0}}},[t._v(" upload ")]),i("el-dialog",{attrs:{visible:t.dialogVisible},on:{"update:visible":function(e){t.dialogVisible=e}}},[i("el-upload",{staticClass:"editor-slide-upload",attrs:{multiple:!0,"file-list":t.defaultFileList,"show-file-list":!0,"on-remove":t.handleRemove,"on-success":t.handleSuccess,"before-upload":t.beforeUpload,action:"/upload","list-type":"picture-card"}},[i("el-button",{attrs:{size:"small",type:"primary"}},[t._v(" Click upload ")])],1),i("el-button",{on:{click:function(e){t.dialogVisible=!1}}},[t._v(" Cancel ")]),i("el-button",{attrs:{type:"primary"},on:{click:t.handleSubmit}},[t._v(" Confirm ")])],1)],1)},p=[],g=(i("a623"),i("d81d"),i("b64b"),i("3ca3"),i("ddb0"),i("2b3d"),function(t){function e(){var t;return Object(o["a"])(this,e),t=Object(c["a"])(this,Object(l["a"])(e).apply(this,arguments)),t.dialogVisible=!1,t.listObj={},t.defaultFileList=[],t}return Object(r["a"])(e,t),Object(s["a"])(e,[{key:"checkAllSuccess",value:function(){var t=this;return Object.keys(this.listObj).every((function(e){return t.listObj[e].hasSuccess}))}},{key:"handleSubmit",value:function(){var t=this,e=Object.keys(this.listObj).map((function(e){return t.listObj[e]}));this.checkAllSuccess()?(this.$emit("successCBK",e),this.listObj={},this.defaultFileList=[],this.dialogVisible=!1):this.$message("Please wait for all images to be uploaded successfully. If there is a network problem, please refresh the page and upload again!")}},{key:"handleSuccess",value:function(t,e){var i=e.uid;console.log(t);for(var n=Object.keys(this.listObj),a=0,o=n.length;a<o;a++)if(this.listObj[n[a]].uid===i)return this.listObj[n[a]].url=t.data.path,this.listObj[n[a]].hasSuccess=!0,void console.log(this.listObj)}},{key:"handleRemove",value:function(t){for(var e=t.uid,i=Object.keys(this.listObj),n=0,a=i.length;n<a;n++)if(this.listObj[i[n]].uid===e)return void delete this.listObj[i[n]]}},{key:"beforeUpload",value:function(t){var e=this,i=t.uid,n=new Image;n.src=window.URL.createObjectURL(t),n.onload=function(){e.listObj[i]={hasSuccess:!1,uid:t.uid,url:"",width:n.width,height:n.height}}}}]),e}(b["c"]));Object(u["a"])([Object(b["b"])({required:!0})],g.prototype,"color",void 0),g=Object(u["a"])([Object(b["a"])({name:"EditorImageUpload"})],g);var v=g,y=v,x=(i("8502"),i("4082"),i("2877")),w=Object(x["a"])(y,f,p,!1,null,"7ac3d61e",null),k=w.exports,_=["advlist anchor autolink autosave code codesample directionality emoticons fullscreen hr image imagetools insertdatetime link lists media nonbreaking noneditable pagebreak paste preview print save searchreplace spellchecker tabfocus table template textpattern visualblocks visualchars wordcount"],O=["searchreplace bold italic underline strikethrough alignleft aligncenter alignright outdent indent blockquote undo redo removeformat subscript superscript code codesample","hr bullist numlist link image charmap preview anchor pagebreak insertdatetime media table emoticons forecolor backcolor fullscreen"],j=function(){return"vue-tinymce-"+ +new Date+(1e3*Math.random()).toFixed(0)},$=function(t){function e(){var t;return Object(o["a"])(this,e),t=Object(c["a"])(this,Object(l["a"])(e).apply(this,arguments)),t.hasChange=!1,t.hasInit=!1,t.fullscreen=!1,t.languageTypeList={en:"en",zh:"zh_CN",es:"es",ja:"ja",fr:"fr"},t}return Object(r["a"])(e,t),Object(s["a"])(e,[{key:"onLanguageChange",value:function(){var t=this,e=window.tinymce,i=e.get(this.id);this.fullscreen&&i.execCommand("mceFullScreen"),i&&i.destroy(),this.$nextTick((function(){return e.init(t.initOptions)}))}},{key:"imageSuccessCBK",value:function(t){var e=window.tinymce.get(this.id);t.forEach((function(t){e.insertContent('<img class="wscnph" src="'.concat(t.url,'" >'))}))}},{key:"language",get:function(){return this.languageTypeList[h["a"].language]}},{key:"uploadButtonColor",get:function(){return m["a"].theme}},{key:"tinymceContent",get:function(){return this.value},set:function(t){this.$emit("input",t)}},{key:"containerWidth",get:function(){var t=this.width;return/^[\d]+(\.[\d]+)?$/.test(t.toString())?"".concat(t,"px"):t}},{key:"initOptions",get:function(){var t=this;return{selector:"#".concat(this.id),height:this.height,body_class:"panel-body ",object_resizing:!1,toolbar:this.toolbar.length>0?this.toolbar:O,menubar:this.menubar,plugins:_,language:this.language,language_url:"en"===this.language?"":"".concat("/back/","tinymce/langs/").concat(this.language,".js"),skin_url:"".concat("/back/","tinymce/skins"),emoticons_database_url:"".concat("/back/","tinymce/emojis.min.js"),end_container_on_empty_block:!0,powerpaste_word_import:"clean",code_dialog_height:450,code_dialog_width:1e3,advlist_bullet_styles:"square",advlist_number_styles:"default",imagetools_cors_hosts:["www.tinymce.com","codepen.io"],default_link_target:"_blank",link_title:!1,nonbreaking_force_tab:!0,init_instance_callback:function(e){t.value&&e.setContent(t.value),t.hasInit=!0,e.on("NodeChange Change KeyUp SetContent",(function(){t.hasChange=!0,t.$emit("input",e.getContent())}))},setup:function(e){e.on("FullscreenStateChanged",(function(e){t.fullscreen=e.state}))}}}}]),e}(b["c"]);Object(u["a"])([Object(b["b"])({required:!0})],$.prototype,"value",void 0),Object(u["a"])([Object(b["b"])({default:j})],$.prototype,"id",void 0),Object(u["a"])([Object(b["b"])({default:function(){return[]}})],$.prototype,"toolbar",void 0),Object(u["a"])([Object(b["b"])({default:"file edit insert view format table"})],$.prototype,"menubar",void 0),Object(u["a"])([Object(b["b"])({default:"360px"})],$.prototype,"height",void 0),Object(u["a"])([Object(b["b"])({default:"auto"})],$.prototype,"width",void 0),Object(u["a"])([Object(b["d"])("language")],$.prototype,"onLanguageChange",null),$=Object(u["a"])([Object(b["a"])({name:"Tinymce",components:{EditorImageUpload:k,TinymceEditor:d["a"]}})],$);var C=$,S=C,T=(i("f220"),Object(x["a"])(S,n,a,!1,null,"d7c7b56c",null));e["b"]=T.exports},8502:function(t,e,i){"use strict";var n=i("99d7"),a=i.n(n);a.a},"99d7":function(t,e,i){t.exports={menuBg:"#304156",menuText:"#bfcbd9",menuActiveText:"#409eff"}},accc:function(t,e,i){var n=i("23e7"),a=i("64e5");n({target:"Date",proto:!0,forced:Date.prototype.toISOString!==a},{toISOString:a})},d6f8:function(t,e,i){},d870:function(t,e,i){},dea6:function(t,e,i){},e373:function(t,e,i){"use strict";var n=i("d870"),a=i.n(n);a.a},f220:function(t,e,i){"use strict";var n=i("d6f8"),a=i.n(n);a.a}}]);