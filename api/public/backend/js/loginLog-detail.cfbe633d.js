"use strict";(self["webpackChunkscui"]=self["webpackChunkscui"]||[]).push([[579],{3899:function(e,l,o){o.r(l),o.d(l,{default:function(){return t}});var d=o(6252);function a(e,l,o,a,m,u){const r=(0,d.up)("el-input"),t=(0,d.up)("el-form-item"),i=(0,d.up)("el-image"),s=(0,d.up)("el-form"),n=(0,d.up)("el-dialog");return(0,d.wg)(),(0,d.j4)(n,{title:m.titleMap[m.mode],modelValue:m.visible,"onUpdate:modelValue":l[8]||(l[8]=e=>m.visible=e),width:600,"destroy-on-close":"",onClosed:l[9]||(l[9]=l=>e.$emit("closed"))},{default:(0,d.w5)((()=>[(0,d.Wm)(s,{model:m.form,disabled:"show"===m.mode,ref:"dialogForm","label-width":"100px","label-position":"left"},{default:(0,d.w5)((()=>[(0,d.Wm)(t,{label:"id",prop:"ID"},{default:(0,d.w5)((()=>[(0,d.Wm)(r,{modelValue:m.form.id,"onUpdate:modelValue":l[0]||(l[0]=e=>m.form.id=e),disabled:""},null,8,["modelValue"])])),_:1}),(0,d.Wm)(t,{label:"商品的id",prop:"user_id"},{default:(0,d.w5)((()=>[(0,d.Wm)(r,{modelValue:m.form.goods_id,"onUpdate:modelValue":l[1]||(l[1]=e=>m.form.goods_id=e),disabled:""},null,8,["modelValue"])])),_:1}),(0,d.Wm)(t,{label:"商品名",prop:"user_name"},{default:(0,d.w5)((()=>[(0,d.Wm)(r,{modelValue:m.form.goods_name,"onUpdate:modelValue":l[2]||(l[2]=e=>m.form.goods_name=e),disabled:""},null,8,["modelValue"])])),_:1}),(0,d.Wm)(t,{label:"商品图片",prop:"goods_image"},{default:(0,d.w5)((()=>[(0,d.Wm)(i,{src:m.form.goods_image,style:{height:"96px",width:"96px"}},null,8,["src"])])),_:1}),(0,d.Wm)(t,{label:"商品规格",prop:"hash_key"},{default:(0,d.w5)((()=>[(0,d.Wm)(r,{modelValue:m.form.rule,"onUpdate:modelValue":l[3]||(l[3]=e=>m.form.rule=e),disabled:""},null,8,["modelValue"])])),_:1}),(0,d.Wm)(t,{label:"数量",prop:"order_time"},{default:(0,d.w5)((()=>[(0,d.Wm)(r,{modelValue:m.form.num,"onUpdate:modelValue":l[4]||(l[4]=e=>m.form.num=e),disabled:""},null,8,["modelValue"])])),_:1}),(0,d.Wm)(t,{label:"单价",prop:"order_id"},{default:(0,d.w5)((()=>[(0,d.Wm)(r,{modelValue:m.form.price,"onUpdate:modelValue":l[5]||(l[5]=e=>m.form.price=e),disabled:""},null,8,["modelValue"])])),_:1}),(0,d.Wm)(t,{label:"实际支付金额",prop:"goods_id"},{default:(0,d.w5)((()=>[(0,d.Wm)(r,{modelValue:m.form.real_pay_amount,"onUpdate:modelValue":l[6]||(l[6]=e=>m.form.real_pay_amount=e),disabled:""},null,8,["modelValue"])])),_:1}),(0,d.Wm)(t,{label:"实际支付哈希币",prop:"goods_name"},{default:(0,d.w5)((()=>[(0,d.Wm)(r,{modelValue:m.form.real_pay_integral,"onUpdate:modelValue":l[7]||(l[7]=e=>m.form.real_pay_integral=e),disabled:""},null,8,["modelValue"])])),_:1})])),_:1},8,["model","disabled"])])),_:1},8,["title","modelValue"])}var m={name:"integralOrderDetail",emits:["success","closed"],data(){return{mode:"detail",titleMap:{detail:"订单详情"},visible:!1,form:{}}},mounted(){},methods:{open(e){return this.form=e,this.visible=!0,this}}},u=o(3744);const r=(0,u.Z)(m,[["render",a]]);var t=r}}]);