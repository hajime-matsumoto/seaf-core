Seaf Coreライブラリ
==================

主なクラス
------------------
* Seaf
* Environment\Environment
* Loader\AutoLoader

コンポーネント
-----------------
ネームスペースをSeaf\\Componentにしてクラスを作成すると
	$environment->コンポーネント名( ) で呼び出せる

以下のようにしてコンポーネントの使用ネームスペース登録できる
	$environment->addComponentNamespace( 'Seaf\\Component' );

ファイルをオートロードしたければ、オートローダに登録しておく
	/**
	 * Components
	 */
	$loader->addNamespace(
		'Seaf\\Component\\',
		null,
		dirname(__FILE__).'/components'
	);
