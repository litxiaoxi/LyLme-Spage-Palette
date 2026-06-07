/**
 * Aurora Theme - Tool Interactions
 * 搜索引擎切换、分类筛选、搜索建议、链接统计
 */

$(function () {
    'use strict';

    // ============ 搜索引擎切换 ============
    var searchData = typeof solist === 'function' ? solist() : [];

    function updateEngine(index) {
        if (!searchData.length) return;
        index = parseInt(index) % searchData.length;
        var engine = searchData[index];
        var iconEl = $('#engineIcon');
        var inputEl = $('#searchInput');

        // 更新搜索引擎图标（通过DOM获取实际的图标元素）
        var ssItems = $('.ss');
        if (ssItems.length > index) {
            var iconHtml = ssItems.eq(index).find('.lg').html();
            if (iconHtml) {
                iconEl.html(iconHtml);
            }
        }

        inputEl.attr('placeholder', engine[1] || '');
        localStorage.setItem('aurora_engine_index', index);
    }

    // 初始化搜索引擎
    var savedEngine = localStorage.getItem('aurora_engine_index');
    updateEngine(savedEngine !== null ? savedEngine : 0);

    // 点击切换
    $('#engineSwitcher').on('click', function () {
        var current = parseInt(localStorage.getItem('aurora_engine_index') || 0);
        updateEngine(current + 1);
    });

    // ============ 搜索执行 ============
    function doSearch(keyword) {
        if (!searchData.length) return;
        var index = parseInt(localStorage.getItem('aurora_engine_index') || 0);
        var engine = searchData[index];
        if (!engine || !engine[2]) return;
        var url = engine[2] + encodeURIComponent(keyword);
        window.open(url, '_blank');
    }

    $('#searchBtn').on('click', function () {
        var keyword = $('#searchInput').val().trim();
        if (keyword) doSearch(keyword);
    });

    $('#searchInput').on('keydown', function (e) {
        if (e.key === 'Enter') {
            var keyword = $(this).val().trim();
            if (keyword) doSearch(keyword);
        }
    });

    // ============ 搜索建议 ============
    var suggestTimer;
    $('#searchInput').on('input', function () {
        var keyword = $(this).val().trim();
        clearTimeout(suggestTimer);

        if (!keyword) {
            $('#searchSuggestions').removeClass('show').empty();
            return;
        }

        suggestTimer = setTimeout(function () {
            $.ajax({
                url: 'https://suggestion.baidu.com/su?wd=' + encodeURIComponent(keyword),
                dataType: 'jsonp',
                jsonp: 'cb',
                success: function (data) {
                    var $list = $('#searchSuggestions');
                    $list.empty();
                    if (data && data.s && data.s.length) {
                        $.each(data.s, function (i, item) {
                            $list.append(
                                '<li><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>' +
                                item + '</li>'
                            );
                        });
                        $list.addClass('show');
                    } else {
                        $list.removeClass('show');
                    }
                },
                error: function () {
                    $('#searchSuggestions').removeClass('show').empty();
                }
            });
        }, 250);
    });

    // 点击建议
    $(document).on('click', '#searchSuggestions li', function () {
        var word = $(this).text().trim();
        $('#searchInput').val(word);
        $('#searchSuggestions').removeClass('show').empty();
        doSearch(word);
    });

    // 点击空白关闭建议
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.search-area').length) {
            $('#searchSuggestions').removeClass('show').empty();
        }
    });

    // ============ HTML 转义函数 ============
    // 转义双引号，防止破坏 HTML 属性值
    function attrEscape(str) {
        if (!str) return '';
        return String(str).replace(/"/g, '&quot;');
    }

    // 去除 HTML 标签，用于 title 属性（与 PHP 端 strip_tags 行为一致）
    function stripTags(str) {
        if (!str) return '';
        return String(str).replace(/<[^>]*>/g, '');
    }

    // ============ 分类筛选 ============
    var toolListData = typeof tool_list !== 'undefined' ? tool_list : [];

    $('.category-item').on('click', function () {
        var catId = $(this).data('cat');
        $('.category-item').removeClass('active');
        $(this).addClass('active');

        if (toolListData.length) {
            renderToolList(catId);
            return;
        }

        // Fallback: show/hide groups
        if (catId === 0) {
            $('.link-group').show();
        } else {
            $('.link-group').each(function () {
                var groupId = $(this).data('group-id');
                if (groupId == catId) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    });

    function renderToolList(catId) {
        var html = '';
        $.each(toolListData, function (i, group) {
            if (catId != 0 && group.id != catId) return;

            html += '<div class="link-group" data-group-id="' + attrEscape(group.id) + '">';
            if (group.icon) {
                html += '<div class="group-header">';
                html += '<span class="group-icon">' + group.icon + '</span>';
                html += '<h3 class="group-title">' + group.title + '</h3>';
                html += '</div>';
            } else {
                html += '<div class="group-header"><h3 class="group-title">' + group.title + '</h3></div>';
            }
            html += '<div class="group-links">';

            $.each(group.items, function (j, item) {
                var iconHtml;
                if (item.icon && item.icon.trim()) {
                    if (item.icon.indexOf('<svg') === 0) {
                        iconHtml = item.icon;
                    } else if (item.icon.indexOf('<img') === 0) {
                        iconHtml = item.icon;
                    } else {
                        iconHtml = '<img src="' + attrEscape(item.icon) + '" alt="">';
                    }
                } else {
                    iconHtml = '<img src="/assets/img/default-icon.png" alt="">';
                }

                html += '<div class="link-item-wrapper">';
                html += '<a href="' + attrEscape(item.url || '#') + '" title="' + attrEscape(stripTags(item.title || '')) + '" data-id="' + attrEscape(item.id || '') + '" ';
                html += 'class="link-card tool-link" ';
                if (item.out) html += 'target="_blank" ';
                html += '>';
                html += '<span class="link-card-icon">' + iconHtml + '</span>';
                html += '<span class="link-card-name">' + (item.title || '') + '</span>';
                html += '</a></div>';
            });

            html += '</div></div>';
        });

        $('#linksContainer').html(html);
        bindStatistics();
    }

    // ============ 链接点击统计 ============
    function bindStatistics() {
        $('.tool-link').off('click.stat').on('click.stat', function () {
            var id = $(this).data('id');
            if (id) {
                $.ajax({
                    type: 'POST',
                    url: '/clitool',
                    data: { id: id },
                    dataType: 'json',
                    async: true
                });
            }
        });
    }

    // 初始绑定
    bindStatistics();

    // ============ 桌面端自动聚焦搜索框 ============
    if ($(window).width() >= 768) {
        setTimeout(function () {
            $('#searchInput').focus();
        }, 300);
    }

});
