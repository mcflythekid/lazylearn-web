<?php

$lang_file = dirname(__FILE__) . "/" . optimize_language() . ".php";
$default_lang_file = dirname(__FILE__) . "/lang/en_US.php";
if (file_exists($lang_file)){
  require_once $lang_file;
} else {
  require_once $default_lang_file;
}

// Function for basic field validation (present and neither empty nor only white space
function IsNullOrEmptyString($str){
    return (!isset($str) || trim($str) === '');
}

function client_ip(){
    return getenv('HTTP_CLIENT_IP')?:
    getenv('HTTP_X_FORWARDED_FOR')?:
    getenv('HTTP_X_FORWARDED')?:
    getenv('HTTP_FORWARDED_FOR')?:
    getenv('HTTP_FORWARDED')?:
    getenv('REMOTE_ADDR');
}

function optimize_language(){
    $lang = get_clientsession_language();

    if (IsNullOrEmptyString($lang)){
        $lang = get_clientcookie_language();

        if (IsNullOrEmptyString($lang)){
            $lang = get_clientip_language();
            setcookie("lang", $lang, time() + (10 * 365 * 24 * 60 * 60), "/");
        }

        $_SESSION["lang"] = $lang;
    }

    return $lang;
}

function get_clientsession_language(){
    return $_SESSION["lang"];
}

function get_clientcookie_language(){
    return $_COOKIE["lang"];
}

function get_clientip_language(){
    $lang = ip_2_language(client_ip());
    // setcookie("ip_" . time(), client_ip(), time() + (10 * 365 * 24 * 60 * 60), "/"); DEBUG
    if (IsNullOrEmptyString($lang)){
        $lang = "en_US";
    }
    return $lang;
}

function ip_2_country($ip){
    $raw = file_get_contents("http://lazylearn.com:8081/" . $ip);
    $json = json_decode($raw, true);
    return $json["country"];
}

function ip_2_language($ip){
    return country_code_to_locale(ip_2_country($ip));
}

