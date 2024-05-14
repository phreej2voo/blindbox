<template>
  <el-dialog
    :title="titleMap[mode]"
    v-model="visible"
    :width="1200"
    destroy-on-close
    @closed="$emit('closed')"
    :close-on-click-modal="false"
  >
    <div shadow="never" style="border: none">
      <el-form :inline="true" :model="form" class="demo-form-inline">
        <el-form-item label="合成状态">
          <el-select v-model="form.status" clearable>
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
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
    <el-table :data="tableData" style="width: 100%">
      <el-table-column prop="id" label="ID" />
      <el-table-column label="合成人">
        <template #default="scope">
          {{ scope.row.username }} <br />
          ID: {{ scope.row.user_id }}
        </template>
      </el-table-column>
      <el-table-column label="合成进度">
        <template #default="scope">
          {{ (scope.row.progress * 100).toFixed(2) }} %
        </template>
      </el-table-column>
      <el-table-column prop="total_conflate_val" label="合成值" />
      <el-table-column label="状态">
        <template #default="scope">
          <el-tag type="danger" v-if="scope.row.status == 1">进行中</el-tag>
          <el-tag type="success" v-if="scope.row.status == 2">已完成</el-tag>
        </template>
      </el-table-column>
    </el-table>
    <div style="margin-top: 20px"></div>
    <el-pagination
      background
      layout="->, prev, pager, next"
      :total="total"
      :page-size="form.limit"
      @current-change="pageChange"
    />
  </el-dialog>
</template>
  
  <script>
export default {
  name: "conflateLog",
  emits: ["success", "closed"],
  data() {
    return {
      mode: "detail",
      titleMap: {
        detail: "合成记录",
      },
      visible: false,
      //表单数据
      form: {
        conflate_id: 0,
        status: "",
        page: 1,
        limit: 10,
      },
      total: 0,
      tableData: [],
      options: [
        { value: 1, label: "进行中" },
        { value: 2, label: "已完成" },
      ],
    };
  },
  mounted() {},
  methods: {
    //显示
    open() {
      this.visible = true;
      return this;
    },
    setData(row) {
      this.form.conflate_id = row.id;
      this.getList();
    },
    // 获取列表
    async getList() {
      let res = await this.$API.conflate.conflateLog.get(this.form);
      this.tableData = res.data.data;
      this.total = res.data.total;
    },
    // 翻页
    pageChange(val) {
      this.form.page = val;
      this.getList();
    },
    search() {
      this.getList();
    },
  },
};
</script>
  