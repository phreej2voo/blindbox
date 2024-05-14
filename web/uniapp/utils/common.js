// 时间补全0
const checkTime = (i) => {
    if(i < 10) {
        i = '0' + i;
    }
    return i;
}
const countDown = () => {
    const startTime = new Date(); // 当前时间
    const endTime = new Date(new Date().setHours(23, 59, 59, 999)); // 设置今天最后时间
    const leftTime = endTime - startTime; // 计算剩余毫秒数
    let hours = parseInt(leftTime / 1000 / 60 / 60 % 24, 10); // 计算剩余小时数
    let minutes = parseInt(leftTime / 1000 / 60 % 60, 10); // 计算剩分钟数
    let seconds = parseInt(leftTime / 1000 % 60, 10); // 计算剩余秒数
    
    hours = checkTime(hours).toString();
    minutes = checkTime(minutes).toString();
    seconds = checkTime(seconds).toString();
    const countTime = hours + ' : ' + minutes + ' : ' + seconds;
    return countTime;
}
const countDownHous = () => {
    const startTime = new Date(); // 当前时间
    const endTime = new Date(new Date().setHours(23, 59, 59, 999)); // 设置今天最后时间
    const leftTime = endTime - startTime; // 计算剩余毫秒数
    let hours = parseInt(leftTime / 1000 / 60 / 60 % 24, 10); // 计算剩余小时数
    hours = checkTime(hours).toString();
    return hours;
}
const countDownMiuntes = () => {
    const startTime = new Date(); // 当前时间
    const endTime = new Date(new Date().setHours(23, 59, 59, 999)); // 设置今天最后时间
    const leftTime = endTime - startTime; // 计算剩余毫秒数
    let minutes = parseInt(leftTime / 1000 / 60 % 60, 10); // 计算剩分钟数
    minutes = checkTime(minutes).toString();  
    return minutes;
}
const countDownSeconds = () => {
    const startTime = new Date(); // 当前时间
    const endTime = new Date(new Date().setHours(23, 59, 59, 999)); // 设置今天最后时间
    const leftTime = endTime - startTime; // 计算剩余毫秒数
    let seconds = parseInt(leftTime / 1000 % 60, 10); // 计算剩余秒数
    seconds = checkTime(seconds).toString();
    return seconds;
}
export {
    countDown,
    countDownHous,
    countDownMiuntes,
    countDownSeconds
}