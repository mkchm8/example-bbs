# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## リポジトリ概要

Laravel 9.xでドメイン駆動設計（DDD）を実装した掲示板システム（BBS）のボイラープレート。クリーンアーキテクチャの原則に従い、ドメインロジックとインフラストラクチャの関心事を分離している。

## 必須コマンド

### 開発環境
```bash
# 開発環境の起動（Laravel Sail経由のDocker）
./vendor/bin/sail up

# 環境の停止
./vendor/bin/sail down

# アプリケーションシェルへのアクセス
./vendor/bin/sail shell
```

### テスト実行
```bash
# 全テストの実行
./vendor/bin/sail php artisan test

# 特定のテストファイルの実行
./vendor/bin/sail php artisan test tests/Unit/Repositories/Eloquent/PostRepositoryTest.php

# カバレッジ付きテスト実行
./vendor/bin/sail php artisan test --coverage
```

### コード品質チェック
```bash
# PHPStan実行（レベル2の静的解析）
./vendor/bin/phpstan analyse app -l 2

# PHP CodeSniffer実行（PSR-12準拠）
./vendor/bin/phpcs --standard=phpcs.xml

# PHP CodeSnifferの自動修正
./vendor/bin/phpcbf --standard=phpcs.xml
```

### データベース操作
```bash
# マイグレーション実行
./vendor/bin/sail php artisan migrate

# マイグレーションのロールバック
./vendor/bin/sail php artisan migrate:rollback

# データベースリフレッシュ（シーディング付き）
./vendor/bin/sail php artisan migrate:fresh --seed
```

## アーキテクチャ概要

### クリーンアーキテクチャの実装

1. **ドメイン層** (`app/Domain/`)
   - ビジネスロジックを含む純粋なドメインエンティティ（Post、Comment）
   - ファクトリメソッド：新規作成用の`create()`、再構築用の`reConstruct()`
   - ステータス管理用のEnum
   - ビジネスルールのカプセル化（例：`hasFullComment()`メソッド）

2. **リポジトリパターン**
   - **インターフェース** (`app/Repositories/`): データアクセスの契約を定義
   - **Eloquent実装** (`app/Repositories/Eloquent/`): Eloquent ORMを使用した具体的な実装
   - **Storage実装** (`app/Repositories/Storage/`): ファイルアップロード処理
   - `RepositoryServiceProvider`でのリポジトリバインディング

3. **データアクセス層** (`app/DataAccess/Eloquent/`)
   - ドメインエンティティとは分離されたEloquentモデル
   - データベース永続化の関心事を処理

4. **アプリケーションサービス** (`app/Usecases/`)
   - ユースケースのオーケストレーション（例：`PostApplicationService`）
   - リポジトリとドメインロジックの調整

### 主要なアーキテクチャ上の決定事項

- **ドメインと永続化の分離**: ドメインエンティティはEloquentモデルから独立
- **リポジトリパターン**: すべてのデータアクセスはリポジトリインターフェース経由
- **ファクトリメソッド**: ドメインエンティティは静的ファクトリメソッドで構築
- **明示的なステータス管理**: 投稿とコメントのステータスにEnumを使用
- **APIバージョニング**: バージョンプレフィックス付きの構造化されたAPIルート（`/api/v1/`）

## 開発上の注意点

### テスト戦略
- リポジトリテストは実際のデータベースをマイグレーション付きで使用
- 各テストはロールバックされるトランザクション内で実行
- テストデータ生成にはファクトリを使用

### GitHub Actions CI
継続的インテグレーションの設定：
- プッシュとプルリクエストごとに実行
- PHPUnitテストの実行
- PHP_CodeSnifferでコードスタイルチェック
- PHPStanで静的解析

### 技術的な検討事項
- クエリサービスパターンの導入を計画中
- パラメータバリデーションの改善が必要（名前付き引数やパラメータオブジェクトを検討）
- Eloquentモデルの名前空間の明確化（エイリアスの使用）

### データベーススキーマ
- **posts**: ステータス、タイトル、本文を持つメインコンテンツ
- **comments**: 外部キー制約付きで投稿に関連
- **users**: 標準的なLaravel認証
- 投稿とコメントの両方でステータスベースのワークフローを使用

### Docker構成
開発環境に含まれるサービス：
- PHP 8.1アプリケーションコンテナ
- MySQL 8.0データベース
- Redisキャッシュ
- Meilisearch全文検索
- Mailhogメールテスト
- Seleniumブラウザテスト