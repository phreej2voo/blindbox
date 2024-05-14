<template>
  <el-main>
    <el-card shadow="false" style="display: flex">
      <div class="click-bar" style="width: 300px; display: flex">
        <div @click="goBack()" style="cursor: pointer">
          <el-icon class="back-icon"><el-icon-arrow-left /></el-icon>返回列表
        </div>
        <span style="font-size: 14px; font-weight: 700; margin-left: 20px"
          >盲盒奖品详情</span
        >
      </div>
    </el-card>
    <el-card shadow="never">
      <div style="display: flex">
        <el-button
          type="primary"
          icon="el-icon-plus"
          @click="addBlindbox"
          style="margin-top: 15px"
          >添加奖品</el-button
        >
        <div style="margin-top: 20px; margin-left: 50px">
          当前设定的概率为：<el-tag type="danger"
            >{{ totalProbability }}%</el-tag
          >，必须配置满100%！
        </div>
      </div>

      <div
        style="
          width: 100%;
          height: 0;
          border-bottom: #e4e7ed 1px dashed;
          margin-top: 15px;
        "
      ></div>
      <el-table :data="tableData" style="width: 100%; margin-top: 20px">
        <el-table-column prop="goods_name" label="商品名称"> </el-table-column>
        <el-table-column label="商品图片">
          <template #default="scope">
            <el-image
              :src="scope.row.goods_image?.split(',')[0] ?? scope.row.goods[0].goods_image"
              style="height: 36px; width: 36px"
            ></el-image>
          </template>
        </el-table-column>
        <el-table-column label="赏种">
          <template #default="scope">
            <el-select
              v-model="scope.row.tag_id"
              placeholder="请选择"
              style="width: 150px"
              @change="selectType(scope.$index)"
            >
              <el-option
                v-for="item in goodsTag"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              >
              </el-option>
            </el-select>
          </template>
        </el-table-column>
        <el-table-column prop="price" label="售价"> </el-table-column>
        <el-table-column prop="recovery_price" label="可兑换哈希币">
        </el-table-column>
        <el-table-column label="中奖概率%">
          <template #default="scope">
            <el-input
              style="width: 150px"
              v-model="scope.row.probability"
              @blur="resetProbaility"
              v-if="scope.row.tag_id > 0"
            >
              <template #append>%</template>
            </el-input>
            <span v-else>/</span>
          </template>
        </el-table-column>
        <el-table-column prop="stock" label="库存">
          <template #default="scope">
            <el-input
              v-model="scope.row.stock"
              v-if="scope.row.tag_id != 0"
              type="number"
              @change="changeProbaility"
              style="width: 150px"
            ></el-input>
            <span v-else>/</span>
          </template>
        </el-table-column>
        <el-table-column label="操作" fixed="right" width="140">
          <template #default="scope">
            <el-button @click="handleDel(scope.$index)" type="text" size="small"
              >删除</el-button
            >
          </template>
        </el-table-column>
      </el-table>

      <el-button
        type="danger"
        icon="el-icon-document"
        @click="saveBlindbox"
        style="margin-top: 15px"
        >保存奖品</el-button
      >
    </el-card>
    <el-card shadow="never" v-if="(playId == 1 || playId == 3) && !hasSet">
      <el-form ref="form" :model="boxForm" label-width="80px">
        <el-form-item label="箱子数量">
          <el-input
            v-model="boxForm.num"
            type="number"
            style="width: 300px"
          ></el-input>
        </el-form-item>
        <el-button
          type="danger"
          icon="el-icon-document"
          @click="makeBox"
          style="margin-top: 15px"
          >生成箱子</el-button
        >
      </el-form>
    </el-card>
    <el-dialog
      title="奖品选择"
      v-model="dialog.goods"
      destroy-on-close
      width="50%"
    >
      <Goods
        :selectedgoods="selectedGoods"
        @handleSelected="handleSelected"
        :goods-type="2"
      ></Goods>
    </el-dialog>
  </el-main>
</template>

