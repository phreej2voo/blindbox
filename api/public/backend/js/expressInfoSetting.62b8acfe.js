"use strict";(self["webpackChunkscui"]=self["webpackChunkscui"]||[]).push([[2739],{6024:function(e,t,l){l.r(t),l.d(t,{default:function(){return p}});var a=l(7267);const o=(0,a.Lk)("div",{style:{"font-size":"12px"}},[(0,a.eW)(" 本系统集成该接口查询物流信息，可点击"),(0,a.Lk)("a",{href:"https://market.aliyun.com/products/57126001/cmapi021863.html?spm=5176.730005.result.2.32ab3524s45loN&innerSource=search_%E5%BF%AB%E9%80%92%E6%9F%A5%E8%AF%A2#sku=yuncode1586300000",target:"\n\t\t\t\t\t\t\t\t",style:{color:"#409eff"}}," 阿里云市场物流查询接口 "),(0,a.eW)(" 开通 ")],-1),s=(0,a.eW)("保存");function u(e,t,l,u,n,r){const d=(0,a.g2)("el-input"),p=(0,a.g2)("el-form-item"),m=(0,a.g2)("el-button"),f=(0,a.g2)("el-form"),i=(0,a.g2)("el-tab-pane"),c=(0,a.g2)("el-tabs"),b=(0,a.g2)("el-card"),k=(0,a.g2)("el-main");return(0,a.uX)(),(0,a.Wv)(k,null,{default:(0,a.k6)((()=>[(0,a.bF)(b,{shadow:"never"},{default:(0,a.k6)((()=>[(0,a.bF)(c,{modelValue:n.activeName,"onUpdate:modelValue":t[3]||(t[3]=e=>n.activeName=e)},{default:(0,a.k6)((()=>[(0,a.bF)(i,{label:"物流信息查询配置",name:"first"},{default:(0,a.k6)((()=>[(0,a.bF)(f,{ref:"form",model:n.form,"label-width":"160px"},{default:(0,a.k6)((()=>[(0,a.bF)(p,{label:"AppKey",style:{width:"700px"}},{default:(0,a.k6)((()=>[(0,a.bF)(d,{modelValue:n.form.app_key,"onUpdate:modelValue":t[0]||(t[0]=e=>n.form.app_key=e)},null,8,["modelValue"])])),_:1}),(0,a.bF)(p,{label:"AppSecret",style:{width:"700px"}},{default:(0,a.k6)((()=>[(0,a.bF)(d,{modelValue:n.form.app_secret,"onUpdate:modelValue":t[1]||(t[1]=e=>n.form.app_secret=e)},null,8,["modelValue"])])),_:1}),(0,a.bF)(p,{label:"AppCode",style:{width:"700px"}},{default:(0,a.k6)((()=>[(0,a.bF)(d,{modelValue:n.form.app_code,"onUpdate:modelValue":t[2]||(t[2]=e=>n.form.app_code=e)},null,8,["modelValue"])])),_:1}),(0,a.bF)(p,null,{default:(0,a.k6)((()=>[o])),_:1}),(0,a.bF)(p,null,{default:(0,a.k6)((()=>[(0,a.bF)(m,{type:"primary",onClick:r.Submit,style:{width:"200px"}},{default:(0,a.k6)((()=>[s])),_:1},8,["onClick"])])),_:1})])),_:1},8,["model"])])),_:1})])),_:1},8,["modelValue"])])),_:1})])),_:1})}var n={name:"expressInfoSettingIndex",data(){return{activeName:"first",form:{app_key:"",app_secret:"",app_code:""}}},mounted(){this.getBaseConf()},methods:{async getBaseConf(){let e=await this.$API.expressInfoSetting.config.get();this.form=e.data.express},async Submit(){let e=await this.$API.expressInfoSetting.save.post(this.form);0==e.code?this.$message.success(e.msg):this.$message.error(e.msg)}}},r=l(1456);const d=(0,r.A)(n,[["render",u]]);var p=d}}]);