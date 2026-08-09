<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = 'Tin tức - ' . $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
if (isset($_GET['limit'])) {
    $limit = Anti_xss($_GET['limit']);
} else {
    $limit = 5;
}
if (isset($_GET['page'])) {
    $page = Anti_xss($_GET['page']);
} else {
    $page = 3;
}
$from = ($page - 1) * $limit;
$where = ' `status` = 1 ';
$keyword = '';
$category = '';
if (!empty($_GET['category'])) {
    $category = Anti_xss($_GET['category']);
    $where .= ' AND `category_id` = "' . $category . '" ';
}
if (!empty($_GET['keyword'])) {
    $keyword = Anti_xss($_GET['keyword']);
    $where .= ' AND `title` LIKE "%' . $keyword . '%" ';
}
$listBlog = $db->get_list('SELECT * FROM `posts` WHERE ' . $where . ' ORDER BY `stt` ASC LIMIT ' . $from . ',' . $limit . ' ');
$blogs = $db->get_list('SELECT * FROM `posts` ORDER BY `stt` ASC LIMIT 5');
echo '<div id="bre-nickgame">
    <div class="breadCrumbs">
        <div class="screen">
            <div class="center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                    <li class="breadcrumb-item active"><a class="text-decoration-none" href=""><span>Tin tức</span></a></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="screen">
    <div class="center">
        <div class="ads-banner row mb-3" id="c_slider_banner">
            ';
foreach ($blogs as $__key => $blog) {
    $key = $__key;
    echo '                ';
    if ($key == 0) {
        echo '                    <div class="col-lg-6 col-md-12 top_box-product">
                        <div class="fix_ads-banner-fecond fix_ads-banner-second_top fix_ads-banner-ffist brs-12 h-100">
                            <a href="/tin-tuc/';
        echo $blog['slug'];
        echo '">
                                <img src="/assets/images/lazyload.gif" data-src="';
        echo $blog['image'];
        echo '" class="lazyload" alt="';
        echo $blog['title'];
        echo '">
                            </a>
                            <div class="item-article-content">
                                <div class="item-article-name text-limit limit-1">';
        echo $blog['title'];
        echo '</div>
                                <div class="item-article-user text-limit limit-1">
                                    <p>';
        echo base64_decode($blog['content']);
        echo '</p>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
    }
    echo '            ';
}
echo '
            <div class="col-lg-3 col-6 d-lg-flex flex-column justify-content-between center_swiper-general_right" style="min-height: 100%">
                ';
foreach ($blogs as $__key => $blog) {
    $key = $__key;
    echo '                    ';
    if ($key == 1 || $key == 2) {
        echo '                        <div class="fix_ads-banner-second fix_ads-banner-fist brs-12">
                            <a href="/tin-tuc/';
        echo $blog['slug'];
        echo '">
                                <img src="/assets/images/lazyload.gif" data-src="';
        echo $blog['image'];
        echo '" class="lazyload" alt="';
        echo $blog['title'];
        echo '">
                            </a>
                            <div class="item-article-content-secon">
                                <div class="item-article-name text-limit limit-1">';
        echo $blog['title'];
        echo '</div>
                                <div class="item-article-user text-limit limit-1">
                                    <p>';
        echo base64_decode($blog['content']);
        echo '</p>
                                </div>
                            </div>
                        </div>
                    ';
    }
    echo '                ';
}
echo '            </div>

            <div class="col-lg-3 col-6 d-lg-flex flex-column justify-content-between end_swiper-general_right" style="min-height: 100%">
                ';
