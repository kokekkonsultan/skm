@extends('include_template/template_survey')

@php
$ci = get_instance();
@endphp

@section('style')

@endsection

@section('content')

<div class="container-fluid">
    <div id="form_container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div id="wizard_container">
                    <div id="top-wizard">
                        <div id="progressbar"></div>
                    </div>
                    <!-- /top-wizard -->
                    <form id="wrapped" method="post">
                        <input id="website" name="website" type="text" value="">
                        <!-- Leave for security protection, read docs for details -->
                        <div id="middle-wizard">

                            <div class="step">
                                <h3 class="main_question"><i class="arrow_right"></i>Have you traveled to any one of the
                                    destinations below in the last 21 days?</h3>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="container_check version_2">China
                                                <input type="checkbox" name="question_1[]" value="China"
                                                    class="required">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="container_check version_2">South Korea
                                                <input type="checkbox" name="question_1[]" value="South Korea"
                                                    class="required">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="container_check version_2">Iran
                                                <input type="checkbox" name="question_1[]" value="Iran"
                                                    class="required">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="container_check version_2">Europe
                                                <input type="checkbox" name="question_1[]" value="Europe"
                                                    class="required">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="container_check version_2">US States
                                                <input type="checkbox" name="question_1[]" value="US States"
                                                    class="required">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="container_check version_2">None of the above
                                                <input type="checkbox" name="question_1[]" value="Mobile Design"
                                                    class="required">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- /row -->
                            </div>
                            <!-- /step-->

                            <div class="step">
                                <h3 class="main_question"><i class="arrow_right"></i>Have you recently been in contact
                                    with a person with Coronavirus?</h3>
                                <div class="form-group">
                                    <label class="container_radio version_2">Yes
                                        <input type="radio" name="question_2" value="Yes" class="required">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="container_radio version_2">No
                                        <input type="radio" name="question_2" value="No" class="required">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <!-- /step-->

                            <div class="step">
                                <h3 class="main_question"><i class="arrow_right"></i>Do you have fever higher than
                                    100.3° F?</h3>
                                <div class="form-group">
                                    <label class="container_radio version_2">Yes
                                        <input type="radio" name="question_5" value="Yes" class="required">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="container_radio version_2">No
                                        <input type="radio" name="question_5" value="No" class="required">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <!-- /step-->

                            <div class="step">
                                <h3 class="main_question"><i class="arrow_right"></i>Do you have a runny nose?</h3>
                                <div class="form-group">
                                    <label class="container_radio version_2">Yes
                                        <input type="radio" name="question_6" value="Yes" class="required">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="container_radio version_2">No
                                        <input type="radio" name="question_6" value="No" class="required">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <!-- /step-->

                            <div class="step">
                                <h3 class="main_question"><i class="arrow_right"></i>Are you experiencing muscle aches,
                                    weakness, or lightheadedness?</h3>
                                <div class="form-group">
                                    <label class="container_radio version_2">Yes
                                        <input type="radio" name="question_7" value="Yes" class="required">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="container_radio version_2">No
                                        <input type="radio" name="question_7" value="No" class="required">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <!-- /step-->

                            <div class="step">
                                <h3 class="main_question"><i class="arrow_right"></i>Are you having diarrhea, stomach
                                    pain, vomiting?</h3>
                                <div class="form-group">
                                    <label class="container_radio version_2">Yes
                                        <input type="radio" name="question_8" value="Yes" class="required">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="container_radio version_2">No
                                        <input type="radio" name="question_8" value="No" class="required">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <!-- /step-->

                            <div class="step">
                                <h3 class="main_question"><i class="arrow_right"></i>Please fill with your personal data
                                </h3>
                                <div class="form-group add_top_30">
                                    <label for="name">First and Last Name</label>
                                    <input type="text" name="name" id="name" class="form-control required"
                                        onchange="getVals(this, 'name_field');">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control required"
                                        onchange="getVals(this, 'email_field');">
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" class="form-control required">
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-4">
                                        <label for="age">Age</label>
                                        <div class="form-group radio_input">
                                            <input type="text" name="age" id="age" class="form-control required">
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-8">
                                        <div class="form-group radio_input">
                                            <label class="container_radio mr-3">Male
                                                <input type="radio" name="gender" value="Male" class="required">
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="container_radio">Female
                                                <input type="radio" name="gender" value="Female" class="required">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- /row-->
                            </div>
                            <!-- /step-->

                            <div class="submit step" id="end">
                                <div class="summary text-center">
                                    <div class="wrapper">
                                        <h3>Thank your for your time<br><span id="name_field"></span>!</h3>
                                        <p>We will contat you shorly at the following email address <strong
                                                id="email_field"></strong> and if necessary take measures.</p>
                                    </div>
                                    <div class="text-center">
                                        <div class="form-group terms">
                                            <label class="container_check">Please accept our <a href="#"
                                                    data-toggle="modal" data-target="#terms-txt">Terms and
                                                    conditions</a> before Submit
                                                <input type="checkbox" name="terms" value="Yes" class="required">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /step last-->

                        </div>
                        <!-- /middle-wizard -->
                        <div id="bottom-wizard">
                            <button type="button" name="backward" class="backward">Prev</button>
                            <button type="button" name="forward" class="forward">Next</button>
                            <button type="submit" name="process" class="submit">Submit</button>
                        </div>
                        <!-- /bottom-wizard -->
                    </form>
                </div>
                <!-- /Wizard container -->
            </div>
        </div><!-- /Row -->
    </div><!-- /Form_container -->
</div>
<!-- /container -->

@endsection

@section('javascript')

@endsection