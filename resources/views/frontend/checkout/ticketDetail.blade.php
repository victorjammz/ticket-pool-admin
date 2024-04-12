<div class="flex flex-row justify-between">
    <div class="flex flex-col" style="width:70%">
        <h4 class="relative font-bold">La Noche - Mature, Stylish, Sexy &#x1F31E;</h4>
        <p id="order_date">Sat, 6th Apr at 4:00pm</p>
    </div>
    <div style="width:30%">
        <img src="images/video_placeholder.png" alt="avatar" width="70px" />
    </div>
</div>
<div class="w-full h-0.5 bg-black mt-5"></div>
<div class="flex flex-col mt-5">
    <h2 class="font-bold text-2xl">Order Summary</h2>
    <div class="flex flex-row justify-between">
        <p class="rest-ticket" style="font-size: 16px; line-height: 2.5">LAST 25 TICKETS &#x1F31E; x <span id="totalNumber">1</span></p>
        <p id="total_price" class="price" style="font-size: 16px; line-height: 2.5">$11.3</p>
    </div>
</div>
<p id="totalPriceDisplay" class="absolute b-0 r-0 font-bold" style="font-size: 18px; bottom: 10px">Total: $11.30p</p>
<script>
// Retrieve data from localStorage
var jsonData = localStorage.getItem('seatsData');
var orderDate = localStorage.getItem('orderDate');

// Parse the JSON string back into an object
var data = JSON.parse(jsonData);

// Use data.totalPrice and data.totalNumber as needed
var totalPrice = data.totalPrice;
var totalSeats = data.totalSeats;

// Example usage in HTML
document.getElementById('totalPriceDisplay').textContent = 'Total: £' + totalPrice + 'p';
document.getElementById('total_price').textContent = '£' + totalPrice;
document.getElementById('totalNumber').textContent = totalSeats;
document.getElementById('order_date').textContent = orderDate;

</script>
