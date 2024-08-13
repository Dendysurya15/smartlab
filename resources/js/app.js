import SignaturePad from "signature_pad";
window.SignaturePad = SignaturePad;
import $ from "jquery";
window.$ = $;
window.jQuery = $; // Ensure jQuery is globally available

// Define the sign function as a jQuery plugin
$.fn.sign = function (options) {
    var params = $.extend(
        {
            resetButton: options.resetButton ? options.resetButton : null,
            width: options.width ? options.width : 500,
            height: options.height ? options.height : 300,
            lineWidth: options.lineWidth ? options.lineWidth : 10,
        },
        options
    );

    var canvas = $(this);
    var context = canvas.get(0).getContext("2d");
    context.lineJoin = context.lineCap = "round";
    canvas.attr("width", params.width);
    canvas.attr("height", params.height);
    var points = [];

    var draw = function (ctx, x, y) {
        points.push({ x: x, y: y, break: false });
        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
        var p1 = points[0];
        var p2 = points[1];

        ctx.beginPath();
        ctx.moveTo(p1.x, p1.y);

        for (var i = 1; i < points.length; i++) {
            var midPoint = {
                x: p1.x + (p2.x - p1.x) / 2,
                y: p1.y + (p2.y - p1.y) / 2,
            };
            if (p1.break) {
                ctx.moveTo(p2.x, p2.y);
            } else {
                ctx.quadraticCurveTo(p1.x, p1.y, midPoint.x, midPoint.y);
            }
            p1 = points[i];
            p2 = points[i + 1];
        }

        ctx.lineWidth = params.lineWidth;
        ctx.lineTo(p1.x, p1.y);
        ctx.stroke();
    };

    var holdClick = false;

    var getPos = function (e) {
        var rect = canvas.get(0).getBoundingClientRect();
        var x, y;

        if (e.type.includes("touch")) {
            x = e.touches[0].clientX - rect.left;
            y = e.touches[0].clientY - rect.top;
        } else {
            x = e.clientX - rect.left;
            y = e.clientY - rect.top;
        }

        return { x: x, y: y };
    };

    canvas.on("touchstart mousedown", function (e) {
        e.preventDefault(); // Prevent scrolling on touch devices
        holdClick = true;
        var pos = getPos(e);
        points.push({ x: pos.x, y: pos.y, break: false });
        draw(context, pos.x, pos.y);
    });

    canvas.on("touchmove mousemove", function (e) {
        if (holdClick) {
            e.preventDefault(); // Prevent scrolling on touch devices
            var pos = getPos(e);
            draw(context, pos.x, pos.y);
        }
    });

    canvas.on("touchend mouseup", function () {
        holdClick = false;
        points[points.length - 1].break = true;
    });

    if (params.resetButton) {
        params.resetButton.on("click", function () {
            context.clearRect(0, 0, canvas.width(), canvas.height());
            points.length = 0;
        });
    }
};
