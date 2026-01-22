@extends('layouts.frontend')
@section('title', 'Checkout')
@section('css')
    <link href="{{ asset('backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            line-height: 36px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .form-control {
            height: 38px;
            line-height: 36px;
        }
    </style>
@endsection
@section('content')
    <div class="container my-5">
        <div class="card">
            <div class="card-body">
                <div class="pb-5 text-center">
                    <h2>Checkout form</h2>
                </div>

                @include('backend.includes.message')
                <div class="row">
                    <div class="col-md-4 order-md-2 mb-4">
                        <h4 class="d-flex justify-content-between align-items-center mb-3">
                            <span class="">Course Information</span>
                        </h4>
                        <ul class="list-group list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <img class="image-preview img-fluid" src="{{ getImageUrl($course->banner) }}"
                                    alt="{{ $course->name }}" style="height: 40px; width: 40px">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">{{ $course->name }}</div>
                                    @if ($course->is_pricing == 1)
                                        {{ $course->detail?->duration }} {{ $course->detail?->type }}
                                    @endif
                                </div>
                                @if ($course->is_pricing == 1)
                                    <span>
                                        <del class="{{ $course->detail?->sell_price <= 0 ? 'd-none' : '' }}">৳
                                            {{ $course->detail?->price }}</del>
                                        <strong>৳
                                            {{ $course->detail?->sell_price > 0 ? $course->detail?->sell_price : $course->detail?->price }}</strong>
                                    </span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">

                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Total</div>
                                </div>
                                <span class="">
                                    <strong>৳
                                        {{ $isTrial == 'free-trial' ? 0 : ($course->detail?->sell_price > 0 ? $course->detail?->sell_price : $course->detail?->price) }}</strong>
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-8 order-md-1">
                        <h4 class="mb-3">Billing address</h4>
                        <form class="needs-validation" novalidate
                            action="{{ route('courses.checkout.store', $course->slug) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="isTrial" value="{{$isTrial}}">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName">First name</label>
                                    <input type="text" class="form-control" name="firstName" id="firstName"
                                        placeholder="" value="{{ old('firstname', Auth::user()->name) }}" required>
                                    <div class="invalid-feedback">
                                        Valid first name is required.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName">Last name ( Optional )</label>
                                    <input type="text" class="form-control" name="lastName" id="lastName"
                                        placeholder="Last name" value="{{ old('lastName') }}" required>
                                    <div class="invalid-feedback">
                                        Valid last name is required.
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', Auth::user()->email) }}">
                                    <div class="invalid-feedback">
                                        Please enter a valid email address for shipping updates.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone">phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        value="{{ old('phone') }}" placeholder="+351 21 343 2148">
                                    <div class="invalid-feedback">
                                        Please enter phone number for shipping updates.
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="1234 Main St" required value="{{ old('address') }}">
                                <div class="invalid-feedback">
                                    Please enter your shipping address.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" name="address2" id="address2"
                                    placeholder="Apartment or suite" value="{{ old('address2') }}">
                            </div>

                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <label for="country_id">Country</label>
                                    <select class="form-control select2" id="country_id" name="country_id" required>
                                        <option value="">Choose One</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}"
                                                {{ old('country_id', 13) == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid country.
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="state_id">City</label>
                                    <select class="form-control select2" name="state_id" id="state_id"
                                        data-old-value="{{ old('state_id') }}" required>
                                        <option value="">Choose One</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please provide a valid state.
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="zip">Zip</label>
                                    <input type="text" class="form-control" id="zip" name="zip"
                                        value="{{ old('zip') }}" placeholder="1897" required>
                                    <div class="invalid-feedback">
                                        Zip code required.
                                    </div>
                                </div>
                            </div>

                            @if ($isTrial == null)
                                <hr class="mb-4">
                                <h4 class="mb-3">Payment</h4>
                                <div class="d-block my-3">
                                    <div class="custom-control custom-radio">
                                        <input id="bkash" name="paymentMethod" type="radio"
                                            class="custom-control-input" {{ old('paymentMethod') == 1 ? 'checked' : '' }}
                                            required value="1">
                                        <label class="custom-control-label" for="bkash">Bkash</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input id="nagod" name="paymentMethod" type="radio"
                                            class="custom-control-input" {{ old('paymentMethod') == 2 ? 'checked' : '' }}
                                            required value="2">
                                        <label class="custom-control-label" for="nagod">Nagod</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="p-phone">Phone Number</label>
                                        <input type="text" class="form-control" name="p-phone" id="p-phone"
                                            value="{{ old('p-phone') }}" placeholder="01700000000" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="p-t_id">Transection ID</label>
                                        <input type="text" class="form-control" name="p-t_id" id="p-t_id"
                                            value="{{ old('p-t_id') }}" placeholder="D35V67K" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="p-amount">Amount</label>
                                        <input type="text" class="form-control" name="p-amount" id="p-amount"
                                            value="{{ old('p-amount') }}"
                                            placeholder="৳
                                        {{ $course->sell_price > 0 ? $course->sell_price : $course->price }}"
                                            required>
                                    </div>
                                </div>
                            @endif
                            <button class="btn btn-primary btn-lg btn-block btn-sm" type="submit">Continue to checkout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('backend/vendors/select2/dist/js/select2.full.min.js') }}"></script>

        <script>
            $(document).ready(function() {
                $('.select2').select2();
                $('#country_id').change(function() {
                    var countryId = $(this).val();
                    $.ajax({
                        url: '/states/' + countryId,
                        type: 'GET',
                        success: function(data) {
                            var oldValue = $('#state_id').data('old-value');
                            $('#state_id').empty();
                            $.each(data, function(key, value) {
                                var option = $('<option>', {
                                    value: value.id,
                                    text: value.name
                                });

                                if (value.id == oldValue) {
                                    option.attr('selected', 'selected');
                                }

                                $('#state_id').append(option);
                            });
                        }
                    });
                });
                $('#country_id').trigger('change');
            });

            function previewImage(event, imagePreviewId) {
                let reader = new FileReader();
                reader.onload = function() {
                    let output = document.getElementById(imagePreviewId);
                    output.src = reader.result;
                    output.style.display = 'block';
                }
                reader.readAsDataURL(event.target.files[0]);
            }
        </script>
    @endpush
@endsection
