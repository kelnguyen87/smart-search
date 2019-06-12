@if (sizeof($listVariant)>0)
    <div class="widget">
        <div class="infor">
            <h2 class="h1--underline">{{$title}}</h2>
            <span>{{$description}}</span>
        </div>
        <div class="widget-inner clearfix">
            <div class="swiper-containerx" id="upsell-carousel">
                <div class="swiper-wrapper">
                    @foreach($data as $value)
                        @if(sizeof($value->variants)>1)
                            @foreach($value->variants as $item)
                                @if(in_array($item->id,$listVariant))
                                    <div class="swiper-slide">
                                        <div class="product-item">
                                            <div class="product-item__content">
                                                <figure class="product-item__figure">
                                                    <a href="{{'/products/'.$value->handle}}" title="">
                                                        <div class="product-item__image-wrapper">
                                                            <img class="product-item__image"
                                                                 src="@if (!empty($value->image)){{$value->image->src}}@else {{asset('images/150.png')}} @endif" alt="{{$value->title}}">
                                                        </div>
                                                    </a>
                                                </figure>
                                                <div class="product-item__details">
                                                    <div class="info">
                                                        <a class="product-item__title"
                                                           href="{{'/products/'.$value->handle}}"
                                                           title="">{{$value->title}} {{$item->title}}</a>
                                                        <span class="product-item__price"
                                                              data-money-convertible="">{{$item->price}}</span>
                                                    </div>
                                                    <button type="button" class="add-to-cart" offer-type="{{$type}}"
                                                            variant-id="{{$item->id}}"
                                                            trigger-id="{{$shopify_id}}"
                                                            data-action="add-to-cart">{{$buttonTitle}}</button>
                                                    <div class="product__form-status" style="display: none">
                                                        <p class="product__form-message">{{$addedAlert}}</p>
                                                        <a href="#" data-action="close-form-status"
                                                           class="product__form-continue button button--full button--black">{{$continueText}}</a>
                                                        <br>
                                                        <a href="/cart"
                                                           class="button button--full button--black">{{$goCartText}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="swiper-slide">
                                <div class="product-item">
                                    <div class="product-item__content">
                                        <figure class="product-item__figure">
                                            <a href="{{'/products/'.$value->handle}}" title="">
                                                <div class="product-item__image-wrapper">
                                                    <img class="product-item__image" src="{{$value->image->src}}"
                                                         alt="{{$value->title}}">
                                                </div>
                                            </a>
                                        </figure>
                                        <div class="product-item__details">
                                            <div class="info">
                                                <a class="product-item__title" href="{{'/products/'.$value->handle}}"
                                                   title="">{{$value->title}}</a>
                                                <span class="product-item__price"
                                                      data-money-convertible="">{{$value->variants[0]->price}}</span>
                                            </div>
                                            <button type="button" class="add-to-cart" offer-type="{{$type}}"
                                                    variant-id="{{$value->variants[0]->id}}"
                                                    trigger-id="{{$shopify_id}}"
                                                    data-action="add-to-cart">{{$buttonTitle}}</button>
                                            <div class="product__form-status" style="display: none">
                                                <p class="product__form-message">{{$addedAlert}}</p>
                                                <a href="#" data-action="close-form-status"
                                                   class="product__form-continue button button--full button--black">{{$continueText}}</a>
                                                <br>
                                                <a href="/cart"
                                                   class="button button--full button--black">{{$goCartText}}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="swiper-button-next slick-arrow-next swiper-button-black">
                    <svg class="icon icon-arrow-right">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-arrow-right"></use>
                    </svg>
                </div>
                <div class="swiper-button-prev slick-arrow-prev swiper-button-black">
                    <svg class="icon icon-arrow-left">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-arrow-left"></use>
                    </svg>
                </div>
            </div>
        </div>
    </div>
@endif
<script>
    $(window).load(function () {
        var smartswiper = new Swiper('#upsell-carousel', {
            slidesPerView: 4,
            spaceBetween: 20,
            slidesPerGroup: 1,
            // disableOnInteraction: false,
            loop: false,
            autoplay: true,
            navigation: {
                nextEl: '.slick-arrow-next',
                prevEl: '.slick-arrow-prev',
            },

            breakpoints: {

                // when window width is <= 480px
                480: {
                    slidesPerView: 2,
                    spaceBetween: 10
                },
                // when window width is <= 640px
                800: {
                    slidesPerView: 2,
                    spaceBetween: 10
                }
            }
        });
    });

</script>

