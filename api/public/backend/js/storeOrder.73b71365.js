"use strict";(self["webpackChunkscui"]=self["webpackChunkscui"]||[]).push([[9618],{8534:function(e,a,t){t.r(a),t.d(a,{default:function(){return v}});var l=t(7267);const s={shadow:"never",style:{border:"none"}},o=(0,l.eW)("查询"),d=(0,l.Lk)("div",{style:{width:"100%",height:"0","border-bottom":"#e4e7ed 1px dashed","margin-top":"15px"}},null,-1),r=(0,l.Lk)("div",{style:{width:"100%",height:"0","border-bottom":"#e4e7ed 1px dashed","margin-top":"15px"}},null,-1),i=(0,l.eW)("已预约"),u=(0,l.eW)("已核销"),m=(0,l.eW)("已取消"),n=(0,l.eW)("编辑"),p=(0,l.eW)("核销"),b={class:"page-div",style:{"margin-top":"20px"}},h=(0,l.eW)("已预约"),c=(0,l.eW)("已核销"),g=(0,l.eW)("已取消"),f={class:"dialog-footer"},k=(0,l.eW)("取 消"),F=(0,l.eW)("确 定");function V(e,a,t,V,_,y){const w=(0,l.g2)("el-input"),v=(0,l.g2)("el-form-item"),C=(0,l.g2)("el-button"),W=(0,l.g2)("el-form"),x=(0,l.g2)("el-table-column"),L=(0,l.g2)("el-tag"),$=(0,l.g2)("el-table"),U=(0,l.g2)("el-pagination"),X=(0,l.g2)("el-card"),Q=(0,l.g2)("el-radio"),z=(0,l.g2)("el-dialog"),A=(0,l.g2)("el-main");return(0,l.uX)(),(0,l.Wv)(A,null,{default:(0,l.k6)((()=>[(0,l.bF)(X,{shadow:"never"},{default:(0,l.k6)((()=>[(0,l.Lk)("div",s,[(0,l.bF)(W,{inline:!0,model:_.searchForm,class:"demo-form-inline"},{default:(0,l.k6)((()=>[(0,l.bF)(v,{label:"门店名称"},{default:(0,l.k6)((()=>[(0,l.bF)(w,{modelValue:_.searchForm.name,"onUpdate:modelValue":a[0]||(a[0]=e=>_.searchForm.name=e),placeholder:"门店名称",clearable:""},null,8,["modelValue"])])),_:1}),(0,l.bF)(v,null,{default:(0,l.k6)((()=>[(0,l.bF)(C,{type:"primary",onClick:y.search},{default:(0,l.k6)((()=>[o])),_:1},8,["onClick"])])),_:1})])),_:1},8,["model"])]),d,r,(0,l.bF)($,{data:_.tableData,style:{width:"100%","margin-top":"20px"}},{default:(0,l.k6)((()=>[(0,l.bF)(x,{prop:"id",label:"ID"}),(0,l.bF)(x,{prop:"uuid",label:"券码标识"}),(0,l.bF)(x,{prop:"name",label:"门店名称"}),(0,l.bF)(x,{prop:"address",label:"门店地址"}),(0,l.bF)(x,{label:"订单状态",width:"100px"},{default:(0,l.k6)((e=>[1==e.row.status?((0,l.uX)(),(0,l.Wv)(L,{key:0},{default:(0,l.k6)((()=>[i])),_:1})):(0,l.Q3)("",!0),2==e.row.status?((0,l.uX)(),(0,l.Wv)(L,{key:1,type:"success"},{default:(0,l.k6)((()=>[u])),_:1})):(0,l.Q3)("",!0),3==e.row.status?((0,l.uX)(),(0,l.Wv)(L,{key:2,type:"danger"},{default:(0,l.k6)((()=>[m])),_:1})):(0,l.Q3)("",!0)])),_:1}),(0,l.bF)(x,{prop:"reserve_date",label:"预约时间"}),(0,l.bF)(x,{prop:"create_time",label:"创建时间"}),(0,l.bF)(x,{prop:"update_time",label:"更新时间"}),(0,l.bF)(x,{label:"操作"},{default:(0,l.k6)((e=>[3!=e.row.status?((0,l.uX)(),(0,l.Wv)(C,{key:0,onClick:a=>y.handleEdit(e.row),type:"text",size:"small"},{default:(0,l.k6)((()=>[n])),_:2},1032,["onClick"])):(0,l.Q3)("",!0),1==e.row.status?((0,l.uX)(),(0,l.Wv)(C,{key:1,onClick:a=>y.handleCheck(e.row),type:"text",size:"small"},{default:(0,l.k6)((()=>[p])),_:2},1032,["onClick"])):(0,l.Q3)("",!0)])),_:1})])),_:1},8,["data"]),(0,l.Lk)("div",b,[(0,l.bF)(U,{background:"",layout:"->, prev, pager, next","page-size":_.searchForm.limit,onCurrentChange:y.pageChangeHandle,total:_.page.total},null,8,["page-size","onCurrentChange","total"])])])),_:1}),(0,l.bF)(z,{title:_.title,"append-to-body":!0,"close-on-click-modal":!1,modelValue:_.dialogVisible,"onUpdate:modelValue":a[9]||(a[9]=e=>_.dialogVisible=e),width:"30%"},{default:(0,l.k6)((()=>[(0,l.bF)(W,{model:_.form,"label-width":"80px",rules:_.rules,ref:"ruleForm"},{default:(0,l.k6)((()=>[(0,l.bF)(v,{label:"券码标识",prop:"uuid"},{default:(0,l.k6)((()=>[(0,l.bF)(w,{modelValue:_.form.uuid,"onUpdate:modelValue":a[1]||(a[1]=e=>_.form.uuid=e)},null,8,["modelValue"])])),_:1}),(0,l.bF)(v,{label:"门店名称",prop:"name"},{default:(0,l.k6)((()=>[(0,l.bF)(w,{modelValue:_.form.name,"onUpdate:modelValue":a[2]||(a[2]=e=>_.form.name=e)},null,8,["modelValue"])])),_:1}),(0,l.bF)(v,{label:"门店地址",prop:"address"},{default:(0,l.k6)((()=>[(0,l.bF)(w,{modelValue:_.form.address,"onUpdate:modelValue":a[3]||(a[3]=e=>_.form.address=e)},null,8,["modelValue"])])),_:1}),(0,l.bF)(v,{label:"订单状态",prop:"status"},{default:(0,l.k6)((()=>[(0,l.bF)(Q,{label:1,modelValue:_.form.status,"onUpdate:modelValue":a[4]||(a[4]=e=>_.form.status=e)},{default:(0,l.k6)((()=>[h])),_:1},8,["modelValue"]),(0,l.bF)(Q,{label:2,modelValue:_.form.status,"onUpdate:modelValue":a[5]||(a[5]=e=>_.form.status=e)},{default:(0,l.k6)((()=>[c])),_:1},8,["modelValue"]),(0,l.bF)(Q,{label:3,modelValue:_.form.status,"onUpdate:modelValue":a[6]||(a[6]=e=>_.form.status=e)},{default:(0,l.k6)((()=>[g])),_:1},8,["modelValue"])])),_:1})])),_:1},8,["model","rules"]),(0,l.Lk)("span",f,[(0,l.bF)(C,{onClick:a[7]||(a[7]=e=>_.dialogVisible=!1)},{default:(0,l.k6)((()=>[k])),_:1}),(0,l.bF)(C,{type:"primary",onClick:a[8]||(a[8]=e=>y.submitForm("ruleForm"))},{default:(0,l.k6)((()=>[F])),_:1})])])),_:1},8,["title","modelValue"])])),_:1})}var _={name:"storeOrder",components:{},data(){return{tableData:[],dialogVisible:!1,title:"",searchForm:{name:"",page:1,limit:15},page:{total:1},form:{id:0,uuid:"",name:"",address:"",status:1},rules:{name:[{required:!0,message:"请输入门店名称"}],address:[{required:!0,message:"请选择门店地址"}],status:[{required:!0,message:"请选择订单状态"}]}}},mounted(){this.getList()},methods:{search(){this.getList()},handleEdit(e){this.title="编辑线下预约订单",this.form.id=e.id,this.form.uuid=e.uuid,this.form.name=e.name,this.form.address=e.address,this.form.status=e.status,this.dialogVisible=!0},async submitForm(e){this.$refs[e].validate((async e=>{if(e){let e=await this.$API.storeOrder.edit.post(this.form);0==e.code?(this.$message.success(e.msg),this.dialogVisible=!1):this.$message.error(e.msg)}}))},handleCheck(e){this.$confirm("是否确定核销?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((async()=>{let a=await this.$API.storeOrder.check.get({id:e.id});0==a.code?(this.$message.success(a.msg),this.getList()):this.$message.error(a.msg)})).catch((()=>{}))},async getList(){let e=await this.$API.storeOrder.list.get(this.searchForm);this.tableData=e.data.rows,this.page.total=e.data.total},handleSuccess(){this.getList()},pageChangeHandle(e){this.searchForm.page=e,this.getList()}}},y=t(1456);const w=(0,y.A)(_,[["render",V]]);var v=w}}]);