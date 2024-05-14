<template>
  <el-main>
    <el-card shadow="never" style="width: 1500px">
      <el-form ref="form" :model="form" label-width="120px">
        <el-form-item label="活动大图">
          <ul class="img-list">
            <li v-if="form.pic != ''">
              <el-image
                :src="form.pic"
                alt="图片"
                style="height: 58px; width: 58px"
                :preview-src-list="[form.pic]"
              ></el-image>
              <div class="img-tools">
                <el-icon
                  style="font-size: 14px; color: #fff"
                  @click="removeIcon()"
                  ><component :is="deleteIcon"
                /></el-icon>
              </div>
            </li>
            <li>
              <div class="addImg" @click="showImage">
                <el-icon><component :is="plusIcon" /></el-icon>
              </div>
            </li>
          </ul>
        </el-form-item>
        <el-form-item label="注册赠送" style="width: 1000px">
          <el-button type="primary" @click="showResource(1)">添加</el-button>
          <el-table
            :data="form.reg_send"
            style="width: 100%; margin-top: 10px"
            border
          >
            <el-table-column label="类型">
              <template #default="scope2">
                <span v-if="scope2.row.type == 'balance'">余额</span>
                <span v-if="scope2.row.type == 'coupon'">优惠券</span>
              </template>
            </el-table-column>
            <el-table-column prop="name" label="名称"> </el-table-column>
            <el-table-column label="数量">
              <template #default="scope2">
                <span v-if="scope2.row.type == 'balance'">{{
                  scope2.row.value
                }}</span>
                <span v-else>{{ scope2.row.num }}</span>
              </template>
            </el-table-column>
            <el-table-column label="操作">
              <template #default="scope">
                <el-button
                  @click="handleDelReg(scope.$index)"
                  type="text"
                  size="small"
                  >删除</el-button
                >
              </template>
            </el-table-column>
          </el-table>
        </el-form-item>
        <el-form-item label="首次下单赠送" style="width: 1000px">
          <el-button type="primary" @click="showResource(2)">添加</el-button>
          <el-table
            :data="form.first_buy_send"
            style="width: 100%; margin-top: 10px"
            border
          >
            <el-table-column label="类型">
              <template #default="scope2">
                <span v-if="scope2.row.type == 'balance'">余额</span>
                <span v-if="scope2.row.type == 'coupon'">优惠券</span>
              </template>
            </el-table-column>
            <el-table-column prop="name" label="名称"> </el-table-column>
            <el-table-column label="数量">
              <template #default="scope2">
                <span v-if="scope2.row.type == 'balance'">{{
                  scope2.row.value
                }}</span>
                <span v-else>{{ scope2.row.num }}</span>
              </template>
            </el-table-column>
            <el-table-column label="操作">
              <template #default="scope">
                <el-button
                  @click="handleDelOrder(scope.$index)"
                  type="text"
                  size="small"
                  >删除</el-button
                >
              </template>
            </el-table-column>
          </el-table>
        </el-form-item>
        <el-form-item label="返佣时限" style="margin-bottom: 0px">
          <el-input
            v-model="form.send_valid_days"
            style="width: 880px"
            type="number"
          ></el-input
          >天
        </el-form-item>
        <p
          style="
            font-size: 12px;
            margin-left: 120px;
            color: #606266;
            margin-bottom: 18px;
            margin-top: 5px;
          "
        >
          好友绑定关系后，在设定期间内发生的购买支付行为，则推广人可获得相应的奖励
        </p>
        <el-form-item label="返佣比例" style="margin-bottom: 0px">
          <el-input
            v-model="form.rebate_ratio"
            style="width: 880px"
            type="number"
          ></el-input>
        </el-form-item>
        <p
          style="
            font-size: 12px;
            margin-left: 120px;
            color: #606266;
            margin-bottom: 18px;
            margin-top: 5px;
          "
        >
          例如：5%，此处填写 0.05
        </p>
        <el-form-item label="活动介绍">
          <sc-editor v-model="form.info" :height="600"></sc-editor>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" @click="onSubmit">立即保存</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </el-main>

  <el-dialog
    title="选择资源"
    v-model="imgResourceVisible"
    :width="1350"
    destroy-on-close
    :close-on-click-modal="false"
  >
    <Attachment :select-num="1" @selectedFiles="selectedFiles"></Attachment>
  </el-dialog>

  <el-dialog
    title="选择资源"
    v-model="resourceVisible"
    :width="1350"
    destroy-on-close
    :close-on-click-modal="false"
  >
    <resource @selectedResource="handleResource"></resource>
  </el-dialog>
</template>
<script>
import Attachment from "@/components/attachment";
import resource from "@/components/resource";
import { defineAsyncComponent } from "vue";
const scEditor = defineAsyncComponent(() => import("@/components/scEditor"));

export default {
  components: {
    Attachment,
    scEditor,
    resource,
  },
  data() {
    return {
      plusIcon: "el-icon-plus",
      deleteIcon: "el-icon-delete",
      form: {
        pic: "",
        reg_send: [],
        first_buy_send: [],
        send_valid_days: 7,
        rebate_ratio: 0.05,
        info: "",
      },
      imgResourceVisible: false,
      resourceVisible: false,
      type: 1,
    };
  },
  mounted() {
    this.getInfo();
  },
  methods: {
    async getInfo() {
      let res = await this.$API.invite.getSetInfo.get();
      if (res.data) {
        this.form = res.data;
      }
    },
    // 显示大图
    showImage() {
      this.imgResourceVisible = true;
    },
    // 移除图片
    removeIcon() {
      this.form.pic = "";
    },
    // 选择的图片
    selectedFiles(file) {
      this.form.pic = file[0].url;
      this.imgResourceVisible = false;
    },
    // 立即上传
    async onSubmit() {
      let res = await this.$API.invite.set.post(this.form);
      if (res.code == 0) {
        this.$message.success("设置成功");
      } else {
        this.$message.error(res.msg);
      }
    },
    // 显示资源
    showResource(type) {
      this.type = type;
      this.resourceVisible = true;
    },
    // 选择了资源
    handleResource(row) {
      this.resourceVisible = false;
      if (this.type == 1) {
        this.form.reg_send.push(row);
      } else {
        this.form.first_buy_send.push(row);
      }
    },
    // 删除注册赠送
    handleDelReg(row) {
      this.form.reg_send.splice(row, 1);
    },
    // 首次下单赠送
    handleDelOrder(row) {
      this.form.first_buy_send.splice(row, 1);
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
.del-btn {
  color: #f56c6c;
  cursor: pointer;
  margin-left: 5px;
}
.item-tool {
  width: 50px;
  height: 30px;
  line-height: 30px;
  margin-top: 6px;
  cursor: pointer;
}
.up-item-img {
  width: 40px;
  height: 40px;
  border: 1px dashed #c2c2c2;
  text-align: center;
  line-height: 40px;
  cursor: pointer;
}
.cell .img-tools {
  position: absolute;
  width: 40px;
  height: 15px;
  line-height: 15px;
  text-align: center;
  top: 33px;
  background: rgba(0, 0, 0, 0.6);
  cursor: pointer;
}
</style>
