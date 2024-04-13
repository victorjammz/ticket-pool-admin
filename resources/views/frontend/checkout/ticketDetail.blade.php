<script>
    var thumbnail = localStorage.getItem('thumbnail');
    var imageUrl = '{{ url("images/upload/") }}/' + thumbnail;
</script>
<div class="flex flex-row justify-between">
    <div class="flex flex-col" style="width:70%">
        <h4 class="relative font-bold"><span id="eventTitle"></span> &#x1F31E;</h4>
        <p id="order_date"></p>
    </div>
    <div style="width:30%">
        <img id="thumbnailImage" alt="avatar" style="width:100%"/>
    </div>
</div>
<div class="w-full h-0.5 bg-black mt-5"></div>
<div class="flex flex-col mt-5">
    <h2 class="font-bold text-2xl">Order Summary</h2>
    <div class="flex flex-row justify-between">
        <p class="rest-ticket" style="font-size: 16px; line-height: 2.5">LAST 25 TICKETS &#x1F31E; x <span id="totalNumber">1</span></p>
        <p id="total_price" class="price" style="font-size: 16px; line-height: 2.5"></p>
    </div>
</div>
<p id="totalPriceDisplay" class="absolute b-0 r-0 font-bold" style="font-size: 18px; bottom: 10px"></p>
<script>
// Retrieve data from localStorage
var jsonData = localStorage.getItem('seatsData');
var orderDate = localStorage.getItem('orderDate');
var event_title = localStorage.getItem('event_title');

var seatsIoIds = localStorage.getItem('seatsIoIds');
var selectedSeatsInput = localStorage.getItem('selectedSeatsInput');
var seatsio_eventId = localStorage.getItem('seatsio_eventId');

var thumbnail = localStorage.getItem('thumbnail');
var imageUrl = '{{ url("images/upload/") }}/' + thumbnail;
// Parse the JSON string back into an object
var data = JSON.parse(jsonData);

// Use data.totalPrice and data.totalNumber as needed
var totalPrice = data.totalPrice;
var totalSeats = data.totalSeats;

// Example usage in HTML
document.getElementById('totalPriceDisplay').textContent = 'Total: £' + totalPrice + 'p';
document.getElementById('total_price').textContent = '£' + totalPrice;
document.getElementById('button_total_price').textContent = '£' + totalPrice;
document.getElementById('totalNumber').textContent = totalSeats;
document.getElementById('order_date').textContent = orderDate;
document.getElementById('eventTitle').textContent = event_title;

document.getElementById('_seatsIoIds').value = seatsIoIds;
document.getElementById('_selectedSeatsInput').value = selectedSeatsInput;
document.getElementById('_seatsio_eventId').value = seatsio_eventId;
document.getElementById('_selectedSeatsTotalPrice').value = totalPrice;
document.getElementById('_selectedSeatsTotalNumber').value = totalSeats;

document.getElementById('thumbnailImage').src = imageUrl;

</script>
