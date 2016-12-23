@extends('VersionLog.layout')
@section('content')



    @if($result['items'] === "")

        <div class="versionlog">
            <div class="top">
                <p class="date">Empty</p>
                <p class="circle">Empty</p>
            </div>

            <div class="content">
                <h3>No entries yet!</h3>
            </div>

            <p class="bottom">#empty</p>
        </div>
    @else

        @foreach($result['items'] as $item)
            <div class="versionlog {{ date('Y-m-d', strtotime($item['publish_start'])) == date('Y-m-d', strtotime($currentDate)) ? 'blue' : '' }}">
                <div class="top">

                    <p class="date">@if($item['date'] == '12.12.70')Draft @else {{$item['date']}}@endif</p>
                    @if(isset($item['userstring']))<p class="circle">{{$item['userstring']}}</p>@endif
                </div>

                <div class="content">
                    <h3>{{$item['title']}}</h3>
                    <p>{{$item['content']}}</p>
                </div>

                <div class="bottom">
                    <p>#{{$item['product_type']}}</p>
                    @if (\Illuminate\Support\Facades\Auth::check())
                        @if (Request::path() !== 'tv')
                        <div class="bottombtn">
                        <a href="#"
                           class="editbtn"
                           data-toggle="modal"
                           data-target="#favoritesModal">
                            Edit
                        </a>


                        <a href="#"
                           class="deletebtn"
                           onclick="event.preventDefault();
                            document.getElementById('delete-form').submit();">
                            Delete

                        <form action="/versionlog/{{$item['id']}}" method="post" id="delete-form">
                            <input type="hidden" name="_method" value="delete" />
                            {{ csrf_field() }}
                        </form>
                        </a>
                        <div class="idfield" data-field-id="{{$item['id']}}" ></div>
                    @endif
                            @endif
                        </div>
                </div>

            </div>
        @endforeach
        <div id="field" data-field-id="{{$dataString}}" ></div>
    @endif
@stop


