<?php
// config/l5-swagger.php

return [
    'default' => 'default',

    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'Book Library API',
            ],

            'routes' => [
                'api' => 'api/documentation',
            ],

            'paths' => [
                /*
                |--------------------------------------------------------------
                | List EITHER a parent directory OR its children — never both.
                | app/Swagger already contains Schemas/ and Responses/ as
                | subdirectories, so listing it once is sufficient.
                |--------------------------------------------------------------
                */
                'annotations' => [
                    base_path('app/Http/Controllers/Api'), // controllers
                    base_path('app/Swagger'),              // all swagger definitions
                ],

                'excludes' => [
                    // Marker interfaces contain no annotations — skip them
                    base_path('app/Swagger/Responses/SwaggerResponseDefinition.php'),
                    base_path('app/Swagger/Schemas/SwaggerSchemaDefinition.php'),
                ],

                'docs'                   => storage_path('api-docs'),
                'docs_json'              => 'api-docs.json',
                'docs_yaml'              => 'api-docs.yaml',
                'format_to_use_for_docs' => env('L5_FORMAT_TO_USE_FOR_DOCS', 'json'),
                'base'                   => env('L5_SWAGGER_BASE_PATH', null),
                'swagger_ui_assets_path' => env(
                    'L5_SWAGGER_UI_ASSETS_PATH',
                    'vendor/swagger-api/swagger-ui/dist/'
                ),
                'fill_missing_variables' => false,
            ],
        ],
    ],

    'defaults' => [
        'routes' => [
            'docs'            => 'docs',
            'oauth2_callback' => 'api/oauth2-callback',
            'middleware'      => [
                'api'    => [],
                'asset'  => [],
                'docs'   => [],
                'oauth2' => [],
            ],
            'group_options' => [],
        ],

        'paths' => [
            'docs'                   => storage_path('api-docs'),
            'docs_json'              => 'api-docs.json',
            'docs_yaml'              => 'api-docs.yaml',
            'format_to_use_for_docs' => env('L5_FORMAT_TO_USE_FOR_DOCS', 'json'),
            'swagger_ui_assets_path' => env(
                'L5_SWAGGER_UI_ASSETS_PATH',
                'vendor/swagger-api/swagger-ui/dist/'
            ),
        ],

        'scanOptions' => [
            'exclude' => [
                base_path('app/Swagger/Responses/SwaggerResponseDefinition.php'),
                base_path('app/Swagger/Schemas/SwaggerSchemaDefinition.php'),
            ],
            'open_api_spec_version' => env(
                'L5_SWAGGER_SPEC_VERSION',
                \L5Swagger\Generator::OPEN_API_DEFAULT_SPEC_VERSION
            ),
        ],

        'securityDefinitions' => [
            'securitySchemes' => [],
            'security'        => [],
        ],

        'generate_always'      => env('L5_SWAGGER_GENERATE_ALWAYS', false),
        'generate_yaml_copy'   => env('L5_SWAGGER_GENERATE_YAML_COPY', false),
        'proxy'                => false,
        'additional_config_url'=> null,
        'operations_sort'      => env('L5_SWAGGER_OPERATIONS_SORT', null),
        'validator_url'        => null,

        'ui' => [
            'display' => [
                'doc_expansion'          => env('L5_SWAGGER_UI_DOC_EXPANSION', 'none'),
                'filter'                 => env('L5_SWAGGER_UI_FILTERS', true),
                'show_extensions'        => env('L5_SWAGGER_UI_SHOW_EXTENSIONS', false),
                'show_common_extensions' => env('L5_SWAGGER_UI_SHOW_COMMON_EXTENSIONS', false),
                'try_it_out_enabled'     => env('L5_SWAGGER_UI_TRY_IT_OUT_ENABLED', false),
            ],
            'authorization' => [
                'persist_authorization' => env('L5_SWAGGER_UI_PERSIST_AUTHORIZATION', false),
                'oauth2' => [
                    'use_pkce_with_authorization_code_grant' => false,
                ],
            ],
        ],
    ],
];
