<template>
  <el-dialog
    :title="titleMap[mode]"
    v-model="visible"
    :width="800"
    destroy-on-close
    @closed="$emit('closed')"
  >
    <el-form :model="form" :rules="rules" ref="addForm" label-width="140px">
      <el-form-item label="等级名称" prop="title">
        <el-input v-model="form.title"></el-input>
      </el-form-item>
      <el-form-item label="等级值" prop="level">
        <el-input v-model="form.level" type="number"></el-input>
      </el-form-item>
      <!--<el-form-item prop="discount">
        <template #label>
          <span>享受折扣</span>
          <el-tooltip
            effect="dark"
            placement="top"
            content="例如：9折填写0.9，98折填写 0.98"
          >
            <el-icon><component :is="infoFilled" /></el-icon>
          </el-tooltip>
        </template>
        <template #default>
          <el-input v-model="form.discount" type="number"></el-input>
        </template>
      </el-form-item>-->
      <el-form-item label="解锁需经验值达到" prop="experience">
        <el-input v-model="form.experience" type="number"></el-input>
      </el-form-item>
      <el-form-item label="图标" prop="icon">
        <ul class="img-list">
          <li v-if="form.icon">
            <img
              :src="form.icon"
              style="width: 58px; height: 58px"
              alt="图片"
            />
            <div class="img-tools" @click="removeImg(1)">
              <el-icon style="color: #fff"><el-icon-delete /></el-icon>
            </div>
          </li>
          <li>
            <div class="addImg" @click="showImage(1)">
              <el-icon><el-icon-plus /></el-icon>
            </div>
          </li>
        </ul>
      </el-form-item>
      <el-form-item label="背景图" prop="card_bg">
        <ul class="img-list">
          <li v-if="form.card_bg">
            <img
              :src="form.card_bg"
              style="width: 58px; height: 58px"
              alt="图片"
            />
            <div class="img-tools" @click="removeImg(2)">
              <el-icon style="color: #fff"><el-icon-delete /></el-icon>
            </div>
          </li>
          <li>
            <div class="addImg" @click="showImage(2)">
              <el-icon><el-icon-plus /></el-icon>
            </div>
          </li>
        </ul>
      </el-form-item>
      <el-form-item label="赠送优惠券">
        <div style="width: 100%; display: flex">
          <el-select
            style="width: 48%"
            v-model="form.present.coupon"
            placeholder="选择优惠券"
            clearable
          >
            <el-option
              :label="item.name"
              :value="item.id"
              v-for="item in couponList"
              :key="item.id"
            ></el-option>
          </el-select>
          <el-input
            style="width: 48%; margin-left: 4%"
            v-model="form.present.coupon_num"
            type="number"
          ></el-input>
        </div>
      </el-form-item>
      <el-form-item label="是否显示" prop="status">
        <el-radio v-model="form.status" :label="1">显示</el-radio>
        <el-radio v-model="form.status" :label="2">隐藏</el-radio>
      </el-form-item>
      <el-form-item label="等级说明" prop="remark">
        <el-input v-model="form.remark" type="textarea" rows="3"></el-input>
      </el-form-item>
    </el-form>
    <template #footer>
      <el-button @click="visible = false">取 消</el-button>
      <el-button
        v-if="mode !== 'show'"
        type="primary"
        :loading="isSaveing"
        @click="submit()"
        >保 存</el-button
      >
    </template>
  </el-dialog>

  <el-dialog
    title="选择资源"
    v-model="imgResourceVisible"
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
        add: "新增会员等级",
        edit: "编辑会员等级",
        show: "查看",
      },
      infoFilled: "el-icon-InfoFilled",
      visible: false,
      isSaveing: false,
      //表单数据
      form: {
        id: 0,
        title: "",
        level: 0,
        discount: "1.00",
        experience: 0,
        icon: "",
        card_bg: "",
        status: 1,
        remark: "",
        present: {
          coupon: "",
          coupon_num: 1,
          prop: "",
          prop_num: 1,
        },
      },
      couponList: [],
      //验证规则
      rules: {
        title: [{ required: true, message: "等级名称", trigger: "blur" }],
        level: [{ required: true, message: "请选择状态", trigger: "blur" }],
        experience: [
          {
            required: true,
            message: "请输入解锁经验",
            trigger: "blur",
          },
        ],
      },
      imgResourceVisible: false,
      imgType: 1,
    };
  },
  mounted() {
    this.getCouponList();
  },
  methods: {
    //显示
    open(mode = "add") {
      this.mode = mode;
      this.visible = true;
      return this;
    },
    // 显示图片
    showImage(type) {
      this.imgResourceVisible = true;
      this.imgType = type;
    },
    // 表单提交方法
    submit() {
      if (this.mode === "edit") {
        this.$refs.addForm.validate(async (valid) => {
          if (valid) {
            this.isSaveing = true;
            var res;
            res = await this.$API.userLevel.edit.post(this.form);

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
      } else {
        this.$refs.addForm.validate(async (valid) => {
          if (valid) {
            this.isSaveing = true;
            var res;
            res = await this.$API.userLevel.add.post(this.form);

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
      }
    },
    // 获取全部的优惠券
    async getCouponList() {
      let res = await this.$API.coupon.getAll.get();
      this.couponList = res.data;
    },
    // 表单注入数据
    setData(data) {
      console.log("数据信息", data);
      this.form.id = data.id;
      this.form.title = data.title;
      this.form.level = data.level;
      this.form.discount = data.discount;
      this.form.experience = data.experience;
      this.form.balance = data.balance;
      this.form.status = data.status;
      this.form.icon = data.icon;
      this.form.card_bg = data.card_bg;
      this.form.remark = data.remark;

      data.present.forEach((item) => {
        if (item.type == 1) {
          this.form.present.coupon = item.present_id;
          this.form.present.coupon_num = item.present_num;
        } else if (item.type == 2) {
          this.form.present.prop = item.present_id + "";
          this.form.present.prop_num = item.present_num;
        }
      });
    },
    // 选择图片
    selectedFiles(file) {
      if (this.imgType == 1) {
        this.form.icon = file[0].url;
      } else if (this.imgType == 2) {
        this.form.card_bg = file[0].url;
      }
      this.imgResourceVisible = false;
    },
    // 删除图片
    removeImg(type) {
      if (type == 1) {
        this.form.icon = "";
      } else {
        this.form.card_bg = "";
      }
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
