(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-dedac1c8"],{"0ccb":function(t,e,a){var n=a("50c4"),o=a("1148"),r=a("1d80"),i=Math.ceil,l=function(t){return function(e,a,l){var s,c,d=String(r(e)),u=d.length,g=void 0===l?" ":String(l),p=n(a);return p<=u||""==g?d:(s=p-u,c=o.call(g,i(s/g.length)),c.length>s&&(c=c.slice(0,s)),t?d+c:c+d)}};t.exports={start:l(!1),end:l(!0)}},1148:function(t,e,a){"use strict";var n=a("a691"),o=a("1d80");t.exports="".repeat||function(t){var e=String(o(this)),a="",r=n(t);if(r<0||r==1/0)throw RangeError("Wrong number of repetitions");for(;r>0;(r>>>=1)&&(e+=e))1&r&&(a+=e);return a}},"64e5":function(t,e,a){"use strict";var n=a("d039"),o=a("0ccb").start,r=Math.abs,i=Date.prototype,l=i.getTime,s=i.toISOString;t.exports=n((function(){return"0385-07-25T07:06:39.999Z"!=s.call(new Date(-5e13-1))}))||!n((function(){s.call(new Date(NaN))}))?function(){if(!isFinite(l.call(this)))throw RangeError("Invalid time value");var t=this,e=t.getUTCFullYear(),a=t.getUTCMilliseconds(),n=e<0?"-":e>9999?"+":"";return n+o(r(e),n?6:4,0)+"-"+o(t.getUTCMonth()+1,2,0)+"-"+o(t.getUTCDate(),2,0)+"T"+o(t.getUTCHours(),2,0)+":"+o(t.getUTCMinutes(),2,0)+":"+o(t.getUTCSeconds(),2,0)+"."+o(a,3,0)+"Z"}:s},a6c3:function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{staticClass:"content"},[a("div",[a("span",[t._v(t._s(t.$t("documentation.Tradename")))]),a("el-input",{attrs:{placeholder:t.$t("documentation.Entername")},model:{value:t.goodsname,callback:function(e){t.goodsname=e},expression:"goodsname"}}),a("span",[t._v(t._s(t.$t("documentation.date")))]),a("el-date-picker",{attrs:{type:"date",placeholder:t.$t("documentation.Selectdate")},model:{value:t.datevalue,callback:function(e){t.datevalue=e},expression:"datevalue"}}),a("el-button",{attrs:{type:"primary",icon:"el-icon-search"},on:{click:t.searchdata}},[t._v(t._s(t.$t("documentation.search")))])],1),a("div",{staticClass:"goodswrap"},[a("div",{staticClass:"saletitle"},[a("i",{staticClass:"el-icon-s-data"}),t._v(" "+t._s(t.$t("documentation.Productsales"))+" ")]),a("el-table",{staticStyle:{width:"100%"},attrs:{data:t.tableData,"default-sort":{prop:"number",order:"ascending"}}},[a("el-table-column",{attrs:{prop:"ranking",label:t.$t("documentation.ranking")}}),a("el-table-column",{attrs:{prop:"name",label:t.$t("documentation.Tradename")}}),a("el-table-column",{attrs:{prop:"number",sortable:"",label:t.$t("documentation.Totalsales")}}),a("el-table-column",{attrs:{prop:"add_time",label:t.$t("documentation.date")}})],1)],1)])])},o=[],r=(a("fb6a"),a("accc"),a("0d03"),a("e25e"),a("b8f0")),i={data:function(){return{datevalue:"",goodsname:"",tableData:[]}},created:function(){this.statistics()},methods:{formatTime:function(t){var e=new Date(1e3*t);return new Date(Date.UTC(e.getFullYear(),e.getMonth(),e.getDate())).toISOString().slice(0,10)},searchdata:function(){this.statistics()},statistics:function(){var t=this;this.tableData=[];var e=this.goodsname,a="",n=new Date;a=this.datevalue?this.datevalue.getTime()/1e3:"",Object(r["ob"])({name:e,time:a}).then((function(e){for(var a in console.log(e.data),e.data){t.tableData.push(e.data[a]),console.log(a,"i"),console.log(e.data,"res.data"),console.log(t.tableData,"this.tableData");try{t.tableData[a].ranking=parseInt(a)+parseInt(1),t.datevalue?t.tableData[a].add_time=t.datevalue.getFullYear()+"-"+(parseInt(t.datevalue.getMonth())+parseInt(1))+"-"+t.datevalue.getDate():t.tableData[a].add_time=n.getFullYear()+"-"+(parseInt(n.getMonth())+parseInt(1))+"-"+n.getDate()}catch(o){console.log(o)}}})).catch((function(t){}))}}},l=i,s=(a("aab9"),a("2877")),c=Object(s["a"])(l,n,o,!1,null,"d28316a2",null);e["default"]=c.exports},aab9:function(t,e,a){"use strict";var n=a("f898"),o=a.n(n);o.a},accc:function(t,e,a){var n=a("23e7"),o=a("64e5");n({target:"Date",proto:!0,forced:Date.prototype.toISOString!==o},{toISOString:o})},f898:function(t,e,a){}}]);