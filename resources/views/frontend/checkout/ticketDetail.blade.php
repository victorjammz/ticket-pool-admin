<div class="flex flex-row justify-between">
    <div class="flex flex-col" style="width:70%">
        <h4 class="relative font-bold"><span id="eventTitle"> {{ $data->event->name }}</span> &#x1F31E;</h4>
        <input type="hidden" name="event_id" id="event_id" value="{{ $data->event->id }}">
        <p id="order_date">{{ date('D, jS M \a\t g:ia', strtotime($data->event->start_time)) }}</p>
        {{-- @if ($data->allday == 0)
            <div>
                <input type="date" name="ticket_date" id="onetime" data-date="{{ $data->event->end_time }}" placeholder="mm/dd/yy" class="mt-3 border p-2 border-gray-light">
                @if ($errors->has('ticket_date'))
                <div class="text-danger">{{ $errors->first('ticket_date') }}</div>
                @endif
                <div class="ticket_date text-danger"></div>
            </div>
        @endif --}}
    </div>
    <div style="width:30%">
        <img id="thumbnailImage" src="{{ asset('images/upload/' . $data->event->image) }}" alt="avatar" style="width:100%" />
    </div>
</div>
<div class="w-full h-0.5 bg-black mt-5"></div>
<div class="flex flex-col mt-5">
    <h2 class="font-bold text-2xl">Order Summary</h2>
{{--    <span>Needs to update Height if needeed</span>--}}
{{--    @dd($singleEvent)--}}
    @if(isset($singleEvent) && $singleEvent > 0)
        <div class="flex flex-row justify-between">
            <div class="ticket-name">
                <p class="rest-ticket" style="font-size: 16px; line-height: 2.5"> {{$data->event->name}} * <span id="quantityDisplay">1</span></p>
            </div>
            <p id="total_price" class="price" style="font-size: 16px; line-height: 2.5">{{ $data->currency }}<span id="totalAmountDisplay">{{$data->price_total}}</span></p>
        </div>

        <div class="flex flex-row justify-between">
            <p id="total_price" class="price" style="font-size: 16px; line-height: 2.5">{{__('Quantity')}}</p>
            <div class="ticket-name">
                <button type="button" class="pls minus border-l dec qtybtn border-t border-b border-primary bg-primary-light text-primary hover:text-black-700 h-8 w-9 cursor-pointer"> - </button>&nbsp;
                <input type="text" name="quantity" readonly value="1" class="left-mob" id="txtAcrescimo" style="width: 40px;height: 25px;padding-left: 10px;border:0px;">
                <button type="button" class="pls altera border-r inc qtybtn border-t border-b border-primary bg-primary-light text-primary hover:text-black-700 h-8 w-9 cursor-pointer"> + </button>
            </div>
        </div>
    @else
        <div class="flex flex-row justify-between">
            <div class="ticket-name">
                @foreach($data->ticket as $ticket)
                    <p class="rest-ticket" style="font-size: 16px; line-height: 2.5"> {{$ticket->name}} * {{$ticket->selectedseatsCount}}</p>
                @endforeach
            </div>
            <p id="total_price" class="price" style="font-size: 16px; line-height: 2.5">{{ $data->currency }}{{$data->price_total}}</p>
        </div>
    @endif
    @if(auth()->guard('appuser')->user())
    <div class="flex justify-between">
        <input type="text" name="coupon_code" id="coupon_id"
               class="block w-50 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
               placeholder="{{ __('Discount code') }}">
        <button type="submit"  id="applyCuppon" name="apply"
                class="flex w-25 justify-center rounded-md bg-black px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-black focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black">{{ __('Apply') }}</button>
    </div>
        <div class="couponerror text-danger ml-2"></div> <!-- Error message container -->
    @endif
</div>
{{-- @if (isset($data->type) && $data->type == 'paid') --}}

<div class="flex flex-col mt-5">
    <div class="space-y-5">
        <p class="font-semibold text-base leading-8 text-black ">
            {{ __('Taxes and Charges') }}</p>
        <div class="flex justify-between">
            <p class="font-normal text-lg leading-7 text-gray-200">
                {{ __('Total Tax amount') }}</p>
            <p class="font-medium text-lg leading-7 text-gray-300 totaltax">
                {{ $currency }}
                @php
                    if($data->price_total > 0){
                        echo $data->tax_total;
                    }
                    else{
                        $data->tax_total = 0;
                    }
                @endphp
            </p>
        </div>
        <div class="flex justify-between">
            <p class="font-normal text-lg leading-7 text-gray-200">
                {{ __('Tickets amount') }}</p>
            <p id="ticketAmount" class="font-medium text-lg leading-7 text-gray-300">
                {{ $currency }} <span id="ticketTotalAmount">{{ $data->price_total }}</span>
            </p>
        </div>

        <div class="flex justify-between">
            <p
                class="font-semibold text-xl leading-7 text-primary xlg:text-lg 1xl:text-xl">
                </p>
            <p
                class="font-semibold text-2xl leading-7 text-primary xlg:text-lg 1xl:text-2xl">
                {{ __('Total:') }}
                {{-- @if ($data->seatmap_id == null || $data->module->is_enable == 0) --}}
                @php
                   $totalAmount = $data->price_total + $data->tax_total;
                @endphp

                    {{ $currency }}<span id="totalWithTax">{{ $data->price_total + $data->tax_total }}</span>
                {{-- @endif --}}
                <input type="hidden" name="payment" value="{{$totalAmount}}" id="hiddenTotalAmount">
            </p>
        </div>


    </div>
