<?php 
class PromotionSlide
{   
    private function get_table_name() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'PromotionSlide';
        return $table_name;
    }

    public function get_cache_key() {
        $key_cache = "PromotionSlide";
        return $key_cache;
    }

    public function create_db() {
        if ( isset( $_POST['creat-db-slide'] ) && $_POST['creat-db-slide'] == true ) {
            global $wpdb;
            $table_name = $this->get_table_name();
            
            // check table exists
            $query_check = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

            if ( $wpdb->get_var( $query_check ) == $table_name ) {
                echo "Database đã tồn tại!";
            } else {
                $charset_collate = $wpdb->get_charset_collate();
                $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                        ID          INT(1)          NOT NULL AUTO_INCREMENT,
                        TYPE        VARCHAR(100)    NOT NULL DEFAULT '0',
                        STATUS      INT(1)          NOT NULL DEFAULT 0,
                        STT         INT(10)          NOT NULL DEFAULT 0,
                        URL         VARCHAR(100)    NOT NULL DEFAULT 'https://dienthoaivui.com.vn',
                        CONTENT     VARCHAR(100)    NOT NULL DEFAULT '0', 
                        IMAGE       VARCHAR(200)    NOT NULL DEFAULT 'https://dienthoaivui.com.vn/wp-content/uploads/2019/05/khai-truong-DTV-690-AuCo-1600x600.png', 
                        IMAGE_M     VARCHAR(200)    NOT NULL DEFAULT 'https://dienthoaivui.com.vn/wp-content/uploads/2019/05/khai-truong-DTV-690-AuCo-1600x600-800x300.png',
                        B_DATE      DATETIME        NOT NULL DEFAULT '0000-00-00 00:00:00', 
                        PRIMARY KEY (ID)
                )  $charset_collate ;";
                dbDelta( $sql );
                
                for($i=1;$i<10;$i++){
                    if($i<=6){ $insert = "INSERT INTO ".$table_name." (TYPE) VALUES ('BIG')";}
                    else if($i<=8){$insert = "INSERT INTO ".$table_name." (TYPE) VALUES ('SMALL')";}
                         else if($i=9) {$insert = "INSERT INTO ".$table_name."(TYPE) VALUES ('BOTTOM')";}
                    $wpdb->query($insert);
                }

                echo "Đã khởi tạo thành công!";
                $url=$_SERVER['REQUEST_URI'];
                header("Refresh: 1; URL=$url");
            }
        }
    }
    public function delete_db() {
        if ( isset( $_POST['delete-db-slide'] ) && $_POST['delete-db-slide'] == true ) {
            global $wpdb;
            $table_name = $this->get_table_name();
            $sql = "DROP TABLE IF EXISTS ".$table_name;
            $wpdb->query($sql);
            echo "Đã xoá Database";
            $url=$_SERVER['REQUEST_URI'];
            header("Refresh: 1; URL=$url");
        }
    } 

    public function delete_cache() {
        if ( isset( $_POST['delete-cache-slide'] ) && $_POST['delete-cache-slide'] == true ) {
            $key_cache = $this->get_cache_key();
            delete_transient($key_cache);

            // Tạo lại cache

            global $wpdb;
            $table_name = $this->get_table_name();

            $query_check = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
            if ( $wpdb->get_var( $query_check ) != $table_name ) {
                echo "Database chưa tồn tại, không thể xoá / tạo cache ! <br/> Vui lòng ấn nút Khởi tạo Database!";
                return;
            } else {
                $query =" SELECT * FROM ". $table_name ." ORDER BY STT";           
                $get_data = $wpdb->get_results($query);
                set_transient( $key_cache, $get_data );
            }

            echo "Đã xoá Cache";
            $url=$_SERVER['REQUEST_URI'];
            header("Refresh: 1; URL=$url");
        }
    }

    public function admin_slider_banner() {
        $this->save_data_banner();
        // save_data_cat();

        global $wpdb;
        $table_name = $this->get_table_name();
        wp_enqueue_media();

        $query_check = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
        if ( $wpdb->get_var( $query_check ) != $table_name ) {
            echo "Database chưa tồn tại! <br/> Vui lòng ấn nút Khởi tạo Database!";
            return;
        } else {
            
            $query =" SELECT * FROM ". $table_name ." ORDER BY STT";           
            $get_data = $wpdb->get_results($query);

            echo "<div class='banner-wrapper row' style='margin:0px'>";
                echo "<div class='row' style='margin:0px'>";
                echo "<h2>Banner lớn (800x600)</h2>";
                foreach ($get_data as $item) {
                    if ($item->TYPE == "BIG") {
                        ?>
                            <div class='col-sm-6 col-xs-12'>
                                <div class="image-preview col-sm-4 col-xs-4"><img width="100%" src="<?php echo $item->IMAGE; ?>" /></div>
                                <div class="info-preview col-sm-8 col-xs-8">
                                    <form method="post" enctype="multipart/form-data">
                                        <table style="font-size:15px;" >
                                            <tr>
                                                <td style="text-align:left; padding-right: 5px;">Link ảnh đến</td>
                                                <td><input type="text" name = "link-banner-<?php echo $item->ID ?>" value = "<?php echo $item->URL ?>" class = "input"/></td> 
                                            </tr>
                                            <tr>
                                                <td style="text-align:left; padding-right: 5px;">Điền nội dung</td>
                                                <td><input type="text" name = "content-banner-<?php echo $item->ID ?>" value = "<?php echo $item->CONTENT ?>" class = "input"/></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:left; padding-right: 5px;">Tải ảnh</td>
                                                <td>
                                                    <input type="text" name="image-url-<?php echo $item->ID ?>" class="image_url_<?php echo $item->ID ?>" value="<?php echo $item->IMAGE ?>">
                                                    <input type="button" name="upload-btn" id="upload-btn" class="upload_banner_big button-secondary" value="Upload Image">
                                                    <input type="hidden" name="image-mobile-<?php echo $item->ID ?>" class="image_mobile_<?php echo $item->ID ?>" value="<?php echo $item->IMAGE_M ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:left; padding-right: 5px;">Bật/Tắt</td>
                                                <td><input type="text" name = "on-off-<?php echo $item->ID ?>" value = "<?php echo $item->STATUS ?>" class = "input"/></td> 
                                            </tr>
                                            <tr>
                                                <td style="text-align:left; padding-right: 5px;">Vị trí</td>
                                                <td><input type="text" name = "orderby-<?php echo $item->ID ?>" value = "<?php echo $item->STT ?>" class = "input"/></td> 
                                            </tr>
                                            <tr>
                                                <td style="text-align:left; padding-right: 5px;">Hẹn lịch (YYYY-MM-DD HH:MM:SS)</td>
                                                <td><input type="text" name = "schedule-<?php echo $item->ID ?>" value = "<?php echo $item->B_DATE ?>" class = "input"/></td> 
                                            </tr>
                                        </table>
                                        <?php  submit_button( 'Lưu ','primary', 'save-banner-'. $item->ID); ?>
                                    </form>
                                </div>
                            </div>
                        <?php 
                    }
                }
                echo "</div>";

                echo "<div class='row' style='margin:0px'>";
                    echo "<div class='col-sm-6 col-xs-12'>";
                    echo "<h2>Banner nhỏ (385x175)</h2>";
                    foreach ($get_data as $item) {
                        if ($item->TYPE == "SMALL") {
                            ?>
                                <div class="image-preview col-sm-4 col-xs-4"><img width="100%" src="<?php echo $item->IMAGE; ?>" /></div>
                                <div class="info-preview col-sm-8 col-xs-8">
                                    <form method="post" enctype="multipart/form-data">
                                    
                                        <table style="font-size:15px;" >
                                            <tr>
                                                <td style="text-align:left; padding-right: 5px;">Link ảnh đến</td>
                                                <td><input type="text" name = "link-banner-<?php echo $item->ID ?>" value = "<?php echo $item->URL ?>" class = "input"/></td> 
                                            </tr>
                                            <tr>
                                                <td style="text-align:left; padding-right: 5px;">Tải ảnh</td>
                                                <td>
                                                    <input type="text" name="image-url-<?php echo $item->ID ?>" class="image_url_<?php echo $item->ID ?>" value="<?php echo $item->IMAGE ?>">
                                                    <input type="button" name="upload-btn" id="upload-btn" class="upload_banner_small button-secondary" value="Upload Image">
                                                    <input type="hidden" name="image-mobile-<?php echo $item->ID ?>" class="image_mobile_<?php echo $item->ID ?>" value="<?php echo $item->IMAGE_M ?>">
                                                </td>
                                            </tr>
                                        </table>
                                    <?php  submit_button( 'Lưu ','primary', 'save-banner-'. $item->ID); ?>
                                    </form>
                                </div>
                            <?php 
                        }
                    }
                echo "</div>";
            echo "</div>";

            echo "<div class='row' style='margin:0px'>";
                echo "<div class='col-sm-12 col-xs-12'>";
                echo "<h2>Banner dưới cùng (Desktop: 1200x150, mobile: 800x150)</h2>";
                foreach ($get_data as $item) {
                    if ($item->TYPE == "BOTTOM") {
                        ?>
                            <div class="image-preview col-sm-6 col-xs-6"><img width="100%" src="<?php echo $item->IMAGE; ?>" /></div>
                            <div class="image-preview col-sm-6 col-xs-6"><img width="100%" src="<?php echo $item->IMAGE_M; ?>" /></div>
                            <div class="info-preview col-sm-12 col-xs-12" style="margin-top: 15px;">
                                <form method="post" enctype="multipart/form-data">
                                    <table style="font-size:15px;" >
                                        <tr>
                                            <td style="text-align:left; padding-right: 5px;">Link ảnh đến</td>
                                            <td><input type="text" name = "link-banner-<?php echo $item->ID ?>" value = "<?php echo $item->URL ?>" class = "input"/></td> 
                                        </tr>
                                        <tr>
                                            <td style="text-align:left; padding-right: 5px;">Bật/Tắt</td>
                                            <td><input type="text" name = "on-off-<?php echo $item->ID ?>" value = "<?php echo $item->STATUS ?>" class = "input"/></td> 
                                        </tr>
                                        <tr>
                                            <td style="text-align:left; padding-right: 5px;">Tải ảnh</td>
                                            <td>
                                                <input type="text" name="image-url-<?php echo $item->ID ?>" class="image_url_<?php echo $item->ID ?>" value="<?php echo $item->IMAGE ?>">
                                                <input type="button" name="upload-btn" id="upload-btn" class="upload_banner_bottom button-secondary" value="Upload Image">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:left; padding-right: 5px;">Tải ảnh Mobile</td>
                                            <td>
                                                <input type="text" name="image-mobile-<?php echo $item->ID ?>" class="image_mobile_<?php echo $item->ID ?>" value="<?php echo $item->IMAGE_M ?>">
                                                <input type="button" name="upload-btn" id="upload-btn" class="upload_banner_bottom_mobile button-secondary" value="Upload Image">
                                            </td>
                                        </tr>
                                    </table>
                                <?php  submit_button( 'Lưu ','primary', 'save-banner-'. $item->ID); ?>
                                </form>
                            </div>
                        <?php 
                    }
                }
                echo "</div>";
            echo "</div>";
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    
                    var button_upload_big = $('.upload_banner_big');
                    var button_upload_small = $('.upload_banner_small');
                    var button_upload_bottom = $('.upload_banner_bottom');
                    var button_upload_bottom_mobile = $('.upload_banner_bottom_mobile');
                    var image_big;
                    var image_small;
                    var image_bottom;
                    var image_bottom_mobile;

                    button_upload_big.click(function(e) {
                        e.preventDefault();
                        
                        $(this).addClass('opened');
                        opened = $('.opened');

                        if ( image_big ) {
                            image_big.open();
                            return;
                        }

                        image_big = wp.media({ 
                            title: 'Upload Banner',
                            button: {
                                text: 'Use this media'
                                },
                            // mutiple: true if you want to upload multiple files at once
                            multiple: false
                        }).open();
                        // console.log(image);

                        image_big.on('select', function(e){
                            // This will return the selected image from the Media Uploader, the result is an object
                            var uploaded_image = image_big.state().get('selection').first();
                            // console.log(uploaded_image);
                            console.log(uploaded_image.toJSON()); // lấy size ảnh khác
                            // We convert uploaded_image to a JSON object to make accessing it easier
                            // Output to the console uploaded_image
                            var image_mobile = uploaded_image.toJSON().sizes.homepage_slide_big_m.url;
                            var image_url = uploaded_image.toJSON().url;

                            opened.prev().val(image_url);
                            opened.next().val(image_mobile);
                            button_upload_big.removeClass('opened');
                            opened = null;
                        });
                    });

                    button_upload_small.click(function(e) {
                        e.preventDefault();
                        
                        $(this).addClass('opened');
                        opened = $('.opened');

                        if ( image_small ) {
                            image_small.open();
                            return;
                        }

                        image_small = wp.media({ 
                            title: 'Upload Banner',
                            button: {
                                text: 'Use this media'
                                },
                            // mutiple: true if you want to upload multiple files at once
                            multiple: false
                        }).open();
                        // console.log(image);

                        image_small.on('select', function(e){
                            // This will return the selected image from the Media Uploader, the result is an object
                            var uploaded_image = image_small.state().get('selection').first();
                            // console.log(uploaded_image);
                            console.log(uploaded_image.toJSON()); // lấy size ảnh khác
                            // We convert uploaded_image to a JSON object to make accessing it easier
                            // Output to the console uploaded_image
                            var image_mobile = uploaded_image.toJSON().sizes.homepage_slide_small_m.url;
                            var image_url = uploaded_image.toJSON().url;

                            opened.prev().val(image_url);
                            opened.next().val(image_mobile);
                            button_upload_small.removeClass('opened');
                            opened = null;
                        });
                    });

                    button_upload_bottom.click(function(e) {
                        e.preventDefault();
                        
                        $(this).addClass('opened');
                        opened = $('.opened');

                        if ( image_bottom ) {
                            image_bottom.open();
                            return;
                        }

                        image_bottom = wp.media({ 
                            title: 'Upload Banner',
                            button: {
                                text: 'Use this media'
                                },
                            // mutiple: true if you want to upload multiple files at once
                            multiple: false
                        }).open();
                        // console.log(image);

                        image_bottom.on('select', function(e){
                            // This will return the selected image from the Media Uploader, the result is an object
                            var uploaded_image = image_bottom.state().get('selection').first();
                            // console.log(uploaded_image);
                            console.log(uploaded_image.toJSON()); // lấy size ảnh khác
                            // We convert uploaded_image to a JSON object to make accessing it easier
                            // Output to the console uploaded_image
                            var image_url = uploaded_image.toJSON().url;

                            opened.prev().val(image_url);
                            button_upload_bottom.removeClass('opened');
                            opened = null;
                        });
                    });
                    button_upload_bottom_mobile.click(function(e) {
                        e.preventDefault();
                        
                        $(this).addClass('opened');
                        opened = $('.opened');

                        if ( image_bottom_mobile ) {
                            image_bottom_mobile.open();
                            return;
                        }

                        image_bottom_mobile = wp.media({ 
                            title: 'Upload Banner',
                            button: {
                                text: 'Use this media'
                                },
                            // mutiple: true if you want to upload multiple files at once
                            multiple: false
                        }).open();
                        // console.log(image);

                        image_bottom_mobile.on('select', function(e){
                            // This will return the selected image from the Media Uploader, the result is an object
                            var uploaded_image = image_bottom_mobile.state().get('selection').first();
                            // console.log(uploaded_image);
                            console.log(uploaded_image.toJSON()); // lấy size ảnh khác
                            // We convert uploaded_image to a JSON object to make accessing it easier
                            // Output to the console uploaded_image
                            var image_url = uploaded_image.toJSON().url;

                            opened.prev().val(image_url);
                            button_upload_bottom_mobile.removeClass('opened');
                            opened = null;
                        });
                    });
                });
            </script>
            <?php
        }
    }

    private function save_data_banner() {
        global $wpdb;
        $table_name = $this->get_table_name();

        for ( $i=1; $i<10; $i++ ) {
            $save = "save-banner-".$i;
            if ( isset( $_POST[$save] ) && $_POST[$save] == true ) {
                $url = "link-banner-".$i;
                $content = "content-banner-".$i;
                $image = "image-url-".$i;
                $image_m = "image-mobile-".$i;
                $status = "on-off-".$i;
                $orderby = "orderby-".$i;
                $schedule = "schedule-".$i;
                $_schedule = "9999-12-30 00:00:00";

                if ( isset($_POST[$status]) ) {
                    $_POST[$status] = $_POST[$status];
                } else {
                    $_POST[$status] = 0;
                }
                if ( isset($_POST[$content]) ) {
                    $_POST[$content] = $_POST[$content];
                } else {
                    $_POST[$content] = '0';
                }

                if ( !empty($_POST[$schedule]) ) {
                    $_schedule = $_POST[$schedule];
                }

                $sql = $wpdb->prepare(" UPDATE {$table_name}
                                        SET 
                                            STATUS = %d,
                                            STT = %d,
                                            URL = %s,
                                            CONTENT = %s,
                                            IMAGE = %s,
                                            IMAGE_M = %s,
                                            B_DATE = %s
                                        WHERE ID = {$i}",
                     $_POST[$status], $_POST[$orderby], $_POST[$url], $_POST[$content], $_POST[$image], $_POST[$image_m], $_schedule );
                $wpdb->query($sql);
            }
        }
    }
}

