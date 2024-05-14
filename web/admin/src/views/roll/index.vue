<template>
  <el-main>
    <el-card shadow="never">
      <div shadow="never" style="border: none">
        <el-form :inline="true" :model="searchForm" class="demo-form-inline">
          <el-form-item label="房间名称">
            <el-input
              v-model="searchForm.title"
              placeholder="房间名称"
              clearable
            ></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="search">查询</el-button>
          </el-form-item>
        </el-form>
      </div>
      <el-button
        type="primary"
        icon="el-icon-plus"
        @click="addRoll"
        style="margin-top: 15px"
        >创建福利房</el-button
      >
      <div class="line"></div>
      <el-table :data="tableData" style="width: 100%; margin-top: 20px">
        <el-table-column prop="id" label="ID"> </el-table-column>
        <el-table-column prop="title" label="房间名称"> </el-table-column>
        <el-table-column label="创建人">
          <template #default="scope">
            <img :src="scope.row.avatar" style="width: 40px; height: 40px" />
            {{ scope.row.username }}
          </template>
        </el-table-column>
        <el-table-column label="类型">
          <template #default="scope">
            <el-tag type="success" v-if="scope.row.type == 1">大佬房</el-tag>
            <el-tag type="danger" v-if="scope.row.type == 2">密码房</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="状态">
          <template #default="scope">
            <el-tag v-if="scope.row.status == 1">进行中</el-tag>
            <el-tag type="success" v-if="scope.row.status == 2">已发放</el-tag>
            <el-tag type="danger" v-if="scope.row.status == 3">发放失败</el-tag>
            <el-tag type="warning" v-if="scope.row.status == 4">被关闭</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="操作">
          <template #default="scope">
            <el-button @click="handleJoin(scope.row)" type="text" size="small"
              >参与详情</el-button
            >
            <el-button
              @click="handleEdit(scope.row)"
              type="text"
              size="small"
              v-if="scope.row.status != 4"
              >编辑</el-button
            >
            <el-button
              @click="handleDel(scope.row)"
              type="text"
              size="small"
              v-if="scope.row.status != 4"
              >关闭</el-button
            >
          </template>
        </el-table-column>
      </el-table>
      <div class="page-div" style="margin-top: 20px">
        <el-pagination
          background
          layout="->, prev, pager, next"
          :page-size="searchForm.limit"
          @current-change="pageChangeHandle"
          :total="page.total"
        >
        </el-pagination>
      </div>
    </el-card>

    <save-dialog
      v-if="dialog.save"
      ref="saveDialog"
      @success="handleSuccess"
      @closed="dialog.save = false"
    ></save-dialog>

    <el-dialog title="参与详情" v-model="joinVisiable" width="800px">
      <el-table :data="joinTable" style="width: 100%">
        <el-table-column prop="id" label="ID"> </el-table-column>
        <el-table-column prop="user_id" label="参与人ID"> </el-table-column>
        <el-table-column prop="username" label="参与人"> </el-table-column>
        <el-table-column prop="create_time" label="参与时间"> </el-table-column>
      </el-table>
    </el-dialog>
  </el-main>
</template>

<script>
import saveDialog from "./save";

export default {
  name: "Roll",
  components: {
    saveDialog,
  },
  data() {
    return {
      dialog: {
        save: false,
      },
      joinVisiable: false,
      tableData: [],
      searchForm: {
        title: "",
        page: 1,
        limit: 15,
      },
      page: {
        total: 1,
      },
      joinTable: [],
    };
  },
  mounted() {
    this.getList();
  },
  methods: {
    // 查询
    search() {
      this.getList();
    },
    // 添加
    addRoll() {
      this.dialog.save = true;
      this.$nextTick(() => {
        this.$refs.saveDialog.open();
      });
    },
    // 编辑
    handleEdit(row) {
      this.dialog.save = true;
      this.$nextTick(() => {
        this.$refs.saveDialog.open("edit").setData(row);
      });
    },
    // 删除
    handleDel(row) {
      this.$confirm("此操作将关闭该福利房, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(async () => {
          let res = await this.$API.roll.del.get({ id: row.id });
          if (res.code == 0) {
            this.$message.success(res.msg);
            this.getList();
          } else {
            this.$message.error(res.msg);
          }
        })
        .catch(() => {});
    },
    // 获取列表
    async getList() {
      let res = await this.$API.roll.list.get(this.searchForm);
      this.tableData = res.data.data;
      this.page.total = res.data.total;
    },
    handleSuccess() {
      this.getList();
    },
    pageChangeHandle(page) {
      this.searchForm.page = page;
      this.getList();
    },
    // 参与详情
    async handleJoin(row) {
      let res = await this.$API.roll.detail.get({ id: row.id });
      this.joinTable = res.data;

      this.joinVisiable = true;
    },
  },
};
</script>

<style scoped>
.line {
  width: 100%;
  height: 0;
  border-bottom: #e4e7ed 1px dashed;
  margin-top: 15px;
}
</style>