<template>
  <el-dialog
    :title="titleMap[mode]"
    v-model="visible"
    :width="1000"
    destroy-on-close
    @closed="$emit('closed')"
    :close-on-click-modal="false"
  >
    <el-form
      :model="form"
      :rules="rules"
      :disabled="mode === 'show'"
      ref="dialogForm"
      label-width="110px"
      label-position="left"
    >
      <el-form-item label="物流公司名称" prop="express_name">
        <el-select
          v-model="form.express_name"
          @change="expressChange"
          style="width: 100%"
        >
          <el-option
            v-for="(item, index) in expressList"
            :key="index"
            :label="item.name"
            :value="item.name"
          ></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="物流公司编码" prop="express_code">
        <el-input v-model="form.express_code" disabled clearable></el-input>
      </el-form-item>
      <el-form-item label="物流单号" prop="express_no">
        <el-input
          v-model="form.express_no"
          placeholder="物流单号"
          clearable
        ></el-input>
      </el-form-item>
      <el-form-item label="是否开启分单">
        <el-switch v-model="form.splitOrder"> </el-switch>
      </el-form-item>
      <el-form-item label="订单商品" v-if="form.splitOrder">
        <el-table
          :data="tableData"
          style="width: 100%"
          @selection-change="handleSelectionChange"
        >
          <el-table-column type="selection" width="55"> </el-table-column>
          <el-table-column prop="id" label="ID"> </el-table-column>
          <el-table-column label="商品图片">
            <template #default="scope">
              <el-image
                :src="scope.row.reward.goods_image"
                style="width: 36px; height: 36px"
              ></el-image>
            </template>
          </el-table-column>
          <el-table-column prop="reward.goods_name" label="商品名称">
          </el-table-column>
        </el-table>
      </el-form-item>
    </el-form>
    <template #footer>
      <el-button @click="visible = false">取 消</el-button>
      <el-button
        v-if="mode !== 'show'"
        type="primary"
        :loading="isSaveing"
        @click="submit()"
        >确认发货</el-button
      >
    </template>
  </el-dialog>
</template>

<script>
export default {
  emits: ["success", "closed"],
  data() {
    return {
      areas: [],
      mode: "deliver",
      titleMap: {
        deliver: "发货",
        show: "查看",
      },
      expressList: [],
      visible: false,
      isSaveing: false,
      //表单数据
      form: {
        id: "",
        express_name: "",
        express_code: "",
        express_no: "",
        splitOrder: false,
        detailIds: [],
      },
      //验证规则
      rules: {
        express_name: [
          {
            required: true,
            message: "请输入物流公司名称",
            trigger: "blur",
          },
        ],
        express_no: [
          {
            required: true,
            message: "请输入物流单号",
            trigger: "blur",
          },
        ],
      },
      tableData: [],
    };
  },
  mounted() {
    this.getExpressList();
  },
  methods: {
    //显示
    open(mode = "add") {
      this.mode = mode;
      this.visible = true;
      return this;
    },
    // 表单提交方法
    submit() {
      this.$refs.dialogForm.validate(async (valid) => {
        if (valid) {
          this.isSaveing = true;
          var res;
          res = await this.$API.userBoxDeliver.deliver.post(this.form);

          this.isSaveing = false;
          if (res.code === 0) {
            this.$emit("success", this.form, this.mode);
            this.visible = false;
            this.$message.success(res.msg);
          } else {
            this.$message.error(res.msg);
          }
        } else {
          return false;
        }
      });
    },
    // 表单注入数据
    setData(data) {
      this.form.id = data.id;
      this.getOrderDetail();
    },
    handleChange(value) {
      console.log(value);
    },
    //获取物流公司列表
    async getExpressList() {
      let res = await this.$API.integralOrder.expressList.get();
      this.expressList = res.data.data;
    },
    expressChange(name) {
      this.expressList.forEach((e) => {
        if (e.name == name) {
          this.form.express_code = e.code;
        }
      });
    },
    // 获取订单详情
    async getOrderDetail() {
      let res = await this.$API.userBoxDeliverDetail.detail.get({
        deliver_id: this.form.id,
      });
      this.tableData = res.data;
    },
    // 选中数据
    handleSelectionChange(row) {
      this.form.detailIds = [];
      row.forEach((item) => {
        this.form.detailIds.push(item.id);
      });
    },
  },
};
</script>

<style scoped>
.img-list {
  height: 60px;
  padding-left: 0;
  margin-top: 0;
}
.img-list li:first-child {
  margin-left: 0;
}
.img-list li {
  width: 58px;
  height: 58px;
  float: left;
  margin-left: 5px;
  cursor: pointer;
  position: relative;
}
.addImg {
  height: 56px;
  width: 56px;
  line-height: 56px;
  text-align: center;
  border: 1px dashed rgb(221, 221, 221);
}
ul li {
  list-style: none;
}
.image-check-dialog .el-dialog__header {
  display: none;
}
.image-check-dialog .el-dialog__body {
  padding: 0;
}
.img-list .img-tools {
  position: absolute;
  width: 58px;
  height: 15px;
  line-height: 15px;
  text-align: center;
  top: 43px;
  background: rgba(0, 0, 0, 0.6);
  cursor: pointer;
}
</style>
