! function(window) {
    function getQuery(e) {
        i = "";
        if (-1 != e.indexOf("?")) var i = e.split("?")[1];
        return i
    }
    var myutil = {};
    myutil.myShowLink = function(e) {
        var i = myutil.dialog("选择器", ["./index.php?c=site&a=entry&do=system/dialoglink&m=slwl_fitment"], "");
        i.modal({
            keyboard: !1
        }), i.find(".modal-body").css({
            height: "600px",
            "overflow-y": "auto",
            padding: "0 15px"
        }), i.modal("show"), window.myShowLinkComplete = function(t, o, p) {
            $.isFunction(e) && (e(t, o, p), i.modal("hide"))
        }
    }, myutil.dialog = function(e, i, t, o) {
        o || (o = {}), o.containerName || (o.containerName = "modal-message");
        var n = $("#" + o.containerName);
        if (0 == n.length && ($(document.body).append('<div id="' + o.containerName + '" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"></div>'), n = $("#" + o.containerName)), html = '<div class="modal-dialog we7-modal-dialog">\t<div class="modal-content">', e && (html += '<div class="modal-header">\t<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>\t<h3>' + e + "</h3></div>"), i && ($.isArray(i) ? html += '<div class="modal-body">正在加载中</div>' : html += '<div class="modal-body">' + i + "</div>"), t && (html += '<div class="modal-footer">' + t + "</div>"), html += "\t</div></div>", n.html(html), i && $.isArray(i)) {
            var a = function(e) {
                n.find(".modal-body").html(e)
            };
            2 == i.length ? $.post(i[0], i[1]).success(a) : $.get(i[0]).success(a)
        }
        return n
    }, myutil.map = function(e, t) {
        require(["map"], function() {
            function i(e) {
                n.getPoint(e, function(e) {
                    map.panTo(e), marker.setPosition(e), marker.setAnimation(BMAP_ANIMATION_BOUNCE), setTimeout(function() {
                        marker.setAnimation(null)
                    }, 3600)
                })
            }
            e || (e = {}), e.lng || (e.lng = 116.403851), e.lat || (e.lat = 39.915177);
            var o = new BMap.Point(e.lng, e.lat),
                n = new BMap.Geocoder,
                a = $("#map-dialog");
            if (0 == a.length) {
                let html_cont =
                    '<div class="layui-form-item">'+
                        '<div class="input-group">'+
                            '<input type="text" class="layui-input" placeholder="请输入地址来直接查找相关位置">'+
                            '<div class="input-group-btn">'+
                                '<button class="btn btn-default">'+
                                    '<i class="icon-search"></i>搜索'+
                                '</button>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div id="map-container" style="height:450px;"></div>'+
                    '';
                let html_bottom =
                    '<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>'+
                    '<button type="button" class="btn btn-primary">确认</button>';
                (a = myutil.dialog("请选择地点", html_cont, html_bottom, {
                    containerName: "map-dialog"
                })).find(".modal-dialog").css("width", "80%"), a.modal({
                    keyboard: !1
                }), map = myutil.map.instance = new BMap.Map("map-container"), map.centerAndZoom(o, 12), map.enableScrollWheelZoom(), map.enableDragging(), map.enableContinuousZoom(), map.addControl(new BMap.NavigationControl), map.addControl(new BMap.OverviewMapControl), marker = myutil.map.marker = new BMap.Marker(o), marker.setLabel(new BMap.Label("&nbsp;请您移动此标记，选择您的坐标&nbsp;", {
                    offset: new BMap.Size(10, -20)
                })), map.addOverlay(marker), marker.enableDragging(), marker.addEventListener("dragend", function(e) {
                    var t = marker.getPosition();
                    n.getLocation(t, function(e) {
                        a.find(".input-group :text").val(e.address)
                    })
                }), a.find(".input-group :text").keydown(function(e) {
                    13 == e.keyCode && i($(this).val())
                }), a.find(".input-group button").click(function() {
                    i($(this).parent().prev().val())
                })
            }
            a.off("shown.bs.modal"), a.on("shown.bs.modal", function() {
                marker.setPosition(o), map.panTo(marker.getPosition())
            }), a.find("button.btn-primary").off("click"), a.find("button.btn-primary").on("click", function() {
                if ($.isFunction(t)) {
                    var e = myutil.map.marker.getPosition();
                    n.getLocation(e, function(i) {
                        var o = {
                            lng: e.lng,
                            lat: e.lat,
                            label: i.address
                        };
                        t(o)
                    })
                }
                a.modal("hide")
            }), a.modal("show")
        })
    }, myutil.toast = function(e, t, i) {
        myutil.modal_message(i, e, "", t, "")
    }, "function" == typeof define && define.amd ? define(function() {
        return myutil
    }) : window.myutil = myutil
}(window);
