<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

</head>

<body class="antialiased">
    <h1>Photo upload / 写真登録</h1>
    <div id="container" style="">
        <img id="draggable-image" src={{ asset('image/flyman.png') }} style="cursor: pointer; width: 100px;">
    </div>
    <button id="right_rotate">右回転</button>
    <button id="left_rotate">左回転</button>
    <button id="reset-position">おじさんの位置をリセット</button>

    <form method="POST" action="{{ route('are.create') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="xPos" name="xPos">
        <input type="hidden" id="yPos" name="yPos">
        <input type="hidden" id="angle" name="angle">
        <div>
            <label for="formFileSm">こちらからアップロードしてください。</label>
        </div>
        <input type="file" id="upload">



        <div id="upload-demo"></div>
        <input type="hidden" id="base64image" name="base64image">
        <button type="submit" id="upload-result">作成</button>
    </form>


    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery UI library -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://rawgit.com/godswearhats/jquery-ui-rotatable/master/jquery.ui.rotatable.js"></script>


    {{-- croppie --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>

    <style>
        #draggable-image {
            z-index: 9999;
        }


        #upload-demo {
            width: 300px;
            height: 300px;
        }
    </style>
    <script>
        const draggableImage = document.getElementById('draggable-image');
        let angle = 0;
        let xPos = 0;
        let yPos = 0;

        $(function() {
            $("#draggable-image").draggable({
                containment: "#upload-demo",
                // uploaddemo内での座標を取得

                stop: function(event, ui) {
                    var uploadDemo = $("#upload-demo").offset();
                    var uploadDemoWidth = $("#upload-demo").width();
                    var uploadDemoHeight = $("#upload-demo").height();

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
            });
            $('#left_rotate').click(function() {
                angle -= 15;
                $('#draggable-image').css({
                    'transform': 'rotate(' + angle + 'deg)'
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var $uploadCrop;

            function readFile(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $uploadCrop.croppie('bind', {
                            url: e.target.result
                        });
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $uploadCrop = $('#upload-demo').croppie({
                enableExif: true,
                viewport: {
                    width: 250,
                    height: 250,
                    type: 'square'
                },
                boundary: {
                    width: 300,
                    height: 300
                }
            });

            $('#upload').on('change', function() {
                readFile(this);
            });
            $('#upload-result').on('click', function() {
                $uploadCrop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport',
                    quality: 1
                }).then(function(resp) {
                    $('#base64image').val(resp);
                });
                $('#xPos').val(xPos);
                $('#yPos').val(yPos);
                $('#angle').val(angle);
            });
        });
    </script>
</body>


</html>
