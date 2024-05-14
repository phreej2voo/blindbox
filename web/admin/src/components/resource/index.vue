<template>
  <el-main>
    <el-form ref="form" :model="form" label-width="80px">
      <el-form-item label="赠送数量" v-if="activeName == 'first'">
        <el-input v-model="form.num" style="width: 500px"></el-input>
      </el-form-item>
      <el-form-item label="赠送种类">
        <el-tabs
          v-model="activeName"
          @tab-click="handleClick"
          style="width: 1200px"
        >
          <el-tab-pane label="优惠券" name="first">
            <Coupon @handleSelected="handleSelectedCoupon"></Coupon>
          </el-tab-pane>
          <el-tab-pane label="余额" name="fourth">
            <el-input
              v-model="form.value"
              style="width: 500px"
              type="number"
            ></el-input>
          </el-tab-pane>
        </el-tabs>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit">确认提交</el-button>
      </el-form-item>
    </el-form>
  </el-main>
</template>
<script>
import blindbox from "@/components/blindbox";
import Coupon from "@/components/coupon";
export default {
  components: {
    blindbox,
    Coupon,
  },
  data() {
    return {
      activeName: "first",
      form: {
        num: 1,
        type: "coupon",
        name: "",
        resourceId: "1",
        value: 0,
      },
      selectedGoods: [],
    };
  },
  methods: {
    // tab切换
    handleClick(_, event) {
      let id = event.target.getAttribute("id");
      if (id == "tab-first") {
        this.form.type = "coupon";
        this.form.name = "优惠券";
      } else if (id == "tab-fourth") {
        this.form.type = "balance";
        this.form.name = "余额";
      }
    },
    // 选择了优惠券
    handleSelectedCoupon(row) {
      this.form.type = "coupon";
      this.form.resourceId = row[0].id;
      this.form.name = row[0].name;
    },
    // 确认提交
    onSubmit() {
      this.$emit("selectedResource", this.form);
    },
  },
};
</script>
