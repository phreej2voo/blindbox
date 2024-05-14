<template>
  <el-form
    ref="ruleFormRef"
    :model="resetForm"
    :rules="resetRules"
    label-width="120px"
    class="reset-form"
  >
    <el-form-item label="旧密码" prop="old_pwd" class="form-item">
      <el-input
        v-model="resetForm.old_pwd"
        type="password"
        show-password
        autocomplete="off"
      />
    </el-form-item>
    <el-form-item label="新密码" prop="new_pwd" class="form-item">
      <el-input
        v-model="resetForm.new_pwd"
        type="password"
        show-password
        autocomplete="off"
      />
    </el-form-item>
    <el-form-item label="确认密码" prop="new_pwd2" class="form-item">
      <el-input
        v-model="resetForm.new_pwd2"
        type="password"
        show-password
        autocomplete="off"
      />
    </el-form-item>
    <div class="btn-class">
      <el-button type="primary" @click="submitPwd">提交</el-button>
      <el-button @click="cancelPwd">取消</el-button>
    </div>
  </el-form>
</template>

<script>
export default {
  data() {
    const validatePass = (rule, value, callback) => {
      if (!value) {
        callback(new Error("请输入新密码"));
      } else {
        callback();
      }
    };
    const validatePass2 = (rule, value, callback) => {
      if (!value) {
        callback(new Error("请再次输入新密码"));
      } else if (value !== this.resetForm.new_pwd) {
        callback(new Error("两次输入不一致 请重新输入!"));
      } else {
        callback();
      }
    };
    return {
      resetForm: {
        old_pwd: "",
        new_pwd: "",
        new_pwd2: "",
      },
      resetRules: {
        old_pwd: [{ required: true, message: "请输入旧密码", trigger: "blur" }],
        new_pwd: [{ required: true, validator: validatePass, trigger: "blur" }],
        new_pwd2: [
          { required: true, validator: validatePass2, trigger: "blur" },
        ],
      },
    };
  },
  methods: {
    submitPwd() {
      this.$refs.ruleFormRef.validate(async (valid) => {
        if (valid) {
          const param = {
            old_pwd: this.resetForm.old_pwd,
            new_pwd: this.resetForm.new_pwd,
          };
          const { code, msg } = await this.$API.homeData.resetPassword.post(
            param
          );
          if (code == 0) {
            this.$message.success(msg);
            this.$emit("resetSuccess", 1);
          } else {
            this.$message.error(msg);
          }
        }
      });
    },
    cancelPwd() {
      this.$refs.ruleFormRef.resetFields();
      this.$emit("resetSuccess", 2);
    },
  },
};
</script>

<style lang="scss" scoped>
.reset-form {
  width: 100%;
}
.form-item {
  width: 90%;
}
.btn-class {
  text-align: center;
}
</style>