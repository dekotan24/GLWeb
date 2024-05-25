# GLWeb
## Game Launcher Web Viewer

このファイルは、[Game Launcher](https://github.com/dekotan24/glc_cs/)のWebビューアです。

各自の環境に合わせて、以下の設定を変更してください。

| 変数名 | 概要 |
|----- | ----- |
| $DBHost | データベース接続ホスト |
| $DBName | データベースの名前 |
| $DBTable | データベースのテーブル名 |
| $DBUser | データベースの接続ユーザ名 |
| $DBPass | データベースの接続パスワード |


SQLインジェクション対策は行いましたが、抜け穴があるかもしれませんので、各自でコードを確認・編集するか、ユーザをReadOnlyで作成してください。
