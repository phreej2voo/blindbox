"use strict";(self["webpackChunkscui"]=self["webpackChunkscui"]||[]).push([[8795,5857,9964],{1455:function(e,t,a){a.r(t),a.d(t,{default:function(){return S}});var l=a(6252),s=a(3577);const o=e=>((0,l.dD)("data-v-51f7e1a9"),e=e(),(0,l.Cn)(),e),i={class:"search-box"},d=(0,l.Uk)("已上架"),r=(0,l.Uk)("已下架"),n=(0,l.Uk)("查询"),c=o((()=>(0,l._)("div",{style:{width:"100%",height:"0","border-bottom":"#e4e7ed 1px dashed","margin-bottom":"15px"}},null,-1))),h=(0,l.Uk)("添加商品"),p=(0,l.Uk)("批量上架"),m=(0,l.Uk)("批量下架"),u=o((()=>(0,l._)("div",{style:{width:"100%",height:"0","border-bottom":"#e4e7ed 1px dashed","margin-top":"15px"}},null,-1))),g=(0,l.Uk)("普通商品"),w=(0,l.Uk)("盲盒商品"),f={key:0},y={key:1},k=(0,l.Uk)("上架"),_=(0,l.Uk)("下架"),b=(0,l.Uk)("编辑"),C=(0,l.Uk)("删除"),W={class:"page-div",style:{"margin-top":"20px"}},v={class:"click-bar"},x=(0,l.Uk)("返回 "),F={style:{"font-size":"14px","font-weight":"700"}};function U(e,t,a,o,U,$){const D=(0,l.up)("el-button"),j=(0,l.up)("el-button-group"),q=(0,l.up)("el-form-item"),S=(0,l.up)("el-form"),T=(0,l.up)("el-input"),V=(0,l.up)("el-option"),z=(0,l.up)("el-select"),L=(0,l.up)("el-cascader"),P=(0,l.up)("el-table-column"),G=(0,l.up)("el-image"),I=(0,l.up)("el-tag"),A=(0,l.up)("el-table"),B=(0,l.up)("el-pagination"),E=(0,l.up)("el-card"),H=(0,l.up)("el-icon-arrow-left"),M=(0,l.up)("el-icon"),Z=(0,l.up)("el-divider"),J=(0,l.up)("save-page"),K=(0,l.up)("el-main");return(0,l.wg)(),(0,l.j4)(K,null,{default:(0,l.w5)((()=>[1==U.type?((0,l.wg)(),(0,l.j4)(E,{key:0,shadow:"never"},{default:(0,l.w5)((()=>[(0,l._)("div",i,[(0,l.Wm)(S,{model:U.searchForm},{default:(0,l.w5)((()=>[(0,l.Wm)(q,{label:"商品状态"},{default:(0,l.w5)((()=>[(0,l.Wm)(j,null,{default:(0,l.w5)((()=>[(0,l.Wm)(D,{class:(0,s.C_)({primary:1==U.searchForm.status}),onClick:t[0]||(t[0]=e=>$.checkTab(1))},{default:(0,l.w5)((()=>[d])),_:1},8,["class"]),(0,l.Wm)(D,{class:(0,s.C_)({primary:2==U.searchForm.status}),onClick:t[1]||(t[1]=e=>$.checkTab(2))},{default:(0,l.w5)((()=>[r])),_:1},8,["class"])])),_:1})])),_:1})])),_:1},8,["model"]),(0,l.Wm)(S,{inline:!0,model:U.searchForm,class:"demo-form-inline",style:{"margin-top":"20px"}},{default:(0,l.w5)((()=>[(0,l.Wm)(q,{label:"商品名称"},{default:(0,l.w5)((()=>[(0,l.Wm)(T,{modelValue:U.searchForm.name,"onUpdate:modelValue":t[2]||(t[2]=e=>U.searchForm.name=e),placeholder:"",clearable:""},null,8,["modelValue"])])),_:1}),(0,l.Wm)(q,{label:"商品类型"},{default:(0,l.w5)((()=>[(0,l.Wm)(z,{modelValue:U.searchForm.goods_type,"onUpdate:modelValue":t[3]||(t[3]=e=>U.searchForm.goods_type=e),placeholder:"请选择",clearable:""},{default:(0,l.w5)((()=>[(0,l.Wm)(V,{label:"普通商品",value:"1"}),(0,l.Wm)(V,{label:"盲盒商品",value:"2"})])),_:1},8,["modelValue"])])),_:1}),(0,l.Wm)(q,{label:"商品分类"},{default:(0,l.w5)((()=>[(0,l.Wm)(L,{modelValue:U.selectedCate,"onUpdate:modelValue":t[4]||(t[4]=e=>U.selectedCate=e),options:U.goodsCate,props:U.treeProps,clearable:""},null,8,["modelValue","options","props"])])),_:1}),(0,l.Wm)(q,null,{default:(0,l.w5)((()=>[(0,l.Wm)(D,{type:"primary",onClick:$.onSubmit,icon:"el-icon-search"},{default:(0,l.w5)((()=>[n])),_:1},8,["onClick"])])),_:1})])),_:1},8,["model"])]),c,(0,l.Wm)(D,{type:"primary",icon:"el-icon-plus",onClick:$.addGoods},{default:(0,l.w5)((()=>[h])),_:1},8,["onClick"]),2==U.searchForm.status?((0,l.wg)(),(0,l.j4)(D,{key:0,type:"primary",icon:"el-icon-shopping-cart-full",onClick:t[5]||(t[5]=e=>$.shelves(1))},{default:(0,l.w5)((()=>[p])),_:1})):(0,l.kq)("",!0),1==U.searchForm.status?((0,l.wg)(),(0,l.j4)(D,{key:1,type:"primary",icon:"el-icon-shopping-cart-full",onClick:t[6]||(t[6]=e=>$.shelves(2))},{default:(0,l.w5)((()=>[m])),_:1})):(0,l.kq)("",!0),u,(0,l.Wm)(A,{data:U.tableData,onSelectionChange:$.handleSelectionChange,"row-style":{height:"57px"},style:{width:"100%","font-size":"12px"}},{default:(0,l.w5)((()=>[(0,l.Wm)(P,{type:"selection",width:"55"}),(0,l.Wm)(P,{prop:"id",label:"商品ID"}),(0,l.Wm)(P,{label:"商品图"},{default:(0,l.w5)((e=>[(0,l.Wm)(G,{src:e.row.image.split(",")[0],style:{width:"36px",height:"36px"}},null,8,["src"])])),_:1}),(0,l.Wm)(P,{prop:"name",label:"商品名称"}),(0,l.Wm)(P,{prop:"cate.name",label:"商品分类"}),(0,l.Wm)(P,{label:"商品类型"},{default:(0,l.w5)((e=>[1==e.row.goods_type?((0,l.wg)(),(0,l.j4)(I,{key:0,type:"danger"},{default:(0,l.w5)((()=>[g])),_:1})):(0,l.kq)("",!0),2==e.row.goods_type?((0,l.wg)(),(0,l.j4)(I,{key:1,type:"success"},{default:(0,l.w5)((()=>[w])),_:1})):(0,l.kq)("",!0)])),_:1}),(0,l.Wm)(P,{prop:"price",label:"展示价格"}),(0,l.Wm)(P,{prop:"recovery_price",label:"可兑换哈希币"}),(0,l.Wm)(P,{prop:"integral_price",label:"售价(哈希币)"}),(0,l.Wm)(P,{label:"库存"},{default:(0,l.w5)((e=>[-1==e.row.stock?((0,l.wg)(),(0,l.iD)("span",f,"无限库存")):((0,l.wg)(),(0,l.iD)("span",y,(0,s.zw)(e.row.stock),1))])),_:1}),(0,l.Wm)(P,{label:"状态"},{default:(0,l.w5)((e=>[1==e.row.status?((0,l.wg)(),(0,l.j4)(I,{key:0,type:"success"},{default:(0,l.w5)((()=>[k])),_:1})):(0,l.kq)("",!0),2==e.row.status?((0,l.wg)(),(0,l.j4)(I,{key:1,type:"danger"},{default:(0,l.w5)((()=>[_])),_:1})):(0,l.kq)("",!0)])),_:1}),(0,l.Wm)(P,{fixed:"right",label:"操作",width:"140"},{default:(0,l.w5)((e=>[(0,l.Wm)(D,{onClick:t=>$.handleEdit(e.row),type:"text",size:"small"},{default:(0,l.w5)((()=>[b])),_:2},1032,["onClick"]),(0,l.Wm)(D,{onClick:t=>$.handleDel(e.row),type:"text",size:"small"},{default:(0,l.w5)((()=>[C])),_:2},1032,["onClick"])])),_:1})])),_:1},8,["data","onSelectionChange"]),(0,l._)("div",W,[(0,l.Wm)(B,{background:"",layout:"->, prev, pager, next","page-size":U.searchForm.limit,onCurrentChange:$.pageChangeHandle,total:U.page.total},null,8,["page-size","onCurrentChange","total"])])])),_:1})):(0,l.kq)("",!0),2==U.type?((0,l.wg)(),(0,l.j4)(E,{key:1,shadow:"false",style:{display:"flex"}},{default:(0,l.w5)((()=>[(0,l._)("div",v,[(0,l._)("div",{onClick:t[7]||(t[7]=e=>U.type=1)},[(0,l.Wm)(M,{class:"back-icon"},{default:(0,l.w5)((()=>[(0,l.Wm)(H)])),_:1}),x]),(0,l.Wm)(Z,{direction:"vertical"}),(0,l._)("span",F,(0,s.zw)(U.title),1)])])),_:1})):(0,l.kq)("",!0),2==U.type?((0,l.wg)(),(0,l.j4)(E,{key:2,shadow:"false",style:{"margin-top":"10px"}},{default:(0,l.w5)((()=>[(0,l.Wm)(J,{onSave:$.saveGoods,ref:"saveDialog"},null,8,["onSave"])])),_:1})):(0,l.kq)("",!0)])),_:1})}a(7658);var $=a(6425),D={name:"goods",components:{savePage:$["default"]},data(){return{type:1,selectedCate:"",goodsCate:[],treeProps:{value:"id",label:"name",children:"children",checkStrictly:!0},searchForm:{goods_type:"",cate_id:"",status:1,name:"",page:1,limit:10},page:{total:0},tableData:[],shelfForm:{ids:[],status:1},title:"添加商品"}},mounted(){this.getList(),this.getGoodsCate()},methods:{onSubmit(){this.selectedCate&&this.selectedCate.length>0?this.searchForm.cate_id=this.selectedCate[this.selectedCate.length-1]:this.searchForm.cate_id=0,this.searchForm.page=1,this.getList()},checkTab(e){this.searchForm.status=e,this.getList()},addGoods(){this.type=2,this.title="添加商品",this.$nextTick((()=>{this.$refs.saveDialog.setMode("add")}))},async shelves(e){if(0==this.shelfForm.ids.length)return void this.$message.error("请先勾选要操作的商品");this.shelfForm.type=e;let t="您确定你要批量上架这批商品?";2==e&&(t="您确定你要批量下架这批商品?"),this.$confirm(t,"提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((async()=>{let e=await this.$API.goods.shelves.post(this.shelfForm);0==e.code?(this.$message.success(e.msg),this.getList()):this.$message.error(e.msg)})).catch((()=>{}))},pageChangeHandle(e){this.searchForm.page=e,this.getList()},handleSelectionChange(e){this.shelfForm.ids=[],e.forEach((e=>{this.shelfForm.ids.push(e.id)}))},handleEdit(e){this.type=2,this.title="编辑商品",this.$nextTick((()=>{this.$refs.saveDialog.setMode("edit").setData(e)}))},handleDel(e){this.$confirm("此操作将删除该商品, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((async()=>{let t=await this.$API.goods.del.get({id:e.id});0==t.code?(this.$message.success(t.msg),this.getList()):this.$message.error(t.msg)})).catch((()=>{}))},saveGoods(){this.type=1,this.getList()},async getList(){let e=await this.$API.goods.list.get(this.searchForm);this.tableData=e.data.rows,this.page.total=e.data.total},async getGoodsCate(){let e=await this.$API.goodsCate.list.get({hashmart_auth_skip:1});this.goodsCate=e.data}}},j=a(3744);const q=(0,j.Z)(D,[["render",U],["__scopeId","data-v-51f7e1a9"]]);var S=q}}]);