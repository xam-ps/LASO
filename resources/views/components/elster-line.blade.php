@props(['elster', 'lineKey'])

@php($line = $elster?->line($lineKey))

@if ($line === null)
<td class="text-gray-400 dark:text-gray-500" title="Für dieses Jahr ist keine Zeilennummer hinterlegt.">
    <span aria-hidden="true">&ndash;</span>
    <span class="sr-only">Keine Zeilennummer hinterlegt</span>
</td>
@elseif ($elster->confirmed)
<td title="Zeile {{ $line }} der Anlage EÜR {{ $elster->formYear }}">{{ $line }}</td>
@else
<td class="font-semibold text-red-700 dark:text-red-400 print:text-black"
    title="Zeile {{ $line }} der Anlage EÜR {{ $elster->formYear }} &ndash; noch nicht gegen den amtlichen Vordruck geprüft.">
    {{ $line }}</td>
@endif