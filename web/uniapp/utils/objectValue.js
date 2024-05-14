import baseUrl from '../utils/siteInfo';

export const DailyPopupImg = {
    content: baseUrl.imgroot + '/static/images/daily-popup.png',
    right: baseUrl.imgroot + '/static/images/rules.png',
    time: baseUrl.imgroot + '/static/images/time.png',
    consume: baseUrl.imgroot + '/static/images/has-consume.png',
    noConsume: baseUrl.imgroot + '/static/images/no-consume.png',
    rulesBack: baseUrl.imgroot + '/static/images/rules-back.png'
}

export const CouponsObj = {
    1: '优惠券',
    2: '重抽卡',
    3: '惊喜盲盒'
}

// 卡券类型type
export const CouponsImgObj = {
    coupon: baseUrl.imgroot + '/static/images/coupons/coupons1.png', // 折扣券
    prop: baseUrl.imgroot + '/static/images/coupons/coupons2.png', // 重抽卡(无详情)
    balance: baseUrl.imgroot + '/static/images/coupons/balance.png', // 余额(无详情)
    blindbox: baseUrl.imgroot + '/static/images/coupons/coupons3.png' // 盲盒
}

// 卡券状态文本  1:未解锁 2:去领取 3:去使用(已领取) 4:已使用 5:已过期
export const CouponsStatus = {
    1: '未解锁',
    2: '去领取',
    3: '',
    4: '',
    5: ''
}
// 卡券状态背景图
export const CouponsStatusImg = {
    1: baseUrl.imgroot + '/static/images/coupons/lock-back.png',
    2: baseUrl.imgroot + '/static/images/coupons/unlock-back.png',
    3: baseUrl.imgroot + '/static/images/coupons/has-get.png',
    4: baseUrl.imgroot + '/static/images/coupons/has-get.png',
    5: baseUrl.imgroot + '/static/images/coupons/has-get.png'
}

// 优惠券状态status
export const CouponsStatusObj = {
    // 未使用(可用)
    1: {
        1: baseUrl.imgroot + '/static/images/coupons/can-use-coupon.png', // 满减券
        2: baseUrl.imgroot + '/static/images/coupons/can-use-discount.png', // 折扣券
    },
    // 已使用
    2: baseUrl.imgroot + '/static/images/coupons/has-use-icon.png', 
    // 已过期
    3: baseUrl.imgroot + '/static/images/coupons/expired-icon.png', // 过期
    hasBack: baseUrl.imgroot + '/static/images/coupons/has-use-back.png' // 已用/过期背景
}

export const SpendAmountProgressImg = {
    0: baseUrl.imgroot + '/static/images/198.png',
    1: baseUrl.imgroot + '/static/images/298.png',
    2: baseUrl.imgroot + '/static/images/598.png'
}

export const CouponsType = {
    1: '满减券',
    2: '折扣券'
}

export const StarPng = baseUrl.imgroot + '/static/images/star.png';

export const UserLevelRights = {
    coupon: baseUrl.imgroot + '/static/images/blind-coupons-icon.png',
    prop: baseUrl.imgroot + '/static/images/redraw-icon.png',
    blindbox: baseUrl.imgroot + '/static/images/surprise-icon.png'
}

export const ShareImg = {
    back: baseUrl.imgroot + '/static/images/invitations/share-back.png',
    wechat: baseUrl.imgroot + '/static/images/invitations/share-wechat.png',
    friend: baseUrl.imgroot + '/static/images/invitations/share-friend.png',
    posters: baseUrl.imgroot + '/static/images/invitations/share-posters.png',
    left: baseUrl.imgroot + '/static/images/invitations/share-left.png',
    right: baseUrl.imgroot + '/static/images/invitations/share-right.png',
    invitationsBack: baseUrl.imgroot + '/static/images/invitations/invitations-back.png',
    invitationsTitle: baseUrl.imgroot + '/static/images/invitations/invitations-title.png',
    btnBack: baseUrl.imgroot + '/static/images/invitations/invite-btn.png',
    rewardBack: baseUrl.imgroot + '/static/images/invitations/reward-back.png',
    rewardTitleBack: baseUrl.imgroot + '/static/images/invitations/reward-title-back.png',
    rewardTitleBack1: baseUrl.imgroot + '/static/images/invitations/reward-title-back1.png',
    rewardTitleBack2: baseUrl.imgroot + '/static/images/invitations/reward-title-back2.png',
    rewardTitleBack3: baseUrl.imgroot + '/static/images/invitations/reward-title-back3.png',
    arrow: baseUrl.imgroot + '/static/images/invitations/arrow.png',
    share1: baseUrl.imgroot + '/static/images/invitations/share1.png',
    share2: baseUrl.imgroot + '/static/images/invitations/share2.png',
    share3: baseUrl.imgroot + '/static/images/invitations/share3.png',
}

