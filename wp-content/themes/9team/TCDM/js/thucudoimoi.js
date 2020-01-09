Vue.use(VueLazyload);

var formatter = new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND',
    minimumFractionDigits: 0,
});

var showProduct = Vue.component('products', {
    props: ['id', 'img', 'name', 'price', 'saleprice'],
    computed: {
        currentPrice: function() {
            var _price = ~~this.price;
            if (~~this.saleprice > 0) {
                _price = ~~this.saleprice
            }
            return _price;
        },
        getOldProductID: function() {
            return ~~ThuCuDoiMoi.dataGuest.oldProductID;
        },
        getNewProductID: function() {
            return ~~ThuCuDoiMoi.dataGuest.newProductID;
        },
        getOldPrice: function() {
            var statusOldProduct = ThuCuDoiMoi.dataGuest.statusOldProduct;
            if (statusOldProduct && statusOldProduct != '') {
                switch (statusOldProduct) {
                    case '1':
                        ThuCuDoiMoi.dataGuest.oldPrice = ThuCuDoiMoi.dataGuest.oldProductInfo[0].Thuloai_1;
                        break;
                    case '2':
                        ThuCuDoiMoi.dataGuest.oldPrice = ThuCuDoiMoi.dataGuest.oldProductInfo[0].Thuloai_2;
                        break;
                    case '3':
                        ThuCuDoiMoi.dataGuest.oldPrice = ThuCuDoiMoi.dataGuest.oldProductInfo[0].Thuloai_3;
                        break;
                    case '4':
                        ThuCuDoiMoi.dataGuest.oldPrice = ThuCuDoiMoi.dataGuest.oldProductInfo[0].Thuloai_4;
                        break;
                    case '5':
                        ThuCuDoiMoi.dataGuest.oldPrice = ThuCuDoiMoi.dataGuest.oldProductInfo[0].Thuloai_5;
                        break;
                }
            }
            return ~~ThuCuDoiMoi.dataGuest.oldPrice;
        },
        getFinalPrice: function() {
            var _price = 0;
            if (this.currentPrice > 0) {
                _price = this.currentPrice;
            }
            var finalPrice = _price - this.getOldPrice;
            return finalPrice;
        }
    },
    methods: {
        formatPrice: function(price) {
            if (!price) {
                return;
            } else {
                return formatter.format(price);
            }
        },
        selectOldProduct: function(id) {
            var _info = ThuCuDoiMoi.oldProducts.lists.filter(
                function(product) {
                    return product.ProductID === id;
                }
            );

            ThuCuDoiMoi.dataGuest.oldProductID = id;
            ThuCuDoiMoi.dataGuest.oldProductInfo = _info;
            ThuCuDoiMoi.dataGuest.oldPrice = _info[0].Thuloai_1;

            console.log('Old: ' + id);
            console.log(_info[0].Thuloai_1);
        },
        selectNewProduct: function(id) {
            var _info = ThuCuDoiMoi.newProducts.lists.filter(
                function(product) {
                    return product.DefaultID === id;
                }
            );

            console.log(_info);

            ThuCuDoiMoi.dataGuest.newProductID = id;
            ThuCuDoiMoi.dataGuest.newProductInfo = _info;

            ThuCuDoiMoi.dataGuest.newPrice = _info[0].Price;
            if (_info[0].SalePrice > 0) {
                ThuCuDoiMoi.dataGuest.newPrice = _info[0].SalePrice;
            }

            if (this.getOldProductID) {
                ThuCuDoiMoi.dataGuest.finalPrice = this.getFinalPrice;
            }

            // Show Modal
            ThuCuDoiMoi.showModal = true;

            console.log('New: ' + id);
            // alert("Hệ thống đang nâng cấp và cập nhật bảng giá cho tháng 9. Quý khách vui lòng liên hệ HotLine: 1800.2097 ! \nCellphoneS xin trân thành cảm ơn !")
        },
        scrollToTop: function() {
            var check = document.querySelector('.modal-container');
            if (check) {
                check.scrollTop = 0;
            }
        }
    },
    template: `<div v-if="!currentPrice || currentPrice < 0" @click="selectOldProduct(id); scrollToTop();" class="col-sm-2 col-xs-6 list__item" v-bind:id="id">
                    <img class="list__item--img" v-lazy="img">
                    <div class="list__item__about">
                        <h3 class="about--name">{{ name }}</h3>
                    </div>
                    <button>CHỌN</button>
                </div>
                <div v-else @click="selectNewProduct(id); scrollToTop();" class="col-sm-2 col-xs-6 list__item" v-bind:id="id">
                    <img class="list__item--img" v-lazy="img">
                    <div class="list__item__about">
                        <h3 class="about--name">{{ name }}</h3>

                        <template v-if="!getOldProductID">
                            <span class="about--price">{{ formatPrice(currentPrice) }}</span>
                        </template>

                        <template v-else>
                            <span>Giá: <strong class="gach">{{ formatPrice(currentPrice) }}</strong></span>
                            <span>Định giá máy cũ: <strong class="normal">{{ formatPrice(getOldPrice) }}</strong></span>
                            <span><b>Số tiền bù</b>: <strong class="bigfont">{{ formatPrice(getFinalPrice) }}</strong></span>
                        </template>
                    </div>
                    <button>ĐỔI NGAY</button>
                </div>
                `
});

