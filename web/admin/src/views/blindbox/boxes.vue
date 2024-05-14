<template>
  <el-main>
    <el-card shadow="false" style="display: flex">
      <div class="click-bar" style="width: 300px; display: flex">
        <div @click="goBack()" style="cursor: pointer">
          <el-icon class="back-icon"><el-icon-arrow-left /></el-icon>返回列表
        </div>
        <span style="font-size: 14px; font-weight: 700; margin-left: 20px"
          >箱子列表</span
        >
      </div>
    </el-card>
    <el-card shadow="never">
      <el-button
        type="danger"
        icon="el-icon-plus"
        @click="incBlindbox"
        style="margin-top: 15px"
        v-if="playId == 4 && playId != 5"
        >补箱</el-button
      >
      <div class="line"></div>
      <el-table :data="tableData" style="width: 100%; margin-top: 20px">
        <el-table-column prop="id" label="ID"> </el-table-column>
        <el-table-column prop="box_no" label="箱号"> </el-table-column>
        <el-table-column
          prop="sales_cost_price"
          label="已销售成本"
        ></el-table-column>
        <el-table-column prop="sales_amount" label="销售金额"></el-table-column>
        <el-table-column
          prop="profit_loss_amount"
          label="总盈亏"
        ></el-table-column>
        <el-table-column prop="sales" label="销量"></el-table-column>
        <el-table-column prop="stock" label="库存"></el-table-column>
        <el-table-column label="状态">
          <template #default="scope">
            <el-tag type="success" v-if="scope.row.status == 1">在售</el-tag>
            <el-tag type="danger" v-if="scope.row.status == 2">售罄</el-tag>
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

    <el-dialog title="补箱" v-model="dialogVisible" width="400px">
      <el-form :model="form" label-width="120px">
        <el-form-item label="新增箱数">
          <el-input v-model="form.num" type="number" />
        </el-form-item>
      </el-form>
      <template #footer>
        <el-button @click="dialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="doInc">确 定</el-button>
      </template>
    </el-dialog>
  </el-main>
</template>

<script>
export default {
  name: "boxes",
  data() {
    return {
      dialogVisible: false,
      tableData: [],
      searchForm: {
        blindbox_id: this.$route.query.id,
        page: 1,
        limit: 15,
      },
      playId: this.$route.query.playId,
      page: {
        total: 1,
      },
      form: {
        blindbox_id: this.$route.query.id,
        num: 0,
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
    // 获取列表
    async getList() {
      let res = await this.$API.blindboxDetail.boxesList.get(this.searchForm);
      this.tableData = res.data.data;
      this.page.total = res.data.total;
    },
    handleSuccess() {
      this.getList();
    },
    // 翻页
    pageChangeHandle(page) {
      this.searchForm.page = page;
      this.getList();
    },
    goBack() {
      this.$router.push({
        path: "/blindbox/index",
      });
    },
    // 补箱
    incBlindbox() {
      this.dialogVisible = true;
    },
    // 处理补箱
    async doInc() {
      let res = await this.$API.blindboxDetail.incBoxes.post(this.form);
      if (res.code == 0) {
        this.$message.success("补箱成功");
        this.dialogVisible = false;
        this.getList();
      } else {
        this.$message.error(res.msg);
      }
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
