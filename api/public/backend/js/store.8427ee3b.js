"use strict";(self["webpackChunkscui"]=self["webpackChunkscui"]||[]).push([[3962],{918:function(e,l,a){a.r(l),a.d(l,{default:function(){return _}});var t=a(7267);const o=(0,t.eW)("本地存储"),d=(0,t.eW)("阿里云OSS"),i=(0,t.eW)("腾讯云COS"),u=(0,t.eW)("七牛云KODO"),m=(0,t.eW)("保存"),n=(0,t.eW)("保存"),s=(0,t.eW)("保存"),b=(0,t.eW)("保存");function r(e,l,a,r,c,f){const p=(0,t.g2)("el-radio"),_=(0,t.g2)("el-form-item"),k=(0,t.g2)("el-button"),y=(0,t.g2)("el-form"),F=(0,t.g2)("el-tab-pane"),V=(0,t.g2)("el-input"),h=(0,t.g2)("el-tabs"),q=(0,t.g2)("el-card"),w=(0,t.g2)("el-main");return(0,t.uX)(),(0,t.Wv)(w,null,{default:(0,t.k6)((()=>[(0,t.bF)(q,{shadow:"never"},{default:(0,t.k6)((()=>[(0,t.bF)(h,{modelValue:c.activeName,"onUpdate:modelValue":l[20]||(l[20]=e=>c.activeName=e)},{default:(0,t.k6)((()=>[(0,t.bF)(F,{label:"默认开启引擎",name:"first"},{default:(0,t.k6)((()=>[(0,t.bF)(y,{ref:"form",model:c.baseForm,"label-width":"80px"},{default:(0,t.k6)((()=>[(0,t.bF)(_,{label:"默认引擎"},{default:(0,t.k6)((()=>[(0,t.bF)(p,{label:"local",modelValue:c.baseForm.store_way,"onUpdate:modelValue":l[0]||(l[0]=e=>c.baseForm.store_way=e)},{default:(0,t.k6)((()=>[o])),_:1},8,["modelValue"]),(0,t.bF)(p,{label:"aliyun",modelValue:c.baseForm.store_way,"onUpdate:modelValue":l[1]||(l[1]=e=>c.baseForm.store_way=e)},{default:(0,t.k6)((()=>[d])),_:1},8,["modelValue"]),(0,t.bF)(p,{label:"qcloud",modelValue:c.baseForm.store_way,"onUpdate:modelValue":l[2]||(l[2]=e=>c.baseForm.store_way=e)},{default:(0,t.k6)((()=>[i])),_:1},8,["modelValue"]),(0,t.bF)(p,{label:"qiniu",modelValue:c.baseForm.store_way,"onUpdate:modelValue":l[3]||(l[3]=e=>c.baseForm.store_way=e)},{default:(0,t.k6)((()=>[u])),_:1},8,["modelValue"])])),_:1}),(0,t.bF)(_,{style:{"margin-top":"50px"}},{default:(0,t.k6)((()=>[(0,t.bF)(k,{type:"primary",onClick:f.baseSubmit,style:{width:"200px"}},{default:(0,t.k6)((()=>[m])),_:1},8,["onClick"])])),_:1})])),_:1},8,["model"])])),_:1}),(0,t.bF)(F,{label:"阿里云OSS",name:"second"},{default:(0,t.k6)((()=>[(0,t.bF)(y,{ref:"form",model:c.aliform,"label-position":c.labelPosition,"label-width":"160px"},{default:(0,t.k6)((()=>[(0,t.bF)(_,{label:"空间域名 Domain",style:{width:"700px"}},{default:(0,t.k6)((()=>[(0,t.bF)(V,{modelValue:c.aliform.oss_domain,"onUpdate:modelValue":l[4]||(l[4]=e=>c.aliform.oss_domain=e)},null,8,["modelValue"])])),_:1}),(0,t.bF)(_,{label:"AccessKey ID",style:{width:"700px"}},{default:(0,t.k6)((()=>[(0,t.bF)(V,{modelValue:c.aliform.accesskey_id,"onUpdate:modelValue":l[5]||(l[5]=e=>c.aliform.accesskey_id=e)},null,8,["modelValue"])])),_:1}),(0,t.bF)(_,{label:"AccessKey Secret",style:{width:"700px"}},{default:(0,t.k6)((()=>[(0,t.bF)(V,{modelValue:c.aliform.accesskey_secret,"onUpdate:modelValue":l[6]||(l[6]=e=>c.aliform.accesskey_secret=e)},null,8,["modelValue"])])),_:1}),(0,t.bF)(_,{label:"Bucket",style:{width:"700px"}},{default:(0,t.k6)((()=>[(0,t.bF)(V,{modelValue:c.aliform.bucket,"onUpdate:modelValue":l[7]||(l[7]=e=>c.aliform.bucket=e)},null,8,["modelValue"])])),_:1}),(0,t.bF)(_,{label:"Endpoint",style:{width:"700px"}},{default:(0,t.k6)((()=>[(0,t.bF)(V,{modelValue:c.aliform.endpoint,"onUpdate:modelValue":l[8]||(l[8]=e=>c.aliform.endpoint=e)},null,8,["modelValue"])])),_:1}),(0,t.bF)(_,{style:{"margin-top":"50px"}},{default:(0,t.k6)((()=>[(0,t.bF)(k,{type:"primary",onClick:f.aliSubmit,style:{width:"200px"}},{default:(0,t.k6)((()=>[n])),_:1},8,["onClick"])])),_:1})])),_:1},8,["model","label-position"])])),_:1}),(0,t.bF)(F,{label:"腾讯云COS",name:"third"},{default:(0,t.k6)((()=>[(0,t.bF)(y,{ref:"form",model:c.qcloudform,"label-position":c.labelPosition,"label-width":"160px"},{default:(0,t.k6)((()=>[(0,t.bF)(_,{label:"腾讯云APPID",style:{width:"700px"}},{default:(0,t.k6)((()=>[(0,t.bF)(V,{modelValue:c.qcloudform.tencent_appid,"onUpdate:modelValue":l[9]||(l[9]=e=>c.qcloudform.tencent_appid=e)},null,8,["modelValue"])])),_:1}),(0,t.bF)(_,{label:"空间域名 Domain",style:{width:"700px"}},{default:(0,t.k6)((()=>[(0,t.bF)(V,{modelValue:c.qcloudform.tencent_domain,"onUpdate:modelValue":l[10]||(l[10]=e=>c.qcloudform.tencent_domain=e)},null,8,["modelValue"])])),_:1}),(0,t.bF)(_,{label:"SecretId",style:{width:"700px"}},{default:(0,t.k6)((()=>[(0,t.bF)(V,{modelValue:c.qcloudform.secret_id,"onUpdate:modelValue":l[11]||(l[11]=e=>c.qcloudform.secret_id=e)},null,8,["modelValue"])])),_:1}),(0,t.bF)(_,{label:"SecretKey",style:{width:"700px"}},{default:(0,t.k6)((()=>[(0,t.bF)(V,{modelValue:c.qcloudform.secret_key,"onUpdate:modelValue":l[12]||(l[12]=e=>c.qcloudform.secret_key=e)},null,8,["modelValue"])])),_:1}),(0,t.bF)(_,{label:"存储桶名称",style:{width:"700px"}},{default:(0,t.k6)((()=>[(0,t.bF)(V,{modelValue:c.qcloudform.tencent_bucket,"onUpdate:modelValue":l[13]||(l[13]=e=>c.qcloudform.tencent_bucket=e)},null,8,["modelValue"])])),_:1}),(0,t.bF)(_,{label:"所属地域",style:{width:"700px"}},{default:(0,t.k6)((()=>[(0,t.bF)(V,{modelValue:c.qcloudform.tencent_endpoint,"onUpdate:modelValue":l[14]||(l[14]=e=>c.qcloudform.tencent_endpoint=e)},null,8,["modelValue"])])),_:1}),(0,t.bF)(_,{style:{"margin-top":"50px"}},{default:(0,t.k6)((()=>[(0,t.bF)(k,{type:"primary",onClick:f.qcloudSubmit,style:{width:"200px"}},{default:(0,t.k6)((()=>[s])),_:1},8,["onClick"])])),_:1})])),_:1},8,["model","label-position"])])),_:1}),(0,t.bF)(F,{label:"七牛云KODO",name:"fourth"},{default:(0,t.k6)((()=>[(0,t.bF)(y,{ref:"form",model:c.qiniuform,"label-position":c.labelPosition,"label-width":"160px"},{default:(0,t.k6)((()=>[(0,t.bF)(_,{label:"空间域名 Domain",style:{width:"700px"}},{default:(0,t.k6)((()=>[(0,t.bF)(V,{modelValue:c.qiniuform.qiniu_domain,"onUpdate:modelValue":l[15]||(l[15]=e=>c.qiniuform.qiniu_domain=e)},null,8,["modelValue"])])),_:1}),(0,t.bF)(_,{label:"accessKey",style:{width:"700px"}},{default:(0,t.k6)((()=>[(0,t.bF)(V,{modelValue:c.qiniuform.accesskey,"onUpdate:modelValue":l[16]||(l[16]=e=>c.qiniuform.accesskey=e)},null,8,["modelValue"])])),_:1}),(0,t.bF)(_,{label:"secretKey",style:{width:"700px"}},{default:(0,t.k6)((()=>[(0,t.bF)(V,{modelValue:c.qiniuform.secretkey,"onUpdate:modelValue":l[17]||(l[17]=e=>c.qiniuform.secretkey=e)},null,8,["modelValue"])])),_:1}),(0,t.bF)(_,{label:"空间名称",style:{width:"700px"}},{default:(0,t.k6)((()=>[(0,t.bF)(V,{modelValue:c.qiniuform.qiniu_bucket,"onUpdate:modelValue":l[18]||(l[18]=e=>c.qiniuform.qiniu_bucket=e)},null,8,["modelValue"])])),_:1}),(0,t.bF)(_,{label:"存储区域",style:{width:"700px"}},{default:(0,t.k6)((()=>[(0,t.bF)(V,{modelValue:c.qiniuform.qiniu_endpoint,"onUpdate:modelValue":l[19]||(l[19]=e=>c.qiniuform.qiniu_endpoint=e)},null,8,["modelValue"])])),_:1}),(0,t.bF)(_,{style:{"margin-top":"50px"}},{default:(0,t.k6)((()=>[(0,t.bF)(k,{type:"primary",onClick:f.qiniuSubmit,style:{width:"200px"}},{default:(0,t.k6)((()=>[b])),_:1},8,["onClick"])])),_:1})])),_:1},8,["model","label-position"])])),_:1})])),_:1},8,["modelValue"])])),_:1})])),_:1})}var c={data(){return{labelPosition:"left",activeName:"first",baseForm:{store_way:"local"},aliform:{oss_domain:"",accesskey_id:"",accesskey_secret:"",bucket:"",endpoint:""},qcloudform:{tencent_appid:"",tencent_domain:"",secret_id:"",secret_key:"",tencent_bucket:"",tencent_endpoint:""},qiniuform:{qiniu_domain:"",accesskey:"",secretkey:"",qiniu_bucket:"",qiniu_endpoint:""},form:{}}},mounted(){this.getBaseConf()},methods:{baseSubmit(){this.form=this.baseForm,this.doSubmit()},aliSubmit(){this.form=this.aliform,this.doSubmit()},qcloudSubmit(){this.form=this.qcloudform,this.doSubmit()},qiniuSubmit(){this.form=this.qiniuform,this.doSubmit()},async getBaseConf(){let e=await this.$API.system.store.get();this.baseForm=e.data.store,this.aliform=e.data.aliyun,this.qcloudform=e.data.qcloud,this.qiniuform=e.data.qiniu},async doSubmit(){let e=await this.$API.system.storeSave.post(this.form);0==e.code?this.$message.success(e.msg):this.$message.error(e.msg)}}},f=a(1456);const p=(0,f.A)(c,[["render",r]]);var _=p}}]);