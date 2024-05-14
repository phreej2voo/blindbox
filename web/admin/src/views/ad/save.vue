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
      :rules="rules"
      :disabled="mode == 'show'"
      ref="dialogForm"
      label-width="100px"
      label-position="left"
    >
      <el-form-item label="类型" prop="name">
        <el-radio v-model="form.type" label="2">新人必买</el-radio>
        <el-radio v-model="form.type" label="3">买一送一</el-radio>
      </el-form-item>
      <el-form-item label="广告图片" prop="pic">
        <ul class="img-list">
          <li v-if="form.pic">
            <img :src="form.pic" style="width: 58px; height: 58px" alt="图片" />
            <div class="img-tools" @click="removeImg()">
              <el-icon style="color: #fff"><el-icon-delete /></el-icon>
            </div>
          </li>
          <li>
            <div class="addImg" @click="showImage()">
              <el-icon><el-icon-plus /></el-icon>
            </div>
          </li>
        </ul>
      </el-form-item>
      <el-form-item label="状态" prop="status">
        <el-radio :label="1" v-model="form.status">正常</el-radio>
        <el-radio :label="2" v-model="form.status">禁用</el-radio>
      </el-form-item>
    </el-form>
    <template #footer>
      <el-button @click="visible = false">取 消</el-button>
      <el-button
        v-if="mode != 'show'"
        type="primary"
        :loading="isSaveing"
        @click="submit()"
        >保 存</el-button
      >
    </template>
  </el-dialog>

  <el-dialog
    title="选择资源"
    v-model="resourceVisible"
    :width="1200"
    destroy-on-close
  >
    <Attachment :select-num="1" @selectedFiles="selectedFiles"></Attachment>
  </el-dialog>
</template>

<script>
import Attachment from "@/components/attachment";
export default {
  components: {
    Attachment,
  },
  emits: ["success", "closed"],
  data() {
    return {
      mode: "add",
      titleMap: {
        add: "新增广告",
        edit: "编辑广告",
        show: "查看",
      },
      resourceVisible: false,
      visible: false,
      isSaveing: false,
      //表单数据
      form: {
        id: "",
        type: "2",
        pic: "",
        status: 1,
      },
      plusIcon: "el-icon-plus",
      deleteIcon: "el-icon-delete",
      //验证规则
      rules: {
        type: [{ required: true, message: "请选择类型", trigger: "blur" }],
        pic: [{ required: true, message: "请选择弹层图片", trigger: "blur" }],
        status: [{ required: true, message: "请选择状态", trigger: "blur" }],
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
      this.$refs.dialogForm.validate(async (valid) => {
        if (valid) {
          this.isSaveing = true;
          var res;
          if (this.mode == "add") {
            res = await this.$API.ad.add.post(this.form);
          } else {
            res = await this.$API.ad.edit.post(this.form);
          }
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
    // 显示图片
    showImage() {
      this.resourceVisible = true;
    },
    // 选择图片
    selectedFiles(file) {
      this.form.pic = file[0].url;
      this.resourceVisible = false;
    },
    // 删除图片
    removeImg() {
      this.form.pic = "";
    },
    // 表单注入数据
    setData(data) {
      this.form.id = data.id;
      this.form.pic = data.pic;
      this.form.type = data.type + "";
      this.form.status = data.status;
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
