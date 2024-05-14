<template>
  <el-card class="box-card" shadow="never" style="margin-top: 10px">
    <div class="form-body">
      <el-form
        :model="form"
        label-width="150px"
        :rules="formRules"
        ref="ruleForm"
      >
        <el-form-item label="合成标题" prop="name">
          <el-input
            style="width: 500px"
            v-model="form.name"
            autocomplete="off"
          ></el-input>
        </el-form-item>
        <!-- 需要合成的商品 -->
        <el-form-item label="选择商品" prop="goods_id">
          <div class="sel-div">
            <div v-if="sel_goods.length">
              <el-table
                ref="selTable"
                style="width: 100%"
                :data="sel_goods"
                v-loading="listLoading"
                max-height="500"
              >
                <el-table-column prop="goods_id" label="商品ID" width="100">
                </el-table-column>
                <el-table-column
                  prop="goods_name"
                  label="商品名称"
                  show-overflow-tooltip
                  width="200"
                >
                </el-table-column>
                <el-table-column label="商品图片" width="120" prop="image">
                  <template #default="scope">
                    <div>
                      <img
                        :src="scope.row.image"
                        style="width: 50px; height: 50px"
                      />
                    </div>
                  </template>
                </el-table-column>
                <el-table-column prop="price" label="商品售价" width="200">
                </el-table-column>
                <el-table-column prop="conflate_val" label="合成值" width="200">
                  <template #default="scope">
                    <el-input
                      style="min-width: 100px"
                      placeholder=""
                      type="number"
                      min="0"
                      v-model.number="scope.row.conflate_val"
                      @change="changeConflateVal"
                    >
                    </el-input>
                  </template>
                </el-table-column>
              </el-table>
            </div>
            <div class="add-btn">
              <el-button
                type="primary"
                icon="el-icon-plus"
                @click="selBlindbox"
              >
                选择商品
              </el-button>
            </div>
          </div>
        </el-form-item>
        <el-form-item label="是否指定碎片材料" prop="conflate_limit">
          <el-radio-group v-model="form.conflate_limit" @change="radioChange">
            <el-radio :label="1"> 不限制碎片材料 </el-radio>
            <el-radio :label="2"> 指定商品碎片材料 </el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item
          label="指定商品"
          v-if="form.conflate_limit == 2"
          prop="conflate_goods"
        >
          <el-table :data="form.conflate_goods" style="width: 100%">
            <el-table-column prop="goods_id" label="商品ID" width="100">
            </el-table-column>
            <el-table-column
              prop="goods_name"
              label="商品名称"
              show-overflow-tooltip
              width="200"
            >
            </el-table-column>
            <el-table-column label="商品图片" width="120" prop="image">
              <template #default="scope">
                <div>
                  <img
                    :src="scope.row.image"
                    style="width: 50px; height: 50px"
                  />
                </div>
              </template>
            </el-table-column>
            <el-table-column prop="conflate_val" label="合成值" width="200">
              <template #default="scope">
                <el-input
                  style="min-width: 100px"
                  placeholder=""
                  type="number"
                  min="0"
                  v-model.number="scope.row.conflate_val"
                >
                </el-input>
              </template>
            </el-table-column>
            <el-table-column fixed="right" label="操作">
              <template #default="scope">
                <el-button
                  @click="handleDel(scope.$index)"
                  type="text"
                  size="small"
                >
                  删除
                </el-button>
              </template>
            </el-table-column>
          </el-table>
          <div>
            <el-button
              type="primary"
              icon="el-icon-plus"
              style="margin-top: 15px"
              @click="assignBlindbox"
            >
              指定商品
            </el-button>
          </div>
        </el-form-item>
        <!-- 分割线 -->
        <el-divider border-style="dashed" />
        <el-form-item label="活动时间" prop="activity_time">
          <div style="width: 500px; display: flex">
            <el-date-picker
              v-model="form.activity_time"
              unlink-panels
              type="daterange"
              value-format="YYYY-MM-DD"
              range-separator="至"
              start-placeholder="开始日期"
              end-placeholder="结束日期"
            ></el-date-picker>
          </div>
        </el-form-item>
        <el-form-item label="库存" prop="stock">
          <el-input v-model="form.stock" style="width: 500px" type="number" />
        </el-form-item>
        <el-form-item label="是否上架" prop="status">
          <el-radio v-model="form.status" :label="1">上架</el-radio>
          <el-radio v-model="form.status" :label="2">下架</el-radio>
        </el-form-item>
        <el-form-item label="排序值" prop="sort">
          <el-input
            v-model="form.sort"
            style="width: 500px"
            type="number"
            placeholder="值越大越靠前"
          />
        </el-form-item>
        <!-- <el-form-item label="合成值" prop="conflate_val">
					<el-input-number v-model="form.conflate_val" :min="0" />
					<span style="font-size: 12px; margin-left: 10px">
						(用于碎片合成时进度计算)
					</span>
				</el-form-item> -->
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
    </div>

    <el-dialog
      title="商品选择"
      v-model="dialog.selGoods"
      destroy-on-close
      width="60%"
    >
      <Goods
        :selectedgoods="selectedGoods"
        @handleSelected="handleSelected"
        :goods-type="2"
      >
      </Goods>
    </el-dialog>
    <el-dialog
      title="指定商品"
      v-model="dialog.assignGoods"
      destroy-on-close
      width="60%"
    >
      <MultiBlindGoods
        :selectedgoods="assignedGoods"
        :goods-type="2"
        @handleSelected="handleAssignSelected"
      >
      </MultiBlindGoods>
    </el-dialog>
  </el-card>
