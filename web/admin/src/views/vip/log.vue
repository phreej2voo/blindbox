<template>
  <el-main>
    <el-card shadow="never">
      <div class="search-box">
        <el-form
          :inline="true"
          :model="searchForm"
          class="demo-form-inline"
          label-width="70px"
        >
          <el-form-item label="领取人ID">
            <el-input
              v-model="searchForm.user_id"
              placeholder=""
              clearable
            ></el-input>
          </el-form-item>
          <el-form-item label="领取日期">
            <el-date-picker
              v-model="searchForm.receive_date"
              type="date"
              placeholder="选择日期"
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
      <div class="line"></div>
      <el-table :data="tableData" style="width: 100%; margin-top: 20px">
        <el-table-column prop="id" label="ID"> </el-table-column>
        <el-table-column prop="receive_date" label="领取日期">
        </el-table-column>
        <el-table-column label="奖品类型">
          <template #default="scope">
            <el-tag type="success" v-if="scope.row.award_type == 'balance'"
              >余额</el-tag
            >
            <el-tag type="danger" v-if="scope.row.award_type == 'coupon'"
              >优惠券</el-tag
            >
          </template>
        </el-table-column>
        <el-table-column prop="num" label="数量"> </el-table-column>
        <el-table-column prop="amount" label="金额"> </el-table-column>
        <el-table-column prop="user_id" label="领取人ID"> </el-table-column>
        <el-table-column prop="username" label="领取人名"> </el-table-column>
        <el-table-column prop="create_time" label="领取时间"> </el-table-column>
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
  </el-main>
</template>
    
  <script>
export default {
  name: "goodsCate",
  data() {
    return {
      dialog: {
        save: false,
      },
      tableData: [],
      searchForm: {
        receive_date: "",
        user_id: "",
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
    // 查询
    onSubmit() {
      this.getList();
    },
    // 获取列表
    async getList() {
      let res = await this.$API.vipCard.log.get(this.searchForm);
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
  
  <style scoped>
.line {
  width: 100%;
  height: 0;
  border-bottom: #e4e7ed 1px dashed;
  margin-bottom: 10px;
}
</style>