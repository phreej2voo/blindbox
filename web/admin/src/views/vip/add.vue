<template>
  <el-main>
    <el-card shadow="never">
      <el-form
        :model="form"
        label-width="150px"
        :rules="formRules"
        ref="ruleForm"
      >
        <el-form-item label="会员卡名" prop="title">
          <el-input
            style="width: 500px"
            v-model="form.title"
            autocomplete="off"
          ></el-input>
        </el-form-item>
        <el-form-item label="类型" prop="type">
          <!--<el-radio v-model="form.type" :label="1">周卡</el-radio>
          <el-radio v-model="form.type" :label="2">月卡</el-radio>-->
          <el-radio v-model="form.type" :label="3">年卡</el-radio>
        </el-form-item>
        <el-form-item label="设置会员卡" class="set-card">
          <div>
            <el-button type="primary" @click="batchSet">批量设置</el-button>
          </div>
          <div v-if="form.type == 1" class="month-box">
            <div
              class="card-item"
              v-for="(item, index) in sevenDay"
              :key="item.day"
            >
              <div class="title">
                第{{ item.day }}天
                <span
                  style="color: #409eff; cursor: pointer; margin-left: 80px"
                  @click="editCard(index)"
                  >设置</span
                >
              </div>
              <ul>
                <li v-for="(val, index2) in item.award" :key="index2">
                  {{ val.name }}
                  <span v-if="val.type == 'balance'">{{ val.value }}</span> *
                  {{ val.num }}
                </li>
              </ul>
            </div>
          </div>
          <div
            v-if="form.type == 3"
            class="month-box"
            style="flex-wrap: wrap; width: 990px"
          >
            <div
              class="card-item"
              v-for="(item, index) in monthDay"
              :key="item.day"
            >
              <div class="title">
                第{{ item.day }}天
                <span
                  style="color: #409eff; cursor: pointer; margin-left: 70px"
                  @click="editCard(index)"
                  >设置</span
                >
              </div>
              <ul>
                <li v-for="(val, index2) in item.award" :key="index2">
                  {{ val.name }}
                  <span v-if="val.type == 'balance'">{{ val.value }}</span> *
                  {{ val.num }}
                </li>
              </ul>
            </div>
          </div>
        </el-form-item>
        <el-form-item label="价格" prop="price">
          <el-input
            style="width: 500px"
            v-model="form.price"
            autocomplete="off"
            type="number"
          ></el-input>
        </el-form-item>
        <el-form-item label="库存" prop="stock">
          <el-input
            style="width: 500px"
            v-model="form.stock"
            autocomplete="off"
            type="number"
          ></el-input>
          （-1表示无限制）
        </el-form-item>
        <el-form-item label="每人限制" prop="one_limit">
          <el-input
            style="width: 500px"
            v-model="form.one_limit"
            autocomplete="off"
            type="number"
          ></el-input>
          （-1表示无限制）
        </el-form-item>
        <el-form-item label="描述">
          <el-input
            style="width: 500px"
            v-model="form.description"
            autocomplete="off"
            :rows="8"
            type="textarea"
          ></el-input>
        </el-form-item>
        <el-form-item label="状态" prop="status">
          <el-radio v-model="form.status" :label="1">正常</el-radio>
          <el-radio v-model="form.status" :label="2">作废</el-radio>
        </el-form-item>
        <el-form-item>
          <el-button
            type="primary"
            class="button-item"
            @click="onSubmit"
            :loading="btnloading"
          >
            提交
          </el-button>
        </el-form-item>
      </el-form>
    </el-card>

    <el-dialog
      title="选择资源"
      v-model="imgResourceVisible"
      :width="1350"
      destroy-on-close
      :close-on-click-modal="false"
    >
      <resource @selectedResource="handleResource"></resource>
    </el-dialog>

    <el-dialog
      title="批量设置"
      v-model="awardBatchDialog"
      :width="700"
      destroy-on-close
      :close-on-click-modal="false"
    >
      <el-button type="primary" @click="showResource(1)">添加</el-button>
      <el-button type="success" @click="saveSet"> 使用设置 </el-button>
      <el-table :data="awardDay" style="width: 100%; margin-top: 10px" border>
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
            <el-button @click="handleDel(scope.$index)" type="text" size="small"
              >删除</el-button
            >
          </template>
        </el-table-column>
      </el-table>
    </el-dialog>

    <el-dialog
      title="单独设置"
      v-model="awardDialog"
      :width="700"
      destroy-on-close
      :close-on-click-modal="false"
    >
      <el-button type="primary" @click="showResource(2)">添加</el-button>
      <el-button type="success" @click="saveSet"> 使用设置 </el-button>
      <el-table
        :data="awardOneDay"
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
            <el-button @click="handleDel(scope.$index)" type="text" size="small"
              >删除</el-button
            >
          </template>
        </el-table-column>
      </el-table>
    </el-dialog>
  </el-main>
</template>

<script>
import resource from "@/components/resource";

