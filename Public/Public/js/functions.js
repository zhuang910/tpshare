/**
 * 通用表单ajax提交
 * @param  {string} url    表单提交地址
 * @param  {string} formObj    待提交的表单对象或ID
 * @author zhuangqianlin 2015-09-09
 */
function commonAjaxForm(fromUrl,formObj) {
    if(!formObj||formObj==''){
        var formObj="form";
    }
    if(!fromUrl||fromUrl==''){
        var fromUrl=document.URL;
    }
    jQuery(formObj).ajaxForm({
        url:fromUrl,
        type:"POST",
        dataType:"json",
        success:function(r,stat,xhr,form){
            if(r.status) {
                alert(r.info);
                if(r.url&&r.url!=''){
                setTimeout(function(){
                    window.location.href=r.url;
                },1000);
                }
            }else if(r.status == 0){
                alert(r.info);
            }
        }
    });
}

/**
 * 通用ajax删除
 * 
 * @param {type} value
 * @returns {Boolean}
 */
function ajaxDelete(fromUrl,data) {
    if(!fromUrl||fromUrl==''){
        var fromUrl=document.URL;
    }
    BUI.Message.Confirm('确认要删除吗？',function(r){
        setTimeout(function(){
            jQuery.ajax({
                dataType:"json",
                type:"POST",
                url:fromUrl,
                data:eval('(' + data + ')'),
                success:function(r,stat,xhr,form){
                    if(r.status) {
                        alert(r.info);
                        if(r.url&&r.url!=''){
                        setTimeout(function(){
                            window.location.href=r.url;
                        },1000);
                        }
                    }else if(r.status == 0){
                        alert(r.info);
                    }
                }
            });
        });
    });
}

/**
 * 检测字符串是否是电子邮件地址格式
 * @param  {string} value    待检测字符串
 */
function isEmail(value){
    var Reg =/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
    return Reg.test(value);
}