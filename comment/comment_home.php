<!DOCTYPE html>
<html>
    <head> 
        <link rel="stylesheet" href="comment.css">
        <meta charset="UTF-8">
    </head>
    <body>
        <div class="comment_home">
            <!-- <header>
                <h3>タイトル</h3>
                <div class="search-box">
                    <input type="text">
                    <img src="">
                </div>
                
            </header> -->
            <ul class="menu">
                <li class="menu-item_red">回答募集中&nbsp;|&nbsp;</li>
                <li class="menu-item_blue">解決済み&nbsp;|&nbsp;</li>
                <li class="menu-item_blue">カテゴリー</li>
                <li><input class="menu-item_q" type="button" onclick="location.href='./comment_post.php'" value="質問する"></li>
                <!-- <li ></li> -->
            </ul>
            <P><ul class="menu_sort">
                <li style="float:left;"><img src="./image/icons8-sort.png"/></li>
                <select name="pulldown1">
                    <option>投稿が新しい順</option>
                    <option>投稿が古い順</option>
                    <option>回答が多い順</option>
                </select>
            </ul></P>
            <hr>
            <?php
                require_once __DIR__.'/';
            ?>
            <table id="comment_home_item" align="center">
                <tr>
                    <td>
                        <button class="list_button" onclick="location.href='comment_detail.html'">
                            <div><span class="language">Python</span></div>
                            <div><span class="post">投稿１</span></div>
                            <div class="date">4/17 21:30</div>
                            <div class="comment_number">コメント12個</div>
                        </button>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>

<!-- <a target="_blank" href="https://icons8.com/icon/103378/descending-sorting">ソート</a> アイコン by <a target="_blank" href="https://icons8.com">Icons8</a> -->

<!-- <tr align="left">
                <td id="a"></td>
            </tr>
            <tr align="right">
                <td>返信</td>
            </tr> -->