<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="og:title" content="Flyman">
    {{-- 以下バイネームの説明文 --}}
    <meta property="og:description" content="Flymanをレイアウト">
    <meta property="og:image" content={{ asset('image/80233.jpg') ?? null }}>
    {{-- ラインの画像はポジション指定可能 --}}
    <meta property="og:image:position" content="above">

    <title>Flyman Creater</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="bg-light py-5" style="max-width: 400px; margin: 0 auto;">
    <div class="container">
        <h1 class="text-center mb-4">Flyman Creater</h1>

        <div class="text-center mb-4">
            <img id="draggable-image" src="{{ asset('image/flyman.png') }}" class="cursor-pointer"
                style="width: 100px;">
        </div>

        <div class="d-flex justify-content-between mb-2">
            <button id="zoom-in" class="btn btn-primary">拡大</button>
            <button id="zoom-out" class="btn btn-primary">縮小</button>
            <button id="left_rotate" class="btn btn-primary">左回転</button>
            <button id="right_rotate" class="btn btn-primary">右回転</button>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <select id="flyman-selector" class="custom-select">
                <option value="flyman.png">オリジン</option>
                <option value="flyman_white.png">ホワイト</option>
                <option value="flyman_black.png">ブラック</option>
                <option value="flyman_blue.png">ブルー</option>
                <option value="flyman_green.png">グリーン</option>
                <option value="flyman_purple.png">パープル</option>
                <option value="flyman_red.png">レッド</option>
            </select>
            <button id="reset-position" class="btn btn-danger mb-2">おっちゃんをリセット</button>

        </div>


        <form method="POST" action="{{ route('are.create') }}" enctype="multipart/form-data" class="mb-4">
            @csrf
            <input type="hidden" id="xPos" name="xPos">
            <input type="hidden" id="yPos" name="yPos">
            <input type="hidden" id="angle" name="angle">
            <input type="hidden" id="width" name="width">
            <input type="hidden" id="flyman" name="flyman">
            <!-- Hidden Inputs -->
            <div class="custom-file mb-2">
                <input type="file" class="custom-file-input" id="upload-image" name="background">
            </div>
            <div id="container" style="position: relative; width: 350px; height: 350px; border: 1px solid #000;">
                <img id="uploaded-image" style="cursor: pointer; display: none;">
            </div>
            <button type="submit" id="upload-result" class="btn btn-success">作成</button>
        </form>


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
        let width = 100;

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

            // リセットボタンがクリックされたときの処理
            $("#reset-position").click(function() {
                $("#draggable-image").css({
                    'left': '0px',
                    'top': '0px'
                });
                xPos = 0;
                yPos = 0;
                angle = 0;
                width = 100;
                $('#draggable-image').css('width', width);
                document.getElementById('draggable-image').src = '/image/' + 'flyman.png';
            });
        });
        $(document).ready(function() {
            $('#zoom-in').click(function() {
                width += 10;
                $('#draggable-image').css('width', width);
            });

            $('#zoom-out').click(function() {
                width -= 10;
                $('#draggable-image').css('width', width);
            });
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
        document.getElementById('flyman-selector').addEventListener('change', function() {
            const selectedImage = this.value ?? flyman.png;
            document.getElementById('draggable-image').src = '/image/' + selectedImage;
        });
    </script>
    <script>
        $('#upload-result').on('click', function() {
            $('#xPos').val(xPos);
            $('#yPos').val(yPos);
            $('#angle').val(angle);
            $('#width').val(width);
            $('#flyman').val($('#flyman-selector').val());
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
                            const maxWidth = 350; // コンテナの幅
                            const maxHeight = 350; // コンテナの高さ
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
