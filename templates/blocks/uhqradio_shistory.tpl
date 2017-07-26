<{if $block.error}>
    <{$block.error}>
<{else}>
    <{$smarty.const._MB_UHQRADIO_SHIST_PREFIX}><{$block.size}><{$smarty.const._MB_UHQRADIO_SHIST_SUFFIX}>
    <br>
    <table>
        <tr>
            <{if $block.extended == 1}>
                <th><{$smarty.const._MB_UHQRADIO_SHIST_TS}></th>
            <{/if}>
            <th><{$smarty.const._MB_UHQRADIO_SHIST_ARTIST}></th>
            <th><{$smarty.const._MB_UHQRADIO_SHIST_TITLE}></th>
            <{if $block.extended == 1}>
                <th><{$smarty.const._MB_UHQRADIO_SHIST_ALBUM}></th>
                <th><{$smarty.const._MB_UHQRADIO_SHIST_YEAR}></th>
            <{/if}>
        </tr>
        <{foreach item=list from=$block.history}>
            <tr><{if $block.extended == 1}>
                    <td nowrap><{$list.stamp}></td>
                <{/if}>
                <td><{$list.artist}></td>
                <td><{$list.track}></td>
                <{if $block.extended == 1}>
                    <td><{$list.album}></td>
                    <td><{$list.albumyear}></td>
                <{/if}>
            </tr>
        <{/foreach}>
    </table>
<{/if}>

