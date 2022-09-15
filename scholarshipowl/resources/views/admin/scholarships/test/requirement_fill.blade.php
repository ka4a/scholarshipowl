@if ($requirement instanceof \App\Entity\RequirementText)
    <pre>{{ $requirement->getTitle() }}</pre>
    <pre>{{$requirement->getDescription()}}</pre>
    {{ Form::textarea("requirement_text[{$requirement->getId()}]", null, ['class' => 'form-control']) }}
    <p><b>Min Words:</b> {{ $requirement->getMinWords() }} | <b>Max Words:</b> {{ $requirement->getMaxWords() }}</p>
    <p><b>Min Characters:</b> {{ $requirement->getMinCharacters() }} | <b>Max Characters:</b> {{ $requirement->getMaxCharacters() }}</p>
    <p><b>Send Type:</b> {{ strtoupper($requirement->getSendType()) }}</p>
    <p>
        <b>Attachment Type:</b> {{ strtoupper($requirement->getAttachmentType()) }} |
        <b>Attachment Format:</b>{{ strtoupper($requirement->getAttachmentFormat()) }}
    </p>

    @if ($requirement->getAllowFile())
        <p><b>File extension:</b> {{$requirement->getFileExtension() }} | <b>Max file size:</b> {{ $requirement->getMaxFileSize() }}</p>
        {{ Form::file("requirement_text_file_{$requirement->getId()}") }}
    @endif
@elseif ($requirement instanceof \App\Entity\RequirementFile)
    <pre>{{ $requirement->getTitle() }}</pre>
    <pre>{{$requirement->getDescription()}}</pre>
    <p><b>File extension:</b> {{$requirement->getFileExtension() }} | <b>Max file size:</b> {{ $requirement->getMaxFileSize() }}</p>
    {{ Form::file("requirement_file_{$requirement->getId()}") }}
@elseif ($requirement instanceof \App\Entity\RequirementImage)
    <pre>{{ $requirement->getTitle() }}</pre>
    <pre>{{$requirement->getDescription()}}</pre>
    <p><b>Min width:</b> {{ $requirement->getMinWidth() }} | <b>Max width:</b> {{ $requirement->getMaxWidth() }}</p>
    <p><b>Min height:</b> {{ $requirement->getMinHeight() }} | <b>Max height:</b> {{ $requirement->getMaxHeight() }}</p>
    <p><b>File extension:</b> {{$requirement->getFileExtension() }} | <b>Max file size:</b> {{ $requirement->getMaxFileSize() }}</p>
    {{ Form::file("requirement_images_{$requirement->getId()}") }}
@elseif ($requirement instanceof \App\Entity\RequirementInput)
    <pre>{{ $requirement->getTitle() }}</pre>
    <pre>{{$requirement->getDescription()}}</pre>
    {{ Form::textarea("requirement_input[{$requirement->getId()}]", null, ['class' => 'form-control']) }}
@elseif ($requirement instanceof \App\Entity\RequirementSpecialEligibility)
    <pre>{{ $requirement->getTitle() }}</pre>
    <pre>{{$requirement->getDescription()}}</pre>
    <pre>{{ Form::checkbox("requirement_special_eligibility[{$requirement->getId()}]", null, ['class' => 'form-control']) }} {{$requirement->getText()}}</pre>

@elseif ($requirement instanceof \App\Entity\RequirementSurvey)
    <pre>{{$requirement->getTitle() }}</pre>
    <pre>{{$requirement->getDescription()}}</pre>

    @foreach($requirement->getSurveyWithId() as $key => $survey )
        <p><b>{{$survey['question']}}:</b></p>
        @foreach($survey['options'] as $op)
            @php
                $id = $survey['id'];
                $reqId = $requirement->getId();
            @endphp
            <p>
                @if($survey['type'] == \App\Entity\RequirementSurvey::SURVEY_TYPE_CHECKBOX )
                    {{ Form::checkbox("requirement_survey[$reqId][$id][]", $op ) }}
                @else
                    {{ Form::radio("requirement_survey[$reqId][$id][]", $op) }}
                @endif
                {{ $op }}
            </p>
        @endforeach
    @endforeach

@else
    <div class="alert alert-danger">Unknown requirement type!</div>
@endif
