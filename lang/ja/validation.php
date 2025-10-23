<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attributeを承認してください。',
    'accepted_if' => ':otherが:valueの場合、:attributeを承認してください。',
    'active_url' => ':attributeは有効なURLではありません。',
    'after' => ':attributeは:dateより後の日付を指定してください。',
    'after_or_equal' => ':attributeは:date以降の日付を指定してください。',
    'alpha' => ':attributeは英字のみ使用できます。',
    'alpha_dash' => ':attributeは英数字とダッシュ(-)、アンダースコア(_)のみ使用できます。',
    'alpha_num' => ':attributeは英数字のみ使用できます。',
    'array' => ':attributeは配列である必要があります。',
    'ascii' => ':attributeは半角英数字と記号のみ使用できます。',
    'before' => ':attributeは:dateより前の日付を指定してください。',
    'before_or_equal' => ':attributeは:date以前の日付を指定してください。',
    'between' => [
        'array' => ':attributeは:min個から:max個までの項目を指定してください。',
        'file' => ':attributeは:min KBから:max KBまでのファイルを選択してください。',
        'numeric' => ':attributeは:minから:maxまでの数値を指定してください。',
        'string' => ':attributeは:min文字から:max文字までで入力してください。',
    ],
    'boolean' => ':attributeはtrueかfalseを指定してください。',
    'can' => ':attributeには許可されていない値が含まれています。',
    'confirmed' => ':attributeの確認が一致しません。',
    'current_password' => 'パスワードが正しくありません。',
    'date' => ':attributeは有効な日付ではありません。',
    'date_equals' => ':attributeは:dateと同じ日付である必要があります。',
    'date_format' => ':attributeは:format形式で入力してください。',
    'decimal' => ':attributeは:decimal桁の小数である必要があります。',
    'declined' => ':attributeを拒否してください。',
    'declined_if' => ':otherが:valueの場合、:attributeを拒否してください。',
    'different' => ':attributeと:otherは異なる値である必要があります。',
    'digits' => ':attributeは:digits桁である必要があります。',
    'digits_between' => ':attributeは:min桁から:max桁までである必要があります。',
    'dimensions' => ':attributeの画像サイズが無効です。',
    'distinct' => ':attributeに重複した値があります。',
    'doesnt_end_with' => ':attributeは次の値で終わってはいけません: :values',
    'doesnt_start_with' => ':attributeは次の値で始まってはいけません: :values',
    'email' => ':attributeは有効なメールアドレスである必要があります。',
    'ends_with' => ':attributeは次の値のいずれかで終わる必要があります: :values',
    'enum' => '選択された:attributeは無効です。',
    'exists' => '選択された:attributeは無効です。',
    'file' => ':attributeはファイルである必要があります。',
    'filled' => ':attributeは値が必要です。',
    'gt' => [
        'array' => ':attributeは:value個より多い項目が必要です。',
        'file' => ':attributeは:value KBより大きいファイルである必要があります。',
        'numeric' => ':attributeは:valueより大きい値である必要があります。',
        'string' => ':attributeは:value文字より多い文字数である必要があります。',
    ],
    'gte' => [
        'array' => ':attributeは:value個以上の項目が必要です。',
        'file' => ':attributeは:value KB以上のファイルである必要があります。',
        'numeric' => ':attributeは:value以上の値である必要があります。',
        'string' => ':attributeは:value文字以上である必要があります。',
    ],
    'image' => ':attributeは画像である必要があります。',
    'in' => '選択された:attributeは無効です。',
    'in_array' => ':attributeは:otherに存在しません。',
    'integer' => ':attributeは整数である必要があります。',
    'ip' => ':attributeは有効なIPアドレスである必要があります。',
    'ipv4' => ':attributeは有効なIPv4アドレスである必要があります。',
    'ipv6' => ':attributeは有効なIPv6アドレスである必要があります。',
    'json' => ':attributeは有効なJSON文字列である必要があります。',
    'lowercase' => ':attributeは小文字である必要があります。',
    'lt' => [
        'array' => ':attributeは:value個未満の項目である必要があります。',
        'file' => ':attributeは:value KB未満のファイルである必要があります。',
        'numeric' => ':attributeは:value未満の値である必要があります。',
        'string' => ':attributeは:value文字未満である必要があります。',
    ],
    'lte' => [
        'array' => ':attributeは:value個以下の項目である必要があります。',
        'file' => ':attributeは:value KB以下のファイルである必要があります。',
        'numeric' => ':attributeは:value以下の値である必要があります。',
        'string' => ':attributeは:value文字以下である必要があります。',
    ],
    'mac_address' => ':attributeは有効なMACアドレスである必要があります。',
    'max' => [
        'array' => ':attributeは:max個以下の項目である必要があります。',
        'file' => ':attributeは:max KB以下のファイルである必要があります。',
        'numeric' => ':attributeは:max以下の値である必要があります。',
        'string' => ':attributeは:max文字以下である必要があります。',
    ],
    'max_digits' => ':attributeは:max桁以下である必要があります。',
    'mimes' => ':attributeは:valuesタイプのファイルである必要があります。',
    'mimetypes' => ':attributeは:valuesタイプのファイルである必要があります。',
    'min' => [
        'array' => ':attributeは:min個以上の項目が必要です。',
        'file' => ':attributeは:min KB以上のファイルである必要があります。',
        'numeric' => ':attributeは:min以上の値である必要があります。',
        'string' => ':attributeは:min文字以上である必要があります。',
    ],
    'min_digits' => ':attributeは:min桁以上である必要があります。',
    'missing' => ':attributeフィールドは存在してはいけません。',
    'missing_if' => ':otherが:valueの場合、:attributeフィールドは存在してはいけません。',
    'missing_unless' => ':otherが:valueでない場合、:attributeフィールドは存在してはいけません。',
    'missing_with' => ':valuesが存在する場合、:attributeフィールドは存在してはいけません。',
    'missing_with_all' => ':valuesが存在する場合、:attributeフィールドは存在してはいけません。',
    'multiple_of' => ':attributeは:valueの倍数である必要があります。',
    'not_in' => '選択された:attributeは無効です。',
    'not_regex' => ':attributeの形式が無効です。',
    'numeric' => ':attributeは数値である必要があります。',
    'password' => [
        'letters' => ':attributeは少なくとも1つの文字を含む必要があります。',
        'mixed' => ':attributeは少なくとも1つの大文字と1つの小文字を含む必要があります。',
        'numbers' => ':attributeは少なくとも1つの数字を含む必要があります。',
        'symbols' => ':attributeは少なくとも1つの記号を含む必要があります。',
        'uncompromised' => '指定された:attributeはデータ漏洩に含まれています。別の:attributeを選択してください。',
    ],
    'present' => ':attributeフィールドが存在する必要があります。',
    'prohibited' => ':attributeフィールドは禁止されています。',
    'prohibited_if' => ':otherが:valueの場合、:attributeフィールドは禁止されています。',
    'prohibited_unless' => ':otherが:valuesにない場合、:attributeフィールドは禁止されています。',
    'prohibits' => ':attributeフィールドは:otherの存在を禁止します。',
    'regex' => ':attributeの形式が無効です。',
    'required' => ':attributeは必須です。',
    'required_array_keys' => ':attributeフィールドには:valuesのエントリが含まれている必要があります。',
    'required_if' => ':otherが:valueの場合、:attributeは必須です。',
    'required_if_accepted' => ':otherが承認された場合、:attributeは必須です。',
    'required_unless' => ':otherが:valuesにない場合、:attributeは必須です。',
    'required_with' => ':valuesが存在する場合、:attributeは必須です。',
    'required_with_all' => ':valuesが存在する場合、:attributeは必須です。',
    'required_without' => ':valuesが存在しない場合、:attributeは必須です。',
    'required_without_all' => ':valuesのいずれも存在しない場合、:attributeは必須です。',
    'same' => ':attributeと:otherは一致する必要があります。',
    'size' => [
        'array' => ':attributeは:size個の項目を含む必要があります。',
        'file' => ':attributeは:size KBである必要があります。',
        'numeric' => ':attributeは:sizeである必要があります。',
        'string' => ':attributeは:size文字である必要があります。',
    ],
    'starts_with' => ':attributeは次の値のいずれかで始まる必要があります: :values',
    'string' => ':attributeは文字列である必要があります。',
    'timezone' => ':attributeは有効なタイムゾーンである必要があります。',
    'unique' => ':attributeは既に使用されています。',
    'uploaded' => ':attributeのアップロードに失敗しました。',
    'uppercase' => ':attributeは大文字である必要があります。',
    'url' => ':attributeは有効なURLである必要があります。',
    'ulid' => ':attributeは有効なULIDである必要があります。',
    'uuid' => ':attributeは有効なUUIDである必要があります。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "rule.attribute" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => '名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'password_confirmation' => 'パスワード確認',
        'current_password' => '現在のパスワード',
        'phone' => '電話番号',
        'address' => '住所',
        'postal_code' => '郵便番号',
        'prefecture' => '都道府県',
        'city' => '市区町村',
        'address_line1' => '住所1',
        'address_line2' => '住所2',
        'full_name' => '氏名',
        'payment_method' => '支払い方法',
        'shipping_address_id' => '配送先住所',
        'quantity' => '数量',
        'product_id' => '商品',
        'notes' => '備考',
        'admin_notes' => '管理者メモ',
        'order_status' => '注文ステータス',
        'payment_status' => '支払いステータス',
        'title' => 'タイトル',
        'description' => '説明',
        'price' => '価格',
        'category_id' => 'カテゴリー',
        'is_active' => 'アクティブ',
        'is_featured' => 'おすすめ',
        'stock_quantity' => '在庫数',
        'sku' => 'SKU',
        'weight' => '重量',
        'dimensions' => 'サイズ',
        'image' => '画像',
        'slug' => 'スラッグ',
        'sort_order' => '並び順',
        'is_visible' => '表示',
        'parent_id' => '親カテゴリー',
    ],

]; 