/**
* Returns a locale from a country code that is provided.
*
* @param $country_code  ISO 3166-2-alpha 2 country code
* @param $language_code ISO 639-1-alpha 2 language code
* @returns  a locale, formatted like en_US, or null if not found
**/
function country_code_to_locale($country_code, $language_code = '')
{
    // Locale list taken from:
    // http://stackoverflow.com/questions/3191664/
    // list-of-all-locales-and-their-short-codes
    $locales = array('af-ZA',
                    'am-ET',
                    'ar-AE',
                    'ar-BH',
                    'ar-DZ',
                    'ar-EG',
                    'ar-IQ',
                    'ar-JO',
                    'ar-KW',
                    'ar-LB',
                    'ar-LY',
                    'ar-MA',
                    'arn-CL',
                    'ar-OM',
                    'ar-QA',
                    'ar-SA',
                    'ar-SY',
                    'ar-TN',
                    'ar-YE',
                    'as-IN',
                    'az-Cyrl-AZ',
                    'az-Latn-AZ',
                    'ba-RU',
                    'be-BY',
                    'bg-BG',
                    'bn-BD',
                    'bn-IN',
                    'bo-CN',
                    'br-FR',
                    'bs-Cyrl-BA',
                    'bs-Latn-BA',
                    'ca-ES',
                    'co-FR',
                    'cs-CZ',
                    'cy-GB',
                    'da-DK',
                    'de-AT',
                    'de-CH',
                    'de-DE',
                    'de-LI',
                    'de-LU',
                    'dsb-DE',
                    'dv-MV',
                    'el-GR',
                    'en-029',
                    'en-AU',
                    'en-BZ',
                    'en-CA',
                    'en-GB',
                    'en-IE',
                    'en-IN',
                    'en-JM',
                    'en-MY',
                    'en-NZ',
                    'en-PH',
                    'en-SG',
                    'en-TT',
                    'en-US',
                    'en-ZA',
                    'en-ZW',
                    'es-AR',
                    'es-BO',
                    'es-CL',
                    'es-CO',
                    'es-CR',
                    'es-DO',
                    'es-EC',
                    'es-ES',
                    'es-GT',
                    'es-HN',
                    'es-MX',
                    'es-NI',
                    'es-PA',
                    'es-PE',
                    'es-PR',
                    'es-PY',
                    'es-SV',
                    'es-US',
                    'es-UY',
                    'es-VE',
                    'et-EE',
                    'eu-ES',
                    'fa-IR',
                    'fi-FI',
                    'fil-PH',
                    'fo-FO',
                    'fr-BE',
                    'fr-CA',
                    'fr-CH',
                    'fr-FR',
                    'fr-LU',
                    'fr-MC',
                    'fy-NL',
                    'ga-IE',
                    'gd-GB',
                    'gl-ES',
                    'gsw-FR',
                    'gu-IN',
                    'ha-Latn-NG',
                    'he-IL',
                    'hi-IN',
                    'hr-BA',
                    'hr-HR',
                    'hsb-DE',
                    'hu-HU',
                    'hy-AM',
                    'id-ID',
                    'ig-NG',
                    'ii-CN',
                    'is-IS',
                    'it-CH',
                    'it-IT',
                    'iu-Cans-CA',
                    'iu-Latn-CA',
                    'ja-JP',
                    'ka-GE',
                    'kk-KZ',
                    'kl-GL',
                    'km-KH',
                    'kn-IN',
                    'kok-IN',
                    'ko-KR',
                    'ky-KG',
                    'lb-LU',
                    'lo-LA',
                    'lt-LT',
                    'lv-LV',
                    'mi-NZ',
                    'mk-MK',
                    'ml-IN',
                    'mn-MN',
                    'mn-Mong-CN',
                    'moh-CA',
                    'mr-IN',
                    'ms-BN',
                    'ms-MY',
                    'mt-MT',
                    'nb-NO',
                    'ne-NP',
                    'nl-BE',
                    'nl-NL',
                    'nn-NO',
                    'nso-ZA',
                    'oc-FR',
                    'or-IN',
                    'pa-IN',
                    'pl-PL',
                    'prs-AF',
                    'ps-AF',
                    'pt-BR',
                    'pt-PT',
                    'qut-GT',
                    'quz-BO',
                    'quz-EC',
                    'quz-PE',
                    'rm-CH',
                    'ro-RO',
                    'ru-RU',
                    'rw-RW',
                    'sah-RU',
                    'sa-IN',
                    'se-FI',
                    'se-NO',
                    'se-SE',
                    'si-LK',
                    'sk-SK',
                    'sl-SI',
                    'sma-NO',
                    'sma-SE',
                    'smj-NO',
                    'smj-SE',
                    'smn-FI',
                    'sms-FI',
                    'sq-AL',
                    'sr-Cyrl-BA',
                    'sr-Cyrl-CS',
                    'sr-Cyrl-ME',
                    'sr-Cyrl-RS',
                    'sr-Latn-BA',
                    'sr-Latn-CS',
                    'sr-Latn-ME',
                    'sr-Latn-RS',
                    'sv-FI',
                    'sv-SE',
                    'sw-KE',
                    'syr-SY',
                    'ta-IN',
                    'te-IN',
                    'tg-Cyrl-TJ',
                    'th-TH',
                    'tk-TM',
                    'tn-ZA',
                    'tr-TR',
                    'tt-RU',
                    'tzm-Latn-DZ',
                    'ug-CN',
                    'uk-UA',
                    'ur-PK',
                    'uz-Cyrl-UZ',
                    'uz-Latn-UZ',
                    'vi-VN',
                    'wo-SN',
                    'xh-ZA',
                    'yo-NG',
                    'zh-CN',
                    'zh-HK',
                    'zh-MO',
                    'zh-SG',
                    'zh-TW',
                    'zu-ZA',);

    foreach ($locales as $locale)
    {
        $locale_region = locale_get_region($locale);
        $locale_language = locale_get_primary_language($locale);
        $locale_array = array('language' => $locale_language,
                             'region' => $locale_region);

        if (strtoupper($country_code) == $locale_region &&
            $language_code == '')
        {
            return locale_compose($locale_array);
        }
        elseif (strtoupper($country_code) == $locale_region &&
                strtolower($language_code) == $locale_language)
        {
            return locale_compose($locale_array);
        }
    }

    return null;
}
?>