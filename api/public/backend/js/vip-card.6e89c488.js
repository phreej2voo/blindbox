"use strict";(self["webpackChunkscui"]=self["webpackChunkscui"]||[]).push([[3987,1206],{1929:function(e,t,a){a.r(t),a.d(t,{default:function(){return X}});var l=a(7267),s=a(5710);const i=e=>((0,l.Qi)("data-v-e7b40554"),e=e(),(0,l.jt)(),e),o={class:"search-box"},d=(0,l.eW)("查询"),r=i((()=>(0,l.Lk)("div",{class:"line"},null,-1))),n=(0,l.eW)("添加会员卡"),u=i((()=>(0,l.Lk)("div",{style:{width:"100%",height:"0","border-bottom":"#e4e7ed 1px dashed","margin-top":"15px"}},null,-1))),c=(0,l.eW)("周卡"),p=(0,l.eW)("月卡"),b=(0,l.eW)("正常"),h=(0,l.eW)("作废"),g={key:0},k={key:1},m=(0,l.eW)("编辑"),f=(0,l.eW)("删除"),y={class:"page-div",style:{"margin-top":"20px"}},v={class:"click-bar",style:{display:"flex"}},F=(0,l.eW)("返回 "),_={style:{"font-size":"14px","font-weight":"700"}};function C(e,t,a,i,C,w){const x=(0,l.g2)("el-input"),W=(0,l.g2)("el-form-item"),L=(0,l.g2)("el-option"),X=(0,l.g2)("el-select"),D=(0,l.g2)("el-button"),Q=(0,l.g2)("el-form"),V=(0,l.g2)("el-table-column"),$=(0,l.g2)("el-tag"),S=(0,l.g2)("el-table"),z=(0,l.g2)("el-pagination"),T=(0,l.g2)("el-card"),E=(0,l.g2)("el-icon-arrow-left"),I=(0,l.g2)("el-icon"),A=(0,l.g2)("el-divider"),P=(0,l.g2)("save-page"),U=(0,l.g2)("el-main");return(0,l.uX)(),(0,l.Wv)(U,null,{default:(0,l.k6)((()=>[1==C.type?((0,l.uX)(),(0,l.Wv)(T,{key:0,shadow:"never"},{default:(0,l.k6)((()=>[(0,l.Lk)("div",o,[(0,l.bF)(Q,{inline:!0,model:C.searchForm,class:"demo-form-inline","label-width":"70px"},{default:(0,l.k6)((()=>[(0,l.bF)(W,{label:"会员卡名"},{default:(0,l.k6)((()=>[(0,l.bF)(x,{modelValue:C.searchForm.title,"onUpdate:modelValue":t[0]||(t[0]=e=>C.searchForm.title=e),placeholder:"",clearable:""},null,8,["modelValue"])])),_:1}),(0,l.bF)(W,{label:"类型"},{default:(0,l.k6)((()=>[(0,l.bF)(X,{modelValue:C.searchForm.type,"onUpdate:modelValue":t[1]||(t[1]=e=>C.searchForm.type=e),placeholder:"请选择",clearable:""},{default:(0,l.k6)((()=>[(0,l.bF)(L,{label:"周卡",value:"1"}),(0,l.bF)(L,{label:"月卡",value:"2"})])),_:1},8,["modelValue"])])),_:1}),(0,l.bF)(W,{label:"状态"},{default:(0,l.k6)((()=>[(0,l.bF)(X,{modelValue:C.searchForm.status,"onUpdate:modelValue":t[2]||(t[2]=e=>C.searchForm.status=e),placeholder:"请选择",clearable:""},{default:(0,l.k6)((()=>[(0,l.bF)(L,{label:"正常",value:"1"}),(0,l.bF)(L,{label:"作废",value:"2"})])),_:1},8,["modelValue"])])),_:1}),(0,l.bF)(W,null,{default:(0,l.k6)((()=>[(0,l.bF)(D,{type:"primary",onClick:w.onSubmit,icon:"el-icon-search"},{default:(0,l.k6)((()=>[d])),_:1},8,["onClick"])])),_:1})])),_:1},8,["model"])]),r,(0,l.bF)(D,{type:"primary",icon:"el-icon-plus",onClick:w.addCard,style:{"margin-top":"15px"}},{default:(0,l.k6)((()=>[n])),_:1},8,["onClick"]),u,(0,l.bF)(S,{data:C.tableData,style:{width:"100%","margin-top":"20px"}},{default:(0,l.k6)((()=>[(0,l.bF)(V,{prop:"id",label:"ID"}),(0,l.bF)(V,{label:"类型"},{default:(0,l.k6)((e=>[1==e.row.type?((0,l.uX)(),(0,l.Wv)($,{key:0,type:"success"},{default:(0,l.k6)((()=>[c])),_:1})):(0,l.Q3)("",!0),2==e.row.type?((0,l.uX)(),(0,l.Wv)($,{key:1,type:"danger"},{default:(0,l.k6)((()=>[p])),_:1})):(0,l.Q3)("",!0)])),_:1}),(0,l.bF)(V,{prop:"title",label:"标题"}),(0,l.bF)(V,{prop:"price",label:"价格"}),(0,l.bF)(V,{label:"状态"},{default:(0,l.k6)((e=>[1==e.row.status?((0,l.uX)(),(0,l.Wv)($,{key:0,type:"success"},{default:(0,l.k6)((()=>[b])),_:1})):(0,l.Q3)("",!0),2==e.row.status?((0,l.uX)(),(0,l.Wv)($,{key:1,type:"danger"},{default:(0,l.k6)((()=>[h])),_:1})):(0,l.Q3)("",!0)])),_:1}),(0,l.bF)(V,{label:"库存"},{default:(0,l.k6)((e=>[-1==e.row.stock?((0,l.uX)(),(0,l.CE)("div",g,"无限制")):((0,l.uX)(),(0,l.CE)("div",k,(0,s.v_)(e.row.stock),1))])),_:1}),(0,l.bF)(V,{prop:"create_time",label:"创建时间"}),(0,l.bF)(V,{label:"操作"},{default:(0,l.k6)((e=>[(0,l.bF)(D,{onClick:t=>w.handleEdit(e.row),type:"text",size:"small"},{default:(0,l.k6)((()=>[m])),_:2},1032,["onClick"]),1==e.row.status?((0,l.uX)(),(0,l.Wv)(D,{key:0,onClick:t=>w.handleDel(e.row),type:"text",size:"small"},{default:(0,l.k6)((()=>[f])),_:2},1032,["onClick"])):(0,l.Q3)("",!0)])),_:1})])),_:1},8,["data"]),(0,l.Lk)("div",y,[(0,l.bF)(z,{background:"",layout:"->, prev, pager, next","page-size":C.searchForm.limit,onCurrentChange:w.pageChangeHandle,total:C.page.total},null,8,["page-size","onCurrentChange","total"])])])),_:1})):(0,l.Q3)("",!0),2==C.type?((0,l.uX)(),(0,l.Wv)(T,{key:1,shadow:"false",style:{display:"flex",cursor:"pointer"}},{default:(0,l.k6)((()=>[(0,l.Lk)("div",v,[(0,l.Lk)("div",{onClick:t[3]||(t[3]=e=>C.type=1)},[(0,l.bF)(I,{class:"back-icon"},{default:(0,l.k6)((()=>[(0,l.bF)(E)])),_:1}),F]),(0,l.bF)(A,{direction:"vertical"}),(0,l.Lk)("span",_,(0,s.v_)(C.title),1)])])),_:1})):(0,l.Q3)("",!0),2==C.type?((0,l.uX)(),(0,l.Wv)(T,{key:2,shadow:"false",style:{"margin-top":"10px"}},{default:(0,l.k6)((()=>[(0,l.bF)(P,{onSave:w.saveSuccess,ref:"saveDialog"},null,8,["onSave"])])),_:1})):(0,l.Q3)("",!0)])),_:1})}var w=a(1970),x={name:"vip_card",components:{savePage:w["default"]},data(){return{dialog:{save:!1},tableData:[],searchForm:{title:"",type:"",status:"",page:1,limit:15},type:1,page:{total:1},title:"新增会员卡",logTableData:[]}},mounted(){this.getList()},methods:{search(){this.getList()},addCard(){this.type=2,this.title="新增会员卡",this.$nextTick((()=>{this.$refs.saveDialog.setMode("add",0)}))},handleEdit(e){this.type=2,this.title="编辑会员卡",this.$nextTick((()=>{this.$refs.saveDialog.setMode("edit",e.id)}))},handleDel(e){this.$confirm("此操作将作废会员卡, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((async()=>{let t=await this.$API.vipCard.del.get({id:e.id});0==t.code?(this.$message.success(t.msg),this.getList()):this.$message.error(t.msg)})).catch((()=>{}))},onSubmit(){this.getList()},async getList(){let e=await this.$API.vipCard.list.get(this.searchForm);this.tableData=e.data.data,this.page.total=e.data.total},handleSuccess(){this.getList()},pageChangeHandle(e){this.searchForm.page=e,this.getList()},saveSuccess(){this.type=1,this.getList()}}},W=a(1456);const L=(0,W.A)(x,[["render",C],["__scopeId","data-v-e7b40554"]]);var X=L}}]);