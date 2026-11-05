@extends('template')

@section('content')
<div class="row" style="width:98%; margin:auto;">
    <h5 class="text-center center" style="text-align: center; margin-bottom: 30px;">
        Notes & Documents for: {{ $inventory->item_name }}
    </h5>

    @if (session('success'))
        <div class="card-panel teal lighten-2" style="margin-bottom: 20px;">
            <span class="white-text">{{ session('success') }}</span>
        </div>
    @endif

    <div style="margin-bottom: 20px;">
        <a href="{{ route('notes.create', ['inventory_id' => $inventory->id]) }}" class="btn waves-effect waves-light dark-purple">
            <i class="material-icons left">add</i>Add Note for This Item
        </a>
        <a href="{{ route('item', $inventory->id) }}" class="btn waves-effect waves-light grey">
            <i class="material-icons left">arrow_back</i>Back to Item
        </a>
    </div>

    @if ($notes->count() > 0)
        <div class="col s12">
            @foreach ($notes as $note)
                <div class="card white" style="margin-bottom: 20px;">
                    <div class="card-content">
                        <h5 class="card-title">{{ $note->title }}</h5>
                        <p>
                            <small class="grey-text">
                                By <strong>{{ $note->user->name }}</strong> on 
                                <strong>{{ $note->created_at->format('M d, Y H:i') }}</strong>
                                @if ($note->updated_at->diffInSeconds($note->created_at) > 60)
                                    (Updated: {{ $note->updated_at->format('M d, Y H:i') }})
                                @endif
                            </small>
                        </p>

                        <!-- Note Preview -->
                        <div style="margin: 15px 0; padding: 15px; background-color: #f9f9f9; border-radius: 4px;">
                            {!! Illuminate\Support\Str::limit(strip_tags($note->content), 300, '...') !!}
                        </div>

                        <!-- Attachments Summary -->
                        @if ($note->attachments->count() > 0)
                            <p style="margin-bottom: 10px;">
                                <strong>Attachments:</strong>
                                <span class="badge">{{ $note->attachments->count() }}</span>
                            </p>
                        @endif
                    </div>

                    <div class="card-action">
                        <a href="{{ route('notes.show', $note) }}" class="blue-text">View Full Note</a>
                        @can('update', $note)
                            | <a href="{{ route('notes.edit', $note) }}" class="orange-text">Edit</a>
                        @endcan
                        @can('delete', $note)
                            | <a href="{{ route('notes.destroy', $note) }}" class="red-text" onclick="return confirm('Delete this note?');">Delete</a>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card-panel grey lighten-4">
            <span class="grey-text">No notes found for this item. <a href="{{ route('notes.create', ['inventory_id' => $inventory->id]) }}">Create a new note</a></span>
        </div>
    @endif
</div>

<style>
    .badge {
        background-color: #673AB7;
        color: white;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 12px;
    }
</style>
@endsection
