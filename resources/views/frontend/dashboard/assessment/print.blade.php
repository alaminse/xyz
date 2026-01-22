<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @page {
            margin: 15mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            position: relative;
            margin: 0;
            padding: 0;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }

        /* Watermark style */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 60px;
            font-weight: bold;
            color: rgba(150, 150, 150, 0.15);
            z-index: -1;
            text-align: center;
            white-space: nowrap;
        }

        /* Header Section */
        .header-container {
            width: 100%;
            border: 2px solid #4A90E2;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background: #7300ffff;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .logo-section {
            flex: 1;
            min-width: 150px;
        }

        .logo-section img {
            height: 50px;
            max-width: 100%;
        }

        .contact-section {
            flex: 1;
            text-align: right;
            min-width: 200px;
        }

        .contact-section p {
            margin: 2px 0;
            font-size: 12px;
            line-height: 1.4;
        }

        .contact-section a,strong {
            color: #ffff;
            text-decoration: none;
        }

        /* Main Content */
        .content {
            z-index: 1;
            position: relative;
        }

        .assessment-title {
            background: linear-gradient(135deg, #4A90E2 0%, #357ABD 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(74, 144, 226, 0.3);
        }

        .assessment-title h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        /* Summary Cards */
        .summary-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin: 20px 0;
        }

        .summary-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 5px 20px;
            display: table;
            width: 100%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: transform 0.2s;
            margin-bottom: 5px;
        }

        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .summary-card .label {
            font-size: 16px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 600;
            white-space: nowrap;
            display: table-cell;
            text-align: left;
            width: 1%;
        }

        .summary-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #4A90E2;
            white-space: nowrap;
            display: table-cell;
            text-align: right;
        }

        /* Questions Section */
        .questions-container {
            margin-top: 30px;
        }

        .question-block {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .question-header {
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            border-left: 4px solid #4A90E2;
        }

        .question-text {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        /* Options Grid */
        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
            margin: 15px 0;
        }

        .option-box {
            padding: 12px 10px;
            text-align: center;
            border: 2px solid #dee2e6;
            border-radius: 6px;
            background: white;
            font-size: 13px;
            transition: all 0.2s;
            word-wrap: break-word;
            margin-bottom: 5px;
        }

        .option-box.correct {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-color: #28a745;
            color: #155724;
            font-weight: 600;
        }

        .option-box.incorrect {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-color: #dc3545;
            color: #721c24;
            font-weight: 600;
        }

        /* Explanation Section */
        .explanation-section {
            background: #f8f9fa;
            border-left: 4px solid #4A90E2;
            padding: 15px;
            margin-top: 15px;
            border-radius: 4px;
        }

        .explanation-section h3 {
            color: #4A90E2;
            margin-top: 0;
            font-size: 16px;
        }

        .explanation-section ul {
            margin: 10px 0;
            padding-left: 20px;
        }

        .explanation-section li {
            margin: 8px 0;
            line-height: 1.6;
        }

        .explanation-section h4,
        .explanation-section h5 {
            margin-top: 15px;
            margin-bottom: 8px;
        }

        /* Mobile Responsive */
        @media screen and (max-width: 768px) {
            body {
                font-size: 13px;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .contact-section {
                text-align: center;
                margin-top: 10px;
            }

            .summary-card .label {
                font-size: 14px;
            }

            .summary-card .value {
                font-size: 20px;
            }

            .options-grid {
                grid-template-columns: 1fr;
            }

            .assessment-title h2 {
                font-size: 18px;
            }

            .question-text {
                font-size: 15px;
            }
        }

        @media screen and (max-width: 480px) {
            .summary-card .label {
                font-size: 13px;
            }

            .summary-card .value {
                font-size: 18px;
            }
        }

        /* Print Styles */
        @media print {
            .question-block {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">
        {{ env('APP_NAME') }}
    </div>

    @php
        $base64 = base64_encode(file_get_contents(public_path('uploads/logo/logo.png')));
        $contact = contact();
    @endphp

    <!-- Header -->
    <div class="header-container">
        <div class="header-content">
            <div class="logo-section">
                <img src="data:image/png;base64,{{ $base64 }}" alt="Logo">
            </div>
            <div class="contact-section">
                <p><strong>{{ $contact['address'] ?? '' }}</strong></p>
                <p><a href="tel:{{ $contact['phone'] ?? '' }}">{{ $contact['phone'] ?? '' }}</a></p>
                <p><a href="mailto:{{ $contact['email'] ?? '' }}">{{ $contact['email'] ?? '' }}</a></p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <!-- Assessment Title -->
        <div class="assessment-title">
            <h2 style="color: black;"></h2>
        </div>

        <!-- Summary Cards -->
        <div class="summary-container">
            <h2 style="color: black;">{{ $data['name'] }}</h2>
            <div class="summary-card">
                <div class="label">Name</div>
                <div class="value" style="font-size: 18px;">{{ $data['student_name'] }}</div>
            </div>
            <div class="summary-card">
                <div class="label">Total Marks</div>
                <div class="value">{{ $data['total_marks'] }}</div>
            </div>
            <div class="summary-card">
                <div class="label">Achieved</div>
                <div class="value" style="color: #28a745;">{{ $data['achive_marks'] }}</div>
            </div>
        </div>

        <!-- Questions Container -->
        <div class="questions-container">
            @foreach ($data['details'] as $index => $question)
                <div class="question-block">
                    <div class="question-header">
                        <p class="question-text">Q{{ $index + 1 }}. {!! $question['question'] !!}</p>
                    </div>

                    @if ($question['question_type'] == 'sba')
                        @php
                            $correctOption = $question['options']['correct_option'];
                            $userAnswer = $question['options']['user_option'] ?? null;
                            $optionLabels = ['A', 'B', 'C', 'D', 'E'];
                        @endphp

                        <div class="options-grid">
                            @for ($i = 1; $i <= 5; $i++)
                                @php
                                    $optionKey = 'option' . $i;
                                    $optionValue = $question['options'][$optionKey] ?? null;

                                    $class = '';
                                    if ($userAnswer === $optionKey && $userAnswer === $correctOption) {
                                        $class = 'correct';
                                    } elseif ($userAnswer === $optionKey && $userAnswer != $correctOption) {
                                        $class = 'incorrect';
                                    } elseif ($correctOption === $optionKey) {
                                        $class = 'correct';
                                    } else {
                                    }
                                @endphp

                                @if ($optionValue)
                                    <div class="option-box {{ $class }}">
                                        <strong>{{ $optionLabels[$i-1] }}.</strong> {{ $optionValue }}
                                    </div>
                                @endif
                            @endfor
                        </div>

                    @elseif ($question['question_type'] == 'mcq')
                        @php
                            $optionLabels = ['A', 'B', 'C', 'D', 'E'];
                        @endphp

                        <div class="options-grid">
                            @for ($i = 1; $i <= 5; $i++)
                                @php
                                    $optionKey = 'option' . $i;
                                    $userOptionKey = 'user_option' . $i;
                                    $answerOption = 'answers' . $i;
                                    $optionValue = $question['options'][$optionKey] ?? null;
                                    $userOptionValue = $question['options'][$userOptionKey] ?? null;
                                    $answerOptionValue = $question['options'][$answerOption] ?? null;

                                    $class = '';

                                    if($userOptionValue !== null && $answerOptionValue !== null){
                                        $userOptionValue = $userOptionValue == 'false' ? 0 : 1;
                                        if ($userOptionValue == $answerOptionValue) {
                                            $class = 'correct';
                                            $indicator = ' ✓';
                                        } else {
                                            $class = 'incorrect';
                                            $indicator = ' ✗';
                                        }
                                    } elseif ($answerOptionValue == 1) {
                                        $class = 'correct';
                                    }
                                @endphp

                                @if ($optionValue)
                                    <div class="option-box {{ $class }}">
                                        <strong>{{ $optionLabels[$i-1] }}.</strong> {{ $optionValue }}
                                    </div>
                                @endif
                            @endfor
                        </div>
                    @endif

                    @if (!empty($question['explanation']))
                        <div class="explanation-section">
                            <h3><u>Explanation</u></h3>
                            {!! $question['explanation'] !!}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
