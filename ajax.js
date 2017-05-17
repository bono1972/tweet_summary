$(function () {
    //テキストエリア監視用変数
    var text = "";
    // Ajax通信を開始する
    $.ajax({
        url: 'main.php',
        type: 'post', // getかpostを指定(デフォルトは前者)
        dataType: 'html', // 「json」を指定するとresponseがJSONとしてパースされたオブジェクトになる
        /*
        data: { // 送信データを指定(getの場合は自動的にurlの後ろにクエリとして付加される)
            message: $('#massage').val()
        }
        */
    })
    // ・ステータスコードは正常で、dataTypeで定義したようにパース出来たとき
    .done(function (response) {
        //console.log(response);
        $('#answer').append(response);
    })
    // ・サーバからステータスコード400以上が返ってきたとき
    // ・ステータスコードは正常だが、dataTypeで定義したようにパース出来なかったとき
    // ・通信に失敗したとき
    .fail(function () {
        $('#answer').append('<p>失敗です・・・</p>');
    });

    //logout処理
    $(document).on('click','#logout',function () {
        $.ajax({
            url: 'logout.php',
            type: 'post',
            dataType: 'html',
            
        })
        .done(function (response) {
            $('#answer').empty();
            $('#logout_area').append(response);
        })
        .fail(function () {
            $('#logout_area').append('<p>失敗です・・・</p>');
        });
    });
    //ツイート処理
    $(document).on('click','#submit',function () {
        console.log(text);
        $.ajax({
            url: 'tweet.php',
            type: 'post',
            dataType: 'html',
            data: {'data' : text},          
        })
        .done(function (response) {
            $('#answer').empty();
            $('#tweet_area').append(response);
        })
        .fail(function () {
            $('#tweet_area').append('<p>失敗です・・・</p>');
        });
    });
    //テキストエリア変化監視
    $(document).on('change','#textarea',function () {
        text = $(this).val();
        //console.log(text);
        //$('#test_area').text(text);
    });

});