function display_promotion_slide() {
    $key_cache = "PromotionSlide";
    $data = get_transient($key_cache);
    $count = 0;

    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $current_date = date('Y-m-d H:i:s');
    $current_date = strtotime($current_date);

    if (false == $data) { 
        return;
    } else {
    ?>
        <section class="row SlidePromotion">
            <div class="col-md-8 col-sm-12" style="padding: 0;">
                <div class="owl-carousel row big-banner">
                <?php 
                    foreach ($data as $item) {
                        if ( $item->TYPE == "BIG" && $item->STATUS == 1 && strtotime($item->B_DATE) > $current_date ) :
                ?>
                            <div class="item">
                                <a href="<?php echo $item->URL ?>">
                                    <img title="<?php echo esc_html($item->CONTENT); ?>" alt="<?php echo esc_html($item->CONTENT); ?>" class="lazyOwl" data-src="<?php echo $item->IMAGE ?>"/>
                                </a>
                            </div>
                <?php
                            $count++;
                        endif;
                    }
                ?>
                </div>
                <div class="promotion-nav row">
                    <ul>
                    <?php 
                        $i = 0;
                        foreach ($data as $key => $item) {
                            if ( $item->TYPE == "BIG" && $item->STATUS == 1 && strtotime($item->B_DATE) > $current_date ) :
                    ?>
                                <li style="width: <?php echo 100/$count; ?>%">
                                    <a href="javascript:void(0)" class="promotion-button" data-index="<?php echo $i; ?>">
                                        <?php echo $item->CONTENT; ?>
                                        <span><font class="fa fa-caret-up fa-2x"></font></span>
                                    </a>
                                </li>
                    <?php
                            $i++;
                            endif;
                        }
                    ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-4 col-sm-12" style="padding: 0;">
                <div class="row small-banner">
                    <?php 
                        foreach ($data as $key => $item) {
                            if ($item->TYPE == "SMALL") :
                    ?>
                        <div class="col-md-12 col-sm-6 right-small">
                            <a href="<?php echo $item->URL ?>">
                                <img title="Dịch vụ nổi bật - Điện Thoại Vui" alt="Dịch vụ nổi bật - Điện Thoại Vui" src="<?php echo $item->IMAGE; ?>"/>
                            </a>
                        </div>
                    <?php
                            endif;
                        }
                    ?>
                </div>
            </div>
            <div class="col-md-12 col-sm-12" style="padding: 0;">
                <div class="row bottom-banner">
                    <?php 
                        foreach ($data as $key => $item) {
                            if ($item->TYPE == "BOTTOM" && $item->STATUS == 1) :
                    ?>
                                <a href="<?php echo $item->URL ?>">
                                    <img title="Dịch vụ nổi bật - Điện Thoại Vui" alt="Dịch vụ nổi bật - Điện Thoại Vui" src="https://cdn.dienthoaivui.com.vn/wp-content/plugins/a3-lazy-load/assets/images/lazy_placeholder.gif" data-lazy-type="image" data-src="<?php echo $item->IMAGE; ?>" class="lazy-hidden"/>
                                </a>
                    <?php
                            endif;
                        }
                    ?>
                </div>
            </div>
        </section>

        <script defer>
            jQuery(document).ready(function() {
                var promotion_slide = jQuery('.owl-carousel');
                promotion_slide.owlCarousel({
                    navigation : false,
                    slideSpeed : 300,
                    lazyLoad: true,
                    paginationSpeed : 400,
                    singleItem: true,
                    autoPlay: true,
                    afterAction: function(){
                        var currentIndex = this.owl.currentItem;
                        jQuery('.promotion-button').each(function(){
                            jQuery(this).removeClass('active');
                            if(currentIndex == jQuery(this).data('index')){
                                jQuery(this).addClass('active');
                            }
                        });
                    },
                });  
                jQuery('.promotion-button').click(function(){
                    jQuery('.promotion-button').each(function(){
                        jQuery(this).removeClass('active');
                    });
                    promotion_slide.trigger('owl.goTo', jQuery(this).data('index'));
                    jQuery(this).addClass('active');
                });
            });
        </script>
    <?php 
    }
}

add_shortcode( 'promotion_slide', 'display_promotion_slide' );


