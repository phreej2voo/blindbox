"use strict";(self["webpackChunkscui"]=self["webpackChunkscui"]||[]).push([[6014,4507],{5721:function(e,a,t){t.r(a),t.d(a,{default:function(){return x}});var l=t(6252),i=t(3577);const s=e=>((0,l.dD)("data-v-29e2aafe"),e=e(),(0,l.Cn)(),e),o={shadow:"never",style:{border:"none"}},n=(0,l.Uk)("查询"),d=(0,l.Uk)("创建福利房"),r=s((()=>(0,l._)("div",{class:"line"},null,-1))),u=["src"],p=(0,l.Uk)("大佬房"),c=(0,l.Uk)("密码房"),m=(0,l.Uk)("进行中"),g=(0,l.Uk)("已发放"),w=(0,l.Uk)("发放失败"),h=(0,l.Uk)("被关闭"),k=(0,l.Uk)("参与详情"),f=(0,l.Uk)("编辑"),y=(0,l.Uk)("关闭"),_={class:"page-div",style:{"margin-top":"20px"}};function b(e,a,t,s,b,v){const W=(0,l.up)("el-input"),C=(0,l.up)("el-form-item"),j=(0,l.up)("el-button"),x=(0,l.up)("el-form"),D=(0,l.up)("el-table-column"),U=(0,l.up)("el-tag"),V=(0,l.up)("el-table"),$=(0,l.up)("el-pagination"),q=(0,l.up)("el-card"),F=(0,l.up)("save-dialog"),I=(0,l.up)("el-dialog"),T=(0,l.up)("el-main");return(0,l.wg)(),(0,l.j4)(T,null,{default:(0,l.w5)((()=>[(0,l.Wm)(q,{shadow:"never"},{default:(0,l.w5)((()=>[(0,l._)("div",o,[(0,l.Wm)(x,{inline:!0,model:b.searchForm,class:"demo-form-inline"},{default:(0,l.w5)((()=>[(0,l.Wm)(C,{label:"房间名称"},{default:(0,l.w5)((()=>[(0,l.Wm)(W,{modelValue:b.searchForm.title,"onUpdate:modelValue":a[0]||(a[0]=e=>b.searchForm.title=e),placeholder:"房间名称",clearable:""},null,8,["modelValue"])])),_:1}),(0,l.Wm)(C,null,{default:(0,l.w5)((()=>[(0,l.Wm)(j,{type:"primary",onClick:v.search},{default:(0,l.w5)((()=>[n])),_:1},8,["onClick"])])),_:1})])),_:1},8,["model"])]),(0,l.Wm)(j,{type:"primary",icon:"el-icon-plus",onClick:v.addRoll,style:{"margin-top":"15px"}},{default:(0,l.w5)((()=>[d])),_:1},8,["onClick"]),r,(0,l.Wm)(V,{data:b.tableData,style:{width:"100%","margin-top":"20px"}},{default:(0,l.w5)((()=>[(0,l.Wm)(D,{prop:"id",label:"ID"}),(0,l.Wm)(D,{prop:"title",label:"房间名称"}),(0,l.Wm)(D,{label:"创建人"},{default:(0,l.w5)((e=>[(0,l._)("img",{src:e.row.avatar,style:{width:"40px",height:"40px"}},null,8,u),(0,l.Uk)(" "+(0,i.zw)(e.row.username),1)])),_:1}),(0,l.Wm)(D,{label:"类型"},{default:(0,l.w5)((e=>[1==e.row.type?((0,l.wg)(),(0,l.j4)(U,{key:0,type:"success"},{default:(0,l.w5)((()=>[p])),_:1})):(0,l.kq)("",!0),2==e.row.type?((0,l.wg)(),(0,l.j4)(U,{key:1,type:"danger"},{default:(0,l.w5)((()=>[c])),_:1})):(0,l.kq)("",!0)])),_:1}),(0,l.Wm)(D,{label:"状态"},{default:(0,l.w5)((e=>[1==e.row.status?((0,l.wg)(),(0,l.j4)(U,{key:0},{default:(0,l.w5)((()=>[m])),_:1})):(0,l.kq)("",!0),2==e.row.status?((0,l.wg)(),(0,l.j4)(U,{key:1,type:"success"},{default:(0,l.w5)((()=>[g])),_:1})):(0,l.kq)("",!0),3==e.row.status?((0,l.wg)(),(0,l.j4)(U,{key:2,type:"danger"},{default:(0,l.w5)((()=>[w])),_:1})):(0,l.kq)("",!0),4==e.row.status?((0,l.wg)(),(0,l.j4)(U,{key:3,type:"warning"},{default:(0,l.w5)((()=>[h])),_:1})):(0,l.kq)("",!0)])),_:1}),(0,l.Wm)(D,{label:"操作"},{default:(0,l.w5)((e=>[(0,l.Wm)(j,{onClick:a=>v.handleJoin(e.row),type:"text",size:"small"},{default:(0,l.w5)((()=>[k])),_:2},1032,["onClick"]),4!=e.row.status?((0,l.wg)(),(0,l.j4)(j,{key:0,onClick:a=>v.handleEdit(e.row),type:"text",size:"small"},{default:(0,l.w5)((()=>[f])),_:2},1032,["onClick"])):(0,l.kq)("",!0),4!=e.row.status?((0,l.wg)(),(0,l.j4)(j,{key:1,onClick:a=>v.handleDel(e.row),type:"text",size:"small"},{default:(0,l.w5)((()=>[y])),_:2},1032,["onClick"])):(0,l.kq)("",!0)])),_:1})])),_:1},8,["data"]),(0,l._)("div",_,[(0,l.Wm)($,{background:"",layout:"->, prev, pager, next","page-size":b.searchForm.limit,onCurrentChange:v.pageChangeHandle,total:b.page.total},null,8,["page-size","onCurrentChange","total"])])])),_:1}),b.dialog.save?((0,l.wg)(),(0,l.j4)(F,{key:0,ref:"saveDialog",onSuccess:v.handleSuccess,onClosed:a[1]||(a[1]=e=>b.dialog.save=!1)},null,8,["onSuccess"])):(0,l.kq)("",!0),(0,l.Wm)(I,{title:"参与详情",modelValue:b.joinVisiable,"onUpdate:modelValue":a[2]||(a[2]=e=>b.joinVisiable=e),width:"800px"},{default:(0,l.w5)((()=>[(0,l.Wm)(V,{data:b.joinTable,style:{width:"100%"}},{default:(0,l.w5)((()=>[(0,l.Wm)(D,{prop:"id",label:"ID"}),(0,l.Wm)(D,{prop:"user_id",label:"参与人ID"}),(0,l.Wm)(D,{prop:"username",label:"参与人"}),(0,l.Wm)(D,{prop:"create_time",label:"参与时间"})])),_:1},8,["data"])])),_:1},8,["modelValue"])])),_:1})}var v=t(4585),W={name:"Roll",components:{saveDialog:v["default"]},data(){return{dialog:{save:!1},joinVisiable:!1,tableData:[],searchForm:{title:"",page:1,limit:15},page:{total:1},joinTable:[]}},mounted(){this.getList()},methods:{search(){this.getList()},addRoll(){this.dialog.save=!0,this.$nextTick((()=>{this.$refs.saveDialog.open()}))},handleEdit(e){this.dialog.save=!0,this.$nextTick((()=>{this.$refs.saveDialog.open("edit").setData(e)}))},handleDel(e){this.$confirm("此操作将关闭该福利房, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((async()=>{let a=await this.$API.roll.del.get({id:e.id});0==a.code?(this.$message.success(a.msg),this.getList()):this.$message.error(a.msg)})).catch((()=>{}))},async getList(){let e=await this.$API.roll.list.get(this.searchForm);this.tableData=e.data.data,this.page.total=e.data.total},handleSuccess(){this.getList()},pageChangeHandle(e){this.searchForm.page=e,this.getList()},async handleJoin(e){let a=await this.$API.roll.detail.get({id:e.id});this.joinTable=a.data,this.joinVisiable=!0}}},C=t(3744);const j=(0,C.Z)(W,[["render",b],["__scopeId","data-v-29e2aafe"]]);var x=j}}]);