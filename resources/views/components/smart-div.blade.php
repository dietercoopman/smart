@php
    $compiler = app(\Dietercoopman\Smart\Smart::class);

    $compiled = $compiler->parse('<div smart src=\''.$attributes['data-background'].'\' '.$attributes->except('style')->merge().'></div>');

    if(!$slot->isEmpty()){
        $compiled = str_replace('__slot__',$slot,$compiled);
    }else{
        $compiled = str_replace('__slot__','',$compiled);
    }

    echo $compiled;

@endphp

