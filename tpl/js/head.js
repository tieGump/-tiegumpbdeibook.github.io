function clean(){
    var aa = document.getElementsByTagName("input")
    for(var i=0;i<aa.length;i++){
        if (aa[i].value=="请输入需要查找的关键字。") {
            aa[i].value="";

        }
    }
}
//获取下拉列表选中项的值
function getSelectedValue(name){
    var obj=document.getElementById(name);
    return obj.value;      //如此简单，直接用其对象的value属性便可获取到
}
//获取查找关键字的值
function getSelectedtxt(name){
    var obj=document.getElementById(name);
    return obj.value;      //如此简单，直接用其对象的value属性便可获取到
}
function Searchopen(){
    var aa = document.getElementsByTagName("input")
    for(var i=0;i<aa.length;i++){
        if (aa[i].value!="请输入需要查找的关键字。") {
            var tmp=getSelectedtxt('textfield');
            tmp=tmp?"/keyword/"+tmp:'';
            bookurl="/book/search"+tmp+"/findtype/"+getSelectedValue('select');
            window.top.location.href=bookurl;
        }
    }
}
var tx = (window.screen.height-380)/2;
var ty = (window.screen.width-680)/2;
function ht(){
    window.open ('/admin','DLwindow', 'height=380, width=680, top='+tx+',left='+ty+', toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no')
}