<script>
import Goods from "@/components/goods";
export default {
  name: "goodsCate",
  components: {
    Goods,
  },
  data() {
    return {
      dialog: {
        save: false,
        goods: false,
      },
      boxForm: {
        blindbox_id: this.$route.query.id,
        num: 0,
      },
      tableData: [],
      blindbox_id: this.$route.query.id,
      totalProbability: 0,
      goodsTag: [],
      hasSet: false,
      playId: this.$route.query.play_id,
      selectedGoods: [], // 选择的商品
    };
  },
  mounted() {
    this.getList();
    this.getBlindboxTag();
  },
  methods: {
    // 改变概率
    changeProbaility() {
      this.totalProbability = 0;
      let totalStock = 0;
      this.tableData.forEach((item) => {
        totalStock += parseInt(item.stock);
      });

      this.tableData.forEach((item, index) => {
        this.tableData[index].probability =
          totalStock == 0
            ? parseInt(0)
            : ((item.stock / totalStock) * 100).toFixed(3);

        this.totalProbability += this.tableData[index].probability * 1000;
      });

      this.totalProbability = (this.totalProbability / 1000).toFixed(3);
    },
    // 手动更改概率
    resetProbaility() {
      this.totalProbability = 0;
      this.tableData.forEach((item) => {
        this.totalProbability += item.probability * 1000;
      });

      this.totalProbability = (this.totalProbability / 1000).toFixed(3);
    },
    // 添加盲盒商品
    addBlindbox() {
      this.dialog.goods = true;
    },
    // 编辑
    handleEdit(row) {
      this.dialog.save = true;
      this.$nextTick(() => {
        this.$refs.saveDialog.open("edit").setData(row);
      });
    },
    // 删除
    handleDel(index) {
      this.tableData.splice(index, 1);
      this.changeProbaility();
    },
    // 获取列表
    async getList() {
      let res = await this.$API.blindboxDetail.list.get({
        blindbox_id: this.blindbox_id,
      });
      this.tableData = res.data;

      if (res.data.length > 0) {
        this.hasSet = true;
      }

      this.resetProbaility();
    },
    handleSuccess() {
      this.getList();
    },
    // 获取盲盒标签
    async getBlindboxTag() {
      let res = await this.$API.blindboxTag.list.get({
        limit: 9999999,
        page: 1,
        status: 1,
        hashmart_auth_skip: 1,
      });
      this.goodsTag = res.data.rows;

      if (this.playId == 1 || this.playId == 3) {
        this.goodsTag.push({ id: 0, name: "LAST" });
      }
    },
    goBack() {
      this.$router.push({
        path: "/blindbox/index",
      });
    },
    // 保存盲盒商品
    async saveBlindbox() {
      if (this.totalProbability != 100) {
        this.$message.error("当前概率设置错误，总概率不是100%");
        return false;
      }

      let res = await this.$API.blindboxDetail.add.post({
        play_id: this.playId,
        data: this.tableData,
        blindbox_id: this.blindbox_id,
      });

      if (res.code == 0) {
        this.$message.success("保存成功");
      } else {
        this.$message.error(res.msg);
      }
    },
    // 单选
    handleSelected(selectedGoods) {
      this.tableData.push({
        blindbox_id: this.blindbox_id,
        tag_id: "",
        goods_id: selectedGoods[0].id,
        goods_name: selectedGoods[0].name,
        goods_image: selectedGoods[0].image,
        price: selectedGoods[0].price,
        recovery_price: selectedGoods[0].recovery_price,
        stock: 1,
        probability: 0,
      });

      this.dialog.goods = false;
    },
    // 更换赏种
    selectType(index) {
      let tagId = this.tableData[index].tag_id;
      if (tagId <= 0) {
        this.tableData[index].probability = 0;
        this.tableData[index].stock = 1;
      } else {
        this.changeProbaility();
      }
    },
    // 生成箱子
    async makeBox() {
      if (this.boxForm.num <= 0) {
        this.$message.error("箱子数量必须大于0");
        return false;
      }

      let res = await this.$API.blindboxDetail.box.post(this.boxForm);
      if (res.code == 0) {
        this.$message.success("生成箱子成功");
      } else {
        this.$message.error(res.msg);
      }
    },
  },
};
</script>

<style scoped>
.back-icon {
  position: relative;
  top: 2px;
  margin-top: 3px;
}
</style>
