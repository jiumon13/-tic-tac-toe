<!doctype html>
<html>
<head>
    <title>Х - 0</title>
    <meta charset='utf-8' />
    <link rel="stylesheet" href="assetic/css/style.css">
</head>
<body>
<canvas id='field'>Поле</canvas>
<script>

    var canvas = document.getElementById("field"),
        ctx = canvas.getContext('2d');
    var H = 600;
    var W = 600;
    var I = 40;
    var Cx = W / 2;
    var Cy = H / 2;
    var wRect = 0.29 * W;
    var hRect = 0.28 * H;

    var marker = "<?php echo $marker?>";
    var user_id = "<?php echo $user_id?>";
    var game_id = "<?php echo $game_id;?>";
    var map = JSON.parse('<?php echo $map;?>');
    var turn = JSON.parse('<?php echo $whose_turn;?>');

    var makeField = function() {
        canvas.height = H;
        canvas.width = W;

        ctx.beginPath();
        ctx.moveTo(Cx - (wRect / 2), Cy - (1.5 * hRect));
        ctx.lineTo(Cx - (wRect / 2), Cy + (1.5 * hRect));
        ctx.moveTo(Cx + (wRect / 2), Cy - (1.5 * hRect));
        ctx.lineTo(Cx + (wRect / 2), Cy + (1.5 * hRect));
        ctx.moveTo(Cx - (1.5 * wRect), Cy - (hRect / 2));
        ctx.lineTo(Cx + (1.5 * wRect), Cy - (hRect / 2));
        ctx.moveTo(Cx - (1.5 * wRect), Cy + (hRect / 2));
        ctx.lineTo(Cx + (1.5 * wRect), Cy + (hRect / 2));
        ctx.closePath();

        ctx.stroke();
    };

makeField();

    var points = [
                    [
                        {
                            x1: Cx - (1.5 * wRect),
                            y1: Cy - (1.5 * hRect),
                            x2: Cx - (wRect / 2),
                            y2: Cy - (hRect / 2)
                        },

                        {
                            x1: Cx - (wRect / 2),
                            y1: Cy - (1.5 * hRect),
                            x2: Cx + (wRect / 2),
                            y2: Cy - (hRect / 2)
                        },

                        {
                            x1: Cx + (wRect / 2),
                            y1: Cy - (1.5 * hRect),
                            x2: Cx + (1.5 * wRect),
                            y2: Cy - (hRect / 2)

                        }
                    ],

                    [
                        {
                            x1: Cx - (1.5 * wRect),
                            y1: Cy - (hRect / 2),
                            x2: Cx - (wRect / 2),
                            y2: Cy + (hRect / 2)
                        },

                        {
                            x1: Cx - (wRect / 2),
                            y1: Cy - (hRect / 2),
                            x2: Cx + (wRect / 2),
                            y2: Cy + (hRect / 2)
                        },

                        {
                            x1: Cx + (wRect / 2),
                            y1: Cy - (hRect / 2),
                            x2: Cx + (1.5 * wRect),
                            y2: Cy + (hRect / 2)
                        }
                    ],

                    [
                        {
                            x1: Cx - (1.5 * wRect),
                            y1: Cy + (hRect / 2),
                            x2: Cx - (wRect / 2),
                            y2: Cy + (1.5 * hRect)
                        },

                        {
                            x1: Cx - (wRect / 2),
                            y1: Cy + (hRect / 2),
                            x2: Cx + (wRect / 2),
                            y2: Cy + (1.5 * hRect)
                        },

                        {
                            x1: Cx + (wRect / 2),
                            y1: Cy + (hRect / 2),
                            x2: Cx + (1.5 * wRect),
                            y2: Cy + (1.5 * hRect)
                        }
                    ]
                ];

    var local_map = [ [ [], [], [] ], [ [], [], [] ], [ [], [], [] ] ];

    var cross = function (point) {
        ctx.beginPath();
        ctx.moveTo((point.x1 + point.x2) / 2 + I, (point.y1 + point.y2) / 2 + I);
        ctx.lineTo((point.x1 + point.x2) / 2 - I, (point.y1 + point.y2) / 2 - I);
        ctx.moveTo((point.x1 + point.x2) / 2 + I, (point.y1 + point.y2) / 2 - I);
        ctx.lineTo((point.x1 + point.x2) / 2 - I, (point.y1 + point.y2) / 2 + I);
        ctx.closePath();
        ctx.stroke();
    };

    var joe = function(point) {
        ctx.beginPath();
        ctx.arc((point.x1 + point.x2) / 2, (point.y2 + point.y1) / 2 , 50, 0 , 360);
        ctx.stroke()
    };

    function initialization() {
        for(var i = 0; i < map.length; i++) {
            for(var j = 0; j < map[i].length; j++) {
                if(map[i][j] == 2) {
                    joe(points[i][j]);
                    local_map[i][j] = 2;
                } else if (map[i][j] == 1) {
                    cross(points[i][j]);
                    local_map[i][j] = 1;
                }
            }
        }
    }

