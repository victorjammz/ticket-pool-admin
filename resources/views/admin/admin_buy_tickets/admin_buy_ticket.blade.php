@extends('master')

@section('content')
    <section class="section">
        @include('admin.layout.breadcrumbs', [
            'title' => __('Buy Tickets'),
        ])


        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-4 mt-2">
                                <div class="col-lg-8">
                                    <h2 class="section-title mt-0"> {{ __('View Buy Tickets') }}</h2>
                                </div>
                                <div class="col-lg-4 text-right">
                                    @can('category_create')
                                        <button class="btn btn-primary add-button sendAmountBtn">
                                            <i class="fas fa-plus"></i> {{ __('Add New') }}
                                        </button>
                                    @endcan
                                </div>
                            </div>
                    <div class="table-responsive">
                        <table class="table" id="report_table">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Tickets Number</th>
                                    <th>Ticket Name</th>
                                    <th>Ticket Type</th>
                                    <th>Ticket Quantity</th>
                                    <th>Ticket Price</th>
{{--                                    <th>Customer Email</th>--}}
{{--                                    <th>Action</th>--}}
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($ordersByAdmin as $item)
                                <tr>
                                       <td></td>
                                       <td class="text-dark">{{ $item->ticket->ticket_number }}</td>
                                       <td>{{ $item->ticket->name }}</td>
                                       <td>{{ $item->ticket->type }}</td>
                                       <td>{{ $item->quantity }}</td>
                                       <td>{{ $item->ticket->price }}</td>
{{--                                       <td>{{ $item->customer->email }}</td>--}}
{{--                                       <td><a href="{{ url('organizerCheckout/' . $item->id) }}"><button class="btn btn-a btn-primary">{{ __('Book Now') }}</button></a></td>--}}
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            </div>
            </div>
                </div>
            </div>
        </div>
    </section>

    <div id="sendAmountModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Buy Ticket</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- Form to input send amount, quantity, send from, send to -->
                    <form id="sendAmountForm">
                        @csrf
{{--                        <div class="form-group">--}}
{{--                            <label>{{ __('Ticket') }}</label>--}}
{{--                            <select name="ticket" id="ticket"--}}
{{--                                    class="form-control w-100 select2">--}}
{{--                                @foreach($tickets as $ticket)--}}
{{--                                    <option value="{{$ticket->id}}">{{ __($ticket->ticket_number .' | '. $ticket->name) }}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}

                        <div class="form-group">
                            <label>{{ __('Order ID') }}</label>
                            <select name="order_id" id="order_id"
                                    class="form-control w-100 select2">
                                @foreach($orders as $order)
                                    <option value="{{$order->id}}">{{ __($order->order_id) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="quantity">{{ __('Quantity') }}</label>
                            <input type="text" class="form-control" id="quantity" name="quantity" placeholder="Ticket quantity">
                        </div>

{{--                        <div class="form-group">--}}
{{--                            <label for="email">{{ __('Customer Email') }}</label>--}}
{{--                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email">--}}
{{--                        </div>--}}
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            // Function to handle click event on 'Send Amount' button
            $('.sendAmountBtn').click(function () {
                $('#sendAmountModal').modal('show');
            });

            // Function to handle form submission
            $('#sendAmountForm').submit(function (e) {
                e.preventDefault(); // Prevent default form submission

                var ticket = $('#ticket').val();
                var order_id = $('#order_id').val();
                var quantity = $('#quantity').val();
                var email = $('#email').val();

                // Client-side validation
                if (ticket === '' || quantity === '' || email === '' || order_id === '') {
                    alert('Please fill in all fields.');
                    return;
                }

                // Disable all fields and buttons
                $('#sendAmountForm :input').prop('disabled', true);
                $('#saveButton').prop('disabled', true); // Assuming the save button has an id of "saveButton"

                // AJAX request setup with CSRF token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // AJAX request to send data to backend
                $.ajax({
                    type: 'POST',
                    url: '/buy-ticket-admin', // Replace with your backend endpoint
                    data: {
                        ticket: ticket,
                        order_id: order_id,
                        quantity: quantity,
                        email: email,
                    },
                    success: function (response) {
                        // Handle success response
                        console.log(response);
                        $('#sendAmountModal').modal('hide'); // Hide modal after successful submission
                        window.location.reload();
                    },
                    error: function (error) {
                        // Handle error response
                        console.error('Error:', error);
                        // Re-enable fields and buttons if there's an error
                        $('#sendAmountForm :input').prop('disabled', false);
                        $('#saveButton').prop('disabled', false); // Re-enable the save button
                    }
                });
            });
        });
    </script>
@endsection
