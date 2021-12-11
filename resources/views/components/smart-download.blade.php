@php
    $compiler = app(\Dietercoopman\Smart\Smart::class);
    $compiled = $compiler->parse('<a href='.$src.'" smart '.$attributes->merge().'></a>');

    if(!$slot->isEmpty()){
        $compiled = str_replace('__slot__',$slot,$compiled);
    }else{
        $compiled = str_replace('__slot__',config('smart.download.default-text'),$compiled);
    }

    echo $compiled;
@endphp


