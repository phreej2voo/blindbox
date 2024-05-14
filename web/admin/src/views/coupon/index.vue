<template>
  <el-main>
    <el-card shadow="never">
      <div class="search-box">
        <el-form :model="searchForm" label-width="80px">
          <el-form-item label="状态">
            <el-button-group>
              <el-button
                :class="{ primary: searchForm.status == 0 }"
                @click="checkTab(0)"
                >全部</el-button
              >
              <el-button
                :class="{ primary: searchForm.status == 1 }"
                @click="checkTab(1)"
                >进行中</el-button
              >
              <el-button
                :class="{ primary: searchForm.status == 2 }"
                @click="checkTab(2)"
                >已作废</el-button
              >
              <el-button
                :class="{ primary: searchForm.status == 4 }"
                @click="checkTab(4)"
                >已领完</el-button
              >
            </el-button-group>
          </el-form-item>
        </el-form>
        <el-form
          :inline="true"
          :model="searchForm"
          class="demo-form-inline"
          label-width="80px"
        >
          <el-form-item label="优惠券名">
            <el-input
              v-model="searchForm.name"
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
      <el-button
        type="primary"
        icon="el-icon-plus"
        @click="addCoupon"
        style="margin-top: 10px"
        >添加优惠券</el-button
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
        <el-table-column label="领取上限">
          <template #default="scope">
            {{ scope.row.max_receive_num }}张/人
          </template>
        </el-table-column>
        <el-table-column prop="total_num" label="发放数量"> </el-table-column>
        <el-table-column prop="received_num" label="已领数量">
        </el-table-column>
        <el-table-column width="400px" label="有效期">
          <template #default="scope">
            <span v-if="scope.row.validity_type == 1"
              >{{ scope.row.start_time }} / {{ scope.row.end_time }}</span
            >
            <span v-else>领取之日后 {{ scope.row.receive_useful_day }} 天</span>
          </template>
        </el-table-column>
        <el-table-column label="是否开启">
          <template #default="scope">
            <el-switch
              @change="changeOpen($event, scope.row.id)"
              v-model="scope.row.open_flag"
              inactive-color="#ccc"
              :active-value="1"
              :inactive-value="2"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column prop="status_txt" label="状态"> </el-table-column>
        <el-table-column label="操作" width="280">
          <template #default="scope">
            <el-button @click="handleView(scope.row)" type="text" size="small"
              >查看</el-button
            >
            <el-button @click="handleLog(scope.row)" type="text" size="small"
              >领取记录</el-button
            >
            <el-button
              @click="handleSend(scope.row)"
              type="text"
              size="small"
              v-if="scope.row.status == 1"
              >发放</el-button
            >
            <el-button @click="handleClose(scope.row)" type="text" size="small"
              >作废</el-button
            >
          </template>
        </el-table-column>
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
    </el-card>

    <save-dialog
      v-if="dialog.save"
      ref="saveDialog"
      @success="handleSuccess"
      @closed="dialog.save = false"
    ></save-dialog>

    <send-dialog
      v-if="dialog.send"
      ref="sendDialog"
      @success="handleSendSuccess"
      @closed="dialog.send = false"
    ></send-dialog>

    <el-dialog title="领取记录" v-model="dialog.log" width="800px">
      <div shadow="never" style="border: none">
        <el-form :inline="true" :model="logForm" class="demo-form-inline">
          <el-form-item label="用户ID">
            <el-input
              v-model="logForm.user_id"
              placeholder="用户id"
              clearable
            ></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="searchLog">查询</el-button>
          </el-form-item>
        </el-form>
      </div>
      <el-table :data="logTableData" style="width: 100%">
        <el-table-column prop="id" label="ID"> </el-table-column>
        <el-table-column prop="user_id" label="领取人ID"> </el-table-column>
        <el-table-column prop="username" label="领取人"> </el-table-column>
        <el-table-column prop="create_time" label="领取时间"> </el-table-column>
      </el-table>
      <el-pagination
        style="margin-top: 10px"
        background
        layout="->, prev, pager, next"
        :page-size="logForm.limit"
        @current-change="handleLogPageChange"
        :total="page.logTotal"
      >
      </el-pagination>
    </el-dialog>
  </el-main>
</template>

<script>
import saveDialog from "./save";
import sendDialog from "./send";

export default {
  name: "coupon",
  components: {
    saveDialog,
    sendDialog,
  },
  data() {
    return {
      dialog: {
        save: false,
        log: false,
        send: false,
      },
      logTableData: [], // 领取记录
      logForm: {
        coupon_id: 0,
        page: 1,
        limit: 15,
        user_id: "",
      },
      searchForm: {
        status: "",
        name: "",
        page: 1,
        limit: 15,
      },
      options: [
        { value: 1, label: "进行中" },
        { value: 2, label: "已作废" },
        { value: 3, label: "已过期" },
        { value: 4, label: "已领完" },
      ],
      page: {
        total: 0,
        logTotal: 0,
      },
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
    // 添加优惠券
    addCoupon() {
      this.dialog.save = true;
      this.$nextTick(() => {
        this.$refs.saveDialog.open();
      });
    },
    // 翻页
    handlePageChange(page) {
      this.searchForm.page = page;
      this.getList();
    },
    // 查看
    handleView(row) {
      this.dialog.save = true;
      this.$nextTick(() => {
        this.$refs.saveDialog.open("show").setData(row);
      });
    },
    // 领取记录
    handleLog(row) {
      this.dialog.log = true;
      this.logForm.coupon_id = row.id;
      this.getReceiveLog();
    },
    // 作废
    handleClose(row) {
      this.$confirm("此操作将作废该优惠券, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(async () => {
          let res = await this.$API.coupon.del.get({ id: row.id });
          if (res.code == 0) {
            this.$message.success(res.msg);
            this.getList();
          } else {
            this.$message.error(res.msg);
          }
        })
        .catch(() => {});
    },
    // 获取优惠券列表
    async getList() {
      let res = await this.$API.coupon.list.get(this.searchForm);
      this.tableData = res.data.rows;
      this.page.total = res.data.total;
    },
    // 设置类型
    checkTab(status) {
      this.searchForm.status = status;
      this.searchForm.page = 1;
      this.getList();
    },
    // 保存成功
    handleSuccess() {
      this.searchForm.page = 1;
      this.getList();
    },
    // 是否开启
    async changeOpen(val, id) {
      let res = await this.$API.coupon.open.get({
        open_flag: val,
        id: id,
      });
      if (res.code == 0) {
        this.$message.success(res.msg);
      } else {
        this.$message.error(res.msg);
      }
    },
    // 获取领取日志
    async getReceiveLog() {
      let res = await this.$API.coupon.log.get(this.logForm);
      this.logTableData = res.data.rows;
      this.page.logTotal = res.data.total;
    },
    // 翻页
    handleLogPageChange(val) {
      this.logForm.page = val;
      this.getReceiveLog();
    },
    // 查询日志
    searchLog() {
      this.getReceiveLog();
    },
    // 优惠券发送
    handleSend(row) {
      this.dialog.send = true;
      this.$nextTick(() => {
        this.$refs.sendDialog.open().setData(row);
      });
    },
    // 发送成功
    handleSendSuccess() {
      this.dialog.send = false;
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
</style>
