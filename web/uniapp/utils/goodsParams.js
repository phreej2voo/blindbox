let payParams = {}; // 支付参数
let isPlayBack = false;

const setPayParams = (params) => {
    payParams = {...params};
}
const getPayParams = () => {
    return payParams;
}
const getIsPlayBack = () => {
    return isPlayBack;
}
const setIsPlayBack = (flag) => {
    isPlayBack = flag;
}

export default {  
    setPayParams,
    getPayParams,
    getIsPlayBack,
    setIsPlayBack
}