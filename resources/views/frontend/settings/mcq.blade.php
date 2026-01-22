@extends('layouts.frontend')
@section('title', $page_title)
@section('content')
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/notes.css') }}">
    <style>
        @media (max-width: 767px) {

            .question {
                margin-left: 40px;
            }
        }

        @media (max-width: 991px) {

            .choose {
                margin-left: 40px;
            }
        }

        .option_sub {
            margin: 28px 22px;
        }

        .correct-option {
            background-color: #b0e57c;
        }

        .incorrect-option {
            background-color: #ff9999;
        }
    </style>
@endsection
@include('frontend.includes.bradcaump')
    <section class="htc__profile__area bg__white ptb--80">
        <div class="container">
            <div class="row d-flex align-items-stretch">
                <div class="col-sm-12 col-md-4 col-lg-4">
                    <div class='dashboard'>
                        <div class="dashboard-nav">
                            <nav class="dashboard-nav-list">
                                @foreach ($chapters as $chapter)
                                    @if (count($chapter->lessons) > 0)
                                        <div class='dashboard-nav-dropdown'><a href="#" class="dashboard-nav-item dashboard-nav-dropdown-toggle"> {{ $chapter->name }} </a>
                                            <div class='dashboard-nav-dropdown-menu'>
                                                @foreach ($chapter->lessons as $lesson)
                                                    <a type="button" class="dashboard-nav-item open-modal"
                                                        data-chapter="{{ $chapter->id }}" data-lesson="{{$lesson->id}}"
                                                        data-toggle="modal" data-target="#mcqModal">
                                                        {{ $lesson->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <a type="button" class="dashboard-nav-item open-modal"
                                            data-chapter="{{ $chapter->id }}" data-lesson=""
                                            data-toggle="modal" data-target="#mcqModal">
                                            {{ $chapter->name }}
                                        </a>
                                    @endif
                                @endforeach
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-8 col-lg-8 xs-mt-40">
                    <div class="htc__profile__right">
                        <div class="card py-5">
                            <div class="card-body text-center my-5 py-5">
                                <h2 class="card-title">About MCQ</h2>
                                <h6 class="card-text">This is the content of the card. It can include text, images, or other elements.</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

       <!-- Modal -->
       <div class="modal fade" id="mcqModal" data-bs-backdrop="static" data-bs-keyboard="false"  tabindex="-1" aria-labelledby="mcqModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="reloadPage()"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-12">
                                <form id="regForm">
                                    <div class="all-steps" id="all-steps">
                                    </div>
                                    <div class="all-tabs" id="all-tabs"> </div>

                                    <div style="overflow:auto;" id="nextprevious">
                                        <div style="float:right;">
                                            <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                                            <button type="button" id="nextBtn" disabled onclick="nextPrev(1)">Next</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const mobileScreen = window.matchMedia("(max-width: 990px )");
        $(document).ready(function() {
            $(".dashboard-nav-dropdown-toggle").click(function() {
                $(this).closest(".dashboard-nav-dropdown")
                    .toggleClass("show")
                    .find(".dashboard-nav-dropdown")
                    .removeClass("show");
                $(this).parent()
                    .siblings()
                    .removeClass("show");
            });
            $(".menu-toggle").click(function() {
                if (mobileScreen.matches) {
                    $(".dashboard-nav").toggleClass("mobile-show");
                } else {
                    $(".dashboard").toggleClass("dashboard-compact");
                }
            });
        });


        // Get Mcq
        let universal_i = 0;
        let res = [];
        $(document).on('click', '.open-modal', function() {

            $('#all-steps').empty();

            let chapterId = $(this).data('chapter');
            let lessonId = $(this).data('lesson');

            $.ajax({
                url: '/mcq/questions',
                method: 'GET',
                data: {
                    chapter_id: chapterId,
                    lesson_id: lessonId
                },
                success: function(response) {
                    for (let i = 0; i < response.length; i++) {
                        res = response[i];

                        const question = response[i].question;
                        const options = [
                            response[i].option1,
                            response[i].option2,
                            response[i].option3,
                            response[i].option4,
                            response[i].option5
                        ];
                        const explanation = response[i].explain;

                        const optionsHTML = options.map((optionText, optionIndex) => {
                            return `
                                <div class="form-check">
                                    <h5 class="my-3 row">
                                        <span class="col-sm-12 col-md-4 col-lg-2 text-primary">Option ${optionIndex + 1}: </span>
                                        <span class="col-sm-12 col-md-8 col-lg-10">
                                            <div class="row question">
                                                <label class="col-sm-12 col-md-12 col-lg-6"> ${optionText} </label>
                                                <label class="col-sm-12 col-md-6 col-lg-3 form-check-label choose selectedOption${optionIndex + 1}">
                                                    <input class="form-check-input" type="radio" name="selectedOption${optionIndex + 1}" value="1"> True
                                                </label>
                                                <label class="col-sm-12 col-md-6 col-lg-3 form-check-label choose selectedOption${optionIndex + 1}">
                                                    <input class="form-check-input" type="radio" name="selectedOption${optionIndex + 1}" value="0"> False
                                                </label>
                                            </div>
                                        </span>
                                    </h5>
                                </div>`;
                        }).join('');

                        $('#all-steps').append('<span class="step"></span>');
                        $('#all-tabs').append(`
                            <div class="tab">
                                <h2 class="my-3">Q. ${question}</h2>
                                ${optionsHTML}
                                <button type="button" class="option_sub" disabled>Submit</button>
                                <div class="my-2" style="display: none" id="explanation_${i}">${explanation}</div>
                            </div>`);
                    }

                    $('#all-tabs').append('<div class="tab text-center" id="finishTab">' +
                                            '<h2 class="my-3">Congratulations! You have completed the MCQ.</h2>' +
                                            '<button type="button" class=" my-5 submitBtn" onclick="closeModal()">Finish</button>' +
                                        '</div>');
                    currentTab = 0;
                    showTab(currentTab);
                },
                error: function(xhr, status, error) {
                }
            });

            $('#mcqModal').modal('show');
        });

        $(document).on('change', '.form-check-input', function() {
            const questionIndex = $(this).closest('.tab').index();
            const numOptions = 5;

            const selectedOptions = $(`.tab:eq(${questionIndex}) .form-check-input:checked`).length;
            const submitButton = $(`.tab:eq(${questionIndex}) .option_sub`);

            if (selectedOptions === numOptions) {
                submitButton.prop('disabled', false);

            } else {
                submitButton.prop('disabled', true);
            }
        });


        $(document).on('click', '.option_sub', function() {
            const questionIndex = $(this).closest('.tab').index();
            const selectedOptions = [];
            const correctAnswers = [res.answer1, res.answer2, res.answer3, res.answer4, res.answer5];

            $(`.tab:eq(${questionIndex}) .form-check-input:checked`).each(function() {
                selectedOptions.push($(this).val());
            });
            let selected = `[${selectedOptions.join(', ')}]`;

            selectedOptions.forEach(function(selectedOption, optionIndex) {
                const isCorrect = correctAnswers[optionIndex] === selectedOption;

                const optionElement = $(`.tab:eq(${questionIndex}) .selectedOption${optionIndex + 1}`).find('input:checked').closest('.form-check-label');

                if (isCorrect) {
                    optionElement.addClass('correct-option');
                } else {
                    optionElement.addClass('incorrect-option');
                }
            });

            $(`#explanation_${questionIndex}`).show();
            const submitButton = $(`.tab:eq(${questionIndex}) .option_sub`);
            submitButton.prop('disabled', true);
            $('#nextBtn').prop('disabled', false);
            ++universal_i;
        });

        function closeModal() {
            $('#mcqModal').modal('hide');
            reloadPage();
        }

        function reloadPage() {
            location.reload();
        }

        function showTab(n) {
            var x = document.getElementsByClassName("tab");
            if (x.length === 0 || x[n] === undefined) {
                return;
            }

            x[n].style.display = "block";

            if (n === 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }

            if (n === (x.length - 1)) {
                // document.getElementById("nextBtn").innerHTML = "Submit";
            } else {
                document.getElementById("nextBtn").innerHTML = "Next";
            }
        }

        function nextPrev(n) {
            var x = document.getElementsByClassName("tab");
            if (n === 1 && !validateForm()) return false;
            x[currentTab].style.display = "none";
            currentTab = currentTab + n;

            if (currentTab >= x.length) {
                showFinishTab();
                return;
            }

            showTab(currentTab);

            var prevBtn = document.getElementById("prevBtn");
            var nextBtn = document.getElementById("nextBtn");

            if (currentTab === 0) {
                prevBtn.style.display = "none";
            } else {
                prevBtn.style.display = "inline";
            }

            // For last tab next previous btn hide
            if (currentTab === x.length - 1) {
                nextBtn.style.display = "none";
                prevBtn.style.display = "none";
            } else {
                nextBtn.innerHTML = "Next";
            }

            if(universal_i === currentTab)
            {
                nextBtn.disabled = true;
            } else if(universal_i > currentTab) {
                nextBtn.disabled = false;
            } else {
                nextBtn.disabled = true;
            }
        }

        function validateForm() {
            var x, y, i, valid = true;
            x = document.getElementsByClassName("tab");
            y = x[currentTab].getElementsByTagName("input");
            for (i = 0; i < y.length; i++) {
                if (y[i].value == "") {
                    y[i].className += " invalid";
                    valid = false;
                }
            }
            if (valid) {
                document.getElementsByClassName("step")[currentTab].className += " finish";
            }
            return valid;
        }
    </script>
    @endpush
@endsection
