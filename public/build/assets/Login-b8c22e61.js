import{h as x,i as k,v as y,o as d,f as w,T as V,c as f,w as i,a as t,u as s,Z as $,b as o,d as c,j as p,t as B,g,n as C,e as N}from"./app-e664243e.js";import{_ as R}from"./GuestLayout-aa94b52a.js";import{_,a as h}from"./InputLabel-7ced669e.js";import{_ as S}from"./PrimaryButton-5191b921.js";import{_ as b}from"./TextInput-c484e117.js";import"./ApplicationLogo-96ca07fe.js";import"./_plugin-vue_export-helper-c27b6911.js";const U=["value"],q={__name:"Checkbox",props:{checked:{type:[Array,Boolean],default:!1},value:{default:null}},emits:["update:checked"],setup(l,{emit:e}){const m=e,n=l,a=x({get(){return n.checked},set(r){m("update:checked",r)}});return(r,u)=>k((d(),w("input",{type:"checkbox",value:l.value,"onUpdate:modelValue":u[0]||(u[0]=v=>a.value=v),class:"rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"},null,8,U)),[[y,a.value]])}},L={class:"mb-8"},P={class:"float-right"},j=o("span",{class:"clear-both"},null,-1),D={key:0,class:"mb-4 font-medium text-sm text-green-600"},E=["onSubmit"],F={class:"mt-4"},M={class:"block mt-4"},T={class:"flex items-center"},z=o("span",{class:"ml-2 text-sm text-gray-600"},"Remember me",-1),A={class:"flex items-center justify-end mt-4"},Q={__name:"Login",props:{canResetPassword:Boolean,status:String},setup(l){const e=V({email:"",password:"",remember:!1}),m=()=>{e.post(route("login"),{onFinish:()=>e.reset("password")})};return(n,a)=>(d(),f(R,null,{default:i(()=>[t(s($),{title:"Log in"}),o("div",L,[o("span",P,[t(s(p),{href:n.route("register"),class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:i(()=>[c(" Register ")]),_:1},8,["href"])]),j]),l.status?(d(),w("div",D,B(l.status),1)):g("",!0),o("form",{onSubmit:N(m,["prevent"])},[o("div",null,[t(_,{for:"email",value:"Email"}),t(b,{id:"email",type:"email",class:"mt-1 block w-full",modelValue:s(e).email,"onUpdate:modelValue":a[0]||(a[0]=r=>s(e).email=r),required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),t(h,{class:"mt-2",message:s(e).errors.email},null,8,["message"])]),o("div",F,[t(_,{for:"password",value:"Password"}),t(b,{id:"password",type:"password",class:"mt-1 block w-full",modelValue:s(e).password,"onUpdate:modelValue":a[1]||(a[1]=r=>s(e).password=r),required:"",autocomplete:"current-password"},null,8,["modelValue"]),t(h,{class:"mt-2",message:s(e).errors.password},null,8,["message"])]),o("div",M,[o("label",T,[t(q,{name:"remember",checked:s(e).remember,"onUpdate:checked":a[2]||(a[2]=r=>s(e).remember=r)},null,8,["checked"]),z])]),o("div",A,[l.canResetPassword?(d(),f(s(p),{key:0,href:n.route("password.request"),class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:i(()=>[c(" Forgot your password? ")]),_:1},8,["href"])):g("",!0),t(S,{class:C(["ml-4",{"opacity-25":s(e).processing}]),disabled:s(e).processing},{default:i(()=>[c(" Log in ")]),_:1},8,["class","disabled"])])],40,E)]),_:1}))}};export{Q as default};
