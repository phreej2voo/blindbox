"use strict";(self["webpackChunkscui"]=self["webpackChunkscui"]||[]).push([[4061],{8115:function(e,a,l){l.r(a),l.d(a,{default:function(){return T}});var t=l(7267),r=l(5710);const s=e=>((0,t.Qi)("data-v-e9cee770"),e=e(),(0,t.jt)(),e),o={shadow:"never",style:{border:"none"}},d=(0,t.eW)("查询"),i=(0,t.eW)(" 您有 "),u={class:"warning-tips"},n=(0,t.eW)(" 笔订单等待发货，请及时处理！ "),p=s((()=>(0,t.Lk)("div",{style:{width:"100%",height:"0","border-bottom":"#e4e7ed 1px dashed","margin-top":"15px"}},null,-1))),y=(0,t.eW)("微信"),g=(0,t.eW)("支付宝"),k=(0,t.eW)("哈希币"),_=(0,t.eW)("余额"),c=(0,t.eW)("待支付"),h=(0,t.eW)("待发货"),b=(0,t.eW)("待收货"),f=(0,t.eW)("部分发货"),v=(0,t.eW)("已完成"),m=(0,t.eW)("已取消"),w=(0,t.eW)("已关闭"),W=(0,t.eW)("库存不足"),F=(0,t.eW)("待支付"),x=(0,t.eW)("已支付"),X=(0,t.eW)("已退款"),Q=(0,t.eW)("部分退款"),D=(0,t.eW)("部分支付"),C=(0,t.eW)("支付异常"),V=(0,t.eW)("订单详情"),I=(0,t.eW)("发货"),E={key:1,target:"_blank",href:"https://www.kuaidi100.com/",style:{"margin-left":"12px",color:"#409eff"}},L=s((()=>(0,t.Lk)("div",{style:{"margin-top":"20px"}},null,-1)));function $(e,a,l,s,$,z){const A=(0,t.g2)("el-input"),O=(0,t.g2)("el-form-item"),S=(0,t.g2)("el-option"),U=(0,t.g2)("el-select"),P=(0,t.g2)("el-button"),T=(0,t.g2)("el-form"),j=(0,t.g2)("el-table-column"),K=(0,t.g2)("el-tag"),q=(0,t.g2)("el-table"),B=(0,t.g2)("el-pagination"),G=(0,t.g2)("el-card"),H=(0,t.g2)("detail-dialog"),J=(0,t.g2)("delivery-dialog"),M=(0,t.g2)("express-dialog"),N=(0,t.g2)("el-main");return(0,t.uX)(),(0,t.Wv)(N,null,{default:(0,t.k6)((()=>[(0,t.bF)(G,{shadow:"never"},{default:(0,t.k6)((()=>[(0,t.Lk)("div",o,[(0,t.bF)(T,{inline:!0,model:$.searchForm,class:"demo-form-inline"},{default:(0,t.k6)((()=>[(0,t.bF)(O,{label:"支付人ID"},{default:(0,t.k6)((()=>[(0,t.bF)(A,{modelValue:$.searchForm.user_id,"onUpdate:modelValue":a[0]||(a[0]=e=>$.searchForm.user_id=e),placeholder:"支付人ID",clearable:""},null,8,["modelValue"])])),_:1}),(0,t.bF)(O,{label:"订单号"},{default:(0,t.k6)((()=>[(0,t.bF)(A,{modelValue:$.searchForm.order_no,"onUpdate:modelValue":a[1]||(a[1]=e=>$.searchForm.order_no=e),placeholder:"订单号",clearable:""},null,8,["modelValue"])])),_:1}),(0,t.bF)(O,{label:"支付单号"},{default:(0,t.k6)((()=>[(0,t.bF)(A,{modelValue:$.searchForm.pay_order_no,"onUpdate:modelValue":a[2]||(a[2]=e=>$.searchForm.pay_order_no=e),placeholder:"支付单号",clearable:""},null,8,["modelValue"])])),_:1}),(0,t.bF)(O,{label:"订单状态"},{default:(0,t.k6)((()=>[(0,t.bF)(U,{modelValue:$.searchForm.status,"onUpdate:modelValue":a[3]||(a[3]=e=>$.searchForm.status=e),placeholder:"请选择",clearable:""},{default:(0,t.k6)((()=>[((0,t.uX)(!0),(0,t.CE)(t.FK,null,(0,t.pI)($.options,(e=>((0,t.uX)(),(0,t.Wv)(S,{key:e.value,label:e.label,value:e.value},null,8,["label","value"])))),128))])),_:1},8,["modelValue"])])),_:1}),(0,t.bF)(O,null,{default:(0,t.k6)((()=>[(0,t.bF)(P,{type:"primary",onClick:z.search},{default:(0,t.k6)((()=>[d])),_:1},8,["onClick"])])),_:1}),$.notExpress>0?((0,t.uX)(),(0,t.Wv)(O,{key:0},{default:(0,t.k6)((()=>[i,(0,t.Lk)("div",u,(0,r.v_)($.notExpress),1),n])),_:1})):(0,t.Q3)("",!0)])),_:1},8,["model"])]),p,(0,t.bF)(q,{data:$.tableData,style:{width:"100%","margin-top":"20px"}},{default:(0,t.k6)((()=>[(0,t.bF)(j,{prop:"id",label:"ID",width:"100px"}),(0,t.bF)(j,{prop:"order_no",label:"订单号",width:"180px"}),(0,t.bF)(j,{prop:"pay_order_no",label:"支付单号",width:"180px"}),(0,t.bF)(j,{prop:"user_id",label:"支付人id"}),(0,t.bF)(j,{prop:"user_name",label:"支付人名"}),(0,t.bF)(j,{prop:"total_num",label:"购买数量"}),(0,t.bF)(j,{label:"支付方式"},{default:(0,t.k6)((e=>[1===e.row.pay_way?((0,t.uX)(),(0,t.Wv)(K,{key:0,type:"success"},{default:(0,t.k6)((()=>[y])),_:1})):(0,t.Q3)("",!0),2===e.row.pay_way?((0,t.uX)(),(0,t.Wv)(K,{key:1,type:"info"},{default:(0,t.k6)((()=>[g])),_:1})):(0,t.Q3)("",!0),3===e.row.pay_way?((0,t.uX)(),(0,t.Wv)(K,{key:2,type:"warning"},{default:(0,t.k6)((()=>[k])),_:1})):(0,t.Q3)("",!0),4===e.row.pay_way?((0,t.uX)(),(0,t.Wv)(K,{key:3,type:"danger"},{default:(0,t.k6)((()=>[_])),_:1})):(0,t.Q3)("",!0)])),_:1}),(0,t.bF)(j,{prop:"pay_integral",label:"支付哈希币"}),(0,t.bF)(j,{label:"订单状态"},{default:(0,t.k6)((e=>[1==e.row.status?((0,t.uX)(),(0,t.Wv)(K,{key:0,type:"warning"},{default:(0,t.k6)((()=>[c])),_:1})):(0,t.Q3)("",!0),2==e.row.status?((0,t.uX)(),(0,t.Wv)(K,{key:1,type:"warning"},{default:(0,t.k6)((()=>[h])),_:1})):(0,t.Q3)("",!0),3==e.row.status?((0,t.uX)(),(0,t.Wv)(K,{key:2,type:"warning"},{default:(0,t.k6)((()=>[b])),_:1})):(0,t.Q3)("",!0),4==e.row.status?((0,t.uX)(),(0,t.Wv)(K,{key:3,type:"info"},{default:(0,t.k6)((()=>[f])),_:1})):(0,t.Q3)("",!0),5==e.row.status?((0,t.uX)(),(0,t.Wv)(K,{key:4,type:"success"},{default:(0,t.k6)((()=>[v])),_:1})):(0,t.Q3)("",!0),6==e.row.status?((0,t.uX)(),(0,t.Wv)(K,{key:5,type:"error"},{default:(0,t.k6)((()=>[m])),_:1})):(0,t.Q3)("",!0),7==e.row.status?((0,t.uX)(),(0,t.Wv)(K,{key:6,type:"error"},{default:(0,t.k6)((()=>[w])),_:1})):(0,t.Q3)("",!0),8==e.row.status?((0,t.uX)(),(0,t.Wv)(K,{key:7,type:"danger"},{default:(0,t.k6)((()=>[W])),_:1})):(0,t.Q3)("",!0)])),_:1}),(0,t.bF)(j,{label:"支付状态"},{default:(0,t.k6)((e=>[1==e.row.pay_status?((0,t.uX)(),(0,t.Wv)(K,{key:0,type:"warning"},{default:(0,t.k6)((()=>[F])),_:1})):(0,t.Q3)("",!0),2==e.row.pay_status?((0,t.uX)(),(0,t.Wv)(K,{key:1,type:"success"},{default:(0,t.k6)((()=>[x])),_:1})):(0,t.Q3)("",!0),3==e.row.pay_status?((0,t.uX)(),(0,t.Wv)(K,{key:2,type:"danger"},{default:(0,t.k6)((()=>[X])),_:1})):(0,t.Q3)("",!0),4==e.row.pay_status?((0,t.uX)(),(0,t.Wv)(K,{key:3,type:"info"},{default:(0,t.k6)((()=>[Q])),_:1})):(0,t.Q3)("",!0),5==e.row.pay_status?((0,t.uX)(),(0,t.Wv)(K,{key:4,type:"success"},{default:(0,t.k6)((()=>[D])),_:1})):(0,t.Q3)("",!0),6==e.row.pay_status?((0,t.uX)(),(0,t.Wv)(K,{key:5,type:"error"},{default:(0,t.k6)((()=>[C])),_:1})):(0,t.Q3)("",!0)])),_:1}),(0,t.bF)(j,{prop:"create_time",label:"下单时间",width:"150px"}),(0,t.bF)(j,{prop:"express_no",label:"物流单号",width:"150px"}),(0,t.bF)(j,{width:"250",fixed:"right",label:"操作"},{default:(0,t.k6)((e=>[(0,t.bF)(P,{onClick:a=>z.handleDetail(e.row),link:"",type:"primary",size:"small"},{default:(0,t.k6)((()=>[V])),_:2},1032,["onClick"]),2==e.row.status?((0,t.uX)(),(0,t.Wv)(P,{key:0,onClick:a=>z.handleDelivery(e.row),link:"",type:"primary",size:"small"},{default:(0,t.k6)((()=>[I])),_:2},1032,["onClick"])):(0,t.Q3)("",!0),e.row.status>2?((0,t.uX)(),(0,t.CE)("a",E,"物流详情")):(0,t.Q3)("",!0)])),_:1})])),_:1},8,["data"]),L,(0,t.bF)(B,{background:"",layout:"->, prev, pager, next",total:$.total,"page-size":$.searchForm.limit,onCurrentChange:z.pageChange},null,8,["total","page-size","onCurrentChange"])])),_:1}),$.dialog.detail?((0,t.uX)(),(0,t.Wv)(H,{key:0,ref:"detailDialog",onClosed:a[4]||(a[4]=e=>$.dialog.detail=!1)},null,512)):(0,t.Q3)("",!0),$.dialog.delivery?((0,t.uX)(),(0,t.Wv)(J,{key:1,ref:"deliveryDialog",onClosed:a[5]||(a[5]=e=>$.dialog.delivery=!1),onSuccess:z.handleSuccess},null,8,["onSuccess"])):(0,t.Q3)("",!0),$.dialog.express?((0,t.uX)(),(0,t.Wv)(M,{key:2,ref:"expressDialog",onClosed:a[6]||(a[6]=e=>$.dialog.express=!1)},null,512)):(0,t.Q3)("",!0)])),_:1})}var z=l(5108),A=l(7300),O=l(6121),S={name:"integralOrderIndex",components:{detailDialog:z["default"],deliveryDialog:A["default"],expressDialog:O["default"]},data(){return{dialog:{detail:!1,delivery:!1,express:!1},total:1,searchForm:{status:"",user_name:"",order_no:"",pay_order_no:"",user_id:"",page:1,limit:15},notExpress:0,tableData:[],options:[{value:1,label:"待支付"},{value:2,label:"待发货"},{value:3,label:"待收货"},{value:5,label:"已完成"},{value:6,label:"已取消"}]}},mounted(){this.getList()},methods:{search(){this.getList()},pageChange(e){this.searchForm.page=e,this.getList()},handleDetail(e){this.dialog.detail=!0,this.getDetail(e.id,e.address_info)},handleExpressDetail(e){this.dialog.express=!0,this.getExpress(e.id,e.user_id)},handleDelivery(e){this.dialog.delivery=!0,this.$nextTick((()=>{this.$refs.deliveryDialog.open(e)}))},async getList(){let e=await this.$API.integralOrder.list.get(this.searchForm);this.tableData=e.data.rows,this.total=e.data.total,this.notExpress=e.data.not_express},async getDetail(e,a){let l=await this.$API.integralOrder.detail.get({order_id:e});this.$nextTick((()=>{let e={data:l.data,addressInfo:a};this.$refs.detailDialog.open(e)}))},async getExpress(e,a){let l=await this.$API.integralOrder.express.get({order_id:e,user_id:a});this.$nextTick((()=>{this.$refs.expressDialog.open(l.data)}))},handleSuccess(){this.getList()}}},U=l(1456);const P=(0,U.A)(S,[["render",$],["__scopeId","data-v-e9cee770"]]);var T=P}}]);