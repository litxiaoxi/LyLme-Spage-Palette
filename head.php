<!DOCTYPE html>
<html lang="zh" id="content">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo $conf['title'] ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>" />
    <meta name="description" content="<?php echo $conf['description'] ?>" />
    <link rel="shortcut icon" href="<?php echo $conf['logo'] ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="full-screen" content="yes">
    <meta name="browsermode" content="application">
    <meta name="x5-fullscreen" content="true">
    <meta name="x5-page-mode" content="app">
    <script>
        (function() {
            if (localStorage.getItem('palette_nightMode') === 'true') {
                document.documentElement.classList.add('night-mode');
            }
            var savedColor = localStorage.getItem('palette_color');
            if (savedColor) {
                document.documentElement.setAttribute('data-theme', savedColor);
            }
        })();
    </script>
    <link rel="stylesheet" href="<?php echo $templatepath; ?>/css/style.css">
    <script src="<?php echo $cdnpublic ?>/assets/js/svg.js"></script>
</head>

<body>
    <!-- 动态背景层 -->
    <div class="bg-layer"></div>

    <div class="main-wrapper">
        <!-- 顶部栏 -->
        <header class="top-bar">
            <div class="top-bar-left">
                <a href="/" class="brand-link" title="<?php echo $conf['title'] ?>">
                    <img src="<?php echo $conf['logo'] ?>" class="brand-logo" alt="<?php echo $conf['title'] ?>">
                    <span class="brand-text"><?php echo explode("-", $conf['title'])[0]; ?></span>
                </a>
            </div>
            <div class="top-bar-right">
                <!-- 背景切换 -->
                <div class="icon-btn" id="bgBtn" title="切换背景">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                </div>
                <!-- 调色板 -->
                <div class="palette-dropdown">
                    <div class="icon-btn" id="paletteBtn" title="切换主题色彩">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 2a10 10 0 0 1 0 20"/>
                            <path d="M12 2a10 10 0 0 0 0 20"/>
                            <line x1="2" y1="12" x2="22" y2="12"/>
                        </svg>
                    </div>
                    <div class="palette-popover">
                        <div class="color-options">
                            <span class="color-opt active" data-color="purple" title="极光紫"></span>
                            <span class="color-opt" data-color="blue" title="板岩蓝"></span>
                            <span class="color-opt" data-color="green" title="森林绿"></span>
                            <span class="color-opt" data-color="pink" title="玫瑰粉"></span>
                            <span class="color-opt" data-color="orange" title="日落橙"></span>
                            <span class="color-opt" data-color="teal" title="海洋青"></span>
                        </div>
                    </div>
                </div>
                <!-- 夜间模式 -->
                <div class="icon-btn" id="nightBtn" title="切换模式">
                    <svg class="icon-sun" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="5"/>
                        <line x1="12" y1="1" x2="12" y2="3"/>
                        <line x1="12" y1="21" x2="12" y2="23"/>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                        <line x1="1" y1="12" x2="3" y2="12"/>
                        <line x1="21" y1="12" x2="23" y2="12"/>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                    </svg>
                    <svg class="icon-moon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                    </svg>
                </div>
                <!-- 更多菜单 -->
                <div class="dropdown">
                    <div class="icon-btn" title="更多">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                            <circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/>
                        </svg>
                    </div>
                    <div class="dropdown-menu">
                        <div class="dropdown-section">
                            <span class="dropdown-label">背景设置</span>
                            <div class="bg-options">
                                <span class="bg-opt" data-bg="image">图片背景</span>
                                <?php if (!empty(theme_config('background_video'))) : ?>
                                <span class="bg-opt" data-bg="video">视频背景</span>
                                <?php endif; ?>
                                <span class="bg-opt" data-bg="none">清除背景</span>
                            </div>
                        </div>
                        <?php
                        $tagslists = $DB->query("SELECT * FROM `lylme_tags`");
                        if ($DB->num_rows($tagslists) > 0) :
                        ?>
                        <div class="dropdown-section">
                            <span class="dropdown-label">快捷导航</span>
                            <?php
                            while ($taglist = $DB->fetch($tagslists)) {
                                $target = $taglist["tag_target"] ? ' target="_blank"' : '';
                                echo '<a href="' . $taglist["tag_link"] . '"' . $target . ' class="dropdown-link">' . $taglist["tag_name"] . '</a>';
                            }
                            ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>

        <!-- 中心内容区 -->
        <div class="hero-section">
            <!-- 时钟 -->
            <div class="clock-area">
                <div class="clock-time" id="clockTime">00:00:00</div>
                <div class="clock-date" id="clockDate"></div>
                <div class="clock-greeting" id="clockGreeting"></div>
            </div>

            <?php if ($conf['yan'] == 'true') : ?>
            <p class="yiyan-text"><?php echo yan(); ?></p>
            <?php endif; ?>

            <!-- 搜索引擎数据 -->
            <?php
            $soulists = $site->getSou();
            $json = array();
            while ($soulist = $DB->fetch($soulists)) {
                echo '<div class="ss hide"><div class="lg">' . $soulist["sou_icon"] . '</div></div>' . "\n";
                if (checkmobile() && !empty($soulist["sou_waplink"])) {
                    $so = $soulist["sou_waplink"];
                } else {
                    $so = $soulist["sou_link"];
                }
                array_push($json, array($soulist['sou_name'], $soulist['sou_hint'], $so));
            }
            $json = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            ?>

            <!-- 搜索框 -->
            <div class="search-area">
                <div class="search-wrapper">
                    <div class="search-engine-switcher" id="engineSwitcher" title="点击切换搜索引擎">
                        <span class="engine-icon" id="engineIcon"></span>
                        <svg class="engine-arrow" viewBox="0 0 24 24" width="12" height="12" fill="currentColor">
                            <path d="M7 10l5 5 5-5z"/>
                        </svg>
                    </div>
                    <input class="search-input" type="text" id="searchInput" placeholder="" autocomplete="off" x-webkit-speech lang="zh-CN">
                    <button class="search-btn" id="searchBtn" title="搜索">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </button>
                </div>
                <!-- 搜索建议 -->
                <ul class="search-suggestions" id="searchSuggestions"></ul>
            </div>
        </div>

        <!-- 链接导航区 -->
        <div class="nav-section">
