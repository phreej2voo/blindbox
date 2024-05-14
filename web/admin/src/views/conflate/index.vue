<template>
  <el-main>
    <el-card v-if="type == 1" shadow="never" style="margin-top: 10px">
      <el-form
        ref="searchForm"
        :inline="true"
        :model="searchForm"
        class="demo-form-inline"
        label-width="80px"
      >
        <el-form-item label="名称" prop="name">
          <el-input
            v-model="searchForm.name"
            placeholder=""
            clearable
          ></el-input>
        </el-form-item>
        <el-form-item label="状态" prop="status">
          <el-select v-model="searchForm.status" placeholder="请选择" clearable>
            <el-option label="上线" value="1"></el-option>
            <el-option label="下线" value="2"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="活动时间" prop="activity_time">
          <el-date-picker
            v-model="searchForm.activity_time"
            type="daterange"
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
            value-format="YYYY-MM-DD"
            @change="dateChange"
          >
          </el-date-picker>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="searchList">查询</el-button>
        </el-form-item>
      </el-form>

      <el-button type="primary" @click="addAdvance" icon="el-icon-plus">
        添加合成
      </el-button>
      <div
        style="
          width: 100%;
          height: 0;
          border-bottom: #e4e7ed 1px dashed;
          margin-top: 15px;
          margin-bottom: 15px;
        "
      ></div>
      <div class="table-body">
        <el-table
          class="table-info"
          :data="list"
          style="width: 100%"
          v-loading="listLoading"
        >
          <el-table-column label="id" prop="id" width="50"></el-table-column>
          <el-table-column prop="sort" label="排序"> </el-table-column>
          <el-table-column label="图片" prop="image">
            <template #default="scope">
              <el-image
                style="width: 50px; height: 50px"
                :src="scope.row.image"
                :fit="fit"
              /> </template
          ></el-table-column>
          <el-table-column
            label="标题"
            prop="name"
            width="200"
            show-overflow-tooltip
          ></el-table-column>
          <el-table-column label="价格" prop="price" width="130">
            <template #default="scope">
              <div>{{ scope.row.price }}</div>
            </template>
          </el-table-column>
          <el-table-column
            label="活动时间"
            prop="activity_time"
            width="280"
            show-overflow-tooltip
          >
            <template #default="scope">
              <span>{{ scope.row.start_time }} / {{ scope.row.end_time }}</span>
            </template>
          </el-table-column>
          <el-table-column label="总库存" prop="stock" width="120">
            <template #default="scope">
              <div>
                {{ scope.row.stock }}
              </div>
            </template>
          </el-table-column>
          <el-table-column label="已合成数" prop="sales" width="120">
          </el-table-column>
          <el-table-column label="状态" prop="status" width="150">
            <template #default="scope">
              <el-tag type="success" v-if="scope.row.status == 1">上线</el-tag>
              <el-tag type="danger" v-if="scope.row.status == 2">下线</el-tag>
            </template>
          </el-table-column>
          <el-table-column label="操作" width="230" fixed="right">
            <template #default="scope">
              <el-button type="text" size="small" @click="log(scope.row)">
                合成详情
              </el-button>
              <el-button type="text" size="small" @click="editRow(scope.row)">
                编辑
              </el-button>
              <el-button type="text" size="small" @click="delRow(scope.row)">
                删除
              </el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
      <div class="pagination-class">
        <el-pagination
          style="margin-top: 10px"
          background
          layout="->, prev, pager, next"
          :page-size="pager.limit"
          :total="pager.total"
          @current-change="handlePageChange"
        >
        </el-pagination>
      </div>
    </el-card>

    <el-card v-if="type == 2" shadow="false" style="display: flex">
      <div class="click-bar">
        <div @click="type = 1">
          <el-icon class="back-icon"><el-icon-arrow-left /></el-icon>返回
        </div>
        <el-divider direction="vertical" />
        <span style="font-size: 14px; font-weight: 700">
          {{ backTitle }}
        </span>
      </div>
    </el-card>
    <el-card v-if="type == 2" shadow="false" style="margin-top: 10px">
      <advance-condition
        ref="advance"
        @successBack="saveGoods"
      ></advance-condition>
    </el-card>

    <log-dialog
      v-if="logVisiable"
      ref="logDialog"
      @success="handleSendSuccess"
      @closed="logVisiable = false"
    ></log-dialog>
  </el-main>