var Modal = Vue.component('modal', {
    template: '#modal-template'
})


var ThuCuDoiMoi = new Vue({
    el: '#app',
    data: {
        oldProducts: {
            lists: [],
            groups: [],
            errored: false,
            loading: true,
        },
        newProducts: {
            lists: [],
            groups: [],
            errored: false,
            loading: true,
        },
        dataGuest: {
            oldProductID: null,
            oldProductInfo: null,
            newProductID: null,
            newProductInfo: null,
            finalPrice: null,
            oldPrice: null,
            newPrice: null,
            statusOldProduct: '1',
            fullName: null,
            phoneNumber: null,
            mail: null,
            note: null,
            defaultLocalID: '1',
            districtID: '',
            shopID: null,
            shopInfo: [],
        },
        dataStore: {
            listStore: [{"id":"89","code":"126HTM","external_id":"129","external_ud":"129","description":"CPSHN - 126HTM","address":"126 H\u1ed3 T\u00f9ng M\u1eadu, P. Mai D\u1ecbch, Q. C\u1ea7u Gi\u1ea5y","store_id":"1","phone":null,"google_link":"https:\/\/g.page\/CellphoneSHTM?share","district":null,"ward":null,"brand_id":"1","include_in_shop_list":null,"district_id":"28","image":null,"map_image":"\/media\/ltsoft\/shop_media\/shop_image_126HTM.png"},{"id":"88","code":"267QT","external_id":"127","external_ud":"127","description":"CPSHN - 267QT","address":"267 \u0110\u01b0\u1eddng Quang Trung, P. Quang Trung, Q. H\u00e0 \u0110\u00f4ng","store_id":"1","phone":null,"google_link":"https:\/\/goo.gl\/maps\/YmJii9y1whui2Qtv6","district":null,"ward":null,"brand_id":"1","include_in_shop_list":null,"district_id":"34","image":null,"map_image":"\/media\/ltsoft\/shop_media\/shop_image_267QT.png"},{"id":"75","code":"546TD","external_id":"114","external_ud":"114","description":"CPSHN - 546TD","address":"546 Tr\u01b0\u01a1ng \u0110\u1ecbnh, P. T\u00e2n Mai, Q. Ho\u00e0ng Mai - Ng\u00e3 t\u01b0 T\u00e2n Mai, Tr\u01b0\u01a1ng \u0110\u1ecbnh","store_id":"1","phone":null,"google_link":"https:\/\/goo.gl\/maps\/szLqLJFPHgGgksAJ6","district":null,"ward":null,"brand_id":"1","include_in_shop_list":null,"district_id":"38","image":null,"map_image":"\/media\/ltsoft\/shop_media\/shop_image_546TD.png"},{"id":"60","code":"300NT","external_id":"123","external_ud":"123","description":"CPSHN - 300NT","address":"300 Nguy\u1ec5n Tr\u00e3i, Q. Thanh Xu\u00e2n","store_id":"1","phone":null,"google_link":"https:\/\/goo.gl\/maps\/8znwkWLB1hCDSa9x5","district":null,"ward":null,"brand_id":"1","include_in_shop_list":"1","district_id":"52","image":null,"map_image":"\/media\/ltsoft\/shop_media\/shop_image_300NT.png"},{"id":"59","code":"283HTM","external_id":"7","external_ud":"92","description":"CPSHN - 283HTM","address":"283 H\u1ed3 T\u00f9ng M\u1eadu, P. C\u1ea7u Di\u1ec5n, Q. Nam T\u1eeb Li\u00eam","store_id":"1","phone":null,"google_link":"https:\/\/goo.gl\/maps\/diJ3eLuQZVyh8AYB7","district":null,"ward":null,"brand_id":"1","include_in_shop_list":"1","district_id":"42","image":null,"map_image":"\/media\/ltsoft\/shop_media\/shop_image_283HTM.png"},{"id":"55","code":"543NT","external_id":"3","external_ud":"56","description":"CPSHN - 543NT","address":"543 Nguy\u1ec5n Tr\u00e3i, P. Thanh Xu\u00e2n Nam, Q. Thanh Xu\u00e2n","store_id":"1","phone":null,"google_link":"https:\/\/goo.gl\/maps\/8TBsU2GcW5y51DEVA","district":null,"ward":null,"brand_id":"1","include_in_shop_list":"1","district_id":"52","image":null,"map_image":"\/media\/ltsoft\/shop_media\/shop_image_543NT.png"},{"id":"53","code":"160NKT","external_id":"1","external_ud":"54","description":"CPSHN - 160NKT","address":"160 Nguy\u1ec5n Kh\u00e1nh To\u00e0n, P. Quan Hoa, Q .C\u1ea7u Gi\u1ea5y","store_id":"1","phone":null,"google_link":"https:\/\/goo.gl\/maps\/NpxvVBu6twfBhhTv9","district":null,"ward":null,"brand_id":"1","include_in_shop_list":"1","district_id":"28","image":null,"map_image":"\/media\/ltsoft\/shop_media\/shop_image_160NKT.png"},{"id":"39","code":"524BM","external_id":"1552","external_ud":"32","description":"CPSHN - 524BM","address":"524 B\u1ea1ch Mai, P. Tr\u01b0\u01a1ng \u0110\u1ecbnh, Q. Hai B\u00e0 Tr\u01b0ng","store_id":"1","phone":null,"google_link":"https:\/\/goo.gl\/maps\/zPomiDmLzLiRcJM47","district":null,"ward":null,"brand_id":"1","include_in_shop_list":"1","district_id":"35","image":null,"map_image":"\/media\/ltsoft\/shop_media\/shop_image_524BM.png"},{"id":"37","code":"306CG","external_id":"301","external_ud":"37","description":"CPSHN - 306CG","address":"306 C\u1ea7u Gi\u1ea5y, P. D\u1ecbch V\u1ecdng, Q. C\u1ea7u Gi\u1ea5y","store_id":"1","phone":null,"google_link":"https:\/\/goo.gl\/maps\/qiy7H3NxWDcmcPe28","district":null,"ward":null,"brand_id":"1","include_in_shop_list":"1","district_id":"28","image":null,"map_image":"\/media\/ltsoft\/shop_media\/shop_image_306CG.png"},{"id":"36","code":"21AHB","external_id":"358","external_ud":"39","description":"CPSHN - 21AHB","address":"21A H\u00e0ng B\u00e0i, P. H\u00e0ng B\u00e0i, Q. Ho\u00e0n Ki\u1ebfm","store_id":"1","phone":null,"google_link":"https:\/\/goo.gl\/maps\/okax8hx87g8HmGpf7","district":null,"ward":null,"brand_id":"1","include_in_shop_list":"1","district_id":"37","image":null,"map_image":"\/media\/ltsoft\/shop_media\/shop_image_21AHB.png"},{"id":"35","code":"21TH","external_id":"357","external_ud":"40","description":"CPSHN - 21TH","address":"21 Th\u00e1i H\u00e0, P. Trung Li\u1ec7t, Q. \u0110\u1ed1ng \u0110a","store_id":"1","phone":null,"google_link":"https:\/\/goo.gl\/maps\/fPJF9CRfwLusBHRc7","district":null,"ward":null,"brand_id":"1","include_in_shop_list":"1","district_id":"32","image":null,"map_image":"\/media\/ltsoft\/shop_media\/shop_image_21TH.png"},{"id":"34","code":"117TH","external_id":"356","external_ud":"41","description":"CPSHN - 117TH","address":"117 Th\u00e1i H\u00e0, P. Trung Li\u1ec7t, Q. \u0110\u1ed1ng \u0110a","store_id":"1","phone":null,"google_link":"https:\/\/goo.gl\/maps\/KVkzRYobwtmbtPZo7","district":null,"ward":null,"brand_id":"1","include_in_shop_list":"1","district_id":"32","image":null,"map_image":"\/media\/ltsoft\/shop_media\/shop_image_117TH.png"},{"id":"32","code":"280NVC","external_id":"710","external_ud":"33","description":"CPSHN - 278NVC","address":"278-280 Nguy\u1ec5n V\u0103n C\u1eeb, P. Ng\u1ecdc L\u00e2m, Q. Long Bi\u00ean","store_id":"1","phone":null,"google_link":"https:\/\/goo.gl\/maps\/Wf8vdR2wWPtxXXLi8","district":null,"ward":null,"brand_id":"1","include_in_shop_list":"1","district_id":"39","image":null,"map_image":"\/media\/ltsoft\/shop_media\/shop_image_280NVC.png"}],
            listDistrict: [{"id":"37","name":"Ho\u00e0n Ki\u1ebfm","prefix":"Qu\u1eadn","province_id":"2","display_order":"20"},{"id":"48","name":"T\u00e2y H\u1ed3","prefix":"Qu\u1eadn","province_id":"2","display_order":"30"},{"id":"39","name":"Long Bi\u00ean","prefix":"Qu\u1eadn","province_id":"2","display_order":"40"},{"id":"28","name":"C\u1ea7u Gi\u1ea5y","prefix":"Qu\u1eadn","province_id":"2","display_order":"50"},{"id":"32","name":"\u0110\u1ed1ng \u0110a","prefix":"Qu\u1eadn","province_id":"2","display_order":"60"},{"id":"35","name":"Hai B\u00e0 Tr\u01b0ng","prefix":"Qu\u1eadn","province_id":"2","display_order":"70"},{"id":"38","name":"Ho\u00e0ng Mai","prefix":"Qu\u1eadn","province_id":"2","display_order":"80"},{"id":"52","name":"Thanh Xu\u00e2n","prefix":"Qu\u1eadn","province_id":"2","display_order":"90"},{"id":"25","name":"Ba \u0110\u00ecnh","prefix":"Qu\u1eadn","province_id":"2","display_order":"100"},{"id":"46","name":"S\u00f3c S\u01a1n","prefix":"Huy\u1ec7n","province_id":"2","display_order":"100"},{"id":"31","name":"\u0110\u00f4ng Anh","prefix":"Huy\u1ec7n","province_id":"2","display_order":"110"},{"id":"33","name":"Gia L\u00e2m","prefix":"Huy\u1ec7n","province_id":"2","display_order":"120"},{"id":"51","name":"Thanh Tr\u00ec","prefix":"Huy\u1ec7n","province_id":"2","display_order":"130"},{"id":"26","name":"Ba V\u00ec","prefix":"Huy\u1ec7n","province_id":"2","display_order":"140"},{"id":"29","name":"Ch\u01b0\u01a1ng M\u1ef9","prefix":"Huy\u1ec7n","province_id":"2","display_order":"150"},{"id":"30","name":"\u0110an Ph\u01b0\u1ee3ng","prefix":"Huy\u1ec7n","province_id":"2","display_order":"160"},{"id":"36","name":"Ho\u00e0i \u0110\u1ee9c","prefix":"Huy\u1ec7n","province_id":"2","display_order":"170"},{"id":"40","name":"M\u00ea Linh","prefix":"Huy\u1ec7n","province_id":"2","display_order":"180"},{"id":"41","name":"M\u1ef9 \u0110\u1ee9c","prefix":"Huy\u1ec7n","province_id":"2","display_order":"190"},{"id":"43","name":"Ph\u00fa Xuy\u00ean","prefix":"Huy\u1ec7n","province_id":"2","display_order":"200"},{"id":"44","name":"Ph\u00fac Th\u1ecd","prefix":"Huy\u1ec7n","province_id":"2","display_order":"210"},{"id":"45","name":"Qu\u1ed1c Oai","prefix":"Huy\u1ec7n","province_id":"2","display_order":"220"},{"id":"49","name":"Th\u1ea1ch Th\u1ea5t","prefix":"Huy\u1ec7n","province_id":"2","display_order":"230"},{"id":"50","name":"Thanh Oai","prefix":"Huy\u1ec7n","province_id":"2","display_order":"240"},{"id":"53","name":"Th\u01b0\u1eddng T\u00edn","prefix":"Huy\u1ec7n","province_id":"2","display_order":"250"},{"id":"54","name":"\u1ee8ng H\u00f2a","prefix":"Huy\u1ec7n","province_id":"2","display_order":"260"},{"id":"34","name":"H\u00e0 \u0110\u00f4ng","prefix":"Qu\u1eadn","province_id":"2","display_order":"270"},{"id":"47","name":"S\u01a1n T\u00e2y","prefix":"Th\u1ecb x\u00e3","province_id":"2","display_order":"280"},{"id":"27","name":"B\u1eafc T\u1eeb Li\u00eam","prefix":"Qu\u1eadn","province_id":"2","display_order":"290"},{"id":"42","name":"Nam T\u1eeb Li\u00eam","prefix":"Qu\u1eadn","province_id":"2","display_order":"300"}],
            errored: false,
        },
        search: '',
        selectedGroup: "apple",
        showModal: false,
    },
    mounted() {
        this.getNewProducts;
        this.getOldProducts;
    },
    computed: {
        getNewProducts: function() {
            if (this.newProducts.loading === true) {
                axios.get('./wp-json/api-tcdm/v1/newProducts')
                    .then(response => {
                        this.newProducts.lists = response.data.products;
                        this.newProducts.groups = response.data.groups;
                    })
                    .catch(error => {
                        console.log(error)
                        this.newProducts.errored = true
                    })
                    .finally(() => this.newProducts.loading = false)
            }
        },
        getOldProducts: function() {
            if (this.oldProducts.loading === true) {
                axios.get('./wp-json/api-tcdm/v1/oldProducts')
                    .then(response => {
                        this.oldProducts.lists = response.data.products;
                        this.oldProducts.groups = response.data.groups;
                    })
                    .catch(error => {
                        console.log(error)
                        this.oldProducts.errored = true
                    })
                    .finally(() => this.oldProducts.loading = false)
            }
        },
        filterListNewProducts: function() {
            var groupSelected = this.selectedGroup.toLowerCase();
            var _oldProduct = this.dataGuest.oldProductInfo;

            // Dùng để chặn mua CHÉO giữa các loại

            // if (_oldProduct != null) {
            //     return this.newProducts.lists.filter(
            //         function(product) {
            //             return (product.Group === _oldProduct[0].Group && product.ProductID > 0);
            //         }
            //     );
            // } else {
            //     return this.newProducts.lists.filter(
            //         function(product) {
            //             return (product.Group === groupSelected && product.ProductID > 0);
            //         }
            //     );
            // }

            return this.newProducts.lists.filter(
                function(product) {
                    return (product.Group === groupSelected && product.ProductID > 0);
                }
            );

        },
        filterListOldProducts: function() {
            var groupSelected = this.selectedGroup.toLowerCase();
            var _newProduct = this.dataGuest.newProductInfo;

            // Dùng để chặn mua CHÉO giữa các loại

            // if (_newProduct != null) {
            //     return this.oldProducts.lists.filter(
            //         function(product) {
            //             return (product.Group === _newProduct[0].Group && product.ProductID > 0);
            //         }
            //     );
            // } else {
            //     return this.oldProducts.lists.filter(
            //         function(product) {
            //             return (product.Group === groupSelected && product.ProductID > 0);
            //         }
            //     );
            // }

            return this.oldProducts.lists.filter(
                function(product) {
                    return (product.Group === groupSelected && product.ProductID > 0);
                }
            );
        },
        filterDistrict: function() {
            var localID = ~~this.dataGuest.defaultLocalID;
            var province_id = 1;

            if (~~localID == 1) {
                province_id = 2;
            }

            return listDistrict = this.dataStore.listDistrict.filter(
                function(district) {
                    return ~~district.province_id === province_id;
                }
            );
        },
        filterStore: function() {
            var localID = ~~this.dataGuest.defaultLocalID;
            var districtID = ~~this.dataGuest.districtID;

            if (districtID && districtID != '') {
                var listStore = this.dataStore.listStore.filter(
                    function(store) {
                        return ~~store.store_id === localID && ~~store.district_id === districtID;
                    }
                );
                return listStore;
            } else {
                return this.dataStore.listStore.filter(
                    function(store) {
                        return ~~store.store_id === localID;
                    }
                );
            }
        },
        searchListOldProducts: function() {
            if (this.search.length > 0) {
                var _newProduct = this.dataGuest.newProductInfo;

                // Dùng để chặn mua CHÉO giữa các loại

                // if (_newProduct != null) {
                //     return this.oldProducts.lists.filter(product => {
                //         return (product.ProductName.toLowerCase().includes(this.search.toLowerCase().trim()) && product.Group == _newProduct[0].Group && product.ProductID > 0)
                //     })
                // } else {
                //     return this.oldProducts.lists.filter(product => {
                //         return (product.ProductName.toLowerCase().includes(this.search.toLowerCase().trim()) && product.ProductID > 0)
                //     })
                // }

                return this.oldProducts.lists.filter(product => {
                    return (product.ProductName.toLowerCase().includes(this.search.toLowerCase().trim()) && product.ProductID > 0)
                })
            }
        },
        getOldPrice: function() {
            var statusOldProduct = this.dataGuest.statusOldProduct;
            if (statusOldProduct && statusOldProduct != '') {
                switch (statusOldProduct) {
                    case '1':
                        this.dataGuest.oldPrice = this.dataGuest.oldProductInfo[0].Thuloai_1;
                        break;
                    case '2':
                        this.dataGuest.oldPrice = this.dataGuest.oldProductInfo[0].Thuloai_2;
                        break;
                    case '3':
                        this.dataGuest.oldPrice = this.dataGuest.oldProductInfo[0].Thuloai_3;
                        break;
                    case '4':
                        this.dataGuest.oldPrice = this.dataGuest.oldProductInfo[0].Thuloai_4;
                        break;
                    case '5':
                        this.dataGuest.oldPrice = this.dataGuest.oldProductInfo[0].Thuloai_5;
                        break;
                }
            }
            return this.dataGuest.oldPrice;
        },
    },
    methods: {
        moreInfo: function(height) {
            var moreInfo = jQuery('#moreInfo');
            if (moreInfo.hasClass("clicked")) {
                jQuery('.the-le__content').css("height", height);
                moreInfo.removeClass("clicked");
                moreInfo.text("Xem thêm");
            } else {
                jQuery('.the-le__content').css("height", "auto");
                moreInfo.addClass("clicked");
                moreInfo.text("Thu gọn");
            }
        },
        formatPrice: function(price) {
            if (!price) {
                return;
            } else {
                return formatter.format(price);
            }
        },
        selectOldProduct: function(id) {
            var _info = this.oldProducts.lists.filter(
                function(product) {
                    return product.ProductID === id;
                }
            );

            this.dataGuest.oldProductID = id;
            this.dataGuest.oldProductInfo = _info;
            this.dataGuest.oldPrice = _info[0].Thuloai_1;

            console.log('Old: ' + id);
            // alert("Hệ thống đang nâng cấp và cập nhật bảng giá cho tháng 9. Quý khách vui lòng liên hệ HotLine: 1800.2097 ! \nCellphoneS xin trân thành cảm ơn !")
        },
        submitForm: function(e) {
            e.preventDefault();
            var errorMsg = '';
            var isOk = true;
            var shopID = ~~this.dataGuest.shopID;
            var phoneFormat = /((09|03|07|08|05)+([0-9]{8})\b)/g;
            var hotenFormat = /^[^!@#$%^&*()_+=\-\[\]\:\'\"\;\.\?\<\>\|\\0-9]+$/g;

            if (!this.dataGuest.fullName || hotenFormat.test(this.dataGuest.fullName) == false || this.dataGuest.fullName.length > 40) {
                errorMsg = errorMsg + '- Vui lòng điền họ tên\n';
                isOk = false;
            }
            if (!this.dataGuest.phoneNumber || phoneFormat.test(this.dataGuest.phoneNumber) == false || this.dataGuest.phoneNumber.length > 12) {
                errorMsg = errorMsg + '- Vui lòng điền số điện thoại\n';
                isOk = false;
            }
            if (shopID) {
                var _info = this.filterStore.filter(
                    function(store) {
                        return ~~store.external_id === shopID;
                    }
                );
                this.dataGuest.shopInfo = _info;
            } else {
                errorMsg = errorMsg + '- Vui lòng chọn cửa hàng muốn giao dịch\n';
                isOk = false;
            }

            if (isOk) {
                this.dataGuest.note = '[Thu cũ đổi mới] - Máy đang dùng: ' + this.dataGuest.oldProductInfo[0].ProductName + ' (Loại ' + this.dataGuest.statusOldProduct + ') - Máy lên đời: ' + this.dataGuest.newProductInfo[0].ProductName;
                var url = '/wp-json/wc/v3/orders';

                jQuery.ajax({
                    headers: {
                        "Authorization": "Basic Y2tfZjYzZTRmMTI1ZmFlYzkyNjQ0MzA0NjBmMDBkYzQxZDVmYjA2NmFjYjpjc19lMWI1M2YzMmQ0YzEyMTY3NDM0OTZiMTEyOTg1ZmIwYTAzZDlmNTQz"
                    },
                    url: url,
                    dataType: 'json',
                    data: {
                        payment_method : 'alg_custom_gateway_1',
                        payment_method_title : 'Giữ sản phẩm tại cửa hàng',
                        billing: {
                            last_name: this.dataGuest.fullName,
                            address_1: this.dataGuest.shopInfo[0].address,
                            email: this.dataGuest.mail,
                            phone: this.dataGuest.phoneNumber,
                            customer_note: this.dataGuest.note
                        },
                        line_items: [
                            {
                              product_id: this.dataGuest.newProductID,
                              quantity: 1
                            }
                        ]
                    },
                    type: 'post',
                    success: function(data) {
                        console.log(data);
                    },
                    error: function(jqXHR, exception) {
                        console.log(jqXHR);
                    },
                });
                setTimeout(function() {
                    jQuery('#frmThanhToan').submit();
                }, 2000);
            } else {
                alert(errorMsg);
            }
        },
    },
});