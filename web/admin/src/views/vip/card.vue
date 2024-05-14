<template>
  <el-main>
    <el-card shadow="never" v-if="type == 1">
      <div class="search-box">
        <el-form
          :inline="true"
          :model="searchForm"
          class="demo-form-inline"
          label-width="70px"
        >
          <el-form-item label="会员卡名">
            <el-input
              v-model="searchForm.title"
              placeholder=""
              clearable
            ></el-input>
          </el-form-item>
          <el-form-item label="类型">
            <el-select v-model="searchForm.type" placeholder="请选择" clearable>
              <el-option label="周卡" value="1" />
              <el-option label="月卡" value="2" />
            </el-select>
          </el-form-item>
          <el-form-item label="状态">
            <el-select
              v-model="searchForm.status"
              placeholder="请选择"
              clearable
            >
              <el-option label="正常" value="1" />
              <el-option label="作废" value="2" />
            </el-select>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onSubmit" icon="el-icon-search"
              >查询</el-button
            >
          </el-form-item>
        </el-form>
      </div>
      <div class="line"></div>
      <el-button
        type="primary"
        icon="el-icon-plus"
        @click="addCard"
        style="margin-top: 15px"
        >添加会员卡</el-button
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
        <el-table-column label="类型">
          <template #default="scope">
            <!--<el-tag type="success" v-if="scope.row.type == 1">周卡</el-tag>
            <el-tag type="danger" v-if="scope.row.type == 2">月卡</el-tag>-->
			<el-tag type="danger" v-if="scope.row.type == 3">年卡</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="title" label="标题"> </el-table-column>
        <el-table-column prop="price" label="价格"> </el-table-column>
        <el-table-column label="状态">
          <template #default="scope">
            <el-tag type="success" v-if="scope.row.status == 1">正常</el-tag>
            <el-tag type="danger" v-if="scope.row.status == 2">作废</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="库存">
          <template #default="scope">
            <div v-if="scope.row.stock == -1">无限制</div>
            <div v-else>{{ scope.row.stock }}</div>
          </template>
        </el-table-column>
        <el-table-column prop="create_time" label="创建时间"> </el-table-column>
        <el-table-column label="操作">
          <template #default="scope">
            <el-button @click="handleEdit(scope.row)" type="text" size="small"
              >编辑</el-button
            >
            <el-button
              @click="handleDel(scope.row)"
              type="text"
              size="small"
              v-if="scope.row.status == 1"
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

    <el-card
      v-if="type == 2"
      shadow="false"
      style="display: flex; cursor: pointer"
    >
      <div class="click-bar" style="display: flex">
        <div @click="type = 1">
          <el-icon class="back-icon"><el-icon-arrow-left /></el-icon>返回
        </div>
        <el-divider direction="vertical" />
        <span style="font-size: 14px; font-weight: 700">{{ title }}</span>
      </div>
    </el-card>
    <el-card v-if="type == 2" shadow="false" style="margin-top: 10px">
      <save-page @save="saveSuccess" ref="saveDialog"></save-page>
    </el-card>
  </el-main>
</template>

<script>
import savePage from "./add";

export default {
  name: "vip_card",
  components: {
    savePage,
  },
  data() {
    return {
      dialog: {
        save: false,
      },
      tableData: [],
      searchForm: {
        title: "",
        type: "",
        status: "",
        page: 1,
        limit: 15,
      },
      type: 1,
      page: {
        total: 1,
      },
      title: "新增会员卡",
      logTableData: [], // 领取记录
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
    addCard() {
      this.type = 2;
      this.title = "新增会员卡";
      this.$nextTick(() => {
        this.$refs.saveDialog.setMode("add", 0);
      });
    },
    // 编辑
    handleEdit(row) {
      this.type = 2;
      this.title = "编辑会员卡";
      this.$nextTick(() => {
        this.$refs.saveDialog.setMode("edit", row.id);
      });
    },
    // 删除
    handleDel(row) {
      this.$confirm("此操作将作废会员卡, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(async () => {
          let res = await this.$API.vipCard.del.get({ id: row.id });
          if (res.code == 0) {
            this.$message.success(res.msg);
            this.getList();
          } else {
            this.$message.error(res.msg);
          }
        })
        .catch(() => {});
    },
    // 查询
    onSubmit() {
      this.getList();
    },
    // 获取列表
    async getList() {
      let res = await this.$API.vipCard.list.get(this.searchForm);
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
    saveSuccess() {
      this.type = 1;
      this.getList();
    },
  },
};
</script>

<style scoped>
.line {
  width: 100%;
  height: 0;
  border-bottom: #e4e7ed 1px dashed;
  margin-bottom: 10px;
}
</style>
