<?php
session_start();

//ユーザー情報取得
$uid = $_SESSION["uid"];
// var_dump($uid);
// exit();

// DB接続の設定
include('functions.php');
$pdo = connect_to_db();

//DBから投稿データ取得
$content_sql = 'SELECT * FROM content_table ORDER BY getday DESC';
$content_stmt = $pdo->prepare($content_sql);
//SQL実行
$content_status = $content_stmt->execute();

if ($content_status == false) {
    $error = $content_stmt->errorInfo();
    // データ登録失敗次にエラーを表示
    exit('sqlError:' . $error[2]);
} else {
    // データ表示
    $content_result = $content_stmt->fetchAll(PDO::PARAM_STR);
    $json_read_contentdata = json_encode($content_result, JSON_UNESCAPED_UNICODE);
    // var_dump($result[0]['shopname']);
    // exit();
}

//DBからユーザーデータ取得
$user_sql = 'SELECT id,accountname,city,email FROM userdata_table';
$user_stmt = $pdo->prepare($user_sql);
//SQL実行
$user_status = $user_stmt->execute();

if ($user_status == false) {
    $error = $user_stmt->errorInfo();
    // データ登録失敗次にエラーを表示
    exit('sqlError:' . $error[2]);
} else {
    // データ表示
    $user_result = $user_stmt->fetchAll(PDO::PARAM_STR);
    $keyIndex = array_search($uid, array_column($user_result, 'id'));
    $login_user_data = $user_result[$keyIndex];
    // var_dump($user_result);
    // exit();
}
//ログインしているユーザーの情報を取得

// $json_login_user_data = json_encode($login_user_data, JSON_UNESCAPED_UNICODE);

// var_dump($login_user_data);
// exit();
?>
<!DOCTYPE html>
<html lang="ja">

<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="https://kit.fontawesome.com/762ea5f979.js" crossorigin="anonymous"></script>
    <title>sharelog</title>
</head>

