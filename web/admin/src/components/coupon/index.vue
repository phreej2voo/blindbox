<template>
  <div class="search-box">
    <el-form
      :inline="true"
      :model="searchForm"
      class="demo-form-inline"
      label-width="80px"
    >
      <el-form-item label="优惠券名">
        <el-input v-model="searchForm.name" placeholder="" clearable></el-input>
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
      margin-top: 15px;
    "
  ></div>
  <el-table
    :data="tableData"
    style="width: 100%"
    @row-click="singleElection"
    highlight-current-row
  >
    <el-table-column width="55" label="选择">
      <template #default="scope">
        <el-radio
          class="radio"
          v-model="templateSelection"
          :label="scope.row.id"
          >&nbsp;</el-radio
        >
      </template>
    </el-table-column>
    <el-table-column prop="id" width="100px" label="ID"> </el-table-column>
    <el-table-column prop="name" label="名称"> </el-table-column>
    <el-table-column label="类型" prop="type">
      <template #default="scope">
        <el-tag type="success" v-if="scope.row.type == 1">满减券</el-tag>
        <el-tag v-if="scope.row.type == 2">折扣券</el-tag>
      </template>
    </el-table-column>
    <el-table-column label="优惠金额/折扣">
      <template #default="scope">
        <span v-if="scope.row.type == 1">{{ scope.row.amount }}</span>
        <span v-if="scope.row.type == 2">{{ scope.row.discount }}</span>
      </template>
    </el-table-column>
    <el-table-column prop="status_txt" label="状态"> </el-table-column>
  </el-table>
  <el-pagination
    style="margin-top: 10px"
    background
    layout="->, prev, pager, next"
    :page-size="searchForm.limit"
    @current-change="handlePageChange"
    :total="page.total"
  >
  </el-pagination>
</template>
<script>
export default {
  data() {
    return {
      searchForm: {
        status: 1,
        name: "",
        page: 1,
        limit: 10,
      },
      page: {
        total: 0,
        logTotal: 0,
      },
      tableData: [],
      templateSelection: 0,
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
    }, // 翻页
    handlePageChange(page) {
      this.searchForm.page = page;
      this.getList();
    },
    // 获取优惠券列表
    async getList() {
      let res = await this.$API.coupon.list.get(this.searchForm);
      this.tableData = res.data.rows;
      this.page.total = res.data.total;
    },
    // 单选
    singleElection(row) {
      this.templateSelection = row.id;
      let selectedCoupon = this.tableData.filter((item) => item.id === row.id);

      this.$emit("handleSelected", selectedCoupon);
    },
  },
};
</script>
