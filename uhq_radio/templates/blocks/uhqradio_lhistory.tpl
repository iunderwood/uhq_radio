<{if $block.error}>
<{$block.error}>
<{/if}>
<{if $block.display == "L"}>
<{$smarty.const._MB_UHQRADIO_LHIST_PREFIX}><{$block.size}><{$smarty.const._MB_UHQRADIO_LHIST_SUFFIX}><br/>
<table>
    <tr>
        <th><{$smarty.const._MB_UHQRADIO_LHIST_TS}></th>
        <th><{$smarty.const._MB_UHQRADIO_LHIST_POP}></th>
    </tr>
    <{foreach item=list from=$block.history}>
    <tr>
        <td><{$list.stamp}></td>
        <td><{$list.listeners}></td>
    </tr>
    <{/foreach}>
</table>
<{elseif $block.display == "G"}>
<b>Graphs not yet supported</b>
<{/if}>
<{if $block.summary}>
<p>Low:<{$block.summary.low}> / High: <{$block.summary.high}> / Average <{$block.summary.average}></p>
<{/if}>