foreach ($blogs as $__key => $blog) {
    $key = $__key;
    echo '                    ';
    if ($key == 3 || $key == 4) {
        echo '                        <div class="fix_ads-banner-second fix_ads-banner-fist brs-12">
                            <a href="/tin-tuc/';
        echo $blog['slug'];
        echo '">
                                <img src="/assets/images/lazyload.gif" data-src="';
        echo $blog['image'];
        echo '" class="lazyload" alt="';
        echo $blog['title'];
        echo '">
                            </a>
                            <div class="item-article-content-secon">
                                <div class="item-article-name text-limit limit-1">';
        echo $blog['title'];
        echo '</div>
                                <div class="item-article-user text-limit limit-1">
                                    <p>';
        echo base64_decode($blog['content']);
        echo '</p>
                                </div>
                            </div>
                        </div>
                    ';
    }
    echo '                ';
}
echo '            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-8" id="list-article">
                <div class="card--mobile__title c-pt-16 d-flex justify-content-between j-flex-sm-wrap">
                    <div>
                        <h4>Tin mới cập nhật</h4>
                    </div>
                    <div class="search-menu1">
                        <form action="" method="get" class="group-input input-search">
                            <div class="group-search">
                                <div class="input-element custom-search-menu1">
                                    <input type="text" id="input-search" name="keyword" value="';
echo $keyword;
echo '" placeholder="Tìm kiếm">
                                    <button type="submit">
                                        <custom-seach>
                                            <icon class="icon-search search"></icon>
                                        </custom-seach>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row flex-column-reverse " id="card--body__news">
                    <div class=" px-0 mt-lg-0" id="list-article" style="max-width: 100%">
                        <div class=" --custom p-3" id="new-article-update">

                            <div class="tab-content-title mt-4">
                                <div class="card--body">
                                    ';
if (count($listBlog) == 0) {
    echo '                                        <div class="empty-state">
                                            <svg width="184" height="152" viewBox="0 0 184 152" xmlns="http://www.w3.org/2000/svg">
                                                <g fill="none" fill-rule="evenodd">
                                                    <g transform="translate(24 31.67)">
                                                        <ellipse fill-opacity=".8" fill="#F5F5F7" cx="67.797" cy="106.89" rx="67.797" ry="12.668">
                                                        </ellipse>
                                                        <path
                                                            d="M122.034 69.674L98.109 40.229c-1.148-1.386-2.826-2.225-4.593-2.225h-51.44c-1.766 0-3.444.839-4.592 2.225L13.56 69.674v15.383h108.475V69.674z"
                                                            fill="#AEB8C2"></path>
                                                        <path
                                                            d="M101.537 86.214L80.63 61.102c-1.001-1.207-2.507-1.867-4.048-1.867H31.724c-1.54 0-3.047.66-4.048 1.867L6.769 86.214v13.792h94.768V86.214z"
                                                            fill="url(#linearGradient-1)" transform="translate(13.56)"></path>
                                                        <path
                                                            d="M33.83 0h67.933a4 4 0 0 1 4 4v93.344a4 4 0 0 1-4 4H33.83a4 4 0 0 1-4-4V4a4 4 0 0 1 4-4z"
                                                            fill="#F5F5F7"></path>
                                                        <path
                                                            d="M42.678 9.953h50.237a2 2 0 0 1 2 2V36.91a2 2 0 0 1-2 2H42.678a2 2 0 0 1-2-2V11.953a2 2 0 0 1 2-2zM42.94 49.767h49.713a2.262 2.262 0 1 1 0 4.524H42.94a2.262 2.262 0 0 1 0-4.524zM42.94 61.53h49.713a2.262 2.262 0 1 1 0 4.525H42.94a2.262 2.262 0 0 1 0-4.525zM121.813 105.032c-.775 3.071-3.497 5.36-6.735 5.36H20.515c-3.238 0-5.96-2.29-6.734-5.36a7.309 7.309 0 0 1-.222-1.79V69.675h26.318c2.907 0 5.25 2.448 5.25 5.42v.04c0 2.971 2.37 5.37 5.277 5.37h34.785c2.907 0 5.277-2.421 5.277-5.393V75.1c0-2.972 2.343-5.426 5.25-5.426h26.318v33.569c0 .617-.077 1.216-.221 1.789z"
                                                            fill="#DCE0E6"></path>
                                                    </g>
                                                    <path
                                                        d="M149.121 33.292l-6.83 2.65a1 1 0 0 1-1.317-1.23l1.937-6.207c-2.589-2.944-4.109-6.534-4.109-10.408C138.802 8.102 148.92 0 161.402 0 173.881 0 184 8.102 184 18.097c0 9.995-10.118 18.097-22.599 18.097-4.528 0-8.744-1.066-12.28-2.902z"
                                                        fill="#DCE0E6"></path>
                                                    <g transform="translate(149.65 15.383)" fill="#FFF">
                                                        <ellipse cx="20.654" cy="3.167" rx="2.849" ry="2.815"></ellipse>
                                                        <path d="M5.698 5.63H0L2.898.704zM9.259.704h4.985V5.63H9.259z"></path>
                                                    </g>
                                                </g>
                                            </svg>
                                            <p>Không có dữ liệu</p>
                                        </div>
                                    ';
}
echo '
                                    ';
