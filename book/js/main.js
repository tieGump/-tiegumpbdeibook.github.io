var book = {
    init: init,
    update: update,
    addfavor: addfavor,
    setIndex: setIndex
}


$(function () {
    book.init();
})


//初始化
function init() {
    this.update();

    $('#addCollect').click(function () {
        var url = window.location.href;
        var tit = $('title').eq(0).html();

        book.addfavor(url, tit);
    })

    $('#setIndex').click(function () {
        var url = window.location.href;
        book.setIndex(url);
    })

    $('#admLogin').click(function(){
        var a = $.dialog.open('login.html', {
            title:'用户登陆',
            fixed: true,
            lock: true,
            init:function(){
                var iframe = this.iframe.contentWindow;
                var btn = $(iframe.document.getElementById('login'));
                btn.click(function(){
                    a.close();
                })
            }
        });
    })
}

//加入收藏
function addfavor(url, title) {
    if (confirm("网站名称：" + title + "\n网址：" + url + "\n确定添加收藏?")) {
        var ua = navigator.userAgent.toLowerCase();
        if (ua.indexOf("msie 8") > -1) {
            external.AddToFavoritesBar(url, title, '');//IE8
        } else {
            try {
                window.external.addFavorite(url, title);
            } catch (e) {
                try {
                    window.sidebar.addPanel(title, url, "");//firefox
                } catch (e) {
                    alert("加入收藏失败，请使用Ctrl+D进行添加");
                }
            }
        }
    }
    return false;
}

//设置首页
function setIndex(url) {

    if (document.all) {

        document.body.style.behavior = 'url(#default#homepage)';

        document.body.setHomePage(url);

    } else {

        alert("您好,您的浏览器不支持自动设置页面为首页功能,请您手动在浏览器里设置该页面为首页!");

    }
}

// update文字弹性滑动
function update() {
    var oDiv = $('.update');
    var oUl = oDiv.find('ul');
    var iH = 0;
    var aLl = oDiv.find('li');
    var oBtnUp = $('#updateUpBtn');
    var oBtnDown = $('#updateDownBtn');
    var iNow = 0;
    var timer = null;
    iH = oUl.find('li').height();

    oBtnUp.click(function () {
        doMove(-1);
    });
    oBtnDown.click(function () {
        doMove(1);
    });

    oDiv.hover(function () {
        clearInterval(timer);
    }, autoPlay);

    function autoPlay() {
        timer = setInterval(function () {
            doMove(-1);
        }, 3500);
    }

    autoPlay();

    function doMove(num) {
        iNow += num;
        if (Math.abs(iNow) > aLl.length - 1) {
            iNow = 0;
        }
        if (iNow > 0) {
            iNow = -(aLl.length - 1);
        }
        oUl.stop().animate({'top': iH * iNow}, 500);
    }
};













