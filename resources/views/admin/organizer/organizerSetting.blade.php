@extends('master')



@section('content')
    <section class="section">
        @include('admin.layout.breadcrumbs', [
            'title' => __('Setting'),
        ])
        <div class="section-body">
            <div class="row">
                <div class="col-lg-8">
                    <h2 class="section-title"> {{ __('Organizer Setting') }}</h2>
                </div>
                <div class="col-lg-4 text-right">
                </div>
            </div>

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
                    @if (session('Exception'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('Exception') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                </div>
                <div class="col-lg-6">
                    <div class="card card-large-icons">
                        <div class="card-icon bg-primary text-white">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div class="card-body">
                            <h4>{{ __('Bank Details') }}</h4>
                            <p>{{ __('Bank details, including the account holder\'s name, account number, bank name, and IBAN, are crucial for accurate fund transfers. Secure handling and adherence to data protection regulations are paramount to safeguard sensitive financial information.') }}
                            </p>
                            <a href="#payment-setting" aria-controls="payment-setting" role="button"
                                data-toggle="collapse" class="card-cta"
                                aria-expanded="false">{{ __('Change Setting') }}
                                <i class="fas fa-chevron-right"></i></a>
                            <div class="collapse mt-3" id="payment-setting">
                                <form method="post" action="{{ route('bank.details') }}">
                                    @csrf

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label col-12">{{ __('Account Holder\'s Name') }}</label>
                                        <div class="col-12">
                                            <input type="text" name="account_holder_name" placeholder="{{ __('Account Holder\'s Name') }}" value="{{ old('account_holder_name', $data->account_holder_name ?? '') }}"
                                                   class="form-control @error('account_holder_name') is-invalid @enderror">
                                            @error('account_holder_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label col-12">{{ __('Account Number') }}</label>
                                        <div class="col-12">
                                            <input type="text" name="account_number" placeholder="{{ __('Account Number') }}" value="{{ old('account_number', $data->account_number ?? '') }}"
                                                   class="form-control @error('account_number') is-invalid @enderror">
                                            @error('account_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label col-12">{{ __('Bank Name') }}</label>
                                        <div class="col-12">
                                            <input type="text" name="bank_name" placeholder="{{ __('Bank Name') }}" value="{{ old('bank_name', $data->bank_name ?? '') }}"
                                                   class="form-control @error('bank_name') is-invalid @enderror">
                                            @error('bank_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label col-12">{{ __('IBAN') }}</label>
                                        <div class="col-12">
                                            <input type="text" name="iban" placeholder="{{ __('IBAN') }}" value="{{ old('iban', $data->iban ?? '') }}"
                                                   class="form-control @error('iban') is-invalid @enderror">
                                            @error('iban')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                        <div class="col-12">
                                            <button type="submit"
                                                class="btn btn-primary demo-button">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    ` <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Test Mail') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="modal-body">
                    <label class="col-form-label">{{ __('Recipient Email for SMTP Testing') }}</label>
                    <input type="email" name="mail_to" id="mail_to" value="{{ auth()->user()->email }}" required
                        class="form-control @error('mail_to') is-invalid @enderror">
                    @error('mail_to')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary" id="TestMail">{{ __('Send') }}</button>
                </div>
                <div class="emailstatus text-right mr-3" id="emailstatus"></div>
                <div class="emailerror text-right mr-3 " id="emailerror"></div>
            </div>
        </div>
    </div>
@endsection
@php
    $gmapkey = App\Models\Setting::find(1)->map_key;
@endphp
@if ($gmapkey)
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ $gmapkey }}&libraries=places">
    </script>
@endif

<script>
    google.maps.event.addDomListener(window, 'load', initialize);

    function initialize() {
        var input = document.getElementById('address');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            $('#latitude').val(place.geometry['location'].lat());
            $('#longitude').val(place.geometry['location'].lng());
        });
    }
</script>
