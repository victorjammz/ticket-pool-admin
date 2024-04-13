<div class="flex flex-row justify-between">
    <div class="flex flex-col" style="width:70%">
        <h4 class="relative font-bold"><span id="eventTitle"> {{ $data->event->name }}</span> &#x1F31E;</h4>
        <p id="order_date">{{$data->currentDateTime}}</p>
    </div>
    <div style="width:30%">
        <img id="thumbnailImage" src="{{ asset('images/upload/' . $data->event->image) }}" alt="avatar" style="width:100%"/>
    </div>
</div>
<div class="w-full h-0.5 bg-black mt-5"></div>
<div class="flex flex-col mt-5">
    <h2 class="font-bold text-2xl">Order Summary</h2>
    <div class="flex flex-row justify-between">
        <p class="rest-ticket" style="font-size: 16px; line-height: 2.5">LAST 25 TICKETS &#x1F31E; x <span id="totalNumber">{{$data->totalTickets}}</span></p>
        <p id="total_price" class="price" style="font-size: 16px; line-height: 2.5">{{ $data->currency }}{{$data->price_total}}</p>
    </div>
</div>
<p id="totalPriceDisplay" class="absolute b-0 r-0 font-bold" style="font-size: 18px; bottom: 10px">Total:&nbsp;{{ $data->currency }}{{$data->price_total}}p</p>
