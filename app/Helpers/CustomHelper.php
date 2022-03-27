<?php

function requestSuccess($message='',$description='',$redirectTo='closeAndModalHide',$timer=null,$call='',$buttonShow=false){
    return (object)[
        'buttonShow' => $buttonShow,
        'timer' => $timer,
        'message' => $message,
        'description' => $description,
        'redirectTo' => $redirectTo,
        'call' => $call
    ];
}
