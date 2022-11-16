<?php

/**
 * Support the htmlinject hook, which allows modules to change header, pre and post body on all pages.
 */
$this->data['htmlinject'] = [
    'htmlContentPre' => [],
    'htmlContentPost' => [],
    'htmlContentHead' => [],
];

$jquery = [];
if (array_key_exists('jquery', $this->data)) {
    $jquery = $this->data['jquery'];
}

if (array_key_exists('pageid', $this->data)) {
    $hookinfo = [
        'pre' => &$this->data['htmlinject']['htmlContentPre'],
        'post' => &$this->data['htmlinject']['htmlContentPost'],
        'head' => &$this->data['htmlinject']['htmlContentHead'],
        'jquery' => &$jquery,
        'page' => $this->data['pageid']
    ];

    SimpleSAML\Module::callHooks('htmlinject', $hookinfo);
}
// - o - o - o - o - o - o - o - o - o - o - o - o -

/**
 * Do not allow to frame SimpleSAMLphp pages from another location.
 * This prevents clickjacking attacks in modern browsers.
 *
 * If you don't want any framing at all you can even change this to
 * 'DENY', or comment it out if you actually want to allow foreign
 * sites to put SimpleSAMLphp in a frame. The latter is however
 * probably not a good security practice.
 */
header('X-Frame-Options: SAMEORIGIN');

?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex, nofollow" />

    <title>IDP <?php if (array_key_exists('header', $this->data)) { echo ' - ' . $this->data['header']; } ?></title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Scripts -->
    <link rel="preload" as="style" href="<?php echo SimpleSAML\Module::getModuleURL('demoTheme/resources/style.css'); ?>" />
    <link rel="stylesheet" href="<?php echo SimpleSAML\Module::getModuleURL('demoTheme/resources/style.css'); ?>" />

    <script type="text/javascript" src="/<?php echo $this->data['baseurlpath']; ?>resources/script.js"></script>
    <!--
    <link rel="stylesheet" type="text/css" href="/<?php echo $this->data['baseurlpath']; ?>resources/default.css" />
    -->
    <link rel="icon" type="image/icon" href="/<?php echo $this->data['baseurlpath']; ?>resources/icons/favicon.ico" />

<?php

if (!empty($jquery)) {
    $version = '1.8';
    if (array_key_exists('version', $jquery)) {
        $version = $jquery['version'];
    }

    if ($version == '1.8') {
        if (isset($jquery['core']) && $jquery['core']) {
            echo '<script type="text/javascript" src="/'.$this->data['baseurlpath'].'resources/jquery-1.8.js"></script>'."\n";
        }

        if (isset($jquery['ui']) && $jquery['ui']) {
            echo '<script type="text/javascript" src="/'.$this->data['baseurlpath'].'resources/jquery-ui-1.8.js"></script>'."\n";
        }

        if (isset($jquery['css']) && $jquery['css']) {
            echo '<link rel="stylesheet" media="screen" type="text/css" href="/'.$this->data['baseurlpath'].'resources/uitheme1.8/jquery-ui.css" />'."\n";
        }
    }
}

if (isset($this->data['clipboard.js'])) {
    echo '<script type="text/javascript" src="/'.$this->data['baseurlpath'].'resources/clipboard.min.js"></script>'."\n";
}

if (!empty($this->data['htmlinject']['htmlContentHead'])) {
    foreach ($this->data['htmlinject']['htmlContentHead'] as $c) {
        echo $c;
    }
}

if ($this->isLanguageRTL()) {
    echo '<link rel="stylesheet" type="text/css" href="/'.$this->data['baseurlpath'].'resources/default-rtl.css" />'."\n";
}

if (array_key_exists('head', $this->data)) {
    echo '<!-- head -->'.$this->data['head'].'<!-- /head -->';
}

$onLoad = '';
if (array_key_exists('autofocus', $this->data)) {
    $onLoad .= ' onload="SimpleSAML_focus(\''.$this->data['autofocus'].'\');"';
}

?>
</head>
<body<?php echo $onLoad; ?>>

<?php

if (!empty($this->data['htmlinject']['htmlContentPre'])) {
    foreach ($this->data['htmlinject']['htmlContentPre'] as $c) {
        echo $c;
    }
}

$config = \SimpleSAML\Configuration::getInstance();

if(!$config->getBoolean('production', true)) {
    echo '<div class="caution">' . $this->t('{preprodwarning:warning:warning}'). '</div>';
}
