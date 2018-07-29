@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-header">Levels</div>

                    <div class="card-body">
                        These Level Sets include a great variety of levels. Some may have levels that are extremely
                        difficult, some may finish themselves with no user interaction, and some may have dozens of
                        rings on a single level.
                    </div>
                </div>

                <table class="table table-striped table-bordered table-hover mt-3">
                    <thead class="thead-light">
                    <tr>
                        <th>Name</th>
                        <th>Levels</th>
                        <th class="text-nowrap">Date posted</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($levelSets as $levelSet)
                        <tr>
                            <td>
                                @if ($levelSet->isDesignedForInfinity())
                                    <img src="{{ asset('images/RI.gif') }}"
                                         alt="Can only be played in Ricochet Infinity"
                                         title="Can only be played in Ricochet Infinity"
                                         width="32"
                                         height="32"
                                         class="float-right">
                                @else
                                    <img src="{{ asset('images/RLW.gif') }}"
                                         alt="Can be played in Ricochet Lost Worlds, Ricochet Recharged, and Ricochet Infinity"
                                         title="Can be played in Ricochet Lost Worlds, Ricochet Recharged, and Ricochet Infinity"
                                         width="32"
                                         height="32"
                                         class="float-right">
                                @endif

                                <p class="m-0"><a href="" class="font-weight-bold">{{ $levelSet->name }}</a></p>
                                <p class="m-0">by <a href="">{{ $levelSet->author }}</a></p>
                                <p class="m-0">{{ $levelSet->description }}</p>

                                @if ($levelSet->tagged)
                                    <p class="m-0">
                                        <strong>Tags:</strong>
                                        @foreach ($levelSet->tagged as $tagged)
                                            <a href=""
                                               title="Find other levelsets with the {{ $tagged->tag_name }} tag"
                                            >{{ $tagged->tag_name }}</a>{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </p>
                                @endif
                            </td>
                            <td class="text-center">{{ $levelSet->rounds }}</td>
                            <td class="text-center text-nowrap">{{ $levelSet->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $levelSets->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
