@extends('template')

@section('content')
<div class="row" style="width:98%; margin:auto;">
    <h5 class="text-center center" style="text-align: center; margin-bottom: 30px;">Notes & Documents</h5>
    
    @if (session('success'))
        <div class="card-panel teal lighten-2" style="margin-bottom: 20px;">
            <span class="white-text">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="card-panel red lighten-2" style="margin-bottom: 20px;">
            <span class="white-text">{{ session('error') }}</span>
        </div>
    @endif

    <div style="margin-bottom: 20px;">
        <a href="{{ route('notes.create') }}" class="btn waves-effect waves-light dark-purple">
            <i class="material-icons left">add</i>New Note
        </a>
    </div>

    @if ($notes->count() > 0)
        <div class="col s12">
            <table class="display responsive-table" style="width:100%;">
                <thead class="thead-dark">
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Item</th>
                        <th>Created</th>
                        <th>Attachments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($notes as $note)
                        <tr>
                            <td><strong>{{ $note->title }}</strong></td>
                            <td>{{ $note->user->name }}</td>
                            <td>
                                @if ($note->inventory)
                                    <a href="{{ route('item', $note->inventory->id) }}">
                                        {{ $note->inventory->item_name }}
                                    </a>
                                @else
                                    <span class="grey-text">-</span>
                                @endif
                            </td>
                            <td>{{ $note->created_at->format('M d, Y') }}</td>
                            <td>
                                @if ($note->attachments->count() > 0)
                                    <span class="badge">{{ $note->attachments->count() }}</span>
                                @else
                                    <span class="grey-text">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="fixed-action-btn horizontal direction-top direction-left click-to-toggle" style="position: relative !important; float: text-align: center; display: inline-block; bottom: 0px !important; padding: 0px !important">
                                    <a class="btn-floating btn-small dark-purple waves-effect waves-light" style="display: inline-block">
                                        <i class="small material-icons">menu</i>
                                    </a>
                                    <ul style="top: 0px !important">
                                        <li>
                                            <a href="{{ route('notes.show', $note) }}" class="btn-floating btn-small waves-effect blue waves-light tooltipped" data-position="top" data-tooltip="View Note">
                                                <i class="material-icons">remove_red_eye</i>
                                            </a>
                                        </li>
                                        @can('update', $note)
                                            <li>
                                                <a href="{{ route('notes.edit', $note) }}" class="btn-floating btn-small waves-effect orange waves-light tooltipped" data-position="top" data-tooltip="Edit Note">
                                                    <i class="material-icons">edit</i>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('delete', $note)
                                            <li>
                                                <form method="POST" action="{{ route('notes.destroy', $note) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-floating btn-small waves-effect red waves-light tooltipped" data-position="top" data-tooltip="Delete Note" onclick="return confirm('Are you sure?')">
                                                        <i class="material-icons">delete</i>
                                                    </button>
                                                </form>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="col s12" style="margin-top: 20px; text-align: center;">
            {{ $notes->links() }}
        </div>
    @else
        <div class="card-panel grey lighten-4">
            <span class="grey-text">No notes found. <a href="{{ route('notes.create') }}">Create your first note</a></span>
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
