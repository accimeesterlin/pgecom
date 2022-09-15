<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validasyon
    |--------------------------------------------------------------------------
    |
    |
    */

    'accepted' => 'Atribi : dwe aksepte.',
    'active_url' => ':attribute a se pa yon URL ki valab.',
    'after' => ':attribute a dwe yon dat apre :date.',
    'after_or_equal' => ':attribute a dwe yon dat apre oswa egal a :date.',
    'alpha' => ':atribi a ka sèlman genyen lèt.',
    'alpha_dash' => 'Atribi : la gendwa genyen sèlman lèt, nimewo, tire ak tirè.',
    'alpha_num' => 'Atribi : la kapab genyen sèlman lèt ak nimewo.',
    'array' => ':attribute a dwe yon etalaj.',
    'before' => ':attribute a dwe yon dat anvan :date.',
    'before_or_equal' => ':attribute a dwe yon dat anvan oswa egal a :date.',
    'between' => [
        'numeric' => ':attribute a dwe ant :min ak :max.',
        'file' => ':attribute a dwe ant :min ak :max kilobytes.',
        'string' => ':atribi a dwe ant karaktè :min ak :max.',
        'array' => ':attribute a dwe genyen ant :min ak :max atik.',
    ],
    'boolean' => 'Champ :attribute a dwe vre oswa fo.',
    'confirmed' => 'Konfimasyon :attribute a pa matche.',
    'date' => ':attribute a pa yon dat valab.',
    'date_equals' => ':attribute a dwe yon dat egal a :date.',
    'date_format' => ':attribute a pa matche ak fòma :format la.',
    'different' => ':atribi ak :lòt dwe diferan.',
    'digits' => ':attribute a dwe :chifs chif.',
    'digits_between' => ':atribi a dwe ant chif :min ak :max.',
    'dimensions' => ':atribi a gen dimansyon imaj ki pa valab.',
    'distinct' => 'Jaden :attribute a gen yon valè kopi.',
    'email' => ':attribute a dwe yon adrès imel ki valab.',
    'ends_with' => ':atribi a dwe fini ak youn nan sa ki annapre yo: :valè.',
    'exists' => 'Atribi yo chwazi a pa valab.',
    'file' => ':attribute a dwe yon dosye.',
    'filled' => 'Jaden :attribute a dwe gen yon valè.',
    'gt' => [
        'numeric' => ':attribute a dwe pi gran pase :value.',
        'file' => ' :attribute a dwe pi gran pase :value kilobytes.',
        'string' => ':attribute a dwe pi gran pase karaktè :value.',
        'array' => ':attribute a dwe gen plis pase :valè atik.',
    ],
    'gte' => [
        'numeric' => ':attribute a dwe pi gran pase oswa egal :valè.',
        'file' => ':attribute a dwe pi gran pase oswa egal :valè kilobytes.',
        'string' => ':atribi a dwe pi gran pase karaktè :valè oswa egal.',
        'array' => ':attribute a dwe genyen :valè atik oswa plis.',
    ],
    'image' => ':attribute a dwe yon imaj.',
    'in' => 'Atribi yo chwazi a pa valab.',
    'in_array' => 'Jaden :attribute a pa egziste nan :other.',
    'integer' => ': atribi a dwe yon nonb antye relatif.',
    'ip' => 'Atribi : la dwe yon adrès IP valab.',
    'ipv4' => ':attribute a dwe yon adrès IPv4 valab.',
    'ipv6' => ':attribute a dwe yon adrès IPv6 valab.',
    'json' => ':attribute a dwe yon kòd JSON valab.',
    'lt' => [
        'numeric' => ':attribute a dwe mwens pase :value.',
        'file' => ':attribute a dwe mwens pase :value kilobytes.',
        'string' => ':attribute a dwe mwens pase karaktè :value.',
        'array' => ':attribute a dwe gen mwens pase :valè atik.',
    ],
    'lte' => [
        'numeric' => ':attribute a dwe mwens pase oswa egal :valè.',
        'file' => ':attribute a dwe mwens pase oswa egal :valè kilobytes.',
        'string' => ':attribute a dwe gen mwens pase oswa egal :valè karaktè.',
        'array' => ':attribute a pa dwe gen plis pase :valè atik.',
    ],
    'max' => [
        'numeric' => ':attribute a pa ka pi gran pase :max.',
        'file' => ':attribute a pa ka pi gran pase :max kilobytes.',
        'string' => ':attribute a pa ka pi gran pase karaktè :max.',
        'array' => ':attribute a pa ka gen plis pase :max atik.',
    ],
    'mimes' => ':atribi a dwe yon fichye ki gen kalite: :valè.',
    'mimetypes' => ':atribi a dwe yon fichye ki gen kalite: :valè.',
    'min' => [
        'numeric' => ':attribute a dwe omwen :min.',
        'file' => ':attribute a dwe omwen :min kilobyte.',
        'string' => ': atribi a dwe omwen : min karaktè.',
        'array' => ':attribute a dwe genyen omwen :min atik.',
    ],
    'not_in' => 'Atribi yo chwazi a pa valab.',
    'not_regex' => 'Fòma :attribute a pa valab.',
    'numeric' => ':atribi a dwe yon nimewo.',
    'password' => 'Modpas la pa kòrèk.',
    'password_or_username' => 'Modpas la oswa non itilizatè a pa kòrèk.',
    'present' => 'Champ :attribute dwe prezan.',
    'regex' => 'Fòma :attribute a pa valab.',
    'required' => 'Se jaden an :attribute obligatwa.',
    'required_if' => 'Champ :attribute a obligatwa lè :other se :valè.',
    'required_unless' => 'Champ :attribute a obligatwa sof si :other nan :valè.',
    'required_with' => 'Jaden :attribute a obligatwa lè :values ​​prezan.',
    'required_with_all' => 'Champ :attribute a obligatwa lè :values ​​yo prezan.',
    'required_without' => 'Champ :attribute a obligatwa lè :values ​​pa prezan.',
    'required_without_all' => 'Champ :attribute a obligatwa lè pa gen youn nan :valè ki prezan.',
    'same' => ':attribute a ak :other dwe matche.',
    'size' => [
        'numeric' => ':attribute a dwe :size.',
        'file' => ':attribute a dwe :size kilobytes.',
        'string' => ':attribute a dwe :size karaktè.',
        'array' => ':atribi a dwe genyen atik :size.',
    ],
    'starts_with' => ':atribi a dwe kòmanse ak youn nan sa ki annapre yo: :valè.',
    'string' => ':atribi a dwe yon fisèl.',
    'timezone' => ':atribi a dwe yon zòn valab.',
    'unique' => 'Atribi : te deja pran.',
    'uploaded' => 'Atribi : la pa t kapab telechaje.',
    'url' => 'Fòma :attribute a pa valab.',
    'uuid' => ':attribute a dwe yon UUID valab.',

    /*
    |--------------------------------------------------------------------------
    | Liy langaj Validasyon Custom
    |--------------------------------------------------------------------------
    |
    | Isit la ou ka presize mesaj validation koutim pou atribi lè l sèvi avèk la
    | konvansyon "attribute.rule" pou bay non liy yo. Sa fè li rapid
    | espesifye yon liy lang espesifik koutim pou yon règ atribi bay yo.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'koutim-mesaj',
        ],
    ],

    'captcha' => 'Kaptcha ki pa kòrèk...',
    /*
    |--------------------------------------------------------------------------
    | Atribi Validasyon Custom
    |--------------------------------------------------------------------------
    |
    | Liy lang sa yo yo itilize pou chanje plas anplasman atribi nou an
    | ak yon bagay ki pi zanmitay lektè tankou "E-Mail Adrès" pito
    | nan "imel". Sa tou senpleman ede nou fè mesaj nou an pi ekspresyon.
    |
    */

    'attributes' => [],

];