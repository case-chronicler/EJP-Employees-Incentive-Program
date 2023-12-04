import{T as h,o as r,c as x,w as _,a as t,u as s,Z as b,f as d,b as o,t as v,g,e as V,d as w,j as U,n as N}from"./app-e664243e.js";import{_ as S}from"./GuestLayout-aa94b52a.js";import{_ as m,a as c}from"./InputLabel-7ced669e.js";import{_ as B}from"./PrimaryButton-5191b921.js";import{_ as u}from"./TextInput-c484e117.js";import{N as $}from"./notice_icon-83523e86.js";import"./ApplicationLogo-96ca07fe.js";import"./_plugin-vue_export-helper-c27b6911.js";const j={key:0,class:"my-6 flex flex-col items-center space-y-3"},q={class:"text-base font-semibold leading-6 text-gray-800"},C={key:0},P={key:1},E={class:"my-4"},R={class:"font-semibold text-red-900 text-sm"},I=["onSubmit"],T={class:"mt-4"},z={class:"mt-4"},A={class:"mt-4"},D={class:"flex items-center justify-center mt-4"},F={key:0,class:"text-red-900 font-semibold uppercase"},J={class:"flex items-center justify-end mt-4"},W={__name:"Register",props:{invite_link_is_valid:Boolean,invite_link:String,invitedUser_email:String,invitedUser_invite_already_processed:String},setup(l){const n=l;console.log(n.invite_link_is_valid);const e=h({name:"",email:"",password:"",password_confirmation:"",terms:!1,invite_link:(n==null?void 0:n.invite_link)??""}),y=()=>{e.post(route("register"),{onFinish:()=>e.reset("password","password_confirmation")})};return(k,i)=>(r(),x(S,null,{default:_(()=>{var f,p;return[t(s(b),{title:"Register"}),l.invite_link_is_valid?g("",!0):(r(),d("div",j,[t($),o("div",q,[l.invitedUser_invite_already_processed!==""?(r(),d("p",C,v(l.invitedUser_invite_already_processed),1)):(r(),d("p",P," It seems you were not invited by E-Justice Project. Only invited users can complete their registration here. Please use the invite link sent to your mailbox "))])])),o("div",E,[o("div",null,[o("p",R,v(s(e).errors.general),1)]),o("form",{onSubmit:V(y,["prevent"])},[o("div",null,[t(m,{for:"name",value:"Name"}),t(u,{id:"name",type:"text",class:"mt-1 block w-full",modelValue:s(e).name,"onUpdate:modelValue":i[0]||(i[0]=a=>s(e).name=a),required:"",autofocus:"",autocomplete:"name"},null,8,["modelValue"]),t(c,{class:"mt-2",message:s(e).errors.name},null,8,["message"])]),o("div",T,[t(m,{for:"email",value:"Email"}),t(u,{id:"email",type:"email",class:"mt-1 block w-full",modelValue:s(e).email,"onUpdate:modelValue":i[1]||(i[1]=a=>s(e).email=a),placeholder:l.invitedUser_email,required:"",autocomplete:"username"},null,8,["modelValue","placeholder"]),t(c,{class:"mt-2",message:s(e).errors.email},null,8,["message"])]),o("div",z,[t(m,{for:"password",value:"Password"}),t(u,{id:"password",type:"password",class:"mt-1 block w-full",modelValue:s(e).password,"onUpdate:modelValue":i[2]||(i[2]=a=>s(e).password=a),required:"",autocomplete:"new-password"},null,8,["modelValue"]),t(c,{class:"mt-2",message:s(e).errors.password},null,8,["message"])]),o("div",A,[t(m,{for:"password_confirmation",value:"Confirm Password"}),t(u,{id:"password_confirmation",type:"password",class:"mt-1 block w-full",modelValue:s(e).password_confirmation,"onUpdate:modelValue":i[3]||(i[3]=a=>s(e).password_confirmation=a),required:"",autocomplete:"new-password"},null,8,["modelValue"]),t(c,{class:"mt-2",message:s(e).errors.password_confirmation},null,8,["message"])]),o("div",D,[((p=(f=s(e))==null?void 0:f.errors)==null?void 0:p.invite_link)??!1?(r(),d("div",F," invalid invite link ")):g("",!0)]),o("div",J,[t(s(U),{href:k.route("login"),class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:_(()=>[w(" Already registered? ")]),_:1},8,["href"]),t(B,{class:N(["ml-4",{"opacity-25":s(e).processing}]),disabled:s(e).processing},{default:_(()=>[w(" Register ")]),_:1},8,["class","disabled"])])],40,I)])]}),_:1}))}};export{W as default};