export default {
	/**
	 * 获取分享海报
	 * @param array imgList 海报素材
	 * @param string qrcode 二维码
	 * @param function successFn 回调函数
	 */
	poster2canvas: function({imgList, qrcode}, successFn) {
		// uni.showLoading({
		// 	title: `海报生成中`,
		// 	mask: true
		// });
		const ctx = uni.createCanvasContext('myCanvas');
		ctx.clearRect(0, 0, 0, 0);
		// 创建海报背景
		ctx.fillStyle = ctx.createPattern(imgList[0], 'no-repeat');
		ctx.fillRect(0, 0, 316, 554);
		ctx.setFillStyle('#1D1F36');
		ctx.draw();
				
		/**
		 * 只能获取合法域名下的图片信息,本地调试无法获取
		 * 获取图片信息(二维码) 需先下载才可获取
		 */

		// #ifndef MP
		uni.getImageInfo({
			src: imgList[0],
			success: (info) => {
				ctx.drawImage(imgList[0], 0, 0, info.width, info.height);
				ctx.draw();
				getQrcodeImg(qrcode, ctx, successFn)
			},
			fail: function(err) {
				console.log('err-', err);
				uni.hideLoading();
				uni.showToast({
					title: `无法获取图片信息`
				})
			}
		})
		// #endif

		// #ifdef MP
		getQrcodeImg(qrcode, ctx, successFn)
		// #endif
	},
	pathToBase64: function (path) {
	    return new Promise(function(resolve, reject) {
	        if (typeof window === 'object' && 'document' in window) {
	            if (typeof FileReader === 'function') {
	                var xhr = new XMLHttpRequest()
	                xhr.open('GET', path, true)
	                xhr.responseType = 'blob'
	                xhr.onload = function() {
	                    if (this.status === 200) {
	                        let fileReader = new FileReader()
	                        fileReader.onload = function(e) {
	                            resolve(e.target.result)
	                        }
	                        fileReader.onerror = reject
	                        fileReader.readAsDataURL(this.response)
	                    }
	                }
	                xhr.onerror = reject
	                xhr.send()
	                return
	            }
	            var canvas = document.createElement('canvas')
	            var c2x = canvas.getContext('2d')
	            var img = new Image
	            img.onload = function() {
	                canvas.width = img.width
	                canvas.height = img.height
	                c2x.drawImage(img, 0, 0)
	                resolve(canvas.toDataURL())
	                canvas.height = canvas.width = 0
	            }
	            img.onerror = reject
	            img.src = path
	            return
	        }
	        if (typeof plus === 'object') {
	            plus.io.resolveLocalFileSystemURL(getLocalFilePath(path), function(entry) {
	                entry.file(function(file) {
	                    var fileReader = new plus.io.FileReader()
	                    fileReader.onload = function(data) {
	                        resolve(data.target.result)
	                    }
	                    fileReader.onerror = function(error) {
	                        reject(error)
	                    }
	                    fileReader.readAsDataURL(file)
	                }, function(error) {
	                    reject(error)
	                })
	            }, function(error) {
	                reject(error)
	            })
	            return
	        }
	        if (typeof wx === 'object' && wx.canIUse('getFileSystemManager')) {
	            wx.getFileSystemManager().readFile({
	                filePath: path,
	                encoding: 'base64',
	                success: function(res) {
	                    resolve('data:image/png;base64,' + res.data)
	                },
	                fail: function(error) {
	                    reject(error)
	                }
	            })
	            return
	        }
	        reject(new Error('not support'))
	    })
	},
	/**
	 * 生成海报获取文字
	 * @param string text 为传入的文本
	 * @param int num 为单行显示的字节长度
	 * @return array 
	 */
	textByteLength: function(text, num) {
		let strLength = 0;
		let rows = 1;
		let str = 0;
		let arr = [];
		for (let j = 0; j < text.length; j++) {
			if (text.charCodeAt(j) > 255) {
				strLength += 2;
				if (strLength > rows * num) {
					strLength++;
					arr.push(text.slice(str, j));
					str = j;
					rows++;
				}
			} else {
				strLength++;
				if (strLength > rows * num) {
					arr.push(text.slice(str, j));
					str = j;
					rows++;
				}
			}
		}
		arr.push(text.slice(str, text.length));
		return [strLength, arr, rows] //  [处理文字的总字节长度，每行显示内容的数组，行数]
	}
}

const getQrcodeImg = function(qrcode, ctx, successFn){
	uni.getImageInfo({
		src: qrcode,
		success: function(res) {
			const WIDTH = res.width / 2;
			const HEIGHT = res.height / 2;
			const qrx = (316 - WIDTH) / 2;
			const qry = 274;
			// 绘制图像到画布
			ctx.drawImage(qrcode, qrx, qry, WIDTH, HEIGHT);
			ctx.save();
	
			setTimeout(() => {
				ctx.draw(true, () => {
					// 把当前画布指定区域的内容导出生成指定大小的图片，并返回文件路径
					uni.canvasToTempFilePath({
						canvasId: 'myCanvas',
						fileType: 'png',
						destWidth: 316,
						destHeight: 554,
						success: function(res) {
							console.log('success-', res);
							uni.hideLoading();
							successFn && successFn(res.tempFilePath);
						},
						fail: function(res){
							console.log('fail-', res);
							uni.hideLoading();
						}
					})
				});
			}, 100)
		},
		fail: function(err) {
			console.log('err-', err);
			uni.hideLoading();
			uni.showToast({
				title: `无法获取图片信息`
			})
		}
	})
}