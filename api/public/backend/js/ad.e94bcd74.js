"use strict";(self["webpackChunkscui"]=self["webpackChunkscui"]||[]).push([[2321,9734,9964],{6747:function(e,t,a){a.r(t),a.d(t,{default:function(){return v}});var s=a(6252);const l=(0,s.Uk)("添加广告"),i=(0,s._)("div",{style:{width:"100%",height:"0","border-bottom":"#e4e7ed 1px dashed","margin-top":"15px"}},null,-1),n=["src"],o=(0,s.Uk)("每日限定"),d=(0,s.Uk)("新人必买"),r=(0,s.Uk)("买一送一"),g=(0,s.Uk)("正常"),u=(0,s.Uk)("禁用"),c=(0,s.Uk)("编辑"),p=(0,s.Uk)("删除"),h={class:"page-div",style:{"margin-top":"20px"}};function w(e,t,a,w,m,k){const f=(0,s.up)("el-button"),y=(0,s.up)("el-table-column"),v=(0,s.up)("el-tag"),_=(0,s.up)("el-table"),b=(0,s.up)("el-pagination"),C=(0,s.up)("el-card"),x=(0,s.up)("save-dialog"),D=(0,s.up)("el-main");return(0,s.wg)(),(0,s.j4)(D,null,{default:(0,s.w5)((()=>[(0,s.Wm)(C,{shadow:"never"},{default:(0,s.w5)((()=>[(0,s.Wm)(f,{type:"primary",icon:"el-icon-plus",onClick:k.addTag,style:{"margin-top":"15px"}},{default:(0,s.w5)((()=>[l])),_:1},8,["onClick"]),i,(0,s.Wm)(_,{data:m.tableData,style:{width:"100%","margin-top":"20px"}},{default:(0,s.w5)((()=>[(0,s.Wm)(y,{prop:"id",label:"ID"}),(0,s.Wm)(y,{label:"图片"},{default:(0,s.w5)((e=>[(0,s._)("img",{src:e.row.pic,style:{width:"40px",height:"40px"}},null,8,n)])),_:1}),(0,s.Wm)(y,{label:"类型"},{default:(0,s.w5)((e=>[1==e.row.type?((0,s.wg)(),(0,s.j4)(v,{key:0,type:"success"},{default:(0,s.w5)((()=>[o])),_:1})):(0,s.kq)("",!0),2==e.row.type?((0,s.wg)(),(0,s.j4)(v,{key:1,type:"danger"},{default:(0,s.w5)((()=>[d])),_:1})):(0,s.kq)("",!0),3==e.row.type?((0,s.wg)(),(0,s.j4)(v,{key:2,type:"warning"},{default:(0,s.w5)((()=>[r])),_:1})):(0,s.kq)("",!0)])),_:1}),(0,s.Wm)(y,{label:"状态"},{default:(0,s.w5)((e=>[1==e.row.status?((0,s.wg)(),(0,s.j4)(v,{key:0,type:"success"},{default:(0,s.w5)((()=>[g])),_:1})):(0,s.kq)("",!0),2==e.row.status?((0,s.wg)(),(0,s.j4)(v,{key:1,type:"danger"},{default:(0,s.w5)((()=>[u])),_:1})):(0,s.kq)("",!0)])),_:1}),(0,s.Wm)(y,{label:"操作"},{default:(0,s.w5)((e=>[(0,s.Wm)(f,{onClick:t=>k.handleEdit(e.row),type:"text",size:"small"},{default:(0,s.w5)((()=>[c])),_:2},1032,["onClick"]),(0,s.Wm)(f,{onClick:t=>k.handleDel(e.row),type:"text",size:"small"},{default:(0,s.w5)((()=>[p])),_:2},1032,["onClick"])])),_:1})])),_:1},8,["data"]),(0,s._)("div",h,[(0,s.Wm)(b,{background:"",layout:"->, prev, pager, next","page-size":m.searchForm.limit,onCurrentChange:k.pageChangeHandle,total:m.page.total},null,8,["page-size","onCurrentChange","total"])])])),_:1}),m.dialog.save?((0,s.wg)(),(0,s.j4)(x,{key:0,ref:"saveDialog",onSuccess:k.handleSuccess,onClosed:t[0]||(t[0]=e=>m.dialog.save=!1)},null,8,["onSuccess"])):(0,s.kq)("",!0)])),_:1})}var m=a(4595),k={name:"goodsCate",components:{saveDialog:m["default"]},data(){return{dialog:{save:!1},tableData:[],searchForm:{name:"",page:1,limit:15},page:{total:1}}},mounted(){this.getList()},methods:{search(){this.getList()},addTag(){this.dialog.save=!0,this.$nextTick((()=>{this.$refs.saveDialog.open()}))},handleEdit(e){this.dialog.save=!0,this.$nextTick((()=>{this.$refs.saveDialog.open("edit").setData(e)}))},handleDel(e){this.$confirm("此操作将永久删除该广告, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((async()=>{let t=await this.$API.ad.del.get({id:e.id});0==t.code?(this.$message.success(t.msg),this.getList()):this.$message.error(t.msg)})).catch((()=>{}))},async getList(){let e=await this.$API.ad.list.get(this.searchForm);this.tableData=e.data.data,this.page.total=e.data.total},handleSuccess(){this.getList()},pageChangeHandle(e){this.searchForm.page=e,this.getList()}}},f=a(3744);const y=(0,f.Z)(k,[["render",w]]);var v=y}}]);