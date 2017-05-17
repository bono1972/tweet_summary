$(function () {
    //テキストエリア監視用変数
    var text = "";
    // Ajax通信を開始する
    $.ajax({
        url: 'main.php',
        type: 'post', // getかpostを指定(デフォルトは前者)
        dataType: 'html', // 「json」を指定するとresponseがJSONとしてパースされたオブジェクトになる
    })
    // ・ステータスコードは正常で、dataTypeで定義したようにパース出来たとき
    .done(function (response) {
        //console.log(response);
        $('#answer').append(response);
        //textareaに予めフォーカスを当てておき、文字列に変更なくツイートする場合も
        //テキストエリア変化監視のblurによって変数textの内容を取得する
        $('#textarea').focus();
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
    $(document).on('blur change','#textarea',function () {
        text = $(this).val();
        //console.log(text);
    });

});