export const SharePostersImg = {
    back: baseUrl.imgroot + '/static/images/invitations/posters-back.png',
    wechat: baseUrl.imgroot + '/static/images/invitations/share-wechat.png',
}

export const RedrawImg = baseUrl.imgroot + '/static/images/goods/redraw-back.png'
export const RedrawPopupImg = baseUrl.imgroot + '/static/images/goods/redraw-back-popup.png'

export const GoodsShareBack = baseUrl.imgroot + '/static/images/goods/goods-share-back.png'
export const GoodsShareClose= baseUrl.imgroot + '/static/images/goods/share-close.png'

export const GoodsPoolImg = {
    back: baseUrl.imgroot + '/static/images/goods/lottery-back.png',
    goodsBack: baseUrl.imgroot + '/static/images/goods/goods-pool-back.png',
	goodsBg: baseUrl.imgroot + '/static/images/goods/goods-bg.png',
    rewardBg: baseUrl.imgroot + '/static/images/goods/reward-bg.png',
    lotteryBack: baseUrl.imgroot + '/static/images/goods/lottery-back2.png',
    poolBack: baseUrl.imgroot + '/static/images/goods/goods-pool-back.png',
}

export const CenterImage = {
    free: baseUrl.imgroot + '/static/images/center/free-center.png',
    prop: baseUrl.imgroot + '/static/images/center/prop-center.png',
    back: baseUrl.imgroot + '/static/images/center/center-back.png',
}

export const CenterChange = {
    left: baseUrl.imgroot + '/static/images/center/level-left.png',
    right: baseUrl.imgroot + '/static/images/center/level-right.png',
}
export const NoDataImg = baseUrl.imgroot + '/static/images/no-data.png'

export const GoodsRightMenu = {
    fair: baseUrl.imgroot + '/static/images/goods/fair-open.png',
    share: baseUrl.imgroot + '/static/images/goods/share.png',
    free: baseUrl.imgroot + '/static/images/goods/free-order.png',
    freePlay: baseUrl.imgroot + '/static/images/goods/free-play.png',
}

export const SelectImg = baseUrl.imgroot + '/static/images/goods/select.png'

export const PoolsImg = {
    1: baseUrl.imgroot + '/static/images/goods/normal.png', // 普通
    2: baseUrl.imgroot + '/static/images/goods/legend.png', // 传说
    3: baseUrl.imgroot + '/static/images/goods/epic.png', // 史诗
    4: baseUrl.imgroot + '/static/images/goods/rare.png' // 稀有
}

export const PoolsImgChinese = {
    '普通款': baseUrl.imgroot + '/static/images/goods/normal.png', // 普通
    '传说款': baseUrl.imgroot + '/static/images/goods/legend.png', // 传说
    '史诗款': baseUrl.imgroot + '/static/images/goods/epic.png', // 史诗
    '稀有款': baseUrl.imgroot + '/static/images/goods/rare.png' // 稀有
}

export const PoolsResultBack = {
    back: baseUrl.imgroot + '/static/images/goods/result-back.png',
    title: baseUrl.imgroot + '/static/images/goods/result-title.png'
}