</div>
{{-- @endif --}}
<script>
    $(document).ready(function() {

        var eventId = $("#event_id").val();

        var baseUrl = window.location.protocol + "//" + window.location.host;

        $("#applyCuppon").on("click", function() {
            // Clear any previous error messages
            $(".couponerror").html("");
            // Retrieve the coupon code from the input field
            var couponCode = $("input[name=coupon_code]").val();
            if (couponCode.trim() !== "") {
                var totalPayment = $("input[name=payment]").val();

                // Make an AJAX request to apply the coupon
                $.ajax({
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    url: baseUrl + "/applyCoupon",
                    data: {
                        coupon_code: couponCode,
                        total: totalPayment,
                        event_id: eventId
                    },
                    success: function(result) {
                        if (result.success) {
                            $("#stripe_payment").val((parseFloat(result.total_price) * 100).toFixed(2));
                            $("#payment").val(parseFloat(result.total_price).toFixed(2));
                            $(".subtotal").text(parseFloat(result.total_price).toFixed(2));
                            $("#subtotal").val(parseFloat(result.total_price).toFixed(2));
                            $(".discount").text(currency + parseFloat(result.payableamount).toFixed(2));
                            $("#coupon_discount").val(parseFloat(result.discount).toFixed(2));
                            $("#coupon_id").val(result.coupon_code); // Set value using .val()
                            $(".coupon_id").text(result.coupon_code); // Incorrect, should be removed
                            $("#coupon_id").prop("disabled", true);
                            $("#applyCuppon").prop("disabled", true);
                            $(".coupon_code").prop("readonly", true);

                            if (result.coupon_type === 0) {
                                $("#discount_type").text("%" + result.discount);
                            }
                        }
                        else {
                            // Display error message if coupon application fails
                            $(".couponerror").html('<div class="text-danger ml-2">' + result.message + "</div>");
                        }
                    },
                    error: function(xhr, status, error) {
                        // Display error message if there's an AJAX error
                        $(".couponerror").html('<div class="text-danger ml-2">' + result.message + "</div>");
                    }
                });
            } else {
                // Display error message if coupon code is empty
                $(".couponerror").html('<div class="text-danger ml-2">Please enter a coupon code.</div>');
            }
        });

        function updateQuantityAndTotal(quantity) {
            var unitPrice = parseFloat("{{ $data->price_total }}"); // Get the unit price
            var taxTotal = parseFloat("{{ $data->tax_total }}"); // Get the tax total
            var newTotalAmount = (unitPrice * quantity) + taxTotal; // Calculate total amount with tax

            // Update the quantity input field and total amount display
            $("#txtAcrescimo").val(quantity);
            $("#quantityDisplay").text(quantity);
            $("#totalAmountDisplay").text((unitPrice * quantity).toFixed(2));
            $("#ticketTotalAmount").text((unitPrice * quantity).toFixed(2));
            $("#totalWithTax").text(newTotalAmount.toFixed(2));

            // Update total amount in hidden input field
            $("input[name='payment']").val(newTotalAmount);
        }

        $('.pls.altera').click(function() {
            var curr_quantity = parseInt($("#txtAcrescimo").val());
            curr_quantity += 1;
            updateQuantityAndTotal(curr_quantity);
            // Store the updated quantity in local storage
            localStorage.setItem('quantity', curr_quantity);
            window.location.reload();
        });

        $('.pls.minus').click(function() {
            var curr_quantity = parseInt($("#txtAcrescimo").val());
            if (curr_quantity > 1) {
                curr_quantity -= 1;
                updateQuantityAndTotal(curr_quantity);
                // Store the updated quantity in local storage
                localStorage.setItem('quantity', curr_quantity);
            }
            window.location.reload();
        });
        var storedQuantity = localStorage.getItem('quantity');
        if (storedQuantity) {
            // Update quantity and total amount with stored value
            updateQuantityAndTotal(parseInt(storedQuantity));
        }
    });


</script>