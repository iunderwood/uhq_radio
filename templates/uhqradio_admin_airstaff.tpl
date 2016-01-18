<hr>
<table>
    <tr>
        <th><{$smarty.const._AM_UHQRADIO_LIST_DJID}></th>
        <th><{$smarty.const._AM_UHQRADIO_LIST_USER}></th>
        <th><{$smarty.const._AM_UHQRADIO_LIST_ACT}></th>
    </tr>
    <{foreach item=list from=$data}>
    <tr class="<{cycle values=" odd,even
    "}>">
    <td><{$list.djid}></td>
    <td><{$list.djname}></td>
    <td>
        <a href='airstaff.php?op=edit&djid=<{$list.djid}>'><{$smarty.const._AM_UHQRADIO_LIST_ACT_EDIT}></a> -
        <a href='airstaff.php?op=delete&djid=<{$list.djid}>'><{$smarty.const._AM_UHQRADIO_LIST_ACT_DEL}></a>
    </td>
    <{$i++}>
    </tr>
    <{/foreach}>
</table>
<hr><a href='airstaff.php?op=insert'><{$smarty.const._AM_UHQRADIO_ADDAIRSTAFF}></a>