export const PoolsResultFont = {
    '普通款': baseUrl.imgroot + '/static/images/goods/normal-icon.png',
    '传说款': baseUrl.imgroot + '/static/images/goods/legend-icon.png',
    '史诗款': baseUrl.imgroot + '/static/images/goods/epic-icon.png',
    '稀有款': baseUrl.imgroot + '/static/images/goods/rare-icon.png',
    1: baseUrl.imgroot + '/static/images/goods/normal-icon.png',
    2: baseUrl.imgroot + '/static/images/goods/legend-icon.png',
    3: baseUrl.imgroot + '/static/images/goods/epic-icon.png',
    4: baseUrl.imgroot + '/static/images/goods/rare-icon.png'
}

export const HonorListImg = {
    one: baseUrl.imgroot + '/static/images/home/one.png',
    two: baseUrl.imgroot + '/static/images/home/two.png',
    three: baseUrl.imgroot + '/static/images/home/three.png',
    dailyBack: baseUrl.imgroot + '/static/images/home/daily-back.png',
    weekBack: baseUrl.imgroot + '/static/images/home/week-back.png',
    newerBack: baseUrl.imgroot + '/static/images/home/newer-back.png',
    buyOneBack: baseUrl.imgroot + '/static/images/home/buy-one-back.png',
}

export const RankAvatar = {
    0: baseUrl.imgroot + '/static/images/home/one-rank.png',
    1: baseUrl.imgroot + '/static/images/home/two-rank.png',
    2: baseUrl.imgroot + '/static/images/home/three-rank.png',
}

export const FreePlaySeries = {
    back: baseUrl.imgroot + '/static/images/home/free-play-back.png',
    title: baseUrl.imgroot + '/static/images/home/free-play-title.png',
    img: baseUrl.imgroot + '/static/images/home/free-play-img.png',
    options: baseUrl.imgroot + '/static/images/home/free-play-options.png',
}

export const FreeGif = {
    '1': baseUrl.imgroot + '/static/images/gif/odd.gif', // 奇数
    '2': baseUrl.imgroot + '/static/images/gif/even.gif' // 偶数
}

export const MinGuarantee = {
    icon: baseUrl.imgroot + '/static/images/goods/min-gur-icon.png',
    back: baseUrl.imgroot + '/static/images/goods/guarant_back.png',
}

export const GitLog = baseUrl.imgroot + '/static/images/goods/git-log.png'

export const WeeklyTitle = {
    1: '周一限定',
    2: '周二限定',
    3: '周三限定',
    4: '周四限定',
    5: '周五限定',
    6: '周六限定',
    7: '周日限定'
}
export const WeeklyTitle2 = {
    1: '周一',
    2: '周二',
    3: '周三',
    4: '周四',
    5: '周五',
    6: '周六',
    7: '周日'
}
// 第一行颜色
export const WeeklyTitleColor = {
    1: '#1e81e8', // #429eff
    2: '#9a3c7f',
    3: '#007ec6',
    4: '#836353', // #f98d46
    5: '#15809a', // #177489
    6: '#835c00', // #93701f
    7: '#713B72', // #713B72
}
// 第二行颜色
export const WeeklyDescColor = {
    1: '#429eff',
    2: '#9a3c7f',
    3: '#007ec6',
    4: '#f98d46', 
    5: '#177489',
    6: '#93701f',
    7: '#713B72',
}
export const WeeklyBg = {
    1: baseUrl.imgroot + '/static/images/weekly/monday_back.png',
    2: baseUrl.imgroot + '/static/images/weekly/tuesday_back.png',
    3: baseUrl.imgroot + '/static/images/weekly/wednesday_back.png',
    4: baseUrl.imgroot + '/static/images/weekly/thursday_back.png',
    5: baseUrl.imgroot + '/static/images/weekly/friday_back.png',
    6: baseUrl.imgroot + '/static/images/weekly/saturday_back.png',
    7: baseUrl.imgroot + '/static/images/weekly/sunday_back.png'
}

