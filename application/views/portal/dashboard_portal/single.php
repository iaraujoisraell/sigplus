

<!DOCTYPE html>
<html lang="en" translate="no">
    <?php $this->load->view('portal/includes/head'); ?>
    <body class="g-sidenav-show  bg-gray-200">


        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

        <?php $this->load->view('portal/includes/menu'); ?>
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

            <?php $this->load->view('portal/includes/navbar'); ?>

            <div class="container-fluid py-2">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                
                                <div class="row ">
                                    <h5 class="mb-4  text-center"><?php echo $titulo; ?></h5>
                                    <div class="col-xl-12 col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-12 col-md-12  mx-auto">
                                                <img class="border-radius-lg shadow-lg" style="max-width: 100%;" src="<?php echo base_url(); ?>assets/portal/img/unimed/<?php echo $img; ?>" alt="chair">

                                            </div>  
                                            <div class="col-lg-12 col-sm-12 col-md-12">
                                                <!--<br>
                                                <h6 class="mb-0 mt-3">Links</h6>
                                                <a href="<?php echo base_url(); ?>portal/dashboard/index" data-bs-toggle="tooltip" data-bs-placement="top" title="Voltar"> <span class="badge badge-success">Voltar para a Home</span></a>
                                                <br>
                                                <label class="mt-4">Descrição</label>
                                                <ul>
                                                    <li><?php echo $desc; ?></li>

                                                </ul>-->
                                            </div>
                                        </div>



                                    </div>

                                </div>
                                <div class="row mt-5">
                                    <div class="col-12">
                                        <h5 class="ms-3">Outras Notícias</h5>
                                        <div class="my-gallery d-flex mt-4 pt-2 ms-3" itemscope itemtype="http://schema.org/ImageGallery">
                                            <?php
                                            foreach ($imgs as $imagem) {
                                                if ($imagem != $img) {
                                                    ?>
                                                    <figure class="ms-3" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                                                        <a href="<?php echo base_url(); ?>assets/portal/img/unimed/<?php echo $imagem; ?>" itemprop="contentUrl" data-size="500x600">
                                                            <img class="w-100 min-height-100 max-height-100 border-radius-lg shadow" src="<?php echo base_url(); ?>assets/portal/img/unimed/<?php echo $imagem; ?>" itemprop="thumbnail" alt="Image description" />
                                                        </a>
                                                    </figure>
                                                    <?php
                                                }
                                            }
                                            ?>


                                        </div>
                                        <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

                                            <div class="pswp__bg"></div>

                                            <div class="pswp__scroll-wrap">


                                                <div class="pswp__container">
                                                    <div class="pswp__item"></div>
                                                    <div class="pswp__item"></div>
                                                    <div class="pswp__item"></div>
                                                </div>

                                                <div class="pswp__ui pswp__ui--hidden">
                                                    <div class="pswp__top-bar">

                                                        <div class="pswp__counter"></div>
                                                        <button class="btn btn-white btn-sm pswp__button pswp__button--close">Fechar (Esc)</button>
                                                        <button class="btn btn-white btn-sm pswp__button pswp__button--fs">Tela Cheia</button>
                                                        <button class="btn btn-white btn-sm pswp__button pswp__button--arrow--left">Anterior
                                                        </button>
                                                        <button class="btn btn-white btn-sm pswp__button pswp__button--arrow--right">Próxima
                                                        </button>


                                                        <div class="pswp__preloader">
                                                            <div class="pswp__preloader__icn">
                                                                <div class="pswp__preloader__cut">
                                                                    <div class="pswp__preloader__donut"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                                                        <div class="pswp__share-tooltip"></div>
                                                    </div>
                                                    <div class="pswp__caption">
                                                        <div class="pswp__caption__center"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <footer class="footer py-4  ">
                    <div class="container-fluid">
                        <div class="row align-items-center justify-content-lg-between">
                            <div class="col-lg-6 mb-lg-0 mb-4">
                                <div class="copyright text-center text-sm text-muted text-lg-start">
                                    © <script>
                                        document.write(new Date().getFullYear())
                                    </script>,
                                    made by
                                    <a href="" class="font-weight-bold" target="_blank">Sigplus</a>/<a href="" class="font-weight-bold" target="_blank">Unimed Manaus</a>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                                    <li class="nav-item">
                                        <a href="" class="nav-link pe-0 text-muted" target="_blank">Dúvidas Frequentes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="" class="nav-link pe-0 text-muted" target="_blank">Sobre Nós</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </main>

        <script src="<?php echo base_url(); ?>assets/portal/js/core/popper.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/core/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/perfect-scrollbar.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/smooth-scrollbar.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/choices.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/photoswipe.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/photoswipe-ui-default.min.js"></script>
        <script>
                                        if (document.getElementById('choices-quantity')) {
                                            var element = document.getElementById('choices-quantity');
                                            const example = new Choices(element, {
                                                searchEnabled: false,
                                                itemSelectText: ''
                                            });
                                        }
                                        ;

                                        if (document.getElementById('choices-material')) {
                                            var element = document.getElementById('choices-material');
                                            const example = new Choices(element, {
                                                searchEnabled: false,
                                                itemSelectText: ''
                                            });
                                        }
                                        ;

                                        if (document.getElementById('choices-colors')) {
                                            var element = document.getElementById('choices-colors');
                                            const example = new Choices(element, {
                                                searchEnabled: false,
                                                itemSelectText: ''
                                            });
                                        }
                                        ;

                                        // Gallery Carousel
                                        if (document.getElementById('products-carousel')) {
                                            var myCarousel = document.querySelector('#products-carousel')
                                            var carousel = new bootstrap.Carousel(myCarousel)
                                        }


                                        // Products gallery

                                        var initPhotoSwipeFromDOM = function (gallerySelector) {

                                            // parse slide data (url, title, size ...) from DOM elements
                                            // (children of gallerySelector)
                                            var parseThumbnailElements = function (el) {
                                                var thumbElements = el.childNodes,
                                                        numNodes = thumbElements.length,
                                                        items = [],
                                                        figureEl,
                                                        linkEl,
                                                        size,
                                                        item;

                                                for (var i = 0; i < numNodes; i++) {

                                                    figureEl = thumbElements[i]; // <figure> element
                                                    // include only element nodes
                                                    if (figureEl.nodeType !== 1) {
                                                        continue;
                                                    }

                                                    linkEl = figureEl.children[0]; // <a> element

                                                    size = linkEl.getAttribute('data-size').split('x');

                                                    // create slide object
                                                    item = {
                                                        src: linkEl.getAttribute('href'),
                                                        w: parseInt(size[0], 10),
                                                        h: parseInt(size[1], 10)
                                                    };

                                                    if (figureEl.children.length > 1) {
                                                        // <figcaption> content
                                                        item.title = figureEl.children[1].innerHTML;
                                                    }

                                                    if (linkEl.children.length > 0) {
                                                        // <img> thumbnail element, retrieving thumbnail url
                                                        item.msrc = linkEl.children[0].getAttribute('src');
                                                    }

                                                    item.el = figureEl; // save link to element for getThumbBoundsFn
                                                    items.push(item);
                                                }

                                                return items;
                                            };

                                            // find nearest parent element
                                            var closest = function closest(el, fn) {
                                                return el && (fn(el) ? el : closest(el.parentNode, fn));
                                            };

                                            // triggers when user clicks on thumbnail
                                            var onThumbnailsClick = function (e) {
                                                e = e || window.event;
                                                e.preventDefault ? e.preventDefault() : e.returnValue = false;

                                                var eTarget = e.target || e.srcElement;

                                                // find root element of slide
                                                var clickedListItem = closest(eTarget, function (el) {
                                                    return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
                                                });

                                                if (!clickedListItem) {
                                                    return;
                                                }

                                                // find index of clicked item by looping through all child nodes
                                                // alternatively, you may define index via data- attribute
                                                var clickedGallery = clickedListItem.parentNode,
                                                        childNodes = clickedListItem.parentNode.childNodes,
                                                        numChildNodes = childNodes.length,
                                                        nodeIndex = 0,
                                                        index;

                                                for (var i = 0; i < numChildNodes; i++) {
                                                    if (childNodes[i].nodeType !== 1) {
                                                        continue;
                                                    }

                                                    if (childNodes[i] === clickedListItem) {
                                                        index = nodeIndex;
                                                        break;
                                                    }
                                                    nodeIndex++;
                                                }



                                                if (index >= 0) {
                                                    // open PhotoSwipe if valid index found
                                                    openPhotoSwipe(index, clickedGallery);
                                                }
                                                return false;
                                            };

                                            // parse picture index and gallery index from URL (#&pid=1&gid=2)
                                            var photoswipeParseHash = function () {
                                                var hash = window.location.hash.substring(1),
                                                        params = {};

                                                if (hash.length < 5) {
                                                    return params;
                                                }

                                                var vars = hash.split('&');
                                                for (var i = 0; i < vars.length; i++) {
                                                    if (!vars[i]) {
                                                        continue;
                                                    }
                                                    var pair = vars[i].split('=');
                                                    if (pair.length < 2) {
                                                        continue;
                                                    }
                                                    params[pair[0]] = pair[1];
                                                }

                                                if (params.gid) {
                                                    params.gid = parseInt(params.gid, 10);
                                                }

                                                return params;
                                            };

                                            var openPhotoSwipe = function (index, galleryElement, disableAnimation, fromURL) {
                                                var pswpElement = document.querySelectorAll('.pswp')[0],
                                                        gallery,
                                                        options,
                                                        items;

                                                items = parseThumbnailElements(galleryElement);

                                                // define options (if needed)
                                                options = {

                                                    // define gallery index (for URL)
                                                    galleryUID: galleryElement.getAttribute('data-pswp-uid'),

                                                    getThumbBoundsFn: function (index) {
                                                        // See Options -> getThumbBoundsFn section of documentation for more info
                                                        var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
                                                                pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                                                                rect = thumbnail.getBoundingClientRect();

                                                        return {
                                                            x: rect.left,
                                                            y: rect.top + pageYScroll,
                                                            w: rect.width
                                                        };
                                                    }

                                                };

                                                // PhotoSwipe opened from URL
                                                if (fromURL) {
                                                    if (options.galleryPIDs) {
                                                        // parse real index when custom PIDs are used
                                                        // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
                                                        for (var j = 0; j < items.length; j++) {
                                                            if (items[j].pid == index) {
                                                                options.index = j;
                                                                break;
                                                            }
                                                        }
                                                    } else {
                                                        // in URL indexes start from 1
                                                        options.index = parseInt(index, 10) - 1;
                                                    }
                                                } else {
                                                    options.index = parseInt(index, 10);
                                                }

                                                // exit if index not found
                                                if (isNaN(options.index)) {
                                                    return;
                                                }

                                                if (disableAnimation) {
                                                    options.showAnimationDuration = 0;
                                                }

                                                // Pass data to PhotoSwipe and initialize it
                                                gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
                                                gallery.init();
                                            };

                                            // loop through all gallery elements and bind events
                                            var galleryElements = document.querySelectorAll(gallerySelector);

                                            for (var i = 0, l = galleryElements.length; i < l; i++) {
                                                galleryElements[i].setAttribute('data-pswp-uid', i + 1);
                                                galleryElements[i].onclick = onThumbnailsClick;
                                            }

                                            // Parse URL and open gallery if it contains #&pid=3&gid=1
                                            var hashData = photoswipeParseHash();
                                            if (hashData.pid && hashData.gid) {
                                                openPhotoSwipe(hashData.pid, galleryElements[hashData.gid - 1], true, true);
                                            }
                                        };

                                        // execute above function
                                        initPhotoSwipeFromDOM('.my-gallery');
        </script>

        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/dragula/dragula.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/jkanban/jkanban.js"></script>
        <script>
                                        var win = navigator.platform.indexOf('Win') > -1;
                                        if (win && document.querySelector('#sidenav-scrollbar')) {
                                            var options = {
                                                damping: '0.5'
                                            }
                                            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
                                        }
        </script>

        <script async defer src="https://buttons.github.io/buttons.js"></script>

        <script src="<?php echo base_url(); ?>assets/portal/js/material-dashboard.min.js?v=3.0.6"></script>
        <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vb26e4fa9e5134444860be286fd8771851679335129114" integrity="sha512-M3hN/6cva/SjwrOtyXeUa5IuCT0sedyfT+jK/OV+s+D0RnzrTfwjwJHhd+wYfMm9HJSrZ1IKksOdddLuN6KOzw==" data-cf-beacon='{"rayId":"7b292d5c3d42258c","version":"2023.3.0","r":1,"token":"1b7cbb72744b40c580f8633c6b62637e","si":100}' crossorigin="anonymous"></script>
    </body>
</html>