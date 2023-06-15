# 類人猿 Snow Monkeyスキン 企業サイト向けパターン用 A
企業サイト向けパターン（R001）向けのSnow Monkeyスキンです

## このプラグイン動作の前提条件
- Snow Monkeyテーマがインストールされてない環境では動きません

# SCSSのコンパイル方法
当プラグインディレクトリーまで移動したあと、

- npm i でpackegeをインストール
- npm run watch でSCSSファイルの修正を常時監視（SCSSを修正したら即時CSSにコンパイルしてくれる）
- npm run build でCSSにコンパイル（コマンド走ったときだけCSSをコンパイル）

## composerのインストール方法
当プラグインディレクトリーまで移動したあと、

- composer install でパッケージをインストール

# 変更履歴
## 1.8.2
- 各投稿ページ（single）の関連記事の表示がコンテンツ幅より狭くなってしまう不具合について修正
## 1.8.1
- ゴーストボタンのhoverについて背景色が設定されてない不具合について修正
## 1.8.0
- Snow Monkey v19の対応
- Nodeパッケージの更新
## 1.7.0
- Yarn v1からv3にアップグレード
- 企業スキンに更新アラートボックスメッセージ表示機能を追加
## 1.6.0
- Node.jsパッケージのアップグレード
- GitHub Actionsのトリガー調整
## 1.5.0
- wp-env環境の導入
- BackstopJSの導入

## 1.4.1
- プラグインファイルのバージョン番号の変更（永久アップデート通知されてしまう現象の対応）

## 1.4.0
- Snow Monkey Blocks ステップブロックの数字表記が表示されない不具合の対応
- Text domainの呼び出しタイミングの修正
- アクティベートチェックのタイミングなどを修正
- README.md内のコマンド表記間違えの修正

## 1.3.0
- セクションブロックのリード文の表示位置がおかしい問題の対応
- 編集画面にskin用のスタイルを読み込むように修正
- バージョン番号の修正（2桁繰り上げなど）

## 0.0.0.2
- ボタンのHoverスタイル用のclassを追加

## 0.0.0.1
- 製品版リリース
