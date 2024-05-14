<template>
  <el-main>
    <el-card shadow="never">
      <div shadow="never" style="border: none">
        <el-form :inline="true" :model="searchForm" class="demo-form-inline">
          <el-form-item label="等级名称">
            <el-input
              v-model="searchForm.title"
              placeholder="昵称"
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
        @click="addUser()"
        style="margin-top: 15px"
        >添加等级</el-button
      >
      <div
        style="
          width: 100%;
          height: 0;
          border-bottom: #e4e7ed 1px dashed;
          margin-top: 15px;
        "
      ></div>
      <el-table :data="tableData" style="width: 100%">
        <el-table-column label="图标">
          <template #default="scope">
            <el-image
              :src="scope.row.icon"
              style="width: 36px; height: 36px"
              fit="cover"
            />
          </template>
        </el-table-column>
        <el-table-column label="背景图">
          <template #default="scope">
            <el-image
              :src="scope.row.card_bg"
              style="width: 36px; height: 36px"
              fit="cover"
            />
          </template>
        </el-table-column>
        <el-table-column prop="title" label="等级名称"> </el-table-column>
        <el-table-column prop="level" label="等级值"> </el-table-column>
        <!--<el-table-column prop="discount" label="享受折扣">
        </el-table-column
        >-->
        <el-table-column prop="experience" label="经验值"> </el-table-column>
        <el-table-column label="是否显示">
          <template #default="scope">
            <el-tag type="success" v-if="scope.row.status == 1">显示</el-tag>
            <el-tag type="danger" v-if="scope.row.status == 2">隐藏</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="operation" label="操作">
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

      <div style="margin-top: 20px"></div>
      <el-pagination
        background
        layout="->, prev, pager, next"
        :total="total"
        :page-size="searchForm.limit"
        @current-change="pageChange"
      />
    </el-card>
  </el-main>

  <save-dialog
    v-if="dialog.save"
    ref="saveDialog"
    @success="handleSuccess"
    @closed="dialog.save = false"
    :close-on-click-modal="false"
  ></save-dialog>
</template>

<script>
import saveDialog from "./levelSave";
export default {
  name: "userLevel",
  components: {
    saveDialog,
  },
  data() {
    return {
      dialog: {
        save: false,
        integral: false,
        balance: false,
      },
      total: 1,
      searchForm: {
        title: "",
        page: 1,
        limit: 15,
      },
      tableData: [],
    };
  },
  mounted() {
    this.getList();
  },
  methods: {
    search() {
      this.getList();
    },
    // 添加会员
    addUser() {
      this.dialog.save = true;
      this.$nextTick(() => {
        this.$refs.saveDialog.open();
      });
    },
    handleEdit(row) {
      this.dialog.save = true;
      this.$nextTick(() => {
        this.$refs.saveDialog.open("edit").setData(row);
      });
    },
    pageChange(page) {
      this.searchForm.page = page;
      this.getList();
    },
    // 获取列表
    async getList() {
      let res = await this.$API.userLevel.list.get(this.searchForm);
      this.tableData = res.data.rows;
      this.total = res.data.total;
    },
    // 删除用户等级
    handleDel(row) {
      this.$confirm("此操作将永久删除该等级, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(async () => {
          let res = await this.$API.userLevel.del.get({ id: row.id });
          if (res.code === 0) {
            this.$message.success(res.msg);
            await this.getList();
          } else {
            this.$message.error(res.msg);
          }
        })
        .catch(() => {});
    },
    handleSuccess() {
      this.getList();
    },
  },
};
</script>
