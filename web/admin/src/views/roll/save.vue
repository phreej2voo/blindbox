<template>
  <el-dialog
    :title="titleMap[mode]"
    v-model="visible"
    :width="800"
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
      <el-form-item label="类型" prop="type">
        <el-radio v-model="form.type" :label="1">大佬房</el-radio>
        <el-radio v-model="form.type" :label="2">密码房</el-radio>
      </el-form-item>
      <el-form-item label="房间名称" prop="title">
        <el-input v-model="form.title"></el-input>
      </el-form-item>
      <el-form-item label="描述" prop="desc">
        <el-input v-model="form.desc" :rows="5" type="textarea"></el-input>
      </el-form-item>
      <el-form-item label="开奖时间" prop="reward_time">
        <el-date-picker
          v-model="form.reward_time"
          type="datetime"
          format="YYYY-MM-DD HH:mm"
          value-format="YYYY-MM-DD HH:mm"
        />
      </el-form-item>
      <el-form-item label="限制条件" v-if="form.type == 1">
        <div style="display: flex">
          <div style="width: 300px">
            <el-date-picker
              v-model="form.tips"
              type="daterange"
              format="YYYY-MM-DD"
              value-format="YYYY-MM-DD"
              style="width: 300px"
            />
          </div>
          <div style="width: 240px; display: flex; margin-left: 20px">
            <el-input v-model="form.limit_amount"></el-input>
            <div>元</div>
          </div>
        </div>
      </el-form-item>
      <el-form-item label="房间密码" v-if="form.type == 2">
        <el-input v-model="form.password" style="width: 220px"></el-input>
      </el-form-item>
      <el-form-item label="选择奖品" prop="reward">
        <el-button type="primary" icon="el-icon-plus" @click="selectGoods"
          >选择奖品</el-button
        >
        <el-table :data="tableData" style="width: 100%; margin-top: 20px">
          <el-table-column prop="goods_id" label="ID"> </el-table-column>
          <el-table-column prop="goods_name" label="商品名称">
          </el-table-column>
          <el-table-column label="商品图片">
            <template #default="scope">
              <el-image
                :src="scope.row.image"
                style="height: 36px; width: 36px"
              ></el-image>
            </template>
          </el-table-column>
          <el-table-column prop="recovery_price" label="可兑换哈希币">
          </el-table-column>
          <el-table-column label="操作" fixed="right" width="140">
            <template #default="scope">
              <el-button
                @click="handleDel(scope.$index)"
                type="text"
                size="small"
                >删除</el-button
              >
            </template>
          </el-table-column>
        </el-table>
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

    <el-dialog
      title="奖品选择"
      v-model="dialogVisiable"
      destroy-on-close
      width="50%"
    >
      <Goods
        :selectedgoods="selectedGoods"
        @handleSelected="handleSelected"
        :goods-type="2"
      ></Goods>
    </el-dialog>
  </el-dialog>
</template>

<script>
import Goods from "@/components/multiBlindGoods";

export default {
  components: {
    Goods,
  },
  emits: ["success", "closed"],
  data() {
    return {
      mode: "add",
      dialogVisiable: false,
      titleMap: {
        add: "新增福利房",
        edit: "编辑福利房",
        show: "查看",
      },
      resourceVisible: false,
      visible: false,
      isSaveing: false,
      //表单数据
      form: {
        id: "",
        type: 1,
        title: "",
        desc: "",
        reward_time: "",
        tips: "",
        limit_amount: 0,
        password: "",
        reward: "",
      },
      plusIcon: "el-icon-plus",
      deleteIcon: "el-icon-delete",
      // 验证规则
      rules: {
        type: [{ required: true, message: "请选择类型", trigger: "blur" }],
        title: [{ required: true, message: "请输入房间名称", trigger: "blur" }],
        desc: [{ required: true, message: "请输入描述", trigger: "blur" }],
        reward_time: [{ required: true, message: "开奖时间", trigger: "blur" }],
        reward: [{ required: true, message: "请选择奖品", trigger: "blur" }],
      },
      selectedGoods: [], // 选择的商品
      tableData: [],
      allreadySelected: [],
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
      this.form.reward = this.tableData;
      this.$refs.dialogForm.validate(async (valid) => {
        if (valid) {
          this.isSaveing = true;
          var res;
          if (this.mode == "add") {
            res = await this.$API.roll.add.post(this.form);
          } else {
            res = await this.$API.roll.edit.post(this.form);
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
      this.form.title = data.title;
      this.form.type = data.type;
      this.form.desc = data.desc;
      this.form.password = data.password;
      this.form.reward_time = data.reward_time;
      this.form.limit_amount = data.limit_amount;
      if (data.limit_start_time) {
        this.form.tips = [data.limit_start_time, data.limit_end_time];
      }
      this.form.status = data.status;

      if (this.form.id) {
        this.getReward();
      }
    },
    // 选择商品
    selectGoods() {
      this.dialogVisiable = true;
    },
    // 选中了商品
    handleSelected(row) {
      this.dialogVisiable = false;
      row.forEach((item) => {
        if (this.allreadySelected.indexOf(item.id) == -1) {
          this.allreadySelected.push(item.id);

          this.tableData.push({
            goods_id: item.id,
            goods_name: item.name,
            image: item.image.split(",")[0],
            recovery_price: item.recovery_price,
          });
        }
      });
    },
    // 删除商品
    handleDel(index) {
      this.allreadySelected.splice(index, 1);
      this.tableData.splice(index, 1);
    },
    // 获取奖品
    async getReward() {
      let res = await this.$API.roll.reward.get({ id: this.form.id });
      this.allreadySelected = [];
      this.tableData = [];

      res.data.forEach((item) => {
        this.allreadySelected.push(item.id);

        this.tableData.push({
          goods_id: item.id,
          goods_name: item.goods_name,
          image: item.image,
          recovery_price: item.recovery_price,
        });
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
