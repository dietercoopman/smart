<div>
    @php
        $compiler = app(\Dietercoopman\Smart\Smart::class);
        echo $compiler->parse('<img src="'.$src.'" smart '.$attributes->merge().'>');
    @endphp
</div>
