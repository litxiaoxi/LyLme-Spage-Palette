/**
 * Palette Theme - UI Interactions
 * 时钟、夜间模式、背景切换、问候语、主题色彩切换
 */

(function () {
    'use strict';

    // ============ 主题色彩管理 ============
    var COLOR_KEY = 'palette_color';
    var colors = ['purple', 'blue', 'green', 'pink', 'orange', 'teal'];

    var colorNames = {
        purple: '极光紫',
        blue: '板岩蓝',
        green: '森林绿',
        pink: '玫瑰粉',
        orange: '日落橙',
        teal: '海洋青'
    };

    function setColor(color) {
        if (colors.indexOf(color) === -1) color = 'purple';
        document.documentElement.setAttribute('data-theme', color);
        localStorage.setItem(COLOR_KEY, color);
        updateColorActive(color);
    }

    function updateColorActive(color) {
        var items = document.querySelectorAll('.color-opt');
        for (var i = 0; i < items.length; i++) {
            var c = items[i].getAttribute('data-color');
            if (c === color) {
                items[i].classList.add('active');
            } else {
                items[i].classList.remove('active');
            }
        }
    }

    // 恢复色彩偏好
    var savedColor = localStorage.getItem(COLOR_KEY) || 'purple';
    setColor(savedColor);

    // 点击色彩选项
    document.addEventListener('click', function (e) {
        var opt = e.target.closest('.color-opt');
        if (!opt) return;
        var color = opt.getAttribute('data-color');
        if (color) setColor(color);
    });

    // ============ 时钟更新 ============
    function updateClock() {
        var now = new Date();
        var hours = String(now.getHours()).padStart(2, '0');
        var minutes = String(now.getMinutes()).padStart(2, '0');
        var seconds = String(now.getSeconds()).padStart(2, '0');
        var timeEl = document.getElementById('clockTime');
        var dateEl = document.getElementById('clockDate');
        var greetingEl = document.getElementById('clockGreeting');

        if (timeEl) timeEl.textContent = hours + ':' + minutes + ':' + seconds;

        if (dateEl) {
            var year = now.getFullYear();
            var month = now.getMonth() + 1;
            var day = now.getDate();
            var weekdays = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
            var weekday = weekdays[now.getDay()];
            dateEl.textContent = year + '年' + month + '月' + day + '日 ' + weekday;
        }

        if (greetingEl) {
            var h = now.getHours();
            var greeting;
            if (h < 6) {
                greeting = '夜深了，早点休息吧';
            } else if (h < 9) {
                greeting = '早上好！新的一天开始了';
            } else if (h < 12) {
                greeting = '上午好！愿你精力充沛';
            } else if (h < 14) {
                greeting = '中午好！记得按时吃饭';
            } else if (h < 18) {
                greeting = '下午好！工作辛苦了';
            } else if (h < 22) {
                greeting = '晚上好！放松一下吧';
            } else {
                greeting = '夜深了，晚安';
            }
            greetingEl.textContent = greeting;
        }
    }
    updateClock();
    setInterval(updateClock, 1000);

    // ============ 夜间模式切换 ============
    function updateNightModeIcon() {
        var isNight = document.documentElement.classList.contains('night-mode');
        var sunIcon = document.querySelector('#nightBtn .icon-sun');
        var moonIcon = document.querySelector('#nightBtn .icon-moon');
        if (sunIcon && moonIcon) {
            sunIcon.style.display = isNight ? 'none' : '';
            moonIcon.style.display = isNight ? '' : 'none';
        }
    }

    var nightBtn = document.getElementById('nightBtn');
    if (nightBtn) {
        nightBtn.addEventListener('click', function () {
            var html = document.documentElement;
            var isNight = html.classList.toggle('night-mode');
            localStorage.setItem('palette_nightMode', isNight);
            updateNightModeIcon();
        });
    }

    // 恢复夜间模式
    (function () {
        if (localStorage.getItem('palette_nightMode') === 'true') {
            document.documentElement.classList.add('night-mode');
        }
    })();
    updateNightModeIcon();

    // ============ 背景切换 ============
    var bgLayer = document.querySelector('.bg-layer');
    var bgKey = 'palette_bgSetting';

    function setImageBackground() {
        if (!bgLayer || !window.backgroundimg) return;
        localStorage.setItem(bgKey, 'image');
        bgLayer.classList.add('image-bg');
        bgLayer.style.backgroundImage = window.backgroundimg;
        removeVideoBackground();
    }

    function setVideoBackground() {
        if (!window.background_video) return;
        localStorage.setItem(bgKey, 'video');
        if (bgLayer) {
            bgLayer.classList.remove('image-bg');
            bgLayer.style.backgroundImage = 'none';
        }
        removeVideoBackground();
        var video = document.createElement('video');
        video.src = window.background_video;
        video.loop = true;
        video.muted = true;
        video.autoplay = true;
        video.playsInline = true;
        video.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;object-fit:cover;z-index:-3;pointer-events:none;';
        document.body.appendChild(video);
    }

    function clearBackground() {
        localStorage.setItem(bgKey, 'none');
        if (bgLayer) {
            bgLayer.classList.remove('image-bg');
            bgLayer.style.backgroundImage = 'none';
        }
        removeVideoBackground();
    }

    function removeVideoBackground() {
        var videos = document.querySelectorAll('body > video');
        for (var i = 0; i < videos.length; i++) {
            if (videos[i].parentNode) {
                videos[i].parentNode.removeChild(videos[i]);
            }
        }
    }

    var savedBg = localStorage.getItem(bgKey);
    if (savedBg === 'image') {
        setImageBackground();
    } else if (savedBg === 'video') {
        setVideoBackground();
    }

    document.addEventListener('click', function (e) {
        var opt = e.target.closest('.bg-opt');
        if (!opt) return;
        var type = opt.getAttribute('data-bg');
        if (type === 'image') setImageBackground();
        else if (type === 'video') setVideoBackground();
        else if (type === 'none') clearBackground();
    });

    var bgBtn = document.getElementById('bgBtn');
    if (bgBtn) {
        bgBtn.addEventListener('click', function () {
            var current = localStorage.getItem(bgKey);
            if (!current || current === 'none') {
                if (window.backgroundimg) {
                    setImageBackground();
                } else if (window.background_video) {
                    setVideoBackground();
                }
            } else if (current === 'image') {
                if (window.background_video) {
                    setVideoBackground();
                } else {
                    clearBackground();
                }
            } else if (current === 'video') {
                clearBackground();
            }
        });
    }

})();
