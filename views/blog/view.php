<?php
// statically decompiled from view.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (isset($_GET['slug'])) {
    $slug = Anti_xss($_GET['slug']);
    $row = $db->get_row(' SELECT * FROM `posts` WHERE `slug` = \'' . $slug . '\' ');
    if (!$row) {
        new Redirect('/tin-tuc');
    }
    $db->update('posts', ['view' => $row['view'] + 1], ' `id` = \'' . $row['id'] . '\' ');
} else {
    new Redirect('/tin-tuc');
}
$title = $row['title'] . ' | ' . $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
echo '<div id="bre-nickgame">
    <div class="breadCrumbs">
        <div class="screen">
            <div class="center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="/tin-tuc"><span>Tin tức</span></a></li>
                    <li class="breadcrumb-item active"><a class="text-decoration-none" href="/tin-tuc/';
echo $slug;
echo '"><span>';
echo $row['title'];
echo '</span></a></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="screen">
    <div class="center">
        <div class="row">
            <div class="col-12 col-lg-8 mb-3">
                <div class="content-detail">
                    ';
echo base64_decode($row['content']);
echo '                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="sub-article-block">
                    <div class="sub-article-block-header d-flex justify-content-between align-items-center c-px-16 c-py-12">
                        <h3 class="fw-700 fz-24 lh-32">
                            Bài viết liên quan
                        </h3>
                        <a href="/tin-tuc" class="link arr-right">Xem tất cả</a>
                    </div>
                    ';
foreach ($db->get_list(' SELECT * FROM `posts` WHERE `status` = 1 AND `id` != \'' . $row['id'] . '\' AND `category_id` = \'' . $row['category_id'] . '\' ORDER BY `view` DESC ') as $popular) {
    echo '                        <div class="sub-article c-px-16">
                            <div class="row">
                                <div class="col-6 sub-article--thumbnail-container">
                                    <div class="sub-article--thumbnail">
                                        <a href="/tin-tuc/';
    echo $popular['slug'];
    echo '">
                                            <img src="/assets/images/lazyload.gif" data-src="';
    echo $popular['image'];
    echo '" alt="';
    echo $popular['title'];
    echo '" class="sub-article--thumbnail__image lazyload">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-6 sub-article--info">
                                    <a href="/tin-tuc/';
    echo $popular['slug'];
    echo '" class="sub-article--title__link">
                                        ';
    echo $popular['title'];
    echo '                                    </a>
                                </div>
                            </div>
                        </div>
                    ';
}
echo '                </div>
            </div>
        </div>
    </div>
</section>
';
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
