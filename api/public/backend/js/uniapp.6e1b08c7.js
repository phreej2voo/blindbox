"use strict";(self["webpackChunkscui"]=self["webpackChunkscui"]||[]).push([[4410],{1798:function(e,a,l){l.r(a),l.d(a,{default:function(){return o}});var t=l(6252);const p=(0,t.Uk)("保存");function u(e,a,l,u,i,m){const n=(0,t.up)("el-input"),o=(0,t.up)("el-form-item"),d=(0,t.up)("el-button"),r=(0,t.up)("el-form"),s=(0,t.up)("el-tab-pane"),f=(0,t.up)("el-tabs"),_=(0,t.up)("el-card"),c=(0,t.up)("el-main");return(0,t.wg)(),(0,t.j4)(c,null,{default:(0,t.w5)((()=>[(0,t.Wm)(_,{shadow:"never"},{default:(0,t.w5)((()=>[(0,t.Wm)(f,{modelValue:i.activeName,"onUpdate:modelValue":a[4]||(a[4]=e=>i.activeName=e)},{default:(0,t.w5)((()=>[(0,t.Wm)(s,{label:"uniapp配置",name:"first"},{default:(0,t.w5)((()=>[(0,t.Wm)(r,{ref:"form",model:i.form,"label-width":"160px"},{default:(0,t.w5)((()=>[(0,t.Wm)(o,{label:"appid",style:{width:"700px"}},{default:(0,t.w5)((()=>[(0,t.Wm)(n,{modelValue:i.form.uniapp_appid,"onUpdate:modelValue":a[0]||(a[0]=e=>i.form.uniapp_appid=e)},null,8,["modelValue"])])),_:1}),(0,t.Wm)(o,{label:"apiKey",style:{width:"700px"}},{default:(0,t.w5)((()=>[(0,t.Wm)(n,{modelValue:i.form.uniapp_api_key,"onUpdate:modelValue":a[1]||(a[1]=e=>i.form.uniapp_api_key=e)},null,8,["modelValue"])])),_:1}),(0,t.Wm)(o,{label:"apiSecret",style:{width:"700px"}},{default:(0,t.w5)((()=>[(0,t.Wm)(n,{modelValue:i.form.uniapp_api_secret,"onUpdate:modelValue":a[2]||(a[2]=e=>i.form.uniapp_api_secret=e)},null,8,["modelValue"])])),_:1}),(0,t.Wm)(o,{label:"云函数URL化",style:{width:"700px"}},{default:(0,t.w5)((()=>[(0,t.Wm)(n,{modelValue:i.form.uniapp_cloud_url,"onUpdate:modelValue":a[3]||(a[3]=e=>i.form.uniapp_cloud_url=e)},null,8,["modelValue"])])),_:1}),(0,t.Wm)(o,{style:{"margin-top":"50px"}},{default:(0,t.w5)((()=>[(0,t.Wm)(d,{type:"primary",onClick:m.Submit,style:{width:"200px"}},{default:(0,t.w5)((()=>[p])),_:1},8,["onClick"])])),_:1})])),_:1},8,["model"])])),_:1})])),_:1},8,["modelValue"])])),_:1})])),_:1})}var i={name:"uniapp",data(){return{activeName:"first",form:{uniapp_appid:"",uniapp_api_key:"",uniapp_api_secret:"",uniapp_cloud_url:""}}},mounted(){this.getBaseConf()},methods:{async getBaseConf(){let e=await this.$API.appletSetting.getUniapp.get();this.form=e.data.uniapp},async Submit(){let e=await this.$API.appletSetting.saveUniapp.post(this.form);0==e.code?this.$message.success(e.msg):this.$message.error(e.msg)}}},m=l(3744);const n=(0,m.Z)(i,[["render",u]]);var o=n}}]);