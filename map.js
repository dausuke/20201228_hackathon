$(function () {
    //マップ処理
    //マップ表示の制御
    $('.navi').on('click', function () {
        if (!$('#mymap').hasClass('active')) {
            $('#map').addClass('none');
        } else {
            $('#map').removeClass('none');
        }
    });
    let map;
    const marker = [];
    const infoWindow = [];
    function initMap() {
        $.ajax({
            type: 'POST',
            url: 'map_data.php',
        }).then(
            function (data) {
                const responsPosi = JSON.parse(data);
                console.log(responsPosi);
                const mapDataArray = [];
                responsPosi.forEach(function (value) {
                    const mapDataArrayTag = {
                        name: value.shopname,
                        category: value.category,
                        memo: value.freetext,
                        lat: parseFloat(value.lat),
                        lng: parseFloat(value.lng),
                    };
                    mapDataArray.push(mapDataArrayTag);
                });
                console.log(mapDataArray);
                // 現在地取得処理
                // 現在地を取得するときのオプション
                const option = {
                    enableHighAccuracy: true,
                    maximumAge: 20000,
                    timeout: 100000000,
                };
                // Geolocation APIに対応している
                if (navigator.geolocation) {
                    // 現在地を取得
                    navigator.geolocation.getCurrentPosition(
                        // 取得成功した場合
                        function (position) {
                            // 緯度・経度を変数に格納
                            const heremapLatLng = new google.maps.LatLng(
                                position.coords.latitude,
                                position.coords.longitude
                            );
                            // mapDataArray.push(heremapLatLng);
                            // マップオプションを変数に格納
                            const mapOptions = {
                                zoom: 15, // 拡大倍率
                                center: heremapLatLng, // 緯度・経度
                            };
                            // マップオブジェクト作成
                            map = new google.maps.Map(
                                document.getElementById('map'), // マップを表示する要素
                                mapOptions // マップオプション
                            );
                            const heremarker = new google.maps.Marker({
                                map: map, // 対象の地図オブジェクト
                                position: heremapLatLng, // 緯度・経度
                            });

                            mapDataArray.forEach(function (value, key) {
                                if (value.lat != 0 && value.lng != 0) {
                                    const mapLatLng = new google.maps.LatLng({
                                        lat: value.lat,
                                        lng: value.lng,
                                    });
                                    // マップにマーカーを表示する
                                    marker[key] = new google.maps.Marker({
                                        map: map, // 対象の地図オブジェクト
                                        position: mapLatLng, // 緯度・経度
                                        icon: {
                                            url:
                                                'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                                        },
                                    });
                                    // 吹き出しに表示する内容
                                    const infoTag = `
                                    <dl style="margin:0;">
                                        <dt style="font-weight: bold;">${value.name}</dt>
                                        <dd style="margin:0;">${value.category}</dd>
                                        <dd style="margin:0;">${value.memo}</dd>
                                    </dl>
                                `;
                                    //吹き出しの追加
                                    infoWindow[key] = new google.maps.InfoWindow({
                                        content: infoTag,
                                    });
                                    markerEvent(key); // マーカーにクリックイベントを追加
                                }
                            });
                            favoritepushmaker();
                        },
                        // 取得失敗した場合
                        function (error) {
                            // エラーメッセージを表示
                            switch (error.code) {
                                case 1: // PERMISSION_DENIED
                                    alert('位置情報の利用が許可されていません');
                                    break;
                                case 2: // POSITION_UNAVAILABLE
                                    alert('現在位置が取得できませんでした');
                                    break;
                                case 3: // TIMEOUT
                                    alert('タイムアウトになりました');
                                    break;
                                default:
                                    alert(
                                        'その他のエラー(エラーコード:' +
                                            error.code +
                                            ')'
                                    );
                                    break;
                            }
                        },
                        option
                    );
                    // Geolocation APIに対応していない
                } else {
                    alert('この端末では位置情報が取得できません');
                }
            },
            function () {
                alert('読み込み失敗');
            }
        );
    }
    //マーカーにクリックイベントを追加
    function markerEvent(key) {
        marker[key].addListener('click', function () {
            // マーカーをクリックしたときに吹き出しの追加
            infoWindow[key].open(map, marker[key]);
        });
    }

    //いいねを押した投稿にもマーカーと吹き出し表示
    const favoriteMarker = [];
    const favoriteInfoWindow = [];
    function favoritepushmaker() {
        $.ajax({
            //POST通信
            type: 'POST',
            url: 'favorite_map.php',
        }).then(
            // 通信成功時
            function (data) {
                const favorite_markerdata = JSON.parse(data);
                console.log(favorite_markerdata);
                const favoriteMarkerArray = [];
                favorite_markerdata.forEach(function (value) {
                    const favoriteMarkerTag = {
                        name: value.shopname,
                        category: value.category,
                        memo: value.freetext,
                        lat: parseFloat(value.lat),
                        lng: parseFloat(value.lng),
                    };
                    favoriteMarkerArray.push(favoriteMarkerTag);
                });
                favoriteMarkerArray.forEach(function (value, key) {
                    const favoriteLatLng = new google.maps.LatLng({
                        lat: value.lat,
                        lng: value.lng,
                    });
                    // マップにマーカーを表示する
                    favoriteMarker[key] = new google.maps.Marker({
                        map: map, // 対象の地図オブジェクト
                        position: favoriteLatLng, // 緯度・経度
                        icon: {
                            url:
                                ' https://maps.google.com/mapfiles/ms/icons/yellow-dot.png',
                        },
                    });
                    // 吹き出しに表示する内容
                    const infoTag = `
                                    <dl style="margin:0;">
                                        <dt style="font-weight: bold;">${value.name}</dt>
                                        <dd style="margin:0;">${value.category}</dd>
                                        <dd style="margin-bottom:10px;">${value.memo}</dd>
                                        <dd style="margin:0;">保存済みデータ</dd>
                                    </dl>
                                `;
                    //吹き出しの追加
                    favoriteInfoWindow[key] = new google.maps.InfoWindow({
                        content: infoTag,
                    });
                    // マーカーにクリックイベントを追加
                    favoriteMarkerEvent(key);
                });
            },
            // 通信失敗時
            function () {
                alert('読み込み失敗');
            }
        );
    }

    //マーカーにクリックイベントを追加
    function favoriteMarkerEvent(key) {
        favoriteMarker[key].addListener('click', function () {
            // マーカーをクリックしたときに吹き出しの追加
            favoriteInfoWindow[key].open(map, favoriteMarker[key]);
        });
    }

    //マップ表示
    window.onload = function () {
        initMap();
    };
});
