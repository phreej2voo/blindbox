"use strict";(self["webpackChunkscui"]=self["webpackChunkscui"]||[]).push([[8069],{3473:function(e,t,a){a.r(t),a.d(t,{default:function(){return y}});var l=a(7267),r=a(5710);const s=e=>((0,l.Qi)("data-v-7ab99d92"),e=e(),(0,l.jt)(),e),o={class:"click-bar",style:{width:"300px",display:"flex"}},d=(0,l.eW)("返回列表 "),i=s((()=>(0,l.Lk)("span",{style:{"font-size":"14px","font-weight":"700","margin-left":"20px"}},"奖品详情",-1))),u={key:0,style:{color:"#f56c6c"}},c={key:1,style:{color:"#67c23a"}},n=(0,l.eW)("盒子中"),p=(0,l.eW)("已兑换"),g=(0,l.eW)("已提货"),b=(0,l.eW)("已合成");function k(e,t,a,s,k,f){const h=(0,l.g2)("el-icon-arrow-left"),_=(0,l.g2)("el-icon"),y=(0,l.g2)("el-card"),w=(0,l.g2)("el-table-column"),v=(0,l.g2)("el-image"),F=(0,l.g2)("el-tag"),m=(0,l.g2)("el-table"),W=(0,l.g2)("el-main");return(0,l.uX)(),(0,l.Wv)(W,null,{default:(0,l.k6)((()=>[(0,l.bF)(y,{shadow:"false",style:{display:"flex"}},{default:(0,l.k6)((()=>[(0,l.Lk)("div",o,[(0,l.Lk)("div",{onClick:t[0]||(t[0]=e=>f.goBack()),style:{cursor:"pointer"}},[(0,l.bF)(_,{class:"back-icon"},{default:(0,l.k6)((()=>[(0,l.bF)(h)])),_:1}),d]),i])])),_:1}),(0,l.bF)(y,{shadow:"never"},{default:(0,l.k6)((()=>[(0,l.bF)(m,{data:k.tableData,style:{width:"100%","margin-top":"20px"}},{default:(0,l.k6)((()=>[(0,l.bF)(w,{prop:"id",label:"ID"}),(0,l.bF)(w,{prop:"goods_id",label:"商品id"}),(0,l.bF)(w,{prop:"goods_name",label:"商品名称"}),(0,l.bF)(w,{label:"商品图片"},{default:(0,l.k6)((e=>[(0,l.bF)(v,{src:e.row.goods_image,style:{height:"36px",width:"36px"}},null,8,["src"])])),_:1}),(0,l.bF)(w,{prop:"goods_price",label:"商品价值"}),(0,l.bF)(w,{prop:"recovery_price",label:"可兑哈希币"}),(0,l.bF)(w,{label:"盈亏"},{default:(0,l.k6)((e=>[e.row.profit>0?((0,l.uX)(),(0,l.CE)("div",u,(0,r.v_)(e.row.profit),1)):((0,l.uX)(),(0,l.CE)("div",c,(0,r.v_)(e.row.profit),1))])),_:1}),(0,l.bF)(w,{label:"状态"},{default:(0,l.k6)((e=>[1==e.row.status?((0,l.uX)(),(0,l.Wv)(F,{key:0},{default:(0,l.k6)((()=>[n])),_:1})):(0,l.Q3)("",!0),2==e.row.status?((0,l.uX)(),(0,l.Wv)(F,{key:1,type:"sucess"},{default:(0,l.k6)((()=>[p])),_:1})):(0,l.Q3)("",!0),3==e.row.status?((0,l.uX)(),(0,l.Wv)(F,{key:2,type:"danger"},{default:(0,l.k6)((()=>[g])),_:1})):(0,l.Q3)("",!0),4==e.row.status?((0,l.uX)(),(0,l.Wv)(F,{key:3,type:"warning"},{default:(0,l.k6)((()=>[b])),_:1})):(0,l.Q3)("",!0)])),_:1})])),_:1},8,["data"])])),_:1})])),_:1})}a(7414);var f={name:"orderRecordDetail",data(){return{dialog:{save:!1},tableData:[],searchForm:{order_record_id:this.$route.query.order_record_id,page:1,limit:15},page:{total:1},goodsTag:[]}},mounted(){this.getList()},methods:{search(){this.getList()},async getList(){let e=await this.$API.userOrderRecordDetail.list.get(this.searchForm);this.tableData=e.data.rows,this.page.total=e.data.total},pageChangeHandle(e){this.searchForm.page=e,this.getList()},goBack(){this.$router.push({path:"/userOrderRecord/index"})}}},h=a(1456);const _=(0,h.A)(f,[["render",k],["__scopeId","data-v-7ab99d92"]]);var y=_}}]);