@props(['year', 'years', 'location'])

<div id="year_nav" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    <ul>
        @foreach ($years as $yearN)
        <li><a @class(['active'=> $year == $yearN]) href="{{ route($location.'.year', ['year' => $yearN])
                }}">{{
                $yearN }}</a></li>
        @endforeach
    </ul>
</div>