</template>

<script>
import AdvanceCondition from "./add.vue";
import logDialog from "./log";

export default {
  name: "index",
  components: {
    AdvanceCondition,
    logDialog,
  },
  data() {
    return {
      type: 1,
      backTitle: "",
      logVisiable: false,
      pager: {
        page: 1,
        limit: 15,
        total: 0,
      },
      searchForm: {
        name: "", // 名称
        status: "", // 状态
        activity_time: "", // 活动时间
      },
      list: [],

      page: 1,
      sort: 0,
      keyword: "",
      cats_id: "",
      listLoading: false,
      pagination: {},
      search: "",
      id: "",
      stock: "", //实时库存
      sellStock: "", //售罄实时库存
      sell_stock_id: "",
      stock_id: "",
      catsList: [], //进阶分类
    };
  },
  created() {
    this.getList();
  },
  methods: {
    dateChange(val) {
      console.log("val-", val);
    },
    getList() {
      this.listLoading = true;
      const params = {
        name: this.searchForm.name,
        status: this.searchForm.status,
        activity_time: this.searchForm.activity_time,
        page: this.pager.page,
        limit: this.pager.limit,
      };
      this.$API.conflate.conflateList
        .get(params)
        .then((res) => {
          const { code, data } = res;
          if (code == 0) {
            this.list = data.data;
            this.pager.total = data.total;
          }
          this.listLoading = false;
        })
        .catch(() => {
          this.listLoading = false;
        });
    },
    searchList() {
      this.getList();
    },
    addAdvance() {
      this.type = 2;
      this.backTitle = "添加碎片合成条件";
      this.$nextTick(() => {
        this.$refs.advance.setMode("add");
      });
    },
    editRow(row) {
      this.type = 2;
      this.backTitle = "编辑碎片合成条件";
      this.$nextTick(() => {
        this.$refs.advance.setMode("edit", row);
      });
    },
    delRow(row) {
      this.$confirm("确定删除当前碎片活动?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(async () => {
          const { code, msg } = await this.$API.conflate.conflateDel.get({
            id: row.id,
          });
          if (code == 0) {
            this.$message.success(msg);
            this.getList();
          } else {
            this.$message.error(msg);
          }
        })
        .catch(() => {});
    },
    // 查看详情
    log(row) {
      this.logVisiable = true;
      this.$nextTick(() => {
        this.$refs.logDialog.open().setData(row);
      });
    },
    handleSendSuccess() {
      this.logVisiable = false;
    },
    handlePageChange(page) {
      this.pager.page = page;
      this.getList();
    },
    saveGoods() {
      this.type = 1;
      this.pager.page = 1;
      this.getList();
    },
  },
};
</script>

<style lang="scss" scoped>
.table-body {
  margin-top: 30px;
  background-color: #fff;
}
.input-item {
  display: inline-block;
  width: 285px;
  margin-right: 20px;
}

.input-item .el-input__inner {
  border-right: 0;
}

.input-item .el-input__inner:hover {
  border: 1px solid #dcdfe6;
  border-right: 0;
  outline: 0;
}

.input-item .el-input__inner:focus {
  border: 1px solid #dcdfe6;
  border-right: 0;
  outline: 0;
}

.input-item .el-input-group__append {
  background-color: #fff;
  border-left: 0;
  width: 10%;
  padding: 0;
}

.input-item .el-input-group__append .el-button {
  padding: 0;
}

.input-item .el-input-group__append .el-button {
  margin: 0;
}
.el-form-item__content .el-input-group {
  vertical-align: middle;
}
.title {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.click-bar {
  width: 200px;
  height: 16px;
  line-height: 16px;
  cursor: pointer;
  display: flex;
}
.click-bar .back-icon {
  position: relative;
  top: 2px;
}
.pagination-class {
  margin-top: 20px;
}
</style>
