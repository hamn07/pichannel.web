$(document).ready(function() {



    $("#fileSelect").click(function(e) {
        if ($("#fileElem")) {
            $("#fileElem").click();
        }
        e.preventDefault();

    });
    $("#fileElem").change(function(e) {
        // 檔案(array)
        var fileList = this.files;
        // 檔案個數
        var numFiles = fileList.length;
        for (var i = 0; i < numFiles; i++) {
            // 取得檔案物件
            var file = fileList[i];
            // 轉換成local url
            var objectURL = window.URL.createObjectURL(file);

            // 建立img物件，並將src設定為local url
            var img = document.createElement("img");
            img.src = objectURL;
            img.onload = function() {
                window.URL.revokeObjectURL(this.src);
            }

            // 建立a href物件，並將img物件餵給它
            var a = document.createElement("a");
            a.href = "#";
            a.appendChild(img);

            // 建立div物件，並將img物件餵給它
            var div = document.createElement("div");
            div.className = "zitem";
            document.body.insertBefore(div, document.body.firstChild);
            div.appendChild(a);

            // 建立caption
            a = document.createElement("a");
            a.href = "#";
            a.innerHTML = file.name;
            var caption = document.createElement("div");
            caption.className = "caption";
            caption.appendChild(a);
            // div.appendChild(a);
            div.appendChild(caption)
            
            
            // document.body.appendChild(div);

            $('img').width($('.zitem').width());
            $('img').height($('.zitem').height());
            $('.caption').width($('.zitem').width());

            //move the image in pixel
            var move = -15;

            //zoom percentage, 1.2 =120%
            var zoom = 1.2;

            //On mouse over those thumbnail
            $('.zitem').hover(function() {

                    //Set the width and height according to the zoom percentage
                    width = $('.zitem').width() * zoom;
                    height = $('.zitem').height() * zoom;

                    //Move and zoom the image
                    $(this).find('img').stop(false, true).animate({
                        'width': width,
                        'height': height,
                        'top': move,
                        'left': move
                    }, {
                        duration: 200
                    });

                    //Display the caption
                    $(this).find('div.caption').stop(false, true).fadeIn(200);
                },
                function() {
                    //Reset the image
                    $(this).find('img').stop(false, true).animate({
                        'width': $('.zitem').width(),
                        'height': $('.zitem').height(),
                        'top': '0',
                        'left': '0'
                    }, {
                        duration: 100
                    });

                    //Hide the caption
                    $(this).find('div.caption').stop(false, true).fadeOut(200);
                });
        }
    })
});
