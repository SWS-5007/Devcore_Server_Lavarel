(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d0ac391"],{"192e":function(e,t,s){"use strict";s.r(t);s("498a"),s("a4d3"),s("e01a");var i=function(){var e=this,t=e._self._c;return t("div",[e.form?t("b-form",{staticClass:"hide-labels",on:{submit:function(t){return t.preventDefault(),e.saveItem.apply(null,arguments)},keyup:function(t){return e.$validator.validateAll()}}},[t("b-card",{staticClass:"d-block",attrs:{"no-body":""}},[t("b-card-body",{staticClass:"p-0",class:{"p-3":"edit"===e.mode}},[t("div",{staticClass:"form-group"},[t("b-form-input",{directives:[{name:"validate",rawName:"v-validate",value:"required|min:4",expression:"'required|min:4'"},{name:"autofocus",rawName:"v-autofocus"}],attrs:{id:"title",disabled:e.form.busy,placeholder:e.$t("Idea title"),type:"text",name:"title",autocomplete:"off",autofocus:"",state:e.$validateState("title",e.form)},model:{value:e.form.title,callback:function(t){e.$set(e.form,"title","string"===typeof t?t.trim():t)},expression:"form.title"}}),t("label",{attrs:{for:"title"}},[e._v(e._s(e.$t("Idea title")))]),t("b-form-invalid-feedback",[e._v(e._s(e.$displayError("title",e.form)))])],1),"TOOL"===e.form.type?t("div",{staticClass:"form-label-group select required"},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],staticClass:"text-capitalize",class:{"is-invalid":!1===e.$validateState("tool",e.form),"is-valid":!0===e.$validateState("tool",e.form)},attrs:{label:"name","data-vv-name":"tool",placeholder:e.$t("Tool"),reduce:function(e){return e.id},options:e.tools},model:{value:e.form.companyToolId,callback:function(t){e.$set(e.form,"companyToolId",t)},expression:"form.companyToolId"}}),t("label",{attrs:{for:"stage"}},[e._v(e._s(e.$t("Tool")))]),t("b-form-invalid-feedback",[e._v(e._s(e.$displayError("tool",e.form)))])],1):e._e(),t("div",{staticClass:"form-label-group select required"},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],staticClass:"text-capitalize",class:{"is-invalid":!1===e.$validateState("stage",e.form),"is-valid":!0===e.$validateState("stage",e.form)},attrs:{label:"title",disabled:"NEW"!=e.form.status,"data-vv-name":"stage",placeholder:e.$t("Stage"),reduce:function(e){return e.id},options:e.process.process.stages},on:{input:e.changeStage},model:{value:e.form.stageId,callback:function(t){e.$set(e.form,"stageId",t)},expression:"form.stageId"}}),t("label",{attrs:{for:"stage"}},[e._v(e._s(e.$t("Stage")))]),t("b-form-invalid-feedback",[e._v(e._s(e.$displayError("stage_id",e.form)))])],1),e.form.stageId?t("div",{staticClass:"form-label-group select required"},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"",expression:"''"}],staticClass:"text-capitalize",class:{"is-invalid":!1===e.$validateState("operation",e.form),"is-valid":!0===e.$validateState("operation",e.form)},attrs:{label:"title",disabled:"NEW"!=e.form.status,"data-vv-name":"operation_id",placeholder:e.$t("Operation"),reduce:function(e){return e.id},options:e.operations},on:{input:e.changeOperation},model:{value:e.form.operationId,callback:function(t){e.$set(e.form,"operationId",t)},expression:"form.operationId"}}),t("label",{attrs:{for:"operation"}},[e._v(e._s(e.$t("Operation")))]),t("b-form-invalid-feedback",[e._v(e._s(e.$displayError("operation_id",e.form)))])],1):e._e(),e.form.operationId?t("div",{staticClass:"form-label-group select required"},[t("v-select",{directives:[{name:"validate",rawName:"v-validate",value:"",expression:"''"}],staticClass:"text-capitalize",class:{"is-invalid":!1===e.$validateState("phase",e.form),"is-valid":!0===e.$validateState("phase",e.form)},attrs:{label:"title",disabled:"NEW"!=e.form.status,"data-vv-name":"phase",placeholder:e.$t("Phase"),reduce:function(e){return e.id},options:e.phases},model:{value:e.form.phaseId,callback:function(t){e.$set(e.form,"phaseId",t)},expression:"form.phaseId"}}),t("label",{attrs:{for:"phase"}},[e._v(e._s(e.$t("Phase")))]),t("b-form-invalid-feedback",[e._v(e._s(e.$displayError("phase",e.form)))])],1):e._e(),t("div",{staticClass:"form-label-group select required"},[t("b-form-textarea",{directives:[{name:"autoresize",rawName:"v-autoresize"},{name:"validate",rawName:"v-validate",value:"",expression:"''"}],staticStyle:{"max-height":"150px"},attrs:{id:"description",disabled:e.form.busy,placeholder:e.$t("Idea description"),name:"description",state:e.$validateState("description",e.form)},model:{value:e.form.description,callback:function(t){e.$set(e.form,"description",t)},expression:"form.description"}}),t("label",{attrs:{for:"description"}},[e._v(e._s(e.$t("Idea description")))]),t("b-form-invalid-feedback",[e._v(e._s(e.$displayError("description",e.form)))])],1)]),t("b-card-footer",{class:{"px-0":"create"===e.mode}},[t("loading-button",{attrs:{disabled:e.vErrors.any()||e.form.busy,loading:e.form.busy,size:"lg",block:"",type:"submit"}},[e._v(e._s(e.$t("Save changes")))])],1)],1)],1):e._e()],1)},r=[],a=s("c7eb"),o=s("1da1"),n=s("5530"),d=(s("7db0"),s("d3b7"),s("159b"),s("4de4"),s("b64b"),s("2f62")),l=s("9fee"),c={props:{item:null,section:{required:!1,type:String,default:function(){return"ideas"}},formFrom:{required:!1,type:String,default:function(){return"idea"}},type:{required:!1,type:String,default:function(){return"PROCESS"}},issueIdea:{required:!1,type:Object}},data:function(){return{input:null,currentFile:null,storeName:"idea",form:new l["a"]({id:void 0,title:null,processId:null,stageId:null,operationId:null,phaseId:null,description:null,companyToolId:null,removeFileIds:[],file:[],removeFile:!1,sourceId:null,sourceType:null,status:"NEW",type:"PROCESS"})}},computed:Object(n["a"])(Object(n["a"])({},Object(d["b"])({currentProcess:"process/current",currentTool:"companyTool/current",tools:"companyTool/all"})),{},{process:{get:function(){return this.currentProcess(this.section)}},stages:{get:function(){return this.process.process.stages||[]}},operations:{get:function(){var e=this;if(!this.form.stageId||!this.stages)return[];var t=this.stages.find((function(t){return t.id==e.form.stageId}));return(null===t||void 0===t?void 0:t.operations)||[]}},phases:{get:function(){var e=this;return this.form.operationId&&this.operations&&this.operations.find((function(t){return t.id==e.form.operationId})).phases||[]}},currentProcessSection:{get:function(){return this.process.phase||this.process.operation||this.process.stage||this.process.process}},currentProcessSectionName:{get:function(){return this.process.phase?"phase":this.process.operation?"operation":this.process.stage?"stage":this.process.process?"process":null}},mode:{get:function(){return this.item&&this.item.id?"edit":"create"}}}),mounted:function(){var e=this;return Object(o["a"])(Object(a["a"])().mark((function t(){return Object(a["a"])().wrap((function(t){while(1)switch(t.prev=t.next){case 0:if(e.input=e.item,e.item&&e.item.file&&(e.currentFile=e.item.file),"create"===e.mode&&e.currentTool("toolIdeas")&&"toolIdeas"==e.section&&(e.form.companyToolId=e.currentTool("toolIdeas").id),!e.process){t.next=6;break}return t.next=6,e.$store.dispatch("process/findById",{id:e.process.process.id});case 6:e.initForm(),e.storeName="PROCESS"===e.form.type?"idea":"toolIdea";case 8:case"end":return t.stop()}}),t)})))()},methods:{initForm:function(){var e,t,s,i=this;if("issues"===this.section&&this.issueIdea)return this.form.processId=this.issueIdea.processId,this.form.stageId=this.issueIdea.stageId,this.form.operationId=this.issueIdea.operationId,void(this.form.phaseId=this.issueIdea.phaseId);this.input.id||(this.input.type=this.input.type||"PROCESS",this.input.processId=this.input.processId||this.process.process.id,this.input.stageId=this.input.stageId||this.process.stage?null===(e=this.process.stage)||void 0===e?void 0:e.id:null,this.input.operationId=this.input.operationId||this.process.operation?null===(t=this.process.operation)||void 0===t?void 0:t.id:null,this.input.phaseId=this.input.phaseId||this.process.phase?null===(s=this.process.phase)||void 0===s?void 0:s.id:null);if(Object.keys(this.input||{}).filter((function(e){return e in i.form})).forEach((function(e){return i.form[e]=i.input[e]})),this.form.type=this.input.type,this.form.stageId=this.input.stageId,this.form.operationId=this.input.operationId,this.form.phaseId=this.input.phaseId,"issues"===this.section&&(this.form.stageId=this.input.stageId,this.form.operationId=this.input.operationId,this.form.phaseId=this.input.phaseId),this.input.sourceType&&"idea_issue"===this.input.sourceType){var r=this.input.parent.__typename;"ProcessPhase"===r?(this.form.stageId=this.input.parent.operation.stageId,this.form.phaseId=this.input.parent.id,this.form.operationId=this.input.parent.operationId):"ProcessOperation"===r?(this.form.operationId=this.input.parent.id,this.form.stageId=this.input.parent.stageId):this.form.stageId=this.input.parent.id}},changeStage:function(){this.form.operationId=null,this.form.phaseId=null},changeOperation:function(){this.form.phaseId=null},cancel:function(){this.$emit("cancel")},fileChanged:function(){this.form.files?this.form.removeFile=!1:(this.form.removeFile=!0,this.currentFile=null)},saveItem:function(){var e=this;return Object(o["a"])(Object(a["a"])().mark((function t(){var s,i,r;return Object(a["a"])().wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,e.$validator.validateAll();case 2:if(e.vErrors.any()){t.next=31;break}if(e.$validator.reset(),t.prev=4,"edit"!==e.mode){t.next=10;break}return t.next=8,e.$store.dispatch("".concat(e.storeName,"/update"),e.form);case 8:t.next=14;break;case 10:return t.next=12,e.$store.dispatch("".concat(e.storeName,"/create"),e.form);case 12:return t.next=14,e.$store.dispatch("".concat(e.storeName,"/setIdeaTab"),{tab:"New"});case 14:if(e.$emit("done"),"improveIdea"===e.formFrom&&e.$emit("reload"),"issues"!=e.section){t.next=18;break}return t.abrupt("return");case 18:return t.next=20,e.$store.dispatch("process/setCurrentProcess",{section:e.section,process:e.process.process,stage:null===(s=e.stages)||void 0===s?void 0:s.filter((function(t){return t.id===e.form.stageId}))[0],operation:null===(i=e.operations)||void 0===i?void 0:i.filter((function(t){return t.id===e.form.operationId}))[0],phase:null===(r=e.phases)||void 0===r?void 0:r.filter((function(t){return t.id===e.form.phaseId}))[0]});case 20:return t.next=22,e.$store.dispatch("process/findById",{id:e.form.processId,force:!0});case 22:return t.next=24,e.$store.dispatch("".concat(e.storeName,"/findByProcess"),{id:e.form.processId,force:!0});case 24:t.next=31;break;case 26:return t.prev=26,t.t0=t["catch"](4),console.log(t.t0),t.next=31,e.$validator.reset();case 31:case"end":return t.stop()}}),t,null,[[4,26]])})))()}}},p=c,u=s("2877"),f=Object(u["a"])(p,i,r,!1,null,null,null);t["default"]=f.exports}}]);