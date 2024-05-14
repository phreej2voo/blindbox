<template>
  <el-dialog
    :title="titleMap[mode]"
    v-model="visible"
    :width="600"
    destroy-on-close
    @closed="$emit('closed')"
    :close-on-click-modal="false"
  >
    <el-form
      :model="form"
      label-width="120px"
      :rules="rules"
      ref="ruleForm"
      :disabled="mode == 'show'"
    >
      <el-form-item label="优惠券名称">
        <el-input v-model="form.name" style="width: 500px" readonly></el-input>
      </el-form-item>
      <el-form-item label="发放数量">
        <el-input v-model="form.num" style="width: 500px"></el-input>
      </el-form-item>
      <el-form-item label="发放用户id">
        <el-input
          v-model="form.userList"
          style="width: 500px"
          :rows="5"
          type="textarea"
        ></el-input>
        <p style="font-size: 12px">多个用户id之间用英文,隔开。例如：33,2251</p>
      </el-form-item>

      <el-form-item>
        <el-button
          type="primary"
          :loading="isSaveing"
          @click="submit('ruleForm')"
          >立即发放</el-button
        >
      </el-form-item>
    </el-form>
  </el-dialog>
</template>

<script>
export default {
  emits: ["success", "closed"],
  data() {
    return {
      mode: "add",
      titleMap: {
        add: "发放优惠券",
      },
      isSaveing: false,
      dialogVisible: false,
      visible: false,
      allreadySelected: [], // 已经选择的商品id
      form: {
        name: "",
        num: 1,
        id: 0,
        userList: "",
      },
    };
  },
  mounted() {},
  methods: {
    // 显示
    open(mode = "add") {
      this.mode = mode;
      this.visible = true;
      return this;
    },
    // 表单提交方法
    submit() {
      this.$refs.ruleForm.validate(async (valid) => {
        if (valid) {
          this.isSaveing = true;
          var res = await this.$API.coupon.send.post(this.form);

          this.isSaveing = false;
          if (res.code == 0) {
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
      this.form.name = data.name;
      this.form.id = data.id;
    },
  },
};
</script>

<style scoped></style>
