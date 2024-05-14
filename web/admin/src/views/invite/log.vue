<template>
  <el-main>
    <el-card class="box-card" shadow="never">
      <div class="search-box">
        <el-form
          :inline="true"
          :model="searchForm"
          class="demo-form-inline"
          label-width="90px"
        >
          <el-form-item label="邀请人名">
            <el-input
              v-model="searchForm.give_user_name"
              placeholder=""
              clearable
            ></el-input>
          </el-form-item>
          <el-form-item label="被邀请人名">
            <el-input
              v-model="searchForm.from_user_name"
              placeholder=""
              clearable
            ></el-input>
          </el-form-item>
          <el-form-item label="邀请时间">
            <el-date-picker
              v-model="searchForm.create_time"
              type="daterange"
              value-format="YYYY-MM-DD"
              range-separator="至"
              start-placeholder="开始日期"
              end-placeholder="结束日期"
            >
            </el-date-picker>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onSubmit" icon="el-icon-search"
              >查询</el-button
            >
          </el-form-item>
        </el-form>
      </div>
      <div
        style="
          width: 100%;
          height: 0;
          border-bottom: #e4e7ed 1px dashed;
          margin-bottom: 10px;
        "
      ></div>
      <el-table :data="tableData" style="width: 100%">
        <el-table-column prop="give_user_id" label="邀请人id">
        </el-table-column>
        <el-table-column
          prop="give_user_name"
          label="邀请人名"
        ></el-table-column>
        <el-table-column prop="from_user_id" label="被邀请人id">
        </el-table-column>
        <el-table-column prop="from_user_name" label="被邀请人名">
        </el-table-column>
        <el-table-column prop="create_time" label="邀请日期"> </el-table-column>
      </el-table>
      <el-pagination
        style="margin-top: 10px"
        background
        layout="->, prev, pager, next"
        :page-size="searchForm.limit"
        @current-change="handlePageChange"
        :total="total"
      >
      </el-pagination>
    </el-card>
  </el-main>
</template>

<script>
export default {
  name: "receiveLog",
  data() {
    return {
      searchForm: {
        give_user_name: "",
        from_user_name: "",
        create_time: "",
        limit: 15,
        page: 1,
      },
      total: 1,
      tableData: [],
    };
  },
  mounted() {
    this.getList();
  },
  methods: {
    // 查询
    onSubmit() {
      this.searchForm.page = 1;
      this.getList();
    },
    // 翻页
    pageChangeHandle(page) {
      this.searchForm.page = page;
      this.getList();
    },
    // 获取领取记录
    async getList() {
      let res = await this.$API.invite.log.get(this.searchForm);
      this.tableData = res.data.data;
      this.total = res.data.total;
    },
  },
};
</script>

<style scoped>
.primary {
  color: #fff;
  background-color: #409eff;
  border-color: #409eff;
}
.line {
  width: 100%;
  height: 0;
  border-bottom: #e4e7ed 1px dashed;
  margin-top: 15px;
}
</style>
