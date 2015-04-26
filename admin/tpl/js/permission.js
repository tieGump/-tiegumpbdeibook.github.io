
var createNbsp=function (number){
    var re='';
    for(var i=0;i<number;i++){
        re+="&nbsp;";
    }
    return re;
}
var createRoot=function (){
    var permission_list=$('#permission_list');
    for(var i= 0,j=treeArr.length;i<j;i++){
        var option=$('<option>').text(createNbsp(treeArr[i].deep)+treeArr[i].value).val(treeArr[i].id);
        permission_list.append(option);
    }
};