@extends('layouts.app')

@section('content')

    <div class="text-center">
        {!! link_to_route('contents.create','画像をアップロードする') !!}
    </div>
    
    <ul>
        
        @foreach($contents as $content)
        
        <?php
        $user = App\User::find($content->user_id);
        
        $userdetail = App\Userdetail::find($content->user_id);
        if(isset($userdetail->profileImg)){
            if (\Auth::id() === $user->id) {
            $userdetail->profileImg = App\Userdetail::latest('updated_at')->where('user_id',$user->id)->value('profileImg');
            }
        }
        
        ?>
        <li>
            <div class="well col-md-6 col-md-offset-3">
                
                @if(isset($userdetail->profileImg))
                <div class="content-top">
                    <p><img src="/storage/{{ $userdetail->profileImg }}" alt="" width="30px">{!! $user->name !!}</p>
                    <div class="dropdown CSS-right">
	                    <a data-toggle="dropdown" role="button" aria-expanded="false">
	                        <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
	                    </a>
                        	<ul class="dropdown-menu" role="menu">
                        	    @if(Auth::user()->id == $content->user_id)
                        	    <li role="presentation">{!! link_to_route('contents.edit','編集',['id' => $content->id]) !!}</li>
                        	    <li role="presentation">
                        	        {!! Form::model($content, ['route' => ['contents.destroy',$content->id], 'method' => 'delete']) !!}
                                        {!! Form::submit('削除',['style' => 'background-color: transparent;border: none;padding:0;']) !!}
                                    {!! Form::close() !!}
                        	    </li>
                        	    @endif
		                        <li role="presentation">{!! link_to_route('contents.show','詳細を見る',['id' => $content->id]) !!}</li>
	                        </ul>
                    </div>
                </div>
                @else
                <div class="content-top">
                    <p>{!! $user->name !!}</p>
                    <div class="dropdown CSS-right">
	                    <a data-toggle="dropdown" role="button" aria-expanded="false">
	                        <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
	                    </a>
                        	<ul class="dropdown-menu" role="menu">
                        	    @if(Auth::user()->id == $content->user_id)
                        	    <li role="presentation">{!! link_to_route('contents.edit','編集',['id' => $content->id]) !!}</li>
                        	    <li role="presentation">
                        	        {!! Form::model($content, ['route' => ['contents.destroy',$content->id], 'method' => 'delete']) !!}
                                        {!! Form::submit('削除',['style' => 'background-color: transparent;border: none;padding:0;']) !!}
                                    {!! Form::close() !!}
                        	    </li>
                        	    @endif
		                        <li role="presentation">{!! link_to_route('contents.show','詳細を見る',['id' => $content->id]) !!}</li>
	                        </ul>
                    </div>
                </div>
                @endif
                
                <div>
                    <img src="/storage/{{ $content->toShareImg }}" alt="" width="400px">
                </div>
                
                <div>
                    <p>{!! $user->name !!}：{!! nl2br(e($content->caption)) !!}</p>
                    <div>
                        @if(isset($content->tag))
                        {!! link_to_route('contents.indexOfSearch', '#' . $content->tag ,['tag' => $content->tag]) !!}
                        @endif
                    </div>
                    <?php   //時間の差分を求める
                    $postedAt = new \Carbon\Carbon($content->created_at);
                    $now = \Carbon\Carbon::now();
                    $secondsSincePosted = $postedAt->diffInSeconds($now);
                    if($secondsSincePosted > 59){
                        $minutesSincePosted = $postedAt->diffInMinutes($now);
                        if($minutesSincePosted > 59){
                            $hoursSincePosted = $postedAt->diffInHours($now);
                            if($hoursSincePosted > 23){
                                $daysSincePosted = $postedAt->diffInDays($now);
                                if($daysSincePosted > 6){
                                    $yearsSincePosted = $postedAt->diffInYears($now);
                                    if($yearsSincePosted > 0){
                                        echo $postedAt->format("Y年n月j日");
                                    }else{
                                        echo $postedAt->format("n月j日");
                                    }
                                }else{
                                    echo $daysSincePosted.'日前';
                                }
                            }else{
                                echo $hoursSincePosted.'時間前';
                            }
                        }else{
                            echo $minutesSincePosted.'分前';
                        }
                    }else{
                        echo $secondsSincePosted.'秒前';
                    }
                    ?>
                </div>
            </div>
        </li>
        
        @endforeach
        
    </ul>
    
@endsection