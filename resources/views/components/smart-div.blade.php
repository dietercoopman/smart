@php
    $compiler = app(\Dietercoopman\Smart\Smart::class);

    echo $compiler->parse('<div smart src=\''.$attributes['data-background'].'\' '.$attributes->except('style')->merge().' />');

@endphp

