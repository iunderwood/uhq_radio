<{if $error}>

<font color=red><{$smarty.const._AM_UHQRADIO_SQLERR}></font>: <{$error}>

<{elseif $data}>

<hr>
<table>
    <tr>
        <th><{$smarty.const._AM_UHQRADIO_LIST_SVR}></th>
        <th><{$smarty.const._AM_UHQRADIO_LIST_PORT}></th>
        <th><{$smarty.const._AM_UHQRADIO_LIST_MOUNT}></th>
        <th><{$smarty.const._AM_UHQRADIO_LIST_STYPE}></th>
        <th><{$smarty.const._AM_UHQRADIO_LIST_MAXL}></th>
        <th><{$smarty.const._AM_UHQRADIO_LIST_FLAGS}></th>
        <th><{$smarty.const._AM_UHQRADIO_LIST_ACT}></th>
    </tr>
    <{foreach item=list from=$data}>
    <tr class="<{cycle values=" odd,even
    "}>">
    <td><{$list.server}></td>
    <td><{$list.port}></td>
    <td><{$list.mount}></td>
    <td>
        <{if $list.type == "I"}><{$smarty.const._AM_UHQRADIO_STYPE_I}>
        <{elseif $list.type == "S"}><{$smarty.const._AM_UHQRADIO_STYPE_S}>
        <{elseif $list.type == "P"}><{$smarty.const._AM_UHQRADIO_STYPE_P}>
        <{/if}>
    </td>
    <td><{$list.listeners}></td>
    <td>
        Text <{if $list.flag_text == 1}><font color=green>Ok</font><{else}><font color=red>Not used</font><{/if}> ::
        Count <{if $list.flag_count == 1}><font color=green>Ok</font><{else}><font color=red>Not used</font><{/if}>
    </td>
    <td>
        <a href='mountpoints.php?op=edit&mpid=<{$list.mpid}>'><{$smarty.const._AM_UHQRADIO_LIST_ACT_EDIT}></a> -
        <a href='mountpoints.php?op=delete&mpid=<{$list.mpid}>'><{$smarty.const._AM_UHQRADIO_LIST_ACT_DEL}></a>
    </td>
    </tr>
    <{/foreach}>
</table>

<{/if}>

<hr><a href='mountpoints.php?op=insert'><{$smarty.const._AM_UHQRADIO_ADDMOUNT}></a>
