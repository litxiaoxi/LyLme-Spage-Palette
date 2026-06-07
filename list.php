            <!-- 分类导航条 -->
            <nav class="category-nav">
                <div class="category-scroll">
                    <span class="category-item active" data-cat="0">全部</span>
                    <?php
                    $groups = $site->getGroups();
                    while ($group = $DB->fetch($groups)) {
                        echo '<span class="category-item" data-cat="' . $group["group_id"] . '">' . $group["group_name"] . '</span>';
                    }
                    ?>
                </div>
            </nav>

            <!-- 链接列表 -->
            <div class="links-container" id="linksContainer">
                <?php
                $html = array(
                    'g1' => '<div class="link-group" data-group-id="{group_id}">',
                    'g2' => '<div class="group-header"><span class="group-icon">{group_icon}</span><h3 class="group-title">{group_name}</h3></div><div class="group-links">',
                    'g3' => '</div></div>',
                    'l1' => '<div class="link-item-wrapper">',
                    'l2' => '<a href="{link_url}" title="{link_name_text}" data-id="{link_id}" target="_blank" class="link-card tool-link"><span class="link-card-icon">{link_icon}</span><span class="link-card-name">{link_name}</span></a>',
                    'l3' => '</div>',
                );
                lists($html);
                ?>
            </div>
        </div>
    </div>

    <script>
        <?php
        // 生成 tool_list 数据（仅包含启用的链接，与 lists() 逻辑对齐）
        $sessionList = isset($_SESSION['list']) ? $_SESSION['list'] : [];
        $jsonArr = [];
        $groupsRes = $DB->query("SELECT * FROM `lylme_groups` ORDER BY `group_order` ASC");
        while ($group = $DB->fetch($groupsRes)) {
            if (isset($group['group_status']) && $group['group_status'] == '0') continue;
            $groupPwd = isset($group['group_pwd']) ? $group['group_pwd'] : '';
            if (!empty($groupPwd) && !in_array((int)$groupPwd, $sessionList, true)) continue;
            $gid = (int)$group['group_id'];
            $entry = [
                'id' => $gid,
                'title' => isset($group['group_name']) ? $group['group_name'] : '',
                'icon' => isset($group['group_icon']) ? $group['group_icon'] : '',
                'items' => []
            ];
            $linksRes = $DB->query("SELECT * FROM `lylme_links` WHERE `group_id` = {$gid} ORDER BY `link_order` ASC");
            while ($link = $DB->fetch($linksRes)) {
                $linkPwd = isset($link['link_pwd']) ? $link['link_pwd'] : '';
                $linkStatus = isset($link['link_status']) ? $link['link_status'] : 1;
                $lpwd = (empty($groupPwd) && !empty($linkPwd) && !in_array((int)$linkPwd, $sessionList, true)) ? false : true;
                if ($linkStatus && $lpwd) {
                    $entry['items'][] = [
                        'id' => isset($link['id']) ? (int)$link['id'] : 0,
                        'title' => isset($link['name']) ? $link['name'] : '',
                        'alias' => 'link' . (isset($link['id']) ? (int)$link['id'] : 0),
                        'keyword' => isset($link['name']) ? $link['name'] : '',
                        'category_id' => $gid,
                        'icon' => isset($link['icon']) ? $link['icon'] : '',
                        'url' => isset($link['url']) ? $link['url'] : '',
                        'out' => true
                    ];
                }
            }
            $jsonArr[] = $entry;
        }
        ?>
        var tool_list = <?php echo json_encode($jsonArr); ?>;
    </script>
