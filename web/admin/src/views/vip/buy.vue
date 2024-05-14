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
          <el-form-item label="下单人ID">
            <el-input
              v-model="searchForm.user_id"
              placeholder=""
              clearable
            ></el-input>
          </el-form-item>
          <el-form-item label="类型">
            <el-select
              v-model="searchForm.card_type"
              placeholder="请选择"
              clearable
            >
              <el-option label="周卡" value="1" />
              <el-option label="月卡" value="2" />
            </el-select>
          </el-form-item>
          <el-form-item label="支付状态">
            <el-select
              v-model="searchForm.status"
              placeholder="请选择"
              clearable
            >
              <el-option label="待支付" value="1" />
              <el-option label="已支付" value="2" />
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
      <el-table :data="tableData" style="width: 100%; margin-top: 20px">
        <el-table-column prop="id" label="ID"> </el-table-column>
        <el-table-column label="类型">
          <template #default="scope">
            <el-tag type="success" v-if="scope.row.card_type == 1">周卡</el-tag>
            <el-tag type="danger" v-if="scope.row.card_type == 2">月卡</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="title" label="会员卡名"> </el-table-column>
        <el-table-column prop="user_id" label="下单人ID"> </el-table-column>
        <el-table-column prop="username" label="下单人名"> </el-table-column>
        <el-table-column prop="price" label="价格"> </el-table-column>
        <el-table-column label="支付方式">
          <template #default="scope">
            <el-tag v-if="scope.row.pay_way == 1">微信</el-tag>
            <el-tag v-if="scope.row.pay_way == 2">支付宝</el-tag>
            <el-tag v-if="scope.row.pay_way == 3">哈希币</el-tag>
            <el-tag v-if="scope.row.pay_way == 4">余额</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="third_no" label="三方单号"> </el-table-column>
        <el-table-column label="支付状态">
          <template #default="scope">
            <el-tag type="success" v-if="scope.row.status == 1">待支付</el-tag>
            <el-tag type="danger" v-if="scope.row.status == 2">已支付</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="create_time" label="下单时间"> </el-table-column>
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
  name: "vip_buy",
  data() {
    return {
      tableData: [],
      searchForm: {
        user_id: "",
        card_type: "",
        status: "",
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
      let res = await this.$API.vipCard.buy.get(this.searchForm);
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