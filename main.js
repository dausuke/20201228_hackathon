$(function () {
    //画面の切り替え
    $('.navbar-nav a').on('click', function () {
        $(this)
            .parent()
            .addClass('active')
            .siblings('.active')
            .removeClass('active');
        const content = $(this).attr('href');
        $(content).addClass('active').siblings('.active').removeClass('active');
        return false;
    });
    //マイページの切り替え
    $('.nav-link').on('click', function () {
        $(this)
            .parent()
            .addClass('active')
            .siblings('.active')
            .removeClass('active');
        const content = $(this).attr('href');
        $(content).addClass('active').siblings('.active').removeClass('active');
        return false;
    });
    //サインアウト
    $('#signout').on('click', function () {
        if (confirm('サインアウトしますか？')) {
            window.location.href = 'index.php';
        }
    });

    //いいねボタンクリック時
    $(document).on('click', '.favorite_icon', function () {
        //いいねを押していた場合
        if ($(this).hasClass('color')) {
            $(this).removeClass('color');
            const delete_favoriteData = {
                content_id: $(this)
                    .parent()
                    .parent()
                    .find('.content_id')
                    .text(),
            };
            $.ajax({
                //POST通信
                type: 'POST',
                url: 'delete_favoritedata.php',
                data: delete_favoriteData,
            }).then(
                // 通信成功時
                function (data) {
                    console.log(data);
                    location.reload();
                },
                // 通信失敗時
                function () {
                    alert('読み込み失敗');
                }
            );
            //いいねを押していなかった場合
        } else {
            $(this).addClass('color');
            const favorite_data = {
                favorite_class: 'color',
                favorite_id: $(this)
                    .parent()
                    .parent()
                    .find('.content_id')
                    .text(),
            };
            console.log(favorite_data);
            //PHP送信
            $.ajax({
                //POST通信
                type: 'POST',
                url: 'favorite_data.php',
                data: favorite_data,
            }).then(
                // 通信成功時
                function (data) {
                    console.log(data);
                    location.reload();
                },
                // 通信失敗時
                function () {
                    alert('読み込み失敗');
                }
            );
        }
    });

    //マイページの処理
    //店名の候補表示
    $('#area').on('change', function () {
        const shopdata = {
            name: $('#name').val(),
            area: $('#area').val(),
        };
        //PHP送信
        $.ajax({
            //POST通信
            type: 'POST',
            url: 'map_api.php',
            data: shopdata,
        }).then(
            // 通信成功時
            function (data) {
                console.log(data);
                const select_shopname = JSON.parse(data);
                select_shopname.forEach(function (value) {
                    const select_shopnameTag = `
                        <option>${value}</option>
                    `;
                    $('#select-shopnamecontent').append(select_shopnameTag);
                });
                //店名の候補をモーダルウィンドウで表示
                $('#select-shopname').fadeIn(200);
                $('body').addClass('no-scroll');
                $(document).on('change', '#select-name', function () {
                    const confirm_shopname = $('#select-name').val();
                    console.log(confirm_shopname);
                    if (confirm_shopname != '候補地なし') {
                        $('#name').val(confirm_shopname);
                    } else {
                        $('#noname').val('nodata');
                    }
                });
            },
            // 通信失敗時
            function () {
                alert('読み込み失敗');
            }
        );
    });
    //閉じるボタンで検索画面終了
    $(document).on('click', '#select_modal_close', function () {
        $('#select-shopname').fadeOut(200);
        $('body').removeClass('no-scroll');
    });
    //保存するボタンクリックイベント
    $('#save').on('click', function () {
        const data = {
            name: $('#name').val(),
            area: $('#area').val(),
            evaluation: $('#evaluation').val(),
            category: $('#category').val(),
            freetext: $('#freetext').val(),
            candidate:$('#noname').val()
        };
        //PHP送信
        $.ajax({
            //POST通信
            type: 'POST',
            url: 'creat_data.php',
            data: data,
        }).then(
            // 通信成功時
            function (data) {
                console.log(data);
            },
            // 通信失敗時
            function () {
                alert('読み込み失敗');
            }
        );
        //送信後、各エリアの表示を削除
        $('#name').val('');
        $('#area').val('');
        $('#evaluation').val('');
        $('#category').val('');
        $('#freetext').val('');
        location.reload();
    });
    //編集時の関数
    function creatData(testdata) {
        testdata.forEach(function (value, key) {
            //ホームのタイムライン
            $('#listname' + key).text(value.shopname);
            $('#listgetday' + key).text(value.getday);
            $('#listarea' + key).text(value.area);
            $('#listcategory' + key).text(value.category);
            $('#listevaluation' + key).text(value.evaluation);
            $('#listfreetext' + key).text(value.freetext);
            console.log(value);
            //マイページのタイムライン
            $('#mypagelistname' + key).text(value.shopname);
            $('#mypagelistgetday' + key).text(value.getday);
            $('#mypagelistarea' + key).text(value.area);
            $('#mypagelistcategory' + key).text(value.category);
            $('#mypagelistevaluation' + key).text(value.evaluation);
            $('#mypagelistfreetext' + key).text(value.freetext);
        });
    }
    // //投稿の編集
    $(document).on('click', '.edit_button', function () {
        const edit_data = {
            edit_evaluation: $(this)
                .parents('.modal-content')
                .find('.edit_evaluation')
                .val(),
            edit_freetext: $(this)
                .parents('.modal-content')
                .find('.edit_freetext')
                .val(),
            content_id: $(this)
                .parents('.modal')
                .siblings('.timelinecontent')
                .find('.content_id')
                .text(),
        };
        console.log(edit_data);
        $.ajax({
            //POST通信
            type: 'POST',
            url: 'edit_data.php',
            data: edit_data,
        }).then(
            // 通信成功時
            function (data) {
                if (data === 'noedit') {
                    alert('編集項目を入力してください');
                } else {
                    // console.log(data)
                    // location.reload();
                    const testdata = JSON.parse(data);
                    creatData(testdata);
                }
            },
            // 通信失敗時
            function () {
                alert('読み込み失敗');
            }
        );
    });
    //投稿の削除
    $(document).on('click', '.delete_btn', function () {
        const delete_id = {
            content_id: $(this)
                .parents('.modal')
                .siblings('.timelinecontent')
                .find('.content_id')
                .text(),
        };
        console.log(delete_id);
        $.ajax({
            //POST通信
            type: 'POST',
            url: 'delete_deta.php',
            data: delete_id,
        }).then(
            // 通信成功時
            function (data) {
                console.log(data);
                location.reload();
            },
            // 通信失敗時
            function () {
                alert('読み込み失敗');
            }
        );
    });
    //ユーザーアカウント設定
    $('#editaccount').on('click', function () {
        const editAccountData = {
            email: $('#editaccountemail').val(),
            accountname: $('#editaccountname').val(),
            city: $('#editaccountcity').val(),
        };
        $('#editaccountemail').val('');
        $('#editaccountname').val('');
        $('#editaccountcity').val('');
        //PHP送信
        $.ajax({
            //POST通信
            type: 'POST',
            url: 'edit_account.php',
            data: editAccountData,
        }).then(function (data) {
            console.log(data);
            location.reload();
        });
    });
    //検索
    $('#search_submmit').on('click', function () {
        $('#searchmodal').fadeIn(300);
        $('body').addClass('no-scroll');
        const keyword = { keyword: $('#search').val() };
        console.log(keyword);
        //PHP送信
        $.ajax({
            //POST通信
            type: 'POST',
            url: 'search.php',
            data: keyword,
        }).then(function (data) {
            // console.log(data)
            const searchArray = JSON.parse(data);
            // 検索結果をモーダルウィンドウで表示
            searchArray.forEach(function (value, key) {
                const timelineTag = `
                            <article class = "timelineitem search" id = "timelineitem">
                                <header>
                                    pp
                                </header>
                                <div class = "timelinecontent">
                                    <p class = "getday" id= "searchgetday${key}"></p>
                                    <ul>
                                        <li>店　　　名：</li>
                                        <li>エ　リ　ア：</li>
                                        <li>カテゴリー：</li>
                                        <li>評　　　価：</li>
                                    </ul>
                                    <ul>
                                        <li id="searchname${key}" class="searchname"></li>
                                        <li id="searcharea${key}" class="searcharea"></li>
                                        <li id="searchcategory${key}" class="searchcategory"></li>
                                        <li id="searchevaluation${key}" class="searchevaluation"></li>
                                        <li class="uid" hidden>${uid}</li>
                                        <li class="content_id" hidden>${value.id}</li>
                                    </ul>
                                    <ul>
                                        <li>メモ</li>
                                        <li><div id="searchfreetext${key}" class="searchfreetext"></div ></li>
                                        </ul>
                                    <ul>
                                        <i class="fas fa-heart fa-2x favorite_icon" id="searche_favorite_icon${key}"></i>
                                    </ul>
                                </div>
                            </article>
                        `;
                //DBに保存した全データを表示（ホーム画面のタイムライン）
                $('#searchtimeline').prepend(timelineTag);
                $('#searchname' + key).text(value.shopname);
                $('#searchgetday' + key).text(value.getday);
                $('#searcharea' + key).text(value.area);
                $('#searchcategory' + key).text(value.category);
                $('#searchevaluation' + key).text(value.evaluation);
                $('#searchfreetext' + key).text(value.freetext);
            });
        });
    });
    //閉じるボタンで検索画面終了
    $(document).on('click', '#modal_close', function () {
        $('#searchmodal').fadeOut(300);
        $('body').removeClass('no-scroll');
        location.reload();
    });
});
