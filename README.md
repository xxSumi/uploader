# Name

phpUploader

## Description

サーバーに設置するだけで使える簡易PHPアップローダー。

## Requirement

・PHP Version 5.3.3+
・SQLite (PHPにバンドルされたもので可、一部の環境によってはphp○-sqliteのインストールが必要。)

## Usage

簡易なアップローダーなので以下の利用を想定。
・少人数且つ、不特定多数ではない利用者間でのファイルのやり取り

## Install


②config/config.phpを任意の値で編集。

③設置したディレクトリにapacheまたはnginxの実行権限を付与。

④この状態でサーバーに接続するとDBファイル(既定値 ./db/uploader.db)とデータ設置用のディレクトリ(既定値 ./data)が作成される。

⑤configディレクトリとデータ設置用のディレクトリ(既定値 ./data)に.htaccessなどを用いて外部からの接続を遮断させる。

⑥ファイルがアップロードできるよう、PHPとapacheまたはnginxの設定を変更。
