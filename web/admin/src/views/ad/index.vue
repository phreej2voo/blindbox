<template>
  <el-main>
    <el-card shadow="never">
      <el-button
        type="primary"
        icon="el-icon-plus"
        @click="addTag"
        style="margin-top: 15px"
        >添加广告</el-button
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
        <el-table-column label="图片">
          <template #default="scope">
            <img :src="scope.row.pic" style="width: 40px; height: 40px" />
          </template>
        </el-table-column>
        <el-table-column label="类型">
          <template #default="scope">
            <el-tag type="success" v-if="scope.row.type == 1">每日限定</el-tag>
            <el-tag type="danger" v-if="scope.row.type == 2">新人必买</el-tag>
            <el-tag type="warning" v-if="scope.row.type == 3">买一送一</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="状态">
          <template #default="scope">
            <el-tag type="success" v-if="scope.row.status == 1">正常</el-tag>
            <el-tag type="danger" v-if="scope.row.status == 2">禁用</el-tag>
          </template>
        </el-table-column>
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
  name: "goodsCate",
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
      this.$confirm("此操作将永久删除该广告, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(async () => {
          let res = await this.$API.ad.del.get({ id: row.id });
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
      let res = await this.$API.ad.list.get(this.searchForm);
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
  },
};
</script>