export const WeeklyBtn = {
    1: baseUrl.imgroot + '/static/images/weekly/monday_btn.png',
    2: baseUrl.imgroot + '/static/images/weekly/tuesday_btn.png',
    3: baseUrl.imgroot + '/static/images/weekly/wednesday_btn.png',
    4: baseUrl.imgroot + '/static/images/weekly/thursday_btn.png',
    5: baseUrl.imgroot + '/static/images/weekly/friday_btn.png',
    6: baseUrl.imgroot + '/static/images/weekly/saturday_btn.png',
    7: baseUrl.imgroot + '/static/images/weekly/sunday_btn.png'
}
// 公共图片
export const WeeklyCommonImg = {
    weeklyIcon: baseUrl.imgroot + '/static/images/weekly/weekly_icon.png',
    weeklyIcon2: baseUrl.imgroot + '/static/images/weekly/weekly_icon2.png',
    weekly_bg: baseUrl.imgroot + '/static/images/weekly/weekly_bg.png',
    common_back: baseUrl.imgroot + '/static/images/weekly/common_back.png',
    weekly_detail_bottom: baseUrl.imgroot + '/static/images/weekly/weekly_detail_bottom.png',
}
export const WeeklyTitleBg = {
    1: baseUrl.imgroot + '/static/images/weekly/mon_title_bg.png',
    2: baseUrl.imgroot + '/static/images/weekly/tues_title_bg.png',
    3: baseUrl.imgroot + '/static/images/weekly/wed_title_bg.png',
    4: baseUrl.imgroot + '/static/images/weekly/thurs_title_bg.png',
    5: baseUrl.imgroot + '/static/images/weekly/fri_title_bg.png',
    6: baseUrl.imgroot + '/static/images/weekly/sat_title_bg.png',
    7: baseUrl.imgroot + '/static/images/weekly/sun_title_bg.png'
}
export const WeeklyDetailBack = {
    1: baseUrl.imgroot + '/static/images/weekly/mon_back1.png',
    2: baseUrl.imgroot + '/static/images/weekly/tues_back1.png',
    3: baseUrl.imgroot + '/static/images/weekly/wed_back1.png',
    4: baseUrl.imgroot + '/static/images/weekly/thurs_back1.png',
    5: baseUrl.imgroot + '/static/images/weekly/fri_back1.png',
    6: baseUrl.imgroot + '/static/images/weekly/sat_back1.png',
    7: baseUrl.imgroot + '/static/images/weekly/sun_back1.png'
}
export const WeeklyIcon = {
    1: baseUrl.imgroot + '/static/images/weekly/mon_icon.png',
    2: baseUrl.imgroot + '/static/images/weekly/tues_icon.png',
    3: baseUrl.imgroot + '/static/images/weekly/wed_icon.png',
    4: baseUrl.imgroot + '/static/images/weekly/thurs_icon.png',
    5: baseUrl.imgroot + '/static/images/weekly/fri_icon.png',
    6: baseUrl.imgroot + '/static/images/weekly/sat_icon.png',
    7: baseUrl.imgroot + '/static/images/weekly/sun_icon.png'
}
export const WeeklyOrderSureImg = {
    1: baseUrl.imgroot + '/static/images/weekly/mon_sure.png',
    2: baseUrl.imgroot + '/static/images/weekly/tues_sure.png',
    3: baseUrl.imgroot + '/static/images/weekly/wed_sure.png',
    4: baseUrl.imgroot + '/static/images/weekly/thurs_sure.png',
    5: baseUrl.imgroot + '/static/images/weekly/fri_sure.png',
    6: baseUrl.imgroot + '/static/images/weekly/sat_sure.png',
    7: baseUrl.imgroot + '/static/images/weekly/sun_sure.png'
}
export const WeeklyPayBtn = {
    1: baseUrl.imgroot + '/static/images/weekly/mon_pay_btn.png',
    2: baseUrl.imgroot + '/static/images/weekly/tues_pay_btn.png',
    3: baseUrl.imgroot + '/static/images/weekly/mon_pay_btn.png',
    4: baseUrl.imgroot + '/static/images/weekly/tues_pay_btn.png',
    5: baseUrl.imgroot + '/static/images/weekly/fri_pay_btn.png',
    6: baseUrl.imgroot + '/static/images/weekly/sat_pay_btn.png',
    7: baseUrl.imgroot + '/static/images/weekly/fri_pay_btn.png'
}
export const BarrageImg = {
    bg: baseUrl.imgroot + '/static/images/weekly/barrage_bg.png'
}
