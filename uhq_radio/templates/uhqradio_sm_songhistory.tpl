<{if $shistory}>

<h4><{$smarty.const._MD_UHQRADIO_SHIST_HDR1}><{$shistory.count}><{$smarty.const._MD_UHQRADIO_SHIST_HDR2}></h4>
<div class="some_div">
    <table>
        <tr>
            <th width="50px">
            <th><{$smarty.const._MD_UHQRADIO_SHIST_ARTIST}>/<{$smarty.const._MD_UHQRADIO_SHIST_TITLE}></th>
            <th><{$smarty.const._MD_UHQRADIO_SHIST_ALBUM}>/<{$smarty.const._MD_UHQRADIO_SHIST_YEAR}></th>
            <th>Req</th>
            <th>Date/Time</th>
        </tr>
        <{foreach item=list from=$shistory.history}>
        <tr class="<{cycle values=" odd,even
        "}>">
        <td><{if $list.picture}><img src="<{$data.baseurl}>/<{$list.djid}>/<{$list.picture}>" style="max-width:40px; max-height:40px" title="<{$list.album}> (<{$list.albumyear}>)"><{/if}></td>
        <td><{if $list.artist}><{$list.artist}><{else}>-<{/if}><br/><{if $list.track}><{$list.track}><{else}>-<{/if}></td>
        <td><{$list.album}><br/><{$list.albumyear}></td>
        <td nowrap>
            <{if $list.requestID >0}>
            <{$smarty.const._MD_UHQRADIO_SHIST_REQ}>
            <{if $list.requestor}><{$smarty.const._MD_UHQRADIO_SHIST_BY}><br/><{$list.requestor}><{else}>!<{/if}>
            <{/if}>
        </td>
        <td><{$list.stamp}></td>
        </tr>
        <{/foreach}>
    </table>
</div>

<{else}>

<h4><{$smarty.const._MD_UHQRADIO_SHIST_NA}></h4>

<{/if}>
