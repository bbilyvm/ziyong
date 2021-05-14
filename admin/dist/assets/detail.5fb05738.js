var e=Object.defineProperty,l=Object.prototype.hasOwnProperty,a=Object.getOwnPropertySymbols,n=Object.prototype.propertyIsEnumerable,t=(l,a,n)=>a in l?e(l,a,{enumerable:!0,configurable:!0,writable:!0,value:n}):l[a]=n,o=(e,o)=>{for(var i in o||(o={}))l.call(o,i)&&t(e,i,o[i]);if(a)for(var i of a(o))n.call(o,i)&&t(e,i,o[i]);return e};import{r as i,a as d,t as s,b as r,v as u,e as m,j as f,o as c,c as p,s as b,f as g,k as v,F as _,x as h,h as w,i as x,q as I,u as D,p as y,d as C,y as V,z as k,g as U}from"./index.a4f4e22b.js";const E={name:"DialogAssignExam",props:{reload:Function},setup(e,l){const a=i(null),n=d({loading:!1,matchExams:[],visible:!1,formData:{uid:0,exam_id:"",time_range:[]},rules:{exam_id:[{required:"true"}]}});return o(o({},s(n)),{handleSubmit:()=>{a.value.validate((async l=>{if(l){let l=await r.storeExamUser(n.formData);n.visible=!1,u.success(l.msg),e.reload&&e.reload()}}))},formRef:a,open:e=>{n.formData.uid=e,0==n.matchExams.length&&(n.loading=!0,(async()=>{let e=await r.listUserMatchExams({uid:n.formData.uid});n.matchExams=e.data})(),n.loading=!1),n.visible=!0}})}},R=g("div",{class:"time-range-help-text"},"If the time range is not specified, the exam's own configured time range will be used.",-1),P={class:"dialog-footer"},A=w("Cancel"),M=w("Save");E.render=function(e,l,a,n,t,o){const i=m("el-option"),d=m("el-select"),s=m("el-form-item"),r=m("el-date-picker"),u=m("el-form"),w=m("el-button"),x=m("el-dialog"),I=f("loading");return c(),p(x,{title:"Assign exam to user",modelValue:e.visible,"onUpdate:modelValue":l[4]||(l[4]=l=>e.visible=l),center:"","close-on-click-modal":!1},{footer:b((()=>[g("span",P,[g(w,{onClick:l[3]||(l[3]=l=>e.visible=!1)},{default:b((()=>[A])),_:1}),g(w,{type:"primary",onClick:n.handleSubmit},{default:b((()=>[M])),_:1},8,["onClick"])])])),default:b((()=>[v(g(u,{model:e.formData,"label-width":"100px",ref:"formRef",rules:e.rules},{default:b((()=>[g(s,{label:"Exam",prop:"exam_id"},{default:b((()=>[g(d,{modelValue:e.formData.exam_id,"onUpdate:modelValue":l[1]||(l[1]=l=>e.formData.exam_id=l),placeholder:"Select an exam..."},{default:b((()=>[(c(!0),p(_,null,h(e.matchExams,(e=>(c(),p(i,{key:e.id,label:e.name,value:e.id},null,8,["label","value"])))),128))])),_:1},8,["modelValue"])])),_:1}),g(s,{label:"Time range",prop:"time_range"},{default:b((()=>[g(r,{modelValue:e.formData.time_range,"onUpdate:modelValue":l[2]||(l[2]=l=>e.formData.time_range=l),type:"datetimerange",format:"YYYY-MM-DD HH:mm:ss","range-separator":"to","start-placeholder":"Begin","end-placeholder":"End"},null,8,["modelValue"]),R])),_:1})])),_:1},8,["model","rules"]),[[I,e.loading]])])),_:1},8,["modelValue"])};const z={name:"DialogInviteInfo",props:{reload:Function},setup(e,l){const a=i(null),n=d({loading:!1,visible:!1,uid:0,inviteInfo:[]});return o(o({},s(n)),{formRef:a,open:e=>{n.uid=e,0==n.inviteInfo.length&&(n.loading=!0,(async()=>{let e=await r.getInviteInfo({uid:n.uid});n.inviteInfo.push(e.data)})(),n.loading=!1),n.visible=!0}})}};z.render=function(e,l,a,n,t,o){const i=m("el-table-column"),d=m("el-table"),s=m("el-dialog"),r=f("loading");return c(),p(s,{title:"Invite info",modelValue:e.visible,"onUpdate:modelValue":l[1]||(l[1]=l=>e.visible=l),center:"",width:"65%","close-on-click-modal":!1},{default:b((()=>[v(g(d,{data:e.inviteInfo},{default:b((()=>[g(i,{prop:"id",label:"ID",width:"55"}),g(i,{prop:"inviter_user.username",label:"Inviter",width:"150"}),g(i,{prop:"invitee",label:"Receive email"}),g(i,{prop:"hash",label:"Hash"}),g(i,{prop:"valid_text",label:"Hash valid",width:"100"}),g(i,{prop:"invitee_register_email",label:"Register email"}),g(i,{prop:"time_invited",label:"Time invited",width:"160"})])),_:1},8,["data"]),[[r,e.loading]])])),_:1},8,["modelValue"])};const S={name:"DialogDisableUser",props:{reload:Function},setup(e,l){const a=i(null),n=d({loading:!1,visible:!1,formData:{uid:0,reason:""},rules:{reason:[{required:"true"}]}});return o(o({},s(n)),{handleSubmit:()=>{a.value.validate((async l=>{if(l){let l=await r.disableUser(n.formData);n.visible=!1,u.success(l.msg),e.reload&&e.reload()}}))},formRef:a,open:e=>{n.formData.uid=e,n.visible=!0}})}},q={class:"dialog-footer"},F=w("Cancel"),O=w("Save");S.render=function(e,l,a,n,t,o){const i=m("el-input"),d=m("el-form-item"),s=m("el-form"),r=m("el-button"),u=m("el-dialog"),_=f("loading");return c(),p(u,{title:"Disable user",modelValue:e.visible,"onUpdate:modelValue":l[3]||(l[3]=l=>e.visible=l),center:"","close-on-click-modal":!1},{footer:b((()=>[g("span",q,[g(r,{onClick:l[2]||(l[2]=l=>e.visible=!1)},{default:b((()=>[F])),_:1}),g(r,{type:"primary",onClick:n.handleSubmit},{default:b((()=>[O])),_:1},8,["onClick"])])])),default:b((()=>[v(g(s,{model:e.formData,"label-width":"100px",ref:"formRef",rules:e.rules},{default:b((()=>[g(d,{label:"Reason",prop:"reason"},{default:b((()=>[g(i,{type:"textarea",modelValue:e.formData.reason,"onUpdate:modelValue":l[1]||(l[1]=l=>e.formData.reason=l)},null,8,["modelValue"])])),_:1})])),_:1},8,["model","rules"]),[[_,e.loading]])])),_:1},8,["modelValue"])};const j={name:"DialogModComment",props:{reload:Function},setup(e,l){const a=i(null),n=d({loading:!1,visible:!1,uid:0,modComment:""});return o(o({},s(n)),{formRef:a,open:e=>{n.uid=e,n.modComment||(n.loading=!0,(async()=>{let e=await r.getUserModComment({uid:n.uid});n.modComment=e.data})(),n.loading=!1),n.visible=!0}})}};j.render=function(e,l,a,n,t,o){const i=m("el-card"),d=m("el-dialog"),s=f("loading");return c(),p(d,{title:"Mod comment",modelValue:e.visible,"onUpdate:modelValue":l[1]||(l[1]=l=>e.visible=l),center:"",width:"40%","close-on-click-modal":!1},{default:b((()=>[v(g(i,null,{default:b((()=>[g("div",{innerHTML:e.modComment,class:"pre-line"},null,8,["innerHTML"])])),_:1},512),[[s,e.loading]])])),_:1},8,["modelValue"])};const H={name:"DialogResetPassword",props:{reload:Function},setup(e,l){const a=i(null),n=d({loading:!1,visible:!1,formData:{uid:0,password:"",password_confirmation:""},rules:{password:[{required:"true"}],password_confirmation:[{required:"true"}]}});return o(o({},s(n)),{handleSubmit:()=>{a.value.validate((async l=>{if(l){let l=await r.resetPassword(n.formData);n.visible=!1,u.success(l.msg),e.reload&&e.reload()}}))},formRef:a,open:e=>{n.formData.uid=e,n.visible=!0}})}},T={class:"dialog-footer"},Y=w("Cancel"),B=w("Save");H.render=function(e,l,a,n,t,o){const i=m("el-input"),d=m("el-form-item"),s=m("el-form"),r=m("el-button"),u=m("el-dialog"),_=f("loading");return c(),p(u,{title:"Reset password",modelValue:e.visible,"onUpdate:modelValue":l[4]||(l[4]=l=>e.visible=l),center:"","close-on-click-modal":!1},{footer:b((()=>[g("span",T,[g(r,{onClick:l[3]||(l[3]=l=>e.visible=!1)},{default:b((()=>[Y])),_:1}),g(r,{type:"primary",onClick:n.handleSubmit},{default:b((()=>[B])),_:1},8,["onClick"])])])),default:b((()=>[v(g(s,{model:e.formData,"label-width":"200px",ref:"formRef",rules:e.rules},{default:b((()=>[g(d,{label:"Password",prop:"password"},{default:b((()=>[g(i,{modelValue:e.formData.password,"onUpdate:modelValue":l[1]||(l[1]=l=>e.formData.password=l)},null,8,["modelValue"])])),_:1}),g(d,{label:"Password confirmation",prop:"password_confirmation"},{default:b((()=>[g(i,{modelValue:e.formData.password_confirmation,"onUpdate:modelValue":l[2]||(l[2]=l=>e.formData.password_confirmation=l)},null,8,["modelValue"])])),_:1})])),_:1},8,["model","rules"]),[[_,e.loading]])])),_:1},8,["modelValue"])};const G={name:"UserDetail",components:{DialogAssignExam:E,DialogViewInviteInfo:z,DialogDisableUser:S,DialogModComment:j,DialogResetPassword:H},setup(){const e=I();D();const{id:l}=e.query,a=i(null),n=i(null),t=i(null),m=i(null),f=i(null),c=d({loading:!1,baseInfo:{},examInfo:null});x((()=>{p()}));const p=async()=>{c.loading=!0;let e=await r.getUser(l);c.loading=!1,c.baseInfo=e.data.base_info,c.examInfo=e.data.exam_info};return o(o({},s(c)),{handleRemoveExam:async e=>{let l=await r.deleteExamUser(e);u.success(l.msg),await p()},handleAssignExam:async()=>{a.value.open(l)},handleEnableUser:async()=>{let e=await r.enableUser({uid:l});u.success(e.msg),await p()},handleViewInviteInfo:async()=>{n.value.open(l)},handleDisableUser:async()=>{t.value.open(l)},handleGetModComment:async()=>{m.value.open(l)},handleResetPassword:async()=>{f.value.open(l)},fetchPageData:p,assignExam:a,viewInviteInfo:n,disableUser:t,modComment:m,resetPassword:f})}},L=U();y("data-v-05f1091e");const N={class:"page-user-detail"},J=g("div",{class:"card-header"},[g("span",null,"Base info")],-1),K={class:"table-base-info"},Q=g("tr",null,[g("th",null,"Field"),g("th",null,"Value"),g("th",null,"Actions"),g("th",null,"Other")],-1),W=g("td",null,"Username",-1),X=g("td",null,null,-1),Z={colspan:"7"},$={class:"other-actions"},ee=w("Mod comment"),le=w("Reset password"),ae=w("Assign exam"),ne=g("td",null,"Email",-1),te=w("Change"),oe=g("td",null,"Enabled",-1),ie=w("Disable"),de=w("Enable"),se=g("td",null,"Added",-1),re=g("td",null,"Class",-1),ue=g("td",null,"Invite by",-1),me=w("View"),fe=g("td",null,"Uploaded",-1),ce=w("Add"),pe=g("td",null,"Downloaded",-1),be=w("Add"),ge=g("td",null,"Bonus",-1),ve=w("Add"),_e=g("div",{class:"card-header"},[g("span",null,"Exam on the way")],-1),he={class:"table-base-info"},we=g("td",null,"Name",-1),xe=g("td",null,"Created at",-1),Ie=g("td",null,"Exam time",-1),De=g("td",null,"Status",-1),ye=g("td",null,"Action",-1),Ce=w("Remove"),Ve=w("Pass !"),ke=w("Not Pass !");C();const Ue=L(((e,l,a,n,t,o)=>{const i=m("el-button"),d=m("el-popconfirm"),s=m("el-card"),r=m("el-col"),u=m("el-table-column"),b=m("el-tag"),h=m("el-table"),w=m("el-row"),x=m("DialogAssignExam"),I=m("DialogViewInviteInfo"),D=m("DialogDisableUser"),y=m("DialogModComment"),C=m("DialogResetPassword"),U=f("loading");return c(),p(_,null,[v(g("div",N,[g(s,null,{header:L((()=>[J])),default:L((()=>[g("table",K,[Q,g("tr",null,[W,g("td",null,V(e.baseInfo.username),1),X,g("td",Z,[g("div",$,[g(i,{type:"primary",size:"mini",onClick:n.handleGetModComment},{default:L((()=>[ee])),_:1},8,["onClick"]),g(i,{type:"primary",size:"mini",onClick:n.handleResetPassword},{default:L((()=>[le])),_:1},8,["onClick"]),g(i,{type:"primary",size:"mini",onClick:n.handleAssignExam},{default:L((()=>[ae])),_:1},8,["onClick"])])])]),g("tr",null,[ne,g("td",null,V(e.baseInfo.email),1),g("td",null,[g(i,{size:"mini"},{default:L((()=>[te])),_:1})])]),g("tr",null,[oe,g("td",null,V(e.baseInfo.enabled),1),g("td",null,[e.baseInfo.enabled&&"yes"==e.baseInfo.enabled?(c(),p(i,{key:0,size:"mini",onClick:n.handleDisableUser},{default:L((()=>[ie])),_:1},8,["onClick"])):k("",!0),e.baseInfo.enabled&&"no"==e.baseInfo.enabled?(c(),p(d,{key:1,title:"Confirm Enable ?",onConfirm:n.handleEnableUser},{reference:L((()=>[g(i,{size:"mini"},{default:L((()=>[de])),_:1})])),_:1},8,["onConfirm"])):k("",!0)])]),g("tr",null,[se,g("td",null,V(e.baseInfo.added),1)]),g("tr",null,[re,g("td",null,V(e.baseInfo.class_text),1)]),g("tr",null,[ue,g("td",null,V(e.baseInfo.inviter&&e.baseInfo.inviter.username),1),g("td",null,[g(i,{size:"mini",onClick:n.handleViewInviteInfo},{default:L((()=>[me])),_:1},8,["onClick"])])]),g("tr",null,[fe,g("td",null,V(e.baseInfo.uploaded_text),1),g("td",null,[g(i,{size:"mini"},{default:L((()=>[ce])),_:1})])]),g("tr",null,[pe,g("td",null,V(e.baseInfo.downloaded_text),1),g("td",null,[g(i,{size:"mini"},{default:L((()=>[be])),_:1})])]),g("tr",null,[ge,g("td",null,V(e.baseInfo.bonus),1),g("td",null,[g(i,{size:"mini"},{default:L((()=>[ve])),_:1})])])])])),_:1}),e.examInfo?(c(),p(s,{key:0},{header:L((()=>[_e])),default:L((()=>[g(w,null,{default:L((()=>[g(r,{span:12},{default:L((()=>[g("table",he,[g("tr",null,[we,g("td",null,V(e.examInfo.exam&&e.examInfo.exam.name),1)]),g("tr",null,[xe,g("td",null,V(e.examInfo.created_at),1)]),g("tr",null,[Ie,g("td",null,V(e.examInfo.begin)+" ~ "+V(e.examInfo.end),1)]),g("tr",null,[De,g("td",null,V(e.examInfo.status_text),1)]),g("tr",null,[ye,g("td",null,[g(d,{title:"Confirm Remove ?",onConfirm:l[1]||(l[1]=l=>n.handleRemoveExam(e.examInfo.id))},{reference:L((()=>[g(i,{type:"danger",size:"small"},{default:L((()=>[Ce])),_:1})])),_:1})])])])])),_:1}),g(r,{span:12},{default:L((()=>[g(h,{data:e.examInfo.progress_formatted},{default:L((()=>[g(u,{prop:"name",label:"Index"}),g(u,{prop:"require_value_formatted",label:"Require"}),g(u,{prop:"current_value_formatted",label:"Current"}),g(u,{prop:"result",label:"Result"},{default:L((e=>[e.row.passed?(c(),p(b,{key:0,type:"success"},{default:L((()=>[Ve])),_:1})):k("",!0),e.row.passed?k("",!0):(c(),p(b,{key:1,type:"danger"},{default:L((()=>[ke])),_:1}))])),_:1})])),_:1},8,["data"])])),_:1})])),_:1})])),_:1})):k("",!0)],512),[[U,e.loading]]),g(x,{ref:"assignExam",reload:n.fetchPageData},null,8,["reload"]),g(I,{ref:"viewInviteInfo"},null,512),g(D,{ref:"disableUser",reload:n.fetchPageData},null,8,["reload"]),g(y,{ref:"modComment"},null,512),g(y,{ref:"modComment"},null,512),g(C,{ref:"resetPassword"},null,512)],64)}));G.render=Ue,G.__scopeId="data-v-05f1091e";export default G;
