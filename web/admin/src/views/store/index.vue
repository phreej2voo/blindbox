<template>
	<el-main>
		<el-card shadow="never">
			<el-tabs v-model="activeName">
				<el-tab-pane label="默认开启引擎" name="first">
					<el-form ref="form" :model="baseForm" label-width="80px">
						<el-form-item label="默认引擎">
							<el-radio label="local" v-model="baseForm.store_way"
								>本地存储</el-radio
							>
							<el-radio
								label="aliyun"
								v-model="baseForm.store_way"
								>阿里云OSS</el-radio
							>
							<el-radio
								label="qcloud"
								v-model="baseForm.store_way"
								>腾讯云COS</el-radio
							>
							<el-radio label="qiniu" v-model="baseForm.store_way"
								>七牛云KODO</el-radio
							>
						</el-form-item>
						<el-form-item style="margin-top: 50px">
							<el-button
								type="primary"
								@click="baseSubmit"
								style="width: 200px"
								>保存</el-button
							>
						</el-form-item>
					</el-form>
				</el-tab-pane>
				<el-tab-pane label="阿里云OSS" name="second">
					<el-form
						ref="form"
						:model="aliform"
						:label-position="labelPosition"
						label-width="160px"
					>
						<el-form-item
							label="空间域名 Domain"
							style="width: 700px"
						>
							<el-input v-model="aliform.oss_domain"></el-input>
						</el-form-item>
						<el-form-item label="AccessKey ID" style="width: 700px">
							<el-input v-model="aliform.accesskey_id"></el-input>
						</el-form-item>
						<el-form-item
							label="AccessKey Secret"
							style="width: 700px"
						>
							<el-input
								v-model="aliform.accesskey_secret"
							></el-input>
						</el-form-item>
						<el-form-item label="Bucket" style="width: 700px">
							<el-input v-model="aliform.bucket"></el-input>
						</el-form-item>
						<el-form-item label="Endpoint" style="width: 700px">
							<el-input v-model="aliform.endpoint"></el-input>
						</el-form-item>
						<el-form-item style="margin-top: 50px">
							<el-button
								type="primary"
								@click="aliSubmit"
								style="width: 200px"
								>保存</el-button
							>
						</el-form-item>
					</el-form>
				</el-tab-pane>
				<el-tab-pane label="腾讯云COS" name="third">
					<el-form
						ref="form"
						:model="qcloudform"
						:label-position="labelPosition"
						label-width="160px"
					>
						<el-form-item label="腾讯云APPID" style="width: 700px">
							<el-input
								v-model="qcloudform.tencent_appid"
							></el-input>
						</el-form-item>
						<el-form-item
							label="空间域名 Domain"
							style="width: 700px"
						>
							<el-input
								v-model="qcloudform.tencent_domain"
							></el-input>
						</el-form-item>
						<el-form-item label="SecretId" style="width: 700px">
							<el-input v-model="qcloudform.secret_id"></el-input>
						</el-form-item>
						<el-form-item label="SecretKey" style="width: 700px">
							<el-input
								v-model="qcloudform.secret_key"
							></el-input>
						</el-form-item>
						<el-form-item label="存储桶名称" style="width: 700px">
							<el-input
								v-model="qcloudform.tencent_bucket"
							></el-input>
						</el-form-item>
						<el-form-item label="所属地域" style="width: 700px">
							<el-input
								v-model="qcloudform.tencent_endpoint"
							></el-input>
						</el-form-item>
						<el-form-item style="margin-top: 50px">
							<el-button
								type="primary"
								@click="qcloudSubmit"
								style="width: 200px"
								>保存</el-button
							>
						</el-form-item>
					</el-form>
				</el-tab-pane>
				<el-tab-pane label="七牛云KODO" name="fourth">
					<el-form
						ref="form"
						:model="qiniuform"
						:label-position="labelPosition"
						label-width="160px"
					>
						<el-form-item
							label="空间域名 Domain"
							style="width: 700px"
						>
							<el-input
								v-model="qiniuform.qiniu_domain"
							></el-input>
						</el-form-item>
						<el-form-item label="accessKey" style="width: 700px">
							<el-input v-model="qiniuform.accesskey"></el-input>
						</el-form-item>
						<el-form-item label="secretKey" style="width: 700px">
							<el-input v-model="qiniuform.secretkey"></el-input>
						</el-form-item>
						<el-form-item label="空间名称" style="width: 700px">
							<el-input
								v-model="qiniuform.qiniu_bucket"
							></el-input>
						</el-form-item>
						<el-form-item label="存储区域" style="width: 700px">
							<el-input
								v-model="qiniuform.qiniu_endpoint"
							></el-input>
						</el-form-item>
						<el-form-item style="margin-top: 50px">
							<el-button
								type="primary"
								@click="qiniuSubmit"
								style="width: 200px"
								>保存</el-button
							>
						</el-form-item>
					</el-form>
				</el-tab-pane>
			</el-tabs>
		</el-card>
	</el-main>
</template>

<script>
export default {
	data() {
		return {
			labelPosition: "left",
			activeName: "first",
			baseForm: {
				store_way: "local",
			},
			aliform: {
				oss_domain: "",
				accesskey_id: "",
				accesskey_secret: "",
				bucket: "",
				endpoint: "",
			},
			qcloudform: {
				tencent_appid: "",
				tencent_domain: "",
				secret_id: "",
				secret_key: "",
				tencent_bucket: "",
				tencent_endpoint: "",
			},
			qiniuform: {
				qiniu_domain: "",
				accesskey: "",
				secretkey: "",
				qiniu_bucket: "",
				qiniu_endpoint: "",
			},
			form: {},
		};
	},
	mounted() {
		this.getBaseConf();
	},
	methods: {
		baseSubmit() {
			this.form = this.baseForm;
			this.doSubmit();
		},
		aliSubmit() {
			this.form = this.aliform;
			this.doSubmit();
		},
		qcloudSubmit() {
			this.form = this.qcloudform;
			this.doSubmit();
		},
		qiniuSubmit() {
			this.form = this.qiniuform;
			this.doSubmit();
		},
		async getBaseConf() {
			let res = await this.$API.system.store.get();
			this.baseForm = res.data.store;
			this.aliform = res.data.aliyun;
			this.qcloudform = res.data.qcloud;
			this.qiniuform = res.data.qiniu;
		},
		async doSubmit() {
			let res = await this.$API.system.storeSave.post(this.form);
			if (res.code == 0) {
				this.$message.success(res.msg);
			} else {
				this.$message.error(res.msg);
			}
		},
	},
};
</script>
