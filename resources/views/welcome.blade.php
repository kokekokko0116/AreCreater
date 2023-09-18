<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="twitter:card" content="summary_large_image">

    <meta property="og:title" content="Flyman">
    {{-- 以下バイネームの説明文 --}}
    <meta property="og:description" content="Flymanをレイアウト">
    <meta property="og:image" content="{{ asset('image/flyman.png') ?? null }}">
    {{-- ラインの画像はポジション指定可能 --}}
    <meta property="og:image:position" content="above">

    <title>Flyman</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

</head>

<body class="antialiased">
    <h1>Flymans</h1>
    <div id="container" class="justify-center items-center">
        <img id="draggable-image" src={{ asset('image/flyman.png') }} style="cursor: pointer; width: 150px;">
    </div>
    <button id="left_rotate">左回転</button>
    <button id="reset-position">おじさんの位置をリセット</button>
    <button id="right_rotate">右回転</button>


    <form method="POST" action="{{ route('are.create') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="xPos" name="xPos">
        <input type="hidden" id="yPos" name="yPos">
        <input type="hidden" id="angle" name="angle">

        <input type="hidden" id="base64image" name="base64image">
        <input type="file" id="upload-image" name="background">

        <button type="submit" id="upload-result">作成</button>
    </form>

    <div id="container" style="position: relative; width: 500px; height: 500px; border: 1px solid #000;">
        <img id="uploaded-image" style="cursor: pointer; display: none;">
    </div>





    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery UI library -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://rawgit.com/godswearhats/jquery-ui-rotatable/master/jquery.ui.rotatable.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>


    <style>
        #draggable-image {
            z-index: 9999;
        }
    </style>
    <script>
        const draggableImage = document.getElementById('draggable-image');
        let angle = 0;
        let xPos = 0;
        let yPos = 0;

        $(function() {
            $("#draggable-image").draggable({
                containment: "#uploaded-image",
                // uploaddemo内での座標を取得

                stop: function(event, ui) {
                    var uploadDemo = $("#uploaded-image").offset();
                    var uploadDemoWidth = $("#uploaded-image").width();
                    var uploadDemoHeight = $("#uploaded-image").height();

                    var imagePos = ui.offset;
                    var imageWidth = $(this).width();
                    var imageHeight = $(this).height();

                    // #upload-demoの境界内に画像があるかチェック
                    if (imagePos.left >= uploadDemo.left &&
                        imagePos.top >= uploadDemo.top &&
                        imagePos.left + imageWidth <= uploadDemo.left + uploadDemoWidth &&
                        imagePos.top + imageHeight <= uploadDemo.top + uploadDemoHeight) {

                        var xPosWithinDemo = imagePos.left - uploadDemo.left;
                        var yPosWithinDemo = imagePos.top - uploadDemo.top;
                        xPos = xPosWithinDemo;
                        yPos = yPosWithinDemo;
                        console.log('X座標:', xPosWithinDemo, 'Y座標:', yPosWithinDemo);
                    }
                }
            });
            $("#draggable-image").rotatable({
                stop: function(event, ui) {
                    angle = ui.angle.current;
                    console.log('角度:', angle);
                }
            });
            // リセットボタンがクリックされたときの処理
            $("#reset-position").click(function() {
                $("#draggable-image").css({
                    'left': '0px',
                    'top': '0px'
                });
                xPos = 0;
                yPos = 0;
                angle = 0;
            });
        });
        $(document).ready(function() {
            $('#right_rotate').click(function() {
                angle += 15;
                $('#draggable-image').css({
                    'transform': 'rotate(' + angle + 'deg)'
                });
                console.log(angle);
            });
            $('#left_rotate').click(function() {
                angle -= 15;
                $('#draggable-image').css({
                    'transform': 'rotate(' + angle + 'deg)'
                });
                console.log(angle);
            });
        });
    </script>
    <script>
        $('#upload-result').on('click', function() {
            $('#xPos').val(xPos);
            $('#yPos').val(yPos);
            $('#angle').val(angle);
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#upload-image").change(function(e) {
                const file = e.target.files[0];

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(event) {
                        const img = new Image();

                        img.onload = function() {
                            const maxWidth = 500; // コンテナの幅
                            const maxHeight = 500; // コンテナの高さ
                            let width = img.width;
                            let height = img.height;

                            if (width > maxWidth) {
                                const ratio = maxWidth / width;
                                width = maxWidth;
                                height *= ratio;
                            }

                            if (height > maxHeight) {
                                const ratio = maxHeight / height;
                                height = maxHeight;
                                width *= ratio;
                            }

                            $("#uploaded-image").css({
                                width: width + "px",
                                height: height + "px"
                            }).attr('src', event.target.result).show();

                            // draggableはここで適用せず、単に画像を表示するだけ
                        }

                        img.src = event.target.result;
                    }

                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</body>


</html>