foreach ($listBlog as $info) {
    echo '                                        <div class="item-article mt-2">
                                            <a href="/tin-tuc/';
    echo $info['slug'];
    echo '">
                                                <div class="card card-service">
                                                    <div class="card-body card-body-blog c-p-16 c-p-lg-8">
                                                        <div class="article-thumb c-mb-lg-0">
                                                            <img src="/assets/images/lazyload.gif" data-src="';
    echo $info['image'];
    echo '" class="article-thumb-image lazyload" alt="article-thumbnail">
                                                        </div>
                                                        <div class="article-body" style="flex: 1">
                                                            <div class="article-title text-limit limit-2 limit-lg-3 fz-lg-13 lh-lg-20 c-ml-12">
                                                                ';
    echo $info['title'];
    echo '                                                            </div>
                                                            <div class="article--description d-none d-lg-block c-pt-16 c-ml-12" style="height: 80px;overflow: hidden">
                                                                <p>';
    echo base64_decode($info['content']);
    echo '</p>
                                                            </div>
                                                            <div class="article-info mt-2 c-mt-lg-6 c-ml-12">
                                                                <div class="datetime">
                                                                    ';
    echo $info['created_at'];
    echo '                                                                </div>
                                                                <div class="author c-ml-4 bread-word text-limit limit-1">
                                                                    ADMIN</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    ';
}
echo '                                </div>
                            </div>
                            <div class="c-pt-32">
                                <div class="paging-account mt-3">
                                    <div class="default-paginate">
                                        ';
$total = $db->num_rows('SELECT * FROM `posts` WHERE ' . $where . ' ORDER BY `id` DESC ');
if ($limit < $total) {
    echo pagination_client('/tin-tuc?keyword=' . $keyword . '&', $from, $total, $limit);
}
echo '                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-none d-lg-block">
                <div class="sub-article-block">
                    <div class="sub-article-block-header d-flex justify-content-between align-items-center c-px-16 c-py-12">
                        <h3 class="fw-700 fz-24 lh-32">
                            Chuyên mục
                        </h3>
                    </div>
                    ';
foreach ($db->get_list(' SELECT * FROM `post_category` WHERE `status` = 1 ') as $category) {
    echo '                        <div class="sub-article c-px-16">
                            <div class="row">
                                <div class="col-6 sub-article--thumbnail-container">
                                    <div class="sub-article--thumbnail">
                                        <a href="/tin-tuc?keyword=&category=';
    echo $category['id'];
    echo '">
                                            <img src="/assets/images/lazyload.gif" data-src="';
    echo $category['icon'];
    echo '" alt="';
    echo $category['name'];
    echo '" class="sub-article--thumbnail__image lazyload">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-6 sub-article--info">
                                    <a href="/tin-tuc?keyword=&category=';
    echo $category['id'];
    echo '" class="sub-article--title__link">
                                        ';
    echo $category['name'];
    echo ' (';
    echo $db->get_row(' SELECT COUNT(id) FROM `posts` WHERE `category_id` = \'' . $category['id'] . '\' ')['COUNT(id)'] ?? 0;
    echo ')
                                    </a>
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