</template>

<script>
import Goods from "@/components/goods";
import MultiBlindGoods from "@/components/multiBlindGoods";

export default {
  name: "AdvanceCondition",
  components: {
    Goods,
    MultiBlindGoods,
  },
  data() {
    return {
      mode: "add",
      form: {
        name: "",
        activity_time: "",

        // 选择商品(需要合成的商品参数)
        goods_id: "",
        goods_name: "",
        image: "",
        price: "",

        conflate_val: 0, // 合成值
        conflate_limit: 1,
        stock: 0, // 库存
        status: 1, // 状态 1-上架 2-下架
        sort: 10, // 排序
        conflate_goods: [], // 指定商品 goods_id goods_name image conflate_val
      },
      sel_goods: [],
      formRules: {
        name: [
          {
            required: true,
            message: "标题不能为空",
            trigger: "blur",
          },
        ],
        goods_id: [
          {
            required: true,
            message: "请选择商品",
            trigger: "blur",
          },
        ],
        conflate_limit: [
          {
            required: true,
            message: "请选择是否指定碎片材料",
            trigger: "change",
          },
        ],
        activity_time: [
          {
            required: true,
            message: "请选择活动时间",
            trigger: "change",
          },
        ],
        stock: [
          {
            required: true,
            message: "请填写库存",
            trigger: "change",
          },
        ],
        status: [
          {
            required: true,
            message: "请选择是否上架",
            trigger: "change",
          },
        ],
        sort: [
          {
            required: true,
            message: "请填写排序值",
            trigger: "change",
          },
        ],
      },
      noRules: {
        name: [
          {
            required: true,
            message: "标题不能为空",
            trigger: "blur",
          },
        ],
        goods_id: [
          {
            required: true,
            message: "请选择商品",
            trigger: "blur",
          },
        ],
        conflate_limit: [
          {
            required: true,
            message: "请选择是否指定碎片材料",
            trigger: "change",
          },
        ],
        activity_time: [
          {
            required: true,
            message: "请选择活动时间",
            trigger: "change",
          },
        ],
        stock: [
          {
            required: true,
            message: "请填写库存",
            trigger: "change",
          },
        ],
        status: [
          {
            required: true,
            message: "请选择是否上架",
            trigger: "change",
          },
        ],
        sort: [
          {
            required: true,
            message: "请填写排序值",
            trigger: "change",
          },
        ],
      },
      assignRules: {
        name: [
          {
            required: true,
            message: "标题不能为空",
            trigger: "blur",
          },
        ],
        goods_id: [
          {
            required: true,
            message: "请选择商品",
            trigger: "blur",
          },
        ],
        conflate_limit: [
          {
            required: true,
            message: "请选择是否指定碎片材料",
            trigger: "change",
          },
        ],
        activity_time: [
          {
            required: true,
            message: "请选择活动时间",
            trigger: "change",
          },
        ],
        stock: [
          {
            required: true,
            message: "请填写库存",
            trigger: "change",
          },
        ],
        status: [
          {
            required: true,
            message: "请选择是否上架",
            trigger: "change",
          },
        ],
        sort: [
          {
            required: true,
            message: "请填写排序值",
            trigger: "change",
          },
        ],
        conflate_goods: [
          {
            required: true,
            message: "请选择指定商品",
            trigger: "change",
          },
        ],
      },
      listLoading: false,
      selectedGoods: [],
      assignedGoods: [],
      dialog: {
        selGoods: false,
        assignGoods: false,
      },
      editId: "",
      btnloading: false,
    };
  },
  methods: {
    radioChange(val) {
      if (this.mode == "add") {
        if (val == 1) {
          this.form.conflate_goods = [];
        }
      }
    },
    setMode(mode, row) {
      this.mode = mode;
      if (mode == "edit") {
        this.editId = row.id;
        this.form = {
          name: row.name,
          activity_time: [row.start_time, row.end_time],
          goods_id: row.goods_id,
          goods_name: row.goods_name,
          image: row.image,
          price: row.price,
          conflate_val: row.conflate_val,
          conflate_limit: row.conflate_limit,
          stock: row.stock,
          status: row.status,
          sort: row.sort,
        };
        this.sel_goods = [
          {
            goods_id: row.goods_id,
            goods_name: row.goods_name,
            image: row.image,
            price: row.price,
            conflate_val: row.conflate_val,
          },
        ];
        if (row.goods) {
          this.form.conflate_goods = row.goods.map((item) => {
            return {
              goods_id: item.goods_id,
              goods_name: item.goods_name,
              image: item.image.split(",")[0],
              conflate_val: item.conflate_val || 0,
            };
          });
        }
      }
    },
    // 选择商品
    selBlindbox() {
      this.dialog.selGoods = true;
    },
    // 指定商品
    assignBlindbox() {
      this.dialog.assignGoods = true;
    },
    changeConflateVal(val) {
      console.log("val-change-", val);
      this.form.conflate_val = val;
    },
    handleSelected(val) {
      console.log("sel-", val);
      this.sel_goods = val.map((item) => {
        return {
          goods_id: item.id,
          goods_name: item.name,
          image: item.image.split(",")[0],
          price: item.price,
          conflate_val: item.conflate_val || 0,
        };
      });
      this.form.goods_id = val[0].id;
      this.form.goods_name = val[0].name;
      this.form.image = val[0].image;
      this.form.price = val[0].price;
      this.form.conflate_val = val[0].conflate_val;

      this.dialog.selGoods = false;
    },
    handleAssignSelected(val) {
      console.log("assign-", val);
      let arr = [];

      if (!this.form.conflate_goods.length) {
        arr = val.map((item) => {
          return {
            goods_id: item.id,
            goods_name: item.name,
            image: item.image.split(",")[0],
            conflate_val: item.conflate_val || 0,
          };
        });
      } else {
        for (let i = 0; i < val.length; i++) {
          const item = val[i];
          const filterArr = this.form.conflate_goods.filter((child) => {
            return item.id == child.goods_id;
          });
          if (!filterArr.length) {
            arr.push({
              goods_id: item.id,
              goods_name: item.name,
              image: item.image.split(",")[0],
              conflate_val: item.conflate_val || 0,
            });
          }
        }
      }

      this.form.conflate_goods = this.form.conflate_goods.concat(arr);

      this.dialog.assignGoods = false;
    },
    // 删除选择的盲盒
    handleDel(index) {
      this.form.conflate_goods.splice(index, 1);
    },
    onSubmit() {
      this.btnloading = true;
      this.$refs.ruleForm.validate(async (valid) => {
        if (valid) {
          const param = JSON.parse(JSON.stringify(this.form));
          if (param.conflate_limit == 1) {
            delete param.conflate_goods;
          } else {
            if (!param.conflate_goods.length) {
              this.$message.error("请指定碎片商品!");
              this.btnloading = false;
              return false;
            }
            param.conflate_goods = JSON.stringify(param.conflate_goods);
          }
          if (this.mode == "add") {
            const { code, msg } = await this.$API.conflate.conflateAdd.post(
              param
            );
            if (code == 0) {
              this.$message.success(msg);
              this.$emit("successBack");
            } else {
              this.$message.error(msg);
            }
          } else {
            param.id = this.editId;
            const { code, msg } = await this.$API.conflate.conflateEdit.post(
              param
            );
            if (code == 0) {
              this.$message.success(msg);
              this.$emit("successBack");
            } else {
              this.$message.error(msg);
            }
          }
          this.btnloading = false;
        } else {
          this.btnloading = false;
        }
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.sel-div {
  display: flex;
  flex-direction: column;
  justify-content: center;
  .add-btn {
    width: 100px;
  }
}
</style>