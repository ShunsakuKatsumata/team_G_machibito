<!DOCTYPE html>
<html>
    <head> 
        <link rel="stylesheet" href="comment.css">
        <link rel="stylesheet" href="../sidebar/sidebar.css">
        <meta charset="UTF-8">
    </head>
    <body>
        <!-- サイドバー -->
        <?php include '../sidebar/sidebar.php'; ?>
        <div class="main-content">
        <!-- ここまで -->
        <!-- 質問 -->
            <div class="detail1">
                <div class="detail_q">
                    <h3 style="text-align:left; float:left;">質問</h3>
                    <div style="text-align:right; padding-top:20px;">12回答</div>
                    <br>
                    <table>
                        <tr>
                            <td><img class="user_icon" src="./image/user-icon1.png" width="40px" height="40px"><div class="user_name">&nbsp;morimoriさん</div></td>
                        </tr>
                        <tr>
                            <td>〇〇はどのようにすればいいですか？</td>
                        </tr>
                    </table>
                </div>
                <!-- コメント欄 -->
                <!-- 質問者の返信がある場合 -->
                <div class="detail_reply">
                    <h3>回答</h3>
                    <table>
                        <tr>
                            <td>このようにすればできる</td>
                        </tr>
                    </table>
                </div>
                <!-- コメント記入 -->
                <!-- <br> -->
                <div class="write_comment">
                    <!-- <label>コメント記入</label> -->
                    <p><textarea rows="3" cols="45" placeholder="質問に回答する"></textarea></p>
                    <p><button>送信</button></p>
                </div>
            </div>
    </body>
</html>


<!-- <tr align="left">
                <td id="a"></td>
            </tr>
            <tr align="right">
                <td>返信</td>
            </tr> -->