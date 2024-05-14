<template>
  <el-main>
    <el-card shadow="never">
      <div shadow="never" style="border: none">
        <el-form :inline="true" :model="searchForm" class="demo-form-inline">
          <el-form-item label="门店名称">
            <el-input
              v-model="searchForm.name"
              placeholder="门店名称"
              clearable
            ></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="search">查询</el-button>
          </el-form-item>
        </el-form>
      </div>
      <div
        style="
          width: 100%;
          height: 0;
          border-bottom: #e4e7ed 1px dashed;
          margin-top: 15px;
        "
      ></div>
      <el-button
        type="primary"
        icon="el-icon-plus"
        @click="addTag"
        style="margin-top: 15px"
        >添加门店</el-button
      >
      <div
        style="
          width: 100%;
          height: 0;
          border-bottom: #e4e7ed 1px dashed;
          margin-top: 15px;
        "
      ></div>

      <el-table :data="tableData" style="width: 100%; margin-top: 20px">
        <el-table-column prop="id" label="ID"> </el-table-column>
        <el-table-column prop="name" label="门店名称"> </el-table-column>
	    <el-table-column prop="user" label="关联用户"> </el-table-column>
	    <el-table-column prop="address" label="门店地址"> </el-table-column>
        <el-table-column label="是否显示">
          <template #default="scope">
            <el-tag type="success" v-if="scope.row.status == 1">显示</el-tag>
            <el-tag type="danger" v-if="scope.row.status == 2">隐藏</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="sort" label="排序"> </el-table-column>
        <el-table-column label="操作">
          <template #default="scope">
            <el-button @click="handleEdit(scope.row)" type="text" size="small"
              >编辑</el-button
            >
            <el-button @click="handleDel(scope.row)" type="text" size="small"
              >删除</el-button
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
  </el-main>
</template>

<script>
import saveDialog from "./save";

export default {
  name: "blindboxType",
  components: {
    saveDialog,
  },
  data() {
    return {
      dialog: {
        save: false,
      },
      tableData: [],
      searchForm: {
        name: "",
        page: 1,
        limit: 15,
      },
      page: {
        total: 1,
      },
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
    addTag() {
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
      this.$confirm("此操作将永久删除该标签, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(async () => {
          let res = await this.$API.store.del.get({ id: row.id });
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
      let res = await this.$API.store.list.get(this.searchForm);
      this.tableData = res.data.rows;
      this.page.total = res.data.total;
    },
    handleSuccess() {
      this.getList();
    },
    pageChangeHandle(page) {
      this.searchForm.page = page;
      this.getList();
    },
  },
};
</script>