export default {
  name: "vip_add",
  components: {
    resource,
  },
  data() {
    return {
      form: {
        type: 3,
        title: "",
        price: 0,
        stock: -1,
        one_limit: -1,
        description: "",
        status: 1,
        equity: "",
      },
      imgResourceVisible: false,
      formRules: {
        title: [{ required: true, message: "请输入会员卡名", trigger: "blur" }],
        type: [{ required: true, message: "会员卡类型", trigger: "blur" }],
        price: [{ required: true, message: "请输入价格", trigger: "blur" }],
        stock: [{ required: true, message: "请输入库存", trigger: "blur" }],
        one_limit: [
          { required: true, message: "累计购买限制", trigger: "blur" },
        ],
        status: [{ required: true, message: "请选择状态", trigger: "blur" }],
      },
      sevenDay: [], // 周卡奖品
      monthDay: [], // 月卡奖品 --> 年卡奖品
      awardDay: [], // 奖品设置
      awardOneDay: [],
      awardBatchDialog: false,
      awardDialog: false,
      resourceType: 1, // 资源类型
      nowIndex: 0,
      type: "add",
      id: 0,
    };
  },
  mounted() {
    this.initDayMap(7);
    this.initDayMap(365);
  },
  methods: {
    async onSubmit() {
      this.$refs.ruleForm.validate(async (valid) => {
        if (valid) {
          let res;
          if (this.form.type == 1) {
            this.form.equity = this.sevenDay;
          } else {
            this.form.equity = this.monthDay;
          }

          if (this.form.id) {
            res = await this.$API.vipCard.edit.post(this.form);
          } else {
            res = await this.$API.vipCard.add.post(this.form);
          }

          if (res.code == 0) {
            this.$message.success(res.msg);
            setTimeout(() => {
              this.$emit("save");
            }, 500);
          } else {
            this.$message.error(res.msg);
          }
        }
      });
    },
    // 初始化
    initDayMap(day) {
      for (let i = 0; i < day; i++) {
        if (day == 7) {
          this.sevenDay.push({
            day: i + 1,
            award: [],
          });
        } else {
          this.monthDay.push({
            day: i + 1,
            award: [],
          });
        }
      }
    },
    setMode(mode, id) {
      this.type = mode;
      this.id = id;

      if (this.type == "edit") {
        this.getCardInfo(this.id);
      }
      return this;
    },
    // 批量设置
    batchSet() {
      this.awardBatchDialog = true;
    },
    // 单独设置
    editCard(index) {
      this.awardDialog = true;
      let award;
      if (this.form.type == 1) {
        award = this.sevenDay[index].award;
      } else {
        award = this.monthDay[index].award;
      }

      this.awardOneDay = JSON.parse(JSON.stringify(award));
      this.nowIndex = index;
    },
    // 显示资源
    showResource(type) {
      this.resourceType = type;
      this.imgResourceVisible = true;
    },
    // 选择资源
    handleResource(row) {
      // 批量设置
      this.imgResourceVisible = false;
      if (this.resourceType == 1) {
        this.awardDay.push(row);
      } else {
        this.awardOneDay.push(row);
      }
    },
    // 删除
    handleDel(index) {
      if (this.awardBatchDialog) {
        this.awardDay.splice(index, 1);
      }

      if (this.awardDialog) {
        this.awardOneDay.splice(index, 1);
      }
    },
    // 应用设置
    saveSet() {
      // 批量设置
      if (this.awardBatchDialog) {
        this.awardBatchDialog = false;
        if (this.form.type == 1) {
          for (let i = 0; i < 7; i++) {
            this.sevenDay[i].award = this.awardDay;
          }
        } else {
          for (let i = 0; i < 365; i++) {
            this.monthDay[i].award = this.awardDay;
          }
        }
      }

      if (this.awardDialog) {
        this.awardDialog = false;
        if (this.form.type == 1) {
          this.sevenDay[this.nowIndex].award = this.awardOneDay;
        } else {
          this.monthDay[this.nowIndex].award = this.awardOneDay;
        }
      }
    },
    // 获取卡片信息
    async getCardInfo(id) {
      let res = await this.$API.vipCard.info.get({ id: id });
      let data = res.data;
      this.form.id = data.id;
      this.form.type = data.type;
      this.form.title = data.title;
      this.form.price = data.price;
      this.form.stock = data.stock;
      this.form.one_limit = data.one_limit;
      this.form.description = data.description;
      this.form.status = data.status;

      if (this.form.type == 1) {
        this.sevenDay = JSON.parse(data.equity);
      } else {
        this.monthDay = JSON.parse(data.equity);
      }
    },
  },
};
</script>

<style>
.set-card .el-form-item__content {
  display: block;
}
</style>

<style scoped>
.month-box {
  display: flex;
}
.month-box .card-item {
  width: 160px;
  height: 180px;
  border: 1px solid #dcdfe6;
  margin-left: 5px;
  margin-top: 5px;
}
.month-box .card-item:first-child {
  margin-left: 0px;
}
.card-item .title {
  background: #f3f5f6;
  padding-left: 5px;
}
.card-item ul {
  width: 100%;
  height: 148px;
  overflow: hidden;
}
.card-item ul li {
  list-style: none;
  padding: 0 5px 0 5px;
}
</style>
