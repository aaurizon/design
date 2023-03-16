<?php // #!/usr/bin/php

$c = `git fetch`;
$branchCurrent = trim(`git branch --show-current`);
$branchList = array_map(fn($v)=>explode(' ', trim($v))[0], preg_split('/\v/', `git branch -r`, -1, PREG_SPLIT_NO_EMPTY));
$branchList = array_map(function ($str) {
    $prefix = 'origin/';
    if (substr($str, 0, strlen($prefix)) == $prefix) $str = substr($str, strlen($prefix));
    return $str;
}, $branchList);

// var_dump($c, $branchCurrent, $branchList);exit;

$current = $_GET['page'] ?? 'getting-started';
$nav = [
    "Getting started" => [
        'welcome',
    ],
    "Commons" => [

    ],
    "Components" => [
        'button',
    ],
];

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if (php_sapi_name() == 'cli-server' AND file_exists(__DIR__ . $uri))
{
    return false;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Design - AAURIZON</title><?php const FILEPATH_CSS = 'dist/css/design.css'; const FILEPATH_CSS_MIN = 'dist/css/design.min.css'; ?>
    <link rel="stylesheet" type="text/css" href="<?=(file_exists(FILEPATH_CSS) AND filemtime(FILEPATH_CSS) > filemtime(FILEPATH_CSS_MIN))?FILEPATH_CSS:FILEPATH_CSS_MIN?>" />
    <style>

    </style>
</head>
<body class="VLayout">

<header>
    <div class="Container HLayout">
        <span style="font-weight: bold">AAURIZON - Design</span>
        <hr class="Spacer" />
        <select id="_git_branch" onchange="git_branch_change(this)">
            <?php foreach ($branchList as $branch): ?>
                <option value="<?=$branch?>" <?=($branchCurrent==$branch)?'selected':''?>><?=$branch?></option>
            <?php endforeach; ?>
        </select>
    </div>
</header>

<main class="Container HLayout Flexible">
    <nav id="NAV" class="VLayout">
        <?php foreach ($nav as $title => $subnav): ?>
            <span style="font-weight: bold"><?=$title?></span>
            <?php foreach ($subnav as $link): ?>
                <?php $linkname = ucfirst(str_replace(['-', '_'], ' ', $link)) ?>
                <a href="?page=<?=$link?>" class="<?=($link == $current)?'current':''?>"><?=$linkname?></a>
            <?php endforeach; ?>
            <hr />
        <?php endforeach; ?>
    </nav>
    <article class="VLayout Flexible">
        <?php
        $files = array();

        /** @var SplFileInfo $file */
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator('doc')) as $file)
        {
            if ($file->isFile())
            {
                if ($file->getFilename() == "{$current}.html")
                {
                    include $file->getPathname();
                }
            }
        }
        ?>
    </article>
</main>

<script>
    function git_branch_change(e)
    {
        console.log(e.value);
    }
</script>
</body>
</html>
