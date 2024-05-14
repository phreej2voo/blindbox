import baseUrl from './siteInfo';

const BthFontUrl = `url(${baseUrl.imgroot}static/fontFamily/BTH.ttf)`;

export default {
	data(){
		return {
			query : '',
		}
	},
	onLoad(query) {
		this.loadFontFaceFromHttp();
	},
	methods:{
		// 加载网络字体
		loadFontFaceFromHttp() {
			uni.loadFontFace({
				global: true,
				family: 'BTH',
				source: BthFontUrl,
				success() {
				},
				fail() {
				}
			});
		},
		// 从网络加载字体并缓存
		async loadFontFaceFromCache() {
			let tempFilePath = '';
			const savedFilePath = uni.getStorageSync('BTH');
			const [error, res] = await uni.getFileSystemManager();
			// getFileSystemManager
			console.log('saved-file-', error, res);
			if (!error) {
				const fileList = res.fileList;
				const file = fileList.find(fileItem => fileItem.filePath === savedFilePath);
				if (file) {
					tempFilePath = file.filePath;
				}
			}
			if (!tempFilePath) {
				const [error, res] = await uni.downloadFile({url});
				if (!error) {
					tempFilePath = res.tempFilePath;
					uni.saveFile({
						tempFilePath,
						success(res) {
							uni.setStorageSync({
								key: 'BTH',
								data: res.savedFilePath
							});
						}
					});
				} else {
					console.log('下载失败');
					return;
				}
			} else {
				console.log('使用缓存资源，跳过下载步骤');
			}
			uni.loadFontFace({
				family: 'BTH',
				// 本地字体路径需转换为平台绝对路径
				source: `url("${plus.io.convertLocalFileSystemURL(tempFilePath)}")`
			})
		},
		async h5LoadFontFace() {
			const BthFont = new window.FontFace('BTH', `url(${BthFontUrl})`);
			document.fonts.add(BthFont);
			await BthFont.load().then(info => {
				document.body.style.fontFamily = 'BTH';
			}).catch(err => {
			});
			const AbelFont = new window.FontFace('Abel', `url(${AbelFontUrl})`);
			document.fonts.add(AbelFont);
			await AbelFont.load().then(info => {
				document.body.style.fontFamily = 'Abel';
			}).catch(err => {
			});
			document.body.style.fontFamily = 'PingFang SC';
		}
	}
}