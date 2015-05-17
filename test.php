<?php
$a=123;
$ss=array();
//echo >>$a;
$base=ord('n');
for($i=0,$j=ord('a');$j<ord('n');$i++,$j++){
    echo chr($j),'=='.chr($base+$i),'<br />';
}
$base=ord('0');
for($i=0;$j<=ord('z');$i++,$j++){
    echo chr($j),'=='.chr($base+$i),'<br />';
}
echo chr($j);
echo ord('1'),'=='.ord('@'),'<br />';
echo ord('2'),'=='.ord('A'),'<br />';
echo ord('3'),'=='.ord('B'),'<br />';
echo ord('4'),'=='.ord('C'),'<br />';
echo ord('9'),'=='.ord('H'),'<br />';
$base=ord('?');
for($i=0,$j=ord('0');$j<=ord('9');$i++,$j++){
    echo chr($j),'=='.chr($base+$i),'<br />';
}
$base=ord('N');

for($i=0,$j=ord('A');$j<ord('N');$i++,$j++){
    echo chr($j),'=='.chr($base+$i),'<br />';
}
$base=ord('f');
for($i=0;$j<=ord('Z');$i++,$j++){
    echo chr($j),'=='.chr($base+$i),'<br />';
}
echo ord('R'),'=='.ord('j'),'<br />';
echo ord('W'),'=='.ord('W'),'<br />';
echo ord('N'),'=='.ord('f'),'<br />';
echo ord('p'),'=='.ord('2'),'<br />';
echo ord('d'),'=='.ord('q'),'<br />';
echo ord('f'),'=='.ord('s'),'<br />';

exit;
include 'src/ascii.class.php';
include 'src/md2.class.php';
$m=new md2;
$a = $m->md2Add('欢迎ni');
print_r($a);
//echo "aaa";
$a=$m->md2Cut('27396;36784;80;75','397543');
//echo $a;
print_r($a);
//echo $a;
exit;
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
                    alert(i);//������������4��Ϊʲô�أ�
                };
            })(i);

        }

    };
</script>