"use strict";(self["webpackChunkscui"]=self["webpackChunkscui"]||[]).push([[8017,4379],{3877:function(e,t,a){a.r(t),a.d(t,{default:function(){return L}});var l=a(7267),i=a(5710);const s=e=>((0,l.Qi)("data-v-98b1e654"),e=e(),(0,l.jt)(),e),o=(0,l.eW)("查询"),n=(0,l.eW)(" 添加合成 "),d=s((()=>(0,l.Lk)("div",{style:{width:"100%",height:"0","border-bottom":"#e4e7ed 1px dashed","margin-top":"15px","margin-bottom":"15px"}},null,-1))),c={class:"table-body"},r=(0,l.eW)("上线"),p=(0,l.eW)("下线"),g=(0,l.eW)(" 合成详情 "),u=(0,l.eW)(" 编辑 "),h=(0,l.eW)(" 删除 "),b={class:"pagination-class"},k={class:"click-bar"},m=(0,l.eW)("返回 "),f={style:{"font-size":"14px","font-weight":"700"}};function v(e,t,a,s,v,y){const _=(0,l.g2)("el-input"),F=(0,l.g2)("el-form-item"),w=(0,l.g2)("el-option"),C=(0,l.g2)("el-select"),L=(0,l.g2)("el-date-picker"),x=(0,l.g2)("el-button"),W=(0,l.g2)("el-form"),V=(0,l.g2)("el-table-column"),$=(0,l.g2)("el-image"),S=(0,l.g2)("el-tag"),T=(0,l.g2)("el-table"),X=(0,l.g2)("el-pagination"),D=(0,l.g2)("el-card"),Q=(0,l.g2)("el-icon-arrow-left"),z=(0,l.g2)("el-icon"),A=(0,l.g2)("el-divider"),B=(0,l.g2)("advance-condition"),M=(0,l.g2)("log-dialog"),P=(0,l.g2)("el-main"),R=(0,l.gN)("loading");return(0,l.uX)(),(0,l.Wv)(P,null,{default:(0,l.k6)((()=>[1==v.type?((0,l.uX)(),(0,l.Wv)(D,{key:0,shadow:"never",style:{"margin-top":"10px"}},{default:(0,l.k6)((()=>[(0,l.bF)(W,{ref:"searchForm",inline:!0,model:v.searchForm,class:"demo-form-inline","label-width":"80px"},{default:(0,l.k6)((()=>[(0,l.bF)(F,{label:"名称",prop:"name"},{default:(0,l.k6)((()=>[(0,l.bF)(_,{modelValue:v.searchForm.name,"onUpdate:modelValue":t[0]||(t[0]=e=>v.searchForm.name=e),placeholder:"",clearable:""},null,8,["modelValue"])])),_:1}),(0,l.bF)(F,{label:"状态",prop:"status"},{default:(0,l.k6)((()=>[(0,l.bF)(C,{modelValue:v.searchForm.status,"onUpdate:modelValue":t[1]||(t[1]=e=>v.searchForm.status=e),placeholder:"请选择",clearable:""},{default:(0,l.k6)((()=>[(0,l.bF)(w,{label:"上线",value:"1"}),(0,l.bF)(w,{label:"下线",value:"2"})])),_:1},8,["modelValue"])])),_:1}),(0,l.bF)(F,{label:"活动时间",prop:"activity_time"},{default:(0,l.k6)((()=>[(0,l.bF)(L,{modelValue:v.searchForm.activity_time,"onUpdate:modelValue":t[2]||(t[2]=e=>v.searchForm.activity_time=e),type:"daterange","range-separator":"至","start-placeholder":"开始日期","end-placeholder":"结束日期","value-format":"YYYY-MM-DD",onChange:y.dateChange},null,8,["modelValue","onChange"])])),_:1}),(0,l.bF)(F,null,{default:(0,l.k6)((()=>[(0,l.bF)(x,{type:"primary",onClick:y.searchList},{default:(0,l.k6)((()=>[o])),_:1},8,["onClick"])])),_:1})])),_:1},8,["model"]),(0,l.bF)(x,{type:"primary",onClick:y.addAdvance,icon:"el-icon-plus"},{default:(0,l.k6)((()=>[n])),_:1},8,["onClick"]),d,(0,l.Lk)("div",c,[(0,l.bo)(((0,l.uX)(),(0,l.Wv)(T,{class:"table-info",data:v.list,style:{width:"100%"}},{default:(0,l.k6)((()=>[(0,l.bF)(V,{label:"id",prop:"id",width:"50"}),(0,l.bF)(V,{prop:"sort",label:"排序"}),(0,l.bF)(V,{label:"图片",prop:"image"},{default:(0,l.k6)((t=>[(0,l.bF)($,{style:{width:"50px",height:"50px"},src:t.row.image,fit:e.fit},null,8,["src","fit"])])),_:1}),(0,l.bF)(V,{label:"标题",prop:"name",width:"200","show-overflow-tooltip":""}),(0,l.bF)(V,{label:"价格",prop:"price",width:"130"},{default:(0,l.k6)((e=>[(0,l.Lk)("div",null,(0,i.v_)(e.row.price),1)])),_:1}),(0,l.bF)(V,{label:"活动时间",prop:"activity_time",width:"280","show-overflow-tooltip":""},{default:(0,l.k6)((e=>[(0,l.Lk)("span",null,(0,i.v_)(e.row.start_time)+" / "+(0,i.v_)(e.row.end_time),1)])),_:1}),(0,l.bF)(V,{label:"总库存",prop:"stock",width:"120"},{default:(0,l.k6)((e=>[(0,l.Lk)("div",null,(0,i.v_)(e.row.stock),1)])),_:1}),(0,l.bF)(V,{label:"已合成数",prop:"sales",width:"120"}),(0,l.bF)(V,{label:"状态",prop:"status",width:"150"},{default:(0,l.k6)((e=>[1==e.row.status?((0,l.uX)(),(0,l.Wv)(S,{key:0,type:"success"},{default:(0,l.k6)((()=>[r])),_:1})):(0,l.Q3)("",!0),2==e.row.status?((0,l.uX)(),(0,l.Wv)(S,{key:1,type:"danger"},{default:(0,l.k6)((()=>[p])),_:1})):(0,l.Q3)("",!0)])),_:1}),(0,l.bF)(V,{label:"操作",width:"230",fixed:"right"},{default:(0,l.k6)((e=>[(0,l.bF)(x,{type:"text",size:"small",onClick:t=>y.log(e.row)},{default:(0,l.k6)((()=>[g])),_:2},1032,["onClick"]),(0,l.bF)(x,{type:"text",size:"small",onClick:t=>y.editRow(e.row)},{default:(0,l.k6)((()=>[u])),_:2},1032,["onClick"]),(0,l.bF)(x,{type:"text",size:"small",onClick:t=>y.delRow(e.row)},{default:(0,l.k6)((()=>[h])),_:2},1032,["onClick"])])),_:1})])),_:1},8,["data"])),[[R,v.listLoading]])]),(0,l.Lk)("div",b,[(0,l.bF)(X,{style:{"margin-top":"10px"},background:"",layout:"->, prev, pager, next","page-size":v.pager.limit,total:v.pager.total,onCurrentChange:y.handlePageChange},null,8,["page-size","total","onCurrentChange"])])])),_:1})):(0,l.Q3)("",!0),2==v.type?((0,l.uX)(),(0,l.Wv)(D,{key:1,shadow:"false",style:{display:"flex"}},{default:(0,l.k6)((()=>[(0,l.Lk)("div",k,[(0,l.Lk)("div",{onClick:t[3]||(t[3]=e=>v.type=1)},[(0,l.bF)(z,{class:"back-icon"},{default:(0,l.k6)((()=>[(0,l.bF)(Q)])),_:1}),m]),(0,l.bF)(A,{direction:"vertical"}),(0,l.Lk)("span",f,(0,i.v_)(v.backTitle),1)])])),_:1})):(0,l.Q3)("",!0),2==v.type?((0,l.uX)(),(0,l.Wv)(D,{key:2,shadow:"false",style:{"margin-top":"10px"}},{default:(0,l.k6)((()=>[(0,l.bF)(B,{ref:"advance",onSuccessBack:y.saveGoods},null,8,["onSuccessBack"])])),_:1})):(0,l.Q3)("",!0),v.logVisiable?((0,l.uX)(),(0,l.Wv)(M,{key:3,ref:"logDialog",onSuccess:y.handleSendSuccess,onClosed:t[4]||(t[4]=e=>v.logVisiable=!1)},null,8,["onSuccess"])):(0,l.Q3)("",!0)])),_:1})}var y=a(1871),_=a(7698),F={name:"index",components:{AdvanceCondition:y["default"],logDialog:_["default"]},data(){return{type:1,backTitle:"",logVisiable:!1,pager:{page:1,limit:15,total:0},searchForm:{name:"",status:"",activity_time:""},list:[],page:1,sort:0,keyword:"",cats_id:"",listLoading:!1,pagination:{},search:"",id:"",stock:"",sellStock:"",sell_stock_id:"",stock_id:"",catsList:[]}},created(){this.getList()},methods:{dateChange(e){console.log("val-",e)},getList(){this.listLoading=!0;const e={name:this.searchForm.name,status:this.searchForm.status,activity_time:this.searchForm.activity_time,page:this.pager.page,limit:this.pager.limit};this.$API.conflate.conflateList.get(e).then((e=>{const{code:t,data:a}=e;0==t&&(this.list=a.data,this.pager.total=a.total),this.listLoading=!1})).catch((()=>{this.listLoading=!1}))},searchList(){this.getList()},addAdvance(){this.type=2,this.backTitle="添加碎片合成条件",this.$nextTick((()=>{this.$refs.advance.setMode("add")}))},editRow(e){this.type=2,this.backTitle="编辑碎片合成条件",this.$nextTick((()=>{this.$refs.advance.setMode("edit",e)}))},delRow(e){this.$confirm("确定删除当前碎片活动?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((async()=>{const{code:t,msg:a}=await this.$API.conflate.conflateDel.get({id:e.id});0==t?(this.$message.success(a),this.getList()):this.$message.error(a)})).catch((()=>{}))},log(e){this.logVisiable=!0,this.$nextTick((()=>{this.$refs.logDialog.open().setData(e)}))},handleSendSuccess(){this.logVisiable=!1},handlePageChange(e){this.pager.page=e,this.getList()},saveGoods(){this.type=1,this.pager.page=1,this.getList()}}},w=a(1456);const C=(0,w.A)(F,[["render",v],["__scopeId","data-v-98b1e654"]]);var L=C}}]);