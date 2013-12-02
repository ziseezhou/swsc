var localDowNames = [ '日', '一', '二', '三', '四', '五', '六' ];
var localMonthNames = [ '1月', '2月', '3月', '4月', '5月', '6月', '6月', '8月', '9月', '10月', '11月', '12月' ];

function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test($email);
}