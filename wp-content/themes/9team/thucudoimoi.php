<?php 
/**
*   The template for "Module Thu Cũ Đổi Mới"
*   Template Name: Thu Cũ Đổi Mới 
*   @package Astra
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

<link rel="stylesheet" type="text/css" href="./wp-content/themes/9team/TCDM/css/tcdm.css">

<?php if ( astra_page_layout() == 'left-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

	<div id="primary" <?php astra_primary_class(); ?>>

        <section class="container thu-cu" id="app">
            <div class="col-sm-12 thu-cu__header">
                <div class="header__box-the-le" style="width: 100%">
                    <div class="the-le__content">
                        <?php 
                        while ( have_posts() ) : the_post();
                            the_content();
                        endwhile;
                        wp_reset_query();
                        ?>
                    </div>
                    <button v-on:click="moreInfo('300px')" id="moreInfo"> Xem thêm </button>
                </div>
            </div>

            <div class="col-sm-12 thu-cu__content">
                <div class="col-sm-12 may-cu">
                    <div class="col-sm-12 may-cu__box-search">
                        <div class="col-sm-12 may-cu__title">
                            <h3>CHỌN MÁY CŨ CỦA BẠN</h3>
                        </div>
                        <input type="text" class="form-control box-search__main" v-model="search" placeholder="Tìm kiếm điện thoại cũ của bạn...">
                        <div class="box-search__result" v-if="search.length >= 2">
                            <ul id="search__result__listsuggest">
                                <li v-for="product in searchListOldProducts">
                                    <a href="#" @click="selectOldProduct(product.ProductID); showModal = true; search = '' ">
                                        <img v-lazy="product.Img" />
                                        <h3>{{ product.ProductName }}</h3>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <a class="box-search__modal" href="#" @click="showModal = true">Xem danh sách máy cũ được đổi</a>

                        <modal v-if="showModal" @close="showModal = false; dataGuest.oldProductID = null; dataGuest.newProductID = null; dataGuest.finalPrice = null; dataGuest.newProductInfo = null; dataGuest.oldProductInfo = null;">
                            <template v-if="!dataGuest.oldProductID && !dataGuest.newProductID">
                                <h3 slot="header">DANH SÁCH MÁY CŨ ĐƯỢC ĐỔI</h3>
                                <div slot="body">
                                    <ul class="col-sm-12 may-cu__box-brand">
                                        <li class="box-brand__item" v-for="group in oldProducts.groups">
                                            <input type="radio" v-bind:id="group" v-bind:value="group"
                                                v-model="selectedGroup">
                                            <label v-bind:for="group">{{ group }}</label>
                                        </li>
                                    </ul>
                                    <div class="col-sm-12 may-cu__list">
                                        <products 
                                            v-for="(product, index) in filterListOldProducts" 
                                            v-bind:key="index"
                                            v-bind:id="product.ProductID" 
                                            v-bind:img="product.Img"
                                            v-bind:name="product.ProductName"
                                        >
                                        </products>
                                    </div>
                                </div>
                            </template>
                            <template v-else-if="dataGuest.oldProductID && !dataGuest.newProductID">
                                <h3 slot="header">LỰA CHỌN TÌNH TRẠNG MÁY CŨ CỦA BẠN</h3>
                                <div slot="body">
                                    <div class="col-sm-12 tinh-trang-may__content">
                                        <div class="col-sm-3 content__main-img">
                                            <img v-lazy="dataGuest.oldProductInfo[0].Img">
                                        </div>
                                        <div class="col-sm-9 content__about-may">
                                            <p>Tên Điện Thoại:
                                                <strong>{{ dataGuest.oldProductInfo[0].ProductName }}</strong></p>
                                            <div class="radio-group">
                                                <div class="radio-group__box">
                                                    <input type="radio" id="loai1"
                                                        value="1"
                                                        v-model="dataGuest.statusOldProduct">
                                                    <label class="radio-group__item" for="loai1">
                                                        <div class="radio-group__item__img">
                                                            <i class="icon-check"></i>
                                                        </div>
                                                        <div class="radio-group__item__span">
                                                            <span>Loại 1: Máy hoạt động, màn đẹp, ngoại hình đẹp.</span>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="radio-group__box">
                                                    <input type="radio" id="loai2"
                                                        value="2"
                                                        v-model="dataGuest.statusOldProduct">
                                                    <label class="radio-group__item" for="loai2">
                                                        <div class="radio-group__item__img">
                                                            <i class="icon-check"></i>
                                                        </div>
                                                        <div class="radio-group__item__span">
                                                            <span>Loại 2: Máy hoạt động, màn đẹp, ngoại hình 90-95%.</span>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="radio-group__box">
                                                    <input type="radio" id="loai3"
                                                        value="3"
                                                        v-model="dataGuest.statusOldProduct">
                                                    <label class="radio-group__item" for="loai3">
                                                        <div class="radio-group__item__img">
                                                            <i class="icon-check"></i>
                                                        </div>
                                                        <div class="radio-group__item__span">
                                                            <span>Loại 3: Máy hoạt động, màn đẹp, ngoại hình xấu, thiếu chức năng.</span>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="radio-group__box">
                                                    <input type="radio" id="loai4"
                                                        value="4"
                                                        v-model="dataGuest.statusOldProduct">
                                                    <label class="radio-group__item" for="loai4">
                                                        <div class="radio-group__item__img">
                                                            <i class="icon-check"></i>
                                                        </div>
                                                        <div class="radio-group__item__span">
                                                            <span>Loại 4: Máy hoạt động, màn bị lỗi nhẹ.</span>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="radio-group__box">
                                                    <input type="radio" id="loai5"
                                                        value="5"
                                                        v-model="dataGuest.statusOldProduct">
                                                    <label class="radio-group__item" for="loai5">
                                                        <div class="radio-group__item__img">
                                                            <i class="icon-check"></i>
                                                        </div>
                                                        <div class="radio-group__item__span">
                                                            <span>Loại 5: Máy hoạt động, màn hình lỗi nặng.</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 may-moi__title">
                                        <h3>CHỌN ĐIỆN THOẠI BẠN MUỐN ĐỔI MỚI</h3>
                                        <ul class="col-sm-12 may-moi__box-brand">
                                            <li class="box-brand__item" v-for="group in newProducts.groups">
                                                <input type="radio" v-bind:id="group" v-bind:value="group"
                                                    v-model="selectedGroup">
                                                <label v-bind:for="group">{{ group }}</label>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="col-sm-12 may-moi__list">
                                        <products v-for="(product, index) in filterListNewProducts" 
                                            v-bind:key="index"
                                            v-bind:id="product.DefaultID" 
                                            v-bind:id2="product.ProductID"
                                            v-bind:img="product.Img"
                                            v-bind:name="product.ProductName" 
                                            v-bind:price="product.Price"
                                            v-bind:saleprice="product.SalePrice"
                                        >
                                        </products>
                                    </div>
                                </div>
                            </template>
                            <template v-else-if="dataGuest.newProductID && !dataGuest.oldProductID">
                                <div slot="body">
                                    <div class="col-sm-12 may-cu__box-search">
                                        <h3 slot="header">TÌM KIẾM MÁY CŨ BẠN ĐANG DÙNG</h3>
                                        <input type="text" class="form-control box-search__main" v-model="search"
                                            placeholder="Tìm kiếm điện thoại cũ của bạn...">
                                        <div class="box-search__result" v-if="search.length >= 2">
                                            <ul id="search__result__listsuggest">
                                                <li v-for="product in searchListOldProducts">
                                                    <a href="#" @click="selectOldProduct(product.ProductID); search = ''">
                                                        <img v-lazy="product.Img" />
                                                        <h3>{{ product.ProductName }}</h3>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <h3 class="may-cu__title">DANH SÁCH MÁY CŨ ĐƯỢC ĐỔI</h3>
                                    <ul class="col-sm-12 may-cu__box-brand">
                                        <li class="box-brand__item" v-for="group in oldProducts.groups">
                                            <input type="radio" v-bind:id="group" v-bind:value="group"
                                                v-model="selectedGroup">
                                            <label v-bind:for="group">{{ group }}</label>
                                        </li>
                                    </ul>
                                    <div class="col-sm-12 may-cu__list">
                                        <products 
                                            v-for="(product, index) in filterListOldProducts" 
                                            v-bind:key="index"
                                            v-bind:id="product.ProductID" 
                                            v-bind:img="product.Img"
                                            v-bind:name="product.ProductName"
                                        >
                                        </products>
                                    </div>
                                </div>

                            </template>
                            <template v-else-if="dataGuest.newProductID && dataGuest.oldProductID && !dataGuest.finalPrice">
                                <h3 slot="header">LỰA CHỌN TÌNH TRẠNG MÁY CŨ CỦA BẠN</h3>
                                <div slot="body">
                                    <div class="col-sm-12 tinh-trang-may__content">
                                        <div class="col-sm-3 content__main-img">
                                            <img v-lazy="dataGuest.oldProductInfo[0].Img">
                                        </div>
                                        <div class="col-sm-9 content__about-may">
                                            <p>Tên Điện Thoại:
                                                <strong>{{ dataGuest.oldProductInfo[0].ProductName }}</strong></p>
                                            <div class="radio-group">
                                            <div class="radio-group__box">
                                                    <input type="radio" id="loai1"
                                                        value="1"
                                                        v-model="dataGuest.statusOldProduct">
                                                    <label class="radio-group__item" for="loai1">
                                                        <div class="radio-group__item__img">
                                                            <i class="icon-check"></i>
                                                        </div>
                                                        <div class="radio-group__item__span">
                                                            <span>Loại 1: Máy hoạt động, màn đẹp, ngoại hình đẹp.</span>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="radio-group__box">
                                                    <input type="radio" id="loai2"
                                                        value="2"
                                                        v-model="dataGuest.statusOldProduct">
                                                    <label class="radio-group__item" for="loai2">
                                                        <div class="radio-group__item__img">
                                                            <i class="icon-check"></i>
                                                        </div>
                                                        <div class="radio-group__item__span">
                                                            <span>Loại 2: Máy hoạt động, màn đẹp, ngoại hình 90-95%.</span>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="radio-group__box">
                                                    <input type="radio" id="loai3"
                                                        value="3"
                                                        v-model="dataGuest.statusOldProduct">
                                                    <label class="radio-group__item" for="loai3">
                                                        <div class="radio-group__item__img">
                                                            <i class="icon-check"></i>
                                                        </div>
                                                        <div class="radio-group__item__span">
                                                            <span>Loại 3: Máy hoạt động, màn đẹp, ngoại hình xấu, cấn nặng, thiếu chức năng.</span>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="radio-group__box">
                                                    <input type="radio" id="loai4"
                                                        value="4"
                                                        v-model="dataGuest.statusOldProduct">
                                                    <label class="radio-group__item" for="loai4">
                                                        <div class="radio-group__item__img">
                                                            <i class="icon-check"></i>
                                                        </div>
                                                        <div class="radio-group__item__span">
                                                            <span>Loại 4: Máy hoạt động, màn bị lỗi nhẹ.</span>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="radio-group__box">
                                                    <input type="radio" id="loai5"
                                                        value="5"
                                                        v-model="dataGuest.statusOldProduct">
                                                    <label class="radio-group__item" for="loai5">
                                                        <div class="radio-group__item__img">
                                                            <i class="icon-check"></i>
                                                        </div>
                                                        <div class="radio-group__item__span">
                                                            <span>Loại 5: Máy hoạt động, màn hình lỗi nặng.</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 may-moi__title may-moi__title-bold">
                                        <h3>GIÁ THU CŨ ĐỔI MỚI</h3>
                                    </div>
                                    <div class="col-sm-12 gia-may__content">
                                        <div class="col-sm-3 content__main-img">
                                            <img v-lazy="dataGuest.newProductInfo[0].Img">
                                        </div>
                                        <div class="col-sm-9 content__about">
                                            <h3>{{ dataGuest.newProductInfo[0].ProductName }}</h3>

                                            <div class="about__gia-may-moi">
                                                Giá bán <b>{{ dataGuest.newProductInfo[0].ProductName }}</b>: <strong>{{ formatPrice(dataGuest.newPrice) }}</strong>
                                            </div>

                                            <div class="about__gia-may-cu">
                                                Định giá <b>{{ dataGuest.oldProductInfo[0].ProductName }} cũ - Loại {{ dataGuest.statusOldProduct }}</b>: <strong>{{ formatPrice(dataGuest.oldPrice) }}</strong>
                                            </div>
                                            
                                            <div class="about__gia-chenh-lech">
                                                Số tiền bù chênh lệch (tham khảo):
                                                <strong>{{ formatPrice(dataGuest.newPrice - getOldPrice) }}</strong>
                                            </div>
                                        </div>
                                        <button id="xacnhan" class="btn btn-default"
                                            @click="dataGuest.finalPrice = dataGuest.newPrice - getOldPrice"> Đổi ngay 
                                        </button>
                                        <p class="content__note">
                                            * Giá bù chênh lệch mang tính tham khảo - Quý khách vui lòng đặt hàng để được tư vấn cụ thể hơn về ưu đãi. 
                                            <br/> * Trường hợp Quý khách chỉ muốn bán lại sản phẩm, vui lòng đến các cửa CellphoneS để được kiểm tra và báo giá chính xác.
                                        </p>
                                    </div>
                                </div>
                            </template>
                            <template v-else-if="dataGuest.newProductID && dataGuest.oldProductID && dataGuest.finalPrice">
                                <h3 slot="header">THU CŨ ĐỔI MỚI</h3>
                                <div slot="body">
                                    <div class="col-sm-12 gia-may__content">
                                        <div class="col-sm-4 content__main-img">
                                            <img v-lazy="dataGuest.newProductInfo[0].Img">
                                        </div>
                                        <div class="col-sm-8 content__about">
                                            <h3>{{ dataGuest.newProductInfo[0].ProductName }}</h3>

                                            <div class="about__gia-may-moi">
                                                Giá bán <b>{{ dataGuest.newProductInfo[0].ProductName }}</b>: <strong>{{ formatPrice(dataGuest.newPrice) }}</strong>
                                            </div>
                                            
                                            <div class="about__gia-may-cu">
                                                Định giá <b>{{ dataGuest.oldProductInfo[0].ProductName }} cũ - Loại {{ dataGuest.statusOldProduct }}</b>: <strong>{{ formatPrice(dataGuest.oldPrice) }}</strong>
                                                <!-- Định giá <b>{{ dataGuest.oldProductInfo[0].ProductName }} cũ - Loại {{ dataGuest.statusOldProduct }}</b>: <strong>{{ formatPrice(dataGuest.oldPrice + dataGuest.pmh) }}</strong> -->
                                            </div>
                                            
                                            <div class="about__gia-chenh-lech">
                                                Số tiền bù chênh lệch (tham khảo):
                                                <strong>{{ formatPrice(dataGuest.newPrice - dataGuest.oldPrice) }}</strong>
                                            </div>
                                        </div>
                                        <div class="col-sm-8 content__about-khach-hang">
                                            <div class="col-sm-6 about__form-store">
                                                <h3>Lựa chọn cửa hàng tại khu vực:</h3>
                                                <div class="form-group">
                                                    <select class="form-control form-store__select" v-model="dataGuest.districtID">
                                                        <option disabled value="">Vui lòng lựa chọn Quận/Huyện </option>
                                                        <option v-for="district in filterDistrict" v-bind:value="district.id">
                                                            {{ district.prefix }} {{ district.name }}
                                                        </option>
                                                    </select>
                                                    <br/>
                                                    <div class="form-store_radio-group">
                                                        <template v-for="store in filterStore">
                                                            <div id="address">
                                                                <input type="radio" v-bind:id="store.code" v-bind:value="store.external_id" v-model="dataGuest.shopID">
                                                                <label v-bind:for="store.code">{{ store.address }}</label>
                                                            </div>
                                                        </template>
                                                    </div>

                                                </div>
                                            </div>
    
                                            <div class="col-sm-6 about__form-khach-hang">
                                                <h3>Thông Tin Mua Hàng</h3>
                                                <form id="frmThanhToan"  method="post" @submit="submitForm">
                                                    <div class="form-group form__name">
                                                        <label>Họ và Tên:</label>
                                                        <input type="text" class="form-control" name="billing[ho_ten]" v-model="dataGuest.fullName">
                                                    </div>
                                                    <div class="form-group form__phone">
                                                        <label>Số Điện Thoại:</label>
                                                        <input type="text" class="form-control" name="billing[dien_thoai]" v-model="dataGuest.phoneNumber">
                                                    </div>
                                                    <div class="form-group form__mail">
                                                        <label>Địa chỉ mail:</label>
                                                        <input type="email" class="form-control" name="billing[mail]" v-model="dataGuest.mail">
                                                    </div>

                                                    <button type="submit" class="btn btn-default">Đặt Ngay</button>

                                                    <p class="content__note">
                                                        * Giá bù chênh lệch mang tính tham khảo - Quý khách vui lòng đặt hàng để được tư vấn cụ thể hơn về ưu đãi. 
                                                        <br/> * Trường hợp Quý khách chỉ muốn bán lại sản phẩm, vui lòng đến các cửa CellphoneS để được kiểm tra và báo giá chính xác.
                                                    </p>                                                    
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </modal>
                    </div>
                    <div class="col-sm-12 line">
                        <div class="line__other"></div>
                        <span>Hoặc</span>
                    </div>

                    <div class="col-sm-12 may-moi">
                        <div class="col-sm-12 may-moi__title">
                            <h3>Chọn điện thoại bạn muốn đổi mới</h3>
                            <ul class="col-sm-12 may-moi__box-brand">
                                <li class="box-brand__item" v-for="group in newProducts.groups">
                                    <input type="radio" v-bind:id="group" v-bind:value="group" v-model="selectedGroup">
                                    <label v-bind:for="group">{{ group }}</label>
                                </li>
                            </ul>
                        </div>

                        <div class="col-sm-12 may-moi__list">
                            <products 
                                v-for="(product, index) in filterListNewProducts" 
                                v-bind:key="index" 
                                v-bind:id="product.DefaultID" 
                                v-bind:id2="product.ProductID"
                                v-bind:img="product.Img" 
                                v-bind:name="product.ProductName" 
                                v-bind:price="product.Price"
                                v-bind:saleprice="product.SalePrice"
                            >
                            </products>
                        </div>
                    </div>
                </div>
        </section>

        <script type="text/x-template" id="modal-template">
            <transition name="modal">
                <div class="modal-tcdm">
                    <div class="modal-wrapper">
                        <div class="modal-container">
                            <div class="modal-header">
                                <slot name="header"></slot>
                                <button class="modal-default-button" @click="$emit('close')">
                                    x
                                </button>
                            </div>

                            <div class="modal-body">
                                <slot name="body"></slot>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </script>

        <script src="https://cdn.jsdelivr.net/npm/vue"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-lazyload/1.3.1/vue-lazyload.js"></script>
        <script src="./wp-content/themes/9team/TCDM/js/thucudoimoi.js?v=1.0.0"></script>
    
    </div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>