<div class="modal-body wrapper">
    <!-- row -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body h-100">
                    <div class="row row-sm ">
                        <div class=" col-xl-5 col-lg-12 col-md-12">
                            <div class="preview-pic tab-content">
                                @if($servicePart->image)
                                <div class="tab-pane active" id="pic-1"><img src="{{ asset($servicePart->image) }}"
                                        alt="image" /></div>

                                @else
                                <p>{{ __('No image available') }}</p>
                                @endif

                            </div>

                        </div>
                        <div class="details col-xl-7 col-lg-12 col-md-12 mt-4 mt-xl-0">
                            <h4 class="product-title mb-1">{{ strtoupper($servicePart->item_type) }}</h4>
                            <p class="text-muted tx-13 mb-1">{{ $servicePart->description }}</p>
                            <h6 class="price">Item group: <span class="h3 ml-2">{{ $servicePart->item_group }}</span>
                            </h6>
                            <h6 class="price">Category: <span class="h3 ml-2">{{ $servicePart->category }}</span></h6>

                            <h6 class="price">Type: <span class="h3 ml-2">{{ $servicePart->type }}</span></h6>
                            <h6 class="price">current price: <span class="h3 ml-2">{{ $servicePart->price }}</span></h6>
                            <h6 class="price">Qty on hand: <span class="h3 ml-2">{{ $servicePart->qty_on_hand }}</span>
                            </h6>

                            <h6 class="price">Unit of Measurement: <span class="h3 ml-2">{{ $servicePart->uom }}</span>
                            </h6>

                            <h6 class="price">Retail price: <span class="h3 ml-2">{{$servicePart->retail_price }}</span>
                            </h6>
                            <h6 class="price">Tax: <span class="h3 ml-2">{{ $servicePart->tax }}</span></h6>

                        </div>
                    </div>
                    <div class='row'>

                        @if($servicePart->item_type === 'standard')
                        <div class="col-md-5">
                            <div class="detail-group">
                                <h6>{{ __('Warranty') }}</h6>
                                <p class="mb-20">{{ $servicePart->warranty }}</p>
                            </div>
                        </div>

                        @endif
                        <!-- Bundled Items (Only for Bundles) -->
                        @if($servicePart->item_type === 'bundle')
                        <div class="col-md-5">
                            <h6>{{ __('Bundled Items') }}</h6>
                            <h4 class="product-title mb-1"> {{ $servicePart->product }}</h4>
                            <p class="text-muted tx-13 mb-1">{{ $servicePart->des }}</p>
                            <h6 class="price">Qty: <span class="h3 ml-2">{{ $servicePart->quantity }}</span></h6>
                            <h6 class="price">Price: <span class="h3 ml-2">{{ $servicePart->pri }}</span></h6>
                            <h6 class="price">Retail Price: <span class="h3 ml-2">{{ $servicePart->retailpri }}</span>
                            </h6>

                        </div>
                        @endif



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /row -->




</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->









</div>