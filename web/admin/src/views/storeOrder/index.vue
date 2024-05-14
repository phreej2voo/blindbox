<template>
  <el-main>
    <el-card shadow="never">
      <div shadow="never" style="border: none">
        <el-form :inline="true" :model="searchForm" class="demo-form-inline">
          <el-form-item label="门店名称">
            <el-input
              v-model="searchForm.name"
              placeholder="门店名称"
              clearable
            ></el-input>
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
      <div
        style="
          width: 100%;
          height: 0;
          border-bottom: #e4e7ed 1px dashed;
          margin-top: 15px;
        "
      ></div>

      <el-table :data="tableData" style="width: 100%; margin-top: 20px">
        <el-table-column prop="id" label="ID"> </el-table-column>
        <el-table-column prop="uuid" label="券码标识"> </el-table-column>
	    <el-table-column prop="name" label="门店名称"> </el-table-column>
	    <el-table-column prop="address" label="门店地址"> </el-table-column>
	    <el-table-column label="订单状态" width="100px">
		  <template #default="scope">
			  <el-tag v-if="scope.row.status == 1">已预约</el-tag>
			  <el-tag type="success" v-if="scope.row.status == 2">已核销</el-tag>
			  <el-tag type="danger" v-if="scope.row.status == 3">已取消</el-tag>
		  </template>
	    </el-table-column>
        <el-table-column prop="reserve_date" label="预约时间"> </el-table-column>
        <el-table-column prop="create_time" label="创建时间"> </el-table-column>
        <el-table-column prop="update_time" label="更新时间"> </el-table-column>
        <el-table-column label="操作">
          <template #default="scope">
            <el-button v-if="scope.row.status != 3" @click="handleEdit(scope.row)" type="text" size="small"
              >编辑</el-button
            >
            <el-button v-if="scope.row.status == 1" @click="handleCheck(scope.row)" type="text" size="small"
              >核销</el-button
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

	  <el-dialog
		  :title="title"
		  :append-to-body="true"
		  :close-on-click-modal="false"
		  v-model="dialogVisible"
		  width="30%"
	  >
		  <el-form
			  :model="form"
			  label-width="80px"
			  :rules="rules"
			  ref="ruleForm"
		  >
			  <el-form-item label="券码标识" prop="uuid">
				  <el-input v-model="form.uuid"></el-input>
			  </el-form-item>
			  <el-form-item label="门店名称" prop="name">
				  <el-input v-model="form.name"></el-input>
			  </el-form-item>
			  <el-form-item label="门店地址" prop="address">
				  <el-input v-model="form.address"></el-input>
			  </el-form-item>
			  <el-form-item label="订单状态" prop="status">
				  <el-radio :label="1" v-model="form.status">已预约</el-radio>
				  <el-radio :label="2" v-model="form.status">已核销</el-radio>
				  <el-radio :label="3" v-model="form.status">已取消</el-radio>
			  </el-form-item>
		  </el-form>
		  <span class="dialog-footer">
				<el-button @click="dialogVisible = false">取 消</el-button>
				<el-button type="primary" @click="submitForm('ruleForm')"
				>确 定</el-button
				>
			</span>
	  </el-dialog>
  </el-main>
</template>

<script>
export default {
  name: "storeOrder",
  components: {

  },
  data() {
    return {
      tableData: [],
	  dialogVisible: false,
	  title: "",
      searchForm: {
        name: "",
        page: 1,
        limit: 15,
      },
      page: {
        total: 1,
      },
	  form: {
		id: 0,
		uuid: "",
		name: "",
		address: "",
		status: 1,
	  },
	  rules: {
	 	name: [{ required: true, message: "请输入门店名称" }],
	  	address: [{ required: true, message: "请选择门店地址" }],
	    status: [{ required: true, message: "请选择订单状态" }],
      }
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
    // 编辑
    handleEdit(row) {
		this.title = "编辑线下预约订单";
		this.form.id = row.id;
		this.form.uuid = row.uuid;
		this.form.name = row.name;
		this.form.address = row.address;
		this.form.status = row.status;
		this.dialogVisible = true;
	},
	// 提价编辑表单
    async submitForm(formName) {
		  this.$refs[formName].validate(async (valid) => {
			  if (valid) {
				  let res = await this.$API.storeOrder.edit.post(this.form);

				  if (res.code == 0) {
					  this.$message.success(res.msg);
					  this.dialogVisible = false;
				  } else {
					  this.$message.error(res.msg);
				  }
			  }
		  });
	},
    // 核销
	  handleCheck(row) {
      this.$confirm("是否确定核销?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(async () => {
          let res = await this.$API.storeOrder.check.get({ id: row.id });
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
      let res = await this.$API.storeOrder.list.get(this.searchForm);
      this.tableData = res.data.rows;
      this.page.total = res.data.total;
    },
    handleSuccess() {
      this.getList();
    },
    pageChangeHandle(page) {
      this.searchForm.page = page;
      this.getList();
    },
  },
};
</script>
