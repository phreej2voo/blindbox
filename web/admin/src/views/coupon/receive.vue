<template>
  <el-main>
    <el-card class="box-card" shadow="never">
      <div class="search-box">
        <el-form
          :inline="true"
          :model="searchForm"
          class="demo-form-inline"
          label-width="80px"
        >
          <el-form-item label="状态">
            <el-select
              v-model="searchForm.status"
              placeholder="请选择"
              clearable
            >
              <el-option label="未使用" value="1"></el-option>
              <el-option label="已使用" value="2"></el-option>
              <el-option label="已过期" value="3"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="领取人">
            <el-input
              v-model="searchForm.user_name"
              placeholder=""
              clearable
            ></el-input>
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
        <el-table-column prop="id" width="100px" label="ID"> </el-table-column>
        <el-table-column prop="coupon_name" label="优惠券名称">
        </el-table-column>
        <el-table-column prop="username" label="领取人"> </el-table-column>
        <el-table-column prop="coupon.amount" label="面值"> </el-table-column>
        <el-table-column prop="coupon.threshold_amount" label="门槛金额">
        </el-table-column>
        <el-table-column prop="end_time" label="有效期"> </el-table-column>
        <el-table-column prop="status_txt" label="状态"> </el-table-column>
        <el-table-column prop="used_time" label="使用日期"> </el-table-column>
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
        user_name: "",
        status: "",
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
    handlePageChange(page) {
      this.searchForm.page = page;
      this.getList();
    },
    // 获取领取记录
    async getList() {
      let res = await this.$API.coupon.receive.get(this.searchForm);
      this.tableData = res.data.rows;
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