<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="main.js"></script>
    <script src="map.js"></script>
    <main>
        <nav class="navbar navbar-expand-sm navbar-light pt-4 mb-3" style="background-color:white;">
            <!--ヘッダ情報 -->
            <div class="navbar-header">
                <a class="navbar-brand">ShareLog</a>
            </div>
            <div class="navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item active mr-5">
                        <a href="#mypage" class="navi">マイページ</a>
                    </li>
                    <li class="nav-item mr-5" >
                        <a href="#mymap" class="navi">マップ</a>
                    </li>
                    <li class="nav-item mr-5">
                        <a href="#home" class="navi">ホーム</a>
                    </li>
                    <li class="nav-item mr-5">
                        <span class="navi" id="signout">サインアウト</span>
                    </li>
                </ul>
            </div>
            <button type="button" class="btn btn-default navbar-btn">
                <span class="glyphicon glyphicon-envelope"></span>
            </button>
        </nav>
        <div class="content active" id="home">
            <form class="form-inline my-2 my-lg-0 searchform">
                <input class="form-control mr-sm-2" id="search" type="search" placeholder="キーワード" aria-label="Search" name="keyword">
                <button class="btn btn-outline-success my-2 my-sm-0" type="button" id="search_submmit">検索</button>
            </form>
            <div id="timeline"></div>
            <div id="searchmodal">
                <div id="searchtimeline"></div>
                <i class="far fa-window-close fa-3x" id="modal_close"></i>
            </div>
        </div>
        <div class="content" id="seach">
            <div></div>
        </div>
        <div class="content" id="mypage">
            <nav class="navbar navbar-expand-sm navbar-light mt-0 mb-4">
                <button class=" navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav4" aria-controls="navbarNav4" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center">
                    <ul class="navbar-nav">
                        <li class="nav-item active">
                            <a class="nav-link" href="#mypagetimeline">投稿</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#savelog">ログ保存</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#favorite">いいね</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#config" data-toggle="modal" data-target="#configModal">設定</a>
                        </li>
                    </ul>
                </div>
            </nav>
            <div id="mypagetimeline" class="mycontent active">

            </div>
            <div id="savelog" class="mycontent">
                <div class="col-8">
                    <div class="row ">
                        <div class="col-lg">
                            <form>
                                <div id="select-shopname">
                                    <div class="select-modal">
                                        <select class="form-control select-shopcontent" id="select-name">
                                            <optgroup label="" id="select-shopnamecontent">
                                                <option>候補を選択</option>
                                                <option>候補地なし</option>
                                            </optgroup>
                                        </select>
                                        <i class="far fa-window-close fa-3x select-modal-close" id="select_modal_close"></i>
                                        <p class="attention">※候補地が出ない場合マップ表示できません</p>
                                    </div>
                                </div>
                                <div class="form-group col-10">
                                    <label>店名</label>
                                    <input type="text" class="form-control" id="name">
                                    <input type="hidden" class="form-control" id="noname">
                                </div>
                                <div class="form-group col-10">
                                    <label>エリア</label>
                                    <select class="form-control" id="area">
                                        <optgroup label="">
                                            <option>都道府県</option>
                                            <option>北海道</option>
                                            <option>青森</option>
                                            <option>岩手</option>
                                            <option>宮城</option>
                                            <option>秋田</option>
                                            <option>山形</option>
                                            <option>福島</option>
                                            <option>茨城</option>
                                            <option>栃木</option>
                                            <option>群馬</option>
                                            <option>埼玉</option>
                                            <option>千葉</option>
                                            <option>東京</option>
                                            <option>神奈川</option>
                                            <option>新潟</option>
                                            <option>富山</option>
                                            <option>石川</option>
                                            <option>福井</option>
                                            <option>山梨</option>
                                            <option>長野</option>
                                            <option>岐阜</option>
                                            <option>静岡</option>
                                            <option>愛知</option>
                                            <option>三重</option>
                                            <option>滋賀</option>
                                            <option>京都</option>
                                            <option>大阪</option>
                                            <option>兵庫</option>
                                            <option>奈良</option>
                                            <option>和歌山</option>
                                            <option>鳥取</option>
                                            <option>島根</option>
                                            <option>岡山</option>
                                            <option>広島</option>
                                            <option>山口</option>
                                            <option>徳島</option>
                                            <option>香川</option>
                                            <option>愛媛</option>
                                            <option>高知</option>
                                            <option>福岡</option>
                                            <option>佐賀</option>
                                            <option>長崎</option>
                                            <option>熊本</option>
                                            <option>大分</option>
                                            <option>宮崎</option>
                                            <option>鹿児島</option>
                                            <option>沖縄</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="form-group col-10">
                                    <label>評価</label>
                                    <select class="form-control" type="text" id="evaluation">
                                        <option value="">選択</option>
                                        <option value="★★★★★">★★★★★</option>
                                        <option value="★★★★">★★★★</option>
                                        <option value="★★★">★★★</option>
                                        <option value="★★">★★</option>
                                        <option value="★">★</option>
                                    </select>
                                </div>
                                <div class="form-group col-10">
                                    <label>カテゴリー</label>
                                    <select class="form-control" id="category">
                                        <option value="">選択</option>
                                        <option value="ファッション">ファッション</option>
                                        <option value="ランチ">ランチ</option>
                                        <option value="ディナー">ディナー</option>
                                        <option value="コーヒー">コーヒー</option>
                                        <option value="スポット">スポット</option>
                                    </select>
                                </div>
                                <div class="form-group col-10">
                                    <label>メモ</label>
                                    <textarea class="form-control" id="freetext" maxlength="150" placeholder="150文字以内"></textarea>
                                </div>
                                <div class="col-10">
                                    <button type="button" class="btn btn-outline-secondary btn-block mt-5" id="save">保存</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div id="favorite" class="mycontent"></div>
            <div id="config" class="mycontent">
                <div class="col-8">
                    <div class="row ">
                        <div class="col-lg">
                            <form class="editaccount">
                                <div class="form-group col-10 editcontent">
                                    <label>メールアドレス</label>
                                    <input type="text" class="form-control" id="editaccountemail" value="<?= $login_user_data['email'] ?>">
                                </div>
                                <div class="form-group col-10 editcontent">
                                    <label>アカウント名</label>
                                    <input type="text" class="form-control" id="editaccountname" value="<?= $login_user_data['accountname'] ?>">
                                </div>
                                <div class="form-group col-10 editcontent">
                                    <label>居住地</label>
                                    <select class="form-control" type="text" name="city" id="editaccountcity">
                                        <optgroup label="">
                                        <option><?= $login_user_data['city'] ?></option>
                                        <option>北海道</option>
                                        <option>青森</option>
                                        <option>岩手</option>
                                        <option>宮城</option>
                                        <option>秋田</option>
                                        <option>山形</option>
                                        <option>福島</option>
                                        <option>茨城</option>
                                        <option>栃木</option>
                                        <option>群馬</option>
                                        <option>埼玉</option>
                                        <option>千葉</option>
                                        <option>東京</option>
                                        <option>神奈川</option>
                                        <option>新潟</option>
                                        <option>富山</option>
                                        <option>石川</option>
                                        <option>福井</option>
                                        <option>山梨</option>
                                        <option>長野</option>
                                        <option>岐阜</option>
                                        <option>静岡</option>
                                        <option>愛知</option>
                                        <option>三重</option>
                                        <option>滋賀</option>
                                        <option>京都</option>
                                        <option>大阪</option>
                                        <option>兵庫</option>
                                        <option>奈良</option>
                                        <option>和歌山</option>
                                        <option>鳥取</option>
                                        <option>島根</option>
                                        <option>岡山</option>
                                        <option>広島</option>
                                        <option>山口</option>
                                        <option>徳島</option>
                                        <option>香川</option>
                                        <option>愛媛</option>
                                        <option>高知</option>
                                        <option>福岡</option>
                                        <option>佐賀</option>
                                        <option>長崎</option>
                                        <option>熊本</option>
                                        <option>大分</option>
                                        <option>宮崎</option>
                                        <option>鹿児島</option>
                                        <option>沖縄</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-10 editcontent">
                                    <button type="button" class="btn btn-outline-secondary btn-block mt-5" id="editaccount">保存</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="mymap" class="content">
            <div id="map" class="none" style="width:900px; height:500px"></div>
        </div>
    </main>
    <script>
        //投稿データ表示用
        const dataArray = <?= $json_read_contentdata ?>; //DBの投稿データ
        // const userData = <?=$json_login_user_data?>;
        const uid = <?= $uid ?>; //ユーザーID
        // console.log(userData);
        console.log(dataArray);
        console.log(uid);

        //update,delete用にid取り出し
        const content_idArray = [];     //content_tableのid用
        dataArray.forEach(function(value){
            const idTag = value.id;
            content_idArray.push(idTag);
        });

        //データ表示
        dataArray.forEach(function(value, key) {
            const timelineTag = `
                <article class = "timelineitem" id = "timelineitem">
                    <header>
                        ${value.accountname}
                    </header>
                    <div class = "timelinecontent">
                        <p class = "getday" id= "listgetday${key}"></p>
                        <ul>
                            <li>店　　　名：</li>
                            <li>エ　リ　ア：</li>
                            <li>カテゴリー：</li>
                            <li>評　　　価：</li>
                        </ul>
                        <ul>
                            <li id="listname${key}" class="listname"></li>
                            <li id="listarea${key}" class="listarea"></li>
                            <li id="listcategory${key}" class="listcategory"></li>
                            <li id="listevaluation${key}" class="listevaluation"></li>
                            <li class="uid" hidden>${uid}</li>
                            <li class="content_id" hidden>${value.id}</li>
                        </ul>
                        <ul>
                            <li>メモ</li>
                            <li><div id="listfreetext${key}" class="listfreetext"></div ></li>
                        </ul>
                        <ul>
                            <i class="fas fa-heart fa-2x favorite_icon" id="liste_favorite_icon${key}"></i>
                        </ul>
                    </div>
                </article>
                    `;
            //DBに保存した全データを表示（ホーム画面のタイムライン）
            $('#timeline').append(timelineTag);
            $('#listname' + key).text(value.shopname);
            $('#listgetday' + key).text(value.getday);
            $('#listarea' + key).text(value.area);
            $('#listcategory' + key).text(value.category);
            $('#listevaluation' + key).text(value.evaluation);
            $('#listfreetext' + key).text(value.freetext);
            //タイムラインのいいねの色判定
            if (uid == value.favorite_userid){
                $('#liste_favorite_icon' + key).addClass(value.class);
            }
            //DBに保存したデータのうちログインしているユーザーのデータのみ表示（マイページのタイムライン）
            const mypageTag = `
            <article class = "timelineitem mypagetimeline" id = "mypagetimelineitem">
                <header>
                    ${value.accountname}
                </header>
                <div class = "timelinecontent">
                    <p class = "getday" id= "mypagelistgetday${key}"></p>
                    <ul>
                        <li>店　　　名：</li>
                        <li>エ　リ　ア：</li>
                        <li>カテゴリー：</li>
                        <li>評　　　価：</li>
                    </ul>
                    <ul>
                        <li id="mypagelistname${key}" class="listname"></li>
                        <li id="mypagelistarea${key}" class="listarea"></li>
                        <li id="mypagelistcategory${key}" class="listcategory"></li>
                        <li id="mypagelistevaluation${key}" class="listevaluation"></li>
                        <li class="uid" hidden>${uid}</li>
                        <li class="content_id" hidden>${value.id}</li>
                    </ul>
                    <ul>
                        <li>メモ</li>
                        <li><div id="mypagelistfreetext${key}" class="listfreetext"></div ></li>
                    </ul>
                    <ul>
                        <i class="fas fa-heart fa-2x favorite_icon" id="mypage_favorite_icon${key}"></i>
                        <i class="far fa-edit fa-2x edit_icon" data-toggle="modal" data-target="#editModal${key}"></i>
                        <i class="far fa-trash-alt fa-2x delete_icon" data-toggle="modal" data-target="#deleteModal${key}"></i>
                    </ul>
                </div>
                <div class="modal fade" id="editModal${key}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <p><div class="modal-title" id="myModalLabel">ログの編集</div></p>
                            </div>
                            <div class="modal-body">
                                <label>評価</label>
                                <select class="form-control edit_evaluation" type="text">
                                    <option value="">選択</option>
                                    <option value="★★★★★">★★★★★</option>
                                    <option value="★★★★">★★★★</option>
                                    <option value="★★★">★★★</option>
                                    <option value="★★">★★</option>
                                    <option value="★">★</option>
                                </select>
                                <label>メモ</label>
                                <textarea class="form-control edit_freetext" maxlength="150" placeholder="150文字以内"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn default_btn" data-dismiss="modal">キャンセル</button>
                                <button type="button" class="btn edit_button" data-dismiss="modal" >更新</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="deleteModal${key}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <p><div class="modal-title" id="myModalLabel">削除確認画面</div></p>
                            </div>
                            <div class="modal-body">
                                <label>ログを削除しますか？</label>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn default_btn" data-dismiss="modal">キャンセル</button>
                                <button type="button" class="btn delete_btn">削除</button>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
            `;
            if (uid == value.userid) {
                $('#mypagetimeline').append(mypageTag);
                $('#mypagelistname' + key).text(value.shopname);
                $('#mypagelistgetday' + key).text(value.getday);
                $('#mypagelistarea' + key).text(value.area);
                $('#mypagelistcategory' + key).text(value.category);
                $('#mypagelistevaluation' + key).text(value.evaluation);
                $('#mypagelistfreetext' + key).text(value.freetext);
                if(uid == value.favorite_userid){
                    $('#mypage_favorite_icon' + key).addClass(value.class);
                }
            };
            //いいねしたデータを表示
            const favoriteTag = `
            <article class = "timelineitem favoritetimeline" id = "favoritetimelineitem">
                <header>
                    ${value.accountname}
                </header>
                <div class = "timelinecontent">
                    <p class = "getday" id= "favoritelistgetday${key}"></p>
                    <ul>
                        <li>店　　　名：</li>
                        <li>エ　リ　ア：</li>
                        <li>カテゴリー：</li>
                        <li>評　　　価：</li>
                    </ul>
                    <ul>
                        <li id="favoritelistname${key}" class="listname"></li>
                        <li id="favoritelistarea${key}" class="listarea"></li>
                        <li id="favoritelistcategory${key}" class="listcategory"></li>
                        <li id="favoritelistevaluation${key}" class="listevaluation"></li>
                        <li class="uid" hidden>${uid}</li>
                        <li class="content_id" hidden>${content_idArray[key]}</li>
                    </ul>
                    <ul>
                        <li>メモ</li>
                        <li><div id="favoritelistfreetext${key}" class="listfreetext"></div ></li>
                    </ul>
                    <ul>
                        <i class="fas fa-heart fa-2x favorite_icon" id=favorite_icon${key}></i>
                    </ul>
                </div>
            </article>
            `;
            if (uid == value.favorite_userid) {
                $('#favorite').append(favoriteTag);
                $('#favoritelistname' + key).text(value.shopname);
                $('#favoritelistgetday' + key).text(value.getday);
                $('#favoritelistarea' + key).text(value.area);
                $('#favoritelistcategory' + key).text(value.category);
                $('#favoritelistevaluation' + key).text(value.evaluation);
                $('#favoritelistfreetext' + key).text(value.freetext);
                $('#favorite_icon'+key).addClass(value.class);
            };
        })
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?v=3.33&language=ja&region=JP&key=&libraries=places&callback=initMap"></script>
</body>

</html>