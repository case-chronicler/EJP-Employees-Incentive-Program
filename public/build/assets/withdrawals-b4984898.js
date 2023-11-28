import{Q as b,T as F,h as f,m as N,o as l,f as d,b as e,a as r,t as m,u as o,g as x,w as h,p as B,e as v,n as j,z as k,I as C,c as M,F as q,Z as V,d as R,l as W,j as D}from"./app-de2ade85.js";import{a as S,_ as T}from"./AuthenticatedLayout-d43f5e69.js";import{_ as E}from"./PrimaryButton-c9251983.js";import{u as P,i as O,S as z}from"./sweetalert2.all-ed79b04f.js";import{_ as U,a as Q,b as I}from"./TextInput-11e883d8.js";import{_ as L}from"./user-74d2988d.js";import"./ApplicationLogo-08ead181.js";import"./_plugin-vue_export-helper-c27b6911.js";const Y={class:"relative z-10","aria-labelledby":"modal-title",role:"dialog","aria-modal":"true"},Z=e("div",{class:"fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"},null,-1),G={class:"fixed inset-0 z-10 w-screen overflow-y-auto"},H={class:"flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"},J={class:"relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"},K={class:"bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4"},X={class:"sm:flex sm:items-start"},ee={class:"mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-gray-100 sm:mx-0 sm:h-10 sm:w-10"},te=e("div",{class:"mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left"},[e("h3",{class:"text-base font-semibold leading-6 text-gray-900",id:"modal-title"}," Request withdrawal "),e("div",{class:"mt-2"},[e("p",{class:"text-sm text-gray-500"}," Please review the details below before sending the invitation. Once invited, your new employee will be granted the selected roles. This action cannot be undone! ")])],-1),se={class:"mt-10 sm:mx-auto sm:w-full sm:max-w-sm"},ae={class:"text-red-800 text-sm"},oe={id:"fundsRequestForm",class:"space-y-6",action:"#",method:"POST"},re={class:"mt-3 text-center sm:text-left"},ne=e("span",{class:"font-semibold leading-6 text-gray-400 text-sm"}," Balance (USD): ",-1),le={class:"font-semibold leading-6 text-gray-800 text-sm ml-2"},ie={key:0,class:"font-semibold leading-6 text-red-800 text-sm mt-2"},de={class:"mt-2"},ce=e("div",null,null,-1),ue={class:"flex items-center gap-4"},me={key:0,class:"text-sm text-gray-600"},_e={class:"bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"},fe=["onClick","disabled"],he={__name:"RequestFunds",props:{allPositions:Array},setup(w){const p=b().props.auth.user,u=b().props.userBalance,_=P(),s=F({amount:"",request_sent_by:p.user_id}),c=f(()=>!(!s.amount||Number(s.amount)<0||Number(s.amount)>u)),y=()=>{if(s.processing||!c)return;(document.querySelector("#fundsRequestForm")??!1)&&s.post(route("withdrawal_requests.store"),{onSuccess:()=>{s.reset(),_.closeModal(),z.fire({title:"Withdrawal request sent!",text:"Your request for fund release has been sent to the attorney",icon:"success"})}})};return N(()=>{O()}),(g,t)=>(l(),d("div",Y,[Z,e("div",G,[e("div",H,[e("div",J,[e("div",K,[e("div",X,[e("div",ee,[r(S)]),te]),e("div",se,[e("p",ae,m(o(s).errors.amount)+" "+m(o(s).errors.general),1),e("form",oe,[e("div",re,[ne,e("span",le,m(g.$page.props.userBalance),1),c.value?x("",!0):(l(),d("div",ie," Please enter a valid amount "))]),e("div",null,[r(U,{class:"block text-sm font-medium leading-6 text-gray-900",for:"Amount",value:"Amount"}),e("div",de,[r(Q,{id:"Amount",type:"number",class:"block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6",modelValue:o(s).amount,"onUpdate:modelValue":t[0]||(t[0]=a=>o(s).amount=a),required:""},null,8,["modelValue"])]),r(I,{class:"mt-2",message:o(s).errors.amount},null,8,["message"])]),ce,e("div",ue,[r(B,{"enter-from-class":"opacity-0","leave-to-class":"opacity-0",class:"transition ease-in-out"},{default:h(()=>[o(s).recentlySuccessful?(l(),d("p",me," Request sent ")):x("",!0)]),_:1})])])])]),e("div",_e,[e("button",{onClick:v(y,["prevent"]),disabled:!c.value||o(s).processing,type:"button",class:j(["inline-flex w-full justify-center rounded-md bg-gray-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 sm:ml-3 sm:w-auto",{"hover:cursor-not-allowed":!c.value||o(s).processing}])}," Send request ",10,fe),e("button",{onClick:t[1]||(t[1]=v((...a)=>o(_).closeModal&&o(_).closeModal(...a),["prevent"])),type:"button",class:"mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"}," Cancel ")])])])])]))}};const xe=e("div",{class:"flex items-center space-x-4"},[e("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," Withdrawals ")],-1),pe={class:"my-16"},ge={class:"max-w-7xl mx-auto px-6 lg:px-8 space-y-14"},ye={key:0,class:"mb-4"},be=e("div",{class:"clear-both"},null,-1),ve={class:"rounded-lg"},we={key:0,class:"grid grid-cols-1 gap-4"},$e={class:"rounded-xl border p-5 shadow-md w-full bg-white"},ke={class:"flex w-full items-center justify-between mb-6"},qe={class:"flex items-center space-x-3"},Re={class:"h-8 w-8 rounded-full flex items-center justify-center shadow-lg"},je={class:"text-lg font-bold text-slate-700"},Se={class:"flex items-center justify-center space-x-4"},Pe={class:"text-base text-neutral-500 inline-flex items-center"},Ae={class:"flex items-center justify-between text-slate-500"},Fe={class:"flex space-x-4 md:space-x-8"},Ne=e("span",{class:"middle none center rounded-lg border border-cyan-800 py-1.5 px-3 font-sans text-xs font-bold uppercase text-cyan-500 transition-all hover:opacity-75 focus:ring focus:ring-cyan-200 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none","data-ripple-dark":"true"}," View transaction ",-1),Be={class:"my-8"},Ce={class:"flex float-right"},Me=["href"],Ve=["href"],We=e("div",{class:"clear-both"},null,-1),Le={__name:"withdrawals",props:{allWithdrawals:Object},setup(w){const p=P(),u=w,_=b().props.isEmployeeAnAttorney;k(""),k([]);const s=C({addRole:null});f(()=>{const t=u.users_and_employees;if(s.addRole===null)return t;for(const a in s.addRole)if(Object.hasOwnProperty.call(s.addRole,a)){const i=s.addRole[a];t[a]=i}return t});const c=f(()=>{var t;return((t=u.allWithdrawals)==null?void 0:t.data)??[]}),y=f(()=>{var a;let t=((a=u.allWithdrawals)==null?void 0:a.links)??!1;return t&&(t=t.find(i=>i.label=="Next &raquo;")),t}),g=f(()=>{var a;let t=((a=u.allWithdrawals)==null?void 0:a.links)??!1;return t&&(t=t.find(i=>i.label=="&laquo; Previous")),t});return(t,a)=>(l(),d(q,null,[o(p).currentModal==="REQUEST_FUNDS"?(l(),M(he,{key:0})):x("",!0),r(o(V),{title:"Withdrawals"}),r(T,null,{header:h(()=>[xe]),default:h(()=>{var i,$;return[e("div",pe,[e("div",ge,[o(_)?x("",!0):(l(),d("div",ye,[r(E,{onClick:a[0]||(a[0]=v(n=>o(p).openRequestFundsModal(),["prevent"])),type:"button",class:"mx-2 float-right"},{default:h(()=>[R("request funds")]),_:1}),be])),e("div",ve,[c.value.length>0?(l(),d("div",we,[(l(!0),d(q,null,W(c.value,(n,A)=>(l(),d("div",{class:"flex items-center justify-center mb-4",key:A},[e("div",$e,[e("div",ke,[e("div",qe,[e("div",Re,[r(L,{w_class:"w-5",h_class:"h-5"})]),e("div",je,m(n.user_email),1)]),e("div",Se,[e("div",Pe,[r(S,{class:"mr-1"}),R(" "+m(n.withdrawal_request_amount),1)]),e("div",{class:j(["rounded-2xl border bg-neutral-100 px-2 py-1 text-xs font-semibold uppercase",{"text-amber-500":n.withdrawal_request_status=="pending","text-red-700":n.withdrawal_request_status=="failed"||n.withdrawal_request_status=="rejected","text-green-700":n.withdrawal_request_status=="success"}])},m(n.withdrawal_request_status),3)])]),e("div",null,[e("div",Ae,[e("div",Fe,[r(o(D),{href:t.route("withdrawal_requests.show",n.withdrawal_request_link_id),class:"flex cursor-pointer items-center transition hover:text-slate-600"},{default:h(()=>[Ne]),_:2},1032,["href"])])])])])]))),128))])):x("",!0),e("div",Be,[e("div",Ce,[e("a",{href:((i=g.value)==null?void 0:i.url)??"#",class:"flex items-center justify-center px-4 h-10 text-base font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"}," Previous ",8,Me),e("a",{href:(($=y.value)==null?void 0:$.url)??"#",class:"flex items-center justify-center px-4 h-10 ms-3 text-base font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"}," Next ",8,Ve)]),We])])])])]}),_:1})],64))}};export{Le as default};