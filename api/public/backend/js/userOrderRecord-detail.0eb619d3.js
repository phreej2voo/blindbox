"use strict";(self["webpackChunkscui"]=self["webpackChunkscui"]||[]).push([[4934],{9380:function(e,t,a){a.r(t),a.d(t,{default:function(){return _}});var l=a(6252),r=a(3577);const s=e=>((0,l.dD)("data-v-0a602790"),e=e(),(0,l.Cn)(),e),o={class:"click-bar",style:{width:"300px",display:"flex"}},i=(0,l.Uk)("返回列表 "),d=s((()=>(0,l._)("span",{style:{"font-size":"14px","font-weight":"700","margin-left":"20px"}},"奖品详情",-1))),c={key:0,style:{color:"#f56c6c"}},p={key:1,style:{color:"#67c23a"}},u=(0,l.Uk)("盒子中"),n=(0,l.Uk)("已兑换"),w=(0,l.Uk)("已提货");function g(e,t,a,s,g,h){const f=(0,l.up)("el-icon-arrow-left"),m=(0,l.up)("el-icon"),_=(0,l.up)("el-card"),k=(0,l.up)("el-table-column"),y=(0,l.up)("el-image"),b=(0,l.up)("el-tag"),W=(0,l.up)("el-table"),v=(0,l.up)("el-main");return(0,l.wg)(),(0,l.j4)(v,null,{default:(0,l.w5)((()=>[(0,l.Wm)(_,{shadow:"false",style:{display:"flex"}},{default:(0,l.w5)((()=>[(0,l._)("div",o,[(0,l._)("div",{onClick:t[0]||(t[0]=e=>h.goBack()),style:{cursor:"pointer"}},[(0,l.Wm)(m,{class:"back-icon"},{default:(0,l.w5)((()=>[(0,l.Wm)(f)])),_:1}),i]),d])])),_:1}),(0,l.Wm)(_,{shadow:"never"},{default:(0,l.w5)((()=>[(0,l.Wm)(W,{data:g.tableData,style:{width:"100%","margin-top":"20px"}},{default:(0,l.w5)((()=>[(0,l.Wm)(k,{prop:"id",label:"ID"}),(0,l.Wm)(k,{prop:"goods_id",label:"商品id"}),(0,l.Wm)(k,{prop:"goods_name",label:"商品名称"}),(0,l.Wm)(k,{label:"商品图片"},{default:(0,l.w5)((e=>[(0,l.Wm)(y,{src:e.row.goods_image,style:{height:"36px",width:"36px"}},null,8,["src"])])),_:1}),(0,l.Wm)(k,{prop:"goods_price",label:"商品价值"}),(0,l.Wm)(k,{prop:"recovery_price",label:"可兑哈希币"}),(0,l.Wm)(k,{label:"盈亏"},{default:(0,l.w5)((e=>[e.row.profit>0?((0,l.wg)(),(0,l.iD)("div",c,(0,r.zw)(e.row.profit),1)):((0,l.wg)(),(0,l.iD)("div",p,(0,r.zw)(e.row.profit),1))])),_:1}),(0,l.Wm)(k,{label:"状态"},{default:(0,l.w5)((e=>[1==e.row.status?((0,l.wg)(),(0,l.j4)(b,{key:0},{default:(0,l.w5)((()=>[u])),_:1})):(0,l.kq)("",!0),2==e.row.status?((0,l.wg)(),(0,l.j4)(b,{key:1,type:"sucess"},{default:(0,l.w5)((()=>[n])),_:1})):(0,l.kq)("",!0),3==e.row.status?((0,l.wg)(),(0,l.j4)(b,{key:2,type:"danger"},{default:(0,l.w5)((()=>[w])),_:1})):(0,l.kq)("",!0)])),_:1})])),_:1},8,["data"])])),_:1})])),_:1})}a(7658);var h={name:"orderRecordDetail",data(){return{dialog:{save:!1},tableData:[],searchForm:{order_record_id:this.$route.query.order_record_id,page:1,limit:15},page:{total:1},goodsTag:[]}},mounted(){this.getList()},methods:{search(){this.getList()},async getList(){let e=await this.$API.userOrderRecordDetail.list.get(this.searchForm);this.tableData=e.data.rows,this.page.total=e.data.total},pageChangeHandle(e){this.searchForm.page=e,this.getList()},goBack(){this.$router.push({path:"/userOrderRecord/index"})}}},f=a(3744);const m=(0,f.Z)(h,[["render",g],["__scopeId","data-v-0a602790"]]);var _=m}}]);