initialization();

    var move = function(e) {
            for (var i = 0; i < points.length; i++) {
                for (var j = 0; j < points[i].length; j++) {
                    if (e.offsetX >= points[i][j].x1 && e.offsetY >= points[i][j].y1 && e.offsetX <= points[i][j].x2 && e.offsetY <= points[i][j].y2) {
                        if (local_map[i][j].length == 0) {
                            if (marker == 1) {
                                cross(points[i][j]);
                                local_map[i][j] = 1;
                            } else if (marker == 2) {
                                joe(points[i][j]);
                                local_map[i][j] = 2;
                            }

                            var moveRequest = new XMLHttpRequest();
                            if (moveRequest.readyState == 4) {
                                if (moveRequest.status == 200) {
                                     console.log(moveRequest.responseText);
                                } else {

                                }
                            } else {

                            }


                            moveRequest.open("GET", "http://localhost/index.php/field/move?user_id=" + user_id + "&marker=" + marker + "&game_id=" + game_id + "&i=" + i + "&j=" + j + "&turn=" + turn, true);
                            moveRequest.send();
                        }
                    }
                }
            }
    };

    var httpRequest = function() {
        var httpRequest = new XMLHttpRequest();
        httpRequest.onreadystatechange = function() {
            if (httpRequest.readyState == 4) {
                if (httpRequest.status == 200) {

                    var json = JSON.parse(httpRequest.responseText);

                    for(var i = 0; i < json.length; i++) {
                        for(var j = 0; j < json[i].length; j++) {
                            if(local_map[i][j] != json[i][j]) {
                                if(json[i][j] == 2){
                                    joe(points[i][j]);
                                    local_map[i][j] = 2;
                                } else if (json[i][j] == 1) {
                                    cross(points[i][j]);
                                    local_map[i][j] = 1;
                                }
                            }
                        }
                    }
                } else {

                }
            } else {

            }
        };

        httpRequest.open("GET", "http://localhost/index.php/field/getMapJSON?game_id=" + game_id, true);
        httpRequest.send();
    };

    setInterval(httpRequest, 500);

    var turnRequest = function(e) {
        var turnRequest = new XMLHttpRequest();
        turnRequest.onreadystatechange = function () {
            if (turnRequest.readyState == 4) {
                if (turnRequest.status == 200) {
                    var turn = turnRequest.responseText;

                    if (turn == marker) {
                        move(e);
                    }
                } else {

                }
            } else {

            }
        };

        turnRequest.open("GET", "http://localhost/index.php/field/whoseTurn?game_id=" + game_id, true);
        turnRequest.send();
    };


    document.getElementById('field').onclick = function (e) {
        turnRequest(e)
    };
</script>
</body>
</html>
