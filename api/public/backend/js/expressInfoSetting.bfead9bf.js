"use strict";(self["webpackChunkscui"]=self["webpackChunkscui"]||[]).push([[5764],{2377:function(e,t,l){l.r(t),l.d(t,{default:function(){return n}});var a=l(6252);const o=(0,a._)("div",{style:{"font-size":"12px"}},[(0,a.Uk)(" 本系统集成该接口查询物流信息，可点击"),(0,a._)("a",{href:"https://market.aliyun.com/products/57126001/cmapi021863.html?spm=5176.730005.result.2.32ab3524s45loN&innerSource=search_%E5%BF%AB%E9%80%92%E6%9F%A5%E8%AF%A2#sku=yuncode1586300000",target:"\r\n\t\t\t\t\t\t\t\t",style:{color:"#409eff"}}," 阿里云市场物流查询接口 "),(0,a.Uk)(" 开通 ")],-1),s=(0,a.Uk)("保存");function u(e,t,l,u,m,p){const r=(0,a.up)("el-input"),n=(0,a.up)("el-form-item"),d=(0,a.up)("el-button"),f=(0,a.up)("el-form"),i=(0,a.up)("el-tab-pane"),c=(0,a.up)("el-tabs"),_=(0,a.up)("el-card"),w=(0,a.up)("el-main");return(0,a.wg)(),(0,a.j4)(w,null,{default:(0,a.w5)((()=>[(0,a.Wm)(_,{shadow:"never"},{default:(0,a.w5)((()=>[(0,a.Wm)(c,{modelValue:m.activeName,"onUpdate:modelValue":t[3]||(t[3]=e=>m.activeName=e)},{default:(0,a.w5)((()=>[(0,a.Wm)(i,{label:"物流信息查询配置",name:"first"},{default:(0,a.w5)((()=>[(0,a.Wm)(f,{ref:"form",model:m.form,"label-width":"160px"},{default:(0,a.w5)((()=>[(0,a.Wm)(n,{label:"AppKey",style:{width:"700px"}},{default:(0,a.w5)((()=>[(0,a.Wm)(r,{modelValue:m.form.app_key,"onUpdate:modelValue":t[0]||(t[0]=e=>m.form.app_key=e)},null,8,["modelValue"])])),_:1}),(0,a.Wm)(n,{label:"AppSecret",style:{width:"700px"}},{default:(0,a.w5)((()=>[(0,a.Wm)(r,{modelValue:m.form.app_secret,"onUpdate:modelValue":t[1]||(t[1]=e=>m.form.app_secret=e)},null,8,["modelValue"])])),_:1}),(0,a.Wm)(n,{label:"AppCode",style:{width:"700px"}},{default:(0,a.w5)((()=>[(0,a.Wm)(r,{modelValue:m.form.app_code,"onUpdate:modelValue":t[2]||(t[2]=e=>m.form.app_code=e)},null,8,["modelValue"])])),_:1}),(0,a.Wm)(n,null,{default:(0,a.w5)((()=>[o])),_:1}),(0,a.Wm)(n,null,{default:(0,a.w5)((()=>[(0,a.Wm)(d,{type:"primary",onClick:p.Submit,style:{width:"200px"}},{default:(0,a.w5)((()=>[s])),_:1},8,["onClick"])])),_:1})])),_:1},8,["model"])])),_:1})])),_:1},8,["modelValue"])])),_:1})])),_:1})}var m={name:"expressInfoSettingIndex",data(){return{activeName:"first",form:{app_key:"",app_secret:"",app_code:""}}},mounted(){this.getBaseConf()},methods:{async getBaseConf(){let e=await this.$API.expressInfoSetting.config.get();this.form=e.data.express},async Submit(){let e=await this.$API.expressInfoSetting.save.post(this.form);0==e.code?this.$message.success(e.msg):this.$message.error(e.msg)}}},p=l(3744);const r=(0,p.Z)(m,[["render",u]]);var n=r}}]);