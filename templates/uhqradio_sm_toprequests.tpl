<div class="some_div">
    <{if $lastmonth.count}>
    <h4><{$smarty.const._MD_UHQRADIO_TOPREQ_TOP}> <{$lastmonth.count}> <{$smarty.const._MD_UHQRADIO_TOPREQ_LM}></h4>
    <table>
        <tr>
            <th width=1%><{$smarty.const._MD_UHQRADIO_TOPREQ_THREQ}></th>
            <th><{$smarty.const._MD_UHQRADIO_TOPREQ_THSONG}></th>
            <th><{$smarty.const._MD_UHQRADIO_TOPREQ_THALBUM}></th>
        </tr>

        <{foreach item=list from=$lastmonth.request}>
        <tr class="<{cycle values=" odd,even"}>">
        <td><{$list.reqs}></td>
        <td><{$list.artist}> - <{$list.track}></td>
        <td><{$list.album}></td>
        </tr>
        <{/foreach}>
    </table>
    <{else}>
    <h4><{$smarty.const._MD_UHQRADIO_TOPREQ_NOLM}></h4>
    <{/if}>

    <hr>

    <{if $chartall.count}>
    <h4><{$smarty.const._MD_UHQRADIO_TOPREQ_TOP}> <{$chartall.count}> <{$smarty.const._MD_UHQRADIO_TOPREQ_AT}></h4>
    <table>
        <tr>
            <th width=1%><{$smarty.const._MD_UHQRADIO_TOPREQ_THREQ}></th>
            <th><{$smarty.const._MD_UHQRADIO_TOPREQ_THSONG}></th>
            <th><{$smarty.const._MD_UHQRADIO_TOPREQ_THALBUM}></th>
        </tr>
        <{foreach item=list from=$chartall.request}>
        <tr class="<{cycle values=" odd,even"}>">
        <td><{$list.reqs}></td>
        <td><{$list.artist}> - <{$list.track}></td>
        <td><{$list.album}></td>
        </tr>
        <{/foreach}>
    </table>
    <{else}>
    <h4><{$smarty.const._MD_UHQRADIO_TOPREQ_NOAT}></h4>
    <{/if}>
