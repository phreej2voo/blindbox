<template>
  <el-main>
    <el-card shadow="never">
      <div shadow="never" style="border: none">
        <el-form :inline="true" :model="searchForm" class="demo-form-inline">
          <el-form-item label="盲盒名称">
            <el-input
              v-model="searchForm.name"
              placeholder="盲盒名称"
              clearable
            ></el-input>
          </el-form-item>
          <el-form-item label="玩法">
            <el-select
              v-model="searchForm.play_id"
              placeholder="请选择玩法"
              clearable
            >
              <!-- <el-option label="一番赏" :value="1"></el-option>
              <el-option label="哈希赏" :value="2"></el-option>
              <el-option label="全随机" :value="3"></el-option>-->
              <el-option label="潮玩赏" :value="4"></el-option>
              <el-option label="无限赏" :value="5"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="search">查询</el-button>
          </el-form-item>
        </el-form>
      </div>
      <div
        style="
          width: 100%;
          height: 0;
          border-bottom: #e4e7ed 1px dashed;
          margin-top: 15px;
        "
      ></div>
      <el-button
        type="primary"
        icon="el-icon-plus"
        @click="addBlindbox"
        style="margin-top: 15px"
        >添加盲盒</el-button
      >
      <div class="line"></div>
      <el-table :data="tableData" style="width: 100%; margin-top: 20px">
        <el-table-column prop="id" label="ID" width="100px"> </el-table-column>
        <el-table-column prop="name" label="盲盒名称"> </el-table-column>
        <el-table-column label="图标" width="100px">
          <template #default="scope">
            <el-image
              :src="scope.row.pic"
              style="height: 36px; width: 36px"
            ></el-image>
          </template>
        </el-table-column>
        <el-table-column label="玩法" width="100px">
          <template #default="scope">
            <el-tag type="success" v-if="scope.row.play_id == 1">一番赏</el-tag>
            <el-tag type="danger" v-if="scope.row.play_id == 2">哈希赏</el-tag>
            <el-tag v-if="scope.row.play_id == 3">全随机</el-tag>
		    <el-tag type="success" v-if="scope.row.play_id == 4">潮玩赏</el-tag>
			<el-tag type="success" v-if="scope.row.play_id == 5">无限赏</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="price" label="单抽价格" width="100px">
        </el-table-column>
        <el-table-column prop="box_num" label="总箱数" width="100px">
        </el-table-column>
        <el-table-column prop="goods_num" label="奖品数量" width="100px">
        </el-table-column>
        <el-table-column prop="sort" label="排序" width="100px">
        </el-table-column>
        <el-table-column label="状态" width="100px">
          <template #default="scope">
            <el-tag type="success" v-if="scope.row.status == 1">上架</el-tag>
            <el-tag type="danger" v-if="scope.row.status == 2">下架</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="奖品详情">
          <template #default="scope">
            <el-button
              type="primary"
              plain
              size="small"
              @click="goodsDetail(scope.row)"
              icon="el-icon-Goods"
              >奖品详情</el-button
            >
          </template>
        </el-table-column>
        <el-table-column label="操作" fixed="right" width="230">
          <template #default="scope">
            <el-button @click="handleEdit(scope.row)" type="text" size="small"
              >编辑</el-button
            >
            <el-button @click="handleDel(scope.row)" type="text" size="small"
              >删除</el-button
            >
            <el-button @click="boxDetail(scope.row)" type="text" size="small"
              >查看箱子</el-button
            >
          </template>
        </el-table-column>
      </el-table>
      <div class="page-div" style="margin-top: 20px">
        <el-pagination
          background
          layout="->, prev, pager, next"
          :page-size="searchForm.limit"
          @current-change="pageChangeHandle"
          :total="page.total"
        >
        </el-pagination>
      </div>
    </el-card>

    <save-dialog
      v-if="dialog.save"
      ref="saveDialog"
      @success="handleSuccess"
      @closed="dialog.save = false"
    ></save-dialog>
  </el-main>
</template>

<script>
import saveDialog from "./save";

export default {
  name: "goodsCate",
  components: {
    saveDialog,
  },
  data() {
    return {
      dialog: {
        save: false,
      },
      tableData: [],
      searchForm: {
        name: "",
        play_id: "",
        page: 1,
        limit: 15,
      },
      page: {
        total: 1,
      },
    };
  },
  mounted() {
    this.getList();
  },
  methods: {
    // 查询
    search() {
      this.getList();
    },
    // 添加
    addBlindbox() {
      this.dialog.save = true;
      this.$nextTick(() => {
        this.$refs.saveDialog.open();
      });
    },
    // 商品详情
    goodsDetail(row) {
      this.$router.push({
        path: "/blindbox/detail",
        query: {
          id: row.id,
          play_id: row.play_id,
        },
      });
    },
    // 编辑
    handleEdit(row) {
      this.dialog.save = true;
      this.$nextTick(() => {
        this.$refs.saveDialog.open("edit").setData(row);
      });
    },
    // 删除
    handleDel(row) {
      this.$confirm("此操作将永久删除该盲盒, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(async () => {
          let res = await this.$API.blindbox.del.get({ id: row.id });
          if (res.code == 0) {
            this.$message.success(res.msg);
            this.getList();
          } else {
            this.$message.error(res.msg);
          }
        })
        .catch(() => {});
    },
    // 获取列表
    async getList() {
      let res = await this.$API.blindbox.list.get(this.searchForm);
      this.tableData = res.data.rows;
      this.page.total = res.data.total;
    },
    handleSuccess() {
      this.getList();
    },
    // 翻页
    pageChangeHandle(page) {
      this.searchForm.page = page;
      this.getList();
    },
    // 箱子详情
    boxDetail(row) {
      this.$router.push({
        path: "/blindbox/boxes",
        query: {
          id: row.id,
          playId: row.play_id,
        },
      });
    },
  },
};
</script>

<style scoped>
.line {
  width: 100%;
  height: 0;
  border-bottom: #e4e7ed 1px dashed;
  margin-top: 15px;
}
</style>
