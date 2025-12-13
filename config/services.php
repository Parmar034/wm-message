<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'recaptcha' => [
        'siteKey' => env('RECAPTCHA_SITE_KEY'),
        'secretKey' => env('RECAPTCHA_SECRET_KEY'),
    ],
    'website_constant' => [
        // 'JWL_FAVICON_URL16'=>env('JWL_FAVICON_URL_16'),
        // 'JWL_FAVICON_URL32'=>env('JWL_FAVICON_URL_32'),
        // 'JWL_BUSINESS_LOGO' =>env('JWL_BUSINESS_LOGO'),
        // 'CATALOG_COVER_IMAGE_LOCATION'=>env('CATALOG_COVER_IMAGE_LOCATION'),
        // 'COLLECTION_COVER_IMAGE'=>env('COLLECTION_COVER_IMAGE'),
        'BASE_URL'=>env('BASE_URL'),
        // 'BUCKET_FOLDER'=>env('BUCKET_FOLDER'),
        // 'BUC_BUSINESS_PRODUCT_LOCATION'=>env('BUC_BUSINESS_PRODUCT_LOCATION'),
        // 'RESOURCES_LOCATION'=>env('RESOURCES_LOCATION'),
        // 'BUSINESS_LOGO'=>env('BUSINESS_LOGO'),
        // 'BUSINESS_PRODUCT_IMG'=>env('BUSINESS_PRODUCT_IMG'),
        // 'BUSINESS_PRODUCT_VIDEO'=>env('BUSINESS_PRODUCT_VIDEO'),
        // 'BUSINESS_PRODUCT_LOCATION'=>env('BUSINESS_PRODUCT_LOCATION'),
        'APP_URL' => env('APP_URL'),
        'BREVO_KEY' => env('BREVO_KEY'),
        'BREVO_SUBSCRIBE_EMAIL_TEMPLATE_ID' => env('BREVO_SUBSCRIBE_EMAIL_TEMPLATE_ID'),
        'BREVO_CONTACT_ENQUERY_EMAIL_TEMPLATE_ID' => env('BREVO_CONTACT_ENQUERY_EMAIL_TEMPLATE_ID'),
        'BREVO_FORGET_PASSWORD_EMAIL_TEMPLATE_ID' => env('BREVO_FORGET_PASSWORD_EMAIL_TEMPLATE_ID'),
        'BREVO_WELCOME_EMAIL_TEMPLATE_ID' => env('BREVO_WELCOME_EMAIL_TEMPLATE_ID'),
        'BREVO_EMAIL_VERIFICATION_OTP_APP_EMAIL_TEMPLATE_ID' => env('BREVO_EMAIL_VERIFICATION_OTP_APP_EMAIL_TEMPLATE_ID'),
        'BREVO_NEW_ORDER_TO_SELLER_EMAIL_TEMPLATE_ID' => env('BREVO_NEW_ORDER_TO_SELLER_EMAIL_TEMPLATE_ID'),
        'BREVO_NEW_ORDER_TO_USER_EMAIL_TEMPLATE_ID' => env('BREVO_NEW_ORDER_TO_USER_EMAIL_TEMPLATE_ID'),
        'BREVO_ORDER_STATUS_PROCESSING_EMAIL_TEMPLATE_ID' => env('BREVO_ORDER_STATUS_PROCESSING_EMAIL_TEMPLATE_ID'),
        'BREVO_ORDER_STATUS_COMPLETED_EMAIL_TEMPLATE_ID' => env('BREVO_ORDER_STATUS_COMPLETED_EMAIL_TEMPLATE_ID'),
        'BREVO_ORDER_STATUS_CANCEL_BY_USER_EMAIL_TEMPLATE_ID' => env('BREVO_ORDER_STATUS_CANCEL_BY_USER_EMAIL_TEMPLATE_ID'),
        'BREVO_ORDER_STATUS_CANCEL_BY_ADMIN_EMAIL_TEMPLATE_ID' => env('BREVO_ORDER_STATUS_CANCEL_BY_ADMIN_EMAIL_TEMPLATE_ID'),
        'JWL_FOOTER_COPYRIGHT_CONTENT' => env('JWL_FOOTER_COPYRIGHT_CONTENT'),
        

        
    ]
];
