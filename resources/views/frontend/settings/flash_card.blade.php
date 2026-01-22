@extends('layouts.frontend')
@section('title', $page_title)
@section('content')
@section('css')
    <style>
        #flash_card {
            cursor: pointer;
        }

        .per {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            /* Add this to enable centering */
            justify-content: center;
            /* Center horizontally */
            align-items: center;
            /* Center vertically */
        }

        .circle strong {
            color: white;
            font-size: 32px;
        }

        .circle[data-color="red"] {
            background-color: red;
        }

        .circle[data-color="blue"] {
            background-color: blue;
        }

        .circle[data-color="green"] {
            background-color: green;
        }

        .circle[data-color="yellow"] {
            background-color: orange;
        }

        .circle[data-color="purple"] {
            background-color: purple;
        }

        .circle.selected {
            background-color: rgb(183, 248, 98);
        }
    </style>
    <link rel="stylesheet" href="{{ asset('frontend/css/notes.css') }}">
@endsection
@include('frontend.includes.bradcaump')

<section class="htc__profile__area bg__white ptb--80">
    <div class="container">
        <div class="row d-flex align-items-stretch">
            <div class="card py-5">
                <div class="card-body text-center">
                    @if ($question)
                        <div id="flash_card" class="my-5 py-5" onclick="showAnswer()">
                            <h2 class="card-title question">{{ $question->question }}</h2>
                            <div class="answer" style="display: none">
                                <h2 class="card-title">{{ $question->answer }}</h2>

                                <div class="per mt-5 d-flex justify-content-center">
                                    <div class="circle" data-color="red"><strong>1</strong></div>
                                    <div class="circle" data-color="blue"><strong>2</strong></div>
                                    <div class="circle" data-color="green"><strong>3</strong></div>
                                    <div class="circle" data-color="yellow"><strong>4</strong></div>
                                    <div class="circle" data-color="purple"><strong>5</strong></div>
                                </div>
                                <div class="mt-5">
                                    <a class="btn btn-primary next-btn" id="nextQuestionBtn"
                                        href="{{ route('flash.card.question', ['chapter' => $chapter, 'lesson' => $lesson ?? '']) }}">Next
                                        Question</a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="my-5 py-5">
                            <h1> Flash Card Done </h1>
                            <a href="{{ route('flash.card') }}" class="btn btn-success mt-5 px-5">
                                <span class="h2 text-white">Back</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@push('scripts')
    <script>
        function showAnswer() {
            const card = document.getElementById('flash_card');
            const question = card.querySelector('.question');
            const answer = card.querySelector('.answer');

            if (answer.style.display === 'none') {
                question.style.display = 'none';
                answer.style.display = 'block';
            }
        }

        const circles = document.querySelectorAll('.circle');
        const nextQuestionBtn = document.querySelector('.next-btn');


        circles.forEach(circle => {
            circle.addEventListener('click', () => {
                circles.forEach(c => {
                    c.classList.remove('selected');
                });
                circle.classList.add('selected');
                nextQuestionBtn.classList.remove('disabled');
            });
        });

        nextQuestionBtn.classList.add('disabled');
    </script>
@endpush
@endsection
