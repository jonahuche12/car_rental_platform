@extends('layouts.app')

@section('title', "Central School system - Create School")

@section('breadcrumb1')
    <a href="{{ route('home') }}">Home</a>
@endsection

@section('breadcrumb2', "Create School")

@section('style')
  <style>
    fieldset {
        border: 1px solid #ccc;
        padding: 0.2em 0.5em;
        margin-bottom: 10px;
        border-radius: 5px;
        font-weight: bold;
    }

    
  </style>
@endsection

@section('content')
    @include('sidebar')
    <h3 class="mt-4 badge @if($package->name == 'Basic Package') bg-info @elseif($package->name == 'Standard Package') bg-primary @elseif($package->name == 'Premium Package') bg-warning @endif mb-4">{{$package->name}}</h3>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <form id="createSchoolForm" action="{{ route('store-school') }}" method="post">
                    @csrf
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">General</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">

                          <div class="error-message" style="display:none"></div>
                          <div class="alert alert-success success-message" style="display:none"></div>
                            <!-- Fieldset 1: School Information -->
                            <fieldset id="school_information">
                            <legend>School Information</legend>
                                <div class="form-group">
                                    <label for="inputName">School Name</label>
                                    <input type="text" name="school_name" id="inputName" class="form-control" value="{{ old('school_name') }}">
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail">School Email</label>
                                    <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" placeholder="Enter School Email">
                                </div>
                                <!-- Add other fields as needed -->
                                <div class="form-group">
                                    <label for="inputDescription">School Description</label>
                                    <textarea id="inputDescription" name="school_description" class="form-control" rows="4">{{ old('school_description', optional($lastSchool)->description) }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="inputMission">School Mission</label>
                                    <textarea id="inputMission" name="school_mission" class="form-control" rows="4">{{ old('school_mission') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="inputVision">School Vision</label>
                                    <textarea id="inputVision" name="school_vision" class="form-control" rows="4">{{ old('school_vision') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="inputMotto">School Motto</label>
                                    <input type="text" name="school_motto" id="inputMotto" class="form-control" value="{{ old('school_motto') }}">
                                </div>
                                <div class="form-group">
                                <!-- <div class="form-group">
                                    <label for="inputLogo">School Logo</label>
                                    <input type="file" name="school_logo" id="inputLogo" class="form-control">
                                    @if(old('school_logo', optional($lastSchool)->logo))
                                        <img src="{{ asset('storage/' . old('school_logo', optional($lastSchool)->logo)) }}" alt="Logo Preview" style="max-width: 100px; max-height: 100px;">
                                    @endif
                                </div> -->


                                
                                <button type="button" class="next-btn btn btn-primary" data-next="location" >Next</button>
                            </fieldset>

                            <!-- Fieldset 2: Location -->
                            <fieldset id="location">
                                <legend>Location</legend>
                                <!-- Your form fields for location -->
                                <div class="form-group mb-3">
                                    <label for="country">Choose Country:</label>
                                    <select class="form-control" name="country" id="country_input" onchange="populateStates()">
                                        <option value="{{ old('country')}}"></option>
                                        <option value="Nigeria">Nigeria</option>
                                        <option value="Ghana">Ghana</option>
                                        <option value="South Africa">South Africa</option>
                                        <option value="Kenya">Kenya</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="state">Select State:</label>
                                    <select class="form-control" name="state" id="state_input" onchange="populateCities()">
                                        <option value="{{ old('state')}}"></option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="city">City:</label>
                                    <select class="form-control" name="city" id="city_input">
                                        <option value="{{ old('city') }}"></option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="city">Address:</label>
                                    <input type="text" class="form-control" name="address" id='addressInput' value="{{ old('address') }}">
                                   
                                </div>
                                                <!-- ... -->
                                <button type="button" class="prev-btn btn btn-info" data-prev="school_information">Previous</button>
                                <button type="button" class="next-btn btn btn-primary" data-next="contact_information" >Next</button>
                            </fieldset>
                            <!-- Fieldset 3: Contact Information -->
                            <fieldset id="contact_information">
                                <legend>Contact Information</legend>
                                <!-- Your form fields for contact information -->
                                <div class="form-group">
                                    <label for="inputPhone">Phone Number</label>
                                    <input type="number" class="form-control" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" placeholder="Enter Phone Number">
                                    
                                </div>
                                <button type="button" class="prev-btn btn btn-info" data-prev="location">Previous</button>
                                <button type="button" class="next-btn btn btn-primary" data-next="social_media" >Next</button>
                            </fieldset>

                            <!-- Fieldset 4: Social Media -->
                            <fieldset id="social_media">
                                <legend>Social Media</legend>
                                <!-- Your form fields for social media -->
                                <div class="form-group">
                                    <label for="facebook">Facebook link <span class="text-danger"><small>optional</small></span></label>
                                    <input type="text" id="facebook" name="facebook" value="{{old('facebook')}}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="facebook">Instagram <span class="text-danger"><small>optional</small></span></label>
                                    <input type="text" id="instagram" name="instagram" value="{{old('instagram')}}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="facebook">Twitter link <span class="text-danger"><small>optional</small></span></label>
                                    <input type="text" id="twitter" name="twitter" value="{{old('twitter')}}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="facebook">Linkedin link <span class="text-danger"><small>optional</small></span></label>
                                    <input type="text" id="linkedin" name="linkedin" value="{{old('linkedin')}}" class="form-control">
                                </div>
                                <button type="button" class="prev-btn btn btn-info" data-prev="contact_information">Previous</button>
                                <button type="button" class="next-btn btn btn-primary" data-next="" >Next</button>
                            </fieldset>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </form>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {

            var lastSchool = {!! json_encode($lastSchool) !!};
        console.log(lastSchool);
            
            var savedNextFieldset = '{{session()->get("nextFieldset")}}';
            // var savedCurrentFieldset = '{{session()->get("current_fieldset")}}';
            console.log(savedNextFieldset)
            // console.log(savedCurrentFieldset)
            if (savedNextFieldset) {
                $('#' + savedNextFieldset).show();
                $('fieldset').not('#' + savedNextFieldset).hide();
            } else {
                // If no saved session data, show the first empty fieldset
                var firstEmptyFieldset = $('fieldset:has(:input:empty):first');
                firstEmptyFieldset.show();
                $('fieldset').not(firstEmptyFieldset).hide();
            }
            // Handle next button click with AJAX
            $('.next-btn').on('click', function () {
                var currentFieldset = $(this).closest('fieldset');
                var nextFieldsetId = $(this).data('next');

                if (validateFields(currentFieldset)) {
                    var formData = new FormData(currentFieldset.closest('form')[0]);
                    formData.append('current_fieldset', currentFieldset.attr('id'));
                    formData.append('school_package_id', '{{ $package->id }}');
                    formData.append('nextFieldset', nextFieldsetId);

                    $.ajax({
                        url: currentFieldset.closest('form').attr('action'),
                        method: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        cache: false,

                        success: function (response) {
                            if (response.success) {
                                if (nextFieldsetId) {
                                    // Show the next fieldset and hide the current one
                                    $('#' + nextFieldsetId).show();
                                    currentFieldset.hide();
                                } else {
                                    // Redirect to the home page if nextFieldsetId is empty
                                    window.location.href = '{{ route("home") }}';
                                    return;
                                }

                                // Display success message from the server for 3 seconds
                                var successMessage = '<span>' + response.message + '</span>';
                                $('.success-message').html(successMessage).fadeIn();

                                setTimeout(function () {
                                    $('.success-message').fadeOut();
                                }, 3000);
                            } else if (response.error === 'Validation error') {
                                // Display validation errors in the frontend
                                var errorMessage = '';
                                $.each(response.errors, function (key, value) {
                                    errorMessage += '<div class="alert alert-danger">' + value.join('<br>') + '</div>';
                                });
                                $('.error-message').html(errorMessage).fadeIn();

                                setTimeout(function () {
                                    $('.error-message').fadeOut();
                                }, 6000);
                            } else {
                                // Handle other server errors
                                console.error('Server error:', response.error);
                                alert('Failed to submit the form. Please try again.');
                            }
                        },

                        error: function (xhr, status, error) {
                            // Handle AJAX error
                            console.error('AJAX error:', status, error);
                            alert('Failed to submit the form. Please try again.');
                        }
                    });
                }
            });



            // Handle previous button click
            $('.prev-btn').on('click', function () {
                var prevFieldsetId = $(this).data('prev');
                $('#' + prevFieldsetId).show();
                $(this).closest('fieldset').hide();
            });

            // Validate fields function
            function validateFields(fieldset) {
                var isValid = true;
                // Add your validation logic here for the fields in the current fieldset

                // Example validation for required fields
                fieldset.find('input, textarea, select').each(function () {
                    if ($(this).prop('required') && !$(this).val()) {
                        alert('Please fill in all required fields.');
                        isValid = false;
                        return false; // Stop the loop if any field is invalid
                    }
                });

                return isValid;
            }
        });
    </script>


               



@endsection