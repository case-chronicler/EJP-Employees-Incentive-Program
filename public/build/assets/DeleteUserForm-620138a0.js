import{o as m,f as h,r as x,D as $,m as C,E as B,h as D,c as S,a,w as l,i as p,P as y,b as e,q as w,n as b,g as V,U as E,A as g,T as U,H as A,d as _,u as i,V as T}from"./app-e664243e.js";import{_ as N,a as P}from"./InputLabel-7ced669e.js";import{_ as K}from"./TextInput-c484e117.js";const M=["type"],v={__name:"DangerButton",props:{type:{type:String,default:"submit"}},setup(t){return(n,s)=>(m(),h("button",{type:t.type,class:"inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"},[x(n.$slots,"default")],8,M))}},O={class:"fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50","scroll-region":""},W=e("div",{class:"absolute inset-0 bg-gray-500 opacity-75"},null,-1),z=[W],F={__name:"Modal",props:{show:{type:Boolean,default:!1},maxWidth:{type:String,default:"2xl"},closeable:{type:Boolean,default:!0}},emits:["close"],setup(t,{emit:n}){const s=t,o=n;$(()=>s.show,()=>{s.show?document.body.style.overflow="hidden":document.body.style.overflow=null});const d=()=>{s.closeable&&o("close")},r=u=>{u.key==="Escape"&&s.show&&d()};C(()=>document.addEventListener("keydown",r)),B(()=>{document.removeEventListener("keydown",r),document.body.style.overflow=null});const c=D(()=>({sm:"sm:max-w-sm",md:"sm:max-w-md",lg:"sm:max-w-lg",xl:"sm:max-w-xl","2xl":"sm:max-w-2xl"})[s.maxWidth]);return(u,f)=>(m(),S(E,{to:"body"},[a(w,{"leave-active-class":"duration-200"},{default:l(()=>[p(e("div",O,[a(w,{"enter-active-class":"ease-out duration-300","enter-from-class":"opacity-0","enter-to-class":"opacity-100","leave-active-class":"ease-in duration-200","leave-from-class":"opacity-100","leave-to-class":"opacity-0"},{default:l(()=>[p(e("div",{class:"fixed inset-0 transform transition-all",onClick:d},z,512),[[y,t.show]])]),_:1}),a(w,{"enter-active-class":"ease-out duration-300","enter-from-class":"opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95","enter-to-class":"opacity-100 translate-y-0 sm:scale-100","leave-active-class":"ease-in duration-200","leave-from-class":"opacity-100 translate-y-0 sm:scale-100","leave-to-class":"opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"},{default:l(()=>[p(e("div",{class:b(["mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto",c.value])},[t.show?x(u.$slots,"default",{key:0}):V("",!0)],2),[[y,t.show]])]),_:3})],512),[[y,t.show]])]),_:3})]))}},I=["type"],L={__name:"SecondaryButton",props:{type:{type:String,default:"button"}},setup(t){return(n,s)=>(m(),h("button",{type:t.type,class:"inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"},[x(n.$slots,"default")],8,I))}},j={class:"space-y-6"},q=e("header",null,[e("h2",{class:"text-lg font-medium text-gray-900"},"Delete Account"),e("p",{class:"mt-1 text-sm text-gray-600"}," Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain. ")],-1),H={class:"p-6"},G=e("h2",{class:"text-lg font-medium text-gray-900"}," Are you sure you want to delete your account? ",-1),J=e("p",{class:"mt-1 text-sm text-gray-600"}," Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account. ",-1),Q={class:"mt-6"},R={class:"mt-6 flex justify-end"},ee={__name:"DeleteUserForm",setup(t){const n=g(!1),s=g(null),o=U({password:""}),d=()=>{n.value=!0,A(()=>s.value.focus())},r=()=>{o.delete(route("profile.destroy"),{preserveScroll:!0,onSuccess:()=>c(),onError:()=>s.value.focus(),onFinish:()=>o.reset()})},c=()=>{n.value=!1,o.reset()};return(u,f)=>(m(),h("section",j,[q,a(v,{onClick:d},{default:l(()=>[_("Delete Account")]),_:1}),a(F,{show:n.value,onClose:c},{default:l(()=>[e("div",H,[G,J,e("div",Q,[a(N,{for:"password",value:"Password",class:"sr-only"}),a(K,{id:"password",ref_key:"passwordInput",ref:s,modelValue:i(o).password,"onUpdate:modelValue":f[0]||(f[0]=k=>i(o).password=k),type:"password",class:"mt-1 block w-3/4",placeholder:"Password",onKeyup:T(r,["enter"])},null,8,["modelValue","onKeyup"]),a(P,{message:i(o).errors.password,class:"mt-2"},null,8,["message"])]),e("div",R,[a(L,{onClick:c},{default:l(()=>[_(" Cancel ")]),_:1}),a(v,{class:b(["ml-3",{"opacity-25":i(o).processing}]),disabled:i(o).processing,onClick:r},{default:l(()=>[_(" Delete Account ")]),_:1},8,["class","disabled"])])])]),_:1},8,["show"])]))}};export{ee as default};
