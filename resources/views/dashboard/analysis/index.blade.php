@extends('layouts.app')
@section('title', 'Analysis')
@section('content')

<div class="wrapper">
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="st_title"><i class="uil uil-analysis"></i> Analyics</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive mt-30">
                        <table class="table ucp-table">
                            <thead class="thead-s">
                                <tr>
                                    <th class="text-center" scope="col">No.</th>
                                    <th class="cell-ta" scope="col">Thumbnail</th>
                                    <th class="cell-ta" scope="col">Title</th>
                                    <th class="text-center" scope="col">Purchases</th>
                                    <th class="text-center" scope="col">Enrolled</th>
                                    <th class="text-center" scope="col">Likes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courses as $row)
                                @php
                                    $transactionCount = \App\Models\Transaction::where(['courseid' => $row->id, 'status' =>  'success'])->count();
                                    $enrolledCount = \App\Models\Enrolled::where(['courseid' => $row->id])->count();
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="cell-ta">
                                        <div class="thumb_img"><img style="width:80px;height:50px;object-fit:cover;" src="{{ asset($row->media_thumbnail) }}" alt="{{ $row->title }}" /></div>
                                    </td>
                                    <td class="cell-ta">{{ $row->title }}</td>
                                    <td class="text-center">{{ $transactionCount }}</td>
                                    <td class="text-center">{{ $enrolledCount }}</td>
                                    <td class="text-center">{{ $row->likes }}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.footer')
</div>

@endsection
