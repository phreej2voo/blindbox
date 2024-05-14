"use strict";(self["webpackChunkscui"]=self["webpackChunkscui"]||[]).push([[5300,5031,1274],{3242:function(e,l,a){a.r(l),a.d(l,{default:function(){return Q}});var t=a(7267),s=a(5710);const o=e=>((0,t.Qi)("data-v-59e53d6b"),e=e(),(0,t.jt)(),e),r={shadow:"never",style:{border:"none"}},i=(0,t.eW)("查询"),d=(0,t.eW)(" 您有 "),n={class:"warning-tips"},u=(0,t.eW)(" 笔订单等待发货，请及时处理！ "),p=o((()=>(0,t.Lk)("div",{style:{width:"100%",height:"0","border-bottom":"#e4e7ed 1px dashed","margin-top":"15px"}},null,-1))),h=(0,t.eW)("待发货"),g=(0,t.eW)("已发货"),c=(0,t.eW)("已签收"),b=(0,t.eW)("异常"),k=(0,t.eW)("发货详情 "),f=(0,t.eW)("发货"),m={key:1,target:"_blank",href:"https://www.kuaidi100.com/",style:{"margin-left":"12px",color:"#409eff"}},v=(0,t.eW)("运费订单"),F=o((()=>(0,t.Lk)("div",{style:{"margin-top":"20px"}},null,-1)));function _(e,l,a,o,_,x){const w=(0,t.g2)("el-input"),y=(0,t.g2)("el-form-item"),D=(0,t.g2)("el-option"),C=(0,t.g2)("el-select"),W=(0,t.g2)("el-button"),Q=(0,t.g2)("el-form"),X=(0,t.g2)("el-table-column"),I=(0,t.g2)("el-tag"),L=(0,t.g2)("el-table"),V=(0,t.g2)("el-pagination"),$=(0,t.g2)("el-card"),E=(0,t.g2)("save-dialog"),z=(0,t.g2)("express-info-dialog"),O=(0,t.g2)("express-order-dialog"),S=(0,t.g2)("el-main");return(0,t.uX)(),(0,t.Wv)(S,null,{default:(0,t.k6)((()=>[(0,t.bF)($,{shadow:"never"},{default:(0,t.k6)((()=>[(0,t.Lk)("div",r,[(0,t.bF)(Q,{inline:!0,model:_.searchForm,class:"demo-form-inline"},{default:(0,t.k6)((()=>[(0,t.bF)(y,{label:"发货人ID",width:"80px"},{default:(0,t.k6)((()=>[(0,t.bF)(w,{modelValue:_.searchForm.user_id,"onUpdate:modelValue":l[0]||(l[0]=e=>_.searchForm.user_id=e),placeholder:"发货人ID",clearable:""},null,8,["modelValue"])])),_:1}),(0,t.bF)(y,{label:"发货订单号",width:"220px"},{default:(0,t.k6)((()=>[(0,t.bF)(w,{modelValue:_.searchForm.deliver_no,"onUpdate:modelValue":l[1]||(l[1]=e=>_.searchForm.deliver_no=e),placeholder:"发货订单号",clearable:""},null,8,["modelValue"])])),_:1}),(0,t.bF)(y,{label:"发货状态"},{default:(0,t.k6)((()=>[(0,t.bF)(C,{modelValue:_.searchForm.status,"onUpdate:modelValue":l[2]||(l[2]=e=>_.searchForm.status=e),placeholder:"选择发货状态",clearable:""},{default:(0,t.k6)((()=>[(0,t.bF)(D,{label:"待发货",value:"1"}),(0,t.bF)(D,{label:"已发货",value:"2"}),(0,t.bF)(D,{label:"已签收",value:"3"}),(0,t.bF)(D,{label:"异常",value:"4"})])),_:1},8,["modelValue"])])),_:1}),(0,t.bF)(y,null,{default:(0,t.k6)((()=>[(0,t.bF)(W,{type:"primary",onClick:x.search},{default:(0,t.k6)((()=>[i])),_:1},8,["onClick"])])),_:1}),_.notExpress>0?((0,t.uX)(),(0,t.Wv)(y,{key:0},{default:(0,t.k6)((()=>[d,(0,t.Lk)("div",n,(0,s.v_)(_.notExpress),1),u])),_:1})):(0,t.Q3)("",!0)])),_:1},8,["model"])]),p,(0,t.bF)(L,{data:_.tableData,style:{width:"100%","margin-top":"20px"}},{default:(0,t.k6)((()=>[(0,t.bF)(X,{prop:"id",label:"ID",width:"80px"}),(0,t.bF)(X,{prop:"deliver_no",label:"发货订单号",width:"220px"}),(0,t.bF)(X,{prop:"user_id",label:"发货人id"}),(0,t.bF)(X,{prop:"user_name",label:"发货人名"}),(0,t.bF)(X,{label:"状态"},{default:(0,t.k6)((e=>[1===e.row.status?((0,t.uX)(),(0,t.Wv)(I,{key:0,type:"warning"},{default:(0,t.k6)((()=>[h])),_:1})):(0,t.Q3)("",!0),2===e.row.status?((0,t.uX)(),(0,t.Wv)(I,{key:1,type:"success"},{default:(0,t.k6)((()=>[g])),_:1})):(0,t.Q3)("",!0),3===e.row.status?((0,t.uX)(),(0,t.Wv)(I,{key:2,type:"info"},{default:(0,t.k6)((()=>[c])),_:1})):(0,t.Q3)("",!0),4===e.row.status?((0,t.uX)(),(0,t.Wv)(I,{key:3,type:"error"},{default:(0,t.k6)((()=>[b])),_:1})):(0,t.Q3)("",!0)])),_:1}),(0,t.bF)(X,{prop:"express_name",label:"物流公司"}),(0,t.bF)(X,{prop:"express_no",label:"物流单号"}),(0,t.bF)(X,{prop:"create_time",label:"申请时间"}),(0,t.bF)(X,{label:"发货详情"},{default:(0,t.k6)((e=>[(0,t.bF)(W,{type:"primary",plain:"",size:"small",onClick:l=>x.deliverDetail(e.row),icon:"el-icon-ship"},{default:(0,t.k6)((()=>[k])),_:2},1032,["onClick"])])),_:1}),(0,t.bF)(X,{label:"操作"},{default:(0,t.k6)((e=>[1===e.row.status?((0,t.uX)(),(0,t.Wv)(W,{key:0,onClick:l=>x.handleDeliver(e.row),type:"text",size:"small",style:{"margin-left":"0px"}},{default:(0,t.k6)((()=>[f])),_:2},1032,["onClick"])):(0,t.Q3)("",!0),e.row.status>1?((0,t.uX)(),(0,t.CE)("a",m,"物流详情")):(0,t.Q3)("",!0),(0,t.bF)(W,{onClick:l=>x.handleOrder(e.row),type:"text",size:"small",style:{"margin-left":"0px"}},{default:(0,t.k6)((()=>[v])),_:2},1032,["onClick"])])),_:1})])),_:1},8,["data"]),F,(0,t.bF)(V,{background:"",layout:"->, prev, pager, next",total:_.total,"page-size":_.searchForm.limit,onCurrentChange:x.pageChange},null,8,["total","page-size","onCurrentChange"])])),_:1}),_.dialog.save?((0,t.uX)(),(0,t.Wv)(E,{key:0,ref:"saveDialog",onSuccess:x.handleSuccess,onClosed:l[3]||(l[3]=e=>_.dialog.save=!1)},null,8,["onSuccess"])):(0,t.Q3)("",!0),_.dialog.show?((0,t.uX)(),(0,t.Wv)(z,{key:1,ref:"expressInfoDialog",onClosed:l[4]||(l[4]=e=>_.dialog.show=!1)},null,512)):(0,t.Q3)("",!0),_.dialog.order?((0,t.uX)(),(0,t.Wv)(O,{key:2,ref:"expressOrderDialog",onClosed:l[5]||(l[5]=e=>_.dialog.order=!1)},null,512)):(0,t.Q3)("",!0)])),_:1})}a(7414);var x=a(4557),w=a(5774),y=a(3928),D={name:"userBoxDeliverIndex",components:{saveDialog:x["default"],expressInfoDialog:w["default"],expressOrderDialog:y["default"]},data(){return{dialog:{save:!1,show:!1,order:!1},notExpress:0,total:1,searchForm:{deliver_no:"",status:"",user_id:"",page:1,limit:15},tableData:[]}},mounted(){this.getList()},methods:{search(){this.getList()},deliverDetail(e){this.$router.push({path:"/userBoxDeliver/detail",query:{id:e.id}})},handleDeliver(e){this.dialog.save=!0,this.$nextTick((()=>{this.$refs.saveDialog.open("deliver").setData(e)}))},handleExpressInfo(e){this.dialog.show=!0,this.$nextTick((()=>{this.$refs.expressInfoDialog.open("show").setData(e)}))},pageChange(e){this.searchForm.page=e,this.getList()},async getList(){let e=await this.$API.userBoxDeliver.list.get(this.searchForm);this.tableData=e.data.rows,this.total=e.data.total,this.notExpress=e.data.not_express},handleOrder(e){this.dialog.order=!0,this.$nextTick((()=>{this.$refs.expressOrderDialog.open("show").setData(e)}))},handleSuccess(){this.getList()}}},C=a(1456);const W=(0,C.A)(D,[["render",_],["__scopeId","data-v-59e53d6b"]]);var Q=W}}]);