"use strict";(self["webpackChunkscui"]=self["webpackChunkscui"]||[]).push([[5439],{2712:function(e,a,t){t.r(a),t.d(a,{default:function(){return y}});var l=t(7267);const i=e=>((0,l.Qi)("data-v-604eaa59"),e=e(),(0,l.jt)(),e),o={class:"click-bar",style:{width:"300px",display:"flex"}},s=(0,l.eW)("返回列表 "),n=i((()=>(0,l.Lk)("span",{style:{"font-size":"14px","font-weight":"700","margin-left":"20px"}},"箱子列表",-1))),d=(0,l.eW)("补箱"),r=i((()=>(0,l.Lk)("div",{class:"line"},null,-1))),u=(0,l.eW)("在售"),p=(0,l.eW)("售罄"),b={class:"page-div",style:{"margin-top":"20px"}},c=(0,l.eW)("取 消"),g=(0,l.eW)("确 定");function h(e,a,t,i,h,m){const k=(0,l.g2)("el-icon-arrow-left"),f=(0,l.g2)("el-icon"),y=(0,l.g2)("el-card"),F=(0,l.g2)("el-button"),_=(0,l.g2)("el-table-column"),x=(0,l.g2)("el-tag"),v=(0,l.g2)("el-table"),w=(0,l.g2)("el-pagination"),C=(0,l.g2)("el-input"),L=(0,l.g2)("el-form-item"),V=(0,l.g2)("el-form"),I=(0,l.g2)("el-dialog"),W=(0,l.g2)("el-main");return(0,l.uX)(),(0,l.Wv)(W,null,{default:(0,l.k6)((()=>[(0,l.bF)(y,{shadow:"false",style:{display:"flex"}},{default:(0,l.k6)((()=>[(0,l.Lk)("div",o,[(0,l.Lk)("div",{onClick:a[0]||(a[0]=e=>m.goBack()),style:{cursor:"pointer"}},[(0,l.bF)(f,{class:"back-icon"},{default:(0,l.k6)((()=>[(0,l.bF)(k)])),_:1}),s]),n])])),_:1}),(0,l.bF)(y,{shadow:"never"},{default:(0,l.k6)((()=>[1==h.playId||3==h.playId?((0,l.uX)(),(0,l.Wv)(F,{key:0,type:"danger",icon:"el-icon-plus",onClick:m.incBlindbox,style:{"margin-top":"15px"}},{default:(0,l.k6)((()=>[d])),_:1},8,["onClick"])):(0,l.Q3)("",!0),r,(0,l.bF)(v,{data:h.tableData,style:{width:"100%","margin-top":"20px"}},{default:(0,l.k6)((()=>[(0,l.bF)(_,{prop:"id",label:"ID"}),(0,l.bF)(_,{prop:"box_no",label:"箱号"}),(0,l.bF)(_,{prop:"sales_cost_price",label:"已销售成本"}),(0,l.bF)(_,{prop:"sales_amount",label:"销售金额"}),(0,l.bF)(_,{prop:"profit_loss_amount",label:"总盈亏"}),(0,l.bF)(_,{prop:"sales",label:"销量"}),(0,l.bF)(_,{prop:"stock",label:"库存"}),(0,l.bF)(_,{label:"状态"},{default:(0,l.k6)((e=>[1==e.row.status?((0,l.uX)(),(0,l.Wv)(x,{key:0,type:"success"},{default:(0,l.k6)((()=>[u])),_:1})):(0,l.Q3)("",!0),2==e.row.status?((0,l.uX)(),(0,l.Wv)(x,{key:1,type:"danger"},{default:(0,l.k6)((()=>[p])),_:1})):(0,l.Q3)("",!0)])),_:1})])),_:1},8,["data"]),(0,l.Lk)("div",b,[(0,l.bF)(w,{background:"",layout:"->, prev, pager, next","page-size":h.searchForm.limit,onCurrentChange:m.pageChangeHandle,total:h.page.total},null,8,["page-size","onCurrentChange","total"])])])),_:1}),(0,l.bF)(I,{title:"补箱",modelValue:h.dialogVisible,"onUpdate:modelValue":a[3]||(a[3]=e=>h.dialogVisible=e),width:"400px"},{footer:(0,l.k6)((()=>[(0,l.bF)(F,{onClick:a[2]||(a[2]=e=>h.dialogVisible=!1)},{default:(0,l.k6)((()=>[c])),_:1}),(0,l.bF)(F,{type:"primary",onClick:m.doInc},{default:(0,l.k6)((()=>[g])),_:1},8,["onClick"])])),default:(0,l.k6)((()=>[(0,l.bF)(V,{model:h.form,"label-width":"120px"},{default:(0,l.k6)((()=>[(0,l.bF)(L,{label:"新增箱数"},{default:(0,l.k6)((()=>[(0,l.bF)(C,{modelValue:h.form.num,"onUpdate:modelValue":a[1]||(a[1]=e=>h.form.num=e),type:"number"},null,8,["modelValue"])])),_:1})])),_:1},8,["model"])])),_:1},8,["modelValue"])])),_:1})}t(7414);var m={name:"boxes",data(){return{dialogVisible:!1,tableData:[],searchForm:{blindbox_id:this.$route.query.id,page:1,limit:15},playId:this.$route.query.playId,page:{total:1},form:{blindbox_id:this.$route.query.id,num:0}}},mounted(){this.getList()},methods:{search(){this.getList()},async getList(){let e=await this.$API.blindboxDetail.boxesList.get(this.searchForm);this.tableData=e.data.data,this.page.total=e.data.total},handleSuccess(){this.getList()},pageChangeHandle(e){this.searchForm.page=e,this.getList()},goBack(){this.$router.push({path:"/blindbox/index"})},incBlindbox(){this.dialogVisible=!0},async doInc(){let e=await this.$API.blindboxDetail.incBoxes.post(this.form);0==e.code?(this.$message.success("补箱成功"),this.dialogVisible=!1,this.getList()):this.$message.error(e.msg)}}},k=t(1456);const f=(0,k.A)(m,[["render",h],["__scopeId","data-v-604eaa59"]]);var y=f}}]);