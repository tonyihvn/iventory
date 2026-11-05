@extends('template')

@section('content')
<div class="row" style="width:98%; margin:auto;">
    <h5 class="text-center center" style="text-align: center; margin-bottom: 30px;">{{ $note->title }}</h5>

    @if (session('success'))
        <div class="card-panel teal lighten-2" style="margin-bottom: 20px;">
            <span class="white-text">{{ session('success') }}</span>
        </div>
    @endif

    <div class="col s12 m10 offset-m1">
        <!-- Note Meta Information -->
        <div class="card white">
            <div class="card-content">
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col s12 m6">
                        <p><strong>Author:</strong> {{ $note->user->name }}</p>
                        <p><strong>Created:</strong> {{ $note->created_at->format('M d, Y H:i') }}</p>
                        @if ($note->updated_at->diffInSeconds($note->created_at) > 60)
                            <p><strong>Last Updated:</strong> {{ $note->updated_at->format('M d, Y H:i') }}</p>
                        @endif
                    </div>
                    <div class="col s12 m6">
                        @if ($note->inventory)
                            <p>
                                <strong>Linked Item:</strong>
                                <a href="{{ route('item', $note->inventory->id) }}">
                                    {{ $note->inventory->item_name }}
                                </a>
                                <br><small class="grey-text">Serial: {{ $note->inventory->serial_no }}</small>
                            </p>
                        @else
                            <p><strong>Type:</strong> <span class="grey-text">General Note</span></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Note Content -->
        <div class="card white">
            <div class="card-content">
                <div id="note-content" style="padding: 20px; line-height: 1.6;">
                    {!! $note->content !!}
                </div>
            </div>
        </div>

        <!-- Attachments -->
        @if ($note->attachments->count() > 0)
            <div class="card white">
                <div class="card-content">
                    <h6 class="card-title">Attachments ({{ $note->attachments->count() }})</h6>
                    <table class="striped" style="width: 100%;">
                        <tbody>
                            @foreach ($note->attachments as $attachment)
                                <tr>
                                    <td>
                                        <i class="material-icons small" style="vertical-align: middle;">
                                            @if (in_array(pathinfo($attachment->file_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                                image
                                            @elseif (in_array(pathinfo($attachment->file_name, PATHINFO_EXTENSION), ['pdf']))
                                                picture_as_pdf
                                            @else
                                                insert_drive_file
                                            @endif
                                        </i>
                                        <span style="margin-left: 10px;">{{ $attachment->file_name }}</span>
                                        <span class="grey-text">({{ round($attachment->file_size / 1024, 2) }} KB)</span>
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="{{ route('notes.attachment.download', [$note, $attachment]) }}" class="btn-small waves-effect waves-light blue">
                                            <i class="material-icons">download</i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="col s12" style="margin-top: 30px; margin-bottom: 20px;">
            <a href="{{ route('notes.index') }}" class="btn waves-effect waves-light grey">
                <i class="material-icons left">arrow_back</i>Back to Notes
            </a>

            @can('update', $note)
                <a href="{{ route('notes.edit', $note) }}" class="btn waves-effect waves-light orange">
                    <i class="material-icons left">edit</i>Edit Note
                </a>
            @endcan

            @can('delete', $note)
                <form method="POST" action="{{ route('notes.destroy', $note) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn waves-effect waves-light red" onclick="return confirm('Are you sure you want to delete this note?')">
                        <i class="material-icons left">delete</i>Delete Note
                    </button>
                </form>
            @endcan
        </div>
    </div>
</div>

<style>
    #note-content img {
        max-width: 100%;
        height: auto;
        margin: 10px 0;
    }

    #note-content table {
        border-collapse: collapse;
        width: 100%;
        margin: 10px 0;
    }

    #note-content table, #note-content th, #note-content td {
        border: 1px solid #ddd;
    }

    #note-content th, #note-content td {
        padding: 8px;
        text-align: left;
    }

    #note-content th {
        background-color: #f2f2f2;
    }

    #note-content ul, #note-content ol {
        margin-left: 20px;
    }

    #note-content blockquote {
        border-left: 4px solid #673AB7;
        padding-left: 15px;
        margin-left: 0;
        font-style: italic;
        color: #666;
    }
</style>
@endsection
