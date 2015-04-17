<?php
?>
<body>
<ul>
    <li>aaaaaaaaaaa</li>
    <li>bbbbbbbbbbb</li>
    <li>cccccccccccccc</li>
    <li>bbbbbbbbbbb</li>
</ul>
</body>
<script type="text/javascript">
    window.onload=function(){
        var oLis=document.getElementsByTagName('li');
        for(var i=0;i<oLis.length;i++){
            (function(i){
                oLis[i].onclick=function(){
                    alert(i);//弹出来的总是4，为什么呢？
                };
            })(i);

        }

    };
</script>