"use strict";(self["webpackChunkscui"]=self["webpackChunkscui"]||[]).push([[4625],{1688:function(e,a,l){l.r(a),l.d(a,{default:function(){return d}});var t=l(6252);const o=e=>((0,t.dD)("data-v-01a13632"),e=e(),(0,t.Cn)(),e),r={class:"search-box"},u=(0,t.Uk)("查询"),n=o((()=>(0,t._)("div",{style:{width:"100%",height:"0","border-bottom":"#e4e7ed 1px dashed","margin-bottom":"10px"}},null,-1)));function m(e,a,l,o,m,s){const i=(0,t.up)("el-option"),p=(0,t.up)("el-select"),d=(0,t.up)("el-form-item"),c=(0,t.up)("el-input"),h=(0,t.up)("el-button"),b=(0,t.up)("el-form"),g=(0,t.up)("el-table-column"),W=(0,t.up)("el-table"),_=(0,t.up)("el-pagination"),w=(0,t.up)("el-card"),f=(0,t.up)("el-main");return(0,t.wg)(),(0,t.j4)(f,null,{default:(0,t.w5)((()=>[(0,t.Wm)(w,{class:"box-card",shadow:"never"},{default:(0,t.w5)((()=>[(0,t._)("div",r,[(0,t.Wm)(b,{inline:!0,model:m.searchForm,class:"demo-form-inline","label-width":"80px"},{default:(0,t.w5)((()=>[(0,t.Wm)(d,{label:"状态"},{default:(0,t.w5)((()=>[(0,t.Wm)(p,{modelValue:m.searchForm.status,"onUpdate:modelValue":a[0]||(a[0]=e=>m.searchForm.status=e),placeholder:"请选择",clearable:""},{default:(0,t.w5)((()=>[(0,t.Wm)(i,{label:"未使用",value:"1"}),(0,t.Wm)(i,{label:"已使用",value:"2"}),(0,t.Wm)(i,{label:"已过期",value:"3"})])),_:1},8,["modelValue"])])),_:1}),(0,t.Wm)(d,{label:"领取人"},{default:(0,t.w5)((()=>[(0,t.Wm)(c,{modelValue:m.searchForm.user_name,"onUpdate:modelValue":a[1]||(a[1]=e=>m.searchForm.user_name=e),placeholder:"",clearable:""},null,8,["modelValue"])])),_:1}),(0,t.Wm)(d,null,{default:(0,t.w5)((()=>[(0,t.Wm)(h,{type:"primary",onClick:s.onSubmit,icon:"el-icon-search"},{default:(0,t.w5)((()=>[u])),_:1},8,["onClick"])])),_:1})])),_:1},8,["model"])]),n,(0,t.Wm)(W,{data:m.tableData,style:{width:"100%"}},{default:(0,t.w5)((()=>[(0,t.Wm)(g,{prop:"id",width:"100px",label:"ID"}),(0,t.Wm)(g,{prop:"coupon_name",label:"优惠券名称"}),(0,t.Wm)(g,{prop:"username",label:"领取人"}),(0,t.Wm)(g,{prop:"coupon.amount",label:"面值"}),(0,t.Wm)(g,{prop:"coupon.threshold_amount",label:"门槛金额"}),(0,t.Wm)(g,{prop:"end_time",label:"有效期"}),(0,t.Wm)(g,{prop:"status_txt",label:"状态"}),(0,t.Wm)(g,{prop:"used_time",label:"使用日期"})])),_:1},8,["data"]),(0,t.Wm)(_,{style:{"margin-top":"10px"},background:"",layout:"->, prev, pager, next","page-size":m.searchForm.limit,onCurrentChange:s.handlePageChange,total:m.total},null,8,["page-size","onCurrentChange","total"])])),_:1})])),_:1})}var s={name:"receiveLog",data(){return{searchForm:{user_name:"",status:"",limit:15,page:1},total:1,tableData:[]}},mounted(){this.getList()},methods:{onSubmit(){this.searchForm.page=1,this.getList()},handlePageChange(e){this.searchForm.page=e,this.getList()},async getList(){let e=await this.$API.coupon.receive.get(this.searchForm);this.tableData=e.data.rows,this.total=e.data.total}}},i=l(3744);const p=(0,i.Z)(s,[["render",m],["__scopeId","data-v-01a13632"]]);var d=p}}]);